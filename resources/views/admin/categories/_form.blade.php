@csrf

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Nome</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
           class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Descrição (opcional)</label>
    <textarea name="description" rows="3"
              class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Imagem de capa (mostrada ao passar o rato)</label>
    <input type="file" name="cover_image" accept="image/*"
           class="w-full text-sm">
    @error('cover_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    @isset($category)
        @if ($category->cover_image)
            <img src="{{ $category->coverImageUrl() }}" class="mt-2 w-24 h-24 rounded-lg object-cover">
        @endif
    @endisset
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" id="is_protected" name="is_protected" value="1"
           {{ old('is_protected', $category->is_protected ?? false) ? 'checked' : '' }}
           onchange="document.getElementById('access_code_wrapper').classList.toggle('hidden', !this.checked)"
           class="rounded border-slate-300">
    <label for="is_protected" class="text-sm text-slate-700">Categoria protegida (exige código de acesso)</label>
</div>

<div id="access_code_wrapper" class="{{ old('is_protected', $category->is_protected ?? false) ? '' : 'hidden' }}">
    <label class="block text-sm font-medium text-slate-700 mb-1">
        Código de acesso
        @isset($category) <span class="text-slate-400 font-normal">(deixa em branco para manter o atual)</span> @endisset
    </label>
    <input type="text" name="access_code" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @error('access_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Ordem de exibição</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
           class="w-32 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
</div>

<button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
    Guardar
</button>
