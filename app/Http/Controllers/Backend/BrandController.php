<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{

    // display brand
    public function brands()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brand.brands', compact('brands'));
    }

    // create brands
    public function createBrand()
    {
        return view('admin.brand.create');
    }

    //store brand
    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('image')) {

            $file = request()->file('image');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); //for name making
            $file->move(public_path('/uploads/brands'), $fileName);
            $filePath = "uploads/brands/{$fileName}";

            Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $filePath,
            ]);
            return redirect()->route('admin.brands')->with('status', 'Brand successfully created.');
        }
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('admin.brands')->with('status', 'Brand successfully created.');

    }

    public function editBrand($slug)
    {
        $brands = Brand::where('slug', $slug)->first();
        return view('admin.brand.edit', compact('brands'));
    }

    public function updateBrand(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:1024',
        ]);

        $brand = Brand::where('slug', $slug)->first();
        if ($request->hasFile('image')) {

            //delete previous image
            if ($brand && File::exists(public_path($brand->image))) {
                File::delete(public_path($brand->image));
            }

            $file = request()->file('image');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); //for name making
            $file->move(public_path('/uploads/brands'), $fileName);
            $filePath = "uploads/brands/{$fileName}";

            Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $filePath,
            ]);
            return redirect()->route('admin.brands')->with('status', 'Brand successfully created.');
        }
           Brand::create([
           'name' => $request->name,
           'slug' => Str::slug($request->name),
           ]);
           return redirect()->route('admin.brands')->with('status', 'Brand successfully created.');


    }
}
