<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductRawMaterial;
use Illuminate\Http\Request;

class ProductRawMaterialController extends Controller
{
    public function index()
    {
        $products = Product::with('rawMaterials')->orderBy('name')->get();
        return view('product-raw-materials.index', compact('products'));
    }

    public function edit(Product $product)
    {
        $allRawMaterials = RawMaterial::orderBy('name')->get();
        return view('product-raw-materials.edit', compact('product', 'allRawMaterials'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'materials'                => 'required|array',
            'materials.*.raw_material_id' => 'required|exists:raw_materials,id',
            'materials.*.quantity'     => 'required|numeric|min:0.001',
            'materials.*.stock'        => 'required|numeric|min:0',
        ]);

        // Primero devolver stock anterior al stock general
        foreach ($product->rawMaterials as $rm) {
            $pivot = ProductRawMaterial::where('product_id', $product->id)
                ->where('raw_material_id', $rm->id)
                ->first();
            if ($pivot && $pivot->stock > 0) {
                RawMaterial::where('id', $rm->id)->increment('stock', $pivot->stock);
            }
        }

        // Eliminar asignaciones anteriores
        ProductRawMaterial::where('product_id', $product->id)->delete();

        // Crear nuevas asignaciones y descontar del stock general
        foreach ($request->materials as $mat) {
            if (empty($mat['raw_material_id'])) continue;

            $stock = (float) $mat['stock'];

            ProductRawMaterial::create([
                'product_id'       => $product->id,
                'raw_material_id'  => $mat['raw_material_id'],
                'quantity_per_unit'=> $mat['quantity'],
                'quantity'         => $mat['quantity'],
                'stock'            => $stock,
            ]);

            // Descontar del stock general
            if ($stock > 0) {
                RawMaterial::where('id', $mat['raw_material_id'])
                    ->decrement('stock', $stock);
            }
        }

        return redirect()->route('product-raw-materials.index')
            ->with('success', 'Materias primas actualizadas correctamente');
    }
}
