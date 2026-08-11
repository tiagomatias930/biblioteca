@extends('layouts.app')

@section('title', 'Enviar documento')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Voltar</a>

    <h1 class="text-2xl font-semibold text-slate-900 mt-3 mb-6">Enviar documento</h1>

    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-5 max-w-lg">
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
            <label class="block text-sm font-medium text-slate-700 mb-1">Título do documento</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ficheiro (máx. 20 MB)</label>
            <input type="file" name="file" required class="w-full text-sm">
            @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
            Enviar
        </button>
    </form>
@endsection
