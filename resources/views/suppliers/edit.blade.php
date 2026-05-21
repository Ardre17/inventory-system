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
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Contacto</label>
                        <input type="text" name="contact" value="{{ old('contact', $supplier->contact) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div class="flex gap-3">
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