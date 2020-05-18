<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});


Auth::routes();
// Admin

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth'], 'namespace' => 'Admin'], function () {

    Route::get('/push','PushController@push')->name('push');

    //Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    //->middleware('ConstructionContract.Select');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');


    Route::get('/construction_contracts-select', '\App\Http\Controllers\Auth\ConstructionContractSelectController@select')->name('construction_contracts-select.select');
    Route::post('/construction_contracts-select','\App\Http\Controllers\Auth\ConstructionContractSelectController@storeSelect')->name('construction_contracts-select.select');

  //  Route::group(['namespace' => 'Admin'], function () {

    
   
    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/parse-csv-import', 'UsersController@parseCsvImport')->name('users.parseCsvImport');
    Route::post('users/process-csv-import', 'UsersController@processCsvImport')->name('users.processCsvImport');
    Route::resource('users', 'UsersController');

    // Jobtitles
    Route::delete('jobtitles/destroy', 'JobtitleController@massDestroy')->name('jobtitles.massDestroy');
    Route::post('jobtitles/parse-csv-import', 'JobtitleController@parseCsvImport')->name('jobtitles.parseCsvImport');
    Route::post('jobtitles/process-csv-import', 'JobtitleController@processCsvImport')->name('jobtitles.processCsvImport');
    Route::resource('jobtitles', 'JobtitleController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Teams
    Route::delete('teams/destroy', 'TeamController@massDestroy')->name('teams.massDestroy');
    Route::post('teams/parse-csv-import', 'TeamController@parseCsvImport')->name('teams.parseCsvImport');
    Route::post('teams/process-csv-import', 'TeamController@processCsvImport')->name('teams.processCsvImport');
    Route::resource('teams', 'TeamController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Departments
    Route::delete('departments/destroy', 'DepartmentController@massDestroy')->name('departments.massDestroy');
    Route::post('departments/parse-csv-import', 'DepartmentController@parseCsvImport')->name('departments.parseCsvImport');
    Route::post('departments/process-csv-import', 'DepartmentController@processCsvImport')->name('departments.processCsvImport');
    Route::resource('departments', 'DepartmentController');

    // Activity Calendar Reports
    Route::resource('activity-calendar-reports', 'ActivityCalendarReportController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Rfas
    Route::delete('rfas/destroy', 'RfaController@massDestroy')->name('rfas.massDestroy');
    Route::post('rfas/media', 'RfaController@storeMedia')->name('rfas.storeMedia');
    Route::post('rfas/parse-csv-import', 'RfaController@parseCsvImport')->name('rfas.parseCsvImport');
    Route::post('rfas/process-csv-import', 'RfaController@processCsvImport')->name('rfas.processCsvImport');
    Route::post('rfas/fetch','RfaController@fetch')->name('rfas.fetch');
    Route::get('rfas/{rfa}/revision','RfaController@revision')->name('rfas.revision');
    Route::post('rfas/storeRevision','RfaController@storeRevision')->name('rfas.storeRevision');
    Route::resource('rfas', 'RfaController');

    // Rfatypes
    Route::delete('rfatypes/destroy', 'RfatypesController@massDestroy')->name('rfatypes.massDestroy');
    Route::resource('rfatypes', 'RfatypesController');

    // Rfa Comment Statuses
    Route::delete('rfa-comment-statuses/destroy', 'RfaCommentStatusController@massDestroy')->name('rfa-comment-statuses.massDestroy');
    Route::resource('rfa-comment-statuses', 'RfaCommentStatusController');

    // Rfa Document Statuses
    Route::delete('rfa-document-statuses/destroy', 'RfaDocumentStatusController@massDestroy')->name('rfa-document-statuses.massDestroy');
    Route::resource('rfa-document-statuses', 'RfaDocumentStatusController');

    // Task Statuses
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tags
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Tasks
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/parse-csv-import', 'TaskController@parseCsvImport')->name('tasks.parseCsvImport');
    Route::post('tasks/process-csv-import', 'TaskController@processCsvImport')->name('tasks.processCsvImport');
    Route::get('tasks/createReport','TaskController@createReport')->name('tasks.createReport');
    Route::post('tasks/createReportTask','TaskController@createReportTask')->name('tasks.createReportTask');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendars
    Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Rfa Calendars
    Route::resource('rfa-calendars', 'RfaCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // File Managers
    Route::delete('file-managers/destroy', 'FileManagerController@massDestroy')->name('file-managers.massDestroy');
    Route::post('file-managers/media', 'FileManagerController@storeMedia')->name('file-managers.storeMedia');
    Route::resource('file-managers', 'FileManagerController');

    // Construction Contracts
    Route::delete('construction-contracts/destroy', 'ConstructionContractController@massDestroy')->name('construction-contracts.massDestroy');
    Route::resource('construction-contracts', 'ConstructionContractController');

    // Wbs Level Threes
    Route::delete('wbs-level-threes/destroy', 'WbsLevelThreeController@massDestroy')->name('wbs-level-threes.massDestroy');
    Route::post('wbs-level-threes/parse-csv-import', 'WbsLevelThreeController@parseCsvImport')->name('wbs-level-threes.parseCsvImport');
    Route::post('wbs-level-threes/process-csv-import', 'WbsLevelThreeController@processCsvImport')->name('wbs-level-threes.processCsvImport');
    Route::resource('wbs-level-threes', 'WbsLevelThreeController');

    // Wbslevelfours
    Route::delete('wbslevelfours/destroy', 'WbslevelfourController@massDestroy')->name('wbslevelfours.massDestroy');
    Route::resource('wbslevelfours', 'WbslevelfourController');

     // Bo Qs
     Route::delete('bo-qs/destroy', 'BoQController@massDestroy')->name('bo-qs.massDestroy');
     Route::post('bo-qs/parse-csv-import', 'BoQController@parseCsvImport')->name('bo-qs.parseCsvImport');
     Route::post('bo-qs/process-csv-import', 'BoQController@processCsvImport')->name('bo-qs.processCsvImport');
     Route::resource('bo-qs', 'BoQController');
     
     // Works Codes
    Route::delete('works-codes/destroy', 'WorksCodeController@massDestroy')->name('works-codes.massDestroy');
    Route::resource('works-codes', 'WorksCodeController');

     // Submittals Rfas
     Route::delete('submittals-rfas/destroy', 'SubmittalsRfaController@massDestroy')->name('submittals-rfas.massDestroy');
     Route::resource('submittals-rfas', 'SubmittalsRfaController');

    // Daily Requests
    Route::delete('daily-requests/destroy', 'DailyRequestController@massDestroy')->name('daily-requests.massDestroy');
    Route::post('daily-requests/media', 'DailyRequestController@storeMedia')->name('daily-requests.storeMedia');
    Route::post('daily-requests/ckmedia', 'DailyRequestController@storeCKEditorImages')->name('daily-requests.storeCKEditorImages');
    Route::resource('daily-requests', 'DailyRequestController');

    // Daily Reports
    Route::delete('daily-reports/destroy', 'DailyReportController@massDestroy')->name('daily-reports.massDestroy');
    Route::post('daily-reports/media', 'DailyReportController@storeMedia')->name('daily-reports.storeMedia');
    Route::post('daily-reports/ckmedia', 'DailyReportController@storeCKEditorImages')->name('daily-reports.storeCKEditorImages');
    Route::resource('daily-reports', 'DailyReportController');

    // Request For Inspections
    Route::delete('request-for-inspections/destroy', 'RequestForInspectionController@massDestroy')->name('request-for-inspections.massDestroy');
    Route::post('request-for-inspections/media', 'RequestForInspectionController@storeMedia')->name('request-for-inspections.storeMedia');
    Route::post('request-for-inspections/ckmedia', 'RequestForInspectionController@storeCKEditorImages')->name('request-for-inspections.storeCKEditorImages');
    Route::resource('request-for-inspections', 'RequestForInspectionController');

    // Department Documents
    Route::delete('department-documents/destroy', 'DepartmentDocumentsController@massDestroy')->name('department-documents.massDestroy');
    Route::post('department-documents/media', 'DepartmentDocumentsController@storeMedia')->name('department-documents.storeMedia');
    Route::post('department-documents/ckmedia', 'DepartmentDocumentsController@storeCKEditorImages')->name('department-documents.storeCKEditorImages');
    Route::resource('department-documents', 'DepartmentDocumentsController');

    // Document Tags
    Route::delete('document-tags/destroy', 'DocumentTagController@massDestroy')->name('document-tags.massDestroy');
    Route::resource('document-tags', 'DocumentTagController');

    // Contract And Components
    Route::delete('contract-and-components/destroy', 'ContractAndComponentsController@massDestroy')->name('contract-and-components.massDestroy');
    Route::post('contract-and-components/media', 'ContractAndComponentsController@storeMedia')->name('contract-and-components.storeMedia');
    Route::post('contract-and-components/ckmedia', 'ContractAndComponentsController@storeCKEditorImages')->name('contract-and-components.storeCKEditorImages');
    Route::resource('contract-and-components', 'ContractAndComponentsController');

    // Meeting Monthlies
    Route::delete('meeting-monthlies/destroy', 'MeetingMonthlyController@massDestroy')->name('meeting-monthlies.massDestroy');
    Route::post('meeting-monthlies/media', 'MeetingMonthlyController@storeMedia')->name('meeting-monthlies.storeMedia');
    Route::post('meeting-monthlies/ckmedia', 'MeetingMonthlyController@storeCKEditorImages')->name('meeting-monthlies.storeCKEditorImages');
    Route::resource('meeting-monthlies', 'MeetingMonthlyController');

    // Meeting Weeklies
    Route::delete('meeting-weeklies/destroy', 'MeetingWeeklyController@massDestroy')->name('meeting-weeklies.massDestroy');
    Route::post('meeting-weeklies/media', 'MeetingWeeklyController@storeMedia')->name('meeting-weeklies.storeMedia');
    Route::post('meeting-weeklies/ckmedia', 'MeetingWeeklyController@storeCKEditorImages')->name('meeting-weeklies.storeCKEditorImages');
    Route::resource('meeting-weeklies', 'MeetingWeeklyController');

    // Meeting Others
    Route::delete('meeting-others/destroy', 'MeetingOtherController@massDestroy')->name('meeting-others.massDestroy');
    Route::post('meeting-others/media', 'MeetingOtherController@storeMedia')->name('meeting-others.storeMedia');
    Route::post('meeting-others/ckmedia', 'MeetingOtherController@storeCKEditorImages')->name('meeting-others.storeCKEditorImages');
    Route::resource('meeting-others', 'MeetingOtherController');

    // Manuals
    Route::delete('manuals/destroy', 'ManualsController@massDestroy')->name('manuals.massDestroy');
    Route::post('manuals/media', 'ManualsController@storeMedia')->name('manuals.storeMedia');
    Route::post('manuals/ckmedia', 'ManualsController@storeCKEditorImages')->name('manuals.storeCKEditorImages');
    Route::resource('manuals', 'ManualsController');

    // Daily Construction Activities
    Route::delete('daily-construction-activities/destroy', 'DailyConstructionActivitiesController@massDestroy')->name('daily-construction-activities.massDestroy');
    Route::post('daily-construction-activities/media', 'DailyConstructionActivitiesController@storeMedia')->name('daily-construction-activities.storeMedia');
    Route::post('daily-construction-activities/ckmedia', 'DailyConstructionActivitiesController@storeCKEditorImages')->name('daily-construction-activities.storeCKEditorImages');
    Route::resource('daily-construction-activities', 'DailyConstructionActivitiesController');

    // Procedures
    Route::delete('procedures/destroy', 'ProceduresController@massDestroy')->name('procedures.massDestroy');
    Route::post('procedures/media', 'ProceduresController@storeMedia')->name('procedures.storeMedia');
    Route::post('procedures/ckmedia', 'ProceduresController@storeCKEditorImages')->name('procedures.storeCKEditorImages');
    Route::resource('procedures', 'ProceduresController');

    // Drone Vdo Recordeds
    Route::delete('drone-vdo-recordeds/destroy', 'DroneVdoRecordedController@massDestroy')->name('drone-vdo-recordeds.massDestroy');
    Route::post('drone-vdo-recordeds/media', 'DroneVdoRecordedController@storeMedia')->name('drone-vdo-recordeds.storeMedia');
    Route::post('drone-vdo-recordeds/ckmedia', 'DroneVdoRecordedController@storeCKEditorImages')->name('drone-vdo-recordeds.storeCKEditorImages');
    Route::resource('drone-vdo-recordeds', 'DroneVdoRecordedController');

    // Records Of Visitors
    Route::delete('records-of-visitors/destroy', 'RecordsOfVisitorsController@massDestroy')->name('records-of-visitors.massDestroy');
    Route::post('records-of-visitors/media', 'RecordsOfVisitorsController@storeMedia')->name('records-of-visitors.storeMedia');
    Route::post('records-of-visitors/ckmedia', 'RecordsOfVisitorsController@storeCKEditorImages')->name('records-of-visitors.storeCKEditorImages');
    Route::resource('records-of-visitors', 'RecordsOfVisitorsController');
    
    
    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

//    });
   
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
        if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
            Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
            Route::post('password', 'ChangePasswordController@update')->name('password.update');
        }
});
