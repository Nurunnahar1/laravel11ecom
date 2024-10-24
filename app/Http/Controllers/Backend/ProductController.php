<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'description' => 'required',
            'short_description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'images.*' => 'nullable|mimes:png,jpg,jpeg', // handle multiple images
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        // Generate a base name using product slug (related naming)
        $slug = Str::slug($request->name);

        // Single image handling with related name
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Use slug for consistent naming, adding a suffix like '_main'
            $fileName = $slug.'_main.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads/products/thumbnails'), $fileName);
            $filePath = "uploads/products/thumbnails/{$fileName}";
        }

        // Multiple images handling with related names
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            $imageIndex = 1; // To differentiate multiple images
            foreach ($request->file('images') as $imageFile) {
                // Use slug and index to create related names for each image
                $multiFileName = $slug.'_image_'.$imageIndex.'.'.$imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('/uploads/products/related_products'), $multiFileName);
                $imagesPaths[] = "uploads/products/related_products/{$multiFileName}";
                $imageIndex++;
            }
        }

        // Store product information in the database
        Product::create([
            'name' => $request->name,
            'slug' => $slug, // already created slug
            'description' => $request->description,
            'short_description' => $request->short_description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'SKU' => $request->SKU,
            'stock_status' => $request->stock_status,
            'featured' => $request->featured,
            'quantity' => $request->quantity,
            'image' => $filePath, // store main image path
            'images' => json_encode($imagesPaths), // store paths of multiple images
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return redirect()->route('admin.product.index')->with('status', 'Product successfully created.');
    }
}
