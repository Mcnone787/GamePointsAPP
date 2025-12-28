<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PlayerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/contacto', function () {
    return Inertia::render('Contact');
})->name('contact');

// Rutas accesibles para TODOS (incluyendo invitados)
Route::post('/rooms/join', [RoomController::class, 'join'])->name('rooms.join.post');
Route::get('/rooms/{room:code}', [RoomController::class, 'show'])->name('rooms.show');
Route::patch('/players/{player}', [PlayerController::class, 'updatePoints'])->name('players.updatePoints');

// Rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [RoomController::class, 'index'])->name('dashboard');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/join', [RoomController::class, 'showJoinForm'])->name('rooms.join');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
