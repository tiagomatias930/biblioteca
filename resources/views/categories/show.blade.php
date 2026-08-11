@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <!-- Back Navigation -->
    <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1 mb-4">
        &larr; Voltar às categorias
    </a>

    <!-- Category Header Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs mb-8 flex flex-col md:flex-row items-start md:items-center gap-6">
        <img src="{{ $category->coverImageUrl() }}" alt="{{ $category->name }}" class="w-20 h-20 rounded-xl object-cover border border-slate-100 shadow-inner shrink-0">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $category->name }}</h1>
                @if ($category->is_protected)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-full border border-amber-100">
                        🔒 Categoria Protegida
                    </span>
                @endif
            </div>
            
            @if ($category->description)
                <p class="text-slate-500 text-sm mt-2 max-w-3xl leading-relaxed">{{ $category->description }}</p>
            @else
                <p class="text-slate-400 text-sm mt-1 italic">Sem descrição disponível para esta categoria.</p>
            @endif
        </div>
        <div class="text-xs text-slate-400 shrink-0 md:text-right">
            <span class="block font-semibold text-slate-700 text-sm">{{ $documents->count() }}</span>
            <span>documento(s) total</span>
        </div>
    </div>

    @if ($documents->isEmpty())
        <div class="text-center py-16 bg-white border border-slate-200 rounded-2xl">
            <span class="text-3xl">📁</span>
            <p class="text-slate-500 text-sm mt-3">Nenhum documento disponível nesta categoria.</p>
        </div>
    @else
        <!-- Search and Filter Bar -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between bg-white border border-slate-200/60 p-4 rounded-xl shadow-2xs">
            <!-- Search Bar -->
            <div class="relative w-full md:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                </span>
                <input type="text" id="doc-search" placeholder="Pesquisar documentos..."
                       class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white placeholder-slate-400">
            </div>

            <!-- Filter Pills -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto items-center" id="filter-pills">
                <span class="text-xs text-slate-400 font-medium mr-1 hidden sm:inline">Filtrar por:</span>
                <button data-ext="all" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white shadow-xs transition cursor-pointer">
                    Todos
                </button>
            </div>
        </div>

        <!-- Document List -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
            <ul class="divide-y divide-slate-100" id="documents-list">
                @foreach ($documents as $document)
                    <li class="document-item flex items-center justify-between px-5 py-4 hover:bg-slate-50/40 transition gap-4" data-extension="{{ $document->extension() }}">
                        <div class="min-w-0 flex items-center gap-4">
                            <!-- Extension badge -->
                            <span class="inline-flex items-center justify-center font-bold text-[10px] tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 w-12 py-1.5 rounded-lg uppercase shrink-0 select-none">
                                {{ $document->extension() }}
                            </span>
                            <div class="truncate">
                                <p class="font-semibold text-slate-800 truncate document-title">{{ $document->title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1.5">
                                    <span>{{ $document->humanSize() }}</span>
                                    <span class="text-slate-300">·</span>
                                    <span class="truncate" title="{{ $document->original_name }}">{{ $document->original_name }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('documents.download', $document) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-98 px-4 py-2.5 rounded-xl transition shadow-xs hover:shadow-sm cursor-pointer select-none">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Transferir
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- No Results message -->
            <div id="no-results" class="text-center py-12 hidden border-t border-slate-100">
                <span class="text-2xl">🔍</span>
                <p class="text-slate-500 text-sm mt-2">Nenhum documento corresponde à sua pesquisa.</p>
            </div>
        </div>
    @endif

    <!-- Clientside search and filter script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('doc-search');
            const docItems = document.querySelectorAll('.document-item');
            const filterPillsContainer = document.getElementById('filter-pills');
            const noResults = document.getElementById('no-results');

            if (!docItems.length) return;

            // Get all unique extensions from document items
            const extensions = new Set();
            docItems.forEach(item => {
                const ext = item.getAttribute('data-extension');
                if (ext) extensions.add(ext.toUpperCase());
            });

            // Generate filter buttons dynamically
            extensions.forEach(ext => {
                const btn = document.createElement('button');
                btn.setAttribute('data-ext', ext);
                btn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition cursor-pointer';
                btn.textContent = ext;
                filterPillsContainer.appendChild(btn);
            });

            let currentSearch = '';
            let currentFilter = 'all';

            function filterDocuments() {
                let visibleCount = 0;
                docItems.forEach(item => {
                    const title = item.querySelector('.document-title').textContent.toLowerCase();
                    const ext = item.getAttribute('data-extension').toUpperCase();
                    
                    const matchesSearch = title.includes(currentSearch);
                    const matchesFilter = currentFilter === 'all' || ext === currentFilter;

                    if (matchesSearch && matchesFilter) {
                        item.style.setProperty('display', 'flex', 'important');
                        visibleCount++;
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });

                if (noResults) {
                    noResults.style.setProperty('display', visibleCount === 0 ? 'block' : 'none', 'important');
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    currentSearch = e.target.value.toLowerCase().trim();
                    filterDocuments();
                });
            }

            filterPillsContainer.addEventListener('click', (e) => {
                const btn = e.target.closest('.filter-btn');
                if (!btn) return;

                // Toggle active styles
                filterPillsContainer.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                    b.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                });

                btn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');

                currentFilter = btn.getAttribute('data-ext');
                filterDocuments();
            });
        });
    </script>
@endsection
