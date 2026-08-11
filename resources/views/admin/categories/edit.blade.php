@extends('layouts.app')

@section('title', 'Editar categoria')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1">&larr; Voltar ao painel</a>

    <div class="mt-3 mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Editar Categoria: {{ $category->name }}</h1>
        <p class="text-slate-500 text-sm">Modifica os detalhes ou as configurações de proteção desta categoria.</p>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5 max-w-lg bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        @method('PUT')
        @include('admin.categories._form')
    </form>
@endsection
