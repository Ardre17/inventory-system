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
        $products = Product::with(['rawMaterials'])->orderBy('name')->get();
        return view('product-raw-materials.index', compact('products'));
    }

    public function edit(Product $product)
    {
        $product->load('rawMaterials');
        $allRawMaterials = RawMaterial::orderBy('name')->get();
        return view('product-raw-materials.edit', compact('product', 'allRawMaterials'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'materials'                => 'nullable|array',
            'materials.*.raw_material_id' => 'required|exists:raw_materials,id',
            'materials.*.quantity'        => 'required|numeric|min:0.001',
        ]);

        // Eliminar asignaciones anteriores y recrear
        ProductRawMaterial::where('product_id', $product->id)->delete();

        if ($request->materials) {
            foreach ($request->materials as $mat) {
                ProductRawMaterial::create([
                    'product_id'      => $product->id,
                    'raw_material_id' => $mat['raw_material_id'],
                    'quantity'        => $mat['quantity'],
                ]);
            }
        }

        return redirect()->route('product-raw-materials.index')
                         ->with('success', "Materias primas de {$product->name} actualizadas.");
    }
}
