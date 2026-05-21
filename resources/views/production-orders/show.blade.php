<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏭 {{ $productionOrder->order_number }}
            </h2>
            <span style="background:{{ $productionOrder->status_color }}; color:white; padding:0.25rem 0.75rem; border-radius:1rem; font-size:0.875rem; font-weight:600;">
                {{ $productionOrder->status_label }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
            @endif

            <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem; margin-bottom:1rem;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; font-size:0.9rem;">
                    <div>
                        <span style="color:#6b7280;">Fecha</span><br>
                        <span style="font-weight:600;">📅 {{ $productionOrder->date->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span style="color:#6b7280;">Creado por</span><br>
                        <span style="font-weight:600;">👤 {{ $productionOrder->user->name }}</span>
                    </div>
                    @if($productionOrder->notes)
                    <div style="grid-column:span 2;">
                        <span style="color:#6b7280;">Notas</span><br>
                        <span style="font-weight:600;">{{ $productionOrder->notes }}</span>
                    </div>
                    @endif
                </div>

                <div style="font-weight:700; color:#374151; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                    📦 Productos Producidos
                </div>

                @foreach($productionOrder->items as $item)
                <div style="background:#f9fafb; border-radius:0.5rem; padding:1rem; margin-bottom:0.75rem;">
                    <div style="font-weight:700; font-size:1rem; margin-bottom:0.5rem;">
                        {{ $item->product->name }}
                        <span style="color:#2563eb; font-size:0.9rem;">({{ $item->quantity }} unidades)</span>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; font-size:0.85rem;">
                        <div>
                            <span style="color:#6b7280;">Sticker tapa</span><br>
                            <span style="font-weight:600;">{{ $item->sticker == 'no_usa' ? 'No usa' : 'Sticker '.$item->sticker }}</span>
                        </div>
                        <div>
                            <span style="color:#6b7280;">Precinto</span><br>
                            <span style="font-weight:600;">{{ $item->precinto == 'no_usa' ? 'No usa' : 'Precinto '.$item->precinto }}</span>
                        </div>
                        <div>
                            <span style="color:#6b7280;">Tipo etiqueta</span><br>
                            <span style="font-weight:600;">
                                {{ $item->label_type == 'local' ? '🇪🇸 Local' : ($item->label_type == 'ingles' ? '🇺🇸 Inglés' : '🇧🇷 Portugués') }}
                            </span>
                        </div>
                        <div>
                            <span style="color:#6b7280;">Sticker idioma</span><br>
                            <span style="font-weight:600;">{{ $item->sticker_idioma == 'no_usa' ? 'No usa' : ucfirst($item->sticker_idioma) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach

                <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                    @if($productionOrder->status !== 'completed')
                    <form action="{{ route('production-orders.complete', $productionOrder) }}" method="POST"
                          onsubmit="return confirm('¿Completar producción? Esto sumará el stock a los productos.')">
                        @csrf
                        <button style="background-color:#16a34a; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            ✅ Completar Producción
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('production-orders.index') }}"
                       style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                        ← Volver
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>