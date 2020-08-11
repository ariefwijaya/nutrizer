<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Aggrid_Model extends CI_Model
{
    private $request;
    private $tableName;
    private $columnList;
    private $columnListDef;
    private $colFilterLike;
    /**To Grouping,  Uniquely Needed */
    private $defaultColId;
    private $CurDB;
    private $customQueryCallback;
    private $joinTableArray;
    private $tableAliasDelimiter;
    private $subQueryDelimiter;

    public function __construct()
    {
        parent::__construct();

        $this->colFilterLike = array();
        $this->columnListDef = array();
        $this->columnList = array();
        $this->defaultColId = "id";
        $this->CurDB = (!empty($this->db))?$this->db:null;
        $this->joinTableArray = array();
        $this->tableName = '';
        $this->tableAliasDelimiter = "-";
        $this->subQueryDelimiter = " {AS} ";
    }

    function setDB($DB)
    {
        $this->CurDB = $DB;
    }

    function setdefaultColId($defaultColId)
    {
        $this->defaultColId = $defaultColId;
    }

    function getSubQueryDelimiter()
    {
        return $this->subQueryDelimiter;
    }

    function setColumnList($columns)
    {
        $this->columnListDef = $columns;
        $columnArr = [];
        $columnLike = [];
        for ($i = 0; $i < count($columns); $i++) {
            $item = $columns[$i];
            if ($item['type'] != "custom") {
                $columnArr[] = $item['key'];
            }

            if (isset($item['filterLike']) && $item['filterLike'] == TRUE) {
                $columnLike[] = $item['key'];
            }
        }
        $this->columnList = $columnArr;
        $this->colFilterLike = $columnLike;
    }

    function setColFilterLike($columns)
    {
        $this->colFilterLike = $columns;
    }

    function setRequestJsonDecode($request)
    {
        $this->request = json_decode($request);
    }
    function setRequest($request)
    {
        $this->request = ($request);
    }

    function setTableName($tableName, $alias)
    {
        $this->tableName = $tableName . " AS " . $alias;
    }

    function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Join to another table
     *  
     * Each array in array contain:
     *
     *     @param string  table Table Name 
     *     @param string  tableAlias Table Alias
     *     @param string cond Join On Condition
     *     @param string  type Join Type (LEFT,RIGHT,OUTTER,INNER)
     *     @param bool  escape Escape Query (Default: TRUE)
     *     
     */
    function setJoinTable(array $joinTableArray)
    {
        $this->joinTableArray = $joinTableArray;
    }

    /**
     * Join to another table
     *  
     * Add array Param to join:
     *
     *     @param string  table Table Name 
     *     @param string  tableAlias Table Alias 
     *     @param string cond Join On Condition
     *     @param string  type Join Type (LEFT,RIGHT,OUTTER,INNER)
     *     @param bool  escape Escape Query (Default: TRUE)
     *     
     */
    function addJoinTable(array $joinTableArray)
    {
        $this->joinTableArray[] = $joinTableArray;
    }

    function getJoinTable($idx = null)
    {
        return is_null($idx) ? $this->joinTableArray : $this->joinTableArray[$idx];
    }

    public function getColumnList()
    {
        return $this->columnList;
    }

    public function setCustomQuery($customQueryCallback_)
    {
        $this->customQueryCallback = $customQueryCallback_;
    }

    private function _getDataQuery()
    {
        $request = $this->request;
        $columnArray = $this->columnListDef;

        //Grouping Active dan belum dibuka
        $colGroup = array();
        $totalGroupkey = count($request->groupKeys);
        $fieldGroup = array();
        if ($totalGroupkey > 0) {
            $this->CurDB->group_start();
            $groupKeys = $request->groupKeys;
            for ($i = 0; $i < $totalGroupkey; $i++) {

                if($groupKeys[$i] == "(Blanks)"){
                    $this->CurDB->where($this->unmaskTableAliasDelimiter($request->rowGroupCols[$i]->field) . " IS NULL ");    
                }else{
                    $this->CurDB->where($this->unmaskTableAliasDelimiter($request->rowGroupCols[$i]->field) . " = '".$groupKeys[$i]."'",NULL,FALSE);
                }
                

                //For ID Group Level
                $fieldGroup[] = $this->unmaskTableAliasDelimiter($request->rowGroupCols[$i]->field);
            }
            $this->CurDB->group_end();
        } else if (count($request->rowGroupCols) > 0) {
            $fieldName = $request->rowGroupCols[0]->field;
            $fieldGroup[] = "(IFNULL(" . $this->unmaskTableAliasDelimiter($fieldName) . "," . $this->defaultColId . "))";
        }

        if ((count($request->rowGroupCols) == $totalGroupkey) && !$request->pivotMode) {
            for ($i = 0; $i < count($columnArray); $i++) {
                $column = $columnArray[$i];

                if ($column['type'] == "custom") continue;

                $columnKey = $column['key'];
                // $tableAlias = isset($column['tableAlias']) ? ($column['tableAlias'].'.') : '';
                $this->CurDB->select($this->unmaskTableAliasDelimiter($columnKey) . " AS " . $this->escapeColumnName($columnKey));
            }
        } else if (count($request->rowGroupCols) > 0) {
            $rowGroupCols = $request->rowGroupCols[$totalGroupkey];
            $field = $rowGroupCols->field;
            $colGroup[] = "CONCAT(" . implode(',"-",', $fieldGroup) . ") AS " .  $this->escapeColumnName($this->defaultColId);
            $colGroup[] = "IFNULL(" .  $this->unmaskTableAliasDelimiter($field) . ",'(Blanks)') as " .  $this->escapeColumnName($field);
            $colGroup[] = "COUNT(IFNULL(" .  $this->unmaskTableAliasDelimiter($field) . "," . $this->defaultColId . ")) AS childCount";
            $this->CurDB->select($colGroup);
            $this->CurDB->group_by($this->unmaskTableAliasDelimiter($field));
            $this->_getAggFunc($request->valueCols);
        } else if (count($request->valueCols) > 0) {
            $this->_getAggFunc($request->valueCols);
        } else {
            $this->CurDB->select($this->defaultColId);
        }

        if (count($request->sortModel) > 0) {
            foreach ($request->sortModel as $item) // looping awal
            {
                $colId = $item->colId;
                $sort = $item->sort;
                $this->CurDB->order_by($this->unmaskTableAliasDelimiter($colId), $sort,FALSE);
            }
        } else {
            // $this->CurDB->order_by("post_date","DESC");
        }

        $quickFilterVal = $request->quickFilter;
        if ($quickFilterVal != "" && !empty($quickFilterVal)) {
            $this->CurDB->group_start();
            for ($i = 0; $i < count($columnArray); $i++) {
                $column = $columnArray[$i];
                // if($value==$this->defaultColId) continue;
                if ($column['type'] == "custom") continue;
                else if ($column['type'] == "date") {
                    $valueCast = $this->unmaskTableAliasDelimiter($column['key']);
                } else {
                    $valueCast = "CAST(" . $this->unmaskTableAliasDelimiter($column['key']) . " as CHAR)";
                }

                if ($i == 0) {
                    // if (array_search($column, $this->colFilterLike) !== FALSE) {
                    //     $this->CurDB->like($valueCast, $quickFilterVal);
                    // }

                    $splitFilter = explode(";", $quickFilterVal);
                    if (is_array($splitFilter)) {
                        for ($j = 0; $j < count($splitFilter); $j++) {
                            $filterValue = $splitFilter[$j];
                            if($column['type']=="date" && !$this->validateDate($filterValue)){
                                continue;
                            }
                            if (strpos($filterValue, '%') !== false) {
                                $this->CurDB->where($valueCast . " LIKE '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 1) == ">") {
                                $this->CurDB->where($valueCast . " > '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 1) == "<") {
                                $this->CurDB->where($valueCast . " < '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == ">=") {
                                $this->CurDB->where($valueCast . " >= '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "<=") {
                                $this->CurDB->where($valueCast . " <= '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "<>") {
                                $this->CurDB->where($valueCast . " != '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "!%") {
                                $this->CurDB->where($valueCast . " IS NULL ");
                            } else {
                                $this->CurDB->where($valueCast . " = '" . $filterValue . "'");
                            }
                        }
                    }
                } else {
                    $splitFilter = explode(";", $quickFilterVal);
                    if (is_array($splitFilter)) {
                        for ($j = 0; $j < count($splitFilter); $j++) {
                            $filterValue = $splitFilter[$j];
                            if($column['type']=="date" && !$this->validateDate($filterValue)){
                                continue;
                            }
                            if (strpos($filterValue, '%') !== false) {
                                $this->CurDB->or_where($valueCast . " LIKE '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 1) == ">") {
                                $this->CurDB->or_where($valueCast . " > '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 1) == "<") {
                                $this->CurDB->or_where($valueCast . " < '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == ">=") {
                                $this->CurDB->or_where($valueCast . " >= '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "<=") {
                                $this->CurDB->or_where($valueCast . " <= '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "<>") {
                                $this->CurDB->or_where($valueCast . " != '" . $filterValue . "'");
                            } else if (substr($filterValue, 0, 2) == "!%") {
                                $this->CurDB->or_where($valueCast . " IS NULL ");
                            } else {
                                $this->CurDB->or_where($valueCast . " = '" . $filterValue . "'");
                            }
                        }
                    }
                }
            }
            $this->CurDB->group_end();
        }

        foreach ($request->filterModel as $key => $item) // looping awal
        {
            if (isset($item->filterType)) {
                $filterType = $item->filterType;
                if ($filterType == "set") {
                    if (count($item->values) > 0) {
                        $this->CurDB->group_start();
                        $valueIn = $item->values;

                        $idxVal = array_search("(Blanks)", $valueIn, TRUE);
                        //remove null from array;
                        if ($idxVal !== FALSE) {
                            unset($valueIn[$idxVal]);
                            $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . ' IS NULL');
                        }

                        if (count($valueIn) > 0) {
                            $chunk = 300;
                            $array_chunk = array_chunk($valueIn, $chunk);
                            for ($i = 0; $i < count($array_chunk); $i++) {
                                $sliceValueIn = $array_chunk[$i];
                                if ($i = 0) {
                                    // $this->CurDB->where_in($this->unmaskTableAliasDelimiter($key), $sliceValueIn,FALSE);
                                    $this->CurDB->where($this->unmaskTableAliasDelimiter($key)." in (".$this->CurDB->escape(join(",",$sliceValueIn)).")",NULL,FALSE); 
                                } else {
                                    $this->CurDB->or_where($this->unmaskTableAliasDelimiter($key)." in (".$this->CurDB->escape(join(",",$sliceValueIn)).")",NULL,FALSE); 
                                    // $this->CurDB->or_where_in($this->unmaskTableAliasDelimiter($key), $sliceValueIn,FALSE);
                                }
                            }
                        }
                        $this->CurDB->group_end();
                    } else {
                        //simulate unselect all
                        $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " = 'azxyewqkdasydas'", NULL, FALSE);
                    }
                } else if ($filterType == "number") {
                    if ($item->type == "inRange") {
                        $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN " . $item->filter . " AND " . $item->filterTo . ")", NULL, FALSE);
                    } else {
                        $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($item->type)." ".$item->filter, NULL, FALSE);
                    }
                } else if ($filterType == "date") {
                    if ($item->type == "inRange") {
                        $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN CAST('" . $item->dateFrom . "' AS DATE) AND CAST('" . $item->dateTo . "' AS DATE))");
                    } else {
                        $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($item->type) . " CAST('" . $item->dateFrom . "' AS DATE)", NULL, FALSE);
                    }
                } else if ($filterType == "text") {
                    $this->_filterText($item->type,  $this->unmaskTableAliasDelimiter($key), $item->filter);
                }
            } else if (isset($item->operator)) {
                $this->CurDB->group_start();
                $operator = $item->operator;
                $condition1 = $item->condition1;
                $condition2 = $item->condition2;

                if ($condition1->filterType == "number") {
                    if ($condition1->type == "inRange") {
                        $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN " . $condition1->filter . " AND " . $condition1->filterTo . ")", NULL, FALSE);
                    } else {
                        $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition1->type)." ".$condition1->filter, NULL, FALSE);
                    }
                } else if ($condition1->filterType == "date") {
                    if ($condition1->type == "inRange") {
                        $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN CAST('" . $condition1->dateFrom . "' AS DATE) AND CAST('" . $condition1->dateTo . "' AS DATE))");
                    } else {
                        $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition1->type) . " CAST('" . $condition1->dateFrom . "' AS DATE)", NULL, FALSE);
                    }
                } else if ($condition1->filterType == "text") {
                    $this->_filterText($condition1->type, $this->unmaskTableAliasDelimiter($key), $condition1->filter);
                }

                if ($condition2->filterType == "number") {
                    if ($condition2->type == "inRange") {
                        if ($operator == 'AND') {
                            $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN " . $condition2->filter . " AND " . $condition2->filterTo . ")", NULL, FALSE);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN " . $condition2->filter . " AND " . $condition2->filterTo . ")", NULL, FALSE);
                        }
                    } else {
                        if ($operator == 'AND') {
                            $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition2->type)." " .$condition2->filter, NULL, FALSE);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition2->type)." ".$condition2->filter, NULL, FALSE);
                        }
                    }
                } else  if ($condition2->filterType == "date") {
                    if ($condition2->type == "inRange") {
                        if ($operator == 'AND') {
                            $this->CurDB->where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN CAST('" . $condition2->dateFrom . "' AS DATE) AND CAST('" . $condition2->dateTo . "' AS DATE))");
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where("(" . $this->unmaskTableAliasDelimiter($key) . " BETWEEN CAST('" . $condition2->dateFrom . "' AS DATE) AND CAST('" . $condition2->dateTo . "' AS DATE))");
                        }
                    } else {
                        if ($operator == 'AND') {
                            $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition2->type) . " CAST('" . $condition2->dateFrom . "' AS DATE)", NULL, FALSE);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where($this->unmaskTableAliasDelimiter($key) . " " . $this->getConditionSqlByName($condition2->type) . " CAST('" . $condition2->dateFrom . "' AS DATE)", NULL, FALSE);
                        }
                    }
                } else if ($condition2->filterType == "text") {
                    if ($condition2->type == "equals") {
                        if ($operator == 'AND') {
                            $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " = ", $condition2->filter);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where($this->unmaskTableAliasDelimiter($key) . " = ", $condition2->filter);
                        }
                    } else if ($condition2->type == "notEqual") {
                        if ($operator == 'AND') {
                            $this->CurDB->where($this->unmaskTableAliasDelimiter($key) . " != ", $condition2->filter);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_where($this->unmaskTableAliasDelimiter($key) . " != ", $condition2->filter);
                        }
                    } else if ($condition2->type == "startsWith") {
                        if ($operator == 'AND') {
                            $this->CurDB->like($this->unmaskTableAliasDelimiter($key), $condition2->filter, 'after');
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_like($this->unmaskTableAliasDelimiter($key), $condition2->filter, 'after');
                        }
                    } else if ($condition2->type == "endsWith") {
                        if ($operator == 'AND') {
                            $this->CurDB->like($this->unmaskTableAliasDelimiter($key), $condition2->filter, 'before');
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_like($this->unmaskTableAliasDelimiter($key), $condition2->filter, 'before');
                        }
                    } else if ($condition2->type == "contains") {
                        if ($operator == 'AND') {
                            $this->CurDB->like($this->unmaskTableAliasDelimiter($key), $condition2->filter);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_like($this->unmaskTableAliasDelimiter($key), $condition2->filter);
                        }
                    } else if ($condition2->type == "notContains") {
                        if ($operator == 'AND') {
                            $this->CurDB->not_like($this->unmaskTableAliasDelimiter($key), $condition2->filter);
                        } else if ($operator == 'OR') {
                            $this->CurDB->or_not_like($this->unmaskTableAliasDelimiter($key), $condition2->filter);
                        }
                    }
                }

                $this->CurDB->group_end();
            }
        }

        $this->CurDB->from($this->tableName);
        for ($i = 0; $i < count($this->joinTableArray); $i++) {
            $joinElement = $this->joinTableArray[$i];
            $joinTable = $joinElement['table'];
            $tableAlias = isset($joinElement['tableAlias']) ? $joinElement['tableAlias'] : NULL;
            $joinCond = $joinElement['cond'];
            $joinType = isset($joinElement['type']) ? $joinElement['type'] : '';
            $joinEscape = isset($joinElement['escape']) ? $joinElement['escape'] : true;
            $this->CurDB->join($joinTable . (is_null($tableAlias) ? '' : ' AS ' . $tableAlias), $joinCond, $joinType, $joinEscape);
        }
    }

    private function _filterText($itemType, $key, $itemFilter)
    {
        if ($itemType == "equals") {
            $this->CurDB->where($key . " = ", $itemFilter);
        } else if ($itemType == "notEqual") {
            $this->CurDB->where($key . " != ", $itemFilter);
        } else if ($itemType == "startsWith") {
            $this->CurDB->like($key, $itemFilter, 'after');
        } else if ($itemType == "endsWith") {
            $this->CurDB->like($key, $itemFilter, 'before');
        } else if ($itemType == "contains") {
            $this->CurDB->like($key, $itemFilter);
        } else if ($itemType == "notContains") {
            $this->CurDB->not_like($key, $itemFilter);
        }
    }

    private function _getAggFunc($valueCols)
    {
        foreach ($valueCols as $key => $value) {
            $fieldName =  $value->field;
            $aggFunc = $value->aggFunc;

            if ($aggFunc == "count") {
                $this->CurDB->select("count(" . $this->unmaskTableAliasDelimiter($fieldName) . ") AS " . $this->escapeColumnName($fieldName));
            } else if ($aggFunc == "countDistinct") {
                $this->CurDB->select("count(DISTINCT(" . $this->unmaskTableAliasDelimiter($fieldName) . ")) AS " .  $this->escapeColumnName($fieldName));
            } else if ($aggFunc == "sum") {
                $this->CurDB->select_sum($this->unmaskTableAliasDelimiter($fieldName), $this->escapeColumnName($fieldName));
            } else if ($aggFunc == "min") {
                $this->CurDB->select_min($this->unmaskTableAliasDelimiter($fieldName), $this->escapeColumnName($fieldName));
            } else if ($aggFunc == "max") {
                $this->CurDB->select_max($this->unmaskTableAliasDelimiter($fieldName), $this->escapeColumnName($fieldName));
            } else if ($aggFunc == "avg") {
                $this->CurDB->select_avg($this->unmaskTableAliasDelimiter($fieldName), $this->escapeColumnName($fieldName));
            }
        }
    }

    function getDataRows()
    {
        $request = $this->request;
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        $this->CurDB->limit($request->endRow - $request->startRow,  $request->startRow);
        $query = $this->CurDB->get();
        return $query->result_array();
    }


    function getFullDataRows()
    {
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        $query = $this->CurDB->get();
        return $query->result_array();
    }

    function getFullDataColumns($removeAlias=false)
    {
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        $this->CurDB->limit(1);
        $query = $this->CurDB->get();
        $list = $query->list_fields();

        if($removeAlias){
            for ($i=0; $i <count($list) ; $i++) { 
                $list[$i] = $this->removeTableAliasColumn($list[$i]);
            }
        }
        return $list;
    }


    function getTotalRows()
    {
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        $compiledQuery = $this->CurDB->get_compiled_select();
        return $this->CurDB->count_all_results("(" . $compiledQuery . ") AS AGGRID");
    }

    function getDataQuery()
    {
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
    }


    function getCompiledQuery()
    {
        $this->_getDataQuery();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        $compiledQuery = $this->CurDB->get_compiled_select();
        return $compiledQuery;
    }

    function getGroupValue($colId)
    {
        // $this->_getDataQuery();
        // $compiledQuery = $this->CurDB->get_compiled_select();
        if (is_callable($this->customQueryCallback)) {
            $customQueryCallback_ = $this->customQueryCallback;
            $customQueryCallback_($this->CurDB);
        }
        
        $this->CurDB->select("(IFNULL(" . $this->unmaskTableAliasDelimiter($colId) . ",'(Blanks)')) AS " . $this->escapeColumnName($colId));
        $this->CurDB->group_by($this->unmaskTableAliasDelimiter($colId));
        $this->CurDB->from($this->tableName);
        for ($i = 0; $i < count($this->joinTableArray); $i++) {
            $joinElement = $this->joinTableArray[$i];
            $joinTable = $joinElement['table'];
            $tableAlias = isset($joinElement['tableAlias']) ? $joinElement['tableAlias'] : NULL;
            $joinCond = $joinElement['cond'];
            $joinType = isset($joinElement['type']) ? $joinElement['type'] : '';
            $joinEscape = isset($joinElement['escape']) ? $joinElement['escape'] : true;
            $this->CurDB->join($joinTable . (is_null($tableAlias) ? '' : ' AS ' . $tableAlias), $joinCond, $joinType, $joinEscape);
        }
        // $this->CurDB->from("(".$compiledQuery.") AS AGGRID");//$this->tableName);
        $query = $this->CurDB->get();
        $result = $query->result_array();
        $this->CurDB->flush_cache();
        return $result;
    }


    private function getColDef()
    {
        $columnDef = array();
        for ($i = 0; $i < count($this->columnListDef); $i++) {
            $itemCol = $this->columnListDef[$i];
            $key = $itemCol['key'];
            if ($key == "id_" || $key == "childCount") {
                continue;
            }

            $colDef =  array(
                "headerName" => $this->getFormattedColumnNameUpper($this->removeTableAliasColumn($key)),
                "field" => $key,
                "colId" => $key,
                "cellClass" => ['borderAll', 'defaultFont', 'p-t-5']
            );
            $colDef["allowedAggFuncs"] = array('count', 'sum', 'min', 'max', 'countDistinct');
            $colDef['resizable'] = true;
            $colDef['sortable'] = true;
            $colDef['filter'] = true;
            $colDef['resizable'] = true;
            $colDef['enableRowGroup'] = true;
            $colDef['enablePivot'] = true;
            $colDef['enableValue'] = true;
            $itemColType = $itemCol['type'];
            $filterParams = [];
            $filterParams["debounceMs"] = 500;
            $filterParams["apply"] = true;
            $filterParams["clearButton"] = true;
            $filterParams["newRowsAction"] = 'keep';
            if ($itemColType == "date") {
                $colDef['cellClass'] = $colDef['cellClass'][] = "dateType";
                $colDef['filter'] = "agDateColumnFilter";
                $filterParams["browserDatePicker"] = true;
            } else if ($itemColType == "number") {
                $colDef['filter'] = "agNumberColumnFilter";
            } else if ($itemColType == "text") {
                $colDef['filter'] = "agTextColumnFilter";
            } else if ($itemColType == "custom") {
                // $colDef['lockVisible'] = true;
                $colDef['enableRowGroup'] = false;
                $colDef['enablePivot'] = false;
                $colDef['filter'] = false;
                $colDef['rowGroup'] = false;
                $colDef['resizable'] = true;
                $colDef['sortable'] = false;
                $colDef['filter'] = false;
                $colDef['enableValue'] = false;
            } else {
                $filterParams["values"] = $this->extract_value($this->getGroupValue($key));
            }
            $colDef["filterParams"] = $filterParams;

            if (isset($itemCol['colDef']) && is_array($itemCol['colDef'])) {
                $itemColDef = $itemCol['colDef'];
                foreach ($itemColDef as $keyParam => $valueParam) {
                    $colDef[$keyParam] = $valueParam;
                }
            }
            array_push($columnDef, $colDef);
        }
        return  $columnDef;
    }

    private function getColDefPivot($columnList)
    {
        $columnDef = array();
        foreach ($columnList as $realKey => $key) {
            if ($key == "id_" || $key == "childCount") {
                continue;
            }

            $colDef =  array(
                "headerName" => $this->getFormattedColumnNameUpper($this->removeTableAliasColumn($key)),
                "field" => $key,
                "colId" => $key,
                "cellClass" => ['borderAll', 'defaultFont', 'p-t-5']
            );

            for ($i = 0; $i < count($this->columnListDef); $i++) {
                $itemCol = $this->columnListDef[$i];
                if ($key == $itemCol['key']) {
                    $colDef["allowedAggFuncs"] = array('count', 'sum', 'min', 'max', 'countDistinct');
                    $colDef['resizable'] = true;
                    $colDef['sortable'] = true;
                    $colDef['filter'] = true;
                    $colDef['resizable'] = true;
                    $colDef['enableRowGroup'] = true;
                    $colDef['enablePivot'] = true;
                    $colDef['enableValue'] = true;

                    $itemColType = $itemCol['type'];
                    $filterParams = [];
                    $filterParams["debounceMs"] = 500;
                    $filterParams["apply"] = true;
                    $filterParams["clearButton"] = true;
                    $filterParams["newRowsAction"] = 'keep';
                    if ($itemColType == "date") {
                        $colDef['cellClass'] = $colDef['cellClass'][] = "dateType";
                        $colDef['filter'] = "agDateColumnFilter";
                        $filterParams["browserDatePicker"] = true;
                    } else if ($itemColType == "number") {
                        $colDef['filter'] = "agNumberColumnFilter";
                    } else if ($itemColType == "text") {
                        $colDef['filter'] = "agTextColumnFilter";
                    } else if ($itemColType == "custom") {
                        // $colDef['lockVisible'] = true;
                        $colDef['enableRowGroup'] = false;
                        $colDef['enablePivot'] = false;
                        $colDef['filter'] = false;
                        $colDef['rowGroup'] = false;
                        $colDef['resizable'] = true;
                        $colDef['sortable'] = false;
                        $colDef['filter'] = false;
                        $colDef['enableValue'] = false;
                    } else {
                        $filterParams["values"] = $this->extract_value($this->getGroupValue($key));
                    }
                    $colDef["filterParams"] = $filterParams;

                    if (isset($itemCol['colDef']) && is_array($itemCol['colDef'])) {
                        $itemColDef = $itemCol['colDef'];
                        foreach ($itemColDef as $keyParam => $valueParam) {
                            $colDef[$keyParam] = $valueParam;
                        }
                    }
                }
            }

            array_push($columnDef, $colDef);
        }

        return  $columnDef;
    }

    public function getGridRows()
    {
        $request = $this->request;
        $pivotMode = $request->pivotMode;
        $valueCols = $request->valueCols;
        $pivotActive = $request->pivotMode && (count($request->pivotCols) > 0 || count($request->valueCols) > 0);

        $dataRows = $this->getDataRows();
        $result = $dataRows;

        $totalRows = $this->getTotalRows();
        $lastRow = $totalRows;

        $columnDef = $this->getColDef();


        if ($pivotActive == TRUE) {
            $newCols = array();
            foreach ($valueCols as $key => $value) {
                $newCols[] = $value->field;
            }
            $secondColumnDef = $this->getColDefPivot($newCols);
        } else if ($pivotMode == TRUE) {
            $secondColumnDef = array();
        } else {
            $secondColumnDef = null;
        }

        return array(
            "rows" => $result,
            "lastRow" => $lastRow,
            "columns" => $columnDef,
            "secondColumns" => $secondColumnDef
        );
    }

    function extract_value($arrayData)
    {
        $data = [];
        foreach ($arrayData as $key => $value) {
            foreach ($value as $keyS => $valueS) {
                array_push($data, $valueS);
            }
        }
        return $data;
    }

    function getFormattedColumnNameUpper($column, $delimiter = "_")
    {
        if (!is_string($column)) return "";
        // $lowerStr = strtolower($column);
        $delimittedStr = str_replace($delimiter, " ", $column);
        $formattedStr = strtoupper($delimittedStr);
        return $formattedStr;
    }

    function escapeColumnName($field)
    {
        return $this->CurDB->escape($field);
    }

    function removeTableAliasColumn($field)
    {
        $newFieldName = strstr($field, $this->tableAliasDelimiter, true);
        if ($newFieldName != FALSE) {
            return substr($field, strlen($newFieldName) + strlen($this->tableAliasDelimiter));
        } else {
            return $field;
        }
    }

    function unmaskTableAliasDelimiter($field)
    {
        //check isSubQuery
        for ($i = 0; $i < count($this->columnListDef); $i++) {
            $element = $this->columnListDef[$i];
            if ($element['key'] == $field && isset($element['query'])) {
                return $element['query'];
            }
        }
        return str_replace($this->tableAliasDelimiter, ".", $field);
    }

    function getConditionSqlByName($name)
    {

        if ($name == "equals") {
            $conOp = "=";
        } else if ($name == "notEqual") {
            $conOp = "!=";
        } else if ($name == "lessThan") {
            $conOp = "<";
        } else if ($name == "lessThanOrEqual") {
            $conOp = "<=";
        } else if ($name == "greaterThan") {
            $conOp = ">";
        } else if ($name == "greaterThanOrEqual") {
            $conOp = ">=";
        } else {
            $conOp = "";
        }

        return $conOp;
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
}
                        
/* End of file M_issue_transaction_dev.php */
