<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\BoxMovement;
use Illuminate\Http\Request;

class BoxMovementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'box_id' => 'required|exists:boxes,id',
            'type' => 'required|in:entrada,salida',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'observation' => 'nullable|string'
        ]);

        $box = Box::findOrFail($request->box_id);

        if (
            $request->type === 'salida'
            && $box->stock < $request->quantity
        ) {
            return back()->with(
                'error',
                'No existe stock suficiente.'
            );
        }

        BoxMovement::create([
            'box_id' => $box->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'observation' => $request->observation
        ]);

        if ($request->type === 'entrada') {
            $box->increment('stock', $request->quantity);
        } else {
            $box->decrement('stock', $request->quantity);
        }

        return redirect()
            ->route('boxes.index')
            ->with('success', 'Movimiento registrado.');
    }
}