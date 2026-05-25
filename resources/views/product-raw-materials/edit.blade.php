<x-app-layout>
    <x-slot name="header">
        🧪 Editar: {{ $product->name }}
    </x-slot>

    <div style="max-width:800px; margin:0 auto;">

        <p style="color:#6b7280; font-size:14px; margin-bottom:1.5rem;">
            Define cuánto de cada materia prima se consume al producir <strong>1 unidad</strong> de este producto.
        </p>

        @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:1rem;">
            {{ session('success') }}
        </div>
        @endif

        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:1.75rem;">
            <form method="POST" action="{{ route('product-raw-materials.update', $product) }}">
                @csrf @method('PATCH')

                <div id="materialLines">
                    @forelse($product->rawMaterials as $i => $rm)
                    <div class="mat-line" style="display:grid; grid-template-columns:1fr auto auto; gap:12px; margin-bottom:12px; align-items:center;">
                        <select name="materials[{{ $i }}][raw_material_id]"
                                style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                            @foreach($allRawMaterials as $m)
                            <option value="{{ $m->id }}" {{ $m->id == $rm->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->unit }})
                            </option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[{{ $i }}][quantity]"
                               value="{{ $rm->pivot->quantity }}"
                               min="0.001" step="0.001" placeholder="Cantidad/unidad"
                               style="width:140px; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        <button type="button" onclick="this.closest('.mat-line').remove()"
                                style="background:#fee2e2; color:#dc2626; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-size:16px;">
                            ✕
                        </button>
                    </div>
                    @empty
                    <div class="mat-line" style="display:grid; grid-template-columns:1fr auto auto; gap:12px; margin-bottom:12px; align-items:center;">
                        <select name="materials[0][raw_material_id]"
                                style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                            <option value="">Selecciona materia prima</option>
                            @foreach($allRawMaterials as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->unit }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[0][quantity]"
                               min="0.001" step="0.001" placeholder="Cantidad/unidad"
                               style="width:140px; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        <button type="button" onclick="this.closest('.mat-line').remove()"
                                style="background:#fee2e2; color:#dc2626; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-size:16px;">
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
            div.style = 'display:grid; grid-template-columns:1fr auto auto; gap:12px; margin-bottom:12px; align-items:center;';

            const opts = rawMaterials.map(m =>
                `<option value="${m.id}">${m.name} (${m.unit})</option>`
            ).join('');

            div.innerHTML = `
                <select name="materials[${lineCount}][raw_material_id]"
                        style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                    <option value="">Selecciona materia prima</option>${opts}
                </select>
                <input type="number" name="materials[${lineCount}][quantity]"
                       min="0.001" step="0.001" placeholder="Cantidad/unidad"
                       style="width:140px; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                <button type="button" onclick="this.closest('.mat-line').remove()"
                        style="background:#fee2e2; color:#dc2626; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-size:16px;">
                    ✕
                </button>
            `;
            container.appendChild(div);
            lineCount++;
        }
    </script>
</x-app-layout>
