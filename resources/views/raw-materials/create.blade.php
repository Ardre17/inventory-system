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
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Unidad *</label>
                            <select name="unit" class="w-full border rounded-lg px-3 py-2" required>
                                @foreach(['kg','g','L','ml','unid','caja','bolsa','saco'] as $u)
                                <option value="{{ $u }}" {{ old('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Stock actual *</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   step="0.001" min="0" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Stock mínimo *</label>
                            <input type="number" name="stock_min" value="{{ old('stock_min', 0) }}"
                                   step="0.001" min="0" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Costo *</label>
                            <input type="number" name="cost" value="{{ old('cost', 0) }}"
                                   step="0.01" min="0" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Proveedor</label>
                            <select name="supplier_id" class="w-full border rounded-lg px-3 py-2">
                                <option value="">— Sin proveedor —</option>
                                @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Lote</label>
                            <input type="text" name="lot" value="{{ old('lot') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha vencimiento</label>
                            <input type="date" name="expiration_date" value="{{ old('expiration_date') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">URL imagen</label>
                            <input type="text" name="image_url" value="{{ old('image_url') }}"
                                   class="w-full border rounded-lg px-3 py-2" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Descripción</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex gap-3">
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
