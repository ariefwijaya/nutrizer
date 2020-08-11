<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . 'core/MY_Aggrid_Model.php');
class Grid_model extends MY_Aggrid_Model
{
    function initiateUserData($request)
    {
        $this->setRequest($request);
        $columnList = array(
            array(
                'key' => 'action',
                'type' => 'custom',
                'colDef' => array(
                    'width' => 100,
                    'cellRenderer' => 'actionCellRenderer',
                    'resizable'=>false
                )
            ),
              array(
                'key' => 'id',
                'type' => 'number'
            ),
            array(
                'key' => 'username',
                'type' => 'text'
            ),
            array(
                'key' => 'nickname',
                'type' => 'text'
            ),
            array(
                'key' => 'gender',
                'type' => 'set',
            ),
            array(
                'key' => 'height',
                'type' => 'number'
            ),
              array(
                'key' => 'weight',
                'type' => 'number'
            ),
            array(
                'key' => 'birthday',
                'type' => 'date'
            ),
            array(
                'key' => 'email',
                'type' => 'text'
            ),
            // array(
            //     'key' => 'avatar_img',
            //     'type' => 'text',
            //     'colDef' => array(
            //         'cellRenderer' => 'thumbImageRenderer',
            //         'filter' => 'agTextColumnFilter',
            //         'enablePivot' => false,
            //         'enableRowGroup' => false,
            //         'enableValue' => false
            //     )
            // ),
            array(
                'key' => 'lastpw_time',
                'type' => 'date',
            ),
            array(
                'key' => 'islocked',
                'type' => 'set',
                'colDef' => array(
                    'cellRenderer' => 'booleanCellRenderer'
                )
            ),
              array(
                'key' => 'lastlogin_id',
                'type' => 'text'
            ),
            array(
                'key' => 'lastlogin_from',
                'type' => 'set'
            ),
            array(
                'key' => 'lastlogin_time',
                'type' => 'date'
            ),
            array(
                'key' => 'idt',
                'type' => 'date',
                'colDef'=>array('headerName'=>"INSERT DATE")
            ),
            array(
                'key' => 'iby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'INSERT BY')
            ),
            array(
                'key' => 'udt',
                'type' => 'date',
                'colDef'=>array('headerName'=>'UPDATE DATE')
            ),
            array(
                'key' => 'uby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'UPDATE BY')
            ),
        );
        
        $this->setCustomQuery(function ($curDB) {
            $curDB->where('isdeleted', 0);
        });
        $this->setdefaultColId("id");
        $this->setDB($this->db);
        $this->setColumnList($columnList);
        $this->setTableName("tbl_user","a");
    }


    function initiateKekData($request)
    {
        $this->setRequest($request);
        $columnList = array(
            array(
                'key' => 'action',
                'type' => 'custom',
                'colDef' => array(
                    'width' => 100,
                    'cellRenderer' => 'actionCellRenderer',
                    'resizable'=>false
                )
            ),
            array(
                'key' => 'id',
                'type' => 'number',
            ),
            array(
                'key' => 'title',
                'type' => 'text'
            ),
            array(
                'key' => 'subtitle',
                'type' => 'text'
            ),
            array(
                'key' => 'content',
                'type' => 'text',
                // 'colDef' => array(
                //     'cellRenderer' => 'contentRenderer',
                //     'filter' => 'agTextColumnFilter',
                //     'enablePivot' => false,
                //     'enableRowGroup' => false,
                //     'enableValue' => false
                // )
            ),
            array(
                'key' => 'idt',
                'type' => 'date',
                'colDef'=>array('headerName'=>"INSERT DATE")
            ),
            array(
                'key' => 'iby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'INSERT BY')
            ),
            array(
                'key' => 'udt',
                'type' => 'date',
                'colDef'=>array('headerName'=>'UPDATE DATE')
            ),
            array(
                'key' => 'uby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'UPDATE BY')
            ),
        );
        $this->setCustomQuery(function ($curDB) {
            $curDB->where('isdeleted', 0);
        });
        $this->setdefaultColId("a.id");
        $this->setDB($this->db);
        $this->setColumnList($columnList);
        $this->setTableName("tbl_kek","a");
    }


