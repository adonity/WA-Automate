<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OutboxController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\BlastGroupController;
use App\Http\Controllers\ProxyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/outbox', [OutboxController::class, 'storeAPI']);
Route::post('/inbox', [InboxController::class, 'storeAPI']);

Route::post('/device/status-update/{id}', [DeviceController::class, 'statusUpdate']);

Route::post('/blast/status-update', [BlastGroupController::class, 'statusUpdate']);
Route::get('/proxy', [ProxyController::class, 'getAPI']);
