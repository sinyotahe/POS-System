<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    public function index(): Response
    {
        $branches = Branch::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->withCount('users')
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return Inertia::render('Branches/Index', [
            'branches' => $branches,
            'filters' => request()->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Branches/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'boolean',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch): Response
    {
        return Inertia::render('Branches/Edit', [
            'branch' => $branch,
        ]);
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,'.$branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $relations = [
            'users', 'sales', 'stockIns', 'stockOuts', 'stockMovements',
            'purchaseOrders', 'heldCarts', 'products',
        ];

        foreach ($relations as $relation) {
            if ($branch->{$method = $relation === 'products' ? 'products' : $relation}()->count() > 0) {
                $labels = [
                    'users' => 'user',
                    'sales' => 'transaksi penjualan',
                    'stockIns' => 'barang masuk',
                    'stockOuts' => 'barang keluar',
                    'stockMovements' => 'mutasi stok',
                    'purchaseOrders' => 'purchase order',
                    'heldCarts' => 'cart ditahan',
                    'products' => 'produk',
                ];

                return redirect()->route('branches.index')
                    ->with('error', "Cabang tidak bisa dihapus karena masih memiliki {$labels[$relation]}.");
            }
        }

        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }

    public function switchBranch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch = Branch::find($validated['branch_id']);

        if (! $branch || ! $branch->status) {
            return redirect()->back()
                ->with('error', 'Cabang tidak aktif atau tidak ditemukan.');
        }

        Session::put('active_branch_id', $branch->id);

        return redirect()->back()
            ->with('success', 'Cabang berhasil diubah.');
    }
}
