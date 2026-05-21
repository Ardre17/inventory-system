<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div style="font-weight:700; color:#374151; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                        📋 Información General
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Descripción</label>
                        <textarea name="description" rows="2"
                                  class="w-full border rounded-lg px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Categoría *</label>
                            <select name="category_id" class="w-full border rounded-lg px-3 py-2">
                                <option value="">Selecciona...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Proveedor</label>
                            <select name="supplier_id" class="w-full border rounded-lg px-3 py-2">
                                <option value="">Sin proveedor</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Código de barras</label>
                            <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div style="font-weight:700; color:#374151; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                        📦 Stock y Precios
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Precio *</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                   step="0.01" class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Costo</label>
                            <input type="number" name="cost" value="{{ old('cost', $product->cost) }}"
                                   step="0.01" class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Stock actual *</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Stock mínimo *</label>
                            <input type="number" name="stock_min" value="{{ old('stock_min', $product->stock_min) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-gray-700 font-semibold mb-2">Unidades por caja</label>
        <input type="number" name="units_per_box" id="units_per_box"
               value="{{ old('units_per_box', $product->units_per_box) }}" min="1"
               class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-gray-700 font-semibold mb-2">Cajas en stock</label>
        <input type="number" name="boxes" id="boxes"
               value="{{ old('boxes', $product->boxes) }}" step="0.01"
               class="w-full border rounded-lg px-3 py-2"
               readonly
               style="background:#f3f4f6; color:#6b7280;">
    </div>
</div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Unidad</label>
                            <select name="unit" class="w-full border rounded-lg px-3 py-2">
                                <option value="unidad" {{ $product->unit == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                                <option value="litro" {{ $product->unit == 'litro' ? 'selected' : '' }}>Litro</option>
                                <option value="caja" {{ $product->unit == 'caja' ? 'selected' : '' }}>Caja</option>
                                <option value="paquete" {{ $product->unit == 'paquete' ? 'selected' : '' }}>Paquete</option>
                            </select>
                        </div>
                    </div>

                    <div style="font-weight:700; color:#374151; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                        📅 Fechas y Control
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Rotación</label>
                        <select name="rotation" class="w-full border rounded-lg px-3 py-2">
                            <option value="alta" {{ $product->rotation == 'alta' ? 'selected' : '' }}>🟢 Alta</option>
                            <option value="media" {{ $product->rotation == 'media' ? 'selected' : '' }}>🟠 Media</option>
                            <option value="baja" {{ $product->rotation == 'baja' ? 'selected' : '' }}>🔴 Baja</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Lote</label>
                        <input type="text" name="lot" value="{{ old('lot', $product->lot) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha Producción</label>
                            <input type="date" name="production_date"
                                   value="{{ old('production_date', $product->production_date?->format('Y-m-d')) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha Vencimiento</label>
                            <input type="date" name="expiration_date"
                                   value="{{ old('expiration_date', $product->expiration_date?->format('Y-m-d')) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Fecha Inventario</label>
                        <input type="date" name="inventory_date"
                               value="{{ old('inventory_date', $product->inventory_date?->format('Y-m-d')) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">URL de imagen</label>
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="imagen actual"
                             style="width:80px; height:80px; object-fit:cover; border-radius:0.5rem; margin-bottom:0.5rem;">
                        @endif
                        <input type="text" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="https://...">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                style="background-color:#16a34a; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Actualizar
                        </button>
                        <a href="{{ route('products.index') }}"
                           style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    function calcularCajas() {
        const stock = parseFloat(document.getElementById('stock_input')?.value) || 0;
        const unidades = parseInt(document.getElementById('units_per_box')?.value) || 1;
        const cajas = unidades > 0 ? (stock / unidades).toFixed(2) : 0;
        document.getElementById('boxes').value = cajas;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const stockInput = document.querySelector('input[name="stock"]');
        if (stockInput) {
            stockInput.id = 'stock_input';
            stockInput.addEventListener('input', calcularCajas);
        }
        document.getElementById('units_per_box').addEventListener('input', calcularCajas);
    });
</script>
</x-app-layout>