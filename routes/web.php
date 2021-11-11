<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AniversaryController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\CommuniqueController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\RequestController;


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

Auth::routes();

//=Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth:sanctum', 'verified'])->get('/', HomeController::class)->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/home', HomeController::class)->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/about', AboutController::class)->name('about');

Route::middleware(['auth:sanctum', 'verified'])->get('/company', CompanyController::class )->name('company');

Route::middleware(['auth:sanctum', 'verified'])->get('/aniversary', AniversaryController::class )->name('aniversary');

Route::middleware(['auth:sanctum', 'verified'])->get('/month', MonthController::class)->name('month');

Route::middleware(['auth:sanctum', 'verified'])->get('/communique',CommuniqueController::class)->name('communique');

Route::middleware(['auth:sanctum', 'verified'])->get('/manual', ManualController::class)->name('manual');

Route::middleware(['auth:sanctum', 'verified'])->get('/access', AccessController::class)->name('access');

Route::middleware(['auth:sanctum', 'verified'])->get('/folder', FolderController::class)->name('folder');

Route::middleware(['auth:sanctum', 'verified'])->get('/request', RequestController::class)->name('request');

