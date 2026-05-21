<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧾 {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-4">
                    <span style="font-weight:600;">Tipo:</span>
                    {{ $order->type === 'sale' ? '📤 Venta' : '📥 Compra' }}
                </div>
                <div class="mb-4">
                    <span style="font-weight:600;">Cliente/Proveedor:</span>
                    {{ $order->client_supplier ?? 'N/A' }}
                </div>
                <div class="mb-4">
                    <span style="font-weight:600;">Fecha:</span>
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </div>

                <table class="w-full text-sm mb-4">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th class="text-left p-2">Producto</th>
                            <th class="text-right p-2">Cant.</th>
                            <th class="text-right p-2">Precio</th>
                            <th class="text-right p-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="p-2">{{ $item->product->name }}</td>
                            <td class="text-right p-2">{{ $item->quantity }}</td>
                            <td class="text-right p-2">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right p-2">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right">
                    <div>Subtotal: <strong>${{ number_format($order->subtotal, 2) }}</strong></div>
                    <div>Impuesto (15%): <strong>${{ number_format($order->tax, 2) }}</strong></div>
                    <div style="font-size:1.25rem; color:#7c3aed;">
                        Total: <strong>${{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('orders.index') }}"
                       style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                        ← Volver
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>