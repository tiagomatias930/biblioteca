<!DOCTYPE html>
<html lang="pt-PT" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca') — {{ config('app.name', 'Biblioteca Pessoal') }}</title>
    <!-- Tailwind CSS v4 via CDN for preview compatibility -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Instrument Sans', sans-serif; 
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 backdrop-blur-md bg-white/95">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-semibold text-lg tracking-tight flex items-center gap-2 hover:opacity-90 transition">
                <span class="text-2xl">📁</span>
                <span class="bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent font-bold">
                    {{ config('app.name', 'Biblioteca Pessoal') }}
                </span>
            </a>
            
            <nav class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5">
                    Categorias
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition px-3.5 py-1.5 rounded-lg shadow-xs hover:shadow-sm">
                        Painel
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition border border-slate-200 hover:border-slate-300 px-3 py-1.5 rounded-lg">
                        Entrar
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        <div class="max-w-5xl mx-auto px-6 py-10">
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-emerald-50/60 border border-emerald-200 text-emerald-800 p-4 text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/60 bg-white text-center text-xs text-slate-400 py-8">
        <div class="max-w-5xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Biblioteca Pessoal') }} — Organização pessoal de documentos.</p>
            <div class="flex items-center gap-4">
                @auth
                    <span class="text-indigo-600/70 font-medium">Sessão iniciada como Administrador</span>
                @else
                    <a href="{{ route('admin.login') }}" class="hover:text-slate-600 transition">Acesso Administrativo</a>
                @endauth
            </div>
        </div>
    </footer>
</body>
</html>
