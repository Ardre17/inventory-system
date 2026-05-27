<x-app-layout>
    <x-slot name="header">
        🧪 Editar: {{ $product->name }}
    </x-slot>

    <div style="max-width:800px; margin:0 auto;">

        <p style="color:#6b7280; font-size:14px; margin-bottom:1.5rem;">
            Define cuánto de cada materia prima se consume al producir <strong>1 unidad</strong> y cuánto stock se asigna a este producto.
        </p>

        @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:1rem;">
            {{ session('success') }}
        </div>
        @endif

        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.75rem;">

            {{-- Info stock materias primas --}}
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem;">
                <div style="font-size:0.85rem; font-weight:700; color:#0369a1; margin-bottom:0.5rem;">📦 Stock disponible en almacén</div>
                @foreach($allRawMaterials as $rm)
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; padding:0.25rem 0; border-bottom:1px solid #e0f2fe;">
                    <span style="color:#374151;">{{ $rm->name }}</span>
                    <span style="font-weight:700; color:{{ $rm->stock <= $rm->stock_min ? '#ef4444' : '#059669' }};">
                        {{ number_format($rm->stock, 2) }} {{ $rm->unit }}
                    </span>
                </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('product-raw-materials.update', $product) }}">
                @csrf @method('PATCH')

                <div style="display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:8px; margin-bottom:8px; font-size:0.8rem; font-weight:700; color:#6b7280;">
                    <div>Materia Prima</div>
                    <div>Cant. por unidad</div>
                    <div>Stock asignado</div>
                    <div></div>
                </div>

                <div id="materialLines">
                    @forelse($product->rawMaterials as $i => $rm)
                    <div class="mat-line" style="display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:8px; margin-bottom:8px; align-items:center;">
                        <select name="materials[{{ $i }}][raw_material_id]"
                                style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                            @foreach($allRawMaterials as $m)
                            <option value="{{ $m->id }}" {{ $m->id == $rm->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->unit }})
                            </option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[{{ $i }}][quantity]"
                               value="{{ $rm->pivot->quantity_per_unit }}"
                               min="0.001" step="0.001" placeholder="Por unidad"
                               style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                        <input type="number" name="materials[{{ $i }}][stock]"
                               value="{{ $rm->pivot->stock }}"
                               min="0" step="0.001" placeholder="Stock asignado"
                               style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                        <button type="button" onclick="this.closest('.mat-line').remove()"
                                style="background:#fee2e2; color:#dc2626; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-size:16px;">
                            ✕
                        </button>
                    </div>
                    @empty
                    <div class="mat-line" style="display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:8px; margin-bottom:8px; align-items:center;">
                        <select name="materials[0][raw_material_id]"
                                style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                            <option value="">Selecciona materia prima</option>
                            @foreach($allRawMaterials as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->unit }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[0][quantity]"
                               min="0.001" step="0.001" placeholder="Por unidad"
                               style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                        <input type="number" name="materials[0][stock]"
                               min="0" step="0.001" placeholder="Stock asignado" value="0"
                               style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                        <button type="button" onclick="this.closest('.mat-line').remove()"
                                style="background:#fee2e2; color:#dc2626; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-size:16px;">
                            ✕
                        </button>
                    </div>
                    @endforelse
                </div>

                <button type="button" onclick="addLine()"
                        style="background:#f3f4f6; color:#374151; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; font-size:14px; font-weight:600; margin-bottom:1.5rem;">
                    + Agregar materia prima
                </button>

                <div style="display:flex; gap:12px; padding-top:1.25rem; border-top:1px solid #f3f4f6;">
                    <button type="submit"
                            style="background:#2563eb; color:white; padding:12px 28px; border:none; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer;">
                        Guardar
                    </button>
                    <a href="{{ route('product-raw-materials.index') }}"
                       style="background:#f3f4f6; color:#374151; padding:12px 28px; border-radius:8px; text-decoration:none; font-size:15px; font-weight:600;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let lineCount = {{ $product->rawMaterials->count() ?: 1 }};
        const rawMaterials = @json($allRawMaterials);

        function addLine() {
            const container = document.getElementById('materialLines');
            const div = document.createElement('div');
            div.className = 'mat-line';
            div.style = 'display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:8px; margin-bottom:8px; align-items:center;';
            const opts = rawMaterials.map(m => `<option value="${m.id}">${m.name} (${m.unit})</option>`).join('');
            div.innerHTML = `
                <select name="materials[${lineCount}][raw_material_id]"
                        style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                    <option value="">Selecciona materia prima</option>${opts}
                </select>
                <input type="number" name="materials[${lineCount}][quantity]"
                       min="0.001" step="0.001" placeholder="Por unidad"
                       style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                <input type="number" name="materials[${lineCount}][stock]"
                       min="0" step="0.001" placeholder="Stock asignado" value="0"
                       style="padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                <button type="button" onclick="this.closest('.mat-line').remove()"
                        style="background:#fee2e2; color:#dc2626; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-size:16px;">
                    ✕
                </button>
            `;
            container.appendChild(div);
            lineCount++;
        }
    </script>
</x-app-layout>
