<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nueva Categoría
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                               placeholder="Nombre de la categoría">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:#374151; margin-bottom:0.4rem;">Descripción</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                  placeholder="Descripción opcional">{{ old('description') }}</textarea>
                    </div>
                    <div style="display:flex; gap:1rem;">
                        <button type="submit"
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600">
                            Guardar
                        </button>
                        <a href="{{ route('categories.index') }}"
                           class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>