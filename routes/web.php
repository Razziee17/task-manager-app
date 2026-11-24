<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('dashboard');
Route::get('/overview', [TaskController::class, 'overview'])->name('overview');
Route::get('/tasks', [TaskController::class, 'tasks'])->name('tasks.index');
