<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/test', 'TestController@index');

Route::get('/brainbox', 'TestController@brainbox');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.' ], function () {
    Route::namespace('Admin')->group(function () {
        Route::get('dashboard', 'Dashboard\DashboardController@index')->name('dashboard.index');
        Route::get('/settings', 'Settings\SetupController@index')->name('settings.index');
        Route::put('/settings/{setup}', 'Settings\SetupController@update')->name('settings.update');

        Route::resource('users', 'Users\UserController');
        Route::get('users/{user}/enable','Users\UserController@enable')->name('users.enable');
        Route::get('users/{user}/disable','Users\UserController@disable')->name('users.disable');
        Route::get('users/{user}/delete','Users\UserController@delete')->name('users.delete');
        Route::get('users/{user}/send-credentials','Users\UserController@sendCredentials')->name('users.credentials');
        Route::get('users/{user}/history','Users\UserController@history')->name('users.history');
        Route::get('users/{user}/documents','Users\UserController@documents')->name('users.documents');
        Route::get('users-export','Users\UserExcelController@export')->name('users.export');
        Route::get('users-import','Users\UserExcelController@import')->name('users.import');

        Route::get('imbox', 'Imbox\ImboxController')->name('imbox.index');
        Route::get('imboxv2', 'Imbox\ImboxControllerv2')->name('imboxv2.index');

        Route::resource('customers', 'Customers\CustomerController');
        Route::post('customers/import', 'Customers\CustomerController@import')->name('customers.import');
        Route::get('exportar-customer', 'Customers\CustomerController@export')->name('customers.export');

        Route::get('tags', 'Tags\TagController')->name('tags.index');
        Route::get('planned', 'Planned\PlannedController')->name('planned.index');
        Route::resource('tracks', 'Tracks\TrackController')->only('index','show');
        Route::get('reports', 'Reports\ReportController')->name('reports.index');

        Route::get('histories', 'Histories\HistoryController')->name('histories.index');
        Route::get('documents', 'Histories\DocumentController')->name('documents.index');
    });
});

Route::get('/account/details','General\AccountController@index')->name('account.index');
Route::patch('/account/details','General\AccountController@update')->name('account.update');

Route::group(['prefix' => 'collaborator', 'middleware' => ['auth'], 'as' => 'counter.' ], function () {
    Route::namespace('Counter')->group(function () {
        Route::get('my-planned', 'Planned\PlannedController')->name('planned.index');
        Route::get('my-planned/import','Planned\TemplateExcelController')->name('planned.import');
        Route::get('my-tracks','Tracks\TrackController')->name('tracks.index');
        Route::get('my-tracksv2','Tracks\Trackv2Controller')->name('tracksv2.index');
        Route::get('my-imbox','Imbox\ImboxController')->name('imbox.index');
        Route::get('my-imboxv2','Imbox\Imboxv2Controller')->name('imboxv2.index');
        Route::get('my-reports','Reports\ReportController')->name('reports.index');
    });
});

Route::group(['prefix' => 'api', 'middleware' => ['auth']], function () {
    Route::get('tags-test','TestController@tags');//new

    Route::namespace('Front')->group(function () {
        Route::get('admin/dashboard','Dashboard\DashboardController');

        Route::get('tracks','Tracks\TrackController@index');//new
        Route::get('track/users/{user_id}','Tracks\TrackShowController');

        Route::get('counter/imbox','Imbox\ImboxController');
        Route::resource('admin/tags','Tags\TagController')->except(['create','show']);

        Route::get('customers/{customer_id}/show','Customers\ShowCustomerController');
        Route::get('customers/{customer_id}/assigned-users','Customers\AssignedUsersController');

        Route::post("imbox/approve-reject", 'Imbox\ApproveRejectActivityController');


        Route::namespace('Activities')->group(function() {
            Route::post("user/activities", 'Crud\StoreActivityController');
            Route::get("user/activities/{id}/edit", 'Crud\EditActivityController');
            Route::put("user/activities/{id}", 'Crud\UpdateActivityController');
            Route::delete('user/activities/{id}/destroy','Crud\DestroyActivityController');
            Route::get("user/activities/{id}", 'Crud\ShowActivityController');

            Route::put("user/activities/{id}/approve", 'Validate\ApproveController');
            Route::get('users/{user_id}/activities/mass-approve','Validate\MassApproveController');
            Route::put("user/activities/{id}/reserve", 'Validate\ReverseController');

            Route::get('users/{user_id}/planned/export-list','Planned\Export\ExportListController');
            Route::get('users/{user_id}/planned/export-day','Planned\Export\ExportDayController');

            Route::get('users/{user_id}/planned/planned-status','Planned\Destroy\GetPlannedStatusController');
            Route::post('activities/planned/mass-planned-status','Planned\Destroy\MassDeleteController');

            Route::post('users/{user_id}/duplicate-work-plan','Planned\Duplicate\WorkPlanController');
            Route::post('users/{user_id}/import-work-plan','Planned\Import\WorkPlanController');

            Route::post("user/new-activities", 'ActivityNewController@store');

            Route::put("users/activities/{id}/finished", 'Planned\Complete\ActivityFinishController');
            Route::put("users/activities/{id}/reset-partial", 'Planned\Complete\ActivityResetController');

            Route::post("users/activities/{id}/sub-activity", 'SubActivity\StoreSubActivityController');
            Route::delete('users/activities/sub-activity/{id}/destroy','SubActivity\DestroySubActivityController');
        });

        Route::namespace('Reports')->group(function() {
            Route::get("reports/users/planned-vs-real", 'Users\PlannedvsRealController');
            Route::get("reports/users/time-worked-by-customer", 'Users\TimeWorkedByCustomerController');
            Route::get("reports/users/time-worked-by-day", 'Users\TimeWorkedByDayController');

            Route::get("reports/customers/time-worked-by-month", 'Customers\TimeWorkedByMonthController');
            Route::get("reports/customers/list-users-working", 'Customers\ListUsersWorkingController');
            Route::get("reports/customers/monthly-working-time-history", 'Customers\HistoryLastSixMonthsController');
            Route::get("reports/customers/activities-tags", 'Customers\ActivityTagController');

            Route::get("reports/activities/list-activities", 'Activities\ListActivitiesController');
        });

        Route::get('counter/{counter_id}/list-customers','Counters\Customers\ListCustomerController');
        Route::get('users/{user_id}/activities-planned','Activities\ActivityPlannedController');

    });
});

Auth::routes([
    "register" => false,
    'verify' => false
]);


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
