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

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @forelse($orders as $order)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $order->order_number }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $order->type === 'sale' ? '📤 Venta' : '📥 Compra' }}
                            @if($order->client_supplier)
                            | {{ $order->client_supplier }}
                            @endif
                        </div>
                        <div class="text-sm mt-1">
                            <span style="color:#7c3aed; font-weight:600;">${{ number_format($order->total, 2) }}</span>
                            <span class="text-gray-400 mx-2">|</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('orders.show', $order) }}"
                           style="background-color:#2563eb; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                            👁️
                        </a>
                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta orden?')">
                            @csrf
                            @method('DELETE')
                            <button style="background-color:#ef4444; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem;">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    No hay órdenes aún. ¡Crea la primera!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>