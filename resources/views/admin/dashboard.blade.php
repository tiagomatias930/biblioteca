@extends('layouts.app')

@section('title', 'Painel')

@section('content')
    <!-- Dashboard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 mb-8 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Painel Administrativo</h1>
            <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                    {{ $categories->count() }} categorias
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-800 border border-indigo-100">
                    {{ $totalDocuments }} documentos
                </span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition bg-slate-100 hover:bg-slate-200/80 px-3 py-2 rounded-lg">
                Ver site público
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50/50 transition border border-transparent hover:border-red-100 px-3 py-2 rounded-lg cursor-pointer">
                    Sair da conta
                </button>
            </form>
        </div>
    </div>

    <!-- Actions Panel -->
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4.5 py-2.5 rounded-xl transition shadow-sm hover:shadow cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nova categoria
        </a>
        <a href="{{ route('admin.documents.create') }}"
           class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4.5 py-2.5 rounded-xl transition shadow-sm hover:shadow cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
            </svg>
            Enviar documento
        </a>
    </div>

    <!-- Category Grid -->
    <div class="grid grid-cols-1 gap-6">
        @forelse ($categories as $category)
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs hover:shadow-sm transition-all duration-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $category->coverImageUrl() }}" class="w-14 h-14 rounded-xl object-cover border border-slate-100 shadow-inner">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold text-slate-800">{{ $category->name }}</h3>
                                @if ($category->is_protected)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-amber-50 text-amber-800 px-2 py-0.5 rounded-full border border-amber-100">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7zm3 9c-.83 0-1.5-.67-1.5-1.5S11.17 13 12 13s1.5.67 1.5 1.5S12.83 16 12 16z"/></svg>
                                        protegida
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $category->documents_count }} documento(s)</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-sm sm:self-start">
                        <a href="{{ route('categories.show', $category) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                            Ver
                        </a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50/50 transition font-medium">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Remover esta categoria e todos os seus documentos?');">
                            @csrf @method('DELETE')
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50/50 transition font-medium cursor-pointer">
                                Remover
                            </button>
                        </form>
                    </div>
                </div>

                @if ($category->documents->isNotEmpty())
                    <ul class="mt-4 divide-y divide-slate-100">
                        @foreach ($category->documents as $document)
                            <li class="flex items-center justify-between py-3 text-sm hover:bg-slate-50/40 px-2 rounded-lg transition">
                                <div class="flex items-center gap-3 truncate pr-4">
                                    <span class="inline-flex items-center justify-center font-bold text-[10px] tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 w-10 py-1 rounded uppercase shrink-0">
                                        {{ $document->extension() }}
                                    </span>
                                    <span class="text-slate-700 font-medium truncate">{{ $document->title }}</span>
                                    <span class="text-slate-400 text-xs shrink-0">({{ $document->humanSize() }})</span>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <a href="{{ route('admin.documents.edit', $document) }}" class="text-indigo-600 hover:text-indigo-800 transition text-xs font-semibold px-2 py-1 rounded hover:bg-indigo-50/50">Editar</a>
                                    <form method="POST" action="{{ route('admin.documents.destroy', $document) }}"
                                          onsubmit="return confirm('Remover este documento?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 transition text-xs font-semibold px-2 py-1 rounded hover:bg-red-50/50 cursor-pointer">Remover</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-400 text-xs mt-4 italic">Nenhum documento adicionado a esta categoria.</p>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-slate-200 rounded-2xl">
                <p class="text-slate-400">Nenhuma categoria criada ainda. Começa por criar uma categoria acima.</p>
            </div>
        @endforelse
    </div>
@endsection
