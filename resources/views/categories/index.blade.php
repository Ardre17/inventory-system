<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📦 Categorías
            </h2>
            <a href="{{ route('categories.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600">
                + Nueva Categoría
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @forelse($categories as $category)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $category->name }}</div>
                        <div class="text-sm text-gray-500">{{ $category->description ?? 'Sin descripción' }}</div>
                        <div class="text-xs text-blue-500 mt-1">{{ $category->products_count }} productos</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('categories.edit', $category) }}"
                           class="bg-yellow-400 text-white px-3 py-1 rounded text-sm hover:bg-yellow-500">
                            ✏️
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta categoría?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    No hay categorías aún. ¡Crea la primera!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>