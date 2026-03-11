@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Links</h1>
        <div class="flex space-x-3">
            <a href="{{ route('links.favorites') }}" class="bg-yellow-500 text-white px-4 py-2 rounded">⭐ Favorites</a>
            <a href="{{ route('links.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">New Link</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4">
        @forelse($links as $link)
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                {{ $link->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $link->url }}</p>
                        <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm mb-2">
                            {{ $link->category->name }}
                        </span>
                        @if($link->tags->count() > 0)
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($link->tags as $tag)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <form action="{{ route('links.favorite', $link) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Favorite">
                                {{ auth()->user()->favorites()->where('link_id', $link->id)->exists() ? '⭐' : '☆' }}
                            </button>
                        </form>
                        <a href="{{ route('links.edit', $link) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Delete this link?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <p>No links found. <a href="{{ route('links.create') }}" class="text-blue-500 hover:text-blue-700">Create your first link</a></p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $links->links() }}
    </div>
</div>
@endsection
