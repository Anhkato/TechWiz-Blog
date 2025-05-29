@csrf

<div class="mb-6">
    <label for="title" class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-1">
        {{ __('Title') }}
    </label>
    <input type="text" name="title" id="title"
           value="{{ old('title', $post->title ?? '') }}"
           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100 sm:text-base @error('title') border-red-500 ring-red-300 @enderror"
           required autofocus>
    @error('title')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="content" class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-1">
        {{ __('Content') }}
    </label>
    <textarea name="content" id="content" rows="8"
              class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100 sm:text-base @error('content') border-red-500 ring-red-300 @enderror"
              required>{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-8">
    <label for="status" class="block text-base font-semibold text-gray-800 dark:text-gray-100 mb-1">
        {{ __('Status') }}
    </label>
    <select name="status" id="status"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100 sm:text-base @error('status') border-red-500 ring-red-300 @enderror">
        <option value="draft" {{ old('status', $post->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
            {{ __('Draft') }}
        </option>
        <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>
            {{ __('Published') }}
        </option>
    </select>
    @error('status')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center justify-end gap-4">
    <a href="{{ route('dashboard') }}"
       class="px-4 py-2 rounded-md text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
        {{ __('Cancel') }}
    </a>
    <button type="submit"
            class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
        {{ $submitButtonText ?? __('Save Post') }}
    </button>
</div>