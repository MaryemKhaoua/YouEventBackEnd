<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * 📌 Afficher tous les événements (accessible à tous, même sans connexion)
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    /**
     * 📌 Créer un événement (Seulement organisateur et admin)
     */
    public function store(EventRequest $request)
    {
        dd($request->validated());

        $user = Auth::user();

        if (!$user || (!$user->hasRole('organizer') && !$user->hasRole('admin'))) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        $event = Event::create(array_merge($request->validated(), ['created_by' => $user->id]));

        return response()->json(['message' => 'Événement créé avec succès', 'event' => $event], 201);
    }

    /**
     * 📌 Afficher un seul événement (accessible à tous)
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
     * 📌 Mettre à jour un événement (Organisateur et Admin uniquement)
     */
    public function update(EventRequest $request, string $id)
    {
        $event = Event::find($id);
        $user = Auth::user();

        if (!$event) {
            return response()->json(['error' => 'Événement non trouvé.'], 404);
        }

        if (!$user || (!$user->hasRole('admin') && $event->created_by !== $user->id)) {
            return response()->json(['error' => 'Vous n\'avez pas la permission de modifier cet événement.'], 403);
        }

        $event->update($request->validated());

        return response()->json(['message' => 'Événement mis à jour avec succès', 'event' => $event]);
    }

    /**
     * 📌 Supprimer un événement 
     * - Admin peut supprimer tous les événements.
     * - Organisateur peut supprimer seulement ses propres événements.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);
        $user = Auth::user();

        if (!$event) {
            return response()->json(['error' => 'Événement non trouvé.'], 404);
        }

        if (!$user || (!$user->hasRole('admin') && $event->created_by !== $user->id)) {
            return response()->json(['error' => 'Vous n\'avez pas la permission de supprimer cet événement.'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Événement supprimé avec succès']);
    }
}
