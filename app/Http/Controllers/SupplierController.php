<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(
        private SupplierRepository $suppliers
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = $this->suppliers->paginated($request->only(['search']));

        return view('suppliers.index', [
            'suppliers' => $suppliers,
            'filters'   => $request->only(['search']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Supplier::class);
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $this->authorize('create', Supplier::class);

        $this->suppliers->create($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created.');
    }

    public function show(Supplier $supplier): View
    {
        $this->authorize('view', $supplier);

        $supplier->load('products');

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        $this->authorize('update', $supplier);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->authorize('update', $supplier);

        $this->suppliers->update($supplier, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->authorize('delete', $supplier);

        if ($supplier->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete a supplier that has products.');
        }

        $this->suppliers->delete($supplier);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted.');
    }
}