<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    public function editProduct(Request $request, $slug)
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $products = Product::where('slug', $slug)->first();

        return view('admin.product.edit', compact('products', 'brands', 'categories'));
    }

    public function updateProduct(Request $request, $slug)
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
            'image' => 'nullable|mimes:png,jpg,jpeg', // main image can be nullable for updates
            'images.*' => 'nullable|mimes:png,jpg,jpeg', // handle multiple images
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();

        // Generate a new slug and ensure it is unique
        $newSlug = Str::slug($request->name);
        if ($newSlug !== $product->slug) {
            // Check if the new slug exists in another product
            $existingProduct = Product::where('slug', $newSlug)->first();
            if ($existingProduct) {
                $newSlug .= '-'.time(); // Append timestamp to ensure uniqueness
            }
        }

        // Handle main image update with the new slug-based name
        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $file = $request->file('image');
            $fileName = $newSlug.'_main.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads/products/thumbnails'), $fileName);
            $filePath = "uploads/products/thumbnails/{$fileName}";
        } else {
            $filePath = $product->image;
        }

        // Handle multiple images update with the new slug-based names
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($product->images) {
                foreach (json_decode($product->images) as $oldImagePath) {
                    if (File::exists(public_path($oldImagePath))) {
                        File::delete(public_path($oldImagePath));
                    }
                }
            }

            // Upload new images with names based on the new slug
            $imageIndex = 1;
            foreach ($request->file('images') as $imageFile) {
                $multiFileName = $newSlug.'_image_'.$imageIndex.'.'.$imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('/uploads/products/related_products'), $multiFileName);
                $imagesPaths[] = "uploads/products/related_products/{$multiFileName}";
                $imageIndex++;
            }
        } else {
            $imagesPaths = json_decode($product->images, true);
        }

        // Update the product with new information, forcing slug and image updates
        $product->update([
            'name' => $request->name,
            'slug' => $newSlug, // save the new slug
            'description' => $request->description,
            'short_description' => $request->short_description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'SKU' => $request->SKU,
            'stock_status' => $request->stock_status,
            'featured' => $request->featured,
            'quantity' => $request->quantity,
            'image' => $filePath, // updated main image path with new slug
            'images' => json_encode($imagesPaths), // updated multiple images paths with new slug
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return redirect()->route('admin.product.index')->with('status', 'Product successfully updated.');
    }
}
