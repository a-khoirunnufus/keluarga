<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonApiController;
use App\Http\Controllers\FamilyApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/person', [PersonApiController::class, 'getAll']);
Route::post('/person', [PersonApiController::class, 'store']);
Route::get('/person/{personId}', [PersonApiController::class, 'get']);
Route::put('/person/{personId}', [PersonApiController::class, 'update']);
Route::delete('/person/{personId}', [PersonApiController::class, 'destroy']);

Route::get('/family/grandparent/{personId}', [FamilyApiController::class, 'grandparent']);
Route::get('/family/parent/{personId}', [FamilyApiController::class, 'parent']);
Route::get('/family/child/{personId}', [FamilyApiController::class, 'child']);
Route::get('/family/grandchild/{personId}', [FamilyApiController::class, 'grandchild']);
Route::get('/family/aunt/{personId}', [FamilyApiController::class, 'aunt']);
Route::get('/family/uncle/{personId}', [FamilyApiController::class, 'uncle']);
Route::get('/family/cousin/{personId}', [FamilyApiController::class, 'cousin']);
Route::get('/family/tree', [FamilyApiController::class, 'getTree']);
