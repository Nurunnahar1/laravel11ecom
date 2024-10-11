<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{

    // display brand
    public function brands(){
    $brands = Brand::orderBy('id', 'desc')->paginate(10);
    return view('admin.brand.brands', compact('brands'));
    }

    // create brands
    public function createBrand(){
        return view('admin.brand.create');
    }

    //store brand
    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
             'image' => 'mimes:png,jpg,jpeg|max:2028'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp() . '.' . $file_extension;
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been created sucessfully!');
    }
}
