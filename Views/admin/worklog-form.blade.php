<!------------------------------------------------------------------------------
| List of elements in worklog form
|------------------------------------------------------------------------------->

{!! Form::open(['route'=>['worklogs.post', 'id' => @$item->id],  'files'=>true, 'method' => 'post'])  !!}

    <!--BUTTONS-->
    <div class='btn-form'>
        <!-- DELETE BUTTON -->
        @if($item)
            <a href="{!! URL::route('worklogs.delete',['id' => @$item->id, '_token' => csrf_token()]) !!}"
            class="btn btn-danger pull-right margin-left-5 delete">
                {!! trans($plang_admin.'.buttons.delete') !!}
            </a>
        @endif
        <!-- DELETE BUTTON -->

        <!-- SAVE BUTTON -->
        {!! Form::submit(trans($plang_admin.'.buttons.save'), array("class"=>"btn btn-info pull-right ")) !!}
        <!-- /SAVE BUTTON -->
    </div>
    <!--/BUTTONS-->

    <!--TAB MENU-->
    <ul class="nav nav-tabs">
        <!--MENU 1-->
        <li class="active">
            <a data-toggle="tab" href="#menu_1">
                {!! trans($plang_admin.'.tabs.menu_1') !!}
            </a>
        </li>

        <!--MENU 2-->
        <li>
            <a data-toggle="tab" href="#menu_2">
                {!! trans($plang_admin.'.tabs.menu_2') !!}
            </a>
        </li>

        <!--MENU 3-->
        <li>
            <a data-toggle="tab" href="#menu_3">
                {!! trans($plang_admin.'.tabs.menu_3') !!}
            </a>
        </li>
    </ul>
    <!--/TAB MENU-->

    <!--TAB CONTENT-->
    <div class="tab-content">

        <!--MENU 1-->
        <div id="menu_1" class="tab-pane fade in active">

            <!--worklog NAME-->
            @include('package-category::admin.partials.input_text', [
                'name' => 'task_name',
                'label' => trans($plang_admin.'.labels.name'),
                'value' => @$item->task_name,
                'description' => trans($plang_admin.'.descriptions.name'),
                'errors' => $errors,
            ])
            <!--/worklog NAME-->

            <div class="row">

                <!-- LIST OF CATEGORIES -->
                <div class='col-md-6'>
                    @include('package-category::admin.partials.select_single', [
                        'name' => 'category_id',
                        'label' => trans($plang_admin.'.labels.category'),
                        'items' => $categories,
                        'value' => @$item->category_id,
                        'description' => trans($plang_admin.'.descriptions.category', [
                                            'href' => URL::route('categories.list', ['_key' => $context->context_key])
                                            ]),
                        'errors' => $errors,
                    ])
                </div>
                <!-- /LIST OF CATEGORIES -->

                <!--STATUS-->
                <div class='col-md-6'>

                    @include('package-category::admin.partials.radio', [
                        'name' => 'task_status',
                        'label' => trans($plang_admin.'.labels.worklog-status'),
                        'value' => @$item->task_status,
                        'description' => trans($plang_admin.'.descriptions.worklog-status'),
                        'items' => $statuses,
                    ])
                </div>
                <!--/STATUS-->

                <!--TASK_ID-->
                <div class='col-md-6'>
                    @include('package-category::admin.partials.input_text', [
                        'name' => 'redmine_id',
                        'label' => trans($plang_admin.'.labels.task_id'),
                        'value' => @$item->task_id,
                        'description' => trans($plang_admin.'.descriptions.task_id'),
                        'errors' => $errors,
                    ])
                </div>
                <!--/TASK_ID-->

                <!--TASK_URL-->
                <div class='col-md-6'>
                    @include('package-category::admin.partials.input_text', [
                        'name' => 'redmine_url',
                        'label' => trans($plang_admin.'.labels.task_url'),
                        'value' => @$item->redmine_url,
                        'description' => trans($plang_admin.'.descriptions.task_url'),
                        'errors' => $errors,
                    ])
                </div>
                <!--/TASK_URL-->

            </div>
             <!--worklog FILES-->
            @include('package-category::admin.partials.input_files', [
                'name' => 'files',
                'label' => trans($plang_admin.'.labels.files'),
                'value' => @$item->task_files,
                'description' => trans($plang_admin.'.descriptions.files'),
                'errors' => $errors,
            ])
            <!--/worklog FILES-->
        </div>

        <!--MENU 2-->
        <div id="menu_2" class="tab-pane fade">
            <div class="row">
            <!--worklog OVERVIEW-->
            @include('package-category::admin.partials.textarea', [
                'name' => 'task_overview',
                'label' => trans($plang_admin.'.labels.overview'),
                'value' => @$item->task_overview,
                'description' => trans($plang_admin.'.descriptions.overview'),
                'tinymce' => false,
                'errors' => $errors,
            ])
            <!--/worklog OVERVIEW-->

            <!--worklog DESCRIPTION-->
            @include('package-category::admin.partials.textarea', [
                'name' => 'task_description',
                'label' => trans($plang_admin.'.labels.description'),
                'value' => @$item->task_description,
                'description' => trans($plang_admin.'.descriptions.description'),
                'rows' => 50,
                'tinymce' => true,
                'errors' => $errors,
            ])
            <!--/worklog DESCRIPTION-->
            </div>
        </div>

        <!--MENU 3-->
        <div id="menu_3" class="tab-pane fade">
            <div class="row">
            <!--worklog IMAGE-->
            @include('package-category::admin.partials.input_image', [
                'name' => 'task_image',
                'label' => trans($plang_admin.'.labels.image'),
                'value' => @$item->task_image,
                'description' => trans($plang_admin.'.descriptions.image'),
                'errors' => $errors,
                'lfm_config' => false,
            ])
            <!--/worklog IMAGE-->
            </div>
        </div>

    </div>
    <!--/TAB CONTENT-->

    <!--HIDDEN FIELDS-->
    <div class='hidden-field'>
        {!! Form::hidden('id',@$item->id) !!}
        {!! Form::hidden('context',$request->get('context',null)) !!}
    </div>
    <!--/HIDDEN FIELDS-->

{!! Form::close() !!}
<!------------------------------------------------------------------------------
| End list of elements in worklog form
|------------------------------------------------------------------------------>