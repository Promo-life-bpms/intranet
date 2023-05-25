<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\FirebaseNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/getUsers', [ApiController::class, 'getUsers']);
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
Route::get('/getTeamMembers/{hashedToken}', [APIController::class, 'getTeamMembers'])->name('api.getTeamMembers');


/* Intranet movil version 1.1 */

Route::post('/postRequestV11', [APIController::class, 'postRequestV11'])->name('api.postRequestV11');


Route::get('/getManagerRequest/{hashedToken}', [APIController::class, 'getManagerRequest'])->name('api.getManagerRequest');
Route::get('/getRhRequest', [APIController::class, 'getRhRequest'])->name('api.getRhRequest');

Route::post('/postCreateRequest', [APIController::class, 'postCreateRequest'])->name('api.postCreateRequest');

Route::post('/postManagerRequest', [APIController::class, 'postManagerRequest'])->name('api.postManagerRequest');
Route::post('/postRhRequest', [APIController::class, 'postRhRequest'])->name('api.postRhRequest');

Route::post('/firebase/publication', [FirebaseNotificationController::class, 'publication'])->name('api.firebase.publication');

