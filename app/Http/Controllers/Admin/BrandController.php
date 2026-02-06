<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

class BrandController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name|max:255',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->description = $request->description;
            
        if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_name = time() . '.' . $image->extension();

        $this->generateBrandThumbnail($image, $file_name);
        $brand->image = $file_name;
    }

        $brand->save();

        return redirect()->route('brands.index')
        ->with('success', 'Brand created successfully.');
    }

      protected function generateBrandThumbnail(string $filePath, string $imageName): void
{
    $destinationPath = public_path('uploads/brands');

    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    $img = $this->imageManager
        ->read($filePath)
        ->cover(124, 124); // v3 replacement for fit()

    $img->save(
        $destinationPath . '/' . $imageName,
        quality: 90
    );
}
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    // Update category function
   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
    ]);

    $brand = Brand::findOrFail($id);
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);

    if ($request->hasFile('image')) {
        // Delete old image
        if ($brand->image && File::exists(public_path('uploads/categories/' . $brand->image))) {
            File::delete(public_path('uploads/categories/' . $brand->image));
        }

        $image = $request->file('image');
        $file_name = time() . '.' . $image->extension();

        // ✅ CORRECT method
        $this->generateBrandThumbnail($image, $file_name);
        $brand->image = $file_name;
    }

    $brand->save();

    return redirect()->route('brands.index')
        ->with('status', 'Brand has been updated successfully');
}


   public function destroy($brand_id)
{
    $brand = Brand::findOrFail($brand_id);

    if ($brand->image && File::exists(public_path('uploads/categories/' . $brand->image))) {
        File::delete(public_path('uploads/brands/' . $brand->image));
    }

    $brand->delete();

    return redirect()->route('brands.index')
        ->with('status', 'brand has been deleted successfully');
}
}
