<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\EventsController;
use App\Controllers\AdminController;
use App\Controllers\MembersController;
use App\Controllers\ParticipantController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('public.index');
Route::get('/home/{event_id}', [HomeController::class, 'show'])->name('public.show');
Route::post('/home/{event_id}/subscribe', [ParticipantController::class, 'register'])->name('public.subscribe');

Route::get('/login', [AuthController::class, 'new'])->name('users.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'destroy'])->name('users.logout');

    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
    Route::get('/events/new', [EventsController::class, 'new']) ->name('events.new');
    Route::post('/events/create', [EventsController::class, 'create'])->name('events.create');

    Route::middleware('event')->group(function () {
        Route::get('/events/{event_id}', [EventsController::class, 'show'])->name('events.show');
        Route::get('/events/{event_id}/edit', [EventsController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event_id}', [EventsController::class, 'update'])->name('events.update');
        Route::delete('/event/{event_id}', [EventsController::class, 'destroy'])->name('events.destroy');

        Route::get('/events/{event_id}/members', [MembersController::class, 'index'])->name('members.index');
        Route::get('/events/{event_id}/participants', [ParticipantController::class, 'index'])->name('participants.index');
        Route::post('/events/{event_id}/participants/{participant_id}/remove', [ParticipantController::class, 'remove'])->name('participants.remove');

        Route::middleware('event_owner')->group(function () {
            Route::get('/events/{event_id}/members/new', [MembersController::class, 'new'])->name('members.new');
            Route::put('/events/{event_id}/members/add', [MembersController::class, 'add'])->name('members.add');
            Route::delete('/events/{event_id}/members/{user_id}/remove', [MembersController::class, 'remove'])
                ->name('events.remove');
        });
    });
});

Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
});
