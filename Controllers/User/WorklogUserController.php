<?php

namespace Foostart\Worklog\Controllers\Admin;

use Illuminate\Http\Request;
use Foostart\Worklog\Controllers\Admin\WorklogController;
use URL;
use Route,
    Redirect;
/**
 * Models
 */
use Foostart\Worklog\Models\Worklog;
use Foostart\Pnd\Models\Students;
use Foostart\Worklog\Models\WorklogCategories;
use Foostart\Worklog\Helper\PexcelParse;
/**
 * Validators
 */
use Foostart\Worklog\Validators\WorklogAdminValidator;

class WorklogAdminController extends WorklogController {

    private $obj_Worklog = NULL;
    private $obj_Worklog_categories = NULL;
    private $obj_validator = NULL;

    public function __construct() {

        $this->obj_Worklog = new Worklog();
        $this->obj_Worklog_categories = new WorklogCategories();
    }

    /**
     *
     * @return type
     */
    public function index(Request $request) {

        $this->isAuthentication();

        $params = $request->all();


        $params['user_name'] = $this->current_user->user_name;
        $params['user_id'] = $this->current_user->id;

        /**
         * EXPORT
         */
        if (isset($params['export'])) {
            $worklogs = $this->obj_worklog->get_worklogs($params);
            $obj_parse = new Parse();
            $obj_parse->export_data($worklogs, 'worklogs');

            unset($params['export']);
        }
        ////////////////////////////////////////////////////////////////////////

        $worklogs = $this->obj_worklog->get_worklogs($params);

        $this->data = array_merge($this->data, array(
            'worklogs' => $worklogs,
            'request' => $request,
            'params' => $params
        ));
        return view('worklog::admin.worklog_list', $this->data);
    }

    /**
     *
     * @return type
     */
    public function edit(Request $request) {

        $this->isAuthentication();

        if ($this->current_user) {
            $worklog = NULL;
            $worklog_id = (int) $request->get('id');


            if (!empty($worklog_id) && (is_int($worklog_id))) {
                $worklog = $this->obj_worklog->find($worklog_id);
            }

            if ($this->is_admin || $this->is_all || $this->is_my || ($worklog->user_id == $this->current_user->id)) {
                $this->data = array_merge($this->data, array(
                    'worklog' => $worklog,
                    'request' => $request,
                    'categories' => $this->obj_worklog_categories->pluckSelect()->toArray(),
                ));
                return view('worklog::admin.worklog_edit', $this->data);
            }
        }
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function post(Request $request) {

        $this->isAuthentication();

        $this->obj_validator = new worklogAdminValidator();

        $input = $request->all();

        $input['user_id'] = $this->current_user->id;

        $worklog_id = (int) $request->get('id');

        $worklog = NULL;

        $data = array();

        if (!$this->obj_validator->adminValidate($input)) {

            $data['errors'] = $this->obj_validator->getErrors();

            if (!empty($worklog_id) && is_int($worklog_id)) {
                $worklog = $this->obj_worklog->find($worklog_id);
            }
        } else {

            if (!empty($worklog_id) && is_int($worklog_id)) {

                $worklog = $this->obj_worklog->find($worklog_id);

                if (!empty($worklog)) {

                    $input['worklog_id'] = $worklog_id;
                    $worklog = $this->obj_worklog->update_worklog($input);

                    //Message
                    $this->addFlashMessage('message', trans('worklog::worklog.message_update_successfully'));

                    return Redirect::route("admin_worklog.parse", ["id" => $worklog->worklog_id]);
                } else {

                    //Message
                    $this->addFlashMessage('message', trans('worklog::worklog.message_update_unsuccessfully'));
                }
            } else {

                $input = array_merge($input, array(
                ));

                $worklog = $this->obj_worklog->add_worklog($input);

                if (!empty($worklog)) {

                    //Message
                    $this->addFlashMessage('message', trans('worklog::worklog.message_add_successfully'));

                    return Redirect::route("admin_worklog.parse", ["id" => $worklog->worklog_id]);
                    //return Redirect::route("admin_worklog.edit", ["id" => $worklog->worklog_id]);
                } else {

                    //Message
                    $this->addFlashMessage('message', trans('worklog::worklog.message_add_unsuccessfully'));
                }
            }
        }

        $this->data = array_merge($this->data, array(
            'worklog' => $worklog,
            'request' => $request,
                ), $data);

        return view('worklog::admin.worklog_edit', $this->data);
    }

    /**
     *
     * @return type
     */
    public function delete(Request $request) {

        $this->isAuthentication();


        $worklog = NULL;
        $worklog_id = $request->get('id');


        if (!empty($worklog_id)) {

            $worklog = $this->obj_worklog->find($worklog_id);

            if (!empty($worklog)) {
                //Message
                $this->addFlashMessage('message', trans('worklog::worklog.message_delete_successfully'));

                if ($this->is_admin || $this->is_all || ($worklog->user_id == $this->current_user->id)) {

                    $obj_student = new Students();

                    $obj_student->deleteStudentsByworklogId($worklog->worklog_id);

                    $worklog->delete();
                }
            }
        } else {

        }

        $this->data = array_merge($this->data, array(
            'worklog' => $worklog,
        ));

        return Redirect::route("admin_worklog");
    }

    public function parse(Request $request) {
        $obj_parse = new Parse();
        $obj_students = new Students();

        $input = $request->all();

        $worklog_id = $request->get('id');

        $worklog = $this->obj_worklog->find($worklog_id);

        $worklog_category = $this->obj_worklog_categories->find($worklog->worklog_category_id);

        $worklog->worklog_category_name = $worklog_category->worklog_category_name;

        $students = $obj_parse->read_data($worklog);

        $worklog->worklog_value = json_encode($students);
        unset($worklog->worklog_category_name);
        $worklog->save();

        $config = config('worklog.status_str');

        /**
         * Import data
         */
        $this->data = array_merge($this->data, array(
            'students' => $students,
            'request' => $request,
            'worklog' => $worklog,
            'config' => $config,
        ));

        return view('worklog::admin.worklog_parse', $this->data);
    }

}
