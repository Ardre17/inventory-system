<?php
namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductRawMaterial;
use App\Models\Supply;
use App\Models\SupplyMovement;
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
        $products     = Product::where('active', true)->with('category')->get();
        $rawMaterials = RawMaterial::all();
        return view('production-orders.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'products'       => 'required|array|min:1',
            'products.*.id'  => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
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
                if (empty($item['id']) || empty($item['qty'])) continue;

                $product  = Product::findOrFail($item['id']);
                $quantity = (int) $item['qty'];

                ProductionOrderItem::create([
                    'production_order_id' => $order->id,
                    'product_id'          => $product->id,
                    'quantity'            => $quantity,
                    'sticker'             => $item['sticker'] ?? 'no_usa',
                    'precinto'            => $item['precinto'] ?? 'no_usa',
                    'label_type'          => $item['label_type'] ?? 'local',
                    'sticker_idioma'      => $item['sticker_idioma'] ?? 'no_usa',
                ]);
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
                $quantity = $item->quantity;

                // 1. Sumar stock producto terminado
                $item->product->increment('stock', $quantity);

                // 2. Descontar materia prima
                // Descontar materia prima SOLO al completar
$rawMaterials = ProductRawMaterial::where('product_id', $item->product_id)->get();
foreach ($rawMaterials as $rm) {
    $totalConsumed = $rm->quantity_per_unit * $quantity;
    RawMaterial::where('id', $rm->raw_material_id)
        ->decrement('stock', $totalConsumed);
    // Descontar también del stock asignado al producto
    $rm->decrement('stock', $totalConsumed);
}

                // 3. Descontar suministros
                $this->discountSupply($item, $quantity, $productionOrder->order_number);
            }

            $productionOrder->update(['status' => 'completed']);
        });

        return redirect()->route('production-orders.show', $productionOrder)
            ->with('success', 'Producción completada y stock actualizado');
    }

    private function discountSupply(ProductionOrderItem $item, int $quantity, string $reference)
    {
        // Sticker de tapa (buscar solo por type y variant)
        if ($item->sticker && $item->sticker !== 'no_usa') {
            $supply = Supply::where('type', 'sticker')
                ->where('variant', $item->sticker)
                ->first();
            if ($supply) {
                $supply->decrement('stock', $quantity);
                SupplyMovement::create([
                    'supply_id'     => $supply->id,
                    'movement_type' => 'exit',
                    'rolls'         => 0,
                    'quantity'      => -$quantity,
                    'reference'     => 'Orden #' . $reference,
                    'notes'         => 'Producción: ' . $item->product->name,
                ]);
            }
        }

        // Precinto (buscar solo por type y variant)
        if ($item->precinto && $item->precinto !== 'no_usa') {
            $supply = Supply::where('type', 'precinto')
                ->where('variant', $item->precinto)
                ->first();
            if ($supply) {
                $supply->decrement('stock', $quantity);
                SupplyMovement::create([
                    'supply_id'     => $supply->id,
                    'movement_type' => 'exit',
                    'rolls'         => 0,
                    'quantity'      => -$quantity,
                    'reference'     => 'Orden #' . $reference,
                    'notes'         => 'Producción: ' . $item->product->name,
                ]);
            }
        }

        // Etiqueta (buscar por product_id y variant porque cada producto tiene la suya)
        if ($item->label_type && $item->label_type !== 'no_usa') {
            $supply = Supply::where('type', 'etiqueta')
                ->where('variant', $item->label_type)
                ->where('product_id', $item->product_id)
                ->first();
            if ($supply) {
                $supply->decrement('stock', $quantity);
                SupplyMovement::create([
                    'supply_id'     => $supply->id,
                    'movement_type' => 'exit',
                    'rolls'         => 0,
                    'quantity'      => -$quantity,
                    'reference'     => 'Orden #' . $reference,
                    'notes'         => 'Producción: ' . $item->product->name,
                ]);
            }
        }

        // Sticker idioma
        if ($item->sticker_idioma && $item->sticker_idioma !== 'no_usa') {
            $supply = Supply::where('type', 'sticker_idioma')
                ->where('variant', $item->sticker_idioma)
                ->first();
            if ($supply) {
                $supply->decrement('stock', $quantity);
                SupplyMovement::create([
                    'supply_id'     => $supply->id,
                    'movement_type' => 'exit',
                    'rolls'         => 0,
                    'quantity'      => -$quantity,
                    'reference'     => 'Orden #' . $reference,
                    'notes'         => 'Producción: ' . $item->product->name,
                ]);
            }
        }
    }

    private function revertSupply(ProductionOrderItem $item, int $quantity, string $reference)
    {
        // Sticker tapa
        if ($item->sticker && $item->sticker !== 'no_usa') {
            $supply = Supply::where('type', 'sticker')
                ->where('variant', $item->sticker)
                ->first();
            if ($supply) {
                $supply->increment('stock', $quantity);
                SupplyMovement::where('supply_id', $supply->id)
                    ->where('reference', 'Orden #' . $reference)
                    ->delete();
            }
        }

        // Precinto
        if ($item->precinto && $item->precinto !== 'no_usa') {
            $supply = Supply::where('type', 'precinto')
                ->where('variant', $item->precinto)
                ->first();
            if ($supply) {
                $supply->increment('stock', $quantity);
                SupplyMovement::where('supply_id', $supply->id)
                    ->where('reference', 'Orden #' . $reference)
                    ->delete();
            }
        }

        // Etiqueta por producto
        if ($item->label_type && $item->label_type !== 'no_usa') {
            $supply = Supply::where('type', 'etiqueta')
                ->where('variant', $item->label_type)
                ->where('product_id', $item->product_id)
                ->first();
            if ($supply) {
                $supply->increment('stock', $quantity);
                SupplyMovement::where('supply_id', $supply->id)
                    ->where('reference', 'Orden #' . $reference)
                    ->delete();
            }
        }

        // Sticker idioma
        if ($item->sticker_idioma && $item->sticker_idioma !== 'no_usa') {
            $supply = Supply::where('type', 'sticker_idioma')
                ->where('variant', $item->sticker_idioma)
                ->first();
            if ($supply) {
                $supply->increment('stock', $quantity);
                SupplyMovement::where('supply_id', $supply->id)
                    ->where('reference', 'Orden #' . $reference)
                    ->delete();
            }
        }
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
        DB::transaction(function () use ($productionOrder) {
            if ($productionOrder->status === 'completed') {
                foreach ($productionOrder->items as $item) {
                    $quantity = $item->quantity;

                    // Devolver stock producto terminado
                    $item->product->decrement('stock', $quantity);

                    // Devolver materia prima
                    $rawMaterials = ProductRawMaterial::where('product_id', $item->product_id)->get();
                    foreach ($rawMaterials as $rm) {
                        $totalConsumed = $rm->quantity_per_unit * $quantity;
                        RawMaterial::where('id', $rm->raw_material_id)
                            ->increment('stock', $totalConsumed);
                    }

                    // Devolver suministros y eliminar movimientos
                    $this->revertSupply($item, $quantity, $productionOrder->order_number);
                }
            }

            $productionOrder->items()->delete();
            $productionOrder->delete();
        });

        return redirect()->route('production-orders.index')
            ->with('success', 'Orden eliminada y stock revertido correctamente');
    }
}
