<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/**
 * Authentication Route
 */

Route::get('account/login', ['as' => 'login', 'uses' => 'HomeController@getLogin']);
Route::post('account/login', ['as' => 'handle.login', 'uses' => 'HomeController@postLogin']);
Route::get('account/logout', ['as' => 'logout', 'uses' => 'HomeController@getLogout']);
Route::get('user/login', ['as' => 'multiple.login', 'uses' => 'HomeController@multipleLogin']);
// Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'HomeController@getMyDashboard']);

/**
 * Authentication Group
 */
Route::group(['middleware' => 'auth'], function () {

    Route::get('/user/org-chart', ['as' => 'organization-chart', 'uses' => 'HomeController@getOrganisationChart']);
    /* Exporting Data into CSV File*/

    Route::get('export-rating/{fileType?}/{peopleId?}/{projectId?}/{managerId}/{fromDate?}/{toDate?}', ['as' => 'admin.export_data.export_rating_report', 'uses' => 'ExportDataController@exportRatingReport']);
    Route::get('export-rating-navigators/{fileType?}', ['as' => 'admin.export_data.navigator', 'uses' => 'ExportDataController@exportNavigatorsList']);
    Route::get('export-rating-summary/{fileType?}/{fromyear?}/{toyear?}/{peopleId?}', ['as' => 'admin.export_report_summary', 'uses' => 'ExportDataController@exportReportSummaryList']);
    Route::get('export-navigator-rating/{fileType?}/{projectId?}/{fromyear?}/{toyear?}/{peopleId?}/{practicesId}', ['as' => 'admin.export_report_rating_summary', 'uses' => 'ExportDataController@exportRatingReportList']);

    Route::get('getRoleDashboard', ['as' => 'dashboard', 'uses' => 'HomeController@getDashboard']);
    Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@getDashboard']);
    Route::post('/', ['as' => 'dashboard', 'uses' => 'HomeController@getDashboard']);
    Route::get('ratingDetailsFilter', ['as' => 'ratingFilter', 'uses' => 'HomeController@filterRatingDetails']);

    Route::post('notification/mail', ['as' => 'handle.mail', 'uses' => 'HomeController@sendMailFunction']);
    Route::get('ajax-resourcecategory/{project_id}', ['as' => 'feedback.ajax', 'uses' => 'HomeController@getResourceName']);
    Route::post('show', ['as' => 'feedback.save', 'uses' => 'Pagescontroller@saveData']);
    Route::post('feedback-update/{recordId}', ['as' => 'feedback.update', 'uses' => 'Pagescontroller@updateData']);
    Route::post('report-dashboard', array('as' => 'reportdata', 'uses' => 'HomeController@getReportDetailsDashboard'));
    Route::post('pm-report-dashboard', array('as' => 'report', 'uses' => 'ReportController@getResourceBetweenDates'));
    Route::get('report-dashboard', ['as' => 'adminReport', 'uses' => 'AdminController@getadminDashboard']);

    /**
     * Route for retrieving the ReportingManager Based on Practices
     */
    Route::post('resource-practices-id', ['as' => 'resourcePracticesId', 'uses' => 'ReportController@getReportManagerListForUserRegisterPractices']);
    Route::post('delivery-practices-id', ['as' => 'deliveryPracticesId', 'uses' => 'ReportController@getDeliveryListForUserRegisterPractices']);
    Route::post('resources-practices', ['as' => 'practicesResourcesId', 'uses' => 'ReportController@getResourcesListForPractices']);
    Route::post('resources-project', ['as' => 'resourcesProjectId', 'uses' => 'ReportController@getResourcesOnProject']);
    Route::post('category-sort-id', ['as' => 'categorySortId', 'uses' => 'ReportController@getCategorySortValue']);

    Route::post('update-resource-status', ['as' => 'updateResourceStatus', 'uses' => 'ReportController@updateResourceStatus']);

    /**
     *  Route For User Profile Changes
     */
    Route::get('profile', ['as' => 'profile-display', 'uses' => 'HomeController@profileDisplay']);
    Route::get('profile-user', ['as' => 'user-profile', 'uses' => 'HomeController@profileUser']);
    Route::get('password-change', ['as' => 'account-settings', 'uses' => 'HomeController@accountChanges']);
    Route::post('user-update', ['as' => 'user-profile', 'uses' => 'HomeController@userProfileUpdate']);
    Route::post('profile-details', ['as' => 'profile-update', 'uses' => 'HomeController@profileDetailsStore']);


    Route::get('report-summary-display', array('as' => 'reportsummary-display', 'uses' => 'ReportController@getReportDashboard'));
    Route::post('report-summary', array('as' => 'reportsummary', 'uses' => 'ReportController@getReportSummary'));
    Route::get('people-report-list', ['as' => 'peopleDashboard', 'uses' => 'ReportController@getPeopleDashboard']);


    /**
     *  Feedback form edit for Practice Lead
     */
    Route::get('edit-feedback-form/{recordId}/{start_date}/{end_date}', ['as' => 'edit.feedback.form', 'uses' => 'ReportController@editFeedbackForm']);

    /*
     * Resource Rating Routes
     */
    Route::get('resource-rating/{peopleId}/{projectId}/{managerId}', ['as' => 'resource.ratingSheet', 'uses' => 'HomeController@getResourceRating']);
    Route::get('resource-rating/{peopleId}/{projectId}/{managerId}/{roleId}', ['as' => 'resource.ratingsheet', 'uses' => 'HomeController@getResourceRatingByRoleId']);
    Route::get('resource-rating/{peopleId}/{projectId}/{managerId}/{fromDate}/{toDate}/{roleId?}', ['as' => 'resource.rating', 'uses' => 'HomeController@getResourceRatingByQuarter']);

    /*
     * Resource Feedback Rating Routes
     */
    Route::post('resource-details', ['as' => 'resource-ratingsheet', 'uses' => 'ReportController@resourceFeedbackDetails']);
    Route::get('rating/{peopleId}/{projectId}/{fromDate}/{toDate}', ['as' => 'report.rating', 'uses' => 'ReportController@ratingDetailsForPeople']);
    Route::get('feedback-form/{desingation}', array('as' => 'feedback-form-desingation', 'uses' => 'HomeController@feedback'));
    Route::get('ajax-resourcelistcategory/{people_id}/{startdate}/{enddate}', ['as' => 'consolidateed-rating', 'uses' => 'HomeController@getResourceDetailsOnPracticeLead']);
    Route::post('practiceLeadResourcerate', ['as' => 'feedback.resource.save', 'uses' => 'Pagescontroller@saveResourceData']);

    /*
     * Project Routes
     */
    Route::Resource('project', 'ProjectController', [
        'names' => [
            'index' => 'project',
            'store' => 'project.new',
            'update' => 'project.update',
            'destroy' => 'project.destroy',
        ],
    ]);
    Route::post('project/grid', ['as' => 'project.grid', 'uses' => 'ProjectController@grid']);

    /**
     * Register Routes
     */
    Route::get('account/register', ['as' => 'register', 'uses' => 'HomeController@getRegister']);
    Route::post('account/register', ['as' => 'handle.register', 'uses' => 'HomeController@postRegister']);
    Route::Resource('register', 'RegisterController', [
        'names' => [
            'index' => 'register',
            'store' => 'register.new',
            'update' => 'register.update',
            'destroy' => 'register.destroy',
        ],
    ]);
    Route::post('register/grid', ['as' => 'register.grid', 'uses' => 'RegisterController@grid']);
    /* user edit panel routes  */    
    Route::get('user-details/{emp_id}', ['as'=>'user.edit','uses' => 'UserController@edit']);
    Route::post('user-update/{emp_id}', ['as'=>'user.update','uses' => 'UserController@update']);


    Route::get('feedback-form', ['as' => 'feedback.form', 'uses' => 'HomeController@getProjectManagerDashboard']);

    /**
     * Practice Lead Route Group
     */
    Route::group(['middleware' => 'roles:' . Config::get('roles.practice-lead')], function () {

        Route::post('projectlead-report', ['as' => 'resource.rating.by.date', 'uses' => 'HomeController@getResourceRatingOnDate']);
        Route::get('practicelead-feedback-form', ['as' => 'plfeedback.form', 'uses' => 'HomeController@getResourceData']);

    });

    /**
     * Project Manager Route Group
     *
     */
    Route::get('reportForProjectManager', ['as' => 'report', 'uses' => 'ReportController@getResourceBetweenDates']);

    Route::group(['middleware' => 'roles:' . Config::get('roles.project-manager')], function () {

        Route::get('feedback-form/{desingation}/{feedbackId}', array('as' => 'edit-feedback-form-desingation', 'uses' => 'HomeController@editFeedback'));
        Route::get('dashboard/projectmanager', ['as' => 'manager.dashboard', 'uses' => 'HomeController@getProjectManagerDashboard']);

        /**
         * Feedback Insertion Part
         */

        Route::get('home', ['as' => 'feedback.home', 'uses' => 'HomeController@show']);
        Route::group(['prefix' => 'projectmanager-report', 'middleware' => 'roles:3'], function () {

            Route::get('/', array('as' => 'report', 'uses' => 'ReportController@getProjectManagerDashboardView'));


            /**
             * Navigators Assign Route
             */
            Route::Resource('projectmanager-navigators', 'ProjectManagerNavigatorsController', [
                'names' => [
                    'index' => 'projectManagerNavigators',
                    'store' => 'projectManagerNavigators.new',
                    'update' => 'projectManagerNavigators.update',
                    'destroy' => 'projectManagerNavigators.destroy',
                ],
            ]);
            Route::post('projectManagerNavigators/grid', function () {
                GridEncoder::encodeRequestedData(new \App\Repositories\ProjectManagerNavigatorsRepository(), Input::all());
            });

        });
    });

    Route::get('getReportDashboard', ['as' => 'reportList', 'uses' => 'HomeController@reportDashBoard']);
    /**
     * Admin Route
     */

    Route::group(['middleware' => ['roles:' . Config::get('roles.admin')]], function () {

        Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles:' . Config::get('roles.admin')]], function () {


            Route::get('role/assign-roles-to-user', ['as' => 'admin.role.index_assign_roles_to_user', 'uses' => 'RoleController@indexAssignRolesToUser']);
            Route::post('role/assign-roles-to-user', ['as' => 'admin.role.store_assign_roles_to_user', 'uses' => 'RoleController@storeAssignRolesToUser']);
            Route::resource('role', 'RoleController', [
                'names' => [
                    'index' => 'role',
                    'store' => 'role.store',
                    'update' => 'role.update',
                    'destroy' => 'role.destroy',
                ],
            ]);
            Route::get('role/{id}/destroy', ['as' => 'admin.role.destroy', 'uses' => 'RoleController@destroy']);
            Route::post('role/grid', function () {
                GridEncoder::encodeRequestedData(app('App\Repositories\RoleRepository'), Input::all());
            });
            /**
             * Project Metrics - Resource Route
             */

            Route::group(['prefix' => 'revisions'], function () {
                Route::get('show-revisions/{revision_id?}', ['as' => 'admin.revisions.show', 'uses' => 'RevisionController@showRevisions']);
            });
        });

        /**
         * Getting ajax feedback form
         */
    });

    /**
     * Navigators - Metrics Routes
     */
    Route::Resource('metrics', 'MetricsController', [
        'names' => [
            'index' => 'metrics',
            'store' => 'metrics.new',
            'update' => 'metrics.update',
            'destroy' => 'metrics.destroy',
        ],
    ]);
    Route::post('metrics/grid', function () {
        GridEncoder::encodeRequestedData(new \App\Repositories\MetricCategoryRepository(), Input::all());
    });

    /*
    * Navigators - Metrics Categories Routes
    */
    Route::Resource('metric-categories', 'CategoriesController', [
        'names' => [
            'index' => 'metric-categories',
            'store' => 'metric-categories.new',
            'update' => 'metric-categories.update',
            'destroy' => 'metric-categories.destroy',
        ],
    ]);

    Route::post('metrics-categories-grid', function () {
        $visibleColumns = array('id', 'name', 'description', 'sort', 'color');
        GridEncoder::encodeRequestedData(new \App\Repositories\MasterRepository(new \App\Models\KraCategory(), $visibleColumns), Input::all());
    });

});

