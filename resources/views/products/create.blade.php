<x-app-layout>
    <x-slot name="header">
        ➕ Nuevo Producto
    </x-slot>

    <div style="max-width:700px; margin:0 auto;">
        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem;">

            @if($errors->any())
            <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                {{-- Información General --}}
                <div style="font-weight:700; color:#1e3a8a; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb; font-size:0.95rem;">
                    📋 Información General
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                           placeholder="Nombre del producto">
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Descripción</label>
                    <textarea name="description" rows="2"
                              style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box; resize:vertical;"
                              placeholder="Descripción del producto">{{ old('description') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Categoría *</label>
                        <select name="category_id" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem;">
                            <option value="">Selecciona...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Proveedor</label>
                        <select name="supplier_id" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem;">
                            <option value="">Sin proveedor</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                               placeholder="Código SKU">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Código de barras</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                               placeholder="Código de barras">
                    </div>
                </div>

                {{-- Stock y Precios --}}
                <div style="font-weight:700; color:#1e3a8a; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb; font-size:0.95rem;">
                    📦 Stock y Precios
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Precio *</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}"
                               step="0.01" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Costo</label>
                        <input type="number" name="cost" value="{{ old('cost', 0) }}"
                               step="0.01" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Stock actual *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" id="stock_input"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Stock mínimo *</label>
                        <input type="number" name="stock_min" value="{{ old('stock_min', 5) }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Unidades por caja</label>
                        <input type="number" name="units_per_box" id="units_per_box" value="{{ old('units_per_box', 1) }}" min="1"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Cajas en stock</label>
                        <input type="number" name="boxes" id="boxes" value="{{ old('boxes', 0) }}" step="0.01" readonly
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box; background:#f9fafb; color:#6b7280;">
                    </div>
                    <div>
    <label>Peso por unidad (gramos)</label>

    <input
        type="number"
        step="0.01"
        name="unit_weight"
        class="w-full border rounded"
        placeholder="Ejemplo: 420">
</div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Unidad</label>
                    <select name="unit" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem;">
                        <option value="unidad">Unidad</option>
                        <option value="kg">Kilogramo</option>
                        <option value="litro">Litro</option>
                        <option value="caja">Caja</option>
                        <option value="paquete">Paquete</option>
                    </select>
                </div>

                {{-- Fechas y Control --}}
                <div style="font-weight:700; color:#1e3a8a; margin:1.5rem 0 1rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb; font-size:0.95rem;">
                    📅 Fechas y Control
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Rotación</label>
                    <select name="rotation" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem;">
                        <option value="alta" {{ old('rotation') == 'alta' ? 'selected' : '' }}>🟢 Alta</option>
                        <option value="media" {{ old('rotation', 'media') == 'media' ? 'selected' : '' }}>🟠 Media</option>
                        <option value="baja" {{ old('rotation') == 'baja' ? 'selected' : '' }}>🔴 Baja</option>
                    </select>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Lote</label>
                    <input type="text" name="lot" value="{{ old('lot') }}"
                           style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                           placeholder="Número de lote">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Fecha Producción</label>
                        <input type="date" name="production_date" value="{{ old('production_date') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Fecha Vencimiento</label>
                        <input type="date" name="expiration_date" value="{{ old('expiration_date') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Fecha Inventario</label>
                        <input type="date" name="inventory_date" value="{{ old('inventory_date') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">URL de imagen</label>
                        <input type="text" name="image_url" value="{{ old('image_url') }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;"
                               placeholder="https://...">
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                    <button type="submit"
                            style="background-color:#16a34a; color:white; padding:0.6rem 1.5rem; border-radius:0.5rem; font-weight:600; border:none; cursor:pointer; font-size:0.95rem;">
                        Guardar
                    </button>
                    <a href="{{ route('products.index') }}"
                       style="background-color:#e5e7eb; color:#374151; padding:0.6rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none; font-size:0.95rem;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularCajas() {
            const stock = parseFloat(document.getElementById('stock_input')?.value) || 0;
            const unidades = parseInt(document.getElementById('units_per_box')?.value) || 1;
            document.getElementById('boxes').value = unidades > 0 ? (stock / unidades).toFixed(2) : 0;
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('stock_input')?.addEventListener('input', calcularCajas);
            document.getElementById('units_per_box')?.addEventListener('input', calcularCajas);
            calcularCajas();
        });
    </script>
</x-app-layout>
