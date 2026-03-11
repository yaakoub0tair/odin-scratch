@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🔔 Notifications</h1>
        <div class="flex space-x-3">
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded text-sm">
                    Mark All as Read
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="border rounded-lg p-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            @if(!$notification->read_at)
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full mr-2">NEW</span>
                            @endif
                            <span class="text-gray-500 text-sm">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <h3 class="font-semibold text-lg">{{ $notification->data['message'] ?? 'Notification' }}</h3>
                        </div>

                        @if(isset($notification->data['link_title']))
                            <div class="bg-gray-100 p-3 rounded text-sm">
                                <p><strong>Link:</strong> {{ $notification->data['link_title'] }}</p>
                                <p><strong>Shared by:</strong> {{ $notification->data['shared_by'] }}</p>
                                <p><strong>Permission:</strong> {{ ucfirst($notification->data['permission']) }}</p>
                                
                                @if(isset($notification->data['link_id']))
                                    <div class="mt-2">
                                        <a href="{{ route('links.show', $notification->data['link_id']) }}" 
                                           class="text-blue-600 hover:text-blue-800 underline">
                                            View Link →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="ml-4">
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-500 hover:text-blue-700 text-sm">
                                    Mark as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <p>No notifications found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
