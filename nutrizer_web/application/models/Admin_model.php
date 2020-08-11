<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Admin_model extends CI_Model {
                        
    public function checkAdminLogin($username,$password){
        $this->db->select('id,username,nickname,islocked,privilege_id,avatar_img image');
        $this->db->from('tbl_admin_user');
        $this->db->where('username',$username);
        $this->db->where('password',$password);
        $this->db->where('isdeleted',0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAdminUser($username){
        $this->db->select("a.username,a.nickname name,a.nickname,a.email,a.privilege_id,a.avatar_img,
        (SELECT sub.privilege_name FROM tbl_admin_privilege sub WHERE sub.id = a.privilege_id) privilege_name");
        $this->db->from("tbl_admin_user a");
        $this->db->where("a.username", $username);
        $query = $this->db->get();
        return $query->row_array();
    }
                            
    public function insertLoginHistory($data)
    {
      $this->db->insert('tbl_admin_login', $data);
      return $this->db->insert_id();
    }

    public function updateAdminUser($id, $data)
    {
        $this->db->where('username', $id);
        $this->db->update('tbl_admin_user', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function getNationalityList($limit = 10, $offset = 0,  $search = null)
    {
        $this->db->select("nationality id,nationality text, name, phonecode,nationality, a.sortname");
        $this->db->from("tbl_countries a");
        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.name', $search);
            $this->db->or_like('a.nationality', $search);
            $this->db->group_end();
        }

        $this->db->limit(empty($limit) ? 10 : $limit);
        $this->db->offset(empty($offset) ? 0 : $offset);

        $query = $this->db->get();
        return $query->result();
    }


    public function getNationalityListTotal($search = null)
    {
        $this->db->select("COUNT(a.id) total");
        $this->db->from("tbl_countries a");

        if (!is_null($search)) {
            $this->db->group_start();
            $this->db->like('a.name', $search);
            $this->db->or_like('a.nationality', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->row_array()['total'];
    }

    
    public function updateConfig($id, $data)
    {
        $this->db->where('option', $id);
        $this->db->update('tbl_config', $data);
        return $this->db->affected_rows() > 0;
    }
    

    public function getConfig($id)
    {
        $this->db->select('option,value,enable');
        $this->db->where('option', $id);
        $query = $this->db->get('tbl_config');
        $result = $query->row_array();
        if (is_array($result)) {
            return $result;
        } else {
            return false;
        }
    }

                        
}
                        
/* End of file admin.php */
    
                        