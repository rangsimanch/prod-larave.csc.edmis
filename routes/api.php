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
 
    });
