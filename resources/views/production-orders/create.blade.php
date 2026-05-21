<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nueva Orden de Producción
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('production-orders.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha *</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Notas</label>
                            <input type="text" name="notes"
                                   class="w-full border rounded-lg px-3 py-2"
                                   placeholder="Notas opcionales">
                        </div>
                    </div>

                    <div style="font-weight:700; color:#374151; margin:1rem 0; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                        📦 Productos a Producir
                    </div>

                    <div id="productLines">
                        <div class="product-line" style="background:#f9fafb; border-radius:0.5rem; padding:1rem; margin-bottom:1rem;">

                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Producto *</label>
                                    <select name="products[0][id]" class="w-full border rounded-lg px-3 py-2 mt-1">
                                        <option value="">Selecciona...</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Cantidad *</label>
                                    <input type="number" name="products[0][qty]" min="1" value="1"
                                           class="w-full border rounded-lg px-3 py-2 mt-1">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Sticker tapa</label>
                                    <select name="products[0][sticker]" class="w-full border rounded-lg px-3 py-2 mt-1">
                                        <option value="no_usa">No usa</option>
                                        <option value="30mm">Sticker 30mm</option>
                                        <option value="43mm">Sticker 43mm</option>
                                        <option value="50mm">Sticker 50mm</option>
                                        <option value="55mm">Sticker 55mm</option>
                                        <option value="65mm">Sticker 65mm</option>
                                        <option value="70mm">Sticker 70mm</option>
                                        <option value="85mm">Sticker 85mm</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Precinto</label>
                                    <select name="products[0][precinto]" class="w-full border rounded-lg px-3 py-2 mt-1">
                                        <option value="no_usa">No usa</option>
                                        <option value="74x30">Precinto 74x30</option>
                                        <option value="94x30">Precinto 94x30</option>
                                        <option value="97x30">Precinto 97x30</option>
                                        <option value="106x30">Precinto 106x30</option>
                                        <option value="118x30">Precinto 118x30</option>
                                        <option value="128x30">Precinto 128x30</option>
                                        <option value="138x30">Precinto 138x30</option>
                                        <option value="175x30">Precinto 175x30</option>
                                        <option value="aliño_2lt">Precinto aliño 2lt</option>
                                        <option value="aliños_pequeños">Precintos aliños pequeños</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Tipo etiqueta</label>
                                    <select name="products[0][label_type]" class="w-full border rounded-lg px-3 py-2 mt-1">
                                        <option value="local">🇪🇸 Local</option>
                                        <option value="ingles">🇺🇸 Inglés</option>
                                        <option value="portugues">🇧🇷 Portugués</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Sticker idioma</label>
                                    <select name="products[0][sticker_idioma]" class="w-full border rounded-lg px-3 py-2 mt-1">
                                        <option value="no_usa">No usa</option>
                                        <option value="español">Español</option>
                                        <option value="portugues">Portugués</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button type="button" onclick="addProductLine()"
                            style="background:#e5e7eb; color:#374151; padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.875rem; margin-bottom:1.5rem;">
                        + Agregar otro producto
                    </button>

                    <div class="flex gap-3">
                        <button type="submit"
                                style="background-color:#2563eb; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Crear Orden
                        </button>
                        <a href="{{ route('production-orders.index') }}"
                           style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let lineCount = 1;
        const products = @json($products);

        function addProductLine() {
            const container = document.getElementById('productLines');
            const div = document.createElement('div');
            div.className = 'product-line';
            div.style = 'background:#f9fafb; border-radius:0.5rem; padding:1rem; margin-bottom:1rem;';
            const i = lineCount;
            div.innerHTML = `
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Producto *</label>
                        <select name="products[${i}][id]" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="">Selecciona...</option>
                            ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Cantidad *</label>
                        <input type="number" name="products[${i}][qty]" min="1" value="1"
                               class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Sticker tapa</label>
                        <select name="products[${i}][sticker]" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="no_usa">No usa</option>
                            <option value="30mm">Sticker 30mm</option>
                            <option value="43mm">Sticker 43mm</option>
                            <option value="50mm">Sticker 50mm</option>
                            <option value="55mm">Sticker 55mm</option>
                            <option value="65mm">Sticker 65mm</option>
                            <option value="70mm">Sticker 70mm</option>
                            <option value="85mm">Sticker 85mm</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Precinto</label>
                        <select name="products[${i}][precinto]" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="no_usa">No usa</option>
                            <option value="74x30">Precinto 74x30</option>
                            <option value="94x30">Precinto 94x30</option>
                            <option value="97x30">Precinto 97x30</option>
                            <option value="106x30">Precinto 106x30</option>
                            <option value="118x30">Precinto 118x30</option>
                            <option value="128x30">Precinto 128x30</option>
                            <option value="138x30">Precinto 138x30</option>
                            <option value="175x30">Precinto 175x30</option>
                            <option value="aliño_2lt">Precinto aliño 2lt</option>
                            <option value="aliños_pequeños">Precintos aliños pequeños</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Tipo etiqueta</label>
                        <select name="products[${i}][label_type]" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="local">🇪🇸 Local</option>
                            <option value="ingles">🇺🇸 Inglés</option>
                            <option value="portugues">🇧🇷 Portugués</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.85rem; font-weight:600; color:#374151;">Sticker idioma</label>
                        <select name="products[${i}][sticker_idioma]" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="no_usa">No usa</option>
                            <option value="español">Español</option>
                            <option value="portugues">Portugués</option>
                        </select>
                    </div>
                </div>
                <div style="text-align:right; margin-top:0.5rem;">
                    <button type="button" onclick="this.closest('.product-line').remove()"
                            style="background:#fee2e2; color:#ef4444; padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.8rem;">
                        ✕ Quitar
                    </button>
                </div>
            `;
            container.appendChild(div);
            lineCount++;
        }
    </script>
</x-app-layout>