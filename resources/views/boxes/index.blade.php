<x-app-layout>

<x-slot name="header">
    📦 Control de Cajas
</x-slot>

<div class="p-6">

    {{-- KPIs --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:20px;
        margin-bottom:25px;
    ">

        <div style="background:white;padding:20px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1)">
            <div style="color:#64748b;font-size:13px;">Tipos de Caja</div>
            <div style="font-size:28px;font-weight:bold;color:#1e3a8a;">
                {{ $boxes->count() }}
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1)">
            <div style="color:#64748b;font-size:13px;">Stock Total</div>
            <div style="font-size:28px;font-weight:bold;color:#059669;">
                {{ number_format($boxes->sum('stock')) }}
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1)">
            <div style="color:#64748b;font-size:13px;">Bajo Stock</div>
            <div style="font-size:28px;font-weight:bold;color:#dc2626;">
                {{ $boxes->filter(fn($b) => $b->stock <= $b->minimum_stock)->count() }}
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1)">
            <div style="color:#64748b;font-size:13px;">Activos</div>
            <div style="font-size:28px;font-weight:bold;color:#2563eb;">
                {{ $boxes->where('active',1)->count() }}
            </div>
        </div>

    </div>

    {{-- Tabla --}}
    <div style="
        background:white;
        border-radius:10px;
        overflow:hidden;
        box-shadow:0 1px 3px rgba(0,0,0,.1);
    ">

        <div style="
            background:#1e3a8a;
            color:white;
            padding:14px 18px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        ">
            <span style="font-size:16px;font-weight:bold;">
                📦 Inventario de Cajas
            </span>

            <a href="{{ route('boxes.create') }}"
               style="
                    background:white;
                    color:#1e3a8a;
                    padding:8px 12px;
                    border-radius:6px;
                    text-decoration:none;
                    font-weight:bold;
               ">
                + Nueva Caja
            </a>
        </div>

        <div style="
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
">

 

        <table style="width:100%; border-collapse:collapse;">

            <thead>

                <tr style="background:#dbeafe;">

                    <th style="padding:12px;">Código</th>
                    <th style="padding:12px;">Caja</th>
                    <th style="padding:12px;">Stock</th>
                    <th style="padding:12px;">Stock Mínimo</th>
                    <th style="padding:12px;">Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

            @forelse($boxes as $box)

                <tr style="border-top:1px solid #e5e7eb;">

                    <td style="padding:12px;text-align:center;">
                        {{ $box->code }}
                    </td>

                    <td style="padding:12px;">
                        {{ $box->name }}
                    </td>

                    <td style="padding:12px;text-align:center;">

                        @if($box->stock <= $box->minimum_stock)

                            <span style="color:#dc2626;font-weight:bold;">
                                {{ number_format($box->stock) }}
                            </span>

                        @else

                            <span style="color:#059669;font-weight:bold;">
                                {{ number_format($box->stock) }}
                            </span>

                        @endif

                    </td>

                    <td style="padding:12px;text-align:center;">
                        {{ $box->minimum_stock }}
                    </td>

                    <td style="padding:12px;text-align:center;">

                        @if($box->stock <= $box->minimum_stock)

                            <span style="
                                background:#fee2e2;
                                color:#dc2626;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:12px;
                            ">
                                ⚠️ Reponer
                            </span>

                        @else

                            <span style="
                                background:#dcfce7;
                                color:#166534;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:12px;
                            ">
                                ✅ Normal
                            </span>

                        @endif

                    </td>
                <td style="padding:12px;text-align:center;">

                    <a href="{{ route('boxes.show',$box) }}"
                    style="
                        background:#2563eb;
                        color:white;
                        padding:6px 10px;
                        border-radius:6px;
                        text-decoration:none;
                        font-size:12px;
                    ">
                        📜
                    </a>

                    <a href="{{ route('boxes.edit',$box) }}"
                    style="
                        background:#f59e0b;
                        color:white;
                        padding:6px 10px;
                        border-radius:6px;
                        text-decoration:none;
                        font-size:12px;
                    ">
                        ✏️
                    </a>

                </td>
                </tr>

            @empty

                <tr>
                    <td colspan="5" style="padding:30px;text-align:center;color:#64748b;">
                        No existen cajas registradas
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>
<div style="
    background:white;
    margin-top:25px;
    border-radius:10px;
    overflow:hidden;
">

    <div style="
        background:#1e3a8a;
        color:white;
        padding:12px;
        font-weight:bold;
    ">
        📜 Últimos Movimientos
    </div>

    <table style="width:100%;">

        <thead>

            <tr style="background:#dbeafe;">
                <th class="p-3">Fecha</th>
                <th class="p-3">Caja</th>
                <th class="p-3">Tipo</th>
                <th class="p-3">Cantidad</th>
                <th class="p-3">Usuario</th>
            </tr>

        </thead>

        <tbody>

        @foreach($movements as $movement)

            <tr style="border-top:1px solid #e5e7eb;">

                <td class="p-3">
                    {{ $movement->created_at->format('d/m/Y H:i') }}
                </td>

                <td class="p-3">
                    {{ $movement->box->name }}
                </td>

                <td class="p-3">

                    @if($movement->type=='entrada')
                        <span style="color:#059669">
                            ➕ Entrada
                        </span>
                    @else
                        <span style="color:#dc2626">
                            ➖ Salida
                        </span>
                    @endif

                </td>

                <td class="p-3">
                    {{ number_format($movement->quantity) }}
                </td>

                <td class="p-3">
                    {{ $movement->user?->name }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>
    </div>

</div>

</x-app-layout>