<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\About\AboutController;
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
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ManagerController;
use Symfony\Component\Routing\Router;

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

    Route::get('/about/promolife', [AboutController::class, 'promolife'])->name('about_promolife');

    Route::get('/about/bhtrade', [AboutController::class, 'bh'])->name('about_trade');
    Route::get('/about/promodreams', [AboutController::class, 'promodreams'])->name('about_promodreams');
    Route::get('/about/trademarket', [AboutController::class, 'trademarket'])->name('about_trademarket');

    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::get('company/getPosition/{id}', [CompanyController::class, 'getPositions']);
    Route::get('company/getEmployees', [CompanyController::class, 'getAllEmployees']);
    Route::get('company/getEmployees/{organization}', [CompanyController::class, 'getEmployeesByCompany']);


    Route::get('/aniversary/aniversary', [AniversaryController::class, 'aniversary'])->name('aniversary');
    Route::get('/aniversary/birthday', [AniversaryController::class, 'birthday'])->name('birthday');

    Route::get('/month', MonthController::class)->name('month');
    Route::get('/manual', ManualController::class)->name('manual');
    Route::get('/access', AccessController::class)->name('access');
    Route::get('/folder', FolderController::class)->name('folder');
    Route::get('/request', [RequestController::class, 'index'])->name('request');

    Route::get('/work', WorkController::class)->name('work');
    /*
    Route::resource('company', CompanyController::class);
    Route::resource('departmens', CompanyController::class);
    Route::resource('company', CompanyController::class);
    */
});


Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/users', [UserController::class, 'index'])->name('admin');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/users/create', [UserController::class, 'create'])->name('admin.user.create');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/admin/users/create', [UserController::class, 'store'])->name('admin.user.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.user.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');


Route::middleware(['auth:sanctum', 'verified'])->get('/admin/contact', [AdminController::class, 'contact'])->name('admin.contact');
Route::middleware(['auth:sanctum', 'verified', 'can:sistemas'])->get('/admin/contacts/create', [AdminController::class, 'contactCreate'])->name('admin.contact.create');
Route::middleware(['auth:sanctum', 'verified', 'can:sistemas'])->post('/admin/contacts/create', [AdminController::class, 'contactStore'])->name('admin.contact.store');
Route::middleware(['auth:sanctum', 'verified', 'can:sistemas'])->get('/admin/contacts/{contact}/edit', [AdminController::class, 'contactEdit'])->name('admin.contact.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:sistemas'])->put('/admin/contacts/{contact}', [AdminController::class, 'contactUpdate'])->name('admin.contact.update');
Route::middleware(['auth:sanctum', 'verified', 'can:sistemas'])->delete('/admin/contacts/{contact}', [AdminController::class, 'contactDestroy'])->name('admin.contact.destroy');


Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/admin/roles/create', [RoleController::class, 'store'])->name('admin.roles.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');


Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('dropdownlist/getPosition/{id}', [EmployeeController::class, 'getPositions']);

Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/communiques/create', [CommuniqueController::class, 'create'])->name('communique.create');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/communiques', [CommuniqueController::class, 'show'])->name('communique.show');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/communiques/create', [CommuniqueController::class, 'store'])->name('communique.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/communiques/{communique}/edit', [CommuniqueController::class, 'edit'])->name('communique.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/communiques/{communique}', [CommuniqueController::class, 'update'])->name('communique.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/communiques/{communique}', [CommuniqueController::class, 'destroy'])->name('communique.destroy');


Route::middleware(['auth:sanctum', 'verified'])->get('/request/create', [RequestController::class, 'create'])->name('request.create');
Route::middleware(['auth:sanctum', 'verified'])->post('/request', [RequestController::class, 'store'])->name('request.store');
Route::middleware(['auth:sanctum', 'verified', 'can:rh.superior'])->get('/request/{request}/edit', [RequestController::class, 'edit'])->name('request.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:rh.superior'])->put('/request/{request}', [RequestController::class, 'update'])->name('request.update');
Route::middleware(['auth:sanctum', 'verified', 'can:rh.superior'])->delete('/request/{request}', [RequestController::class, 'destroy'])->name('request.destroy');


Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/organizations', [OrganizationController::class, 'index'])->name('admin.organization.index');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/organizations/create', [OrganizationController::class, 'store'])->name('admin.organization.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('admin.organization.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('admin.organization.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('admin.organization.destroy');


Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/departments/create', [DepartmentsController::class, 'store'])->name('admin.department.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/departments/{department}/edit', [DepartmentsController::class, 'edit'])->name('admin.department.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/departments/{department}', [DepartmentsController::class, 'update'])->name('admin.department.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/departments/{department}', [DepartmentsController::class, 'destroy'])->name('admin.department.destroy');



Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/positions/create', [PositionController::class, 'store'])->name('admin.position.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('admin.position.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/positions/{position}', [PositionController::class, 'update'])->name('admin.position.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/positions/{position}', [PositionController::class, 'destroy'])->name('admin.position.destroy');

Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/managers', [ManagerController::class, 'index'])->name('admin.manager.index');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/managers/create', [ManagerController::class, 'create'])->name('admin.manager.create');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->post('/managers', [ManagerController::class, 'store'])->name('admin.manager.store');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('/managers/{manager}/edit', [ManagerController::class, 'edit'])->name('admin.manager.edit');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->put('/managers/{manager}', [ManagerController::class, 'update'])->name('admin.manager.update');
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->delete('/managers/{manager}', [ManagerController::class, 'destroy'])->name('admin.manager.destroy');

Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('manager/getPosition/{id}', [ManagerController::class, 'getPosition']);
Route::middleware(['auth:sanctum', 'verified', 'can:admin'])->get('manager/getEmployee/{id}', [ManagerController::class, 'getEmployee']);
