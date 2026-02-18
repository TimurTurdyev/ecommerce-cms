<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Tables\OrdersTable;
use App\Tables\Renderers\TableRenderer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $table = new OrdersTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('order.index', compact('tableRenderer'));
    }

    public function edit(Order $order, Request $request)
    {
        $order->load(['products.product', 'histories']);
        $statuses = Order::STATUSES;

        return view('order.edit', compact('order', 'statuses'));
    }

    public function update(Order $order, OrderRequest $request)
    {
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        $order->update($request->validated());

        if ($oldStatus !== $newStatus) {
            OrderHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'comment' => $request->input('comment'),
            ]);
        }

        return redirect()->route('order.edit', $order);
    }
}
