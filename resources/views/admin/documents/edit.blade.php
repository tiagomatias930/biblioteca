@extends('layouts.app')

@section('title', 'Editar documento')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1">&larr; Voltar ao painel</a>

    <div class="mt-3 mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Editar documento</h1>
        <p class="text-slate-500 text-sm">Modifica as informações ou substitui o ficheiro do documento.</p>
    </div>

    <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data" class="space-y-5 max-w-lg bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Categoria</label>
            <select name="category_id" required
                    class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Seleciona uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Título do documento</label>
            <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ficheiro (opcional)</label>
            <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <div class="mt-2 flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-100 rounded-lg p-2.5">
                <span class="font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 uppercase">{{ $document->extension() }}</span>
                <span class="truncate">{{ $document->original_name }}</span>
                <span class="text-slate-400">·</span>
                <span>{{ $document->humanSize() }}</span>
            </div>
            <p class="text-slate-400 text-xs mt-1.5">Deixa este campo vazio se queres manter o ficheiro atual e alterar apenas o título ou categoria.</p>
            @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm cursor-pointer">
                Atualizar documento
            </button>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-5 py-2.5 rounded-lg transition cursor-pointer">
                Cancelar
            </a>
        </div>
    </form>
@endsection
