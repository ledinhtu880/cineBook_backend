<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
