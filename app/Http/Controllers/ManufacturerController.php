<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerRequest;
use App\Models\Manufacturer;
use App\Tables\ManufacturersTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $table = new ManufacturersTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('manufacturer.index', compact('tableRenderer'));
    }

    public function createOrEdit(Manufacturer $manufacturer, Request $request)
    {
        return view('manufacturer.create', compact('manufacturer'));
    }

    public function delete(Manufacturer $manufacturer)
    {
        $manufacturer->delete();

        return back();
    }

    public function store(ManufacturerRequest $request)
    {
        $manufacturer = Manufacturer::create($request->validated());

        return redirect()->route('manufacturer.edit', $manufacturer);
    }

    public function update(Manufacturer $manufacturer, ManufacturerRequest $request)
    {
        $manufacturer->update($request->validated());

        return redirect()->route('manufacturer.edit', $manufacturer);
    }
}
