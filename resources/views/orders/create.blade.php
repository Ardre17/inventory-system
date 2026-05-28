<x-app-layout>
    <x-slot name="header">
        ➕ Nueva Orden
    </x-slot>

    <div style="max-width:800px; margin:0 auto;">
        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem;">

            @if($errors->any())
            <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipo de movimiento *</label>
                        <select name="type" class="w-full border rounded-lg px-3 py-2">
                            <option value="sale">📤 Venta</option>
                            <option value="purchase">📥 Compra</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipo de pedido *</label>
                        <select name="order_type" class="w-full border rounded-lg px-3 py-2">
                            <option value="local">🏪 Local</option>
                            <option value="encomienda">📦 Encomienda</option>
                            <option value="supermercado">🛒 Supermercado</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div style="position:relative;">
                        <label class="block text-gray-700 font-semibold mb-2">Cliente / Proveedor</label>
                        <input type="text" id="clientSearch" name="client_supplier"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Escribe para buscar cliente..."
                               autocomplete="off">
                        <div id="clientSuggestions"
                             style="position:absolute; background:white; border:1px solid #d1d5db; border-radius:0.5rem; width:100%; z-index:100; display:none; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nro. Orden Cliente</label>
                        <input type="text" name="client_order_number"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Ej: 394902">
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="block text-gray-700 font-semibold mb-2">Notas</label>
                    <input type="text" name="notes"
                           class="w-full border rounded-lg px-3 py-2"
                           placeholder="Notas opcionales">
                </div>

                {{-- Productos --}}
                <div style="font-weight:700; color:#374151; margin:1rem 0 0.5rem; padding-bottom:0.5rem; border-bottom:2px solid #e5e7eb;">
                    📦 Productos *
                </div>

                <div style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:6px; margin-bottom:4px; font-size:0.75rem; font-weight:700; color:#6b7280; padding:0 4px;">
                    <div>Producto</div>
                    <div>Cantidad</div>
                    <div>Precio unitario</div>
                </div>

                <div id="productLines">
                    <div class="product-line" style="display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:6px; margin-bottom:6px; align-items:center;">
                        <select name="products[0][id]" class="border rounded-lg px-2 py-2 text-sm" onchange="loadPrice(this, 0)">
                            <option value="">Selecciona producto</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </option>
                            @endforeach
                        </select>
                        <input type="number" name="products[0][qty]" min="1" value="1"
                               style="border:1px solid #d1d5db; border-radius:0.5rem; padding:0.4rem; text-align:center;">
                        <input type="number" name="products[0][price]" min="0" step="0.01" value="0"
                               id="price_0"
                               style="border:1px solid #d1d5db; border-radius:0.5rem; padding:0.4rem; text-align:center; background:#fffbeb;"
                               placeholder="Precio">
                        <span></span>
                    </div>
                </div>

                <button type="button" onclick="addLine()"
                        style="background:#e5e7eb; color:#374151; padding:0.4rem 1rem; border-radius:0.5rem; font-size:0.875rem; margin-bottom:1.5rem; border:none; cursor:pointer;">
                    + Agregar producto
                </button>

                <div style="display:flex; gap:1rem;">
                    <button type="submit"
                            style="background-color:#7c3aed; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; border:none; cursor:pointer;">
                        Crear Orden
                    </button>
                    <a href="{{ route('orders.index') }}"
                       style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let lineCount = 1;
        const products = @json($products);

        function loadPrice(select, index) {
            const option = select.options[select.selectedIndex];
            const price = option.getAttribute('data-price') || 0;
            const priceInput = document.getElementById('price_' + index);
            if (priceInput) priceInput.value = parseFloat(price).toFixed(2);
        }

        function addLine() {
            const container = document.getElementById('productLines');
            const div = document.createElement('div');
            div.className = 'product-line';
            div.style = 'display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:6px; margin-bottom:6px; align-items:center;';
            const i = lineCount;
            div.innerHTML = `
                <select name="products[${i}][id]" style="border:1px solid #d1d5db; border-radius:0.5rem; padding:0.4rem; font-size:0.875rem;"
                        onchange="loadPriceDynamic(this, ${i})">
                    <option value="">Selecciona producto</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (Stock: ${p.stock})</option>`).join('')}
                </select>
                <input type="number" name="products[${i}][qty]" min="1" value="1"
                       style="border:1px solid #d1d5db; border-radius:0.5rem; padding:0.4rem; text-align:center;">
                <input type="number" name="products[${i}][price]" min="0" step="0.01" value="0"
                       id="price_${i}"
                       style="border:1px solid #d1d5db; border-radius:0.5rem; padding:0.4rem; text-align:center; background:#fffbeb;"
                       placeholder="Precio">
                <button type="button" onclick="this.closest('.product-line').remove()"
                        style="background:#fee2e2; color:#ef4444; border:none; padding:0.4rem 0.6rem; border-radius:0.5rem; cursor:pointer;">✕</button>
            `;
            container.appendChild(div);
            lineCount++;
        }

        function loadPriceDynamic(select, index) {
            const option = select.options[select.selectedIndex];
            const price = option.getAttribute('data-price') || 0;
            const priceInput = document.getElementById('price_' + index);
            if (priceInput) priceInput.value = parseFloat(price).toFixed(2);
        }

        // Autocompletar clientes
        const clientInput = document.getElementById('clientSearch');
        const suggestions = document.getElementById('clientSuggestions');

        clientInput.addEventListener('input', async function() {
            const q = this.value;
            if (q.length < 2) { suggestions.style.display = 'none'; return; }
            try {
                const res = await fetch(`/api/clients/search?q=${encodeURIComponent(q)}`);
                const clients = await res.json();
                if (!clients.length) { suggestions.style.display = 'none'; return; }
                suggestions.innerHTML = clients.map(c => `
                    <div onclick="selectClient('${c.name}')"
                         style="padding:0.75rem 1rem; cursor:pointer; border-bottom:1px solid #f3f4f6;"
                         onmouseover="this.style.background='#f3f4f6'"
                         onmouseout="this.style.background='white'">
                        <div style="font-weight:600;">${c.name}</div>
                        ${c.phone ? `<div style="font-size:0.75rem; color:#6b7280;">📞 ${c.phone}</div>` : ''}
                    </div>
                `).join('');
                suggestions.style.display = 'block';
            } catch(e) { suggestions.style.display = 'none'; }
        });

        function selectClient(name) {
            clientInput.value = name;
            suggestions.style.display = 'none';
        }

        document.addEventListener('click', function(e) {
            if (!clientInput.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
