<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    public function index(Request $request): Response
    {
        $warehouses = Warehouse::query()
            ->when($request->string('q'), function ($query, $q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhere('code', 'ilike', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('warehouses/Index', [
            'warehouses' => $warehouses,
            'filters' => [
                'q' => (string) $request->string('q'),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('warehouses/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:64', 'unique:warehouses,code'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('status', 'Warehouse created');
    }

    public function edit(Warehouse $warehouse): Response
    {
        return Inertia::render('warehouses/Edit', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:64', "unique:warehouses,code,{$warehouse->id}"],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('status', 'Warehouse updated');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('status', 'Warehouse deleted');
    }
}
