<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🧾 Órdenes
            </h2>
            <a href="{{ route('orders.create') }}"
               style="background-color:#7c3aed; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nueva Orden
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
            @endif

            <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1rem; margin-bottom:1rem;">
                <form method="GET" action="{{ route('orders.index') }}">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr auto auto; gap:0.75rem; align-items:end;">
                        <div>
                            <label style="font-size:0.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:0.25rem;">Cliente / Proveedor</label>
                            <input type="text" name="client" value="{{ request('client') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                                   placeholder="Buscar...">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:0.25rem;">Tipo</label>
                            <select name="type" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem;">
                                <option value="">Todos</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Venta</option>
                                <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Compra</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:0.25rem;">Desde</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:600; color:#6b7280; display:block; margin-bottom:0.25rem;">Hasta</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                        </div>
                        <button type="submit"
                                style="background-color:#7c3aed; color:white; padding:0.5rem 1.25rem; border-radius:0.5rem; font-weight:600; white-space:nowrap;">
                            Filtrar
                        </button>
                        <a href="{{ route('orders.index') }}"
                           style="background-color:#e5e7eb; color:#374151; padding:0.5rem 1.25rem; border-radius:0.5rem; font-weight:600; text-decoration:none; white-space:nowrap;">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            @if(!request()->hasAny(['client', 'type', 'from', 'to']))
            <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:3rem; text-align:center;">
                <div style="font-size:3rem; margin-bottom:1rem;">🔍</div>
                <div style="font-size:1.1rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">
                    Busca tu orden de compra o venta
                </div>
                <div style="color:#6b7280; font-size:0.9rem;">
                    Usa los filtros de fecha, cliente o tipo para encontrar tus órdenes
                </div>
            </div>

            @else
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                @forelse($orders as $order)
                <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-weight:700; font-size:1rem;">{{ $order->order_number }}</div>
                            <div style="font-size:0.85rem; color:#6b7280; margin-top:0.25rem; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                <span>{{ $order->type === 'sale' ? '📤 Venta' : '📥 Compra' }}</span>
                                <span style="background:{{ $order->order_type_color }}; color:white; padding:0.1rem 0.5rem; border-radius:1rem; font-size:0.75rem;">
                                    {{ $order->order_type_label }}
                                </span>
                                @if($order->client_supplier)
                                <span>| {{ $order->client_supplier }}</span>
                                @endif
                                @if($order->client_order_number)
                                <span style="color:#2563eb;">| Nro: {{ $order->client_order_number }}</span>
                                @endif
                            </div>
                            <div style="font-size:0.85rem; margin-top:0.25rem;">
                                <span style="color:#7c3aed; font-weight:700;">${{ number_format($order->total, 2) }}</span>
                                <span style="color:#9ca3af; margin-left:0.5rem;">| {{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div style="display:flex; gap:0.5rem;">
                            <a href="{{ route('orders.show', $order) }}"
                               style="background-color:#2563eb; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                                👁️
                            </a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta orden?')">
                                @csrf @method('DELETE')
                                <button style="background-color:#ef4444; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="background:white; border-radius:0.75rem; padding:3rem; text-align:center; color:#9ca3af;">
                    No se encontraron órdenes con esos filtros.
                </div>
                @endforelse
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
