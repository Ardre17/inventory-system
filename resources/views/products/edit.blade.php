<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Categoría *</label>
                        <select name="category_id" class="w-full border rounded-lg px-3 py-2">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
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
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Unidad</label>
                        <select name="unit" class="w-full border rounded-lg px-3 py-2">
                            <option value="unidad" {{ $product->unit == 'unidad' ? 'selected' : '' }}>Unidad</option>
                            <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                            <option value="litro" {{ $product->unit == 'litro' ? 'selected' : '' }}>Litro</option>
                            <option value="caja" {{ $product->unit == 'caja' ? 'selected' : '' }}>Caja</option>
                            <option value="paquete" {{ $product->unit == 'paquete' ? 'selected' : '' }}>Paquete</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                               class="w-full border rounded-lg px-3 py-2">
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
</x-app-layout>