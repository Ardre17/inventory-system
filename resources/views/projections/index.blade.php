<x-app-layout>
    <x-slot name="header">
        📈 Proyección de Demanda
    </x-slot>

    <div style="max-width:1200px; margin:0 auto;">

        {{-- Resumen global --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
            <div style="background:#fee2e2; border-radius:0.75rem; padding:1rem; text-align:center; border-left:4px solid #ef4444;">
                <div style="font-size:2rem; font-weight:800; color:#dc2626;">{{ $summary['critical'] }}</div>
                <div style="font-size:0.8rem; color:#991b1b; font-weight:600;">🚨 Stock Crítico (&lt;7 días)</div>
            </div>
            <div style="background:#fff7ed; border-radius:0.75rem; padding:1rem; text-align:center; border-left:4px solid #ea580c;">
                <div style="font-size:2rem; font-weight:800; color:#ea580c;">{{ $summary['warning'] }}</div>
                <div style="font-size:0.8rem; color:#c2410c; font-weight:600;">⚠️ Stock Bajo (&lt;15 días)</div>
            </div>
            <div style="background:#eff6ff; border-radius:0.75rem; padding:1rem; text-align:center; border-left:4px solid #2563eb;">
                <div style="font-size:2rem; font-weight:800; color:#2563eb;">{{ $summary['info'] }}</div>
                <div style="font-size:0.8rem; color:#1d4ed8; font-weight:600;">ℹ️ Atención (&lt;30 días)</div>
            </div>
            <div style="background:#f0fdf4; border-radius:0.75rem; padding:1rem; text-align:center; border-left:4px solid #16a34a;">
                <div style="font-size:2rem; font-weight:800; color:#16a34a;">{{ $summary['ok'] }}</div>
                <div style="font-size:0.8rem; color:#15803d; font-weight:600;">✅ Stock OK</div>
            </div>
        </div>

        {{-- Totales proyectados --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
            <div style="background:white; border-radius:0.75rem; padding:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                <div style="font-size:0.8rem; color:#6b7280; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">📦 Total a producir esta semana</div>
                <div style="font-size:1.8rem; font-weight:800; color:#7c3aed;">{{ number_format($summary['total_proy_week']) }} <span style="font-size:1rem; color:#6b7280;">unidades</span></div>
            </div>
            <div style="background:white; border-radius:0.75rem; padding:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                <div style="font-size:0.8rem; color:#6b7280; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">📦 Total a producir este mes</div>
                <div style="font-size:1.8rem; font-weight:800; color:#0891b2;">{{ number_format($summary['total_proy_month']) }} <span style="font-size:1rem; color:#6b7280;">unidades</span></div>
            </div>
        </div>

        {{-- Tabla de productos --}}
        <div style="background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.1); overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f3f4f6; font-weight:700; color:#0f172a;">
                📊 Análisis por Producto
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                            <th style="padding:10px 16px; text-align:left; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Producto</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Stock actual</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Vtas 4 sem.</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Vtas 1 mes</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Vtas/día</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Días stock</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Proyec. semana</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Proyec. mes</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Producir semana</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Producir mes</th>
                            <th style="padding:10px 8px; text-align:center; color:#6b7280; font-size:0.75rem; text-transform:uppercase;">Alerta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projections as $p)
                        @php
                            $rowBg = match($p['alert']) {
                                'critical' => '#fff5f5',
                                'warning'  => '#fffbf5',
                                'info'     => '#f0f9ff',
                                default    => 'white',
                            };
                        @endphp
                        <tr style="border-bottom:1px solid #f3f4f6; background:{{ $rowBg }};">
                            <td style="padding:10px 16px;">
                                <div style="font-weight:600; color:#1f2937;">{{ $p['product']->name }}</div>
                                <div style="font-size:0.75rem; color:#9ca3af;">{{ $p['product']->category->name ?? '—' }}</div>
                            </td>
                            <td style="padding:10px 8px; text-align:center; font-weight:700;
                                color:{{ $p['stock'] <= $p['product']->stock_min ? '#dc2626' : '#059669' }};">
                                {{ number_format($p['stock']) }}
                            </td>
                            <td style="padding:10px 8px; text-align:center; color:#374151;">{{ number_format($p['sales_4w']) }}</td>
                            <td style="padding:10px 8px; text-align:center; color:#374151;">{{ number_format($p['sales_1m']) }}</td>
                            <td style="padding:10px 8px; text-align:center; color:#6b7280;">{{ $p['daily_sales'] }}</td>
                            <td style="padding:10px 8px; text-align:center; font-weight:700;
                                color:{{ $p['days_of_stock'] === null ? '#9ca3af' : ($p['days_of_stock'] <= 7 ? '#dc2626' : ($p['days_of_stock'] <= 15 ? '#ea580c' : ($p['days_of_stock'] <= 30 ? '#2563eb' : '#059669'))) }};">
                                {{ $p['days_of_stock'] !== null ? $p['days_of_stock'] . ' días' : '—' }}
                            </td>
                            <td style="padding:10px 8px; text-align:center; font-weight:600; color:#7c3aed;">
                                {{ number_format($p['proy_week']) }}
                            </td>
                            <td style="padding:10px 8px; text-align:center; font-weight:600; color:#0891b2;">
                                {{ number_format($p['proy_month']) }}
                            </td>
                            <td style="padding:10px 8px; text-align:center; font-weight:700;
                                color:{{ $p['to_produce_week'] > 0 ? '#dc2626' : '#059669' }};">
                                {{ $p['to_produce_week'] > 0 ? '+' . number_format($p['to_produce_week']) : '✅ OK' }}
                            </td>
                            <td style="padding:10px 8px; text-align:center; font-weight:700;
                                color:{{ $p['to_produce_month'] > 0 ? '#ea580c' : '#059669' }};">
                                {{ $p['to_produce_month'] > 0 ? '+' . number_format($p['to_produce_month']) : '✅ OK' }}
                            </td>
                            <td style="padding:10px 8px; text-align:center;">
                                @if($p['alert'] === 'critical')
                                    <span style="background:#fee2e2; color:#dc2626; padding:3px 8px; border-radius:99px; font-size:0.72rem; font-weight:700;">🚨 CRÍTICO</span>
                                @elseif($p['alert'] === 'warning')
                                    <span style="background:#fff7ed; color:#ea580c; padding:3px 8px; border-radius:99px; font-size:0.72rem; font-weight:700;">⚠️ BAJO</span>
                                @elseif($p['alert'] === 'info')
                                    <span style="background:#eff6ff; color:#2563eb; padding:3px 8px; border-radius:99px; font-size:0.72rem; font-weight:700;">ℹ️ ATENCIÓN</span>
                                @else
                                    <span style="background:#f0fdf4; color:#16a34a; padding:3px 8px; border-radius:99px; font-size:0.72rem; font-weight:700;">✅ OK</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1rem; font-size:0.8rem; color:#9ca3af; text-align:center;">
            Actualizado: {{ now()->format('d/m/Y H:i') }} — Basado en órdenes completadas
        </div>
    </div>
</x-app-layout>
