<x-app-layout>

<x-slot name="header">
    ✏️ Editar Caja
</x-slot>

<style>

.box-container{
    max-width:900px;
    margin:auto;
}

.box-card{
    background:#ffffff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.box-header{
    background:#f59e0b;
    color:white;
    padding:20px;
}

.box-header h2{
    margin:0;
    font-size:24px;
}

.box-header p{
    margin-top:5px;
    opacity:.9;
}

.box-body{
    padding:25px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    margin-bottom:20px;
}

.form-label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

.form-input{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    font-size:14px;
}

.form-input:focus{
    outline:none;
    border-color:#1e3a8a;
}

.footer-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:25px;
}

.stock-info{
    font-size:14px;
    color:#6b7280;
}

.stock-value{
    font-size:22px;
    font-weight:bold;
    color:#059669;
}

.btn-primary{
    background:#1e3a8a;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.btn-primary:hover{
    background:#1e40af;
}

</style>

<div class="p-6">

    <div class="box-container">

        <div class="box-card">

            <div class="box-header">
                <h2>{{ $box->name }}</h2>
                <p>Modificar información de la caja</p>
            </div>

            <div class="box-body">

                <form method="POST"
                      action="{{ route('boxes.update', $box) }}">

                    @csrf
                    @method('PUT')

                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">
                                Código
                            </label>

                            <input
                                type="text"
                                name="code"
                                value="{{ $box->code }}"
                                class="form-input"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ $box->name }}"
                                class="form-input"
                            >
                        </div>

                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">
                                Stock Actual
                            </label>

                            <input
                                type="number"
                                name="stock"
                                value="{{ $box->stock }}"
                                class="form-input"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Stock Mínimo
                            </label>

                            <input
                                type="number"
                                name="minimum_stock"
                                value="{{ $box->minimum_stock }}"
                                class="form-input"
                            >
                        </div>

                    </div>

                    <div class="footer-actions">

                        <div>
                            <div class="stock-info">
                                Stock actual
                            </div>

                            <div class="stock-value">
                                {{ number_format($box->stock) }}
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn-primary">
                            💾 Guardar Cambios
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</x-app-layout>