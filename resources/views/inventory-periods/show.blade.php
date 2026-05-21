<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 {{ $inventoryPeriod->name }}
            </h2>
            <span style="background:{{ $inventoryPeriod->status === 'open' ? '#dcfce7' : '#f3f4f6' }}; color:{{ $inventoryPeriod->status === 'open' ? '#166534' : '#374151' }}; padding:0.25rem 0.75rem; border-radius:1rem; font-size:0.875rem;">
                {{ $inventoryPeriod->status === 'open' ? '🟢 Abierto' : '🔒 Cerrado' }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.75rem 1rem; border-radius:0.5rem; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
            @endif

            @if($inventoryPeriod->status === 'open')
            <form method="POST" action="{{ route('inventory-periods.update', $inventoryPeriod) }}">
                @csrf
                @method('PUT')

                <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#f3f4f6;">
                                <th class="text-left p-3">Producto</th>
                                <th class="text-left p-3">Categoría</th>
                                <th class="text-right p-3">Stock Inicial</th>
                                <th class="text-right p-3">Conteo Físico</th>
                                <th class="text-right p-3">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryPeriod->items as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">{{ $item->product->name }}</td>
                                <td class="p-3 text-gray-500">{{ $item->product->category->name ?? '-' }}</td>
                                <td class="p-3 text-right">{{ $item->initial_stock }}</td>
                                <td class="p-3 text-right">
                                    <input type="number"
                                           name="physical_count[{{ $item->id }}]"
                                           value="{{ $item->physical_count ?? $item->initial_stock }}"
                                           min="0"
                                           style="width:80px; border:1px solid #d1d5db; border-radius:0.375rem; padding:0.25rem 0.5rem; text-align:right;">
                                </td>
                                <td class="p-3 text-right" style="color:{{ $item->difference < 0 ? '#ef4444' : ($item->difference > 0 ? '#16a34a' : '#374151') }}; font-weight:600;">
                                    {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-3">
                    <button type="submit" name="action" value="save"
                            style="background-color:#ea580c; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                        💾 Guardar Conteo
                    </button>
                    <button type="submit" name="action" value="close"
                            onclick="return confirm('¿Cerrar este inventario? Esta acción actualizará el stock de todos los productos.')"
                            style="background-color:#374151; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                        🔒 Cerrar Inventario
                    </button>
                    <a href="{{ route('inventory-periods.index') }}"
                       style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                        ← Volver
                    </a>
                </div>
            </form>

            @else
            {{-- Vista de solo lectura para inventarios cerrados --}}
            <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th class="text-left p-3">Producto</th>
                            <th class="text-left p-3">Categoría</th>
                            <th class="text-right p-3">Stock Inicial</th>
                            <th class="text-right p-3">Conteo Físico</th>
                            <th class="text-right p-3">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventoryPeriod->items as $item)
                        <tr class="border-b">
                            <td class="p-3 font-semibold">{{ $item->product->name }}</td>
                            <td class="p-3 text-gray-500">{{ $item->product->category->name ?? '-' }}</td>
                            <td class="p-3 text-right">{{ $item->initial_stock }}</td>
                            <td class="p-3 text-right">{{ $item->physical_count ?? '-' }}</td>
                            <td class="p-3 text-right" style="color:{{ $item->difference < 0 ? '#ef4444' : ($item->difference > 0 ? '#16a34a' : '#374151') }}; font-weight:600;">
                                {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('inventory-periods.index') }}"
               style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                ← Volver
            </a>
            @endif

        </div>
    </div>
</x-app-layout>