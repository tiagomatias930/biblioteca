@extends('layouts.app')

@section('title', 'Editar categoria')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Voltar</a>

    <h1 class="text-2xl font-semibold text-slate-900 mt-3 mb-6">Editar categoria</h1>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5 max-w-lg">
        @method('PUT')
        @include('admin.categories._form')
    </form>
@endsection
