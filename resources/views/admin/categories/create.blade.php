@extends('layouts.app')

@section('title', 'Nova categoria')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1">&larr; Voltar ao painel</a>

    <div class="mt-3 mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Nova Categoria</h1>
        <p class="text-slate-500 text-sm">Cria uma categoria para agrupar documentos semelhantes.</p>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-5 max-w-lg bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        @include('admin.categories._form')
    </form>
@endsection
