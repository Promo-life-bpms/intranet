<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Route;

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

Route::get('/getPublications/{hashedToken}', [APIController::class, 'getPublications'])->name('api.getPublications');
Route::post('/postPublications', [APIController::class, 'postPublications'])->name('api.postPublications');
Route::post('/postLike', [APIController::class, 'postLike'])->name('api.postLike');
Route::post('/postUnlike', [APIController::class, 'postUnlike'])->name('api.postUnlike');
Route::post('/postComment', [APIController::class, 'postComment'])->name('api.postComment');
Route::get('/getProfile/{id}', [APIController::class, 'getProfile'])->name('api.getProfile');

Route::get('/getRequest/{hashedToken}', [APIController::class, 'getRequest'])->name('api.as');

Route::post('/postDeleteRequest', [APIController::class, 'postDeleteRequest'])->name('api.postDeleteRequest');
Route::post('/postDeletePublication', [APIController::class, 'postDeletePublication'])->name('api.postDeletePublication');

Route::get('/getUserMessages/{hashedToken}', [APIController::class, 'getUserMessages'])->name('api.getUserMessages');
Route::post('/postUserMessages', [APIController::class, 'postUserMessages'])->name('api.postUserMessages');
Route::post('/postConversation', [APIController::class, 'postConversation'])->name('api.postConversation');
Route::post('/postUpdatePublication', [APIController::class, 'postUpdatePublication'])->name('api.postUpdatePublication');

Route::post('/postImageRequest', [APIController::class, 'postImageRequest'])->name('api.postImageRequest');
