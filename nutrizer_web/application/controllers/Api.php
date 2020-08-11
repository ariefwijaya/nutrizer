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

    public function getAppInfo_get(){
        $resData =  getConfigValue('mobile_app_info');
        if($resData['status']){

    		$responseData = $resData['data'];
    	   $resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $responseData
        	];
            
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => $resData['error'],
                "data" =>[]
            ];
        }
        
      
        $this->response( $resultEncode, 200 );
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
        $gender = $this->post("gender");

        try {
            if (empty($nickname)) throw new Exception("Nickname is Required", 1);
            if (empty($username)) throw new Exception("Username is Required", 1);
            if (empty($password)) throw new Exception("Password is Required", 1);
            if (empty($birthday)) throw new Exception("Birthday is Required", 1);
            if (empty($gender)) throw new Exception("Gender is Required", 1);

            $isUsernameExist = $this->api_model->isUsernameExist($username);
            if ($isUsernameExist) throw new Exception("Username Already Exist", 1);

            $this->load->helper('string');
            $loginId  = random_string();

            $date = new DateTime('now');
            $dataProcess = array(
                "username" => $username,
                "nickname" => $nickname,
                "birthday" => $birthday,
                "gender"=>$gender,
                "password" => md5($password),
                "iby" => $username,
                "idt" => $date->format('Y-m-d H:i:s'),
                "lastlogin_id" => $loginId,
                "lastlogin_from" => "mobile",
                "lastlogin_time" => $date->format('Y-m-d H:i:s'),
            );

            if(!empty($email)){
            	$dataProcess['email'] = $email;
            }

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
                        "id" => (string)$userId,
                        "nickname" => $nickname,
                        "username" => $username,
                        "email" => $email,
                        "birthday" => $birthday,
                        "gender"=>$gender,
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
                "lastlogin_from" => "mobile",
                "lastlogin_time" => $date->format('Y-m-d H:i:s'),
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
                        "gender"=>$userInfo['gender'],
                        "birthday" => $userInfo['birthday'],
                        "height" => !empty($userInfo['height'])? floatVal($userInfo['height']) : 0.0,
                        "weight" => !empty($userInfo['weight'])? floatVal($userInfo['weight']) : 0.0,
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
                    // "data" => $bmi
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
    }

    public function userInfo_get()
    {
        try {
            
            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);
            $resData = $resValidation['data'];
            $userId = $resData->userid;
            // $userId=1;
            $userInfo = $this->api_model->getUserById($userId);
            if (!$userInfo) throw new Exception("Unauthorized Access!. User not found", 401);

            if($userInfo['islocked']) throw new Exception("Account is Locked. Please Contact", 403);

            $height =  $userInfo['height'];
            $weight =  $userInfo['weight'];
            $bmi = 0;
            if(!is_null($height) && !is_null($weight)){
            	$weight  = (int) $weight;
            	$height = (int) $height;
            	$bmi = $weight / pow($height / 100, 2);	
            }
            
            $userProfile = [];
            $userProfile['nickname'] = $userInfo['nickname'];
            $userProfile['username'] = $userInfo['username'];
            $userProfile['email'] = $userInfo['email'];
            $userProfile['birthday'] = $userInfo['birthday'];
            $userProfile['height'] = $height;
            $userProfile['weight'] = $weight;
            $userProfile['bmi'] = round($bmi,2);
            // $userProfile['bmi'] = round($bmi,2);
            $userProfile['lastLoginTime'] = $userInfo['lastlogin_time'];
            $userProfile['lastLoginFrom'] = $userInfo['lastlogin_from'];

            $resultEncode = [
                "success" => true,
                "message" => "success",
                "data" => $userProfile
            ];
          
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

        }


        $this->response($resultEncode, 200);
    }

    public function updateUserProfile_post()
    {

        $nickname = $this->post('nickname'); 
        $email = $this->post('email'); 
        try {
            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);
            $resData = $resValidation['data'];
            $userId = $resData->userid;
            $userInfo = $this->api_model->getUserById($userId);
            if (!$userInfo) throw new Exception("Unauthorized Access!. Please Relogin", 401);
            $username = $userInfo['username'];

            if (empty($nickname)) throw new Exception("Nickname is Required", 1);
            if (empty($email)) throw new Exception("Email is Required", 1);

            $date = new DateTime('now');
            $dataProcess = array(
                "nickname"=>$nickname,
                "email"=>$email,
                "uby" => $userInfo['username'],
                "udt" => $date->format('Y-m-d H:i:s'),
            );
            
            $resultProcess = $this->api_model->updateUser($userId, $dataProcess);

            if ($resultProcess) {
                $resultEncode = [
                    "success" => true,
                    "message" => "Data has been updated",
                ];
            } else {
                throw new Exception("Failed to Login.", 1);
            }

        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }

        $this->response($resultEncode, 200);

    }

    public function changeUserPassword_post()
    {

        $oldPassword = $this->post('oldPassword'); 
        $newPassword = $this->post('newPassword'); 
        try {
            if (empty($oldPassword)) throw new Exception("Old Password is Required", 1);
            if (empty($newPassword)) throw new Exception("New Password is Required", 1);

            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);
            $resData = $resValidation['data'];
            $userId = $resData->userid;
            $userInfo = $this->api_model->getUserById($userId);
            if (!$userInfo) throw new Exception("Unauthorized Access!. Please Relogin", 401);
            $username = $userInfo['username'];
           
            if ($userInfo['password'] != md5($oldPassword)) throw new Exception("Old password is wrong", 1);
            
            $date = new DateTime('now');
            $dataProcess = array(
                "password"=>md5($newPassword),
                "lastpw_time"=>$date->format('Y-m-d H:i:s'),
                "uby" => $userInfo['username'],
                "udt" => $date->format('Y-m-d H:i:s'),
            );
            
            $resultProcess = $this->api_model->updateUser($userId, $dataProcess);

            if ($resultProcess) {
                $resultEncode = [
                    "success" => true,
                    "message" => "Data has been updated",
                ];
            } else {
                throw new Exception("Failed to Login.", 1);
            }

        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }

        $this->response($resultEncode, 200);

    }
    public function userbmi_get()
    {
        try {
            
            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);
            $resData = $resValidation['data'];
            $userId = $resData->userid;
            // $userId=1;
            $userInfo = $this->api_model->getUserById($userId);
            if (!$userInfo) throw new Exception("Unauthorized Access!. User not found", 401);

            if($userInfo['islocked']) throw new Exception("Account is Locked. Please Contact", 403);

            $height =  $userInfo['height'];
            $weight =  $userInfo['weight'];
            $bmi = 0;
            $bmiText = "Unknown";
            $bmiRank = null;
            if(!is_null($height) && !is_null($weight)){
            	$weight  = (int) $weight;
            	$height = (int) $height;
                $bmi = $weight / pow($height / 100, 2);	

                if($bmi<18.5){
                    $bmiText = "Berat badan kurang";
                    $bmiRank = 0;
                }else if($bmi>=18.5 && $bmi <=24.9){
                    $bmiText = "Berat badan ideal";
                    $bmiRank = 1;
                }else if($bmi>=25.0 && $bmi <=29.9){
                    $bmiText = "Berat badan lebih";
                    $bmiRank = 2;
                }else if($bmi>=30.0 && $bmi <=39.9){
                    $bmiText = "Gemuk";
                    $bmiRank = 3;
                }else if($bmi>40.0){
                    $bmiText = "Obesitas";
                    $bmiRank = 4;
                }

            }
            
            $userProfile = [];
            $userProfile['height'] = $height;
            $userProfile['weight'] = $weight;
            $userProfile['bmi'] = round($bmi,2);
            $userProfile['bmiText'] = $bmiText;
            $userProfile['bmiRank'] = $bmiRank;

            $resultEncode = [
                "success" => true,
                "message" => "success",
                "data" => $userProfile
            ];
          
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

        }


        $this->response($resultEncode, 200);
    }

    public function bannerHome_get()
    {
        try{
            $resData =  getConfigValue('banner_home');
            if(!$resData['status']) throw new Exception($resData['error'], 1);
            
            $banner = $resData['data'];
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$banner
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }


    public function kekList_get()
    {
        $page = $this->get('page'); 
        $limit = 6; 
        
        try{
            if (is_null($page)) throw new Exception("Page is Required", 1);

            $offset = $page * $limit;
            $resData =  $this->api_model->getKekList($offset);
            
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$resData
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }

    public function kekDetail_get()
    {
        $id = $this->get('id'); 
        try{
            if (is_null($id)) throw new Exception("Id is Required", 1);
            $resData =  $this->api_model->getKekById($id);
            
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$resData
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }

    public function nutritionDictList_get()
    {
        $page = $this->get('page'); 
        $limit = 6; 
        
        try{
            if (is_null($page)) throw new Exception("Page is Required", 1);

            $offset = $page * $limit;
            $resData =  $this->api_model->getNutriDictList($offset);
            
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$resData
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }

    public function nutritionFoodCatList_get()
    {
        $page = $this->get('page'); 
        $nutritionTypeId = $this->get('id');
        $limit = 6; 
        
        try{
            if (is_null($page)) throw new Exception("Page is Required", 1);

            $offset = $page * $limit;
            $resFinal = [];
            $resData =  $this->api_model->getNutriFoodCatByNutrition($nutritionTypeId,$offset);

            for ($i=0; $i < count($resData); $i++) { 
                $temp = $resData[$i];
                $temp['foods'] = $this->api_model->getNutriFoodByCat($temp['id']);
                $resFinal[]=$temp;
            }
            
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$resFinal
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }


    public function calculateBMIData_post()
    {
        $weight = $this->post("weight");
        $height = $this->post("height");
        try {
            
            $resValidation =  $this->validateToken(true);
            if(!$resValidation['success']) throw new Exception("UnAuthorized Access, Please Relogin", 401);

            $bmi = 0.0;
            $bmiText = "Unknown";
            $bmiRank=null;
            if(!is_null($height) && !is_null($weight)){
            	$weight  = (int) $weight;
            	$height = (int) $height;
                $bmi = $weight / pow($height / 100, 2);	

                if($bmi<18.5){
                    $bmiText = "Berat badan kurang";
                    $bmiRank = 0;
                }else if($bmi>=18.5 && $bmi <=24.9){
                    $bmiText = "Berat badan ideal";
                    $bmiRank = 1;
                }else if($bmi>=25.0 && $bmi <=29.9){
                    $bmiText = "Berat badan lebih";
                    $bmiRank = 2;
                }else if($bmi>=30.0 && $bmi <=39.9){
                    $bmiText = "Gemuk";
                    $bmiRank = 3;
                }else if($bmi>40.0){
                    $bmiText = "Obesitas";
                    $bmiRank = 4;
                }

            }
            
            $bmiDat = [];
            $bmiDat['height'] = $height;
            $bmiDat['weight'] = $weight;
            $bmiDat['bmi'] = $bmi;
            $bmiDat['bmiText'] = $bmiText;
            $bmiDat['bmiRank'] = $bmiRank;

            $resultEncode = [
                "success" => true,
                "message" => "success",
                "data" => $bmiDat
            ];
          
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

        }


        $this->response($resultEncode, 200);
    }

    public function nutritionCalcData_get()
    {
        try{
            $resFinal['activityFactor'] = [
                array("id"=> "1", "title"=> "Sangat Ringan/Bedrest", "factor"=> 1.30,"gender"=> "M","healthy"=> true),
                array("id"=> "2", "title"=> "Ringan", "factor"=> 1.65,"gender"=> "M","healthy"=> true),
                array("id"=> "3", "title"=> "Sedang", "factor"=> 1.76,"gender"=> "M","healthy"=> true),
                array("id"=> "4", "title"=> "Berat", "factor"=> 2.10,"gender"=> "M","healthy"=> true),
                array("id"=> "5", "title"=> "Sangat Ringan/Bedrest", "factor"=> 1.30,"gender"=> "F","healthy"=> true),
                array("id"=> "6", "title"=> "Ringan", "factor"=> 1.55,"gender"=> "F","healthy"=> true),
                array("id"=> "7", "title"=> "Sedang", "factor"=> 1.70,"gender"=> "F","healthy"=> true),
                array("id"=> "8", "title"=> "Berat", "factor"=> 2.00,"gender"=> "F","healthy"=> true),
                array("id"=> "9", "title"=> "Istirahat di Bed", "factor"=> 1.2,"healthy"=> false),
                array("id"=> "10", "title"=> "Tidak terikat di bed", "factor"=> 1.3,"healthy"=> false)
            ];
            
            $resFinal['stressFactor'] = [
                array("id"=> "1", "title"=> "Tidak ada stress, gizi baik", "factor"=> 1.3),
                array("id"=> "2", "title"=> "Stress ringan:radang salcerna, kanker, bedah elektif", "factor"=> 1.4),
                array("id"=> "3", "title"=> "Stress sedang:sepsis, bedahtulang, luka bakar", "factor"=> 1.5),
                array("id"=> "4", "title"=> "Stress berat:trauma multipel, bedah multisistem", "factor"=> 1.6),
                array("id"=> "5", "title"=> "Stress sangat berat:CKB, luka bakar dan sepsis", "factor"=> 1.7),
                array("id"=> "6", "title"=> "Luka bakar sangat berat", "factor"=> 2.1),
            ];
        
            $resultEncode = [
                "success" => true,
                "message" => "Success",
                "data"=>$resFinal
            ];
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];
        }
        $this->response($resultEncode, 200);
    }

    public function nutritionCalculatedResult_post()
    {
        $weight = $this->post("weight");
        $height = $this->post("height");
        $gender = $this->post("gender");
        $age = $this->post("age");
        $activityFactor = $this->post("activityFactor");
        $stressFactor = $this->post("stressFactor");
        try {
            if (empty($weight)) throw new Exception("Weight is Required", 1);
            if (empty($height)) throw new Exception("Height is Required", 1);
            if (empty($gender)) throw new Exception("Gender is Required", 1);
            if (is_null($age)) throw new Exception("Age is Required", 1);
            if (is_null($activityFactor)) throw new Exception("Activity Factor is Required", 1);

            if($gender=="M"){
                $bmr = 66 + (13.7*$weight) + (5*$height) - (6.8*$age);
            }else {//"F"
                $bmr = 65 + (9.6*$weight) + (1.8*$height) - (4.7*$age);
            }
            
            //Dikali faktor aktivitas
            $TEE = $bmr * $activityFactor;

            //dikali faktor stress jika ada
            if(!empty($stressFactor))
            $TEE = $TEE * $stressFactor;

            //kebutuhan gizi mikro
            $fat = 0.2 * $TEE; //kalori
            $fat = $fat/9; //gram

            $protein = 0.15 * $TEE; //kalori
            $protein = $protein/4; //gram

            $carbo = 0.65 * $TEE; //kalori
            $carbo = $carbo/4; //gram

            $nutriCalculation = [];
            $nutriCalculation['bmr'] = $bmr;
            $nutriCalculation['energy'] = $TEE;
            $nutriCalculation['carbo'] = $carbo;
            $nutriCalculation['protein'] = $protein;
            $nutriCalculation['fat'] = $fat;

            $resultEncode = [
                "success" => true,
                "message" => "success",
                "data" => $nutriCalculation
            ];
          
        } catch (Exception $e) {
            $resultEncode = [
                "success" => false,
                "error_code" => $e->getCode(),
                "message" => $e->getMessage(),
            ];

        }


        $this->response($resultEncode, 200);
    }
}
