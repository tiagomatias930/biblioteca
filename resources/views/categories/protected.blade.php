@extends('layouts.app')

@section('title', $category->name . ' — Protegida')

@section('content')
    <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:underline">&larr; Voltar às categorias</a>

    <div class="mt-8 max-w-sm mx-auto text-center">
        <div class="text-4xl mb-3">🔒</div>
        <h1 class="text-xl font-semibold text-slate-900 mb-1">{{ $category->name }}</h1>
        <p class="text-slate-500 text-sm mb-6">Esta categoria é protegida. Introduz o código de acesso para continuar.</p>

        <form method="POST" action="{{ route('categories.unlock', $category) }}" class="space-y-3 text-left">
            @csrf
            <input type="password" name="access_code" placeholder="Código de acesso" required
                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">

            @error('access_code')
                <p class="text-red-600 text-xs">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium py-2.5 rounded-lg transition">
                Desbloquear
            </button>
        </form>
    </div>
@endsection
