@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:underline">&larr; Voltar às categorias</a>

    <div class="mt-3 mb-8 flex items-center gap-3">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h1>
        @if ($category->is_protected)
            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full">Protegida</span>
        @endif
    </div>

    @if ($category->description)
        <p class="text-slate-500 mb-6 max-w-2xl">{{ $category->description }}</p>
    @endif

    @if ($documents->isEmpty())
        <p class="text-slate-500">Nenhum documento nesta categoria ainda.</p>
    @else
        <ul class="divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white">
            @foreach ($documents as $document)
                <li class="flex items-center justify-between px-5 py-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-800 truncate">{{ $document->title }}</p>
                        <p class="text-xs text-slate-400">
                            {{ $document->extension() }} · {{ $document->humanSize() }}
                        </p>
                    </div>
                    <a href="{{ route('documents.download', $document) }}"
                       class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition">
                        ⬇ Transferir
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
