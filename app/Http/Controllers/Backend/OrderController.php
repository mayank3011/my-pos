<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Orderdetails;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class OrderController extends Controller
{
    public function FinalInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_status' => 'required'
        ]);

        // Get authenticated user's cart
        $cart = Cart::with('items.itemable')->where('user_id', auth()->id())->firstOrFail();

        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return ($item->itemable->getPrice() * $item->quantity) / 100;
        });

        $taxRate = config('laravel-cart.tax_rate', 0.18);
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        // Handle discount
        $discountAmount = 0;
        $discountType = null;
        $discountValue = 0;

        if (session('discount')) {
            $discount = session('discount');
            $discountType = $discount['type'];
            $discountValue = $discount['value'];

            $discountAmount = $discount['type'] === 'percentage'
                ? $subtotal * ($discount['value'] / 100)
                : $discount['value'];

            $total -= $discountAmount;
        }


        // Create order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'order_status' => $request->order_status,
            'total_products' => $request->total_products,
            'sub_total' => $subtotal,
            'vat' => $tax,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
            'invoice_no' => 'EPOS' . mt_rand(10000000, 99999999),
            'total' => $total,
            'grand_total' => $total - $discountAmount,
            'payment_status' => $request->payment_status,
            'pay' => floatval(str_replace([',', '₹', '$'], '', $request->pay)),
            'due' => ($total - $discountAmount) - floatval(str_replace([',', '₹', '$'], '', $request->pay)),
            'created_at' => Carbon::now(),
            'category_id' => $request->category_id ?? 1,
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            // Update product stock
            Product::where('id', $item->itemable->id)
                ->decrement('product_store', $item->quantity);

            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $item->itemable->id,
                'quantity' => $item->quantity,
                'unitcost' => $item->itemable->getPrice() / 100,
                'total' => ($item->itemable->getPrice() * $item->quantity) / 100,
                'created_at' => Carbon::now()
            ];
        }

        Orderdetails::insert($orderItems);

        // Generate PDF
        $pdfContent = Pdf::loadView('backend.order.order_invoice', [
            'order' => $order->load(['customer', 'details.product'])
        ])->output();

        Mail::to($order->customer->email)->send(new OrderMail($order, $pdfContent));
        // Clear cart and session data
        $cart->items()->delete();
        session()->forget('discount');

        $notification = [
            'message' => 'Order Completed Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('dashboard')->with($notification);
    }

    public function PendingOrder()
    {
        $orders = Order::where('order_status', 'pending')->get();
        return view('backend.order.pending_order', compact('orders'));
    }

    public function CompleteOrder()
    {
        $orders = Order::where('order_status', 'complete')->get();
        return view('backend.order.complete_order', compact('orders'));
    }

    public function OrderDetails($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orderItem = Orderdetails::with('product')->where('order_id', $order_id)->get();
        return view('backend.order.order_details', compact('order', 'orderItem'));
    }

    public function OrderStatusUpdate(Request $request)
    {
        $order_id = $request->id;
        $orderDetails = Orderdetails::where('order_id', $order_id)->get();

        foreach ($orderDetails as $item) {
            Product::where('id', $item->product_id)
                ->decrement('product_store', $item->quantity);
        }

        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        $notification = [
            'message' => 'Order Completed Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('pending.order')->with($notification);
    }

    public function StockManage()
    {
        $product = Product::latest()->get();
        return view('backend.stock.all_stock', compact('product'));
    }

    public function OrderInvoice($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orderItem = Orderdetails::with('product')->where('order_id', $order_id)->get();

        $pdf = Pdf::loadView('backend.order.order_invoice', compact('order', 'orderItem'))
            ->setPaper('a4')
            ->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
            ]);

        return $pdf->download('invoice-' . $order->invoice_no . '.pdf');
    }
    
   

    public function sendMail($order_id)
    {
        $order = Order::with(['customer', 'details.product'])->findOrFail($order_id);

        // Generate PDF content
        $pdf = PDF::loadView('backend.order.order_invoice', compact('order'));
        $pdfContent = $pdf->output(); // Get the PDF content as string

        // Send mail with PDF attachment
        Mail::to($order->customer->email)->send(new OrderMail($order, $pdfContent));

        $notification = array(
            'message' => 'Order Email Sent Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function Print($order_id)
    {
        $order = Order::findOrFail($order_id);
        $pdf = Pdf::loadView('backend.order.order_invoice', compact('order'))
            ->setPaper('a4')
            ->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
            ]);

        return $pdf->stream('invoice-' . $order->invoice_no . '.pdf');
    }
    public function OrderPrintInvoice($order_id)
    {
        $order = Order::findOrFail($order_id);
        $pdf = Pdf::loadView('backend.order.order_invoice', compact('order'))
            ->setPaper('a4')
            ->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
            ]);

        return $pdf->download('invoice-' . $order->invoice_no . '.pdf');
    }
    public function OrderDelete($order_id)
    {
        $order = Order::with('details')->findOrFail($order_id);

        // Restore stock
        foreach ($order->details as $item) {
            Product::where('id', $item->product_id)
                ->increment('product_store', $item->quantity);
        }

        $order->delete();

        $notification = [
            'message' => 'Order Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
    public function show($orderId)
    {
        $order = Order::with('orderItems.product', 'customer')->findOrFail($orderId);
        return view('backend.orders.show', compact('order'));
    }
}
