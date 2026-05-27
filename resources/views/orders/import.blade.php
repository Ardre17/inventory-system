<x-app-layout>
    <x-slot name="header">
        📂 Importar Orden desde CSV
    </x-slot>

    <div style="max-width:700px; margin:0 auto;">

        @if(session('success'))
        <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
            {{ session('error') }}
        </div>
        @endif

        {{-- Instrucciones --}}
        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:0.75rem; padding:1.5rem; margin-bottom:1rem;">
            <div style="font-weight:700; color:#0369a1; margin-bottom:0.75rem;">📋 Formato del CSV</div>
            <p style="color:#374151; font-size:0.9rem; margin-bottom:0.75rem;">
                El archivo CSV debe tener las siguientes columnas en este orden:
            </p>
            <div style="background:white; border-radius:0.5rem; padding:0.75rem; font-family:monospace; font-size:0.85rem; color:#374151; overflow-x:auto;">
                sku,cantidad<br>
                77510330,24<br>
                77510335,12<br>
                77510332,48
            </div>
            <p style="color:#6b7280; font-size:0.8rem; margin-top:0.75rem;">
                💡 El SKU debe coincidir con el código de barras o SKU del producto en el sistema.
            </p>
            <a href="{{ route('orders.csv-template') }}"
               style="display:inline-block; margin-top:0.75rem; background:#0369a1; color:white; padding:0.4rem 1rem; border-radius:0.5rem; font-size:0.85rem; text-decoration:none;">
                ⬇️ Descargar plantilla CSV
            </a>
        </div>

        {{-- Formulario --}}
        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem;">
            <form method="POST" action="{{ route('orders.import') }}" enctype="multipart/form-data">
                @csrf

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Tipo de movimiento *</label>
                        <select name="type" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem;">
                            <option value="sale">📤 Venta</option>
                            <option value="purchase">📥 Compra</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Tipo de pedido *</label>
                        <select name="order_type" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem;">
                            <option value="local">🏪 Local</option>
                            <option value="encomienda">📦 Encomienda</option>
                            <option value="supermercado">🛒 Supermercado</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div style="position:relative;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Cliente / Proveedor</label>
                        <input type="text" id="clientSearch" name="client_supplier"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; box-sizing:border-box;"
                               placeholder="Escribe para buscar..." autocomplete="off">
                        <div id="clientSuggestions"
                             style="position:absolute; background:white; border:1px solid #d1d5db; border-radius:0.5rem; width:100%; z-index:100; display:none; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Nro. Orden Cliente</label>
                        <input type="text" name="client_order_number"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; box-sizing:border-box;"
                               placeholder="Ej: 394902">
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Notas</label>
                    <input type="text" name="notes"
                           style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; box-sizing:border-box;"
                           placeholder="Notas opcionales">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.5rem;">Archivo CSV *</label>
                    <input type="file" name="csv_file" accept=".csv"
                           style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; box-sizing:border-box; background:white;">
                </div>

                <div style="display:flex; gap:1rem;">
                    <button type="submit"
                            style="background:#7c3aed; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; border:none; cursor:pointer;">
                        📂 Importar Orden
                    </button>
                    <a href="{{ route('orders.index') }}"
                       style="background:#e5e7eb; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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
            if (!clientInput.contains(e.target)) suggestions.style.display = 'none';
        });
    </script>
</x-app-layout>
