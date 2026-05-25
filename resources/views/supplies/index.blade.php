<x-app-layout>
    <x-slot name="header">
        🏷️ Almacén de Suministros
    </x-slot>

@php
$sections = [
    ['label'=>'🏷️ Stickers',  'color'=>'#2563eb', 'items'=>$stickers],
    ['label'=>'🔒 Precintos', 'color'=>'#7c3aed', 'items'=>$precintos],
];
@endphp

{{-- Stickers y Precintos --}}
@foreach($sections as $section)
<div style="margin-bottom:2rem;">
    <h2 style="font-size:1rem; font-weight:700; color:#1f2937; margin-bottom:1rem;">{{ $section['label'] }}</h2>
    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:14px;">
        @foreach($section['items'] as $supply)
        @php $low = $supply->isLowStock(); @endphp
        <a href="{{ route('supplies.show',$supply) }}" style="text-decoration:none;">
            <div style="background:white; border-radius:12px; border:2px solid {{ $low ? '#fca5a5' : '#e5e7eb' }}; padding:18px; position:relative;">
                @if($low)
                <span style="position:absolute; top:10px; right:10px; background:#fee2e2; color:#dc2626; font-size:10px; font-weight:700; padding:2px 8px; border-radius:99px;">BAJO</span>
                @endif
                <div style="font-size:11px; color:#9ca3af; margin-bottom:4px;">{{ $supply->code }}</div>
                <div style="font-size:14px; font-weight:700; color:#1f2937; margin-bottom:12px;">{{ $supply->name }}</div>
                <div style="font-size:30px; font-weight:800; color:{{ $low ? '#dc2626' : $section['color'] }};">
                    {{ number_format($supply->stock) }}
                </div>
                <div style="font-size:12px; color:#6b7280; margin-top:2px;">unidades</div>
                <div style="margin-top:10px; padding-top:10px; border-top:1px solid #f3f4f6; font-size:12px; color:#9ca3af;">
                    Mín: {{ number_format($supply->stock_min) }} · {{ $supply->units_per_roll }} u/rollo
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endforeach

{{-- Etiquetas agrupadas por producto --}}
<div style="margin-bottom:2rem;">
    <h2 style="font-size:1rem; font-weight:700; color:#1f2937; margin-bottom:1rem;">📄 Etiquetas por Producto</h2>

    @foreach($etiquetas as $productName => $items)
    <div style="margin-bottom:1.5rem;">
        <div style="font-size:13px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid #e5e7eb;">
            {{ $productName }}
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:14px;">
            @foreach($items as $supply)
            @php $low = $supply->isLowStock(); @endphp
            <a href="{{ route('supplies.show',$supply) }}" style="text-decoration:none;">
                <div style="background:white; border-radius:12px; border:2px solid {{ $low ? '#fca5a5' : '#e5e7eb' }}; padding:18px; position:relative;">
                    @if($low)
                    <span style="position:absolute; top:10px; right:10px; background:#fee2e2; color:#dc2626; font-size:10px; font-weight:700; padding:2px 8px; border-radius:99px;">BAJO</span>
                    @endif
                    <div style="font-size:11px; color:#9ca3af; margin-bottom:4px;">
                        {{ strtoupper($supply->variant) }}
                    </div>
                    <div style="font-size:14px; font-weight:700; color:#1f2937; margin-bottom:12px;">{{ $supply->name }}</div>
                    <div style="font-size:30px; font-weight:800; color:{{ $low ? '#dc2626' : '#059669' }};">
                        {{ number_format($supply->stock) }}
                    </div>
                    <div style="font-size:12px; color:#6b7280; margin-top:2px;">unidades</div>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px solid #f3f4f6; font-size:12px; color:#9ca3af;">
                        Mín: {{ number_format($supply->stock_min) }} · {{ $supply->units_per_roll }} u/rollo
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

</x-app-layout>
