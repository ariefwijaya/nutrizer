<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nutrition_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->insert('tbl_nutrition_type', $data);
        return $this->db->affected_rows() > 0;
    }

    public function insertFoodCat($data)
    {
        $this->db->insert('tbl_nutri_food_cat', $data);
        
        if($this->db->affected_rows() > 0){
            $this->db->set('food_cat_count', 'food_cat_count+1', FALSE);
            $this->db->where("id", $data['nutrition_type_id']);
            $this->db->update("tbl_nutrition_type");
            return $this->db->affected_rows() > 0;
        }
        else{
            return false;
        }
    }

    public function deleteFoodCat($nutritionId,$foodCatId)
    {
        $this->db->where('nutrition_type_id', $nutritionId);
        $this->db->where('food_cat_id', $foodCatId);
        $this->db->delete('tbl_nutri_food_cat');
        if($this->db->affected_rows() > 0){
            $this->db->set('food_cat_count', 'food_cat_count-1', FALSE);
            $this->db->where("id", $nutritionId);
            $this->db->update("tbl_nutrition_type");
            return $this->db->affected_rows() > 0;
        }
        else{
            return false;
        }
    }


    public function isFoodCatExistInNutrition($nutritionId,$foodCatId)
    {
        $this->db->where('nutrition_type_id', $nutritionId);
        $this->db->where('food_cat_id', $foodCatId);
        $query = $this->db->get('tbl_nutri_food_cat a');
        // echo $this->db->last_query();
        return !empty($query->row_array());
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_nutrition_type', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_nutrition_type');
        return $this->db->affected_rows() > 0;
    }

    public function getById($id)
    {
        $this->db->select('id,name,image,order_pos');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_nutrition_type a');
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
        $this->db->from("tbl_nutrition_type a");
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
        $this->db->from("tbl_nutrition_type a");

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

    public function getNutritionFoodCatById($nutritionId)
    {
        $this->db->select("b.id,b.name");
        $this->db->from("tbl_nutri_food_cat a");
        $this->db->where('a.nutrition_type_id',$nutritionId);
        $this->db->where("b.isdeleted",0);
        
        $this->db->join('tbl_food_category b','a.food_cat_id = b.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getNutritionFoodCatByFoodCatId($nutritionId,$foodCatId)
    {
        $this->db->select("b.id,b.name");
        $this->db->from("tbl_nutri_food_cat a");
        $this->db->where('a.nutrition_type_id',$nutritionId);
        $this->db->where('a.food_cat_id',$foodCatId);
        
        $this->db->join('tbl_food_category b','a.food_cat_id = b.id');

        $query = $this->db->get();
        return $query->row_array();
    }
}
                        
/* End of file user.php */
