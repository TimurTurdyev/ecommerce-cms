<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationRequest;
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

    public function store(InformationRequest $request)
    {
        $information = Information::create($request->validated());

        return redirect()->route('information.edit', $information);
    }

    public function update(Information $information, InformationRequest $request)
    {
        $information->update($request->validated());

        return redirect()->route('information.edit', $information);
    }
}
