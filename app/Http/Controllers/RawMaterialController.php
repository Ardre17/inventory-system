<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterial::with('supplier')
            ->orderBy('name')
            ->paginate(20);
        return view('raw-materials.index', compact('rawMaterials'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('raw-materials.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'unit'            => 'required|string|max:50',
            'stock'           => 'required|numeric|min:0',
            'stock_min'       => 'required|numeric|min:0',
            'cost'            => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'lot'             => 'nullable|string|max:100',
            'expiration_date' => 'nullable|date',
            'image_url'       => 'nullable|url|max:500',
        ]);

        RawMaterial::create($request->all());

        return redirect()->route('raw-materials.index')
                         ->with('success', 'Materia prima creada correctamente.');
    }

    public function show(RawMaterial $rawMaterial)
    {
        $rawMaterial->load('supplier', 'products');
        return view('raw-materials.show', compact('rawMaterial'));
    }

    public function edit(RawMaterial $rawMaterial)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('raw-materials.edit', compact('rawMaterial', 'suppliers'));
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'unit'            => 'required|string|max:50',
            'stock'           => 'required|numeric|min:0',
            'stock_min'       => 'required|numeric|min:0',
            'cost'            => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'lot'             => 'nullable|string|max:100',
            'expiration_date' => 'nullable|date',
            'image_url'       => 'nullable|url|max:500',
        ]);

        $rawMaterial->update($request->all());

        return redirect()->route('raw-materials.index')
                         ->with('success', 'Materia prima actualizada correctamente.');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('raw-materials.index')
                         ->with('success', 'Materia prima eliminada.');
    }
}
