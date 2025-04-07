<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Group;
use App\Models\Customer;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Binafy\LaravelCart\LaravelCart;

class PosController extends Controller
{
    public function Pos()
    {
        $product = Product::latest()->get();
        $group = Group::latest()->get();
        $category = Category::latest()->get();
        $customer = Customer::latest()->get();

        // Get or create user's cart
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Calculate cart totals
        $subtotal = $cart->items->sum(function ($item) {
            return ($item->itemable->getPrice() * $item->quantity) / 100;
        });

        $taxRate = config('laravel-cart.tax_rate', 0);
        $tax = $subtotal * $taxRate;
        $discountAmount = 0;

        if (session('discount')) {
            $discount = session('discount');
            $discountAmount = $discount['type'] === 'percentage'
                ? $subtotal * ($discount['value'] / 100)
                : $discount['value'];
        }

        $total = $subtotal + $tax - $discountAmount;

        return view('backend.pos.pos_page', compact(
            'product',
            'customer',
            'category',
            'group',
            'subtotal',
            'tax',
            'discountAmount',
            'total',
            'cart'
        ));
    }

    public function AddCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1'
        ]);

        $product = Product::findOrFail($request->id);

        // Correct parameter order: item, userId, quantity
        LaravelCart::driver('database')->storeItem(
            $product,          // Item object
            auth()->id(),      // User ID
            $request->qty      // Quantity (third parameter)
        );

        $notification = [
            'message' => 'Product Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function AllItem()
    {
        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        return view('backend.pos.text_item', compact('cart'));
    }

    public function CartUpdate(Request $request, $itemId)
    {
        $request->validate(['qty' => 'required|numeric|min:1']);

        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;
        $currentQty = $cartItem->quantity;

        if ($request->qty > $currentQty) {
            $cart->increaseQuantity(
                item: $cartItem->itemable,
                quantity: $request->qty - $currentQty
            );
        } else {
            $cart->decreaseQuantity(
                item: $cartItem->itemable,
                quantity: $currentQty - $request->qty
            );
        }

        $notification = [
            'message' => 'Cart Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function CartRemove($itemId)
{
    $cartItem = CartItem::findOrFail($itemId);

    // Check ownership
    if ($cartItem->cart->user_id !== auth()->id()) {
        abort(403);
    }

    $cartItem->delete();

    $notification = [
        'message' => 'Item Removed Successfully',
        'alert-type' => 'success'
    ];
    return redirect()->back()->with($notification);
}
    public function CreateInvoice(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);

        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        $customer = Customer::findOrFail($request->customer_id);

        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return ($item->itemable->getPrice() * $item->quantity) / 100;
        });

        $taxRate = config('laravel-cart.tax_rate', 0);
        $tax = $subtotal * $taxRate;
        $discountAmount = 0;

        if (session('discount')) {
            $discount = session('discount');
            $discountAmount = $discount['type'] === 'percentage'
                ? $subtotal * ($discount['value'] / 100)
                : $discount['value'];
        }

        $grandTotal = $subtotal + $tax - $discountAmount;

        return view('backend.invoice.product_invoice', compact(
            'cart',
            'customer',
            'subtotal',
            'tax',
            'discountAmount',
            'grandTotal'
        ));
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0'
        ]);

        session(['discount' => [
            'type' => $request->discount_type,
            'value' => $request->discount_value
        ]]);

        return redirect()->back()->with('success', 'Discount applied successfully');
    }

    public function removeDiscount()
    {
        session()->forget('discount');
        return redirect()->back()->with('success', 'Discount removed successfully');
    }
    public function clearCart()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        session()->forget('discount');

        return redirect()->back()->with('success', 'Cart cleared successfully');
    }
    public function invoicePrint()
    {
        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        return view('backend.invoice.invoice_print', compact('cart'));
    }
    public function invoiceDownload()
    {
        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        return view('backend.invoice.invoice_download', compact('cart'));
    }
    public function invoiceEmail()
    {
        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        return view('backend.invoice.invoice_email', compact('cart'));
    }
    public function invoiceSMS()
    {
        $cart = Cart::with('items.itemable')
            ->where('user_id', auth()->id())
            ->first();

        return view('backend.invoice.invoice_sms', compact('cart'));
    }
    public function invoiceDelete()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        session()->forget('discount');

        return redirect()->back()->with('success', 'Invoice deleted successfully');
    }
    
}
