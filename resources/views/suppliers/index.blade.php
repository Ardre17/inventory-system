<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏭 Proveedores
            </h2>
            <a href="{{ route('suppliers.create') }}"
               style="background-color:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nuevo Proveedor
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
                @forelse($suppliers as $supplier)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $supplier->name }}</div>
                        <div class="text-sm text-gray-500">{{ $supplier->contact ?? 'Sin contacto' }}</div>
                        <div class="text-sm text-gray-500">📞 {{ $supplier->phone ?? 'Sin teléfono' }} | ✉️ {{ $supplier->email ?? 'Sin email' }}</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('suppliers.edit', $supplier) }}"
                           style="background-color:#facc15; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                            ✏️
                        </a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este proveedor?')">
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
                    No hay proveedores aún. ¡Crea el primero!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>