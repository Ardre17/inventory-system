<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- KPIs --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                <div style="background:white; border-radius:0.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem; text-align:center;">
                    <div style="font-size:2rem; font-weight:700; color:#2563eb;">{{ $totalProducts }}</div>
                    <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Productos</div>
                </div>
                <div style="background:white; border-radius:0.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem; text-align:center;">
                    <div style="font-size:2rem; font-weight:700; color:#16a34a;">{{ $totalCategories }}</div>
                    <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Categorías</div>
                </div>
                <div style="background:white; border-radius:0.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem; text-align:center;">
                    <div style="font-size:2rem; font-weight:700; color:#ef4444;">{{ $lowStock }}</div>
                    <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Stock Bajo</div>
                </div>
                <div style="background:white; border-radius:0.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem; text-align:center;">
                    <div style="font-size:2rem; font-weight:700; color:#7c3aed;">{{ $totalOrders }}</div>
                    <div style="font-size:0.875rem; color:#6b7280; margin-top:0.25rem;">Órdenes</div>
                </div>
            </div>

            {{-- Alertas stock bajo --}}
            @if($lowStockProducts->count() > 0)
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem;">
                <div style="font-weight:600; color:#991b1b; margin-bottom:0.5rem;">⚠️ Productos con stock bajo</div>
                @foreach($lowStockProducts as $product)
                <div style="display:flex; justify-content:space-between; font-size:0.875rem; padding:0.25rem 0; border-bottom:1px solid #fecaca;">
                    <span style="color:#374151;">{{ $product->name }}</span>
                    <span style="color:#ef4444; font-weight:700;">{{ $product->stock }} {{ $product->unit }}</span>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Accesos rápidos --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <a href="{{ route('categories.index') }}"
                   style="background-color:#2563eb; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    📦 Categorías
                </a>
                <a href="{{ route('products.index') }}"
                   style="background-color:#16a34a; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    🏷️ Productos
                </a>
                <a href="{{ route('orders.index') }}"
                   style="background-color:#7c3aed; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    🧾 Órdenes
                </a>
                <a href="{{ route('inventory-periods.index') }}"
                   style="background-color:#ea580c; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    📋 Inventario
                </a>
                <a href="{{ route('suppliers.index') }}"
                   style="background-color:#0891b2; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    🏭 Proveedores
                </a>
                <a href="{{ route('clients.index') }}"
                   style="background-color:#be185d; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    👥 Clientes
                </a>
                <a href="{{ route('production-orders.index') }}"
                   style="background-color:#1d4ed8; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    🏭 Producción
                </a>
                <a href="{{ route('raw-materials.index') }}"
                   style="background-color:#0f766e; color:white; border-radius:0.5rem; padding:1rem; text-align:center; font-weight:700; display:block; text-decoration:none;">
                    🧪 Materias Primas
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
EOF