    function initiateNutritionData($request)
    {
        $this->setRequest($request);
        $columnList = array(
            array(
                'key' => 'action',
                'type' => 'custom',
                'colDef' => array(
                    'width' => 150,
                    'cellRenderer' => 'actionCellRenderer',
                    'resizable'=>false
                )
            ),
            array(
                'key' => 'id',
                'type' => 'number',
            ),
            array(
                'key' => 'name',
                'type' => 'text'
            ),
            array(
                'key' => 'order_pos',
                'type' => 'number'
            ),
            array(
                'key' => 'food_category_total',
                'type' => 'number',
                'query'=>'(a.food_cat_count)'
            ),
            array(
                'key' => 'image',
                'type' => 'text',
                'colDef' => array(
                    'cellRenderer' => 'thumbImageRenderer',
                    'filter' => 'agTextColumnFilter',
                    'enablePivot' => false,
                    'enableRowGroup' => false,
                    'enableValue' => false
                )
            ),
            array(
                'key' => 'idt',
                'type' => 'date',
                'colDef'=>array('headerName'=>"INSERT DATE")
            ),
            array(
                'key' => 'iby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'INSERT BY')
            ),
            array(
                'key' => 'udt',
                'type' => 'date',
                'colDef'=>array('headerName'=>'UPDATE DATE')
            ),
            array(
                'key' => 'uby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'UPDATE BY')
            ),
        );
        $this->setCustomQuery(function ($curDB) {
            $curDB->where('isdeleted', 0);
        });
        $this->setdefaultColId("a.id");
        $this->setDB($this->db);
        $this->setColumnList($columnList);
        $this->setTableName("tbl_nutrition_type","a");
    }

    function initiateFoodCategoryData($request)
    {
        $this->setRequest($request);
        $columnList = array(
            array(
                'key' => 'action',
                'type' => 'custom',
                'colDef' => array(
                    'width' => 150,
                    'cellRenderer' => 'actionCellRenderer',
                    'resizable'=>false
                )
            ),
            array(
                'key' => 'id',
                'type' => 'number',
            ),
            array(
                'key' => 'name',
                'type' => 'text'
            ),
            array(
                'key' => 'order_pos',
                'type' => 'number'
            ),
            array(
                'key' => 'food_total',
                'type' => 'number',
                'query'=>'(a.food_count)'
            ),
            array(
                'key' => 'image',
                'type' => 'text',
                'colDef' => array(
                    'cellRenderer' => 'thumbImageRenderer',
                    'filter' => 'agTextColumnFilter',
                    'enablePivot' => false,
                    'enableRowGroup' => false,
                    'enableValue' => false
                )
            ),
            array(
                'key' => 'idt',
                'type' => 'date',
                'colDef'=>array('headerName'=>"INSERT DATE")
            ),
            array(
                'key' => 'iby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'INSERT BY')
            ),
            array(
                'key' => 'udt',
                'type' => 'date',
                'colDef'=>array('headerName'=>'UPDATE DATE')
            ),
            array(
                'key' => 'uby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'UPDATE BY')
            ),
        );
        $this->setCustomQuery(function ($curDB) {
            $curDB->where('isdeleted', 0);
        });
        $this->setdefaultColId("a.id");
        $this->setDB($this->db);
        $this->setColumnList($columnList);
        $this->setTableName("tbl_food_category","a");
    }

    function initiateFoodData($request)
    {
        $this->setRequest($request);
        $columnList = array(
            array(
                'key' => 'action',
                'type' => 'custom',
                'colDef' => array(
                    'width' => 150,
                    'cellRenderer' => 'actionCellRenderer',
                    'resizable'=>false
                )
            ),
            array(
                'key' => 'id',
                'type' => 'number',
            ),
            array(
                'key' => 'name',
                'type' => 'text'
            ),
            array(
                'key' => 'order_pos',
                'type' => 'number'
            ),
            array(
                'key' => 'kkal',
                'type' => 'number',
            ),
            array(
                'key' => 'idt',
                'type' => 'date',
                'colDef'=>array('headerName'=>"INSERT DATE")
            ),
            array(
                'key' => 'iby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'INSERT BY')
            ),
            array(
                'key' => 'udt',
                'type' => 'date',
                'colDef'=>array('headerName'=>'UPDATE DATE')
            ),
            array(
                'key' => 'uby',
                'type' => 'text',
                'colDef'=>array('headerName'=>'UPDATE BY')
            ),
        );
        $this->setCustomQuery(function ($curDB) {
            $curDB->where('isdeleted', 0);
        });
        $this->setdefaultColId("a.id");
        $this->setDB($this->db);
        $this->setColumnList($columnList);
        $this->setTableName("tbl_food","a");
    }

}
                        
/* End of file user_grid_model.php */
