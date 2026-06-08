<x-app-layout>
   <x-slot name="header">
       🧾 {{ $order->order_number }}
   </x-slot>


   <div style="max-width:900px; margin:0 auto;">


       @if(session('success'))
       <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
           {{ session('success') }}
       </div>
       @endif


       <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem; margin-bottom:1rem;">
           <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; font-size:0.9rem;">
               <div>
                   <span style="color:#6b7280;">Tipo</span><br>
                   <span style="font-weight:600;">{{ $order->type === 'sale' ? '📤 Venta' : '📥 Compra' }}</span>
               </div>
               <div>
                   <span style="color:#6b7280;">Cliente / Proveedor</span><br>
                   <span style="font-weight:600;">{{ $order->client_supplier ?? '—' }}</span>
               </div>
               @if($order->client_order_number)
               <div>
                   <span style="color:#6b7280;">Nro. Orden Cliente</span><br>
                   <span style="font-weight:600;">{{ $order->client_order_number }}</span>
               </div>
               @endif
               <div>
                   <span style="color:#6b7280;">Fecha</span><br>
                   <span style="font-weight:600;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
               </div>
               <div>
                   <span style="color:#6b7280;">Total</span><br>
                   <span style="font-weight:700; color:#7c3aed; font-size:1.1rem;">S/{{ number_format($order->total, 2) }}</span>
               </div>
               @if($order->notes)
               <div>
                   <span style="color:#6b7280;">Notas</span><br>
                   <span style="font-weight:600;">{{ $order->notes }}</span>
               </div>
               @endif
           </div>
       </div>


       <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.5rem; margin-bottom:1rem;">
           <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
               <div style="font-weight:700; font-size:1rem;">📦 Productos</div>
               <button onclick="openBarcodeScanner()"
                       style="background:#2563eb; color:white; padding:0.4rem 1rem; border-radius:0.5rem; font-size:0.85rem; font-weight:600; border:none; cursor:pointer;">
                   📷 Escanear código
               </button>
           </div>


          @foreach($order->items as $item)
