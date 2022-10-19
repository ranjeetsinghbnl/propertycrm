<?php

// use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PropertiesController;
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


Route::middleware(['auth'])->group(function () {
    Route::get('properties', [PropertiesController::class, 'index'])->name('properties.index');
    Route::get('properties/create', [PropertiesController::class, 'create'])->name('properties.create');
    Route::post('properties', [PropertiesController::class, 'store'])->name('properties.store');
    Route::get('properties/{property}/edit', [PropertiesController::class, 'edit'])->name('properties.edit');
    Route::put('properties/{property}', [PropertiesController::class, 'update'])->name('properties.update');
    Route::delete('properties/{property}', [PropertiesController::class, 'destroy'])->name('properties.destroy');
});

// Route::controller(PropertiesController::class)->group(['name' => 'properties', 'middleware' => ['auth']], function () {
//     Route::get('properties', 'index')->name('index');
//     Route::get('properties/create', 'create')->name('create');
//     Route::post('properties', 'store')->name('store');
//     Route::get('properties/{property}/edit', 'edit')->name('edit');
//     Route::put('properties/{property}', 'update')->name('update');
//     Route::delete('properties/{property}', 'destroy')->name('destroy');
// });


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//require __DIR__.'/auth.php';
