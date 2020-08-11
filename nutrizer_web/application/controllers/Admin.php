<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once(APPPATH . 'core/MY_Controller_API.php');

class Admin extends CI_Controller
{

    public function loginUser()
    {

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->load->model('admin_model');

        $varKey = array(
            array('field' => 'username',  'label' => 'Username', 'rules' => 'required'),
            array('field' => 'password',  'label' => 'Password', 'rules' => 'required')
        );

        $this->form_validation->set_error_delimiters('', '')->set_rules($varKey);


        $validationError = array();
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));
        if ($this->form_validation->run() == FALSE) {
            $response['status'] = false;
            $response['validation'] = $this->form_validation->error_array(); //$validationError; 
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        } else {
            $userLogin = $this->admin_model->checkAdminLogin($username, $password);
            if(!$userLogin){
                $response['status'] = false;
                $response['validation'] = [array('name' => 'username', 'error' => 'Username/Password is wrong')]; //$validationError; 
                $this->output->set_content_type('application/json')->set_output(json_encode($response));
                return;
            }
            else if($userLogin['islocked']==1){
                $response['status'] = false;
                    $response['validation'] = [array('name' => 'username', 'error' => 'Your account is locked. Please contact administrator.')]; //$validationError; 
                    $this->output->set_content_type('application/json')->set_output(json_encode($response));
                    return;
            }
           
        }

            $this->load->library('user_agent');
            $dataInsert['username'] = $username;
            $dataInsert['iby'] = $username;
            $dataInsert['ip_address'] = $this->input->ip_address();
            $dataInsert['user_agent'] = $this->input->user_agent();
            $dataInsert['resource'] = $this->agent->browser();
            $dataInsert['resource_version'] = $this->agent->version();
            $dataInsert['platform'] = $this->agent->platform();
            $resInsert = $this->admin_model->insertLoginHistory($dataInsert);
            $dataProcess = array();
            $dataProcess['lastlogin_id'] = $resInsert;
            $dataProcess['lastlogin_from'] = "web";
            $date = new DateTime('now');
            $dataProcess['lastlogin_time'] = $date->format('Y-m-d H:i:s');
            $statusProcess = $this->admin_model->updateAdminUser($username, $dataProcess);
            $response = array();
            if ($statusProcess) {
                $sessionData = array(
                    'lastlogin_id'=>$dataProcess['lastlogin_id'],
                    'username'  => $username,
                    'logged_in' => TRUE
                );
                //clear session first for sure
                $this->session->set_userdata($sessionData);

                $response['status'] = true;
                $response['data'] = "Login Success. Please wait...";
            } else {
                $response['status'] = false;
                $response['error'] = "Login failed, please try again.";
            }
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($response));
    }

    public function editDataProfile()
    {
        verifAccess();
        $this->load->library('form_validation');
        $this->load->model('admin_model');
        $varKey = array(
            array('field' => 'nickname', 'label' => 'Full Name', 'rules' => 'required'),
            array('field' => 'email', 'label' => 'Email', 'rules' => 'valid_email'),
            array('field' => 'avatar_img', 'label' => 'Avatar Image', 'rules' => 'callback_avatar_check'),
            array('field' => 'password', 'label' => 'Password'),
            array('field' => 'password_confirm', 'label' => 'Password Confirmation', 'rules' => 'callback_password_conf_edit'),
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
                if ($field == 'password_confirm' || $field == "artist_id") {
                    continue;
                } else if ($field == "password") {
                    if ($formattedVal != NULL) {
                        $formattedVal = md5($formattedVal);
                    } else {
                        continue;
                    }
                } else if ($field == "avatar_img" && (empty($formattedVal) || is_null($formattedVal))) {
                    continue;
                }
                $dataProcess[$field] = $formattedVal;
            }

            $dataProcess['uby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
            $whereId = $this->session->userdata('username');
            $statusProcess = $this->admin_model->updateAdminUser($whereId, $dataProcess);
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

    public function avatar_check($str)
    {
        $fieldName = 'avatar_img';
        if (empty($_FILES[$fieldName]['name'])) {
            return TRUE;
        } else {
            $imagePath = MY_PRIVATE_ASSETS . "images/avatar/";
            $resUpload = uploadImage($fieldName, $imagePath . "", array(
                array('size' => 150, 'path' => $imagePath . "thumb/"),
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

    public function password_conf_edit($str)
    {
        $passValue = $this->input->post('password');
        if ($passValue != $str) {
            $this->form_validation->set_message("password_conf_edit", "The Password Confirmation field does not match the password field.");
            return FALSE;
        } else {
            return TRUE;
        }
    }


    
    function getDataProfile()
    {
        verifAccess();
        $whereId = $this->session->userdata('username');
        $this->load->model('admin_model');
        $result = $this->admin_model->getAdminUser($whereId);
        $response['status'] = true;
        $response['data'] = $result;
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

   
    public function updateBannerNews()
    {
        verifAccess();
        $this->load->library('form_validation');
        $this->load->model('admin_model');
        $varKey = array(
            array('field' => 'title', 'label' => 'Title', 'rules' => 'required'),
            array('field' => 'subtitle', 'label' => 'Subtitle'),
            array('field' => 'linkUrl', 'label' => 'Hyperlink',),
        );
        $this->form_validation->set_error_delimiters('', '')->set_rules($varKey);

        if ($this->form_validation->run() == FALSE) {
            $response['status'] = false;
            $response['validation'] = $this->form_validation->error_array(); //$validationError; 
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $dataProcess = array();
            $dataProcess['uby'] = $this->session->userdata('username');
            $date = new DateTime('now');
            $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
            $resData = array(
                "title" => $this->input->post('title'),
                "subtitle" => $this->input->post('subtitle'),
                "linkUrl" => $this->input->post('linkUrl'),
            );
            $dataProcess['value'] = json_encode($resData);
            $statusProcess = $this->admin_model->updateConfig("banner_home", $dataProcess);

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

    public function getBannerNews()
    {
        verifAccess();
        $this->load->model('admin_model');
        $result = array();

        $res = $this->admin_model->getConfig("banner_home");
        $resValue = $res != false ? json_decode($res['value'], true) : [];
        $resStatus = $res != false && isset($res['enable']) &&  $res['enable'] == "TRUE" ? true : false;

        if (is_array($resValue)) {
            // $result['title'] = isset($resHotNews['title']) ? $resHotNews['title'] : NULL;
            // $result['subtitle'] = isset($resHotNews['content']) ? $resHotNews['content'] : NULL;
            // $result['linkUrl'] = isset($resHotNews['url']) ? $resHotNews['url'] : NULL;
            $response['status'] = true;
            $response['data'] = $resValue;
        } else {
            $response['status'] = false;
            $response['error'] = "Data Not Found!";
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
        
    /* End of file  Admin.php */
