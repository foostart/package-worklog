<?php

use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;
use Foostart\Category\Helpers\FooCategory;
use Foostart\Category\Helpers\SortTable;

/*
  |-----------------------------------------------------------------------
  | GLOBAL VARIABLES
  |-----------------------------------------------------------------------
  |   $sidebar_items
  |   $sorting
  |   $order_by
  |   $plang_admin = 'worklog-admin'
  |   $plang_front = 'worklog-front'
 */
View::composer([
    'package-worklog::admin.worklog-edit',
    'package-worklog::admin.worklog-form',
    'package-worklog::admin.worklog-items',
    'package-worklog::admin.worklog-item',
    'package-worklog::admin.worklog-search',
    'package-worklog::admin.worklog-config',
    'package-worklog::admin.worklog-lang',
    'package-worklog::admin.worklog-view',
    'package-worklog::admin.worklog-view-item',
        ], function ($view) {

    /**
     * $plang-admin
     * $plang-front
     */
    $plang_admin = 'worklog-admin';
    $plang_front = 'worklog-front';

    $view->with('plang_admin', $plang_admin);
    $view->with('plang_front', $plang_front);

    $fooCategory = new FooCategory();
    $key = $fooCategory->getContextKeyByRef('admin/worklogs');
    /**
     * $sidebar_items
     */
    $sidebar_items = [
        trans('worklog-admin.sidebar.add') => [
            'url' => URL::route('worklogs.edit', []),
            'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
        ],
        trans('worklog-admin.sidebar.list') => [
            "url" => URL::route('worklogs.list', []),
            'icon' => '<i class="fa fa-list-ul" aria-hidden="true"></i>'
        ],
        trans('worklog-admin.sidebar.category') => [
            'url' => URL::route('categories.list', ['_key=' . $key]),
            'icon' => '<i class="fa fa-sitemap" aria-hidden="true"></i>'
        ],
        trans('worklog-admin.sidebar.config') => [
            "url" => URL::route('worklogs.config', []),
            'icon' => '<i class="fa fa-braille" aria-hidden="true"></i>'
        ],
        trans('worklog-admin.sidebar.lang') => [
            "url" => URL::route('worklogs.lang', []),
            'icon' => '<i class="fa fa-language" aria-hidden="true"></i>'
        ],
    ];

    /**
     * $sorting
     * $order_by
     */
    $orders = [
        '' => trans($plang_admin . '.form.no-selected'),
        'id' => trans($plang_admin . '.fields.id'),
        'task_id' => trans($plang_admin . '.fields.task_id'),
        'task_url' => trans($plang_admin . '.fields.task_url'),
        'task_name' => trans($plang_admin . '.fields.name'),
        'task_status' => trans($plang_admin . '.fields.worklog-status'),
        'updated_at' => trans($plang_admin . '.fields.updated_at'),
    ];

    $sortTable = new SortTable();
    $sortTable->setOrders($orders);
    $sorting = $sortTable->linkOrders();

    /**
     * $order_by
     */
    $order_by = [
        'asc' => trans('category-admin.order.by-asc'),
        'desc' => trans('category-admin.order.by-des'),
    ];

    /**
     * Send to view
     */
    $view->with('sidebar_items', $sidebar_items );
    $view->with('plang_admin', $plang_admin);
    $view->with('plang_front', $plang_front);
    $view->with('sorting', $sorting);
    $view->with('order_by', $order_by);
});
