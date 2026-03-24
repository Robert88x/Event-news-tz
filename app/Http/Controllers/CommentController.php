<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $event->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        if (!auth()->check() || (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
