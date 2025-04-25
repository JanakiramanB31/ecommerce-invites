<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $products = Product::getAllProduct();
    return view('backend.product.index', compact('products'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
  */
  public function uploadcsv()
  {
    $products = Product::getAllProduct();
    return view('backend.product.upload', compact('products'));
  }

  public function uploadcsvdata(Request $request)
  {
    $csvPath = $request->input('csv');
    $fullPath = public_path($csvPath);

    echo $fullPath;
    // exit;

    $brands = Brand::select('id', 'title')->get();
    $parentCategories = Category::select('id', 'title')->where('is_parent', 1)->get();
    $childCategories = Category::select('id', 'title', 'parent_id')->where('is_parent', 0)->get();


    if (($fileData = fopen($fullPath, 'r')) !== false) {
      $header = fgetcsv($fileData); // read first row as header
      $this->pr($header);
      while (($row = fgetcsv($fileData)) !== false) {

        $parentCat = collect($parentCategories)->first(function ($item) use ($row) {
          return Str::of($item['title'])->lower()->trim() == Str::of($row[4])->lower()->trim();
        })['id'] ?? null;

        $childCat = collect($childCategories)->first(function ($item) use ($row) {
          return Str::of($item['title'])->lower()->trim() == Str::of($row[5])->lower()->trim();
        })['id'] ?? null;
        $this->pr($row);

        $brand = collect($brands)->first(function ($item) use ($row) {
          return Str::of($item['title'])->lower()->trim() == Str::of($row[9])->lower()->trim();
        })['id'] ?? null;

        $size = str_replace(['[', ']','"'], '', $row[8]); 
        $sizes = explode('|', $size);
        $status = Str::of($row[12])->lower()->trim()->toString();
        $condition = Str::of($row[10])->lower()->trim()->toString();

        $data = [
          'title' => $row[0],
          'summary' => $row[1],
          'description' => $row[2],
          'size' => $sizes,
          'stock' => $row[11],
          'cat_id' => $parentCat,
          'brand_id' => $brand,
          'child_cat_id' => $childCat,
          'is_featured' => $row[3],
          'status' => $status,
          'condition' => $condition,
          'price' => $row[6],
          'discount' => $row[7],
        ];

        $rules = [
          'title' => 'required|string',
          'summary' => 'required|string',
          'description' => 'nullable|string',
          'size' => 'nullable',
          'stock' => 'required|numeric',
          'cat_id' => 'required|exists:categories,id',
          'brand_id' => 'nullable|exists:brands,id',
          'child_cat_id' => 'nullable|exists:categories,id',
          'is_featured' => 'sometimes|in:1',
          'status' => 'required|in:active,inactive',
          'condition' => 'required|in:default,new,hot',
          'price' => 'required|numeric',
          'discount' => 'nullable|numeric',
        ];

        // Validate the data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
          $errors = $validator->errors();
          $this->pr($errors);
        exit;
          return redirect()->route('product.uploadcsv')->withErrors($errors)->withInput();
        }


        $slug = $this->generateUniqueSlug($data['title'], Product::class);
        echo $data['slug'] = $slug;

        if (isset($data['size'])) {
          $data['size'] = implode(',', $data['size']);
        } else {
          $data['size'] = '';
        }

        $product = $data;
        $this->pr($product);
        // exit;
      $product = Product::create($data);

      $message = $product
          ? 'Product Successfully added'
          : 'Please try again!!';

      return redirect()->route('product.index')->with(
          $product ? 'success' : 'error',
          $message
      );

      }

      fclose($fileData);
    }
    exit;
    return redirect()->back()->with('success', 'CSV data imported successfully.');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
  */
  public function create()
  {
    $brands = Brand::get();
    $categories = Category::where('is_parent', 1)->get();
    return view('backend.product.create', compact('categories', 'brands'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {

    $this->pr($request->all());
    exit;
      $validatedData = $request->validate([
          'title' => 'required|string',
          'summary' => 'required|string',
          'description' => 'nullable|string',
          'photo' => 'required|string',
          'size' => 'nullable',
          'stock' => 'required|numeric',
          'cat_id' => 'required|exists:categories,id',
          'brand_id' => 'nullable|exists:brands,id',
          'child_cat_id' => 'nullable|exists:categories,id',
          'is_featured' => 'sometimes|in:1',
          'status' => 'required|in:active,inactive',
          'condition' => 'required|in:default,new,hot',
          'price' => 'required|numeric',
          'discount' => 'nullable|numeric',
      ]);

      $slug = generateUniqueSlug($request->title, Product::class);
      $validatedData['slug'] = $slug;
      $validatedData['is_featured'] = $request->input('is_featured', 0);

      if ($request->has('size')) {
          $validatedData['size'] = implode(',', $request->input('size'));
      } else {
          $validatedData['size'] = '';
      }

      $product = Product::create($validatedData);

      $message = $product
          ? 'Product Successfully added'
          : 'Please try again!!';

      return redirect()->route('product.index')->with(
          $product ? 'success' : 'error',
          $message
      );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
      // Implement if needed
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    $brands = Brand::get();
    $product = Product::findOrFail($id);
    $categories = Category::where('is_parent', 1)->get();
    $items = Product::where('id', $id)->get();

    return view('backend.product.edit', compact('product', 'brands', 'categories', 'items'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    $product = Product::findOrFail($id);

    $validatedData = $request->validate([
      'title' => 'required|string',
      'summary' => 'required|string',
      'description' => 'nullable|string',
      'photo' => 'required|string',
      'size' => 'nullable',
      'stock' => 'required|numeric',
      'cat_id' => 'required|exists:categories,id',
      'child_cat_id' => 'nullable|exists:categories,id',
      'is_featured' => 'sometimes|in:1',
      'brand_id' => 'nullable|exists:brands,id',
      'status' => 'required|in:active,inactive',
      'condition' => 'required|in:default,new,hot',
      'price' => 'required|numeric',
      'discount' => 'nullable|numeric',
    ]);

    $validatedData['is_featured'] = $request->input('is_featured', 0);

    if ($request->has('size')) {
      $validatedData['size'] = implode(',', $request->input('size'));
    } else {
      $validatedData['size'] = '';
    }

    $status = $product->update($validatedData);

    $message = $status
      ? 'Product Successfully updated'
      : 'Please try again!!';

    return redirect()->route('product.index')->with(
      $status ? 'success' : 'error',
      $message
    );
  }

  public function copy($id)
  {
    $brands = Brand::get();
    $product = Product::findOrFail($id);
    $categories = Category::where('is_parent', 1)->get();
    $items = Product::where('id', $id)->get();

    return view('backend.product.duplicate', compact('product', 'brands', 'categories', 'items'));
  }

  /**
   * Copy the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function duplicate_entry(Request $request)
  {
    $validatedData = $request->validate([
      'title' => 'required|string',
      'summary' => 'required|string',
      'description' => 'nullable|string',
      'photo' => 'required|string',
      'size' => 'nullable',
      'stock' => 'required|numeric',
      'cat_id' => 'required|exists:categories,id',
      'brand_id' => 'nullable|exists:brands,id',
      'child_cat_id' => 'nullable|exists:categories,id',
      'is_featured' => 'sometimes|in:1',
      'status' => 'required|in:active,inactive',
      'condition' => 'required|in:default,new,hot',
      'price' => 'required|numeric',
      'discount' => 'nullable|numeric',
    ]);

    $slug = $this->generateUniqueSlug($request->title, Product::class);
    $validatedData['slug'] = $slug;
    $validatedData['is_featured'] = $request->input('is_featured', 0);

    if ($request->has('size')) {
      $validatedData['size'] = implode(',', $request->input('size'));
    } else {
      $validatedData['size'] = '';
    }

    $newProduct = Product::create($validatedData);

    $message = $newProduct ? 'Product Duplicated Successfully' : 'Please try again!!';

    return redirect()->route('product.index')->with(
      $newProduct ? 'success' : 'error',
      $message
    );
  }
   
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $status = $product->delete();

    $message = $status
      ? 'Product successfully deleted'
      : 'Error while deleting product';

    return redirect()->route('product.index')->with(
      $status ? 'success' : 'error',
      $message
    );
  }
}
