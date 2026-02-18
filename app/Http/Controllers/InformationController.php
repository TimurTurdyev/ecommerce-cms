<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Tables\InformationsTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        $table = new InformationsTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('information.index', compact('tableRenderer'));
    }

    public function createOrEdit(Information $information, Request $request)
    {
        return view('information.create', compact('information'));
    }

    public function delete(Information $information)
    {
        $information->delete();

        return back();
    }

    public function store(Request $request)
    {
        $information = Information::create($request->only([
            'bottom', 'sort_order', 'status',
            'title', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword',
        ]));

        return redirect()->route('information.edit', $information);
    }

    public function update(Information $information, Request $request)
    {
        $information->update($request->only([
            'bottom', 'sort_order', 'status',
            'title', 'description', 'meta_title', 'meta_h1', 'meta_description', 'meta_keyword',
        ]));

        return redirect()->route('information.edit', $information);
    }
}
