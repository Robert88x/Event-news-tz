<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            {{ __('Events') }}
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('events.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Create Event</a>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-white overflow-hidden shadow-sm flex flex-col sm:rounded-lg">
                        @if($event->image_path)
                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                        @endif
                        <div class="p-6 text-gray-900 flex-1">
                            <h3 class="text-xl font-bold mb-2"><a href="{{ route('events.show', $event) }}" class="hover:underline">{{ $event->title }}</a></h3>
                            <p class="text-sm text-gray-500 mb-2">Location: {{ $event->location ?? 'TBA' }}</p>
                            <p class="text-sm text-gray-500 mb-4">{{ $event->start_time->format('M d, Y h:i A') }}</p>
                            <p class="text-gray-700 mb-4">{{ Str::limit($event->description, 100) }}</p>
                        </div>
                        <div class="p-6 bg-gray-50 mt-auto flex justify-between items-center">
                            <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Read more &rarr;</a>
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="flex space-x-2">
                                    <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 border-l-4 border-blue-500">
                        No events have been posted yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
