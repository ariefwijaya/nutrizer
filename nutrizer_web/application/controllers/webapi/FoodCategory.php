<?php

defined('BASEPATH') or exit('No direct script access allowed');

class FoodCategory extends CI_Controller
{
    public function getGridRows()
    {
        $this->load->model('grid_model');
        $request = json_decode($this->input->post('request'));
        $this->grid_model->initiateFoodCategoryData($request);
        $data['status'] = true;
        $data['data'] = $this->grid_model->getGridRows();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function getById($whereId)
    {
        verifAccess();
        $this->load->model('web/foodCategory_model');
        $result = $this->foodCategory_model->getById($whereId);
        $response['status'] = true;
        $response['data'] = $result;
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function createData()
    {
        verifAccess();

        $this->load->library('form_validation');
        $this->load->model('web/foodCategory_model');
        $varKey = array(
            array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
            // array('field' => 'image', 'label' => 'Image', 'rules' => 'callback_thumbnail_check_required'),
            array('field' => 'order_pos', 'label' => 'Order Position','rules' => 'required'),
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
                $formattedVal = $valPost == "" ? NULL : $valPost;
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['iby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['idt'] = $date->format('Y-m-d H:i:s');
            $statusProcess = $this->foodCategory_model->insert($dataProcess);
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
        $this->load->model('web/foodCategory_model');
        $varKey = array(
            array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
            array('field' => 'order_pos', 'label' => 'Order Position','rules' => 'required'),
            // array('field' => 'image', 'label' => 'Image', 'rules' => 'callback_thumbnail_check'),
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
                $formattedVal = $valPost == "" ? NULL : $valPost;
                if ($field == "image" && (empty($formattedVal) || is_null($formattedVal))) {
                    continue;
                }
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['uby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->input->post('id');
            $statusProcess = $this->foodCategory_model->update($whereId, $dataProcess);
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

    function deleteData()
    {
        verifAccess();

        $this->load->library('form_validation');
        $this->load->model('web/foodCategory_model');
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
                $formattedVal = $valPost == "" ? NULL : $valPost;
                if ($field == "remark") {
                    $formattedVal = substr($valPost, 0, 199);
                }
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['dby'] = $this->session->userdata('username');
            $dataProcess['isdeleted'] = 1;
            $date = new DateTime('now');
            $dataProcess['ddt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->input->post('id');
            $statusProcess = $this->foodCategory_model->update($whereId, $dataProcess);
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

    public function thumbnail_check($str)
    {
        $fieldName = 'image';
        if (empty($_FILES[$fieldName]['name'])) {
            return TRUE;
        } else {
            $imagePath = MY_PRIVATE_ASSETS . "images/foodcat/";
            $resUpload = uploadImage($fieldName, $imagePath . "", array(
                array('size' => 150, 'path' => $imagePath . "thumb/"),
            ));
            if ($resUpload['status']) {
                $_POST[$fieldName] = $resUpload['data'];
                return TRUE;
            } else {
                $this->form_validation->set_message('thumbnail_check', $resUpload['error']);
                return FALSE;
            }
        }
    }

    public function thumbnail_check_required($str)
    {
        $fieldName = 'image';
        if (empty($_FILES[$fieldName]['name'])) {
            $this->form_validation->set_message('thumbnail_check_required', "Image is Required");
            return FALSE;
        } else {
            $imagePath = MY_PRIVATE_ASSETS . "images/foodcat/";
            $resUpload = uploadImage($fieldName, $imagePath . "", array(
                array('size' => 150, 'path' => $imagePath . "thumb/"),
            ));
            if ($resUpload['status']) {
                $_POST[$fieldName] = $resUpload['data'];
                return TRUE;
            } else {
                $this->form_validation->set_message('thumbnail_check_required', $resUpload['error']);
                return FALSE;
            }
        }
    }

    public function getListData()
    {
        $this->load->model('web/foodCategory_model');
        $search = $this->input->post('search');
        $pageNumber = $this->input->post('page');
        $rowPerPage = 5;
        // Row position
        $offset = is_null($pageNumber) || $pageNumber == 0 ? 0 : ($pageNumber - 1) * $rowPerPage;
        $result = $this->foodCategory_model->getListData($rowPerPage, $offset, $search);
        $total = $this->foodCategory_model->getListDataTotal($search);

        $response = array(
            "results" => $result,
            "pagination" => array(
                "more" => ($pageNumber * $rowPerPage) < $total
            )
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function getMemberByParentId($whereParentId){
        $this->load->model('web/foodCategory_model');
        $result = empty($whereParentId)?[]:$this->foodCategory_model->getFoodByCatId($whereParentId);
        $response['status'] = true;
        $response['data'] = $result;
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function addMember(){
        $this->load->model('web/foodCategory_model');
        $parentId = $this->input->post('parentId');
        $memberId = $this->input->post('memberId');

        if(!empty($memberId) && !empty($parentId)){
            if($this->foodCategory_model->isFoodExistCat($parentId,$memberId)){
                $response['status'] = false;
                $response['error'] = "Data is already exist";
            }else{

                $date = new DateTime('now');
                $dataProcess['food_cat_id'] = $parentId;
                $dataProcess['food_id'] = $memberId;
                $dataProcess['iby'] = $this->session->userdata('username');
                $dataProcess['idt'] = $date->format('Y-m-d H:i:s');
                $statusProcess = $this->foodCategory_model->insertFood($dataProcess);
                if ($statusProcess) {
                    $resInsert = $this->foodCategory_model->getFoodByFoodCatId($parentId,$memberId);
                    $response['status'] = true;
                    $response['data'] = $resInsert;
                } else {
                    $response['status'] = false;
                    $response['error'] = "Failed to insert data";
                }
            }
        }else{
            $response['status'] = false;
            $response['error'] = "Failed to insert data, Unknown Data Identifier";
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function removeMember(){
        $this->load->model('web/foodCategory_model');
        $parentId = $this->input->post('parentId');
        $memberId = $this->input->post('memberId');

        if(!empty($memberId) && !empty($parentId)){
            $statusProcess = $this->foodCategory_model->deleteFood($parentId,$memberId);
            if ($statusProcess) {
                $response['status'] = true;
                $response['data'] = "Data has been deleted";
            } else {
                $response['status'] = false;
                $response['error'] = "Failed to delete data";
            }
        }else{
            $response['status'] = false;
            $response['error'] = "Failed to delete data, Unknown Data Identifier";
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    
    
}
        
    /* End of file  .php */
