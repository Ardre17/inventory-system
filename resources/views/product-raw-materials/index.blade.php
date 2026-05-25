<x-app-layout>
    <x-slot name="header">
        🧪 Materias Primas por Producto
    </x-slot>

    <div style="max-width:900px; margin:0 auto;">

        <p style="color:#6b7280; font-size:14px; margin-bottom:1.5rem;">
            Asigna qué materias primas consume cada producto al ser producido.
        </p>

        <div style="display:grid; gap:1rem;">
            @foreach($products as $product)
            <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:1.25rem; display:flex; justify-content:space-between; align-items:center;">
                <div style="flex:1;">
                    <div style="font-weight:700; color:#1f2937; font-size:0.95rem;">{{ $product->name }}</div>
                    <div style="margin-top:0.5rem; display:flex; flex-wrap:wrap; gap:0.5rem;">
                        @forelse($product->rawMaterials as $rm)
                        <span style="background:#f0fdf4; color:#059669; border:1px solid #bbf7d0; padding:3px 10px; border-radius:99px; font-size:0.75rem;">
                            {{ $rm->name }}
                            <strong>{{ $rm->pivot->quantity }} {{ $rm->unit }}</strong>
                            / unidad
                        </span>
                        @empty
                        <span style="color:#9ca3af; font-size:0.8rem;">Sin materias primas asignadas</span>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('product-raw-materials.edit', $product) }}"
                   style="background:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none; font-size:0.85rem; font-weight:600; margin-left:1rem; white-space:nowrap;">
                    ✏️ Editar
                </a>
            </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
