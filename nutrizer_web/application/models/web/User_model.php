<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->insert('tbl_user', $data);
        return $this->db->affected_rows() > 0;
    }

    
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_user', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_user');
        return $this->db->affected_rows() >0;
    }

    public function getById($id)
    {
        $this->db->select('username,nickname,birthday,gender,email,weight,height,islocked');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_user');
        $result = $query->row_array();
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }

    
    public function getListData($limit = 10, $offset = 0,  $search = null)
    {
        $this->db->select("id id,nickname text, nickname,birthday, a.gender");
        $this->db->from("tbl_user a");
        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.nickname', $search);
            $this->db->or_like('a.username', $search);
            $this->db->group_end();
        }

        $this->db->limit(empty($limit) ? 10 : $limit);
        $this->db->offset(empty($offset) ? 0 : $offset);

        $query = $this->db->get();
        return $query->result();
    }


    public function getListDataTotal($search = null)
    {
        $this->db->select("COUNT(1) total");
        $this->db->from("tbl_user a");

        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.nickname', $search);
            $this->db->or_like('a.username', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->row_array()['total'];
    }
}
                        
/* End of file user.php */
