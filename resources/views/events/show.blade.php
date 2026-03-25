<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm flex flex-col sm:rounded-lg">
                @if($event->video_path)
                    <video controls class="w-full bg-black max-h-[500px] object-contain border-b border-gray-200">
                        <source src="{{ asset('storage/' . $event->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
                @if($event->image_path)
                    <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full max-h-[500px] object-cover">
                @endif
                <div class="p-8 text-gray-900">
                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
                            <p class="text-gray-600 font-medium">Location: {{ $event->location ?? 'TBA' }}</p>
                        </div>
                        <div class="bg-blue-50 text-blue-800 p-4 rounded text-right">
                            <p class="font-bold text-sm uppercase mb-1">Date & Time</p>
                            <p>{{ $event->start_time->format('M d, Y') }}</p>
                            <p>{{ $event->start_time->format('h:i A') }}</p>
                            @if($event->end_time)
                                <p class="text-sm text-gray-500 mt-1">Ends: {{ $event->end_time->format('h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="prose max-w-none mb-8 text-gray-800 leading-relaxed whitespace-pre-wrap">
                        {{ $event->description }}
                    </div>

                    <!-- Engagements -->
                    <div class="flex items-center space-x-6 border-t border-b py-4 mb-8">
                        @if(auth()->check())
                        <form action="{{ route('events.like', $event) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 transition-colors duration-200 {{ $event->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $event->isLikedBy(auth()->user()) ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>{{ $event->likes()->count() }} {{ Str::plural('Like', $event->likes()->count()) }}</span>
                            </button>
                        </form>
                        @else
                        <div class="flex items-center space-x-1 text-gray-400 cursor-not-allowed" title="Please login to like">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>{{ $event->likes()->count() }} {{ Str::plural('Like', $event->likes()->count()) }}</span>
                        </div>
                        @endif

                        <div class="flex items-center space-x-1 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ $event->comments()->count() }} {{ Str::plural('Comment', $event->comments()->count()) }}</span>
                        </div>

                        <button onclick="navigator.clipboard.writeText('{{ route('events.show', $event) }}'); alert('Link copied to clipboard!');" class="flex items-center space-x-1 text-gray-500 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            <span>Share</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4">Comments</h3>
                        
                        @if(auth()->check())
                        <form action="{{ route('events.comments.store', $event) }}" method="POST" class="mb-6">
                            @csrf
                            <textarea name="body" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add a comment..." required></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="bg-blue-600 px-4 py-2 text-white rounded hover:bg-blue-700">Post Comment</button>
                            </div>
                        </form>
                        @else
                        <div class="bg-gray-50 border p-4 rounded mb-6 text-center text-gray-600">
                            Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to leave a comment.
                        </div>
                        @endif

                        <div class="space-y-4">
                            @foreach($event->comments()->latest()->get() as $comment)
                                <div class="bg-gray-50 p-4 rounded-lg flex space-x-4">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-bold text-gray-900">{{ $comment->user->name }}</h4>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-800">{{ $comment->body }}</p>
                                    </div>
                                    @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->isAdmin()))
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                            @if($event->comments->isEmpty())
                                <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 border-t pt-6">
                        <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline">&larr; Back to Events</a>
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
