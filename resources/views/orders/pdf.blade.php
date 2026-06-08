<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .header { padding: 16px 20px; border-bottom: 3px solid #1e3a8a; margin-bottom: 12px; }
        .header-grid { display: flex; justify-content: space-between; align-items: start; }
        .company-name { font-size: 20px; font-weight: 900; color: #1e3a8a; letter-spacing: 2px; }
        .company-sub { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .order-badge { background: #1e3a8a; color: white; padding: 6px 14px; border-radius: 6px; text-align: center; }
        .order-badge-num { font-size: 13px; font-weight: 700; }
        .order-badge-label { font-size: 9px; }
        .info-section { padding: 0 20px; margin-bottom: 12px; }
        .info-grid { display: flex; gap: 20px; }
        .info-box { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; }
        .info-label { font-size: 9px; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-bottom: 3px; }
        .info-value { font-size: 12px; font-weight: 600; color: #1f2937; }
        .table-section { padding: 0 20px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1e3a8a; color: white; }
        thead th { padding: 7px 6px; text-align: center; font-size: 10px; font-weight: 700; }
        thead th:nth-child(4) { text-align: left; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr.no-enviado { background: #fee2e2; }
        tbody td { padding: 6px; text-align: center; font-size: 10px; }
        tbody td:nth-child(4) { text-align: left; font-weight: 600; }
            .status-complete {
        color: #059669;
        font-weight: 700;
    }

    .status-partial {
        color: #d97706;
        font-weight: 700;
    }

    .status-none {
        color: #dc2626;
        font-weight: 700;
    }
        .totals { padding: 0 20px; display: flex; justify-content: flex-end; margin-bottom: 16px; }
        .totals-box { width: 260px; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 12px; font-size: 11px; }
        .totals-row.highlight { background: #1e3a8a; color: white; font-weight: 700; font-size: 13px; }
        .totals-row.sub { background: #f8fafc; }

        .watermark {
    position: fixed;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    opacity: 0.06;
    font-size: 90px;
    font-weight: 900;
    color: #1e3a8a;
    letter-spacing: 10px;
    z-index: -1;
}
    </style>
</head>
<body>
<div class="watermark">DISTAN</div>

    <div class="header">
        <div class="header-grid">
            <div>
                <div class="company-name">DISTAN</div>
                <div class="company-sub">Todo tu logística, en un solo lugar</div>
            </div>
            <div class="order-badge">
                <div class="order-badge-label">ORDEN</div>
                <div class="order-badge-num">{{ $order->order_number }}</div>
                <div class="order-badge-label">{{ strtoupper($order->order_type) }}</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">Cliente / Proveedor</div>
                <div class="info-value">{{ $order->client_supplier ?? '—' }}</div>
            </div>
            @if($order->client_order_number)
            <div class="info-box">
                <div class="info-label">Nro. Orden Cliente</div>
                <div class="info-value">{{ $order->client_order_number }}</div>
            </div>
            @endif
            <div class="info-box">
                <div class="info-label">Fecha</div>
                <div class="info-value">{{ $order->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Estado</div>
                <div class="info-value">{{ $order->status === 'completed' ? '✅ Completada' : '⏳ Pendiente' }}</div>
            </div>
        </div>
    </div>

    <div class="table-section">
        <table>
            <thead>
                <tr>
                    @if($order->order_type === 'supermercado')
                    <th>Paleta</th>
                    <th>Pucho</th>
                    <th>Vencimiento</th>
                    @endif
                    <th style="text-align:left;">Descripción</th>
                    <th>Cnt. Solicitada</th>
                    <th>Cnt. Enviada</th>
                    <th>Precio Neto</th>
                    <th>Precio c/Imp.</th>
                    <th>Total Neto</th>
                    <th>Total c/Imp.</th>
                </tr>
            </thead>
           <tbody>
@foreach($order->items as $item)
@php
    $precioImp = $item->unit_price * 1.18;
    $totalNeto = $item->unit_price * $item->quantity_sent;
    $totalImp  = $precioImp * $item->quantity_sent;
    $noEnviado = $item->dispatch_status === 'none';

    $statusClass = match($item->dispatch_status) {
        'complete' => 'status-complete',
        'partial'  => 'status-partial',
        'none'     => 'status-none',
        default    => '',
    };
@endphp

<tr style="{{ $noEnviado ? 'background:#fee2e2;' : '' }}">

    @if($order->order_type === 'supermercado')
    <td style="font-weight:700; color:#1e3a8a;">
        {{ $item->pallet_number ?? '—' }}
    </td>

    <td>
        {{ $item->pucho ?? 0 }}
    </td>

    <td style="color:#0369a1;">
        {{ $item->dispatch_expiration_date ? $item->dispatch_expiration_date->format('d/m/Y') : '—' }}
    </td>
    @endif

    <td style="text-align:left; font-weight:600;">
        <span style="{{ $noEnviado ? 'text-decoration:line-through;color:#dc2626;' : '' }}">
            {{ $item->product?->name ?? $item->rawMaterial?->name ?? 'Producto no encontrado' }}
        </span>

        @if($noEnviado)
        <div style="
            color:#dc2626;
            font-size:9px;
            font-weight:700;
            margin-top:2px;
        ">
            ✖ NO ENVIADO
        </div>
        @endif
    </td>

    <td>
        {{ number_format($item->quantity) }}
    </td>

    <td class="{{ $statusClass }}">
        {{ number_format($item->quantity_sent) }}
    </td>

    <td>
        {{ $noEnviado ? '—' : number_format($item->unit_price, 3) }}
    </td>

    <td>
        {{ $noEnviado ? '—' : number_format($precioImp, 3) }}
    </td>

    <td>
        {{ $noEnviado ? '—' : number_format($totalNeto, 3) }}
    </td>

    <td>
        {{ $noEnviado ? '—' : number_format($totalImp, 3) }}
    </td>

</tr>
@endforeach
</tbody>
        </table>
    </div>

    @php
        $subtotalEnviado = $order->items->sum(function($i) {
            return $i->dispatch_status !== 'none' ? $i->unit_price * $i->quantity_sent : 0;
        });
        $igv   = $subtotalEnviado * 0.18;
        $total = $subtotalEnviado + $igv;
    @endphp

    <div class="totals">
        <div class="totals-box">
            <div class="totals-row sub">
                <span>Subtotal enviado:</span>
                <span>S/. {{ number_format($subtotalEnviado, 3) }}</span>
            </div>
            <div class="totals-row sub">
                <span>IGV (18%):</span>
                <span>S/. {{ number_format($igv, 3) }}</span>
            </div>
            <div class="totals-row highlight">
                <span>TOTAL:</span>
                <span>S/. {{ number_format($total, 3) }}</span>
            </div>
        </div>
    </div>

    <div style="padding:0 20px; font-size:10px; color:#6b7280; margin-bottom:40px;">
        
    {{-- Distribución de Paletas --}}

@php

$pallets = [];

foreach ($order->items as $item) {

    if (!$item->pallet_number) {
        continue;
    }

    $detallePaletas = explode(',', $item->pallet_number);

    foreach ($detallePaletas as $detalle) {

        $detalle = trim($detalle);

        if (str_contains($detalle, '=')) {

            [$paleta, $cantidad] = explode('=', $detalle);

            $paleta   = trim($paleta);
            $cantidad = (int) trim($cantidad);

        } else {

            $paleta   = trim($detalle);
            $cantidad = $item->quantity_sent;

        }

        $pallets[$paleta][] = [
            'producto' => $item->product?->name
                        ?? $item->rawMaterial?->name
                        ?? 'Producto',
            'cantidad' => $cantidad,
            'pucho'    => $item->pucho ?? 0,
        ];
    }
}

ksort($pallets);

@endphp

<div style="padding:0 20px; margin-top:30px;">

@php
$distribucionPaletas = [];

foreach($order->items as $item){

    if(empty($item->pallet_number)){
        continue;
    }

    $paletas = explode(',', $item->pallet_number);

    foreach($paletas as $paleta){

        $paleta = trim($paleta);

        if(!$paleta){
            continue;
        }

        $distribucionPaletas[$paleta][] = [
            'producto' => $item->product?->name
                ?? $item->rawMaterial?->name
                ?? 'Producto',

            'cantidad' => $item->quantity_sent
        ];
    }
}

ksort($distribucionPaletas);
@endphp

    <div style="
        background:#1e3a8a;
        color:white;
        padding:10px;
        font-weight:bold;
        border-radius:6px;
        margin-bottom:15px;
        text-align:center;
    ">
        📦 DISTRIBUCIÓN DE PALETAS
    </div>

    @foreach($pallets as $paleta => $productos)

    @php

        $totalPaleta = collect($productos)->sum('cantidad');

        $pesoTotalPaleta = 0;

        foreach($productos as $producto){

            $itemPeso = $order->items
                ->first(function($i) use ($producto){
                    return ($i->product?->name ?? $i->rawMaterial?->name) === $producto['producto'];
                });

            if($itemPeso && $itemPeso->product){
                $pesoTotalPaleta += (
                    ($itemPeso->product->unit_weight ?? 0)
                    * $producto['cantidad']
                ) / 1000;
            }
        }

@endphp

    <div style="
        border:1px solid #cbd5e1;
        border-radius:6px;
        margin-bottom:15px;
        overflow:hidden;
    ">

        <div style="
            background:#1e40af;
            color:white;
            padding:8px 12px;
            font-weight:bold;
        ">
            Paleta {{ $paleta }}
        </div>

        <table style="width:100%; border-collapse:collapse;">

            <thead>
                <tr>
                    <th style="
                        background:#1e3a8a;
                        color:white;
                        padding:6px;
                        text-align:left;
                    ">
                        Producto
                    </th>

                   <th style="
    background:#1e3a8a;
    color:white;
    padding:6px;
    width:100px;
">
    Unidades
</th>

<th style="
    background:#1e3a8a;
    color:white;
    padding:6px;
    width:120px;
">
    Peso (Kg)
</th>
                </tr>
            </thead>

            <tbody>

                @foreach($productos as $producto)

@php

$itemPeso = $order->items
    ->first(function($i) use ($producto) {
        return ($i->product?->name ?? $i->rawMaterial?->name) === $producto['producto'];
    });

$pesoKg = 0;

if($itemPeso && $itemPeso->product){
    $pesoKg = (($itemPeso->product->unit_weight ?? 0) * $producto['cantidad']) / 1000;
}

@endphp

<tr>

    <td style="padding:6px;">
        {{ $producto['producto'] }}
            </td>

            <td style="
                text-align:center;
                padding:6px;
                font-weight:bold;
            ">
                {{ number_format($producto['cantidad']) }}
            </td>

            <td style="
                text-align:center;
                padding:6px;
                font-weight:bold;
                color:#1e3a8a;
            ">
                {{ number_format($pesoKg,2) }}
            </td>

        </tr>

        @endforeach

              <tr style="background:#dbeafe;">

    <td style="
        font-weight:bold;
        padding:6px;
    ">
        TOTAL PALETA
    </td>

    <td style="
        text-align:center;
        font-weight:bold;
        color:#1e3a8a;
    ">
        {{ number_format($totalPaleta) }}
    </td>

    <td style="
        text-align:center;
        font-weight:bold;
        color:#059669;
    ">
        {{ number_format($pesoTotalPaleta,2) }} Kg
    </td>

</tr>

            </tbody>

        </table>

    </div>

    @endforeach

    @if($order->order_type === 'supermercado')

<div style="
    margin:20px;
    border:1px solid #0f172a;
    border-radius:8px;
    overflow:hidden;
">

    <div style="
    background: #cbd5e1;
    color:white;
    padding:10px;
    font-weight:bold;
    text-align:center;
    letter-spacing:1px;
">
    🚚 RESUMEN LOGÍSTICO
</div>
     @php

$pesoTotalDespacho = 0;

foreach($order->items as $item){

    if($item->product){

        $pesoTotalDespacho += (
            ($item->product->unit_weight ?? 0)
            * $item->quantity_sent
        ) / 1000;

    }

}

@endphp
    <table style="width:100%; border-collapse:collapse;">
        <thead>
           <tr>

   <table style="width:100%; border-collapse:collapse;">

    <thead>
        <tr style="background:#2563eb; color:white;">
            <th style="padding:8px;">Paletas</th>
            <th style="padding:8px;">Productos</th>
            <th style="padding:8px;">Unidades</th>
            <th style="padding:8px;">Peso Total (Kg)</th>
        </tr>
    </thead>

    <tbody>
        <tr>

            <td style="padding:8px; text-align:center;">
                {{ count($distribucionPaletas) }}
            </td>

            <td style="padding:8px; text-align:center;">
                {{ $order->items->count() }}
            </td>

            <td style="padding:8px; text-align:center;">
                {{ number_format($order->items->sum('quantity_sent')) }}
            </td>

            <td style="padding:8px; text-align:center; font-weight:bold; color:#059669;">
                {{ number_format($pesoTotalDespacho,2) }} Kg
            </td>

        </tr>
    </tbody>

</table>

</div>

@endif
</div>
@php
$productosNoEnviados = $order->items->filter(function($item){
    return $item->quantity_sent <= 0;
});
@endphp

@if($productosNoEnviados->count())

<div style="
    margin:20px;
    border:1px solid #fecaca;
    border-radius:8px;
    overflow:hidden;
">

    <div style="
        background:#dc2626;
        color:black;
        padding:10px;
        font-weight:bold;
    ">
        ⚠ PRODUCTOS NO DESPACHADOS
    </div>

    <table style="width:100%; border-collapse:collapse;">

        <thead>
            <tr style="background:#fee2e2;">
                <th style="padding:8px; text-align:left;">Producto</th>
                <th style="padding:8px;">Solicitado</th>
                <th style="padding:8px;">Enviado</th>
                <th style="padding:8px;">Faltante</th>
            </tr>
        </thead>

        <tbody>

        @foreach($productosNoEnviados as $item)

        <tr>

            <td style="padding:8px;">
                {{ $item->product?->name ?? $item->rawMaterial?->name }}
            </td>

            <td style="padding:8px; text-align:center;">
                {{ number_format($item->quantity) }}
            </td>

            <td style="
                padding:8px;
                text-align:center;
                color:#dc2626;
                font-weight:bold;
            ">
                0
            </td>

            <td style="
                padding:8px;
                text-align:center;
                font-weight:bold;
            ">
                {{ number_format($item->quantity) }}
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endif
    
    @php

$totalSolicitado = $order->items->sum('quantity');
$totalEnviado    = $order->items->sum('quantity_sent');

$porcentajeCumplimiento = $totalSolicitado > 0
    ? ($totalEnviado / $totalSolicitado) * 100
    : 0;

$colorCumplimiento =
    $porcentajeCumplimiento >= 100 ? '#059669' :
    ($porcentajeCumplimiento >= 80 ? '#d97706' : '#dc2626');

@endphp

<div style="
    margin:20px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    overflow:hidden;
">

    <div style="
        background:#0f172a;
        color:white;
        padding:10px;
        font-weight:bold;
    ">
        📊 CUMPLIMIENTO DEL DESPACHO
    </div>

    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="padding:10px; text-align:center;">
                <strong>Solicitado</strong><br>
                {{ number_format($totalSolicitado) }}
            </td>

            <td style="padding:10px; text-align:center;">
                <strong>Enviado</strong><br>
                {{ number_format($totalEnviado) }}
            </td>

            <td style="
                padding:10px;
                text-align:center;
                font-weight:bold;
                color:{{ $colorCumplimiento }};
                font-size:16px;
            ">
                {{ number_format($porcentajeCumplimiento,2) }}%
            </td>
        </tr>
    </table>

</div>
    </div>


</body>
</html>
