<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->load->model('api_model');
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

    private function generateToken($tokenData = array())
    {
        // $tokenData = array();
        // $tokenData['id'] = 1; //TODO: Replace with data for token
        return AUTHORIZATION::generateToken($tokenData);
    }

    public function validateUserSession_get()
    {
        $headers = $this->input->request_headers();
        try {
            $resValidation =  $this->validateToken(true);
            if ($resValidation['success']) {

                $resData = $resValidation['data'];
                $checkUser = $this->api_sample_model->checkUserId($resData->userid);
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
                "data" => []
            ];
            $this->response($resultEncode, 200);
        }
    }

    public function checkUserExist_post()
    {
        if ($this->post("username") == "test") {
            $resultEncode = [
                "success" => true,
                "message" => "User already exist",
                "data" => []
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "User is not exist",
                "data" => null
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
        if ($username == "test") {
            $resultEncode = [
                "success" => true,
                "message" => "User registered successfully",
                "data" => [
                    "id" => "123",
                    "nickname" => $nickname,
                    "username" => $username,
                    "privilege" => "user",
                    "email" => $email,
                    "birthday" => $birthday,
                    "token" => "GENERATED_TOKEN"
                ]
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "Failed to Register",
                "data" => []
            ];
        }

        $this->response($resultEncode, 200);
    }

    public function login_post()
    {
        $username = $this->post("username");
        $password = $this->post("password");
        if ($username == "test" && $password == "12345") {
            $resultEncode = [
                "success" => true,
                "message" => "User logged in successfully",
                "data" => [
                    "id" => "123",
                    "nickname" => "tester",
                    "username" => $username,
                    "privilege" => "user",
                    "email" => "email@mail.com",
                    "birthday" => "1996-04-03",
                    "height" => 168,
                    "weight" => 55,
                    "bmi" => 21.2,
                    "token" => "GENERATED_TOKEN"
                ]
            ];
        } else {
            $resultEncode = [
                "success" => false,
                "error_code" => "CUSTOM_CODE",
                "message" => "Failed to login. Eg: Invalid username/password",
                "data" => []
            ];
        }

        $this->response($resultEncode, 200);
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
        $bmi = $weight / pow($height / 100, 2);

        if (true) {
            $resultEncode = [
                "success" => true,
                "message" => "Data has been updated",
                "data" => $bmi
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
