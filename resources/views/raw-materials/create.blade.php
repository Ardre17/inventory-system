<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nueva Materia Prima
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('raw-materials.store') }}">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Nombre *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" required>
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Unidad *</label>
                            <select name="unit" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" required>
                                @foreach(['kg','g','L','ml','unid','caja','bolsa','saco'] as $u)
                                <option value="{{ $u }}" {{ old('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Stock actual *</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   step="0.001" min="0" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" required>
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Stock mínimo *</label>
                            <input type="number" name="stock_min" value="{{ old('stock_min', 0) }}"
                                   step="0.001" min="0" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" required>
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Costo *</label>
                            <input type="number" name="cost" value="{{ old('cost', 0) }}"
                                   step="0.01" min="0" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Proveedor</label>
                            <select name="supplier_id" style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                                <option value="">— Sin proveedor —</option>
                                @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Lote</label>
                            <input type="text" name="lot" value="{{ old('lot') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Fecha vencimiento</label>
                            <input type="date" name="expiration_date" value="{{ old('expiration_date') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">URL imagen</label>
                            <input type="text" name="image_url" value="{{ old('image_url') }}"
                                   style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;" placeholder="https://...">
                        </div>
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Descripción</label>
                        <textarea name="description" rows="3"
                                  style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">{{ old('description') }}</textarea>
                    </div>
                    <div style="display:flex; gap:1rem;">
                        <button type="submit"
                                style="background-color:#2563eb; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Guardar
                        </button>
                        <a href="{{ route('raw-materials.index') }}"
                           style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
