<?php

use App\Http\Controllers\DiaSemanaTecController;
use App\Http\Controllers\MoldeController;
use App\Http\Controllers\PlanSemanalController;
use App\Http\Controllers\TaksController;
use App\Http\Controllers\TecnicoController;
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

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::resource('molde', MoldeController::class);
    Route::get('plan', [PlanSemanalController::class, 'index'])->name('plan');
    Route::put('darbajamolde/{id}', [MoldeController::class, 'darbaja'])->name('darbajamolde');
    Route::put('daraltamolde/{id}', [MoldeController::class, 'altamol'])->name('daraltamolde');
    Route::resource('tecnicos', TecnicoController::class);
    Route::put('darbajatecnico/{id}', [TecnicoController::class, 'bajatecnico'])->name('darbajatecnico');
    Route::put('daraltatecnico/{id}', [TecnicoController::class, 'altatecnico'])->name('daraltatecnico');
    Route::resource('disemtec', DiaSemanaTecController::class);
    Route::resource('task',TaksController::class);
    Route::post('/tasks/split', [TaksController::class, 'split'])->name('tasks.split');


});