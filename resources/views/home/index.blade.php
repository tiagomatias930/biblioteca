@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900 mb-1">Meus Documentos</h1>
    <p class="text-slate-500 mb-8">Escolhe uma categoria para ver e transferir os documentos.</p>

    @if ($categories->isEmpty())
        <p class="text-slate-500">Ainda não há categorias criadas.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="group relative block h-56 rounded-xl overflow-hidden shadow-sm ring-1 ring-slate-200 bg-slate-900">
                    <img src="{{ $category->coverImageUrl() }}"
                         alt="{{ $category->name }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-40 group-hover:scale-105 transition duration-300">

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 p-4 flex items-end justify-between">
                        <div>
                            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                                {{ $category->name }}
                                @if ($category->is_protected)
                                    <span title="Protegida">🔒</span>
                                @endif
                            </h2>
                            <p class="text-slate-200 text-xs">{{ $category->documents_count }} documento(s)</p>
                        </div>
                    </div>

                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <span class="bg-white/90 text-slate-900 text-sm font-medium px-4 py-2 rounded-full shadow">
                            Ver documentos →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
