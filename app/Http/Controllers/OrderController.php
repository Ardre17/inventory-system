<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('active', true)->with('category')->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'             => 'required|in:sale,purchase',
            'client_supplier'  => 'nullable|string|max:255',
            'products'         => 'required|array|min:1',
            'products.*.id'    => 'required|exists:products,id',
            'products.*.qty'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $items    = [];

            foreach ($request->products as $item) {
                $product  = Product::findOrFail($item['id']);
                $price    = $product->price;
                $sub      = $price * $item['qty'];
                $subtotal += $sub;
                $items[]  = [
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'unit_price' => $price,
                    'subtotal'   => $sub,
                ];

                // Actualizar stock
                if ($request->type === 'sale') {
                    $product->decrement('stock', $item['qty']);
                } else {
                    $product->increment('stock', $item['qty']);
                }
            }

            $tax   = $subtotal * 0.15;
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
                'user_id'         => auth()->id(),
                'type'            => $request->type,
                'status'          => 'completed',
                'client_supplier' => $request->client_supplier,
                'subtotal'        => $subtotal,
                'tax'             => $tax,
                'total'           => $total,
                'notes'           => $request->notes,
            ]);

            $order->items()->createMany($items);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Orden creada y stock actualizado correctamente');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order) { return redirect()->route('orders.index'); }
    public function update(Request $request, Order $order) { return redirect()->route('orders.index'); }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Orden eliminada correctamente');
    }
}