<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;

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

Route::get('/', [PersonController::class, 'index']);
Route::post('/person', [PersonController::class, 'store']);
Route::post('/person/{id}/update', [PersonController::class, 'update']);
Route::post('/person/{id}/destroy', [PersonController::class, 'destroy']);
