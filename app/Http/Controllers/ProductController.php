<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $product = Product::create($request->only([
            'model', 'sku', 'quantity', 'price', 'weight', 'length', 'width', 'height',
            'image', 'manufacturer_id', 'status', 'sort_order',
            'name', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword', 'tag',
        ]));

        if ($request->input('categories')) {
            $product->categories()->attach($request->input('categories'));
        }

        return redirect()->route('product.edit', $product);
    }

    public function update(Product $product, Request $request)
    {
        $product->update($request->only([
            'model', 'sku', 'quantity', 'price', 'weight', 'length', 'width', 'height',
            'image', 'manufacturer_id', 'status', 'sort_order',
            'name', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword', 'tag',
        ]));

        if ($request->has('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        return redirect()->route('product.edit', $product);
    }
}
