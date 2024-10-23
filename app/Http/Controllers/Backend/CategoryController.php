<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.category.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('image')) {

            $file = request()->file('image');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension(); //for name making
            $file->move(public_path('/uploads/category'), $fileName);
            $filePath = "uploads/category/{$fileName}";

            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $filePath,
            ]);

            return redirect()->route('admin.category.index')->with('status', 'Category successfully created.');
        }
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.category.index')->with('status', 'Category successfully created.');

    }

    public function editCategory($slug)
    {
        $categories = Category::where('slug', $slug)->first();

        return view('admin.category.edit', compact('categories'));
    }

    public function updateCategory(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:1024',
        ]);

        // Find the category instance by slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Check if an image is uploaded
        if ($request->hasFile('image')) {

            // Delete previous image if it exists
            if ($category && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }

            // Process and upload the new image
            $file = $request->file('image');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads/category'), $fileName);
            $filePath = "uploads/category/{$fileName}";

            // Update the category instance with image
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $filePath,
            ]);

            return redirect()->route('admin.category.index')->with('status', 'Category successfully updated.');
        }

        // Update the category without image
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.category.index')->with('status', 'Category successfully updated.');
    }

    public function deleteCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            // Check if the category  has an image and delete it
            if ($category->image) {
                $image_location = public_path($category->image);
                if (file_exists($image_location)) {
                    unlink($image_location);
                }
            }

            // Delete the  category
            $category->delete();

            return redirect()->route('admin.category.index')->with('status', 'Category deleted successfully.');
        }

        return redirect()->route('admin.category.index')->with('error', 'Category not found.');
    }
}
