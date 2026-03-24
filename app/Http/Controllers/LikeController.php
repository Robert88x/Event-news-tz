<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Event $event)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        $like = $event->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
        } else {
            $event->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        return back();
    }
}
