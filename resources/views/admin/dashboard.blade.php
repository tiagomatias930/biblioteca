@extends('layouts.app')

@section('title', 'Painel')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Painel administrativo</h1>
            <p class="text-slate-500 text-sm">{{ $categories->count() }} categorias · {{ $totalDocuments }} documentos</p>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-sm text-slate-500 hover:text-slate-800">Sair</button>
        </form>
    </div>

    <div class="flex gap-3 mb-8">
        <a href="{{ route('admin.categories.create') }}"
           class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Nova categoria
        </a>
        <a href="{{ route('admin.documents.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Enviar documento
        </a>
    </div>

    <div class="space-y-4">
        @foreach ($categories as $category)
            <div class="bg-white border border-slate-200 rounded-xl p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $category->coverImageUrl() }}" class="w-12 h-12 rounded-lg object-cover">
                        <div>
                            <p class="font-medium text-slate-800 flex items-center gap-2">
                                {{ $category->name }}
                                @if ($category->is_protected)
                                    <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">🔒 protegida</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-400">{{ $category->documents_count }} documento(s)</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('categories.show', $category) }}" class="text-slate-500 hover:text-slate-800">Ver</a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Editar</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Remover esta categoria e todos os seus documentos?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Remover</button>
                        </form>
                    </div>
                </div>

                @if ($category->documents->isNotEmpty())
                    <ul class="mt-4 divide-y divide-slate-100 border-t border-slate-100 pt-3">
                        @foreach ($category->documents as $document)
                            <li class="flex items-center justify-between py-2 text-sm">
                                <span class="text-slate-600 truncate">{{ $document->title }}
                                    <span class="text-slate-400 text-xs">({{ $document->extension() }} · {{ $document->humanSize() }})</span>
                                </span>
                                <form method="POST" action="{{ route('admin.documents.destroy', $document) }}"
                                      onsubmit="return confirm('Remover este documento?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:underline text-xs">Remover</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
@endsection
