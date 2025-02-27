<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Event;
use App\Models\Ticket;

class ReservationController extends Controller
{
  
    public function store(Request $request)
    {
        $user = $request->attributes->get('user');
    
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $userId = $user->id;
    
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $event = Event::findOrFail($request->event_id);
        $ticket = Ticket::findOrFail($request->ticket_id);
    
        if ($event->max_participants && $event->reservations()->count() >= $event->max_participants) {
            return response()->json(['error' => 'Event is full.'], 400);
        }
    
        if (Reservation::where('user_id', $userId)->where('event_id', $event->id)->where('ticket_id', $ticket->id)->exists()) {
            return response()->json(['error' => 'You have already reserved this ticket.'], 400);
        }
    
        if ($ticket->quantity < $request->quantity) {
            return response()->json(['error' => 'Not enough tickets available.'], 400);
        }
    
        $reservation = Reservation::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'quantity' => $request->quantity,
        ]);
    
        $ticket->update(['quantity' => $ticket->quantity - $request->quantity]);
    
        return response()->json(['message' => 'Ticket reserved successfully!', 'reservation' => $reservation], 201);
    }
    
    

    public function destroy(string $id, Request $request)
    {
        $user = $request->attributes->get('user');
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
    
        $reservation = Reservation::where('event_id', $id)
            ->where('user_id', $user->id)
            ->first();
    
        if (!$reservation) {
            return response()->json(['error' => 'You are not registered for this event.'], 404);
        }
    
        $reservation->ticket->update(['quantity' => $reservation->ticket->quantity + $reservation->quantity]);
    
        $reservation->delete();
    
        return response()->json(['message' => 'You have canceled your ticket reservation.']);
    }
    

    public function myEvents(Request $request)
    {
        $user = $request->attributes->get('user');
    
        if (!$user) {
            return response()->json(['error' => 'User not found in request attributes'], 401);
        }
    
        $userId = $user->id;
    
        $events = Event::whereHas('reservations', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    
        return response()->json($events);
    }
    
}