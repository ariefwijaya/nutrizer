<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kek_model extends CI_Model
{

    public function insert($data)
    {
        $this->db->insert('tbl_kek', $data);
        return $this->db->affected_rows() > 0;
    }

    
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_kek', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_kek');
        return $this->db->affected_rows() >0;
    }

    public function getById($id)
    {
        $this->db->select('id,title,subtitle,content,order_pos');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_kek');
        $result = $query->row_array();
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }

    
    public function getListData($limit = 10, $offset = 0,  $search = null)
    {
        $this->db->select("id id,title text, subtitle,content, order_pos");
        $this->db->from("tbl_kek a");
        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.subtitle', $search);
            $this->db->or_like('a.title', $search);
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
        $this->db->from("tbl_kek a");

        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.subtitle', $search);
            $this->db->or_like('a.title', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->row_array()['total'];
    }
}
                        
/* End of file user.php */
