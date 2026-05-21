<?php
namespace App\Http\Controllers;

use App\Models\InventoryPeriod;
use App\Models\InventoryItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryPeriodController extends Controller
{
    public function index()
    {
        $periods = InventoryPeriod::with('user')->latest()->get();
        return view('inventory-periods.index', compact('periods'));
    }

    public function create()
    {
        return view('inventory-periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $period = InventoryPeriod::create([
            'name'       => $request->name,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'notes'      => $request->notes,
            'user_id'    => auth()->id(),
            'status'     => 'open',
        ]);

        // Crear items automáticamente para todos los productos
        $products = Product::where('active', true)->get();
        foreach ($products as $product) {
            InventoryItem::create([
                'inventory_period_id' => $period->id,
                'product_id'          => $product->id,
                'initial_stock'       => $product->stock,
                'entries'             => 0,
                'exits'               => 0,
                'final_stock'         => $product->stock,
                'physical_count'      => null,
                'difference'          => 0,
            ]);
        }

        return redirect()->route('inventory-periods.show', $period)
            ->with('success', 'Periodo de inventario creado correctamente');
    }

    public function show(InventoryPeriod $inventoryPeriod)
    {
        $inventoryPeriod->load('items.product.category');
        return view('inventory-periods.show', compact('inventoryPeriod'));
    }

    public function update(Request $request, InventoryPeriod $inventoryPeriod)
    {
        // Guardar conteo físico
        foreach ($request->physical_count as $itemId => $count) {
            $item = InventoryItem::findOrFail($itemId);
            $finalStock = $item->initial_stock + $item->entries - $item->exits;
            $item->update([
                'physical_count' => $count,
                'final_stock'    => $finalStock,
                'difference'     => $count - $finalStock,
            ]);

            // Actualizar stock real del producto
            $item->product->update(['stock' => $count]);
        }

        if ($request->action === 'close') {
            $inventoryPeriod->update(['status' => 'closed']);
            return redirect()->route('inventory-periods.index')
                ->with('success', 'Inventario cerrado correctamente');
        }

        return redirect()->route('inventory-periods.show', $inventoryPeriod)
            ->with('success', 'Conteo guardado correctamente');
    }

    public function edit(InventoryPeriod $inventoryPeriod)
    {
        return redirect()->route('inventory-periods.show', $inventoryPeriod);
    }

    public function destroy(InventoryPeriod $inventoryPeriod)
    {
        $inventoryPeriod->delete();
        return redirect()->route('inventory-periods.index')
            ->with('success', 'Periodo eliminado correctamente');
    }
}