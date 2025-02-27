<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;  
use App\Http\Controllers\EventController;  
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TicketController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);





Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::put('/categories/{category}', [CategoryController::class, 'update']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);



Route::group([

      'middleware' => 'jwt'
    
    ], function ($router) {
        Route::post('/events', [EventController::class, 'store']);
        Route::get('/events/{id}', [EventController::class, 'show']);
        Route::put('/events/{id}', [EventController::class, 'update']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
    
    });

    Route::get('/events', [EventController::class, 'index']);


    Route::group([

        'middleware' => 'jwt'
      
      ], function ($router) {
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
        Route::get('/my-events', [ReservationController::class, 'myEvents']);
      
      });

// Ticket Routes


Route::get('/tickets', [TicketController::class, 'index']);
Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);
Route::put('/tickets/{id}', [TicketController::class, 'update']);
Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);







