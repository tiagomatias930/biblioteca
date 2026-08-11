@extends('layouts.app')

@section('title', $category->name . ' — Protegida')

@section('content')
    <!-- Back Navigation -->
    <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:underline inline-flex items-center gap-1 mb-8">
        &larr; Voltar às categorias
    </a>

    <!-- Password Prompt Card -->
    <div class="max-w-md mx-auto bg-white border border-slate-200/90 rounded-2xl p-8 shadow-sm text-center">
        <!-- Shield / Lock icon styling -->
        <div class="mx-auto w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center border border-amber-100/80 mb-5">
            <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $category->name }}</h1>
        <p class="text-slate-500 text-sm mt-2 mb-6">Esta categoria é protegida por palavra-passe. Introduz o código de acesso para ver os documentos.</p>

        <form method="POST" action="{{ route('categories.unlock', $category) }}" class="space-y-4 text-left">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5" for="access_code">Código de Acesso</label>
                <input type="password" name="access_code" id="access_code" placeholder="••••••••" required autofocus
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5 shadow-2xs">
                
                @error('access_code')
                    <div class="mt-2.5 rounded-lg bg-red-50 border border-red-100 text-red-700 px-3 py-2 text-xs flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium py-3 rounded-xl transition shadow-sm hover:shadow active:scale-99 cursor-pointer flex items-center justify-center gap-1.5">
                Desbloquear
            </button>
        </form>
    </div>
@endsection
