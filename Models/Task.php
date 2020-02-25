<?php

namespace Foostart\Worklog\Models;

use Foostart\Category\Library\Models\FooModel;
use Illuminate\Database\Eloquent\Model;

class Task extends FooModel {

    /**
     * @table categories
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        //set configurations
        $this->setConfigs();

        parent::__construct($attributes);
    }

    public function setConfigs() {

        //table name
        $this->table = 'tasks';

        //list of field in table
        $this->fillable = [
            'task_name',
            'category_id',
            'user_id',
            'redmine_id',
            'redmine_url',
            'task_overview',
            'task_description',
            'task_image',
            'task_files',
            'task_status',
        ];

        //list of fields for inserting
        $this->fields = [
            'task_name' => [
                'name' => 'task_name',
                'type' => 'Text',
            ],
            'category_id' => [
                'name' => 'category_id',
                'type' => 'Int',
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => 'Int',
            ],
            'redmine_id' => [
                'name' => 'redmine_id',
                'type' => 'Text',
            ],
            'redmine_url' => [
                'name' => 'redmine_url',
                'type' => 'Text',
            ],
            'task_overview' => [
                'name' => 'task_overview',
                'type' => 'Text',
            ],
            'task_description' => [
                'name' => 'task_description',
                'type' => 'Text',
            ],
            'task_image' => [
                'name' => 'task_image',
                'type' => 'Text',
            ],
            'task_files' => [
                'name' => 'files',
                'type' => 'Json',
            ],

            'task_status' => [
                 'name' => 'task_status',
                 'type' => 'Int',
            ],

        ];

        //check valid fields for inserting
        $this->valid_insert_fields = [
            'task_name',
            'user_id',
            'category_id',
            'redmine_id',
            'redmine_url',
            'updated_at',
            'task_overview',
            'task_description',
            'task_image',
            'task_files',
            'task_status',
        ];

        //check valid fields for ordering
        $this->valid_ordering_fields = [
            'redmine_id',
            'redmine_url',
            'task_name',
            'updated_at',
            $this->field_status,
        ];
        //check valid fields for filter
        $this->valid_filter_fields = [
            'keyword',
            'status',
            'user_id',
            'task_status',
        ];

        //primary key
        $this->primaryKey = 'task_id';

        //the number of items on page
        $this->perPage = 10;

        //item status
        $this->field_status = 'task_status';
    }

    /**
     * Gest list of items
     * @param type $params
     * @return object list of categories
     */
    public function selectItems($params = array()) {

        //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo);

        //select fields
        $elo = $this->createSelect($elo);

        //order filters
        $elo = $this->orderingFilters($params, $elo);

        //paginate items
        $items = $this->paginateItems($params, $elo);

        return $items;
    }

    /**
     * Get a task by {id}
     * @param ARRAY $params list of parameters
     * @return OBJECT task
     */
    public function selectItem($params = array(), $key = NULL) {


        if (empty($key)) {
            $key = $this->primaryKey;
        }
        //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo, FALSE);

        //select fields
        $elo = $this->createSelect($elo);

        //id
        if (!empty($params['id'])) {
             $elo = $elo->where($this->primaryKey, $params['id']);
        }

        //first item
        $item = $elo->first();

        return $item;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function joinTable(array $params = []) {
        return $this;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function searchFilters(array $params = [], $elo, $by_status = TRUE) {

        //filter
        if ($this->isValidFilters($params) && (!empty($params))) {
            foreach ($params as $column => $value) {
                if ($this->isValidValue($value)) {
                    switch ($column) {
                        case 'task_name':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.task_name', '=', $value);
                            }
                            break;
                        case 'status':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.' . $this->field_status, '=', $value);
                            }
                            break;
                        case 'user_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.user_id', '=', $value);
                            }
                            break;
                        case 'keyword':
                            if (!empty($value)) {
                                $elo = $elo->where(function($elo) use ($value) {
                                    $elo->where($this->table . '.task_name', 'LIKE', "%{$value}%")
                                            ->orWhere($this->table . '.task_description', 'LIKE', "%{$value}%")
                                            ->orWhere($this->table . '.task_overview', 'LIKE', "%{$value}%");
                                });
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return $elo;
    }

    /**
     * Select list of columns in table
     * @param ELOQUENT OBJECT
     * @return ELOQUENT OBJECT
     */
    public function createSelect($elo) {

        $elo = $elo->select($this->table . '.*', $this->table . '.task_id as id'
        );

        return $elo;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    public function paginateItems(array $params = [], $elo) {
        $items = $elo->paginate($this->perPage);

        return $items;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @param INT $id is primary key
     * @return type
     */
    public function updateItem($params = [], $id = NULL) {

        if (empty($id)) {
            $id = $params['id'];
        }
        $field_status = $this->field_status;

        $task = $this->selectItem($params);

        if (!empty($task)) {
            $dataFields = $this->getDataFields($params, $this->fields);

            foreach ($dataFields as $key => $value) {
                $task->$key = $value;
            }

            //$task->$field_status = $this->status['publish'];

            $task->save();

            return $task;
        } else {
            return NULL;
        }
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return OBJECT task
     */
    public function insertItem($params = []) {

        $dataFields = $this->getDataFields($params, $this->fields);

        //$dataFields[$this->field_status] = $this->status['publish'];


        $item = self::create($dataFields);

        $key = $this->primaryKey;
        $item->id = $item->$key;

        return $item;
    }

    /**
     *
     * @param ARRAY $input list of parameters
     * @return boolean TRUE incase delete successfully otherwise return FALSE
     */
    public function deleteItem($input = [], $delete_type) {

        $item = $this->find($input['id']);

        if ($item) {
            switch ($delete_type) {
                case 'delete-trash':
                    return $item->fdelete($item);
                    break;
                case 'delete-forever':
                    return $item->delete();
                    break;
            }
        }

        return FALSE;
    }

    /**
     *
     * Get list of statuses to push to select
     * @return ARRAY list of statuses
     */

     public function getPluckStatus() {
            $pluck_status = config('package-worklog.status.list');
            return $pluck_status;
     }


     public function getCheckedRules($task_id) {
         $checked_rules = NULL;

         $checked_rules = self::from('checked_rules')
                                ->select('posts.*')
                                ->join('posts','posts.post_id', '=', 'checked_rules.post_id')
                                ->where('task_id','=', $task_id)
                                ->get();
         return $checked_rules;
     }
}
