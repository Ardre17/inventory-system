<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nueva Orden
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

                <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Tipo *</label>
                            <select name="type" class="w-full border rounded-lg px-3 py-2">
                                <option value="sale">📤 Venta</option>
                                <option value="purchase">📥 Compra</option>
                            </select>
                        </div>
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
                    </div>

                    {{-- Productos --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Productos *</label>
                        <div id="productLines">
                            <div class="product-line grid grid-cols-3 gap-2 mb-2">
                                <select name="products[0][id]" class="border rounded-lg px-3 py-2 col-span-2">
                                    <option value="">Selecciona producto</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                    @endforeach
                                </select>
                                <input type="number" name="products[0][qty]" min="1" value="1"
                                       class="border rounded-lg px-3 py-2" placeholder="Cant.">
                            </div>
                        </div>
                        <button type="button" onclick="addLine()"
                                style="background-color:#e5e7eb; color:#374151; padding:0.4rem 1rem; border-radius:0.5rem; font-size:0.875rem; margin-top:0.5rem;">
                            + Agregar producto
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Notas</label>
                        <textarea name="notes" rows="2"
                                  class="w-full border rounded-lg px-3 py-2"
                                  placeholder="Notas opcionales"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                style="background-color:#7c3aed; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
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
    </div>

    <script>
        let lineCount = 1;
        const products = @json($products);

        function addLine() {
            const container = document.getElementById('productLines');
            const div = document.createElement('div');
            div.className = 'product-line grid grid-cols-3 gap-2 mb-2';
            div.innerHTML = `
                <select name="products[${lineCount}][id]" class="border rounded-lg px-3 py-2 col-span-2">
                    <option value="">Selecciona producto</option>
                    ${products.map(p => `<option value="${p.id}">${p.name} (Stock: ${p.stock})</option>`).join('')}
                </select>
                <input type="number" name="products[${lineCount}][qty]" min="1" value="1"
                       class="border rounded-lg px-3 py-2" placeholder="Cant.">
            `;
            container.appendChild(div);
            lineCount++;
        }
    </script>
    <script>
    const clientInput = document.getElementById('clientSearch');
    const suggestions = document.getElementById('clientSuggestions');

    clientInput.addEventListener('input', async function() {
        const q = this.value;
        if (q.length < 2) { suggestions.style.display = 'none'; return; }

        const res = await fetch(`/api/clients/search?q=${encodeURIComponent(q)}`);
        const clients = await res.json();

        if (clients.length === 0) { suggestions.style.display = 'none'; return; }

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
    });

    function selectClient(name) {
        clientInput.value = name;
        suggestions.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!clientInput.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
</script>
<script>
    const clientInput = document.getElementById('clientSearch');
    const suggestions = document.getElementById('clientSuggestions');

    clientInput.addEventListener('input', async function() {
        const q = this.value;
        if (q.length < 2) {
            suggestions.style.display = 'none';
            return;
        }

        try {
            const res = await fetch(`/api/clients/search?q=${encodeURIComponent(q)}`);
            const clients = await res.json();

            if (clients.length === 0) {
                suggestions.style.display = 'none';
                return;
            }

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
        } catch(e) {
            suggestions.style.display = 'none';
        }
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