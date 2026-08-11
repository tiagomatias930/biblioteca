<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca') — {{ config('app.name', 'Biblioteca Pessoal') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <header class="bg-slate-900 text-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-semibold text-lg tracking-tight">
                📁 {{ config('app.name', 'Biblioteca Pessoal') }}
            </a>
            <nav class="text-sm text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white">Categorias</a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        <div class="max-w-5xl mx-auto px-6 py-10">
            @if (session('status'))
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="border-t border-slate-200 text-center text-xs text-slate-400 py-6">
        &copy; {{ date('Y') }} {{ config('app.name', 'Biblioteca Pessoal') }} — organização pessoal de documentos.
    </footer>
</body>
</html>
