<?php

use Illuminate\Session\TokenMismatchException;

/**
 * FRONT
 */
Route::get('worklog', [
    'as' => 'worklog',
    'uses' => 'Foostart\Worklog\Controllers\Front\WorklogFrontController@index'
]);


/**
 * ADMINISTRATOR
 */
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['admin_logged', 'can_see', 'in_context'],
                  'namespace' => 'Foostart\Worklog\Controllers\Admin',
        ], function () {

        /*
          |-----------------------------------------------------------------------
          | Manage worklog
          |-----------------------------------------------------------------------
          | 1. List of worklogs
          | 2. Edit worklog
          | 3. Delete worklog
          | 4. Add new worklog
          | 5. Manage configurations
          | 6. Manage languages
          |
        */

        /**
         * list
         */
        Route::get('admin/worklogs', [
            'as' => 'worklogs.list',
            'uses' => 'WorklogAdminController@index'
        ]);
        Route::get('admin/worklogs/list', [
            'as' => 'worklogs.list',
            'uses' => 'WorklogAdminController@index'
        ]);

        /**
         * view
         */
        Route::get('admin/worklogs/view', [
            'as' => 'worklogs.view',
            'uses' => 'WorklogAdminController@view'
        ]);

        /**
         * view
         */
        Route::get('admin/worklogs/download', [
            'as' => 'worklogs.download',
            'uses' => 'WorklogAdminController@download'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/worklogs/edit', [
            'as' => 'worklogs.edit',
            'uses' => 'WorklogAdminController@edit'
        ]);

        /**
         * copy
         */
        Route::get('admin/worklogs/copy', [
            'as' => 'worklogs.copy',
            'uses' => 'WorklogAdminController@copy'
        ]);

        /**
         * post
         */
        Route::post('admin/worklogs/edit', [
            'as' => 'worklogs.post',
            'uses' => 'WorklogAdminController@post'
        ]);

        /**
         * delete
         */
        Route::get('admin/worklogs/delete', [
            'as' => 'worklogs.delete',
            'uses' => 'WorklogAdminController@delete'
        ]);

        Route::get('admin/taskrule/delete', [
            'as' => 'taskrule.delete',
            'uses' => 'WorklogAdminController@deleteTaskRule'
        ]);

        /**
         * Checked
         */
        Route::get('admin/taskrule/checked', [
            'as' => 'taskrule.checked',
            'uses' => 'WorklogAdminController@checked'
        ]);
        /**
         * trash
         */
         Route::get('admin/worklogs/trash', [
            'as' => 'worklogs.trash',
            'uses' => 'WorklogAdminController@trash'
        ]);

        /**
         * configs
        */
        Route::get('admin/worklogs/config', [
            'as' => 'worklogs.config',
            'uses' => 'WorklogAdminController@config'
        ]);

        Route::post('admin/worklogs/config', [
            'as' => 'worklogs.config',
            'uses' => 'WorklogAdminController@config'
        ]);

        /**
         * language
        */
        Route::get('admin/worklogs/lang', [
            'as' => 'worklogs.lang',
            'uses' => 'WorklogAdminController@lang'
        ]);

        Route::post('admin/worklogs/lang', [
            'as' => 'worklogs.lang',
            'uses' => 'WorklogAdminController@lang'
        ]);

    });
});
