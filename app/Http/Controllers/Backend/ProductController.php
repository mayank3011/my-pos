<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Group;
use App\Models\Supplier;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // ✅ Import the correct driver
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use DNS1D;


class ProductController extends Controller
{
    public function AllProduct()
    {
        $product = Product::latest()->get();
        return view('backend.product.all_product', compact('product'));
    } // End Method
    public function AddProduct()
    {
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        $group = Group::latest()->get();
        return view('backend.product.add_product', compact('category', 'supplier', 'group'));
    } // End Method 
    public function StoreProduct(Request $request)
    {
        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'product_code', 'length' => 4, 'prefix' => 'PC']);
        $image = $request->file('product_image');

        if ($image) {
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Image processing
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/product/' . $name_gen));

            $save_url = 'upload/product/' . $name_gen;
        } else {
            $save_url = null;
        }

        Product::insert([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'group_id' => $request->group_id,
            'supplier_id' => $request->supplier_id,
            'product_code' => $pcode,
            'product_garage' => $request->product_garage,
            'product_store' => $request->product_store,
            'buying_date' => $request->buying_date,
            'expire_date' => $request->expire_date,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'product_image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('all.product')->with([
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }
    public function EditProduct($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::latest()->get();
        $group = Group::latest()->get();
        $supplier = Supplier::latest()->get();

        return view('backend.product.edit_product', compact('product', 'category', 'supplier', 'group'));
    }

    public function UpdateProduct(Request $request)
    {
        $product_id = $request->id;

        if ($request->file('product_image')) {
            $image = $request->file('product_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Correctly using Intervention ImageManager
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/product/' . $name_gen));

            $save_url = 'upload/product/' . $name_gen;

            // Remove old image if exists
            $old_image = Product::findOrFail($product_id)->product_image;
            if (file_exists(public_path($old_image)) && $old_image) {
                unlink(public_path($old_image));
            }

            Product::findOrFail($product_id)->update([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'group_id' => $request->group_id,
                'supplier_id' => $request->supplier_id,
                'product_code' => $request->product_code,
                'product_garage' => $request->product_garage,
                'product_store' => $request->product_store,
                'buying_date' => $request->buying_date,
                'expire_date' => $request->expire_date,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'product_image' => $save_url,
                'updated_at' => Carbon::now(),
            ]);
        } else {
            Product::findOrFail($product_id)->update([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'group_id' => $request->group_id,
                'supplier_id' => $request->supplier_id,
                'product_code' => $request->product_code,
                'product_garage' => $request->product_garage,
                'product_store' => $request->product_store,
                'buying_date' => $request->buying_date,
                'expire_date' => $request->expire_date,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('all.product')->with([
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Remove product image
        if (file_exists(public_path($product->product_image)) && $product->product_image) {
            unlink(public_path($product->product_image));
        }

        $product->delete();

        return redirect()->back()->with([
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function ImportProduct()
    {
        return view('backend.product.import_product');
    } // End Method 
    public function Export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    } // End Method
    public function Import(Request $request)
    {

        Excel::import(new ProductImport, $request->file('import_file'));

        $notification = array(
            'message' => 'Product Imported Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 
    public function BarcodeProduct($id)
    {
        $product = Product::findOrFail($id);

        // Generate barcode SVG
        $barcode = DNS1D::getBarcodeSVG(
            $product->product_code,  // The product code to encode
            'C128',                  // Barcode type (C128, C39, EAN13 etc)
            2,                       // Width factor
            60,                      // Height in px
            'black',                 // Color
            true                     // Show text below barcode
        );

        return view('backend.product.barcode_product', compact('product', 'barcode'));
    }
}
