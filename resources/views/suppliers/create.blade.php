<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nuevo Proveedor
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Nombre del proveedor">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Contacto</label>
                        <input type="text" name="contact" value="{{ old('contact') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Nombre del contacto">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Teléfono">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Email">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                style="background-color:#2563eb; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Guardar
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