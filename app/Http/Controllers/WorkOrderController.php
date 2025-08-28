<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $workOrders = WorkOrder::query()
            ->with('product')
            ->when($request->string('q'), function ($query, $q) {
                $query->where('number', 'ilike', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('work-orders/Index', [
            'workOrders' => $workOrders,
            'filters' => [
                'q' => (string) $request->string('q'),
            ],
        ]);
    }

    public function create(): Response
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('work-orders/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', 'unique:work_orders,number'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.001'],
            'status' => ['required', 'in:planned,in_progress,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        WorkOrder::create($validated);

        return redirect()->route('work-orders.index')->with('status', 'Work Order created');
    }

    public function edit(WorkOrder $workOrder): Response
    {
        $workOrder->load('product');
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('work-orders/Edit', [
            'workOrder' => $workOrder,
            'products' => $products,
        ]);
    }

    public function update(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', "unique:work_orders,number,{$workOrder->id}"],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.001'],
            'status' => ['required', 'in:planned,in_progress,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $workOrder->update($validated);

        return redirect()->route('work-orders.index')->with('status', 'Work Order updated');
    }

    public function destroy(WorkOrder $workOrder): RedirectResponse
    {
        $workOrder->delete();
        return redirect()->route('work-orders.index')->with('status', 'Work Order deleted');
    }
}
