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
        tbody td { padding: 6px; text-align: center; font-size: 10px; }
        tbody td:nth-child(4) { text-align: left; font-weight: 600; }

        .status-complete { color: #059669; font-weight: 700; }
        .status-partial { color: #d97706; font-weight: 700; }
        .status-none { color: #dc2626; font-weight: 700; }

        .totals { padding: 0 20px; display: flex; justify-content: flex-end; margin-bottom: 16px; }
        .totals-box { width: 260px; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 12px; font-size: 11px; }
        .totals-row.highlight { background: #1e3a8a; color: white; font-weight: 700; font-size: 13px; }
        .totals-row.sub { background: #f8fafc; }

        .watermark {
            position: fixed; bottom: 20px; left: 0; right: 0;
            text-align: center;
            opacity: 0.12;
            font-size: 48px;
            font-weight: 900;
            color: #1e3a8a;
            letter-spacing: 8px;
            text-transform: uppercase;
        }

        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            border-top: 1px solid #e2e8f0;
            padding: 6px 20px;
            display: flex; justify-content: space-between;
            font-size: 9px; color: #9ca3af;
            background: white;
        }
    </style>
</head>
<body>

    {{-- Marca de agua --}}
    <div class="watermark">DISTAN</div>

    {{-- Header --}}
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

    {{-- Info general --}}
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

    {{-- Tabla de productos --}}
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
                    $totalNeto = $item->unit_price * $item->quantity;
                    $totalImp  = $precioImp * $item->quantity;

                    if ($item->quantity_sent == 0) {
                        $statusClass = 'status-none';
                    } elseif ($item->quantity_sent < $item->quantity) {
                        $statusClass = 'status-partial';
                    } else {
                        $statusClass = 'status-complete';
                    }
                @endphp
                <tr>
                    @if($order->order_type === 'supermercado')
                    <td style="font-weight:700; color:#1e3a8a;">{{ $item->pallet_number ?? '—' }}</td>
                    <td>{{ $item->pucho ?? 0 }}</td>
                    <td style="color:#0369a1;">
                        {{ $item->dispatch_expiration_date ? $item->dispatch_expiration_date->format('d/m/Y') : '—' }}
                    </td>
                    @endif
                    <td style="text-align:left; font-weight:600;">{{ $item->product->name }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td class="{{ $statusClass }}">{{ number_format($item->quantity_sent) }}</td>
                    <td>{{ number_format($item->unit_price, 3) }}</td>
                    <td>{{ number_format($precioImp, 3) }}</td>
                    <td>{{ number_format($totalNeto, 3) }}</td>
                    <td>{{ number_format($totalImp, 3) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totales --}}
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row sub">
                <span>Subtotal:</span>
                <span>S/. {{ number_format($order->subtotal, 3) }}</span>
            </div>
            <div class="totals-row sub">
                <span>IGV (18%):</span>
                <span>S/. {{ number_format($order->subtotal * 0.18, 3) }}</span>
            </div>
            <div class="totals-row highlight">
                <span>TOTAL:</span>
                <span>S/. {{ number_format($order->subtotal * 1.18, 3) }}</span>
            </div>
        </div>
    </div>

    {{-- Total productos --}}
    <div style="padding:0 20px; font-size:10px; color:#6b7280; margin-bottom:40px;">
        Total de productos: <strong>{{ $order->items->sum('quantity') }}</strong> unidades |
        Total enviado: <strong>{{ $order->items->sum('quantity_sent') }}</strong> unidades
    </div>

    {{-- Footer --}}
    <div class="footer">
        <span>DISTAN — Todo tu logística, en un solo lugar</span>
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>{{ $order->order_number }}</span>
    </div>

</body>
</html>
