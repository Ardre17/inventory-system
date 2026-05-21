<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nuevo Inventario
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

                <form method="POST" action="{{ route('inventory-periods.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre del periodo *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               placeholder="Ej: Inventario Quincena 1 - Mayo 2026">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha inicio *</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Fecha fin *</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Notas</label>
                        <textarea name="notes" rows="2"
                                  class="w-full border rounded-lg px-3 py-2"
                                  placeholder="Notas opcionales"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                style="background-color:#ea580c; color:white; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600;">
                            Crear Inventario
                        </button>
                        <a href="{{ route('inventory-periods.index') }}"
                           style="background-color:#d1d5db; color:#374151; padding:0.5rem 1.5rem; border-radius:0.5rem; font-weight:600; text-decoration:none;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>