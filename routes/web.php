<?php

use Illuminate\Support\Facades\Route;

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

//JUST ADD '->defaults("group", "Settings")' IF YOU WANT TO GROUP A NAV IN A DROPDOWN

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('patient/subjective', "SubjectiveController@index")->name('patient.subjective');
Route::get('patient/search', "SubjectiveController@search")->name('patient.search');
Route::get('patient/getQuestion', "SubjectiveController@getQuestion")->name('patient.getQuestion');
Route::get('patient/getPatientPackage', "SubjectiveController@getPatientPackage")->name('patient.getPatientPackage');
Route::post('patient/updateSubjective', "SubjectiveController@updateSubjective")->name('patient.updateSubjective');
// Route::post('company/import', "CompanyController@import")->name('company.import');

Route::get('/', function(){
   return redirect()->route('login');
});

Route::group([
        'middleware' => 'auth',
    ], function() {
        Route::get('/', "DashboardController@index")->name('dashboard');
        Route::get('getReport1', "DashboardController@getReport1")->name('getReport1');
        Route::get('getReport2', "DashboardController@getReport2")->name('getReport2');


        Route::get('/', 'DashboardController@index')
            ->defaults('sidebar', 1)
            ->defaults('icon', 'fas fa-list')
            ->defaults('name', 'Dashboard')
            ->defaults('roles', array('Admin'))
            ->name('dashboard')
            ->defaults('href', '/');

        Route::get('/profile', 'UserController@profile')
            ->defaults('sidebar', 1)
            ->defaults('icon', 'fas fa-user-doctor')
            ->defaults('name', 'Profile')
            ->defaults('roles', array('Admin', 'Doctor'))
            ->name('profile')
            ->defaults('href', '/profile');

        // USER ROUTES
        $cname = "user";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fas fa-users")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Super Admin", "Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::get("get2/", ucfirst($cname) . "Controller@get2")->name('get2');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("restore/", ucfirst($cname) . "Controller@restore")->name('restore');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
                Route::post("update2/", ucfirst($cname) . "Controller@update2")->name('update2');
                Route::post("removeType/", ucfirst($cname) . "Controller@removeType")->name('removeType');
                Route::post("updatePassword/", ucfirst($cname) . "Controller@updatePassword")->name('updatePassword');
            }
        );

        // PATIENT ROUTES
        $cname = "patient";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-duotone fas fa-users-medical")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Super Admin", "Admin", "Nurse", "Receptionist"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // DOCTOR ROUTES
        $cname = "doctor";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // NURSE ROUTES
        $cname = "nurse";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // COMPANY ROUTES
        $cname = "company";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("dashboard/", ucfirst($cname) . "Controller@dashboard")->name('dashboard');
                Route::post("import/", ucfirst($cname) . "Controller@import")->name('import');
            }
        );

        // PACKAGE ROUTES
        $cname = "package";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::get("getCompanies/", ucfirst($cname) . "Controller@getCompanies")->name('getCompanies');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // PATIENT PACKAGE ROUTES
        $cname = "patientPackage";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');

                Route::get("deleteFile/", ucfirst($cname) . "Controller@deleteFile")->name('deleteFile');

                Route::get("exportDocument/", ucfirst($cname) . "Controller@exportDocument")->name('exportDocument');
                Route::get("exportInvoice/", ucfirst($cname) . "Controller@exportInvoice")->name('exportInvoice');
            }
        );

        // PATIENT PACKAGE ROUTES
        $cname = "examList";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // // PACKAGE
        // $cname = "package";
        // Route::group([
        //         'as' => "$cname.",
        //         'prefix' => "$cname/"
        //     ], function () use($cname){
        //         Route::get("/", "QuestionController@index2")
        //             ->defaults("sidebar", 1)
        //             ->defaults("icon", "fas fa-box-circle-check")
        //             ->defaults("name", "Packages")
        //             ->defaults("roles", array("Super Admin", "Admin"))
        //             // ->defaults("group", "Settings")
        //             ->name($cname)
        //             ->defaults("href", "/$cname");
        //     }
        // );


        // TEMPLATE ROUTES
        $cname = "question";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fas fa-list-check")
                    ->defaults("name", "Template Manager")
                    ->defaults("roles", array("Super Admin", "Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');

                Route::post("reorderRows/", ucfirst($cname) . "Controller@reorderRows")->name('reorderRows');
            }
        );

        // TEMPLATE ROUTES
        $cname = "exam";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/ape", ucfirst($cname) . "Controller@ape")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fas fa-files-medical")
                    ->defaults("name", "APE")
                    ->defaults("roles", array("Admin", "Doctor", "Nurse", "Receptionist", "Imaging", "Laboratory"))
                    ->defaults("group", "Exams")
                    ->name($cname . "ape")
                    ->defaults("href", "/$cname/ape");

                Route::get("/pee", ucfirst($cname) . "Controller@pee")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fas fa-files-medical")
                    ->defaults("name", "PPE")
                    ->defaults("roles", array("Admin", "Doctor", "Nurse", "Receptionist", "Imaging", "Laboratory"))
                    ->defaults("group", "Exams")
                    ->name($cname . 'pee')
                    ->defaults("href", "/$cname/pee");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // SETTING ROUTES
        $cname = "setting";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::get("checkClinicSettings/", ucfirst($cname) . "Controller@checkClinicSettings")->name('checkClinicSettings');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // THEME ROUTES
        $cname = "theme";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // THEME ROUTES
        $cname = "report";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("exam/", ucfirst($cname) . "Controller@exam")->name('exam');
                Route::get("packagesSold/", ucfirst($cname) . "Controller@packagesSold")->name('packagesSold');
                Route::get("sales/", ucfirst($cname) . "Controller@sales")->name('sales');
            }
        );

        // DATATABLES
        $cname = "datatable";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("user", ucfirst($cname) . "Controller@user")->name('user');
                Route::get("patient", ucfirst($cname) . "Controller@patient")->name('patient');
                Route::get("examinees", ucfirst($cname) . "Controller@examinees")->name('examinees');
                Route::get("patientPackage", ucfirst($cname) . "Controller@patientPackage")->name('patientPackage');
            }
        );
    }
);

require __DIR__.'/auth.php';