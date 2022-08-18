<?php

Route::redirect('/', '/login');
Route::get('dashboard-menu',function(){
    return view('auth.dashboard-menu');
})->name('dashboard-menu');


Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

// Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth'], 'namespace' => 'Admin'], function () {

//     Route::get('/push','PushController@push')->name('push');
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    
    Route::get('/', 'HomeController@index')->name('home');


    //->middleware('ConstructionContract.Select');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    Route::get('/', 'HomeController@index')->name('home');


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
    Route::get('rfas/{rfa}/createReportRFA','RfaController@createReportRFA')->name('rfas.createReportRFA');
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
    Route::post('wbslevelfours/parse-csv-import', 'WbslevelfourController@parseCsvImport')->name('wbslevelfours.parseCsvImport');
    Route::post('wbslevelfours/process-csv-import', 'WbslevelfourController@processCsvImport')->name('wbslevelfours.processCsvImport');
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
     Route::post('submittals-rfas/parse-csv-import', 'SubmittalsRfaController@parseCsvImport')->name('submittals-rfas.parseCsvImport');
     Route::post('submittals-rfas/process-csv-import', 'SubmittalsRfaController@processCsvImport')->name('submittals-rfas.processCsvImport');
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

    // Request For Inspections (RFN)
    Route::delete('request-for-inspections/destroy', 'RequestForInspectionController@massDestroy')->name('request-for-inspections.massDestroy');
    Route::post('request-for-inspections/media', 'RequestForInspectionController@storeMedia')->name('request-for-inspections.storeMedia');
    Route::post('request-for-inspections/ckmedia', 'RequestForInspectionController@storeCKEditorImages')->name('request-for-inspections.storeCKEditorImages');
    Route::post('request-for-inspections/parse-csv-import', 'RequestForInspectionController@parseCsvImport')->name('request-for-inspections.parseCsvImport');
    Route::post('request-for-inspections/process-csv-import', 'RequestForInspectionController@processCsvImport')->name('request-for-inspections.processCsvImport');
    Route::post('request-for-inspections/fetch','RequestForInspectionController@fetch')->name('rfns.fetch');
    Route::post('request-for-inspections/WbsTwo','RequestForInspectionController@WbsTwo')->name('rfns.WbsTwo');
    Route::post('request-for-inspections/WbsThree','RequestForInspectionController@WbsThree')->name('rfns.WbsThree');
    Route::post('request-for-inspections/itemBoQ','RequestForInspectionController@itemBoQ')->name('rfns.itemBoQ');
    Route::post('request-for-inspections/ModalAttachFilesUpload','RequestForInspectionController@ModalAttachFilesUpload')->name('rfns.ModalAttachFilesUpload');
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
    Route::get('daily-construction-activities/{request}/ZipFile','DailyConstructionActivitiesController@ZipFile')->name('daily-construction-activities.ZipFile');
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
    

    // Add Letters
    Route::delete('add-letters/destroy', 'AddLetterController@massDestroy')->name('add-letters.massDestroy');
    Route::post('add-letters/media', 'AddLetterController@storeMedia')->name('add-letters.storeMedia');
    Route::post('add-letters/ckmedia', 'AddLetterController@storeCKEditorImages')->name('add-letters.storeCKEditorImages');
    Route::post('add-letters/parse-csv-import', 'AddLetterController@parseCsvImport')->name('add-letters.parseCsvImport');
    Route::post('add-letters/process-csv-import', 'AddLetterController@processCsvImport')->name('add-letters.processCsvImport');
    Route::resource('add-letters', 'AddLetterController');

    // Letter Subject Type
    Route::delete('letter-subject-types/destroy', 'LetterSubjectTypeController@massDestroy')->name('letter-subject-types.massDestroy');
    Route::post('letter-subject-types/parse-csv-import', 'LetterSubjectTypeController@parseCsvImport')->name('letter-subject-types.parseCsvImport');
    Route::post('letter-subject-types/process-csv-import', 'LetterSubjectTypeController@processCsvImport')->name('letter-subject-types.processCsvImport');
    Route::resource('letter-subject-types', 'LetterSubjectTypeController');

     // Announcements
     Route::delete('announcements/destroy', 'AnnouncementsController@massDestroy')->name('announcements.massDestroy');
     Route::post('announcements/media', 'AnnouncementsController@storeMedia')->name('announcements.storeMedia');
     Route::post('announcements/ckmedia', 'AnnouncementsController@storeCKEditorImages')->name('announcements.storeCKEditorImages');
     Route::resource('announcements', 'AnnouncementsController');

   // Crdc Inboxes
   Route::resource('crdc-inboxes', 'CrdcInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Crdc Sent
   Route::resource('crdc-sents', 'CrdcSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);
   
   // Srt Inboxes
   Route::resource('srt-inboxes', 'SrtInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Srt Sents
   Route::resource('srt-sents', 'SrtSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Pmc Inboxes
   Route::resource('pmc-inboxes', 'PmcInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Pmc Sents
   Route::resource('pmc-sents', 'PmcSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Csc Inboxes
   Route::resource('csc-inboxes', 'CscInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Csc Sents
   Route::resource('csc-sents', 'CscSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Ccsp Inboxes
   Route::resource('ccsp-inboxes', 'CcspInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Ccsp Sents
   Route::resource('ccsp-sents', 'CcspSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Nwr Inboxes
   Route::resource('nwr-inboxes', 'NwrInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Nwr Sents
   Route::resource('nwr-sents', 'NwrSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Civil Inboxes
   Route::resource('civil-inboxes', 'CivilInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Civil Sents
   Route::resource('cilvil-sents', 'CivilSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Tei Inboxes
   Route::resource('tei-inboxes', 'TeiInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Tei Sents
   Route::resource('tei-sents', 'TeiSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Sptk Inboxes
   Route::resource('sptk-inboxes', 'SptkInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Sptk Sents
   Route::resource('sptk-sents', 'SptkSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Itd Inboxes
   Route::resource('itd-inboxes', 'ItdInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

   // Itd Sents
   Route::resource('itd-sents', 'ItdSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Can Inboxes
    Route::resource('can-inboxes', 'CanInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Can Sents
    Route::resource('can-sents', 'CanSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Can Inboxes
    Route::resource('unique-inboxes', 'UniqueInboxController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Can Sents
    Route::resource('unique-sents', 'UniqueSentController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);
   
    // Check Sheets
    Route::delete('check-sheets/destroy', 'CheckSheetController@massDestroy')->name('check-sheets.massDestroy');
    Route::post('check-sheets/media', 'CheckSheetController@storeMedia')->name('check-sheets.storeMedia');
    Route::post('check-sheets/ckmedia', 'CheckSheetController@storeCKEditorImages')->name('check-sheets.storeCKEditorImages');
    Route::resource('check-sheets', 'CheckSheetController');

    // Check Lists
    Route::delete('check-lists/destroy', 'CheckListController@massDestroy')->name('check-lists.massDestroy');
    Route::post('check-lists/media', 'CheckListController@storeMedia')->name('check-lists.storeMedia');
    Route::post('check-lists/ckmedia', 'CheckListController@storeCKEditorImages')->name('check-lists.storeCKEditorImages');
    Route::resource('check-lists', 'CheckListController');

    // Download System Activities
    Route::delete('download-system-activities/destroy', 'DownloadSystemActivityController@massDestroy')->name('download-system-activities.massDestroy');
    Route::resource('download-system-activities', 'DownloadSystemActivityController');

    // Download System Works
    Route::delete('download-system-works/destroy', 'DownloadSystemWorkController@massDestroy')->name('download-system-works.massDestroy');
    Route::resource('download-system-works', 'DownloadSystemWorkController');

    // Add Drawings
    Route::delete('add-drawings/destroy', 'AddDrawingController@massDestroy')->name('add-drawings.massDestroy');
    Route::post('add-drawings/media', 'AddDrawingController@storeMedia')->name('add-drawings.storeMedia');
    Route::post('add-drawings/ckmedia', 'AddDrawingController@storeCKEditorImages')->name('add-drawings.storeCKEditorImages');
    Route::resource('add-drawings', 'AddDrawingController');

    // Shop Drawings
    Route::delete('shop-drawings/destroy', 'ShopDrawingController@massDestroy')->name('shop-drawings.massDestroy');
    Route::post('shop-drawings/media', 'ShopDrawingController@storeMedia')->name('shop-drawings.storeMedia');
    Route::post('shop-drawings/ckmedia', 'ShopDrawingController@storeCKEditorImages')->name('shop-drawings.storeCKEditorImages');
    Route::resource('shop-drawings', 'ShopDrawingController');

    // As Build Plans
    Route::delete('as-build-plans/destroy', 'AsBuildPlanController@massDestroy')->name('as-build-plans.massDestroy');
    Route::post('as-build-plans/media', 'AsBuildPlanController@storeMedia')->name('as-build-plans.storeMedia');
    Route::post('as-build-plans/ckmedia', 'AsBuildPlanController@storeCKEditorImages')->name('as-build-plans.storeCKEditorImages');
    Route::resource('as-build-plans', 'AsBuildPlanController');

    // Variation Orders
    Route::delete('variation-orders/destroy', 'VariationOrderController@massDestroy')->name('variation-orders.massDestroy');
    Route::post('variation-orders/media', 'VariationOrderController@storeMedia')->name('variation-orders.storeMedia');
    Route::post('variation-orders/ckmedia', 'VariationOrderController@storeCKEditorImages')->name('variation-orders.storeCKEditorImages');
    Route::resource('variation-orders', 'VariationOrderController');

    // Provisional Sums
    Route::delete('provisional-sums/destroy', 'ProvisionalSumController@massDestroy')->name('provisional-sums.massDestroy');
    Route::post('provisional-sums/media', 'ProvisionalSumController@storeMedia')->name('provisional-sums.storeMedia');
    Route::post('provisional-sums/ckmedia', 'ProvisionalSumController@storeCKEditorImages')->name('provisional-sums.storeCKEditorImages');
    Route::resource('provisional-sums', 'ProvisionalSumController');

    // Interim Payments
    Route::delete('interim-payments/destroy', 'InterimPaymentController@massDestroy')->name('interim-payments.massDestroy');
    Route::post('interim-payments/media', 'InterimPaymentController@storeMedia')->name('interim-payments.storeMedia');
    Route::post('interim-payments/ckmedia', 'InterimPaymentController@storeCKEditorImages')->name('interim-payments.storeCKEditorImages');
    Route::resource('interim-payments', 'InterimPaymentController');

    // Monthly Report Cscs
    Route::delete('monthly-report-cscs/destroy', 'MonthlyReportCscController@massDestroy')->name('monthly-report-cscs.massDestroy');
    Route::post('monthly-report-cscs/media', 'MonthlyReportCscController@storeMedia')->name('monthly-report-cscs.storeMedia');
    Route::post('monthly-report-cscs/ckmedia', 'MonthlyReportCscController@storeCKEditorImages')->name('monthly-report-cscs.storeCKEditorImages');
    Route::resource('monthly-report-cscs', 'MonthlyReportCscController');

    // Monthly Report Constructors
    Route::delete('monthly-report-constructors/destroy', 'MonthlyReportConstructorController@massDestroy')->name('monthly-report-constructors.massDestroy');
    Route::post('monthly-report-constructors/media', 'MonthlyReportConstructorController@storeMedia')->name('monthly-report-constructors.storeMedia');
    Route::post('monthly-report-constructors/ckmedia', 'MonthlyReportConstructorController@storeCKEditorImages')->name('monthly-report-constructors.storeCKEditorImages');
    Route::resource('monthly-report-constructors', 'MonthlyReportConstructorController');

    // Documents And Assignments
    Route::delete('documents-and-assignments/destroy', 'DocumentsAndAssignmentsController@massDestroy')->name('documents-and-assignments.massDestroy');
    Route::post('documents-and-assignments/media', 'DocumentsAndAssignmentsController@storeMedia')->name('documents-and-assignments.storeMedia');
    Route::post('documents-and-assignments/ckmedia', 'DocumentsAndAssignmentsController@storeCKEditorImages')->name('documents-and-assignments.storeCKEditorImages');
    Route::resource('documents-and-assignments', 'DocumentsAndAssignmentsController');

    // Wbs Level Ones
    Route::delete('wbs-level-ones/destroy', 'WbsLevelOneController@massDestroy')->name('wbs-level-ones.massDestroy');
    Route::post('wbs-level-ones/parse-csv-import', 'WbsLevelOneController@parseCsvImport')->name('wbs-level-ones.parseCsvImport');
    Route::post('wbs-level-ones/process-csv-import', 'WbsLevelOneController@processCsvImport')->name('wbs-level-ones.processCsvImport');
    Route::resource('wbs-level-ones', 'WbsLevelOneController');

    // Wbs Level Twos
    Route::delete('wbs-level-twos/destroy', 'WbsLevelTwoController@massDestroy')->name('wbs-level-twos.massDestroy');
    Route::resource('wbs-level-twos', 'WbsLevelTwoController');

    // Wbs Level Fives
    Route::delete('wbs-level-fives/destroy', 'WbsLevelFiveController@massDestroy')->name('wbs-level-fives.massDestroy');
    Route::post('wbs-level-fives/parse-csv-import', 'WbsLevelFiveController@parseCsvImport')->name('wbs-level-fives.parseCsvImport');
    Route::post('wbs-level-fives/process-csv-import', 'WbsLevelFiveController@processCsvImport')->name('wbs-level-fives.processCsvImport');
    Route::resource('wbs-level-fives', 'WbsLevelFiveController');

    // Request For Informations (RFI)
    Route::delete('request-for-informations/destroy', 'RequestForInformationController@massDestroy')->name('request-for-informations.massDestroy');
    Route::post('request-for-informations/media', 'RequestForInformationController@storeMedia')->name('request-for-informations.storeMedia');
    Route::post('request-for-informations/ckmedia', 'RequestForInformationController@storeCKEditorImages')->name('request-for-informations.storeCKEditorImages');
    Route::post('request-for-informations/selectWBS','RequestForInformationController@selectWBS')->name('request-for-informations.selectWBS');
    
    Route::get('request-for-informations/{rfi}/createReportRFI','RequestForInformationController@createReportRFI')->name('request-for-informations.createReportRFI');
    Route::resource('request-for-informations', 'RequestForInformationController');


    

    // Boq Items
    Route::delete('boq-items/destroy', 'BoqItemController@massDestroy')->name('boq-items.massDestroy');
    Route::post('boq-items/parse-csv-import', 'BoqItemController@parseCsvImport')->name('boq-items.parseCsvImport');
    Route::post('boq-items/process-csv-import', 'BoqItemController@processCsvImport')->name('boq-items.processCsvImport');
    Route::resource('boq-items', 'BoqItemController');

     // Srt Input Documents
     Route::delete('srt-input-documents/destroy', 'SrtInputDocumentsController@massDestroy')->name('srt-input-documents.massDestroy');
     Route::post('srt-input-documents/media', 'SrtInputDocumentsController@storeMedia')->name('srt-input-documents.storeMedia');
     Route::post('srt-input-documents/ckmedia', 'SrtInputDocumentsController@storeCKEditorImages')->name('srt-input-documents.storeCKEditorImages');
     Route::post('srt-input-documents/parse-csv-import', 'SrtInputDocumentsController@parseCsvImport')->name('srt-input-documents.parseCsvImport');
     Route::post('srt-input-documents/process-csv-import', 'SrtInputDocumentsController@processCsvImport')->name('srt-input-documents.processCsvImport');
     Route::resource('srt-input-documents', 'SrtInputDocumentsController');
 
     // Srt Document Statuses
     Route::delete('srt-document-statuses/destroy', 'SrtDocumentStatusController@massDestroy')->name('srt-document-statuses.massDestroy');
     Route::resource('srt-document-statuses', 'SrtDocumentStatusController');
 
     // Srt Head Office Documents
     Route::delete('srt-head-office-documents/destroy', 'SrtHeadOfficeDocumentController@massDestroy')->name('srt-head-office-documents.massDestroy');
     Route::post('srt-head-office-documents/media', 'SrtHeadOfficeDocumentController@storeMedia')->name('srt-head-office-documents.storeMedia');
     Route::post('srt-head-office-documents/ckmedia', 'SrtHeadOfficeDocumentController@storeCKEditorImages')->name('srt-head-office-documents.storeCKEditorImages');
     Route::post('srt-head-office-documents/parse-csv-import', 'SrtHeadOfficeDocumentController@parseCsvImport')->name('srt-head-office-documents.parseCsvImport');
     Route::post('srt-head-office-documents/process-csv-import', 'SrtHeadOfficeDocumentController@processCsvImport')->name('srt-head-office-documents.processCsvImport');
     Route::resource('srt-head-office-documents', 'SrtHeadOfficeDocumentController');

     // Tickets
    Route::delete('tickets/destroy', 'TicketController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/ckmedia', 'TicketController@storeCKEditorImages')->name('tickets.storeCKEditorImages');
    Route::resource('tickets', 'TicketController');

    // Srt Pd Documents
    Route::delete('srt-pd-documents/destroy', 'SrtPdDocumentsController@massDestroy')->name('srt-pd-documents.massDestroy');
    Route::post('srt-pd-documents/media', 'SrtPdDocumentsController@storeMedia')->name('srt-pd-documents.storeMedia');
    Route::post('srt-pd-documents/ckmedia', 'SrtPdDocumentsController@storeCKEditorImages')->name('srt-pd-documents.storeCKEditorImages');
    Route::post('srt-pd-documents/parse-csv-import', 'SrtPdDocumentsController@parseCsvImport')->name('srt-pd-documents.parseCsvImport');
    Route::post('srt-pd-documents/process-csv-import', 'SrtPdDocumentsController@processCsvImport')->name('srt-pd-documents.processCsvImport');
    Route::resource('srt-pd-documents', 'SrtPdDocumentsController');

    // Srt Pe Documents
    Route::delete('srt-pe-documents/destroy', 'SrtPeDocumentsController@massDestroy')->name('srt-pe-documents.massDestroy');
    Route::post('srt-pe-documents/media', 'SrtPeDocumentsController@storeMedia')->name('srt-pe-documents.storeMedia');
    Route::post('srt-pe-documents/ckmedia', 'SrtPeDocumentsController@storeCKEditorImages')->name('srt-pe-documents.storeCKEditorImages');
    Route::post('srt-pe-documents/parse-csv-import', 'SrtPeDocumentsController@parseCsvImport')->name('srt-pe-documents.parseCsvImport');
    Route::post('srt-pe-documents/process-csv-import', 'SrtPeDocumentsController@processCsvImport')->name('srt-pe-documents.processCsvImport');
    Route::resource('srt-pe-documents', 'SrtPeDocumentsController');

    // Srt External Documents
    Route::delete('srt-external-documents/destroy', 'SrtExternalDocumentController@massDestroy')->name('srt-external-documents.massDestroy');
    Route::post('srt-external-documents/media', 'SrtExternalDocumentController@storeMedia')->name('srt-external-documents.storeMedia');
    Route::post('srt-external-documents/ckmedia', 'SrtExternalDocumentController@storeCKEditorImages')->name('srt-external-documents.storeCKEditorImages');
    Route::resource('srt-external-documents', 'SrtExternalDocumentController');

     // Srt Other
     Route::delete('srt-others/destroy', 'SrtOtherController@massDestroy')->name('srt-others.massDestroy');
     Route::post('srt-others/media', 'SrtOtherController@storeMedia')->name('srt-others.storeMedia');
     Route::post('srt-others/ckmedia', 'SrtOtherController@storeCKEditorImages')->name('srt-others.storeCKEditorImages');
     Route::resource('srt-others', 'SrtOtherController');

      // Srt Department
    Route::delete('srt-departments/destroy', 'SrtDepartmentController@massDestroy')->name('srt-departments.massDestroy');
    Route::resource('srt-departments', 'SrtDepartmentController');

    // Srt Dpd Documents
    Route::delete('srt-dpd-documents/destroy', 'SrtDpdDocumentsController@massDestroy')->name('srt-dpd-documents.massDestroy');
    Route::post('srt-dpd-documents/media', 'SrtDpdDocumentsController@storeMedia')->name('srt-dpd-documents.storeMedia');
    Route::post('srt-dpd-documents/ckmedia', 'SrtDpdDocumentsController@storeCKEditorImages')->name('srt-dpd-documents.storeCKEditorImages');
    Route::post('srt-dpd-documents/parse-csv-import', 'SrtDpdDocumentsController@parseCsvImport')->name('srt-dpd-documents.parseCsvImport');
    Route::post('srt-dpd-documents/process-csv-import', 'SrtDpdDocumentsController@processCsvImport')->name('srt-dpd-documents.processCsvImport');
    Route::resource('srt-dpd-documents', 'SrtDpdDocumentsController');
    
    // Organizations
    Route::delete('organizations/destroy', 'OrganizationController@massDestroy')->name('organizations.massDestroy');
    Route::resource('organizations', 'OrganizationController');

    // Complaints
    Route::delete('complaints/destroy', 'ComplaintController@massDestroy')->name('complaints.massDestroy');
    Route::post('complaints/media', 'ComplaintController@storeMedia')->name('complaints.storeMedia');
    Route::post('complaints/ckmedia', 'ComplaintController@storeCKEditorImages')->name('complaints.storeCKEditorImages');
    Route::post('complaints/parse-csv-import', 'ComplaintController@parseCsvImport')->name('complaints.parseCsvImport');
    Route::post('complaints/process-csv-import', 'ComplaintController@processCsvImport')->name('complaints.processCsvImport');
    Route::resource('complaints', 'ComplaintController');

     // Swn
    Route::delete('swns/destroy', 'SwnController@massDestroy')->name('swns.massDestroy');
    Route::post('swns/media', 'SwnController@storeMedia')->name('swns.storeMedia');
    Route::post('swns/ckmedia', 'SwnController@storeCKEditorImages')->name('swns.storeCKEditorImages');
    Route::post('swns/parse-csv-import', 'SwnController@parseCsvImport')->name('swns.parseCsvImport');
    Route::post('swns/process-csv-import', 'SwnController@processCsvImport')->name('swns.processCsvImport');
    Route::get('swns/{swn}/createReportSWN','SwnController@createReportSWN')->name('swn.createReportSWN');
    Route::resource('swns', 'SwnController');

     // Ncn
     Route::delete('ncns/destroy', 'NcnController@massDestroy')->name('ncns.massDestroy');
     Route::post('ncns/media', 'NcnController@storeMedia')->name('ncns.storeMedia');
     Route::post('ncns/ckmedia', 'NcnController@storeCKEditorImages')->name('ncns.storeCKEditorImages');
     Route::post('ncns/parse-csv-import', 'NcnController@parseCsvImport')->name('ncns.parseCsvImport');
     Route::post('ncns/process-csv-import', 'NcnController@processCsvImport')->name('ncns.processCsvImport');
     Route::get('ncns/{ncn}/createReportNCN','NcnController@createReportNCN')->name('ncn.createReportNCN');
     Route::resource('ncns', 'NcnController');
 
     // Ncr
     Route::delete('ncrs/destroy', 'NcrController@massDestroy')->name('ncrs.massDestroy');
     Route::post('ncrs/media', 'NcrController@storeMedia')->name('ncrs.storeMedia');
     Route::post('ncrs/ckmedia', 'NcrController@storeCKEditorImages')->name('ncrs.storeCKEditorImages');
     Route::post('ncrs/parse-csv-import', 'NcrController@parseCsvImport')->name('ncrs.parseCsvImport');
     Route::post('ncrs/process-csv-import', 'NcrController@processCsvImport')->name('ncrs.processCsvImport');
     Route::resource('ncrs', 'NcrController');

      // Interim Payment Meeting
    Route::delete('interim-payment-meetings/destroy', 'InterimPaymentMeetingController@massDestroy')->name('interim-payment-meetings.massDestroy');
    Route::post('interim-payment-meetings/media', 'InterimPaymentMeetingController@storeMedia')->name('interim-payment-meetings.storeMedia');
    Route::post('interim-payment-meetings/ckmedia', 'InterimPaymentMeetingController@storeCKEditorImages')->name('interim-payment-meetings.storeCKEditorImages');
    Route::resource('interim-payment-meetings', 'InterimPaymentMeetingController');

    // Srt External Agency Documents
    Route::delete('srt-external-agency-documents/destroy', 'SrtExternalAgencyDocumentsController@massDestroy')->name('srt-external-agency-documents.massDestroy');
    Route::post('srt-external-agency-documents/media', 'SrtExternalAgencyDocumentsController@storeMedia')->name('srt-external-agency-documents.storeMedia');
    Route::post('srt-external-agency-documents/ckmedia', 'SrtExternalAgencyDocumentsController@storeCKEditorImages')->name('srt-external-agency-documents.storeCKEditorImages');
    Route::resource('srt-external-agency-documents', 'SrtExternalAgencyDocumentsController');

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
