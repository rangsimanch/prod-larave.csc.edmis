<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

    // Jobtitles
    Route::apiResource('jobtitles', 'JobtitleApiController');

    // Teams
    Route::apiResource('teams', 'TeamApiController');

    // Departments
    Route::apiResource('departments', 'DepartmentApiController');

    // Rfas
    Route::post('rfas/media', 'RfaApiController@storeMedia')->name('rfas.storeMedia');
    Route::apiResource('rfas', 'RfaApiController');

    // Rfatypes
    Route::apiResource('rfatypes', 'RfatypesApiController');

    // Rfa Comment Statuses
    Route::apiResource('rfa-comment-statuses', 'RfaCommentStatusApiController');

    // Rfa Document Statuses
    Route::apiResource('rfa-document-statuses', 'RfaDocumentStatusApiController');

    // Task Statuses
    Route::apiResource('task-statuses', 'TaskStatusApiController');

    // Task Tags
    Route::apiResource('task-tags', 'TaskTagApiController');

    // Tasks
    Route::post('tasks/media', 'TaskApiController@storeMedia')->name('tasks.storeMedia');
    Route::apiResource('tasks', 'TaskApiController');

    // Tasks Calendars
    Route::apiResource('tasks-calendars', 'TasksCalendarApiController', ['except' => ['store', 'show', 'update', 'destroy']]);

    // File Managers
    Route::post('file-managers/media', 'FileManagerApiController@storeMedia')->name('file-managers.storeMedia');
    Route::apiResource('file-managers', 'FileManagerApiController');

    // Construction Contracts
    Route::apiResource('construction-contracts', 'ConstructionContractApiController');

    // Wbs Level Threes
    Route::apiResource('wbs-level-threes', 'WbsLevelThreeApiController');

    // Wbslevelfours
    Route::apiResource('wbslevelfours', 'WbslevelfourApiController');

    // Bo Qs
    Route::apiResource('bo-qs', 'BoQApiController');

    // Works Codes
    Route::apiResource('works-codes', 'WorksCodeApiController');

    // Submittals Rfas
    Route::apiResource('submittals-rfas', 'SubmittalsRfaApiController');

    // Daily Requests
    Route::post('daily-requests/media', 'DailyRequestApiController@storeMedia')->name('daily-requests.storeMedia');
    Route::apiResource('daily-requests', 'DailyRequestApiController');

    // Daily Reports
    Route::post('daily-reports/media', 'DailyReportApiController@storeMedia')->name('daily-reports.storeMedia');
    Route::apiResource('daily-reports', 'DailyReportApiController');

    // Request For Inspections
    Route::post('request-for-inspections/media', 'RequestForInspectionApiController@storeMedia')->name('request-for-inspections.storeMedia');
    Route::apiResource('request-for-inspections', 'RequestForInspectionApiController');

    // Department Documents
    Route::post('department-documents/media', 'DepartmentDocumentsApiController@storeMedia')->name('department-documents.storeMedia');
    Route::apiResource('department-documents', 'DepartmentDocumentsApiController');

    // Document Tags
    Route::apiResource('document-tags', 'DocumentTagApiController');

    // Contract And Components
    Route::post('contract-and-components/media', 'ContractAndComponentsApiController@storeMedia')->name('contract-and-components.storeMedia');
    Route::apiResource('contract-and-components', 'ContractAndComponentsApiController');

    // Meeting Monthlies
    Route::post('meeting-monthlies/media', 'MeetingMonthlyApiController@storeMedia')->name('meeting-monthlies.storeMedia');
    Route::apiResource('meeting-monthlies', 'MeetingMonthlyApiController');

    // Meeting Weeklies
    Route::post('meeting-weeklies/media', 'MeetingWeeklyApiController@storeMedia')->name('meeting-weeklies.storeMedia');
    Route::apiResource('meeting-weeklies', 'MeetingWeeklyApiController');

    // Meeting Others
    Route::post('meeting-others/media', 'MeetingOtherApiController@storeMedia')->name('meeting-others.storeMedia');
    Route::apiResource('meeting-others', 'MeetingOtherApiController');

    // Manuals
    Route::post('manuals/media', 'ManualsApiController@storeMedia')->name('manuals.storeMedia');
    Route::apiResource('manuals', 'ManualsApiController');

    // Daily Construction Activities
    Route::post('daily-construction-activities/media', 'DailyConstructionActivitiesApiController@storeMedia')->name('daily-construction-activities.storeMedia');
    Route::apiResource('daily-construction-activities', 'DailyConstructionActivitiesApiController');

    // Procedures
    Route::post('procedures/media', 'ProceduresApiController@storeMedia')->name('procedures.storeMedia');
    Route::apiResource('procedures', 'ProceduresApiController');

    // Drone Vdo Recordeds
    Route::post('drone-vdo-recordeds/media', 'DroneVdoRecordedApiController@storeMedia')->name('drone-vdo-recordeds.storeMedia');
    Route::apiResource('drone-vdo-recordeds', 'DroneVdoRecordedApiController');

    // Records Of Visitors
    Route::post('records-of-visitors/media', 'RecordsOfVisitorsApiController@storeMedia')->name('records-of-visitors.storeMedia');
    Route::apiResource('records-of-visitors', 'RecordsOfVisitorsApiController');

    // Add Letters
    Route::post('add-letters/media', 'AddLetterApiController@storeMedia')->name('add-letters.storeMedia');
    Route::apiResource('add-letters', 'AddLetterApiController');

    // Check Sheets
    Route::post('check-sheets/media', 'CheckSheetApiController@storeMedia')->name('check-sheets.storeMedia');
    Route::apiResource('check-sheets', 'CheckSheetApiController');

    // Check Lists
    Route::post('check-lists/media', 'CheckListApiController@storeMedia')->name('check-lists.storeMedia');
    Route::apiResource('check-lists', 'CheckListApiController');

    // Download System Activities
    Route::apiResource('download-system-activities', 'DownloadSystemActivityApiController');

    // Download System Works
    Route::apiResource('download-system-works', 'DownloadSystemWorkApiController');

    // Add Drawings
    Route::post('add-drawings/media', 'AddDrawingApiController@storeMedia')->name('add-drawings.storeMedia');
    Route::apiResource('add-drawings', 'AddDrawingApiController');

    // Shop Drawings
    Route::post('shop-drawings/media', 'ShopDrawingApiController@storeMedia')->name('shop-drawings.storeMedia');
    Route::apiResource('shop-drawings', 'ShopDrawingApiController');

    // As Build Plans
    Route::post('as-build-plans/media', 'AsBuildPlanApiController@storeMedia')->name('as-build-plans.storeMedia');
    Route::apiResource('as-build-plans', 'AsBuildPlanApiController');

    // Variation Orders
    Route::post('variation-orders/media', 'VariationOrderApiController@storeMedia')->name('variation-orders.storeMedia');
    Route::apiResource('variation-orders', 'VariationOrderApiController');

    // Provisional Sums
    Route::post('provisional-sums/media', 'ProvisionalSumApiController@storeMedia')->name('provisional-sums.storeMedia');
    Route::apiResource('provisional-sums', 'ProvisionalSumApiController');

    // Interim Payments
    Route::post('interim-payments/media', 'InterimPaymentApiController@storeMedia')->name('interim-payments.storeMedia');
    Route::apiResource('interim-payments', 'InterimPaymentApiController');

    // Monthly Report Cscs
    Route::post('monthly-report-cscs/media', 'MonthlyReportCscApiController@storeMedia')->name('monthly-report-cscs.storeMedia');
    Route::apiResource('monthly-report-cscs', 'MonthlyReportCscApiController');

    // Monthly Report Constructors
    Route::post('monthly-report-constructors/media', 'MonthlyReportConstructorApiController@storeMedia')->name('monthly-report-constructors.storeMedia');
    Route::apiResource('monthly-report-constructors', 'MonthlyReportConstructorApiController');

    // Documents And Assignments
    Route::post('documents-and-assignments/media', 'DocumentsAndAssignmentsApiController@storeMedia')->name('documents-and-assignments.storeMedia');
    Route::apiResource('documents-and-assignments', 'DocumentsAndAssignmentsApiController');

    // Wbs Level Ones
    Route::apiResource('wbs-level-ones', 'WbsLevelOneApiController');

    // Wbs Level Fives
    Route::apiResource('wbs-level-fives', 'WbsLevelFiveApiController');

    // Request For Informations
    Route::post('request-for-informations/media', 'RequestForInformationApiController@storeMedia')->name('request-for-informations.storeMedia');
    Route::apiResource('request-for-informations', 'RequestForInformationApiController');

    // Site Warning Notices
    Route::post('site-warning-notices/media', 'SiteWarningNoticeApiController@storeMedia')->name('site-warning-notices.storeMedia');
    Route::apiResource('site-warning-notices', 'SiteWarningNoticeApiController');

    // Non Conformance Notices
    Route::post('non-conformance-notices/media', 'NonConformanceNoticeApiController@storeMedia')->name('non-conformance-notices.storeMedia');
    Route::apiResource('non-conformance-notices', 'NonConformanceNoticeApiController');

    // Non Conformance Reports
    Route::post('non-conformance-reports/media', 'NonConformanceReportApiController@storeMedia')->name('non-conformance-reports.storeMedia');
    Route::apiResource('non-conformance-reports', 'NonConformanceReportApiController');

    // Boq Items
    Route::apiResource('boq-items', 'BoqItemApiController');

    // Srt Input Documents
    Route::post('srt-input-documents/media', 'SrtInputDocumentsApiController@storeMedia')->name('srt-input-documents.storeMedia');
    Route::apiResource('srt-input-documents', 'SrtInputDocumentsApiController');

    // Srt Document Statuses
    Route::apiResource('srt-document-statuses', 'SrtDocumentStatusApiController');

    // Srt Head Office Documents
    Route::post('srt-head-office-documents/media', 'SrtHeadOfficeDocumentApiController@storeMedia')->name('srt-head-office-documents.storeMedia');
    Route::apiResource('srt-head-office-documents', 'SrtHeadOfficeDocumentApiController');

    // Tickets
    Route::post('tickets/media', 'TicketApiController@storeMedia')->name('tickets.storeMedia');
    Route::apiResource('tickets', 'TicketApiController');

      // Srt Pd Documents
      Route::post('srt-pd-documents/media', 'SrtPdDocumentsApiController@storeMedia')->name('srt-pd-documents.storeMedia');
      Route::apiResource('srt-pd-documents', 'SrtPdDocumentsApiController');
  
      // Srt Pe Documents
      Route::post('srt-pe-documents/media', 'SrtPeDocumentsApiController@storeMedia')->name('srt-pe-documents.storeMedia');
      Route::apiResource('srt-pe-documents', 'SrtPeDocumentsApiController');
      
      // Srt External Documents
    Route::post('srt-external-documents/media', 'SrtExternalDocumentApiController@storeMedia')->name('srt-external-documents.storeMedia');
    Route::apiResource('srt-external-documents', 'SrtExternalDocumentApiController');

     // Organizations
     Route::apiResource('organizations', 'OrganizationApiController');

      // Complaints
    Route::post('complaints/media', 'ComplaintApiController@storeMedia')->name('complaints.storeMedia');
    Route::apiResource('complaints', 'ComplaintApiController');
});
