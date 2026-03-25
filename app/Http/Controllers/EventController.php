<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        return view('events.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'image_path' => 'nullable|image|max:2048',
            'video_path' => 'nullable|mimes:mp4,mov,ogg,qt|max:40000',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        if ($request->hasFile('video_path')) {
            $videoPath = $request->file('video_path')->store('events/videos', 'public');
            $validated['video_path'] = $videoPath;
        }

        auth()->user()->events()->create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'image_path' => 'nullable|image|max:2048',
            'video_path' => 'nullable|mimes:mp4,mov,ogg,qt|max:40000',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        if ($request->hasFile('video_path')) {
            $videoPath = $request->file('video_path')->store('events/videos', 'public');
            $validated['video_path'] = $videoPath;
        }

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
