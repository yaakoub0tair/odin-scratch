@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🗑️ Trashed Links (Admin Only)</h1>
        <a href="{{ route('links.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Back to Links</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4">
        @forelse($links as $link)
            <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold mb-2 text-red-800">
                            {{ $link->title }}
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
                        <p class="text-red-600 text-sm">
                            Deleted: {{ $link->deleted_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <form action="{{ route('links.restore', $link) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                Restore
                            </button>
                        </form>
                        <form action="{{ route('links.forceDelete', $link) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600" onclick="return confirm('Permanently delete this link? This cannot be undone!')">
                                Delete Forever
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <p>No trashed links found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $links->links() }}
    </div>
</div>
@endsection
