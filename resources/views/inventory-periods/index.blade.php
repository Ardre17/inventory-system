<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Inventarios
            </h2>
            <a href="{{ route('inventory-periods.create') }}"
               style="background-color:#ea580c; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nuevo Inventario
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
                @forelse($periods as $period)
                <div class="flex items-center justify-between p-4 border-b hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $period->name }}</div>
                        <div class="text-sm text-gray-500">
                            📅 {{ \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') }}
                            al {{ \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="text-sm mt-1">
                            <span style="background:{{ $period->status === 'open' ? '#dcfce7' : '#f3f4f6' }}; color:{{ $period->status === 'open' ? '#166534' : '#374151' }}; padding:0.1rem 0.5rem; border-radius:1rem; font-size:0.75rem;">
                                {{ $period->status === 'open' ? '🟢 Abierto' : '🔒 Cerrado' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('inventory-periods.show', $period) }}"
                           style="background-color:#ea580c; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                            👁️ Ver
                        </a>
                        <form action="{{ route('inventory-periods.destroy', $period) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este inventario?')">
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
                    No hay inventarios aún. ¡Crea el primero!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>