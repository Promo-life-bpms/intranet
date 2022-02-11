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
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\NoWorkingDaysController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationsController;
use App\Http\Controllers\RequestCalendarController;
use App\Models\RequestCalendar;
use Illuminate\Support\Facades\Auth;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/home', HomeController::class)->name('home');

    Route::get('/about/promolife', [AboutController::class, 'promolife'])->name('about_promolife');
    Route::get('/about/bhtrade', [AboutController::class, 'bh'])->name('about_trade');
    Route::get('/about/promodreams', [AboutController::class, 'promodreams'])->name('about_promodreams');
    Route::get('/about/trademarket', [AboutController::class, 'trademarket'])->name('about_trademarket');

    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::get('company/getPosition/{id}', [CompanyController::class, 'getPositions']);
    Route::get('company/getEmployees', [CompanyController::class, 'getAllEmployees']);
    Route::get('company/getEmployeesByOrganization/{organization}', [CompanyController::class, 'getEmployeesByOrganization']);
    Route::get('company/getEmployeesByDepartment/{department}', [CompanyController::class, 'getEmployeesByDepartment']);
    Route::get('contact/getContacts/{contact}', [ContactController::class, 'getContacts']);

    Route::get('/aniversary/aniversary', [AniversaryController::class, 'aniversary'])->name('aniversary');
    Route::get('/aniversary/birthday', [AniversaryController::class, 'birthday'])->name('birthday');

    Route::get('/month', MonthController::class)->name('month');
    // Route::get('/manual', ManualController::class)->name('manual');
    Route::get('/folder', FolderController::class)->name('folder');
    Route::get('/work', WorkController::class)->name('work');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        // Route::resource('roles', RoleController::class);
        Route::resource('departments', DepartmentsController::class);
        Route::resource('position', PositionController::class);
        Route::resource('manager', ManagerController::class);
        Route::resource('organization', OrganizationController::class);
    });

    Route::resource('manuals', ManualController::class);
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('contacts', ContactController::class);

     });

    Route::post('contacts/export/', [ContactController::class, 'export'])->name('contacts.export');

    Route::resource('communiques', CommuniqueController::class);

    /*     Route::resource('days-no-working', NoWorkingDaysController::class);
 */
    Route::get('/days-no-working', [NoWorkingDaysController::class, 'index'])->name('admin.noworkingdays.index');
    Route::get('/days-no-working/create', [NoWorkingDaysController::class, 'create'])->name('admin.noworkingdays.create');
    Route::post('/days-no-working', [NoWorkingDaysController::class, 'store'])->name('admin.noworkingdays.store');
    Route::get('/days-no-working/{noworkingday}/edit', [NoWorkingDaysController::class, 'edit'])->name('admin.noworkingdays.edit');
    Route::put('/days-no-working/{noworkingday}', [NoWorkingDaysController::class, 'update'])->name('admin.noworkingdays.update');
    Route::delete('/days-no-working/{noworkingday}', [NoWorkingDaysController::class, 'destroy'])->name('admin.noworkingdays.delete');

    Route::get('/access', [AccessController::class, 'index'])->name('access');
    Route::get('/access/create', [AccessController::class, 'create'])->name('access.create');
    Route::post('/access', [AccessController::class, 'store'])->name('access.store');
    Route::get('/access/{acc}/edit', [AccessController::class, 'edit'])->name('access.edit');
    Route::put('/access/{acc}', [AccessController::class, 'update'])->name('access.update');
    Route::delete('/access/{acc}', [AccessController::class, 'destroy'])->name('access.delete');

    Route::get('/vacations', [VacationsController::class, 'index'])->middleware('role:rh')->name('admin.vacations.index');
    Route::get('/vacations/create', [VacationsController::class, 'create'])->middleware('role:rh')->name('admin.vacations.create');
    Route::post('/vacations', [VacationsController::class, 'store'])->middleware('role:rh')->name('admin.vacations.store');
    Route::get('/vacations/{user}/edit', [VacationsController::class, 'edit'])->middleware('role:rh')->name('admin.vacations.edit');
    Route::put('/vacations/{vacation}', [VacationsController::class, 'update'])->middleware('role:rh')->name('admin.vacations.update');
    Route::delete('/vacations/{vacation}', [VacationsController::class, 'destroy'])->middleware('role:rh')->name('admin.vacations.destroy');
    Route::get('request/reports', [RequestController::class, 'reportRequest'])->middleware('role:rh')->name('request.reportRequest');


    Route::get('/request', [RequestController::class, 'index'])->name('request.index');
    Route::get('request/authorize-manager', [RequestController::class, 'authorizeRequestManager'])->name('request.authorizeManager');
    Route::get('request/show-all', [RequestController::class, 'show'])->name('request.showAll');
    Route::put('request-auth/{request}', [RequestController::class, 'authorizeUpdate'])->name('request.authorize.update');
    Route::get('request/{request}/auth-edit', [RequestController::class, 'authorizeEdit'])->name('request.authorize.edit');
    Route::get('request/{request}/rh-edit', [RequestController::class, 'authorizeRHEdit'])->name('request.rh.edit');
    Route::delete('request/{request}/notification', [RequestController::class, 'deleteNotification'])->name('request.delete.notification');
    Route::delete('request/{request}/all', [RequestController::class, 'deleteAll'])->name('request.delete.all');
    /*  Route::post('request/filter-request', [RequestController::class, 'filterRequest'])->name('request.filter.request'); */
    Route::post('request/filter', [RequestController::class, 'filter'])->name('request.filter');
    Route::post('request/filter-date', [RequestController::class, 'filterDate'])->name('request.filter.data');
    Route::get('request/reports/all', [RequestController::class, 'exportAll'])->name('request.report.all');
    Route::get('request/reports/filter', [RequestController::class, 'filterExport'])->name('request.report.filter');
    Route::post('request/export/', [RequestController::class, 'export'])->name('request.export');
    Route::post('request/export/filter', [RequestController::class, 'exportFilter'])->name('request.export.filter');
    /*     Route::post('request/export/data', [RequestController::class, 'exportDataFilter'])->name('request.export.data'); */
    Route::post('request/dataFilter', [RequestController::class, 'getDataFilter'])->name('request.export.filterdata');;
    Route::resource('request', RequestController::class);


    Route::get('dropdownlist/getPosition/{id}', [EmployeeController::class, 'getPositions']);
    Route::get('request/getData/{lista}', [EmployeeController::class, 'getData']);
    Route::get('manager/getPosition/{id}', [ManagerController::class, 'getPosition']);
    Route::get('manager/getEmployee/{id}', [ManagerController::class, 'getEmployee']);
    Route::get('user/getPosition/{id}', [UserController::class, 'getPosition']);
    Route::get('user/getManager/{id}', [UserController::class, 'getManager']);
    Route::get('home/getCommunique/{id}', [HomeController::class, 'getCommunique']);


    Route::get('/events', [EventsController::class, 'index'])->middleware('role:rh')->name('admin.events.index');
    Route::get('/events/create', [EventsController::class, 'create'])->middleware('role:rh')->name('admin.events.create');
    Route::get('/events/showEvents', [EventsController::class, 'showEvents'])->middleware('role:rh')->name('admin.events.showEvents');
    Route::post('/events', [EventsController::class, 'store'])->middleware('role:rh')->name('admin.events.store');
    Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->middleware('role:rh')->name('admin.events.edit');
    Route::put('/events/{event}', [EventsController::class, 'update'])->middleware('role:rh')->name('admin.events.update');
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])->middleware('role:rh')->name('admin.events.destroy');

    Route::post('fullcalenderAjax', [RequestController::class, 'ajax']);


    Route::get('/com/department', [CommuniqueController::class, 'department'])->name('communiques.department');
    Route::get('communiques/show', [CommuniqueController::class, 'show'])->name('admin.communique.show');


    Route::put('communiques/{communique}', [CommuniqueController::class, 'update'])->name('admin.communique.update');

    Route::get('profile/', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile/filter', [ProfileController::class, 'change'])->name('profile.change');

    Route::get('/loginEmail', [LoginController::class, 'loginWithLink'])->name('loginWithLink');
    /*     Route::get('events', [EventsController::class, 'index'])->name('admin.events.index');
    Route::post('eventsAjax', [EventsController::class, 'ajax']); */
});
