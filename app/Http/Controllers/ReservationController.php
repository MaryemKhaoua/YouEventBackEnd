<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Allow a user to join an event.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $event = Event::findOrFail($request->event_id);

        if ($event->max_participants && $event->reservations()->count() >= $event->max_participants) {
            return response()->json(['error' => 'Event is full.'], 400);
        }

        if (Reservation::where('user_id', $userId)->where('event_id', $event->id)->exists()) {
            return response()->json(['error' => 'You have already joined this event.'], 400);
        }

        $reservation = Reservation::create([
            'user_id' => $userId,
            'event_id' => $event->id,
        ]);

        return response()->json(['message' => 'You have successfully joined the event!', 'reservation' => $reservation], 201);
    }

    /**
     * Remove a user from an event.
     */
    public function destroy(string $id)
    {
        $userId = Auth::id();

        $reservation = Reservation::where('event_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$reservation) {
            return response()->json(['error' => 'You are not registered for this event.'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'You have left the event.']);
    }

    /**
     * Get all events the authenticated user is attending.
     */
    public function myEvents()
    {
        $userId = Auth::id();
        $events = Event::whereHas('reservations', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return response()->json($events);
    }
}