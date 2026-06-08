<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\BoxMovement;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function show(Box $box)
{
    $movements = $box->movements()
        ->with('user')
        ->latest()
        ->paginate(30);

    return view(
        'boxes.show',
        compact(
            'box',
            'movements'
        )
    );
}
public function edit(Box $box)
{
    return view(
        'boxes.edit',
        compact('box')
    );
}

public function update(Request $request, Box $box)
{
    $request->validate([
        'code' => 'required|unique:boxes,code,' . $box->id,
        'name' => 'required',
        'stock' => 'required|integer|min:0',
        'minimum_stock' => 'required|integer|min:0',
    ]);

    $box->update([
        'code' => $request->code,
        'name' => $request->name,
        'stock' => $request->stock,
        'minimum_stock' => $request->minimum_stock,
    ]);

    return redirect()
        ->route('boxes.index')
        ->with('success', 'Caja actualizada correctamente');
}
    public function create()
{
    return view('boxes.create');
}

public function store(Request $request)
{
    $request->validate([
        'code' => 'required|unique:boxes',
        'name' => 'required',
        'stock' => 'required|integer|min:0',
        'minimum_stock' => 'required|integer|min:0'
    ]);

    Box::create([
        'code' => $request->code,
        'name' => $request->name,
        'stock' => $request->stock,
        'minimum_stock' => $request->minimum_stock,
        'active' => true
    ]);

    return redirect()
        ->route('boxes.index')
        ->with('success','Caja creada correctamente');
}

    public function index()
    {
        $boxes = Box::orderBy('name')->get();

        $movements = BoxMovement::with([
            'box',
            'user'
        ])
        ->latest()
        ->take(20)
        ->get();

        return view(
            'boxes.index',
            compact(
                'boxes',
                'movements'
            )
        );
    }
}