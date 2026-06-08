<x-app-layout>

<x-slot name="header">
    📦 Nueva Caja
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
    background:#1e3a8a;
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
    justify-content:flex-end;
    gap:10px;
    margin-top:25px;
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

.btn-secondary{
    background:#e5e7eb;
    color:#111827;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:600;
}

.btn-secondary:hover{
    background:#d1d5db;
}

.error-box{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}

</style>

<div class="p-6">

```
<div class="box-container">

    <div class="box-card">

        <div class="box-header">
            <h2>📦 Nueva Caja</h2>
            <p>Registrar un nuevo tipo de caja para almacén</p>
        </div>

        <div class="box-body">

            @if ($errors->any())

            <div class="error-box">

                <strong>Se encontraron errores:</strong>

                <ul style="margin-top:8px; padding-left:20px;">

                    @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            @endif

            <form method="POST"
                  action="{{ route('boxes.store') }}">

                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">
                            Código
                        </label>

                        <input
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            class="form-input"
                            placeholder="Ej: CJ001"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-input"
                            placeholder="Ej: Caja Master"
                            required
                        >
                    </div>

                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">
                            Stock Inicial
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', 0) }}"
                            class="form-input"
                            min="0"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Stock Mínimo
                        </label>

                        <input
                            type="number"
                            name="minimum_stock"
                            value="{{ old('minimum_stock', 0) }}"
                            class="form-input"
                            min="0"
                            required
                        >
                    </div>

                </div>

                <div class="footer-actions">

                    <a
                        href="{{ route('boxes.index') }}"
                        class="btn-secondary">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn-primary">
                        💾 Guardar Caja
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
```

</div>

</x-app-layout>
