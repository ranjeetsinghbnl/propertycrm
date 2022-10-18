<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Services\CraigPropertyService;

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

Route::get('sync-properties', function (CraigPropertyService $craigPropertyService) {
    // App\Jobs\FetchProperties::dispatch(); // if using jobs, we need to increase the timeout.
    $craigPropertyService->handleCraigProperties();
    return ['ok' => true];
});
