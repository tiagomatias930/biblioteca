@csrf

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Nome da Categoria</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required placeholder="Ex: Finanças, Pessoal, Viagens"
           class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Descrição (opcional)</label>
    <textarea name="description" rows="3" placeholder="Breve resumo sobre o conteúdo desta categoria..."
              class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Imagem de Capa (opcional)</label>
    <input type="file" name="cover_image" accept="image/*"
           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    @error('cover_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    @isset($category)
        @if ($category->cover_image)
            <div class="mt-2.5">
                <p class="text-xs text-slate-400 mb-1">Capa atual:</p>
                <img src="{{ $category->coverImageUrl() }}" class="w-24 h-24 rounded-lg object-cover border border-slate-200 shadow-xs">
            </div>
        @endif
    @endisset
</div>

<div class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-4">
    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_protected" name="is_protected" value="1"
               {{ old('is_protected', $category->is_protected ?? false) ? 'checked' : '' }}
               onchange="document.getElementById('access_code_wrapper').classList.toggle('hidden', !this.checked)"
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_protected" class="text-sm font-medium text-slate-700 select-none cursor-pointer">Proteger esta categoria com código de acesso</label>
    </div>

    <div id="access_code_wrapper" class="{{ old('is_protected', $category->is_protected ?? false) ? '' : 'hidden' }}">
        <label class="block text-sm font-medium text-slate-700 mb-1">
            Código de Acesso
            @isset($category) <span class="text-slate-400 font-normal text-xs">(deixa em branco para manter o atual)</span> @endisset
        </label>
        <input type="text" name="access_code" placeholder="Introduz o código de acesso"
               class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white">
        @error('access_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Ordem de Exibição</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
           class="w-32 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    <p class="text-slate-400 text-xs mt-1">Categorias com menor número aparecem primeiro.</p>
</div>

<div class="pt-2 flex gap-3">
    <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm cursor-pointer">
        Guardar categoria
    </button>
    <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-5 py-2.5 rounded-lg transition cursor-pointer">
        Cancelar
    </a>
</div>
