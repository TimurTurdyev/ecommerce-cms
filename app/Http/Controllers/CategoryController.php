<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Tables\CategoriesTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return redirect()->route('category.edit', $category);
    }

    public function update(Category $category, CategoryRequest $request)
    {
        $category->update($request->validated());

        return redirect()->route('category.edit', $category);
    }

    /**
     * AJAX endpoint для поиска категорий (autocomplete)
     */
    public function search(Request $request)
    {
        $startTime = microtime(true);

        // Валидация
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $query = $validated['q'];

        // Поиск категорий
        $categories = Category::search($query)
            ->limit(10)
            ->get();

        // Формирование результатов с full_path
        $results = $categories->map(static function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'full_path' => $category->getFullPath(),
            ];
        });

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        // Verbose logging
        if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
            Log::debug('[CategoryController.search] Search completed', [
                'query' => $query,
                'results_count' => $results->count(),
                'duration_ms' => $duration,
                'results' => $results->toArray(),
            ]);
        }

        return response()->json($results);
    }
}
