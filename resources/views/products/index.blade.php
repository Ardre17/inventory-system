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

            <div style="display:flex; flex-direction:column; gap:1rem;">
                @forelse($products as $product)
                <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); overflow:hidden; display:flex;">

                    {{-- Imagen --}}
                    <div style="width:120px; min-height:120px; background:#f3f4f6; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 style="width:120px; height:120px; object-fit:cover;">
                        @else
                            <span style="font-size:2.5rem;">📦</span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="flex:1; padding:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <div style="font-size:1.1rem; font-weight:700; color:#111827;">{{ $product->name }}</div>
                            <div style="display:flex; gap:0.5rem;">
                                <a href="{{ route('products.edit', $product) }}"
                                   style="background-color:#facc15; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem; text-decoration:none;">✏️</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button style="background-color:#ef4444; color:white; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.875rem;">🗑️</button>
                                </form>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-top:0.75rem; font-size:0.85rem;">

                            {{-- Rotación --}}
                            <div>
                                <span style="color:#6b7280;">Rotación</span><br>
                                <span style="color:{{ $product->rotation_color }}; font-weight:600;">
                                    {{ ucfirst($product->rotation) }}
                                </span>
                            </div>

                            {{-- Categoría --}}
                            <div>
                                <span style="color:#6b7280;">Categoría</span><br>
                                <span style="font-weight:600;">{{ $product->category->name ?? '-' }}</span>
                            </div>

                            {{-- Stock --}}
                            <div>
                                <span style="color:#6b7280;">Stock</span><br>
                                <span style="font-weight:700; color:{{ $product->isLowStock() ? '#ef4444' : '#111827' }};">
                                    {{ $product->stock }} {{ $product->unit }}
                                    @if($product->isLowStock()) ⚠️ @endif
                                </span>
                            </div>

                            {{-- Cajas --}}
            <div>
                <span style="color:#6b7280;">Cajas</span><br>
                <span style="font-weight:700; color:#ea580c;">
                    {{ $product->boxes }}
                    <span style="font-size:0.75rem; color:#6b7280;">
                        ({{ $product->units_per_box }} u/caja)
                    </span>
                </span>
            </div>

                            {{-- Lote --}}
                            @if($product->lot)
                            <div>
                                <span style="color:#6b7280;">Lote</span><br>
                                <span style="font-weight:600;">{{ $product->lot }}</span>
                            </div>
                            @endif

                            {{-- Código de barras --}}
                            @if($product->barcode)
                            <div>
                                <span style="color:#6b7280;">Código de barras</span><br>
                                <span style="font-weight:600;">{{ $product->barcode }}</span>
                            </div>
                            @endif

                            {{-- Fecha producción --}}
                            @if($product->production_date)
                            <div>
                                <span style="color:#6b7280;">Fecha Producción</span><br>
                                <span style="font-weight:600;">📅 {{ $product->production_date->format('d/m/Y') }}</span>
                            </div>
                            @endif

                            {{-- Fecha vencimiento --}}
                            @if($product->expiration_date)
                            <div>
                                <span style="color:#6b7280;">Fecha Vencimiento</span><br>
                                <span style="font-weight:600; color:{{ $product->expiration_date->isPast() ? '#ef4444' : '#111827' }};">
                                    📅 {{ $product->expiration_date->format('d/m/Y') }}
                                    @if($product->expiration_date->isPast()) ⚠️ Vencido @endif
                                </span>
                            </div>
                            @endif

                            {{-- Fecha inventario --}}
                            @if($product->inventory_date)
                            <div>
                                <span style="color:#6b7280;">Fecha Inventario</span><br>
                                <span style="font-weight:600;">📅 {{ $product->inventory_date->format('d/m/Y') }}</span>
                            </div>
                            @endif

                            {{-- Precio --}}
                            <div>
                                <span style="color:#6b7280;">Precio</span><br>
                                <span style="font-weight:700; color:#16a34a;">${{ number_format($product->price, 2) }}</span>
                            </div>

                        </div>

                        {{-- Descripción --}}
                        @if($product->description)
                        <div style="margin-top:0.5rem; font-size:0.8rem; color:#6b7280;">
                            {{ $product->description }}
                        </div>
                        @endif

                    </div>
                </div>
                @empty
                <div style="background:white; padding:3rem; text-align:center; color:#9ca3af; border-radius:0.75rem;">
                    No hay productos aún. ¡Crea el primero!
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>