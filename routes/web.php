<?php

use App\Http\Controllers\ComputerController;
use App\Http\Controllers\WorkerController;
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
    return view('dashboard');
})->name('dashboard');


# workers
Route::get('/workers', [WorkerController::class, 'list'])->name('worker.list');
Route::get('/workers/{workerId}/show', [WorkerController::class, 'show'])->name('worker.show');

# computers
Route::get('/computers', [ComputerController::class, 'list'])->name('computer.list');
Route::get('/computers/{computerId}/show', [ComputerController::class, 'show'])->name('computer.show');




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
