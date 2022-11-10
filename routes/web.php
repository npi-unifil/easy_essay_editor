<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\DocumentoController;
use App\Mail\pdfCreatedMail;
use Illuminate\Support\Facades\Mail;
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
Route::get('/novo_documento/{template}', [DocumentoController::class, 'novo_doc'])->middleware(['auth', 'verified']);

Route::get('/newdoc', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('documents', DocumentoController::class)->middleware(['auth', 'verified']);

Route::delete('/deleteComponent/{id}', [DocumentoController::class, 'removeComponent'])->middleware(['auth', 'verified']);

// Capitulos
Route::post('/newChapter', [DocumentoController::class, 'saveChapter'])->middleware(['auth', 'verified']);

Route::get('/newchapter/{id}', [DocumentoController::class, 'newChapter'])->middleware(['auth', 'verified']);

Route::get('/editChapter/{id}', [DocumentoController::class, 'chapterComponent'])->middleware(['auth', 'verified']);

Route::delete('/chapter/{id}', [DocumentoController::class, 'removeChapter'])->middleware(['auth', 'verified']);

// Gerenciar Documento ------------------------------------------------------------------------------------
Route::get('/gerenciar/{id}', [DocumentoController::class, 'gerenciar_trabalho'])->middleware(['auth', 'verified'])->name('gerenciar_trabalho');

// Referências ------------------------------------------------------------------------------------
Route::get('/referencias/{id}', [DocumentoController::class, 'buscar_referencias'])->middleware(['auth', 'verified'])->name('gerenciar_referencias');

Route::get('/add_referencia/{id}', [DocumentoController::class, 'add_referencia'])->middleware(['auth', 'verified'])->name('adicionar_referencia');

Route::get('/editar_referencia/{id}', [DocumentoController::class, 'editar_referencia'])->middleware(['auth', 'verified'])->name('editar_referencia');

Route::delete('deletar_referencia/{id}', [DocumentoController::class, 'deletar_referencia'])->middleware(['auth', 'verified'])->name('deletar_referencia');

Route::post('/salvar_referencia', [DocumentoController::class, 'salvar_referencia'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';

Route::get('/export/{document}', [DocumentoController::class, 'exportPdf'])->middleware(['auth', 'verified']);
require __DIR__.'/auth.php';

// Route::post('/export/{id}', [DocumentoController::class, 'exportOnUpdate']);
// require __DIR__.'/auth.php';

