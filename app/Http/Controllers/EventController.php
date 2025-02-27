<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(EventRequest $request): JsonResponse
    {
        $user = $request->attributes->get('user');
    
        $eventData = $request->validated();
        $eventData['created_by'] = $user->id;
    
        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }
    
        $event = Event::create($eventData);
    
        return response()->json([
            'message' => 'Event créé avec succès',
            'event' => $event
        ], 201);
    }
    

    public function show(string $id)
    {
        $event = Event::find($id);
        
        if (!$event) {
            return response()->json(['error' => 'Event not found.'], 404);
        }

        return response()->json($event);
    }

    public function update(EventRequest $request, string $id)
    {
        $event = Event::find($id);
        
        if (!$event) {
            return response()->json(['error' => 'Event not found.'], 404);
        }

        $existingEvent = Event::where('title', $request->title)->where('id', '!=', $id)->first();
        if ($existingEvent) {
            return response()->json(['error' => 'Another event with this title already exists.'], 400);
        }

        $event->update($request->validated());

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
    }

    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found.'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
