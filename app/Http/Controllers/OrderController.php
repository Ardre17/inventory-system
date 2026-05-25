<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = collect();

        if (request()->hasAny(['client', 'type', 'from', 'to'])) {
            $query = Order::with('user', 'items')->latest();

            if (request('client')) {
                $query->where('client_supplier', 'like', '%' . request('client') . '%');
            }
            if (request('type')) {
                $query->where('type', request('type'));
            }
            if (request('from')) {
                $query->whereDate('created_at', '>=', request('from'));
            }
            if (request('to')) {
                $query->whereDate('created_at', '<=', request('to'));
            }

            $orders = $query->get();
        }

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
            'type'            => 'required|in:sale,purchase',
            'order_type'      => 'required|in:local,encomienda,supermercado',
            'client_supplier' => 'nullable|string|max:255',
            'products'        => 'required|array|min:1',
            'products.*.id'   => 'required|exists:products,id',
            'products.*.qty'  => 'required|integer|min:1',
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
                    'product_id'      => $product->id,
                    'quantity'        => $item['qty'],
                    'quantity_sent'   => 0,
                    'dispatch_status' => 'pending',
                    'unit_price'      => $price,
                    'subtotal'        => $sub,
                ];
            }

            $tax   = $subtotal * 0.15;
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number'        => 'ORD-' . strtoupper(uniqid()),
                'user_id'             => auth()->id(),
                'type'                => $request->type,
                'order_type'          => $request->order_type,
                'status'              => 'pending',
                'client_supplier'     => $request->client_supplier,
                'client_order_number' => $request->client_order_number,
                'barcode'             => 'ORD-' . strtoupper(uniqid()),
                'subtotal'            => $subtotal,
                'tax'                 => $tax,
                'total'               => $total,
                'notes'               => $request->notes,
            ]);

            $order->items()->createMany($items);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Orden creada correctamente');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function dispatch(Request $request, Order $order)
    {
        $request->validate([
            'item_id'                  => 'required|exists:order_items,id',
            'quantity_sent'            => 'required|integer|min:0',
            'pallet_number'            => 'nullable|string|max:50',
            'pucho'                    => 'nullable|integer|min:0',
            'dispatch_expiration_date' => 'nullable|date',
        ]);

        $item = OrderItem::findOrFail($request->item_id);

        DB::transaction(function () use ($item, $request, $order) {
            $sent = $request->quantity_sent;

            if ($item->quantity_sent > 0) {
                $item->product->increment('stock', $item->quantity_sent);
            }

            if ($sent > 0) {
                $item->product->decrement('stock', $sent);
            }

            if ($sent == 0) {
                $status = 'none';
            } elseif ($sent >= $item->quantity) {
                $status = 'complete';
            } else {
                $status = 'partial';
            }

            $item->update([
                'quantity_sent'            => $sent,
                'dispatch_status'          => $status,
                'pallet_number'            => $request->pallet_number,
                'pucho'                    => $request->pucho ?? 0,
                'dispatch_expiration_date' => $request->dispatch_expiration_date,
            ]);

            $order->load('items');
            if ($order->items->every(fn($i) => $i->dispatch_status !== 'pending')) {
                $order->update(['status' => 'completed']);
            } else {
                $order->update(['status' => 'pending']);
            }
        });

        return response()->json(['success' => true]);
    }

    public function updateItem(Request $request, Order $order)
    {
        $request->validate([
            'item_id'  => 'required|exists:order_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = OrderItem::findOrFail($request->item_id);
        $item->update([
            'quantity' => $request->quantity,
            'subtotal' => $item->unit_price * $request->quantity,
        ]);

        $subtotal = $order->items()->sum('subtotal');
        $tax      = $subtotal * 0.15;
        $order->update([
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'total'    => $subtotal + $tax,
        ]);

        return response()->json(['success' => true]);
    }

    public function findByBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)->first();
        if (!$product) return response()->json(['found' => false]);
        return response()->json([
            'found' => true,
            'id'    => $product->id,
            'name'  => $product->name,
            'stock' => $product->stock,
            'unit'  => $product->unit,
        ]);
    }

    public function edit(Order $order)
    {
        return redirect()->route('orders.index');
    }

    public function update(Request $request, Order $order)
    {
        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->quantity_sent > 0) {
                    $item->product->increment('stock', $item->quantity_sent);
                }
            }
            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('orders.index')
            ->with('success', 'Orden eliminada y stock revertido correctamente');
    }
}
