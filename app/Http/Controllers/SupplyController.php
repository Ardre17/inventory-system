<?php
namespace App\Http\Controllers;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        $stickers  = Supply::where('type','sticker')->orderBy('variant')->get();
        $precintos = Supply::where('type','precinto')->orderBy('variant')->get();
        $etiquetas = Supply::where('type','etiqueta')
                          ->with('product')
                          ->orderBy('variant')
                          ->get()
                          ->groupBy(fn($s) => $s->product?->name ?? 'Sin producto');
        return view('supplies.index', compact('stickers','precintos','etiquetas'));
    }

    public function show(Supply $supply)
    {
        $movements = $supply->movements()->latest()->paginate(20);
        return view('supplies.show', compact('supply','movements'));
    }

    public function entry(Request $request, Supply $supply)
    {
        $request->validate([
            'rolls'         => 'required|integer|min:1',
            'units_per_roll'=> 'required|integer|min:1',
            'notes'         => 'nullable|string|max:500',
        ]);
        $supply->update(['units_per_roll' => $request->units_per_roll]);
        $supply->registerEntry($request->rolls, 'Entrada manual', $request->notes ?? '');
        $total = $request->rolls * $request->units_per_roll;
        return redirect()->route('supplies.show', $supply)
                         ->with('success', "Entrada registrada: {$request->rolls} rollos × {$request->units_per_roll} u = {$total} unidades.");
    }

    public function updateMin(Request $request, Supply $supply)
    {
        $request->validate(['stock_min'=>'required|integer|min:0']);
        $supply->update(['stock_min' => $request->stock_min]);
        return back()->with('success','Stock mínimo actualizado.');
    }
}
