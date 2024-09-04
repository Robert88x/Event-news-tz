<?php

use Illuminate\Support\Facades\Route;

//add manually controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DiscussController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'welcome'])->name('home');

Route::get('/discussions', [DiscussController::class, 'index'])->name('index');
Route::get('/discussions/{id}/show', [DiscussController::class, 'show'])->name('show');
Route::post('/discussions/{id}', [DiscussController::class, 'store'])->name('store');
Route::post('/discussions/{id}/edit', [DiscussController::class, 'edit'])->name('edit');
Route::put('/discussions/{id}/update', [DiscussController::class, 'update'])->name('update');
Route::delete('/discussions', [DiscussController::class, 'destroy'])->name('destroy');