<div id="item-{{ $item->id }}"
     style="background:{{ $item->status_bg }}; border-radius:0.5rem; padding:1rem; margin-bottom:0.75rem; border-left:4px solid {{ $item->status_color }};">

    @if($item->dispatch_status === 'none')
    <div style="background:#ef4444; color:white; padding:0.4rem 0.75rem; border-radius:0.375rem; font-size:0.8rem; font-weight:700; margin-bottom:0.5rem; display:inline-block;">
        ❌ PRODUCTO NO ENVIADO — No se cobrará
    </div>
    @endif

    <div style="display:flex; justify-content:space-between; align-items:start;">
        <div style="flex:1;">
            <div style="font-weight:700; {{ $item->dispatch_status === 'none' ? 'text-decoration:line-through; color:#9ca3af;' : '' }}">
                {{ $item->product->name }}
            </div>
            <div style="font-size:0.85rem; color:#6b7280; margin-top:0.25rem;">
                Stock actual: <span style="font-weight:600; color:#374151;">{{ $item->product->stock }} {{ $item->product->unit }}</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.5rem; margin-top:0.5rem; font-size:0.85rem;">
                <div>
                    <span style="color:#6b7280;">Solicitado</span><br>
                    <span style="font-weight:700;">{{ $item->quantity }}</span>
                </div>
                <div>
                    <span style="color:#6b7280;">Enviado</span><br>
                    <span style="font-weight:700; color:{{ $item->status_color }};">{{ $item->quantity_sent }}</span>
                </div>
                <div>
                    <span style="color:#6b7280;">Estado</span><br>
                    <span style="font-weight:700; color:{{ $item->status_color }};">
                        @if($item->dispatch_status === 'complete') ✅ Completo
                        @elseif($item->dispatch_status === 'partial') 🟡 Parcial
                        @elseif($item->dispatch_status === 'none') ❌ No enviado
                        @else ⏳ Pendiente
                        @endif
                    </span>
                </div>
            </div>

            @if($order->order_type === 'supermercado' && $item->dispatch_status !== 'pending')
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid #e5e7eb; font-size:0.85rem;">
                <div>
                    <span style="color:#6b7280;">Paleta</span><br>
                    <span style="font-weight:700;">{{ $item->pallet_number ?? '—' }}</span>
                </div>
                <div>
                    <span style="color:#6b7280;">Pucho</span><br>
                    <span style="font-weight:700;">{{ $item->pucho ?? 0 }} cajas</span>
                </div>
                <div>
                    <span style="color:#6b7280;">Vencimiento</span><br>
                    <span style="font-weight:700; color:#0369a1;">
                        {{ $item->dispatch_expiration_date ? $item->dispatch_expiration_date->format('d/m/Y') : '—' }}
                    </span>
                </div>
            </div>
            @endif
        </div>

        <div style="display:flex; flex-direction:column; gap:0.5rem; margin-left:1rem; min-width:120px;">
            @if($item->dispatch_status === 'pending')
            <button onclick="openDispatch({{ $item->id }},'{{ addslashes($item->product->name) }}',{{ $item->quantity }},{{ $item->product->stock }},'{{ $item->product->barcode }}','',0,'{{ $item->product->expiration_date ? $item->product->expiration_date->format('Y-m-d') : '' }}')"
                    style="background:#2563eb; color:white; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:0.8rem; white-space:nowrap; border:none; cursor:pointer; width:100%;">
                📤 Despachar
            </button>
            @else
            <button onclick="openDispatch({{ $item->id }},'{{ addslashes($item->product->name) }}',{{ $item->quantity }},{{ $item->product->stock }},'{{ $item->product->barcode }}','{{ $item->pallet_number }}',{{ $item->pucho ?? 0 }},'{{ $item->dispatch_expiration_date ? $item->dispatch_expiration_date->format('Y-m-d') : ($item->product->expiration_date ? $item->product->expiration_date->format('Y-m-d') : '') }}')"
                    style="background:#ea580c; color:white; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:0.8rem; white-space:nowrap; border:none; cursor:pointer; width:100%;">
                ✏️ Editar envío
            </button>
            @endif
            <button onclick="openEditRequested({{ $item->id }}, {{ $item->quantity }})"
                    style="background:#6b7280; color:white; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:0.8rem; white-space:nowrap; border:none; cursor:pointer; width:100%;">
                📝 Editar pedido
            </button>
        </div>
    </div>
</div>
@endforeach


           <div style="text-align:right; margin-top:1rem; font-size:0.9rem;">
               <div>Subtotal: <strong>S/{{ number_format($order->subtotal, 2) }}</strong></div>
               <div>IGV (18%): <strong>S/{{ number_format($order->subtotal * 0.18, 2) }}</strong></div>
               <div style="font-size:1.2rem; color:#7c3aed; margin-top:0.25rem;">
                   Total: <strong>S/{{ number_format($order->subtotal * 1.18, 2) }}</strong>
               </div>
           </div>
       </div>


       <a href="{{ route('orders.index') }}"
          style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
           ← Volver</a>
       <a href="{{ route('orders.pdf', $order) }}"
          style="background-color:#dc2626; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
           🖨️ Descargar PDF
       </a>
       <a
    href="{{ route('orders.labels',$order) }}"
    target="_blank"
    style="
        background:#059669;
        color:white;
        padding:8px 12px;
        border-radius:6px;
        text-decoration:none;
        margin-left:5px;
    "
>
    🏷️ Etiquetas
