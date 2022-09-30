<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Login ------------------------------------------------------------------------------------
Route::get('/redirect', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('login-google');

// Documento ------------------------------------------------------------------------------------
Route::get('/newdoc', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('documents', DocumentoController::class)->middleware(['auth', 'verified']);

// Gerenciar Documento ------------------------------------------------------------------------------------
Route::get('/gerenciar/{id}', [DocumentoController::class, 'gerenciar_trabalho'], function () {
    return Inertia::render('GerenciarTrabalho');
})->middleware(['auth', 'verified'])->name('gerenciar_trabalho');

// Referências ------------------------------------------------------------------------------------
Route::get('/referencias/{id}', [DocumentoController::class, 'buscar_referencias'], function () {
    return Inertia::render('GerenciarReferencias');
})->middleware(['auth', 'verified'])->name('gerenciar_referencias');

Route::get('/add_referencia/{id}', function($id) {
    return Inertia::render('Referencias/AddReferencia', [
        'doc_id' => $id
    ]);
})->middleware(['auth', 'verified'])->name('adicionar_referencia');

Route::post('/salvar_referencia', [DocumentoController::class, 'salvar_referencia'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';

// Route::post('/export/', [DocumentoController::class, 'exportPdf']);
// require __DIR__.'/auth.php';

// Route::post('/export/{id}', [DocumentoController::class, 'exportOnUpdate']);
// require __DIR__.'/auth.php';

