<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;



class CategoryController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));

        //dd(vars: Image::getDriverName());
    }

    public function create()
    {
        return view('admin.categories.add');
        
    }

   public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|unique:categories,name|max:255',
        'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);
        //dd($this->imageManager->driver());
    $category = new Category();
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->description = $request->description;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = time() . '.' . $image->extension();

        $this->generateCategoryThumbnail($image->getRealPath(), $fileName);
        $category->image = $fileName;

    }
    

    $category->save();

    return redirect()
        ->route('categories.index')
        ->with('status', 'Category created successfully.');
}

protected function generateCategoryThumbnail(string $filePath, string $imageName): void
{
    $destinationPath = public_path('uploads/categories');

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

    public function edit(Category $category)
    {
       
        return view('admin.categories.edit', compact('category'));
    }

    
     // Update category function
   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
    ]);

    $category = Category::findOrFail($id);
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);

    if ($request->hasFile('image')) {
        // Delete old image
        if ($category->image && File::exists(public_path('uploads/categories/' . $category->image))) {
            File::delete(public_path('uploads/categories/' . $category->image));
        }

        $image = $request->file('image');
        $file_name = time() . '.' . $image->extension();

        // ✅ CORRECT method
        $this->generateCategoryThumbnail($image, $file_name);
        $category->image = $file_name;
    }

    $category->save();

    return redirect()->route('categories.index')
        ->with('status', 'Category has been updated successfully');
}



    // Delete Category Function
  public function destroy($category_id)
{
    $category = Category::findOrFail($category_id);

    if ($category->image && File::exists(public_path('uploads/categories/' . $category->image))) {
        File::delete(public_path('uploads/categories/' . $category->image));
    }

    $category->delete();

    return redirect()->route('categories.index')
        ->with('status', 'Category has been deleted successfully');
}
}
