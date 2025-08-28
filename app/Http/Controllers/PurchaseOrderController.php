<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with('supplier')
            ->when($request->string('q'), function ($query, $q) {
                $query->where('number', 'ilike', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('purchase-orders/Index', [
            'purchaseOrders' => $purchaseOrders,
            'filters' => [
                'q' => (string) $request->string('q'),
            ],
        ]);
    }

    public function create(): Response
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('purchase-orders/Create', [
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', 'unique:purchase_orders,number'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,ordered,received,cancelled'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        PurchaseOrder::create($validated);

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase Order created');
    }

    public function edit(PurchaseOrder $purchaseOrder): Response
    {
        $purchaseOrder->load('supplier');
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('purchase-orders/Edit', [
            'purchaseOrder' => $purchaseOrder,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:64', "unique:purchase_orders,number,{$purchaseOrder->id}"],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,ordered,received,cancelled'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $purchaseOrder->update($validated);

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase Order updated');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('status', 'Purchase Order deleted');
    }
}
