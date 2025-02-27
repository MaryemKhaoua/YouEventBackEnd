<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::middleware('auth')->group(function () {
//     Route::get('/events', [EventController::class, 'index']); // Lister tous les événements
//     Route::post('/events', [EventController::class, 'store']); // Créer un événement
//     Route::get('/events/{id}', [EventController::class, 'show']); // Afficher un événement spécifique
//     Route::put('/events/{id}', [EventController::class, 'update']); // Mettre à jour un événement
//     Route::delete('/events/{id}', [EventController::class, 'destroy']); // Supprimer un événement
// });