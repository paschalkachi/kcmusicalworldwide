<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShippingClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use App\Services\SkuGenerator;
use Intervention\Image\Facades\Image;



class ProductController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories =  Category::select('id', 'name', 'code')->get();
        $brands = Brand::pluck('name', 'id');
        $shippingClasses = ShippingClass::all();
        $shippingClassOptions = $shippingClasses->pluck('name', 'id');
        return view('admin.products.add', compact('categories', 'brands','shippingClassOptions','shippingClasses'));        
    }

   public function store(Request $request)
{
    $shippingClasses = ShippingClass::all();

    // VALIDATION
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'sale_price' => 'required|numeric',
        'regular_price' => 'required|numeric',
        'SKU' => 'required|string|max:50',
        'stock_status' => 'required|in:instock,preorder,outofstock',
        'quantity' => 'nullable|required_if:stock_status,instock|integer|min:0',
        'featured' => 'required|boolean',

        // SHIPPING
        'shipping_class_id' => 'required|exists:shipping_classes,id',
        'shipping_units' => 'required|integer|min:1',

        // IMAGES
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
    ]);

    // CLEAN DATA BASED ON STOCK STATUS
    $quantity = null;
    $preorderLimit = null;

    if ($request->stock_status === 'instock') {
        $quantity = $request->quantity;
    }

    if ($request->stock_status === 'preorder') {
        $preorderLimit = $request->quantity;
    }


    // CREATE PRODUCT INSTANCE
    $product = new Product();

    // MAIN IMAGE
    if ($request->hasFile('image')) {
        $mainImage = $request->file('image');
        $mainImageName = time() . '_' . uniqid() . '.' . $mainImage->extension();

        // Generate thumbnails (your custom method)
        $this->GenerateProductThumbnails($mainImage->getRealPath(), $mainImageName);

        $product->image = $mainImageName;
    }

    // GALLERY IMAGES
    $galleryPaths = [];
    if ($request->hasFile('images')) {
        $destination = public_path('uploads/products');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        foreach ($request->file('images') as $galleryImage) {
            $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->extension();
            $galleryImage->move($destination, $galleryName);
            $galleryPaths[] = $galleryName;
        }
    }

    // FETCH CATEGORY TO GENERATE SKU
    $category = Category::findOrFail($request->category_id);

    // FILL PRODUCT DATA
    $product->fill([
        'name' => $request->name,
        'slug' => $request->slug,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'short_description' => $request->short_description,
        'description' => $request->description,
        'regular_price' => $request->regular_price,
        'sale_price' => $request->sale_price,
        'SKU' => SkuGenerator::generate($category->code),
        'stock_status' => $request->stock_status,
        'quantity' => $quantity,
        'preorder_limit' => $preorderLimit,
        'featured' => $request->featured,
        'shipping_class_id' => $request->shipping_class_id,
        'shipping_unit' => $request->shipping_units,
        'images' => json_encode($galleryPaths),
    ]);

    // SAVE PRODUCT
    $product->save();

    return redirect()->route('products.index')->with('status', 'Product added successfully!');
}

public function generateSku(Request $request)
{
    try {
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        $category = \App\Models\Category::findOrFail($request->category_id);

        if (!$category->code) {
            return response()->json(['error' => 'Category code missing'], 422);
        }

        $sku = SkuGenerator::generate($category->code);

        return response()->json(['sku' => $sku]);
    } catch (\Throwable $e) {
        \Log::error($e);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // Generate Product Thumbnails
    public function GenerateProductThumbnails(string $filePath, string $imageName): void
    {
        $destinationPath = public_path('uploads/products');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Resize/cover to larger size to prevent blurriness (400x400)
        $img = $this->imageManager
            ->read($filePath)
            ->cover(400, 400);

        $img->save($destinationPath . '/' . $imageName, quality: 90);
    }

    
     public function edit(Product $product)
    {    
            //dd($product->images);  
            $categories = Category::pluck('name', 'id');
            $brands = Brand::pluck('name', 'id');
            $shippingClasses = ShippingClass::all();
            $shippingClassOptions = $shippingClasses->pluck('name', 'id');
            return view('admin.products.edit', compact('product', 'categories', 'brands', 'shippingClasses', 'shippingClassOptions'));
 
    }

     // Update product function
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug,' . $id,
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'regular_price' => 'required|numeric',
        'sale_price' => 'required|numeric',
        'SKU' => 'required|string|max:50',
        'stock_status' => 'required|in:instock,preorder,outofstock',
        'quantity' => 'nullable|required_if:stock_status,instock|integer|min:0',
        'featured' => 'required|boolean',

        /* SHIPPING */
        'shipping_class_id' => 'required|exists:shipping_classes,id',
        'shipping_units' => 'required|integer|min:1',

        'image' => 'nullable|image|max:4096',
        'images.*' => 'nullable|image|max:4096',
        'existing_images' => 'nullable|string',
    ]);

    $product = Product::findOrFail($id);
    $quantity = null;
    $preorderLimit = null;

    if ($request->stock_status === 'instock') {
        $quantity = $request->quantity;
    }

    if ($request->stock_status === 'preorder') {
        $preorderLimit = $request->quantity;
    }
/* ---------------- BASIC FIELDS ---------------- */
    $product->fill([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'short_description' => $request->short_description,
        'description' => $request->description,
        'regular_price' => $request->regular_price,
        'sale_price' => $request->sale_price,
        'SKU' => $request->SKU,
        'stock_status' => $request->stock_status,
        'quantity' => $quantity,
        'preorder_limit' => $preorderLimit,
        'featured' => $request->featured,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'shipping_class_id' => $request->shipping_class_id,
        'shipping_unit' => $request->shipping_units,
    ]);

    /* ---------------- MAIN IMAGE ---------------- */
    if ($request->hasFile('image')) {
        if ($product->image) {
            File::delete(public_path('uploads/products/' . $product->image));
            File::delete(public_path('uploads/products/thumbnails/' . $product->image));
        }

        $image = $request->file('image');
        $fileName = time() . '_' . uniqid() . '.' . $image->extension();

        $this->GenerateProductThumbnails($image->getRealPath(), $fileName);

        $product->image = $fileName;
    }

    /* ---------------- GALLERY (MERGE LOGIC) ---------------- */

    $oldImages = json_decode($product->images ?? '[]', true);
    $keptImages = json_decode($request->existing_images ?? '[]', true);

    // Delete removed images
    foreach ($oldImages as $img) {
        if (!in_array($img, $keptImages)) {
            File::delete(public_path('uploads/products/' . $img));
        }
    }

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $name = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/products'), $name);
            $keptImages[] = $name;
        }
    }

    $product->images = json_encode($keptImages);

    /* ---------------- SAVE ---------------- */
    $product->save();

    return redirect()
        ->route('products.index')
        ->with('status', 'Product updated successfully');
}

    public function destroy(Product $product)
{
    $uploadPath = public_path('uploads/products');

    /* ===========================
     | Delete main image
     =========================== */
    if ($product->image && File::exists($uploadPath . '/' . $product->image)) {
        File::delete($uploadPath . '/' . $product->image);
    }

    /* ===========================
     | Delete gallery images
     =========================== */
    if (!empty($product->images)) {
        $galleryImages = is_array($product->images)
            ? $product->images
            : json_decode($product->images, true);

        foreach ($galleryImages as $image) {
            $path = $uploadPath . '/' . $image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    /* ===========================
     | Delete product record
     =========================== */
    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('status', 'Product deleted successfully!');
}


}