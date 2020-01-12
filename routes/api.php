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
});
