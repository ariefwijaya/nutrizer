<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kek extends CI_Controller
{
    public function getGridRows()
    {
        $this->load->model('grid_model');
        $request = json_decode($this->input->post('request'));
        $this->grid_model->initiateKekData($request);
        $data['status'] = true;
        $data['data'] = $this->grid_model->getGridRows();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function getGridRowsExcel()
    {
        $this->load->model('grid_model');
        $request = json_decode($this->input->post('request'));
        $this->grid_model->initiateKekData($request);

        $columnExcel = $this->grid_model->getColumnList();
        $rowExcel = $this->grid_model->getFullDataRows();

        $this->load->helper('export');
        
        $date = new DateTime('now');
        $timestampData =  $date->getTimestamp();  
        $name = "Info Covid - ".$timestampData;
        $pathFile = "assets_private/temp/".$name.".xlsx";
        
        $exportUrl =  exportExcel($columnExcel,$rowExcel,$pathFile);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($exportUrl));
    }

    function getById($whereId){
        verifAccess();
        $this->load->model('web/kek_model');
        $result = $this->kek_model->getById($whereId);
        $response['status'] = true;
        $response['data'] = $result;
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function createData()
    {
        verifAccess();
        
        $this->load->library('form_validation');
        $this->load->model('web/kek_model');
        $varKey = array(
            array('field' => 'title', 'label' => 'Title', 'rules' => 'required'),
            array('field' => 'subtitle', 'label' => 'Subtitle'),
            array('field' => 'content', 'label' => 'Content','rules' => 'required'),
            array('field' => 'order_pos', 'label' => 'Order Position', 'rules' => 'required'),
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
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['iby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['idt'] = $date->format('Y-m-d H:i:s');
            $statusProcess = $this->kek_model->insert($dataProcess);
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
        $this->load->model('web/kek_model');
        $varKey = array(
            array('field' => 'title', 'label' => 'Title', 'rules' => 'required'),
            array('field' => 'subtitle', 'label' => 'Subtitle'),
            array('field' => 'content', 'label' => 'Content','rules' => 'required'),
            array('field' => 'order_pos', 'label' => 'Order Position', 'rules' => 'required'),
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
                $dataProcess[$field] = $formattedVal;
            }
            
            $dataProcess['uby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->input->post('id');
            $statusProcess = $this->kek_model->update($whereId,$dataProcess);
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
        $this->load->model('web/kek_model');
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
            $statusProcess = $this->kek_model->update($whereId,$dataProcess);
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

    public function getListData(){
        $this->load->model('web/kek_model');
        $search = $this->input->post('search');
        $pageNumber = $this->input->post('page');
        $rowPerPage=5;
        // Row position
        $offset= is_null($pageNumber) || $pageNumber==0 ? 0 : ($pageNumber - 1) * $rowPerPage ;            
        $result = $this->kek_model->getListData($rowPerPage,$offset,$search);
        $total = $this->kek_model->getListDataTotal($search);
        
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
