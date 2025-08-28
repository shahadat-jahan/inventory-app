<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SalesOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $salesOrders = SalesOrder::query()
            ->with('customer')
            ->when($request->string('q'), function ($query, $q) {
                $query->where('number', 'ilike', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('sales-orders/Index', [
            'salesOrders' => $salesOrders,
            'filters' => [
                'q' => (string) $request->string('q'),
            ],
        ]);
    }

    public function create(): Response
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('sales-orders/Create', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', 'unique:sales_orders,number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,confirmed,shipped,delivered,cancelled'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        SalesOrder::create($validated);

        return redirect()->route('sales-orders.index')->with('status', 'Sales Order created');
    }

    public function edit(SalesOrder $salesOrder): Response
    {
        $salesOrder->load('customer');
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('sales-orders/Edit', [
            'salesOrder' => $salesOrder,
            'customers' => $customers,
        ]);
    }

    public function update(Request $request, SalesOrder $salesOrder): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', "unique:sales_orders,number,{$salesOrder->id}"],
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,confirmed,shipped,delivered,cancelled'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $salesOrder->update($validated);

        return redirect()->route('sales-orders.index')->with('status', 'Sales Order updated');
    }

    public function destroy(SalesOrder $salesOrder): RedirectResponse
    {
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')->with('status', 'Sales Order deleted');
    }
}
