<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Food_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->insert('tbl_food', $data);
        return $this->db->affected_rows() > 0;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_food', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_food');
        return $this->db->affected_rows() > 0;
    }

    public function getById($id)
    {
        $this->db->select('id,name,image,order_pos,kkal');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_food a');
        $result = $query->row_array();
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getListData($limit = 10, $offset = 0,  $search = null)
    {
        $this->db->select("id,name text, image,order_pos,kkal");
        $this->db->from("tbl_food a");
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
        $this->db->from("tbl_food a");

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

}
                        
/* End of file user.php */