Route::group(['middleware' => ['auth', 'roles:5']], function () {

    /**
     * Project Navigators - Resource Route
     */
    Route::Resource('navigator', 'NavigatorsController', [
        'names' => [
            'index' => 'navigator',
            'store' => 'navigator.new',
            'update' => 'navigator.update',
            'destroy' => 'navigator.destroy',
        ],
    ]);
    Route::post('navigator-grid', ['as' => 'navigator-grid.grid', 'uses' => 'NavigatorsController@grid']);
    // Route::post('navigator/grid', function () {
    //     GridEncoder::encodeRequestedData(new \App\Repositories\ProjectManagerRepository(), Input::all());
    // });

    /**
     * Navigatores - Designation Routes
     */
    Route::Resource('designation', 'NavigatorDesignationController', [
        'names' => [
            'index' => 'designation',
            'store' => 'designation.new',
            'update' => 'designation.update',
            'destroy' => 'designation.destroy',
        ],
    ]);
    Route::post('designation/grid', ['as' => 'designationList', 'uses' => 'NavigatorDesignationController@getNavigatorData']);


    /**
     * Navigatores - Practices Routes
     */
    Route::Resource('practices', 'PracticesController', [
        'names' => [
            'index' => 'practices',
            'store' => 'practices.new',
            'update' => 'practices.update',
            'destroy' => 'practices.destroy',
        ],
    ]);
    Route::post('practices/grid', ['as' => 'practicesList', 'uses' => 'PracticesController@getPracticesData']);

    /**
     * Navigators - Metrics Assign Routes
     */
    Route::Resource('assign-metrics', 'AssignMetricsController', [
        'names' => [
            'index' => 'assign-metrics',
            'store' => 'assign-metrics.new',
            'update' => 'assign-metrics.update',
            'destroy' => 'assign-metrics.destroy',
        ],
    ]);
    Route::post('assigned-metrics-grid', ['as' => 'assigned-metrics-grid.grid', 'uses' => 'AssignMetricsController@grid']);

    /**
     * Revision Routes
     */
    Route::group(['prefix' => 'revisions'], function () {
        Route::get('show-revisions/{revision_id?}', ['as' => 'admin.revisions.show', 'uses' => 'RevisionController@showRevisions']);
    });
});

Route::get('passwordemail', ['as' => 'passwordemail', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post('password/email', ['as' => 'password.email', 'middleware' => 'throttleReset:3,1800', 'uses' => 'Auth\PasswordController@postEmail']);

Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\PasswordController@postReset']);