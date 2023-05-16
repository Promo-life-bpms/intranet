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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\NoWorkingDaysController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\Soporte\SoporteController;
use App\Http\Controllers\SoporteSolucionController;
use App\Http\Livewire\SoporteSolucionComponent;
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

Route::get('/loginEmail', [LoginController::class, 'loginWithLink'])->name('loginWithLink');


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Administrador
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('departments', DepartmentsController::class);
        Route::resource('position', PositionController::class);
        Route::resource('manager', ManagerController::class);
        Route::resource('organization', OrganizationController::class);
        Route::resource('users', UserController::class);

        Route::get('exportUsuarios/', [UserController::class, 'exportUsuarios'])->name('user.exportUsuarios');
        Route::get('sendAccess/', [UserController::class, 'sendAccess'])->name('user.sendAccess');
        Route::get('sendAccess/{user}', [UserController::class, 'sendAccessPerUser'])->name('user.sendAccessUnit');
    });

    // Inicio
    Route::get('/home', HomeController::class)->name('home');

    // Acerca de
    Route::get('/about/promolife', [AboutController::class, 'promolife'])->name('about_promolife');
    Route::get('/about/bhtrade', [AboutController::class, 'bh'])->name('about_trade');

    // Organigrama
    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::get('/company_data', [CompanyController::class, 'index_data'])->name('company_data');
    Route::get('company/getEmployees', [CompanyController::class, 'getAllEmployees']);
    Route::get('company/getPosition/{id}', [CompanyController::class, 'getPositions']);
    Route::get('company/getEmployeesByOrganization/{organization}', [CompanyController::class, 'getEmployeesByOrganization']);
    Route::get('company/getEmployeesByDepartment/{department}', [CompanyController::class, 'getEmployeesByDepartment']);

    // Permisos y Vacaciones

    // Aniversarios y cumpleaños0
    Route::get('/aniversary/aniversary', [AniversaryController::class, 'aniversary'])->name('aniversary');
    Route::get('/aniversary/birthday', [AniversaryController::class, 'birthday'])->name('birthday');

    // Empleado del mes
    Route::get('/month', MonthController::class)->name('month');

    // Directorio de Proveedores
    Route::resource('/directories', DirectoryController::class);

    // Manuales
    Route::resource('manuals', ManualController::class);



    Route::resource('communiques', CommuniqueController::class)->except(["show"]);

    /*     Route::resource('days-no-working', NoWorkingDaysController::class);  */
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
    Route::get('/vacations/{vacation}/edit', [VacationsController::class, 'edit'])->middleware('role:rh')->name('admin.vacations.edit');
    Route::put('/vacations/{vacation}', [VacationsController::class, 'update'])->middleware('role:rh')->name('admin.vacations.update');
    Route::delete('/vacations/{vacation}', [VacationsController::class, 'destroy'])->middleware('role:rh')->name('admin.vacations.destroy');
    Route::get('vacations/export/', [VacationsController::class, 'export'])->name('admin.vacations.export');


    Route::get('/request', [RequestController::class, 'index'])->name('request.index');
    Route::get('/request/create', [RequestController::class, 'create'])->name('request.create');
    Route::post('/request', [RequestController::class, 'store'])->name('request.store');
    Route::get('/request/{request}/edit', [RequestController::class, 'edit'])->name('request.edit');
    Route::put('/request/{request}', [RequestController::class, 'update'])->name('request.update');
    Route::delete('/request/{request}', [RequestController::class, 'destroy'])->name('request.destroy');

    Route::get('request/authorize-manager', [RequestController::class, 'authorizeRequestManager'])->name('request.authorizeManager');
    Route::get('request/authorize-rh', [RequestController::class, 'authorizeRequestRH'])->name('request.authorizeRH');
    Route::post('request/filter', [RequestController::class, 'filter'])->name('request.filter');
    Route::post('request/filter-date', [RequestController::class, 'filterDate'])->name('request.filter.data');
    Route::get('request/reports/all', [RequestController::class, 'exportAll'])->name('request.report.all');
    Route::post('request/export/', [RequestController::class, 'export'])->name('request.export');
    Route::post('request/export/filter', [RequestController::class, 'exportFilter'])->name('request.export.filter');
    Route::post('request/dataFilter', [RequestController::class, 'getDataFilter'])->name('request.export.filterdata');;
    Route::get('request/reports', [RequestController::class, 'reportRequest'])->middleware('role:rh')->name('request.reportRequest');
    Route::post('fullcalenderAjax', [RequestController::class, 'ajax']);

    Route::get('dropdownlist/getPosition/{id}', [EmployeeController::class, 'getPositions']);
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

    Route::get('/com/department', [CommuniqueController::class, 'department'])->name('communiques.department');
    Route::get('communiques/show', [CommuniqueController::class, 'show'])->name('admin.communique.show');

    Route::put('communiques/{communique}', [CommuniqueController::class, 'update'])->name('admin.communique.update');

    Route::get('profile/', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile/filter', [ProfileController::class, 'change'])->name('profile.change');
    Route::get('profile/{prof}/view', [ProfileController::class, 'show'])->name('profile.view');
    Route::put('profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
    Route::get('/manual/create', [ManualController::class, 'create'])->name('manual.create');
    Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');
    Route::get('/manual/{manual}/edit', [ManualController::class, 'edit'])->name('manual.edit');
    Route::put('/manual/{manual}', [ManualController::class, 'update'])->name('manual.update');
    Route::delete('/manual/{manual}', [ManualController::class, 'delete'])->name('manual.delete');

    Route::prefix('social')->group(function () {
        // Publicaciones
        Route::patch('/publication/store', [PublicationsController::class, 'store'])->name('publications.store');
        Route::post('/publication/items', [PublicationsController::class, 'uploadItems'])->name('publication.uploadItems');
        Route::post('/publication/deleteItem', [PublicationsController::class, 'deleteItem'])->name('publication.deleteItem');

        //Ruta de likes
        Route::post('/publication/{publications}', [LikeController::class, 'update'])->name('like.update');


        //Ruta de comentarios
        Route::post('/comment/store', [CommentController::class, 'store'])->name('comment');
    });
    //Rutas de Chat
    Route::prefix('chat')->group(function () {

        //Usuarios
        Route::get('/obtenerUsuarios', [MessageController::class, 'obtenerUsuarios'])->name('usuariosChat');

        //Chat
        Route::get('/fetchMessages/{userId}', [MessageController::class, 'fetchMessages'])->name('fetch.message');
        Route::post('/sendMessage', [MessageController::class, 'sendMessage'])->name('send.message');
        Route::get('/markNotification/{notification}', [MessageController::class, 'markAsRead'])->name('message.markAsRead');
        Route::get('eliminarNotificacion/{notification}', [MessageController::class, 'markAsRead'])->name('eliminar.notificacion');
    });

    Route::resource('providers', ProviderController::class);
    Route::get('/providers/import/create', [ProviderController::class, 'create_import'])->name('providers.createImport');
    Route::post('/providers/import/store', [ProviderController::class, 'store_import'])->name('providers.storeImport');
    //soporte
    Route::prefix('soporte')->group(function () {
        Route::get('/', [SoporteController::class, 'index'])->name('soporte');
        Route::get('/create', [SoporteController::class, 'create'])->name('soporte.create');
        Route::get('/store', [SoporteController::class, 'store'])->name('soporte.store');
        Route::get('/solucion', [SoporteController::class, 'solucion'])->middleware('role:systems')->name('solucion');
        Route::get('/admin', [SoporteController::class, 'admin'])->middleware('role:systems')->name('admin');
        // Route::post('ckeditor/upload', [SoporteController::class,'upload'])->name('ckeditor.upload');
        // Route::post('ckeditor/upload', [SoporteController::class,'store'])->name('ckeditor.store');

        Route::post('editor/image_upload',[SoporteController::class,'upload'])->name('upload');
    });
});

Route::get('vacations/updateExpiration/', [VacationsController::class, 'updateExpiration'])->name('admin.vacations.updateExpiration');
Route::get('vacations/sendRemembers/', [VacationsController::class, 'sendRemembers'])->name('admin.vacations.sendRemembers');
Route::get('request/alertRequesPendients/', [RequestController::class, 'alertPendient']);
