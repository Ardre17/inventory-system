<x-app-layout>
    <x-slot name="header">
        🏷️ {{ $supply->name }}
    </x-slot>

<div style="display:grid; grid-template-columns:320px 1fr; gap:24px; align-items:start;">

    {{-- Panel izquierdo --}}
    <div>
        <div style="background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:24px; margin-bottom:20px;">
            <div style="font-size:11px; color:#9ca3af; margin-bottom:4px;">{{ $supply->code }}</div>
            <div style="font-size:20px; font-weight:700; color:#1f2937; margin-bottom:16px;">{{ $supply->name }}</div>

            <div style="text-align:center; padding:20px; background:{{ $supply->isLowStock() ? '#fff7ed' : '#f0fdf4' }}; border-radius:10px; margin-bottom:16px;">
                <div style="font-size:48px; font-weight:800; color:{{ $supply->isLowStock() ? '#dc2626' : '#059669' }};">
                    {{ number_format($supply->stock) }}
                </div>
                <div style="font-size:14px; color:#6b7280;">unidades en stock</div>
                @if($supply->isLowStock())
                <div style="margin-top:8px; background:#fee2e2; color:#dc2626; padding:4px 12px; border-radius:99px; font-size:12px; font-weight:700; display:inline-block;">
                    ⚠️ Stock bajo
                </div>
                @endif
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:13px; color:#6b7280; margin-bottom:16px;">
                <div>Unidades/rollo<br><strong style="color:#1f2937; font-size:16px;">{{ $supply->units_per_roll }}</strong></div>
                <div>Stock mínimo<br><strong style="color:#1f2937; font-size:16px;">{{ number_format($supply->stock_min) }}</strong></div>
            </div>

            <form method="POST" action="{{ route('supplies.updateMin',$supply) }}">
                @csrf
                <label style="display:block; font-size:12px; font-weight:600; color:#6b7280; margin-bottom:6px;">Actualizar stock mínimo</label>
                <div style="display:flex; gap:8px;">
                    <input type="number" name="stock_min" value="{{ $supply->stock_min }}" min="0"
                           style="flex:1; padding:8px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;">
                    <button type="submit"
                            style="background:#f3f4f6; color:#374151; padding:8px 12px; border:none; border-radius:6px; cursor:pointer; font-size:13px; font-weight:600;">
                        Guardar
                    </button>
                </div>
            </form>
        </div>

        {{-- Entrada de stock --}}
        <div style="background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.1); padding:24px;">
            <h3 style="font-size:15px; font-weight:700; color:#1f2937; margin-bottom:16px;">📥 Registrar Entrada</h3>

            @if(session('success'))
            <div style="background:#d1fae5; color:#065f46; padding:10px 14px; border-radius:8px; margin-bottom:14px; font-size:13px;">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('supplies.entry',$supply) }}">
                @csrf
                <div style="margin-bottom:14px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Cantidad de rollos / paquetes *</label>
                    <input type="number" name="rolls" min="1" required placeholder="ej. 5"
                           style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Unidades por rollo / paquete *</label>
                    <input type="number" name="units_per_roll" min="1" value="{{ $supply->units_per_roll }}" required
                           style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Notas (proveedor, factura...)</label>
                    <input type="text" name="notes" placeholder="opcional"
                           style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;">
                </div>
                <button type="submit"
                        style="width:100%; background:#2563eb; color:white; padding:12px; border:none; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer;">
                    + Registrar entrada
                </button>
            </form>
        </div>
    </div>

    {{-- Historial --}}
    <div style="background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.1); overflow:hidden;">
        <div style="padding:18px 20px; border-bottom:1px solid #f3f4f6;">
            <h3 style="font-size:15px; font-weight:700; color:#1f2937; margin:0;">Historial de movimientos</h3>
        </div>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;">
                    <th style="padding:10px 16px; text-align:left; font-size:12px; color:#6b7280; text-transform:uppercase;">Fecha</th>
                    <th style="padding:10px 16px; text-align:left; font-size:12px; color:#6b7280; text-transform:uppercase;">Tipo</th>
                    <th style="padding:10px 16px; text-align:right; font-size:12px; color:#6b7280; text-transform:uppercase;">Rollos</th>
                    <th style="padding:10px 16px; text-align:right; font-size:12px; color:#6b7280; text-transform:uppercase;">Unidades</th>
                    <th style="padding:10px 16px; text-align:left; font-size:12px; color:#6b7280; text-transform:uppercase;">Referencia / Notas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr style="border-bottom:1px solid #f9fafb;">
                    <td style="padding:10px 16px; font-size:13px; color:#6b7280;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding:10px 16px;">
                        @if($m->movement_type === 'entry')
                        <span style="background:#d1fae5; color:#059669; padding:3px 10px; border-radius:99px; font-size:12px; font-weight:600;">↑ Entrada</span>
                        @else
                        <span style="background:#fee2e2; color:#dc2626; padding:3px 10px; border-radius:99px; font-size:12px; font-weight:600;">↓ Salida</span>
                        @endif
                    </td>
                    <td style="padding:10px 16px; text-align:right; font-size:13px; color:#374151;">{{ $m->rolls > 0 ? $m->rolls : '—' }}</td>
                    <td style="padding:10px 16px; text-align:right; font-weight:700; color:{{ $m->movement_type === 'entry' ? '#059669' : '#dc2626' }};">
                        {{ $m->movement_type === 'entry' ? '+' : '-' }}{{ number_format(abs($m->quantity)) }}
                    </td>
                    <td style="padding:10px 16px; font-size:13px; color:#6b7280;">
                        {{ $m->reference ?? '—' }}
                        @if($m->notes)<br><span style="font-size:11px;">{{ $m->notes }}</span>@endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px; text-align:center; color:#9ca3af;">Sin movimientos aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $movements->links() }}</div>
    </div>
</div>

</x-app-layout>
