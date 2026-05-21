<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🧪 Materias Primas
            </h2>
            <a href="{{ route('raw-materials.create') }}"
               style="background-color:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                + Nueva Materia Prima
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
                @forelse($rawMaterials as $rm)
                @php $lowStock = $rm->isLowStock(); @endphp
                <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1rem; {{ $lowStock ? 'border-left:4px solid #ef4444;' : 'border-left:4px solid #16a34a;' }}">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:1rem; color:#111827;">
                                {{ $rm->name }}
                                @if($rm->lot)
                                <span style="font-size:0.75rem; color:#9ca3af; font-weight:400;"> · Lote: {{ $rm->lot }}</span>
                                @endif
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-top:0.75rem; font-size:0.85rem;">
                                <div>
                                    <span style="color:#6b7280;">Stock actual</span><br>
                                    <span style="font-weight:700; color:{{ $lowStock ? '#ef4444' : '#16a34a' }};">
                                        {{ number_format($rm->stock, 2) }} {{ $rm->unit }}
                                        @if($lowStock) ⚠️ @endif
                                    </span>
                                </div>
                                <div>
                                    <span style="color:#6b7280;">Stock mínimo</span><br>
                                    <span style="font-weight:600;">{{ number_format($rm->stock_min, 2) }} {{ $rm->unit }}</span>
                                </div>
                                <div>
                                    <span style="color:#6b7280;">Costo</span><br>
                                    <span style="font-weight:600; color:#2563eb;">${{ number_format($rm->cost, 2) }}</span>
                                </div>
                                <div>
                                    <span style="color:#6b7280;">Proveedor</span><br>
                                    <span style="font-weight:600;">{{ $rm->supplier?->name ?? '—' }}</span>
                                </div>
                                @if($rm->expiration_date)
                                <div>
                                    <span style="color:#6b7280;">Vencimiento</span><br>
                                    <span style="font-weight:600; color:{{ $rm->expiration_date->isPast() ? '#ef4444' : '#374151' }};">
                                        📅 {{ $rm->expiration_date->format('d/m/Y') }}
                                        @if($rm->expiration_date->isPast()) ⚠️ Vencido @endif
                                    </span>
                                </div>
                                @endif
                                @if($rm->description)
                                <div>
                                    <span style="color:#6b7280;">Descripción</span><br>
                                    <span style="font-weight:600;">{{ $rm->description }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex; gap:0.5rem; margin-left:1rem;">
                            <a href="{{ route('raw-materials.edit', $rm) }}"
                               style="background-color:#facc15; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">
                                ✏️
                            </a>
                            <form action="{{ route('raw-materials.destroy', $rm) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar {{ $rm->name }}?')">
                                @csrf @method('DELETE')
                                <button style="background-color:#ef4444; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="background:white; padding:3rem; text-align:center; color:#9ca3af; border-radius:0.75rem;">
                    No hay materias primas aún. ¡Crea la primera!
                </div>
                @endforelse
            </div>

            <div style="margin-top:1rem;">
                {{ $rawMaterials->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
