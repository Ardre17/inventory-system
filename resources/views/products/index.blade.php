<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏷️ Productos
            </h2>
            <a href="{{ route('products.create') }}"
               style="background-color:#16a34a; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nuevo Producto
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
                @forelse($products as $product)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div style="flex:1;">
                        <div class="font-semibold text-gray-800">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">
                            📦 {{ $product->category->name ?? 'Sin categoría' }}
                            @if($product->supplier)
                            | 🏭 {{ $product->supplier->name }}
                            @endif
                        </div>
                        <div class="text-sm mt-1">
                            <span style="color:#16a34a; font-weight:600;">${{ number_format($product->price, 2) }}</span>
                            <span class="text-gray-400 mx-2">|</span>
                            <span style="color:{{ $product->isLowStock() ? '#ef4444' : '#374151' }}; font-weight:600;">
                                Stock: {{ $product->stock }} {{ $product->unit }}
                                @if($product->isLowStock()) ⚠️ @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('products.edit', $product) }}"
                           style="background-color:#facc15; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                            ✏️
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este producto?')">
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
                    No hay productos aún. ¡Crea el primero!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>