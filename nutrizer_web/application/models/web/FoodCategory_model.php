<?php

defined('BASEPATH') or exit('No direct script access allowed');

class FoodCategory_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->insert('tbl_food_category', $data);
        return $this->db->affected_rows() > 0;
    }

    public function insertFood($data)
    {
        $this->db->insert('tbl_food_cat', $data);
        
        if($this->db->affected_rows() > 0){
            $this->db->set('food_count', 'food_count+1', FALSE);
            $this->db->where("id", $data['food_cat_id']);
            $this->db->update("tbl_food_category");
            return $this->db->affected_rows() > 0;
        }
        else{
            return false;
        }
    }

    public function deleteFood($foodCatId,$foodId)
    {
        $this->db->where('food_cat_id', $foodCatId);
        $this->db->where('food_id', $foodId);
        $this->db->delete('tbl_food_cat');
        if($this->db->affected_rows() > 0){
            $this->db->set('food_count', 'food_count-1', FALSE);
            $this->db->where("id", $foodCatId);
            $this->db->update("tbl_food_category");
            return $this->db->affected_rows() > 0;
        }
        else{
            return false;
        }
    }


    public function isFoodExistCat($foodCatId,$foodId)
    {
        $this->db->where('food_cat_id', $foodCatId);
        $this->db->where('food_id', $foodId);
        $query = $this->db->get('tbl_food_cat a');
        return !empty($query->row_array());
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_food_category', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_food_category');
        return $this->db->affected_rows() > 0;
    }

    public function getById($id)
    {
        $this->db->select('id,name,image,order_pos');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_food_category a');
        $result = $query->row_array();
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getListData($limit = 10, $offset = 0,  $search = null)
    {
        $this->db->select("id,name text, image,order_pos");
        $this->db->from("tbl_food_category a");
        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.name', $search);
            // $this->db->or_like('a.agency', $search);
            $this->db->group_end();
        }
        $this->db->where('isdeleted', 0);

        $this->db->limit(empty($limit) ? 10 : $limit);
        $this->db->offset(empty($offset) ? 0 : $offset);

        $query = $this->db->get();
        return $query->result();
    }


    public function getListDataTotal($search = null)
    {
        $this->db->select("COUNT(a.id) total");
        $this->db->from("tbl_food_category a");

        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.name', $search);
            // $this->db->or_like('a.agency', $search);
            $this->db->group_end();
        }
        $this->db->where('isdeleted', 0);

        $query = $this->db->get();
        return $query->row_array()['total'];
    }

    public function getFoodByCatId($foodCatId)
    {
        $this->db->select("b.id,b.name,b.kkal");
        $this->db->from("tbl_food_cat a");
        $this->db->where('a.food_cat_id',$foodCatId);
        $this->db->where("b.isdeleted",0);
        
        $this->db->join('tbl_food b','a.food_id = b.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFoodByFoodCatId($foodCatId,$foodId)
    {
        $this->db->select("b.id,b.name,b.kkal");
        $this->db->from("tbl_food_cat a");
        $this->db->where('a.food_cat_id',$foodCatId);
        $this->db->where('a.food_id',$foodId);
        
        $this->db->join('tbl_food b','a.food_id = b.id');

        $query = $this->db->get();
        return $query->row_array();
    }
}
                        
/* End of file user.php */
