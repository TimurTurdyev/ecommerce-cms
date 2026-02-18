<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Tables\ProductsTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $table = new ProductsTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('product.index', compact('tableRenderer'));
    }

    public function createOrEdit(Product $product, Request $request)
    {
        $manufacturers = Manufacturer::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');

        return view('product.create', compact('product', 'manufacturers', 'categories'));
    }

    public function delete(Product $product)
    {
        $product->delete();

        return back();
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->input('categories')) {
            $product->categories()->attach($request->input('categories'));
        }

        return redirect()->route('product.edit', $product);
    }

    public function update(Product $product, ProductRequest $request)
    {
        $product->update($request->validated());

        if ($request->has('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        return redirect()->route('product.edit', $product);
    }
}
