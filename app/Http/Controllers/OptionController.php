<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionRequest;
use App\Models\Option;
use App\Models\OptionValue;
use App\Tables\OptionsTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index(Request $request)
    {
        $table = new OptionsTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('option.index', compact('tableRenderer'));
    }

    public function createOrEdit(Option $option, Request $request)
    {
        return view('option.create', compact('option'));
    }

    public function delete(Option $option)
    {
        $option->delete();

        return back();
    }

    public function store(OptionRequest $request)
    {
        $option = Option::create($request->validated());

        return redirect()->route('option.edit', $option);
    }

    public function update(Option $option, OptionRequest $request)
    {
        $option->update($request->validated());

        return redirect()->route('option.edit', $option);
    }

    public function storeValue(Request $request, Option $option)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $option->values()->create($request->only(['name', 'sort_order']));

        return back();
    }

    public function deleteValue(OptionValue $optionValue)
    {
        $optionValue->delete();

        return back();
    }
}
