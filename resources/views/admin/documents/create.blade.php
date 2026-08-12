@extends('layouts.app')

@section('title', 'Enviar documento')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1">&larr; Voltar ao painel</a>

    <div class="mt-3 mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Enviar Novo Documento</h1>
        <p class="text-slate-500 text-sm">Carrega um ficheiro e associa-o a uma categoria existente.</p>
    </div>

    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-5 max-w-lg bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Categoria</label>
            <select name="category_id" required
                    class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Seleciona uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Título do documento (opcional)</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: Contrato de Trabalho (usado apenas se enviar 1 ficheiro)"
                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <p class="text-slate-400 text-xs mt-1">Se deixar em branco ou enviar múltiplos ficheiros, o nome de cada ficheiro será usado como título.</p>
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ficheiros (máx. 20 MB por ficheiro)</label>
            <input type="file" name="files[]" multiple required 
                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="text-slate-400 text-xs mt-1.5">Pode selecionar múltiplos ficheiros em simultâneo. Formatos suportados: PDF, DOC, DOCX, JPG, PNG, ZIP, XLSX, PPTX.</p>
            @error('files') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('files.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm cursor-pointer">
                Enviar documento
            </button>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-5 py-2.5 rounded-lg transition cursor-pointer">
                Cancelar
            </a>
        </div>
    </form>
@endsection
