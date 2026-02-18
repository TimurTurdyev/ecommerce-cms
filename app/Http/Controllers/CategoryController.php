<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Tables\CategoriesTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $table = new CategoriesTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('category.index', compact('tableRenderer'));
    }

    public function createOrEdit(Category $category, Request $request)
    {
        $parents = Category::where('id', '!=', $category->id)
            ->pluck('name', 'id');

        return view('category.create', compact('category', 'parents'));
    }

    public function delete(Category $category)
    {
        $category->delete();

        return back();
    }

    public function store(Request $request)
    {
        $category = Category::create($request->only([
            'parent_id', 'image', 'sort_order', 'status',
            'name', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword',
        ]));

        return redirect()->route('category.edit', $category);
    }

    public function update(Category $category, Request $request)
    {
        $category->update($request->only([
            'parent_id', 'image', 'sort_order', 'status',
            'name', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword',
        ]));

        return redirect()->route('category.edit', $category);
    }
}