</a>
   </div>


   {{-- Modal despacho --}}
   <div id="dispatchModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
       <div style="background:white; border-radius:0.75rem; padding:1.5rem; width:90%; max-width:450px; max-height:90vh; overflow-y:auto;">
           <div style="font-weight:700; font-size:1.1rem; margin-bottom:1rem;">📤 Despachar Producto</div>
           <div id="modalProductName" style="font-weight:600; color:#374151; margin-bottom:0.75rem;"></div>
           <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:1rem; font-size:0.85rem;">
               <div style="background:#f3f4f6; padding:0.5rem; border-radius:0.375rem;">
                   <div style="color:#6b7280;">Solicitado</div>
                   <div id="modalRequested" style="font-weight:700; font-size:1.1rem;"></div>
               </div>
               <div style="background:#f3f4f6; padding:0.5rem; border-radius:0.375rem;">
                   <div style="color:#6b7280;">Stock actual</div>
                   <div id="modalStock" style="font-weight:700; font-size:1.1rem;"></div>
               </div>
           </div>
           <div style="margin-bottom:1rem;">
               <label style="font-size:0.85rem; font-weight:600; color:#374151; display:block; margin-bottom:0.5rem;">Cantidad enviada *</label>
               <div style="display:flex; align-items:center; gap:0.5rem;">
                   <button type="button" onclick="changeQty(-1)"
                           style="background:#e5e7eb; color:#374151; width:2.5rem; height:2.5rem; border-radius:0.5rem; font-size:1.2rem; font-weight:700; border:none; cursor:pointer;">−</button>
                   <input type="number" id="qtySent" min="0" value="0"
                          style="flex:1; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem; text-align:center; font-size:1.1rem; font-weight:700;">
                   <button type="button" onclick="changeQty(1)"
                           style="background:#e5e7eb; color:#374151; width:2.5rem; height:2.5rem; border-radius:0.5rem; font-size:1.2rem; font-weight:700; border:none; cursor:pointer;">+</button>
               </div>
           </div>


           @if($order->order_type === 'supermercado')
           <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:0.5rem; padding:1rem; margin-bottom:1rem;">
               <div style="font-size:0.8rem; font-weight:700; color:#0369a1; margin-bottom:0.75rem;">🛒 Datos Supermercado</div>
               <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                   <div>
                       <label style="font-size:0.8rem; font-weight:600; color:#374151; display:block; margin-bottom:0.25rem;">Nº Paleta</label>
                       <input type="text" id="palletNumber"
                              style="width:100%; border:1px solid #d1d5db; border-radius:0.375rem; padding:0.4rem 0.6rem; font-size:0.9rem; box-sizing:border-box;"
                              placeholder="Ej: 1, 2, A...">
                   </div>
                   <div>
                       <label style="font-size:0.8rem; font-weight:600; color:#374151; display:block; margin-bottom:0.25rem;">Pucho (cajas sueltas)</label>
                       <input type="number" id="pucho" min="0" value="0"
                              style="width:100%; border:1px solid #d1d5db; border-radius:0.375rem; padding:0.4rem 0.6rem; font-size:0.9rem; box-sizing:border-box;">
                   </div>
               </div>
               <div>
                   <label style="font-size:0.8rem; font-weight:600; color:#374151; display:block; margin-bottom:0.25rem;">Fecha de vencimiento</label>
                   <input type="date" id="expirationDate"
                          style="width:100%; border:1px solid #d1d5db; border-radius:0.375rem; padding:0.4rem 0.6rem; font-size:0.9rem; box-sizing:border-box;">
               </div>
           </div>
           @endif


           <div style="display:flex; gap:0.75rem;">
               <button type="button" onclick="confirmDispatch()"
                       style="flex:1; background:#16a34a; color:white; padding:0.75rem; border-radius:0.5rem; font-weight:700; font-size:0.95rem; border:none; cursor:pointer;">
                   ✅ Confirmar
               </button>
               <button type="button" onclick="closeModal()"
                       style="flex:1; background:#e5e7eb; color:#374151; padding:0.75rem; border-radius:0.5rem; font-weight:700; font-size:0.95rem; border:none; cursor:pointer;">
                   Cancelar
               </button>
           </div>
       </div>
   </div>


   {{-- Modal editar pedido --}}
   <div style="margin-bottom:1rem;">

    <label style="
        font-size:0.85rem;
        font-weight:600;
        color:#374151;
        display:block;
        margin-bottom:0.5rem;
    ">
        Producto
    </label>

    <select
        id="editProduct"
        style="
            width:100%;
            border:1px solid #d1d5db;
            border-radius:0.5rem;
            padding:0.75rem;
            font-size:0.95rem;
        "
    >

        @foreach($products as $product)

            <option value="{{ $product->id }}">
                {{ $product->name }}
            </option>

        @endforeach

    </select>

