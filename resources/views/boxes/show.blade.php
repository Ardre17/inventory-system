<x-app-layout>

<x-slot name="header">
    📦 {{ $box->name }}
</x-slot>

<div class="p-6">

    <div class="bg-white rounded-lg shadow p-6 mb-6">

        <h2 style="
            font-size:24px;
            font-weight:bold;
            color:#1e3a8a;
        ">
            {{ $box->name }}
        </h2>

        <div style="
            margin-top:10px;
            display:flex;
            gap:40px;
        ">

            <div>
                <strong>Código:</strong>
                {{ $box->code }}
            </div>

            <div>
                <strong>Stock Actual:</strong>

                <span style="
                    color:#059669;
                    font-size:20px;
                    font-weight:bold;
                ">
                    {{ number_format($box->stock) }}
                </span>
            </div>

        </div>

    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div style="
            background:#1e3a8a;
            color:white;
            padding:12px;
            font-weight:bold;
        ">
            📜 Historial de Movimientos
        </div>

        <table style="
            width:100%;
            border-collapse:collapse;
        ">

            <thead>

            <tr style="
                background:#dbeafe;
            ">
                <th class="p-3">Fecha</th>
                <th class="p-3">Tipo</th>
                <th class="p-3">Cantidad</th>
                <th class="p-3">Motivo</th>
                <th class="p-3">Usuario</th>
            </tr>

            </thead>
<div style="
    background:white;
    border-radius:12px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
">

    <h3 style="
        margin-top:0;
        color:#1e3a8a;
    ">
        🔄 Registrar Movimiento
    </h3>

    <form
        method="POST"
        action="{{ route('boxes.movement.store') }}"
    >

        @csrf

        <input
            type="hidden"
            name="box_id"
            value="{{ $box->id }}"
        >

        <div style="
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:15px;
        ">

            <div>
                <label>Tipo</label>

                <select
                    name="type"
                    required
                    style="
                        width:100%;
                        padding:10px;
                        border:1px solid #d1d5db;
                        border-radius:8px;
                    "
                >
                    <option value="entrada">
                        ➕ Entrada
                    </option>

                    <option value="salida">
                        ➖ Salida
                    </option>
                </select>
            </div>

            <div>
                <label>Cantidad</label>

                <input
                    type="number"
                    name="quantity"
                    required
                    min="1"
                    style="
                        width:100%;
                        padding:10px;
                        border:1px solid #d1d5db;
                        border-radius:8px;
                    "
                >
            </div>

        </div>

        <div style="margin-top:15px;">

            <label>Motivo</label>

            <input
                type="text"
                name="reason"
                style="
                    width:100%;
                    padding:10px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                "
            >

        </div>

        <div style="margin-top:15px;">

            <label>Observación</label>

            <textarea
                name="observation"
                rows="3"
                style="
                    width:100%;
                    padding:10px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                "
            ></textarea>

        </div>

        <button
            type="submit"
            style="
                margin-top:15px;
                background:#1e3a8a;
                color:white;
                border:none;
                padding:12px 20px;
                border-radius:8px;
                cursor:pointer;
            "
        >
            Guardar Movimiento
        </button>

    </form>

</div>
            <tbody>

            @forelse($movements as $movement)

                <tr style="
                    border-top:1px solid #e5e7eb;
                ">

                    <td class="p-3">
                        {{ $movement->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="p-3">

                        @if($movement->type=='entrada')

                            <span style="
                                color:#059669;
                                font-weight:bold;
                            ">
                                ➕ Entrada
                            </span>

                        @else

                            <span style="
                                color:#dc2626;
                                font-weight:bold;
                            ">
                                ➖ Salida
                            </span>

                        @endif

                    </td>

                    <td class="p-3">
                        {{ number_format($movement->quantity) }}
                    </td>

                    <td class="p-3">
                        {{ $movement->reason }}
                    </td>

                    <td class="p-3">
                        {{ $movement->user?->name }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5"
                        class="p-4 text-center">
                        No existen movimientos.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>