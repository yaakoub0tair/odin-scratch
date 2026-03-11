@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="mb-6">
        <a href="{{ route('links.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">&larr; Back to Links</a>
    </div>

    <div class="bg-white border rounded-lg p-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold mb-2">{{ $link->title }}</h1>
            <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                {{ $link->url }}
            </a>
        </div>

        <div class="mb-4">
            <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded">
                Category: {{ $link->category->name }}
            </span>
        </div>

        @if($link->tags->count() > 0)
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($link->tags as $tag)
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pt-4 border-t flex space-x-3">
            <a href="{{ route('links.edit', $link) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
            <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Delete this link?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
