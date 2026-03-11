@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Create New Link</h1>

    <form action="{{ route('links.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL</label>
            <input type="url" id="url" name="url" value="{{ old('url') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2" required>
            @error('url')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select id="category_id" name="category_id" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags (optional)</label>
            <input type="text" id="tags" name="tags" 
                   value="{{ old('tags') ? implode(', ', old('tags')) : '' }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2"
                   placeholder="Enter tags separated by commas (max 5)">
            <p class="text-gray-500 text-sm mt-1">Separate multiple tags with commas. Example: php, laravel, tutorial</p>
            @error('tags')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('links.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create Link</button>
        </div>
    </form>
</div>
@endsection
