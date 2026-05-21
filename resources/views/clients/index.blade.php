<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👥 Clientes
            </h2>
            <a href="{{ route('clients.create') }}"
               style="background-color:#0891b2; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nuevo Cliente
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
                @forelse($clients as $client)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $client->name }}</div>
                        <div class="text-sm text-gray-500">
                            📞 {{ $client->phone ?? 'Sin teléfono' }}
                            | ✉️ {{ $client->email ?? 'Sin email' }}
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('clients.edit', $client) }}"
                           style="background-color:#facc15; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                            ✏️
                        </a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este cliente?')">
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
                    No hay clientes aún. ¡Crea el primero!
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>