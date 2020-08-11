<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function getGridRows()
    {
        $this->load->model('grid_model');
        $request = json_decode($this->input->post('request'));
        $this->grid_model->initiateUserData($request);
        $data['status'] = true;
        $data['data'] = $this->grid_model->getGridRows();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function getGridRowsExcel()
    {
        $this->load->model('grid_model');
        $request = json_decode($this->input->post('request'));
        $this->grid_model->initiateUserData($request);

        $columnExcel = $this->grid_model->getColumnList();
        $rowExcel = $this->grid_model->getFullDataRows();

        $this->load->helper('export');
        
        $date = new DateTime('now');
        $timestampData =  $date->getTimestamp();  
        $name = "UserList - ".$timestampData;
        $pathFile = "assets_private/temp/".$name.".xlsx";
        
        $exportUrl =  exportExcel($columnExcel,$rowExcel,$pathFile);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($exportUrl));
    }

    function getById($whereId){
        verifAccess();
        $this->load->model('web/user_model');
        $result = $this->user_model->getById($whereId);
        $response['status'] = true;
        $response['data'] = $result;
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function createData()
    {
        verifAccess();
        
        $this->load->library('form_validation');
        $this->load->model('web/user_model');
        $varKey = array(
            array('field' => 'username', 'label' => 'Username', 'rules' => 'required|alpha_numeric|is_unique[tbl_user.username]',  'errors' => array(
                'is_unique' => '%s is already exists.',
            )),
            array('field' => 'nickname', 'label' => 'Full Name', 'rules' => 'required'),
            array('field' => 'gender', 'label' => 'Gender', 'rules' => 'in_list[M,F]'),
            array('field' => 'birthday', 'label' => 'Birth Date'),
            array('field' => 'weight', 'label' => 'Weight', 'rules' => 'required'),
            array('field' => 'height', 'label' => 'Height', 'rules' => 'required'),
            // array('field' => 'avatar_img', 'label' => 'Avatar Image', 'rules' => 'callback_avatar_check'),
            array('field' => 'email', 'label' => 'Email', 'rules' => 'valid_email'),
            array('field' => 'password', 'label' => 'Password', 'rules' => 'required'),
            array('field' => 'password_confirm', 'label' => 'Password Confirmation', 'rules' => 'required|matches[password]'),
            array('field' => 'islocked', 'label' => 'Is Locked', 'rules' => 'in_list[0,1]'),
        );
        $this->form_validation->set_error_delimiters('', '')->set_rules($varKey);

        if ($this->form_validation->run() == FALSE) {
            $response['status'] = false;
            $response['validation'] = $this->form_validation->error_array(); //$validationError; 
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $dataProcess = array();
            foreach ($varKey as $key => $value) {
                $field = $value['field'];
                $valPost = $this->input->post($field);
                $formattedVal = $valPost==""?NULL:$valPost;
                if($field=='password_confirm'){
                    continue;
                }else if($field=="password" && $formattedVal!=NULL){
                    $formattedVal = md5($formattedVal);
                }
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['iby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['idt'] = $date->format('Y-m-d H:i:s');
            $statusProcess = $this->user_model->insert($dataProcess);
            if ($statusProcess) {
                $response['status'] = true;
                $response['data'] = "Data has been Created";
            } else {
                $response['status'] = false;
                $response['error'] = "Failed to create data";
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    public function editData()
    {
        verifAccess();
        $this->load->library('form_validation');
        $this->load->model('web/user_model');
        $varKey = array(
            array('field' => 'nickname', 'label' => 'Full Name', 'rules' => 'required'),
            array('field' => 'gender', 'label' => 'Gender', 'rules' => 'in_list[M,F]'),
            array('field' => 'birthday', 'label' => 'Birth Date'),
             array('field' => 'weight', 'label' => 'Weight', 'rules' => 'required'),
            array('field' => 'height', 'label' => 'Height', 'rules' => 'required'),
            // array('field' => 'avatar_img', 'label' => 'Avatar Image', 'rules' => 'callback_avatar_check'),
            array('field' => 'email', 'label' => 'Email', 'rules' => 'valid_email'),
            array('field' => 'password', 'label' => 'Password'),
            array('field' => 'password_confirm', 'label' => 'Password Confirmation', 'rules' => 'callback_password_conf_edit'),
            array('field' => 'islocked', 'label' => 'Is Locked', 'rules' => 'in_list[0,1]'),
        );
        $this->form_validation->set_error_delimiters('', '')->set_rules($varKey);

        if ($this->form_validation->run() == FALSE) {
            $response['status'] = false;
            $response['validation'] = $this->form_validation->error_array(); //$validationError; 
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $dataProcess = array();
            foreach ($varKey as $key => $value) {
                $field = $value['field'];
                $valPost = $this->input->post($field);
                $formattedVal = $valPost==""?NULL:$valPost;
                if($field=='password_confirm'){
                    continue;
                }else if($field=="password"){
                    if($formattedVal != NULL){
                        $formattedVal = md5($formattedVal);
                    }else{
                        continue;
                    }
                }else if($field=="avatar_img" && (empty($formattedVal) || is_null($formattedVal))){
                    continue;
                }
                $dataProcess[$field] = $formattedVal;
            }
            
            $dataProcess['uby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->input->post('id');
            $statusProcess = $this->user_model->update($whereId,$dataProcess);
            if ($statusProcess) {
                $response['status'] = true;
                $response['data'] = "Data has been Updated";
            } else {
                $response['status'] = false;
                $response['error'] = "Failed to update data";
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    function deleteData(){
        verifAccess();
        $this->load->library('form_validation');
        $this->load->model('web/user_model');
        $varKey = array(
            array('field' => 'remarksdeleted', 'label' => 'Remarks', 'rules' => 'required'),
        );
        $this->form_validation->set_error_delimiters('', '')->set_rules($varKey);

        if ($this->form_validation->run() == FALSE) {
            $response['status'] = false;
            $response['validation'] = $this->form_validation->error_array(); //$validationError; 
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $dataProcess = array();
            foreach ($varKey as $key => $value) {
                $field = $value['field'];
                $valPost = $this->input->post($field);
                $formattedVal = $valPost==""?NULL:$valPost;
                if($field=="remark"){
                    $formattedVal = substr($valPost,0,199);
                }
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['dby'] = $this->session->userdata('username');
            $dataProcess['isdeleted'] = 1;
            $date = new DateTime('now');
            $dataProcess['ddt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->input->post('id');
            $statusProcess = $this->user_model->update($whereId,$dataProcess);
            if ($statusProcess) {
                $response['status'] = true;
                $response['data'] = "Data has been Deleted";
            } else {
                $response['status'] = false;
                $response['error'] = "Failed to delete data";
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    public function avatar_check($str)
    {
        $fieldName = 'avatar_img';
        if (empty($_FILES[$fieldName]['name'])) {
            return TRUE;
        } else {
            $imagePath = MY_PRIVATE_ASSETS . "images/userapp/";
            $resUpload = uploadImage($fieldName, $imagePath . "original/", array(
                array('size' => 50, 'path' => $imagePath . "avatar/"),
                array('size' => 240, 'path' => $imagePath . "normal/")
            ));
            if ($resUpload['status']) {
                $_POST[$fieldName] = $resUpload['data'];
                return TRUE;
            } else {
                $this->form_validation->set_message('avatar_check', $resUpload['error']);
                return FALSE;
            }
        }
    }

    public function password_conf_edit($str){
        $passValue = $this->input->post('password');
        if($passValue!=$str){
            $this->form_validation->set_message("password_conf_edit", "The Password Confirmation field does not match the password field.");
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function getListData(){
        $this->load->model('web/user_model');
        $search = $this->input->post('search');
        $pageNumber = $this->input->post('page');
        $rowPerPage=5;
        // Row position
        $offset= is_null($pageNumber) || $pageNumber==0 ? 0 : ($pageNumber - 1) * $rowPerPage ;            
        $result = $this->user_model->getListData($rowPerPage,$offset,$search);
        $total = $this->user_model->getListDataTotal($search);
        
        $response=array(
            "results"=>$result,
            "pagination"=> array(
                "more"=>($pageNumber * $rowPerPage) < $total
            )
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
        
    /* End of file  User.php */
