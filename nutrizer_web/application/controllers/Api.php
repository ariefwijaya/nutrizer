<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('api_model');
    }


    private function validateToken($returnResponse = false)
    {
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (isset($decodedToken->userid) && isset($decodedToken->lastlogin_id)) {
                    $resultEncode = [
                        "success" => true,
                        "data" => $decodedToken,
                        "message" => "Access Authorized"
                    ];

                    return $resultEncode;
                }
            }
        }


        $resultEncode = [
            "success" => false,
            "error_code" => 401,
            "message" => "Unauthorized Access!. Please Relogin",
            "data" => []
        ];
        if ($returnResponse) {
            return $resultEncode;
        }

        $this->set_response($resultEncode, 401);
        die();
    }

    public function validateUserSession_get()
    {
        try {
            $resValidation =  $this->validateToken(true);
            if ($resValidation['success']) {

                $resData = $resValidation['data'];
                $checkUser = $this->api_model->checkUserById($resData->userid);
                if (!$checkUser)
                    throw new Exception("Unauthorized Access!. Please Relogin", 401);

                if ($checkUser['lastlogin_id'] != $resData->lastlogin_id)
                    throw new Exception("Session Expired. Already Logged in another device. Logout automatically", 1);


                $resultEncode = [
                    "success" => true,
                    "message" => "Session is valid"
                ];

                $this->response($resultEncode, 200);
            } else {
                throw new Exception("Unauthorized Access!. Please Relogin", 401);
            }
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
            $this->response($resultEncode, 200);
        }
    }

    public function checkUserExist_post()
    {
        $username = $this->post('username');
        if ($this->api_model->isUsernameExist($username)) {
            $resultEncode = [
                "success" => true,
                "message" => "User already exist",
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "User is not exist",
            ];
        }

        $this->response($resultEncode, 200);
    }

    public function signup_post()
    {
        $email = $this->post("email");
        $username = $this->post("username");
        $password = $this->post("password");
        $birthday = $this->post("birthday");
        $nickname = $this->post("nickname");

        try {
            if (empty($nickname)) throw new Exception("Nickname is Required", 1);
            if (empty($username)) throw new Exception("Username is Required", 1);
            if (empty($password)) throw new Exception("Password is Required", 1);
            if (empty($birthday)) throw new Exception("Birthday is Required", 1);

            $isUsernameExist = $this->api_model->isUsernameExist($username);
            if ($isUsernameExist) throw new Exception("Username Already Exist", 1);

            $this->load->helper('string');
            $loginId  = random_string();

            $date = new DateTime('now');
            $dataProcess = array(
                "username" => $username,
                "nickname" => $nickname,
                "birthday" => $birthday,
                "password" => md5($password),
                "iby" => $username,
                "idt" => $date->format('Y-m-d H:i:s'),
                "lastlogin_id" => $loginId,
                "lastloginfrom" => "mobile",
                "lastlogin" => $date->format('Y-m-d H:i:s'),
            );

            $userId =  $this->api_model->createUser($dataProcess);

            if ($userId > 0) {

                $tokenData = array();
                $tokenData['userid'] = $userId;
                $tokenData['lastlogin_id'] = $loginId;
                $tokenNumber = AUTHORIZATION::generateToken($tokenData);

                $resultEncode = [
                    "success" => true,
                    "message" => "User registered successfully",
                    "data" => [
                        "id" => $userId,
                        "nickname" => $nickname,
                        "username" => $username,
                        "email" => $email,
                        "birthday" => $birthday,
                        "token" => $tokenNumber
                    ]
                ];

                //create history
                $this->load->library('user_agent');
                $history['username'] = $username;
                $history['iby'] = $username;
                $history['ip_address'] = $this->input->ip_address();
                $history['user_agent'] = $this->input->user_agent();
                $history['resource'] = $this->agent->browser();
                $history['resource_version'] = $this->agent->version();
                $history['platform'] = $this->agent->platform();

                $this->api_model->insertLoginHistory($history);
                $this->response($resultEncode, 200);
            } else {
                throw new Exception("Failed to Register", 1);
            }
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

            $this->response($resultEncode, 200);
        }
    }

    public function login_post()
    {
        $username = $this->post("username");
        $password = $this->post("password");
        try {
            if (empty($username)) throw new Exception("Username is Required", 1);

            $userInfo = $this->api_model->getUserByUsername($username);
            if (empty($userInfo)) throw new Exception("Username or Password is wrong", 1);

            if ($userInfo['password'] != md5($password)) throw new Exception("Username or Password is wrong", 1);
            //calculate BMI;
            if (!empty($userInfo['weight']) && !empty($userInfo['height'])) {
                $bmi = $userInfo['weight'] / pow($userInfo['height'] / 100, 2);
            } else {
                $bmi = 0;
            }

            $this->load->helper('string');
            $loginId  = random_string();

            $userId = $userInfo['id'];
            $tokenData = array();
            $tokenData['userid'] = $userId;
            $tokenData['lastlogin_id'] = $loginId;
            $tokenNumber = AUTHORIZATION::generateToken($tokenData);

            $date = new DateTime('now');
            $dataProcess = array(
                "uby" => $username,
                "udt" => $date->format('Y-m-d H:i:s'),
                "lastlogin_id" => $loginId,
                "lastloginfrom" => "mobile",
                "lastlogin" => $date->format('Y-m-d H:i:s'),
            );
            $resultProcess = $this->api_model->updateUser($userId, $dataProcess);

            if ($resultProcess) {



                $resultEncode = [
                    "success" => true,
                    "message" => "User logged in successfully",
                    "data" => [
                        "id" => $userInfo['id'],
                        "nickname" => $userInfo['nickname'],
                        "username" => $userInfo['username'],
                        "email" => $userInfo['email'],
                        "birthday" => $userInfo['birthday'],
                        "height" => $userInfo['height'],
                        "weight" => $userInfo['weight'],
                        "bmi" => $bmi,
                        "token" => $tokenNumber
                    ]
                ];

                //create history
                $this->load->library('user_agent');
                $history['username'] = $username;
                $history['iby'] = $username;
                $history['ip_address'] = $this->input->ip_address();
                $history['user_agent'] = $this->input->user_agent();
                $history['resource'] = $this->agent->browser();
                $history['resource_version'] = $this->agent->version();
                $history['platform'] = $this->agent->platform();

                $this->api_model->insertLoginHistory($history);
                $this->response($resultEncode, 200);
            } else {
                throw new Exception("Failed to Login.", 1);
            }
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

            $this->response($resultEncode, 200);
        }
    }


    public function resetPassword_post()
    {

        if ($this->post("username") == "test") {
            $resultEncode = [
                "success" => true,
                "message" => "Email for reset password has been sent to tesxxx@gmail.com",
                "data" => []
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "Username not found!",
                "data" => null
            ];
        }

        $this->response($resultEncode, 200);
    }



    public function updateUserBMI_post()
    {
        $weight = $this->post('weight'); //kg
        $height = $this->post('height'); //cm
        try {
            if (empty($weight)) throw new Exception("Weight is Required", 1);
            if (empty($height)) throw new Exception("Height is Required", 1);

            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);
            $resData = $resValidation['data'];
            $userId = $resData->userid;
            $userInfo = $this->api_model->getUserById($userId);
            if (!$userInfo) throw new Exception("Unauthorized Access!. Please Relogin", 401);

            $bmi = $weight / pow($height / 100, 2);

            $date = new DateTime('now');
            $dataProcess = array(
                "weight"=>$weight,
                "height"=>$height,
                "uby" => $userInfo['username'],
                "udt" => $date->format('Y-m-d H:i:s'),
            );
            $resultProcess = $this->api_model->updateUser($userId, $dataProcess);

            if ($resultProcess) {
                $resultEncode = [
                    "success" => true,
                    "message" => "Data has been updated",
                    "data" => $bmi
                ];
                $this->response($resultEncode, 200);
            } else {
                throw new Exception("Failed to Login.", 1);
            }

        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

            $this->response($resultEncode, 200);
        }


        $this->response($resultEncode, 200);
    }

    public function updateUserProfile_post()
    {

        if (true) {
            $resultEncode = [
                "success" => true,
                "message" => "Data has been updated",
                "data" => []
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "Failed to update",
                "data" => null
            ];
        }

        $this->response($resultEncode, 200);
    }
}
