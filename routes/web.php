<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PausaController;
use App\Http\Controllers\PontoController;
use App\Models\Ponto;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    //Rotas para a Categorias (CREATE, SHOW, EDIT)
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');

    Route::post('/categorias/store', [CategoriaController::class, 'store'])->name('categoria.store');

    //Rotas para Pontos
    Route::get('/pontos/{categoria_id}', [PontoController::class, 'index'])->name('pontos.index');

    Route::post('/ponto/store', [PontoController::class, 'store'])->name('ponto.store');

    Route::put('/ponto/update', [PontoController::class, 'update'])->name('ponto.update');

    //Rota para pausas
    Route::post('/pausa/store', [PausaController::class, 'store'])->name('pausa.store');

    Route::put('/pausas/update', [PausaController::class, 'update'])->name('pausa.update');
});
