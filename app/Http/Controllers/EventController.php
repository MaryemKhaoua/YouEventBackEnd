<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    /**
     * Affiche tous les événements.
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    /**
     * Enregistre un nouvel événement.
     */
    public function store(EventRequest $request)
    {
        $existingEvent = Event::where('title', $request->title)->first();
        if ($existingEvent) {
            return response()->json(['error' => 'Un événement avec ce titre existe déjà.'], 400);
        }

        $event = Event::create($request->validated());
        return response()->json(['message' => 'Événement créé avec succès', 'event' => $event], 201);
    }

    /**
     * Affiche un événement spécifique.
     */
    public function show(string $id)
    {
        $event = Event::find($id);
        
        if (!$event) {
            return response()->json(['error' => 'Événement non trouvé.'], 404);
        }

        return response()->json($event);
    }

    /**
     * Met à jour un événement existant.
     */
    public function update(EventRequest $request, string $id)
    {
        $event = Event::find($id);
        
        if (!$event) {
            return response()->json(['error' => 'Événement non trouvé.'], 404);
        }

        $existingEvent = Event::where('title', $request->title)->where('id', '!=', $id)->first();
        if ($existingEvent) {
            return response()->json(['error' => 'Un autre événement avec ce titre existe déjà.'], 400);
        }

        $event->update($request->validated());

        return response()->json(['message' => 'Événement mis à jour avec succès', 'event' => $event]);
    }

    /**
     * Supprime un événement.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Événement non trouvé.'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Événement supprimé avec succès']);
    }
}
