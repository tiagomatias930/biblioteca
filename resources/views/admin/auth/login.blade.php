@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="max-w-md mx-auto bg-white border border-slate-200/90 rounded-2xl p-8 shadow-sm">
        <div class="text-center mb-6">
            <span class="text-3xl">🔐</span>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-3">Área Administrativa</h1>
            <p class="text-slate-500 text-sm mt-1">Inicia sessão para gerir os teus documentos e categorias.</p>
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5" for="email">Endereço de E-mail</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5 shadow-2xs">
                @error('email')
                    <div class="mt-2 rounded-lg bg-red-50 border border-red-100 text-red-700 px-3 py-2 text-xs flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5" for="password">Palavra-passe</label>
                <input type="password" name="password" id="password" required
                       class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5 shadow-2xs">
                @error('password')
                    <div class="mt-2 rounded-lg bg-red-50 border border-red-100 text-red-700 px-3 py-2 text-xs flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 text-sm text-slate-600 select-none cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Lembrar-me
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-3 rounded-xl transition shadow-xs hover:shadow active:scale-99 cursor-pointer flex items-center justify-center gap-1.5">
                Entrar no Painel
            </button>
        </form>
    </div>
@endsection
