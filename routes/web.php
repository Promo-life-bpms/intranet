<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\About\BhtradeController;
use App\Http\Controllers\About\PromolifeController;
use App\Http\Controllers\About\PromodreamsController;
use App\Http\Controllers\About\TrademarketController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AniversaryController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\CommuniqueController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\WorkController;
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
    return redirect('/home');
});

Auth::routes();

//=Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    Route::get('/communique', [CommuniqueController::class, 'index'])->name('communique.index');
    Route::get('/communique/create', [CommuniqueController::class, 'create'])->name('communique.create');
    Route::post('/communique', [CommuniqueController::class, 'store'])->name('communique.store');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/about', AboutController::class)->name('about');
Route::middleware(['auth:sanctum', 'verified'])->get('/about/bhtrade', BhtradeController::class)->name('about_trade');
Route::middleware(['auth:sanctum', 'verified'])->get('/about/promolife', PromolifeController::class)->name('about_promolife');
Route::middleware(['auth:sanctum', 'verified'])->get('/about/promodreams', PromodreamsController::class)->name('about_promodreams');
Route::middleware(['auth:sanctum', 'verified'])->get('/about/trademarket', TrademarketController::class)->name('about_trademarket');

Route::middleware(['auth:sanctum', 'verified'])->get('/company', CompanyController::class)->name('company');

Route::middleware(['auth:sanctum', 'verified'])->get('/aniversary/aniversary', [AniversaryController::class,'aniversary'])->name('aniversary');
Route::middleware(['auth:sanctum', 'verified'])->get('/aniversary/birthday', [AniversaryController::class,'birthday'])->name('birthday');

Route::middleware(['auth:sanctum', 'verified'])->get('/month', MonthController::class)->name('month');
Route::middleware(['auth:sanctum', 'verified'])->get('/manual', ManualController::class)->name('manual');
Route::middleware(['auth:sanctum', 'verified'])->get('/access', AccessController::class)->name('access');
Route::middleware(['auth:sanctum', 'verified'])->get('/folder', FolderController::class)->name('folder');
Route::middleware(['auth:sanctum', 'verified'])->get('/request', [RequestController::class,'index'])->name('request');

Route::middleware(['auth:sanctum', 'verified'])->get('/work', WorkController::class)->name('work');
