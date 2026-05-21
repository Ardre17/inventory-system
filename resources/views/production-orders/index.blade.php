<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏭 Órdenes de Producción
            </h2>
            <a href="{{ route('production-orders.create') }}"
               style="background-color:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
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

            <div style="display:flex; flex-direction:column; gap:1rem;">
                @forelse($orders as $order)
                <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <div>
                            <div style="font-weight:700; font-size:1rem;">{{ $order->order_number }}</div>
                            <div style="font-size:0.85rem; color:#6b7280; margin-top:0.25rem;">
                                📅 {{ $order->date->format('d/m/Y') }}
                                | 👤 {{ $order->user->name }}
                            </div>
                            <div style="margin-top:0.5rem;">
                                <span style="background:{{ $order->status_color }}; color:white; padding:0.2rem 0.75rem; border-radius:1rem; font-size:0.8rem; font-weight:600;">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                        <div style="display:flex; gap:0.5rem;">
                            <a href="{{ route('production-orders.show', $order) }}"
                               style="background-color:#2563eb; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                                👁️ Ver
                            </a>
                            <form action="{{ route('production-orders.destroy', $order) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta orden?')">
                                @csrf @method('DELETE')
                                <button style="background-color:#ef4444; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Productos de la orden --}}
                    <div style="margin-top:0.75rem; border-top:1px solid #f3f4f6; padding-top:0.75rem;">
                        @foreach($order->items as $item)
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; padding:0.25rem 0;">
                            <span style="font-weight:600;">{{ $item->product->name }}</span>
                            <span style="color:#2563eb; font-weight:700;">{{ $item->quantity }} und</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div style="background:white; padding:3rem; text-align:center; color:#9ca3af; border-radius:0.75rem;">
                    No hay órdenes de producción aún.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>