<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/getAllUsers', [ApiController::class, 'getAllUsers']);

Route::get('/manuals', [APIController::class, 'manuals'])->name('api.manual');
Route::get('/month-employees', [APIController::class, 'monthEmployees'])->name('api.month.employees');
Route::get('/month-anniversaries', [APIController::class, 'aniversary'])->name('api.anniversaries');
Route::get('/month-birthdays', [APIController::class, 'birthday'])->name('api.birthdays');
Route::get('/communicate', [APIController::class, 'communicate'])->name('api.communicate');
Route::get('/directory', [APIController::class, 'directory'])->name('api.directory');
Route::get('/organization/{id}', [APIController::class, 'organization'])->name('api.organization');

Route::post('/login', [APIController::class, 'requestToken'])->name('api.login');
Route::get('/getUser/{hashedToken}', [APIController::class, 'getUser'])->name('api.getUser');
Route::get('/getRequest/{hashedToken}', [APIController::class, 'getRequest'])->name('api.getRequest');
Route::post('/postRequest', [APIController::class, 'postRequest'])->name('api.postRequest');

/* 
Route::get('/api/logout', [APIController::class, 'logout'])->name('api.logout');
Route::get('/api/user', [APIController::class, 'getUser'])->name('api.user');
/* 
Route::post('/update', 'UserController@update');
Route::get('/logout', 'UserController@logout'); */

//Solictudes - Pendiente