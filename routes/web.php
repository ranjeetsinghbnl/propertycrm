<?php

// use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\ImagesController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/', function () {
    return redirect()->route('properties.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('properties', [PropertiesController::class, 'index'])->name('properties.index');
    Route::get('properties/create', [PropertiesController::class, 'create'])->name('properties.create');
    Route::post('properties', [PropertiesController::class, 'store'])->name('properties.store');
    Route::get('properties/{property}/edit', [PropertiesController::class, 'edit'])->name('properties.edit');
    Route::post('properties/{property}', [PropertiesController::class, 'update'])->name('properties.update'); // Problem with multipart data in PUT request, so using POST call
    Route::delete('properties/{property}', [PropertiesController::class, 'destroy'])->name('properties.destroy');
});

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
->where('path', '.*')
->name('image');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//require __DIR__.'/auth.php';
