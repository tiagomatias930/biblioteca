@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
    <!-- Hero / Intro -->
    <div class="mb-10 text-left">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Os Meus Documentos</h1>
        <p class="text-slate-500 mt-2 text-base max-w-2xl">Seleciona uma categoria abaixo para visualizar, pesquisar e transferir os documentos organizados.</p>
    </div>

    @if ($categories->isEmpty())
        <div class="text-center py-16 bg-white border border-slate-200/80 rounded-2xl shadow-xs">
            <span class="text-4xl">📂</span>
            <h3 class="mt-4 text-lg font-semibold text-slate-800">Sem categorias</h3>
            <p class="text-slate-500 text-sm mt-1">Ainda não foram adicionadas categorias a esta biblioteca.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="group relative flex flex-col justify-end h-64 rounded-2xl overflow-hidden border border-slate-200/60 bg-slate-950 shadow-xs hover:shadow-md hover:border-slate-300/80 transition-all duration-300">
                    
                    <!-- Cover Image with Zoom Effect -->
                    <img src="{{ $category->coverImageUrl() }}"
                         alt="{{ $category->name }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-50 group-hover:scale-102 transition duration-500 ease-out">

                    <!-- Dark Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                    <!-- Lock Overlay for Protected Categories -->
                    @if ($category->is_protected)
                        <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-xs p-2 rounded-xl border border-white/10 text-white shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        </div>
                    @endif

                    <!-- Category Details -->
                    <div class="relative p-6 z-10">
                        <div class="flex items-center gap-2">
                            <h2 class="text-white font-bold text-xl tracking-tight leading-tight group-hover:text-indigo-200 transition-colors">
                                {{ $category->name }}
                            </h2>
                        </div>
                        
                        @if ($category->description)
                            <p class="text-slate-300 text-xs mt-1.5 line-clamp-2 leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ $category->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-white/10">
                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-300 font-medium">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                {{ $category->documents_count }} {{ $category->documents_count === 1 ? 'documento' : 'documentos' }}
                            </span>
                            
                            <span class="text-xs font-semibold text-white group-hover:translate-x-1.5 transition-transform duration-300 flex items-center gap-1">
                                Abrir
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