</div>
   <div id="editRequestedModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
       <div style="background:white; border-radius:0.75rem; padding:1.5rem; width:90%; max-width:350px;">
           <div style="font-weight:700; font-size:1.1rem; margin-bottom:1rem;">📝 Editar Cantidad Pedido</div>
           <div style="margin-bottom:1rem;">
               <label style="font-size:0.85rem; font-weight:600; color:#374151; display:block; margin-bottom:0.5rem;">Nueva cantidad solicitada *</label>
               <div style="display:flex; align-items:center; gap:0.5rem;">
                   <button type="button" onclick="changeReqQty(-1)"
                           style="background:#e5e7eb; color:#374151; width:2.5rem; height:2.5rem; border-radius:0.5rem; font-size:1.2rem; font-weight:700; border:none; cursor:pointer;">−</button>
                   <input type="number" id="reqQty" min="1" value="0"
                          style="flex:1; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem; text-align:center; font-size:1.1rem; font-weight:700;">
                   <button type="button" onclick="changeReqQty(1)"
                           style="background:#e5e7eb; color:#374151; width:2.5rem; height:2.5rem; border-radius:0.5rem; font-size:1.2rem; font-weight:700; border:none; cursor:pointer;">+</button>
               </div>
           </div>
           <div style="display:flex; gap:0.75rem;">
               <button type="button" onclick="confirmEditRequested()"
                       style="flex:1; background:#16a34a; color:white; padding:0.75rem; border-radius:0.5rem; font-weight:700; border:none; cursor:pointer;">
                   ✅ Guardar
               </button>
               <button type="button" onclick="closeEditModal()"
                       style="flex:1; background:#e5e7eb; color:#374151; padding:0.75rem; border-radius:0.5rem; font-weight:700; border:none; cursor:pointer;">
                   Cancelar
               </button>
           </div>
       </div>
   </div>


   <script>
       let currentItemId = null;
       let currentEditItemId = null;


       function openDispatch(itemId, name, requested, stock, barcode, pallet, pucho, expDate) {
           currentItemId = itemId;
           document.getElementById('modalProductName').textContent = name;
           document.getElementById('modalRequested').textContent = requested;
           document.getElementById('modalStock').textContent = stock;
           document.getElementById('qtySent').value = requested;
           @if($order->order_type === 'supermercado')
           if (document.getElementById('palletNumber')) document.getElementById('palletNumber').value = pallet || '';
           if (document.getElementById('pucho')) document.getElementById('pucho').value = pucho || 0;
           if (document.getElementById('expirationDate')) document.getElementById('expirationDate').value = expDate || '';
           @endif
           document.getElementById('dispatchModal').style.display = 'flex';
       }


       function openBarcodeScanner() {
           const barcode = prompt('Ingresa o escanea el código de barras:');
           if (!barcode) return;
           fetch(`/orders/barcode?barcode=${encodeURIComponent(barcode)}`)
               .then(r => r.json())
               .then(data => { if (!data.found) alert('Producto no encontrado'); });
       }


       function changeQty(delta) {
           const input = document.getElementById('qtySent');
           input.value = Math.max(0, (parseInt(input.value) || 0) + delta);
       }


       function closeModal() {
           document.getElementById('dispatchModal').style.display = 'none';
           currentItemId = null;
       }


       function confirmDispatch() {
           const qty = parseInt(document.getElementById('qtySent').value) || 0;
           if (currentItemId === null) return;
           const payload = { item_id: currentItemId, quantity_sent: qty };
           @if($order->order_type === 'supermercado')
           payload.pallet_number = document.getElementById('palletNumber')?.value || null;
           payload.pucho = parseInt(document.getElementById('pucho')?.value) || 0;
           payload.dispatch_expiration_date = document.getElementById('expirationDate')?.value || null;
           @endif
           fetch(`/orders/{{ $order->id }}/dispatch`, {
               method: 'POST',
               headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
               body: JSON.stringify(payload)
           }).then(r => r.json()).then(data => { if (data.success) { closeModal(); window.location.reload(); } });
       }


       function confirmEditRequested() {

    const qty = parseInt(
        document.getElementById('reqQty').value
    ) || 1;

    const productId = document.getElementById(
        'editProduct'
    ).value;

    if (!currentEditItemId) return;

    fetch(`/orders/{{ $order->id }}/update-item`, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            item_id: currentEditItemId,

            product_id: productId,

            quantity: qty

        })

    })
    .then(r => r.json())
    .then(data => {

        if (data.success) {

            closeEditModal();

            window.location.reload();

        }

    });

}


       function changeReqQty(delta) {
           const input = document.getElementById('reqQty');
           input.value = Math.max(1, (parseInt(input.value) || 1) + delta);
       }


       function closeEditModal() {
           document.getElementById('editRequestedModal').style.display = 'none';
           currentEditItemId = null;
       }


       function confirmEditRequested() {

    const qty = parseInt(
        document.getElementById('reqQty').value
    ) || 1;

    const productId = document.getElementById(
        'editProduct'
    ).value;

    if (!currentEditItemId) return;

    fetch(`/orders/{{ $order->id }}/update-item`, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            item_id: currentEditItemId,

            product_id: productId,

            quantity: qty

        })

    })
    .then(r => r.json())
    .then(data => {

        if (data.success) {

            closeEditModal();

            window.location.reload();

        }

    });

}


       document.getElementById('dispatchModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
       document.getElementById('editRequestedModal').addEventListener('click', function(e) { if (e.target === this) closeEditModal(); });

       // === EDITAR PRECIO ===
       let currentPriceItemId = null;

       function openEditPrice(itemId, currentPrice) {
           currentPriceItemId = itemId;
           document.getElementById('priceInput').value = parseFloat(currentPrice).toFixed(2);
           document.getElementById('editPriceModal').style.display = 'flex';
           setTimeout(() => document.getElementById('priceInput').select(), 100);
       }

       function closeEditPrice() {
           document.getElementById('editPriceModal').style.display = 'none';
           currentPriceItemId = null;
       }

       function confirmEditPrice() {
           const price = parseFloat(document.getElementById('priceInput').value);
           if (!currentPriceItemId || isNaN(price) || price < 0) return;
           fetch(`/orders/{{ $order->id }}/update-item-price`, {
               method: 'POST',
               headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
               body: JSON.stringify({ item_id: currentPriceItemId, unit_price: price })
           }).then(r => r.json()).then(data => { if (data.success) { closeEditPrice(); window.location.reload(); } });
       }

       document.getElementById('editPriceModal').addEventListener('click', function(e) { if (e.target === this) closeEditPrice(); });
   </script>

   {{-- Modal editar precio --}}
   <div id="editPriceModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
       <div style="background:white; border-radius:0.75rem; padding:1.5rem; width:90%; max-width:350px;">
           <div style="font-weight:700; font-size:1.1rem; margin-bottom:0.5rem;">💲 Editar Precio Unitario</div>
           <div style="font-size:0.82rem; color:#6b7280; margin-bottom:1rem; background:#f0f9ff; border:1px solid #bae6fd; border-radius:0.375rem; padding:0.5rem 0.75rem;">
               ℹ️ Solo afecta esta orden. El precio del producto en el sistema no cambia.
           </div>
           <div style="margin-bottom:1rem;">
               <label style="font-size:0.85rem; font-weight:600; color:#374151; display:block; margin-bottom:0.5rem;">Nuevo precio unitario (S/)</label>
               <input type="number" id="priceInput" min="0" step="0.01"
                      style="width:100%; border:2px solid #0ea5e9; border-radius:0.5rem; padding:0.6rem 0.75rem; text-align:center; font-size:1.4rem; font-weight:700; box-sizing:border-box; color:#0369a1;">
           </div>
           <div style="display:flex; gap:0.75rem;">
               <button type="button" onclick="confirmEditPrice()"
                       style="flex:1; background:#0ea5e9; color:white; padding:0.75rem; border-radius:0.5rem; font-weight:700; font-size:1rem; border:none; cursor:pointer;">
                   ✅ Guardar
               </button>
               <button type="button" onclick="closeEditPrice()"
                       style="flex:1; background:#e5e7eb; color:#374151; padding:0.75rem; border-radius:0.5rem; font-weight:700; font-size:1rem; border:none; cursor:pointer;">
                   Cancelar
               </button>
           </div>
       </div>
   </div>
</x-app-layout>
