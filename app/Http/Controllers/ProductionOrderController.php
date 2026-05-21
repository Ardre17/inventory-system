<?php
namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductRawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function index()
    {
        $orders = ProductionOrder::with('user', 'items.product')->latest()->get();
        return view('production-orders.index', compact('orders'));
    }

    public function create()
    {
        $products     = Product::where('active', true)->with('category', 'rawMaterials')->get();
        $rawMaterials = RawMaterial::where('active', true)->get();
        return view('production-orders.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'          => 'required|date',
            'products'      => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.qty'=> 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $order = ProductionOrder::create([
                'order_number' => 'PROD-' . strtoupper(uniqid()),
                'user_id'      => auth()->id(),
                'date'         => $request->date,
                'status'       => 'in_progress',
                'notes'        => $request->notes,
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                // Crear item de producción
                ProductionOrderItem::create([
                    'production_order_id' => $order->id,
                    'product_id'          => $product->id,
                    'quantity'            => $item['qty'],
                    'sticker'             => $item['sticker'] ?? 'no_usa',
                    'precinto'            => $item['precinto'] ?? 'no_usa',
                    'label_type'          => $item['label_type'] ?? 'local',
                    'sticker_idioma'      => $item['sticker_idioma'] ?? 'no_usa',
                ]);

                // Descontar materia prima
                $rawMaterials = ProductRawMaterial::where('product_id', $product->id)->get();
                foreach ($rawMaterials as $rm) {
                    $totalConsumed = $rm->quantity_per_unit * $item['qty'];
                    RawMaterial::where('id', $rm->raw_material_id)
                        ->decrement('stock', $totalConsumed);
                }
            }
        });

        return redirect()->route('production-orders.index')
            ->with('success', 'Orden de producción creada correctamente');
    }

    public function show(ProductionOrder $productionOrder)
    {
        $productionOrder->load('items.product.category', 'user');
        return view('production-orders.show', compact('productionOrder'));
    }

    public function complete(ProductionOrder $productionOrder)
    {
        DB::transaction(function () use ($productionOrder) {
            foreach ($productionOrder->items as $item) {
                // Sumar stock al producto terminado
                $item->product->increment('stock', $item->quantity);
            }
            $productionOrder->update(['status' => 'completed']);
        });

        return redirect()->route('production-orders.show', $productionOrder)
            ->with('success', 'Producción completada y stock actualizado');
    }

    public function edit(ProductionOrder $productionOrder)
    {
        return redirect()->route('production-orders.index');
    }

    public function update(Request $request, ProductionOrder $productionOrder)
    {
        return redirect()->route('production-orders.index');
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        $productionOrder->delete();
        return redirect()->route('production-orders.index')
            ->with('success', 'Orden eliminada correctamente');
    }
}