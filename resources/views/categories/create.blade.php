@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Create Category</h1>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border p-2">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        </div>
    </form>
</div>
@endsection
