<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ComponenteController;

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

use Laravel\Socialite\Facades\Socialite;

Route::get('/redirect', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/documents', [DocumentoController::class, 'index'],  function () {
    return Inertia::render('Documents');
})->middleware(['auth', 'verified'])->name('documents');

Route::get('/documents/{id}', [DocumentoController::class, 'getById'],  function ($id) {
    return Inertia::render('EditAcademicWork');
})->middleware(['auth', 'verified'])->name('editDocument');

Route::post('/documento', [DocumentoController::class, 'store']);
require __DIR__.'/auth.php';

Route::post('/doc/{id}', [DocumentoController::class, 'update']);
require __DIR__.'/auth.php';

Route::delete('/documento/{id}', [DocumentoController::class, 'destroy']);
require __DIR__.'/auth.php';
