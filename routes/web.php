<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/complete-task/{id}', [TaskController::class, 'completeTask'])->name('tasks.complete');
Route::post('/delete-task/{id}', [TaskController::class, 'deleteTask'])->name('tasks.deleteTask');
