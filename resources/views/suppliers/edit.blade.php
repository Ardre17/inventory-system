<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Proveedor
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Contacto</label>
                        <input type="text" name="contact" value="{{ old('contact', $supplier->contact) }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                               style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div style="display:flex; gap:1rem;">
                        <button type="submit"
                                style="background-color:#2563eb; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Actualizar
                        </button>
                        <a href="{{ route('suppliers.index') }}"
                           style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>