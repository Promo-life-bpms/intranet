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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

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
    Route::get('/communique/create', [CommuniqueController::class, 'create'])->middleware('can:communique.create')->name('communique.create');
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

Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/users', [UserController::class,'index'])->name('admin.users');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/user/create', [UserController::class,'create'])->name('admin.user.create');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->post('/admin/user/create', [UserController::class,'store'])->name('admin.user.store');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/user/{user}/edit', [UserController::class,'edit'])->name('admin.user.edit');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->put('/admin/user/{user}', [UserController::class,'update'])->name('admin.user.update');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->delete('/admin/user/{user}', [UserController::class,'destroy'])->name('admin.user.destroy');


Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/employee', [AdminController::class,'employees'])->name('admin.employee');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/contact', [AdminController::class,'contact'])->name('admin.contact');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/contacts/create', [AdminController::class,'contactCreate'])->name('admin.contact.create');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->post('/admin/contacts/create', [AdminController::class,'contactStore'])->name('admin.contact.store');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/contacts/{contact}/edit', [AdminController::class,'contactEdit'])->name('admin.contact.edit');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->put('/admin/contacts/{contact}', [AdminController::class,'contactUpdate'])->name('admin.contact.update');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->delete('/admin/contacts/{contact}', [AdminController::class,'contactDestroy'])->name('admin.contact.destroy');


Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/roles', [RoleController::class,'index'])->name('admin.roles');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/roles/create', [RoleController::class,'create'])->name('admin.roles.create');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->post('/admin/roles/create', [RoleController::class,'store'])->name('admin.roles.store');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->get('/admin/roles/{role}/edit', [RoleController::class,'edit'])->name('admin.roles.edit');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->put('/admin/roles/{role}', [RoleController::class,'update'])->name('admin.roles.update');
Route::middleware(['auth:sanctum', 'verified','can:admin.users'])->delete('/admin/roles/{role}', [RoleController::class,'destroy'])->name('admin.roles.destroy');
