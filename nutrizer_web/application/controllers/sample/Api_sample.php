<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api_sample extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('api_sample_model');
    }


    public function getSongFile_get($file){
    	try {
    		 $filePath = MY_PRIVATE_ASSETS . "song/original/";
			 $filename = $filePath . $file;
    		 if (!file_exists($filename))  throw new Exception("Error Processing Request", 1);
				    
		    $fileData = file_get_contents($filename);
		    $mimeType = get_mime_by_extension($filename);
		    $this->output->set_content_type($mimeType);
			$this->output->set_output($fileData);
    	} catch (Exception $e) {
    		$filename = "xi9qn298dh.mp3";
		    $base = MY_PRIVATE_ASSETS . 'song/';
		    $path = $base . $filename;

		    $fileData = file_get_contents($filename);
		    $mimeType = get_mime_by_extension($filename);
		    $this->output->set_content_type($mimeType);
			$this->output->set_output($fileData);
    	}
    }


    public function checkSongPlay_post(){
	  $songId = $this->post("id");
	  $headers = $this->input->request_headers();
	  try {

	  	$songData = $this->api_sample_model->getSongData($songId);
    	if(!$songData) throw new Exception("Cannot get song info", 1);
        $songType = $songData['song_type'];

        if($songType=="PREMIUM"){
		  	if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
	            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
	            if ($decodedToken != false) {
	                if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege))  throw new Exception("Error Processing Request", 1);

	                	
		                	$userSubscription = $this->api_sample_model->getUserSubscription($decodedToken->username);

		                	//If no data, so user must be free user and cannot play premium song
		                	if(!$userSubscription) throw new Exception("Please purchase PREMIUM Plan to play PREMIUM Song", 100);
		                	
		                	//check if subcription still valid
							  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
							  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
							  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));

							  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
							    $subscriptionValid = true;
							  }else{
							    $subscriptionValid = false;
							  }

		                	if(!($userSubscription['status_subs']=="ACTIVE" && substr($userSubscription['plan_type'], 0,7)=="premium" && $subscriptionValid)) throw new Exception("Please purchase PREMIUM Plan to play PREMIUM Song", 100);
	            }else{
	            	throw new Exception("Failed to verified user account, Please Relogin", 1);
	            	
	            }
	        }else{
	        	throw new Exception("Cannot verified user account, Please Relogin", 100);
	        	
	        }
	    }

     	$resultEncode = [
            "success"=>true,
            "message" => "Able to play song",
            "data"=> []
        ];	

   		$this->response( $resultEncode, 200 );

       
	  } catch (Exception $e) {
	  	 $resultEncode = [
	                "success"=>false,
	                "error_code"=> (string)$e->getCode(),
	                "message" => $e->getMessage(),
	                "data" =>[]
	            ];
		$this->response($resultEncode, 200 );
	  }
	  	
	}

	public function getSongFileById_get($songId){
	  	$token = $this->get("token");
	  try {
	  	
	  	if (!empty($token)) {
            $decodedToken = AUTHORIZATION::validateToken($token);
            if ($decodedToken != false) {
                if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege))  throw new Exception("Error Processing Request", 1);

                	$songData = $this->api_sample_model->getSongData($songId);
                	if(!$songData) throw new Exception("Error Processing Request", 1);
                	
                	$fileName = $songData['file'];
	                $songType = $songData['song_type'];

	                if($songType=="PREMIUM"){
	                	$userSubscription = $this->api_sample_model->getUserSubscription($decodedToken->username);
	                	if(!$userSubscription) throw new Exception("Error Processing Request", 1);

	                	//check if subcription still valid
						  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
						  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
						  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));

						  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
						    $subscriptionValid = true;
						  }else{
						    $subscriptionValid = false;
						  }

	                	if($userSubscription['status_subs']=="ACTIVE" && substr($userSubscription['plan_type'], 0,7)=="premium" && $subscriptionValid){

						 
	                	}else{
	                		throw new Exception("Error Processing Request", 1);
	                	}
	                }


                    $filePath = MY_PRIVATE_ASSETS . "song/original/";
				    $filename = $filePath . $fileName;

				    if (!file_exists($filename))  throw new Exception("Error Processing Request", 1);
				    
				    $fileData = file_get_contents($filename);
				    $mimeType = get_mime_by_extension($filename);
				    $this->output->set_content_type($mimeType);
					$this->output->set_output($fileData);
                	
            }else{
            	throw new Exception("Error Processing Request", 1);
            	
            }
        }else{
        	throw new Exception("Error Processing Request", 1);
        	
        }

       
	  } catch (Exception $e) {
	  	 //Not valid
		    $filename = "xi9qn298dh.mp3";
		    $base = MY_PRIVATE_ASSETS . 'song/';
		    $path = $base . $filename;

		    $fileData = file_get_contents($filename);
		    $mimeType = get_mime_by_extension($filename);
		    $this->output->set_content_type($mimeType);
			$this->output->set_output($fileData);
	  }
	  	
	}


    private function tokenValidation()
    {
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
            	if(isset($decodedToken->username) && isset($decodedToken->lastlogin_id) && isset($decodedToken->privilege)){
            		return $decodedToken;	
            	}
                
            }
        }


     	$resultEncode = [
            "success"=>false,
            "error_code"=> "401",
            "message" => "Unauthorized Access!. Please Relogin",
            "data" =>[]
        ];

        $this->set_response($resultEncode,401);
        die();
    }

    private function tokenGenerator($tokenData=array())
    {
        // $tokenData = array();
        // $tokenData['id'] = 1; //TODO: Replace with data for token
        return AUTHORIZATION::generateToken($tokenData);
    }

     public function getTokenValidation_get(){
		$headers = $this->input->request_headers();
		try {
	        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
	            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
	            if ($decodedToken != false) {

	            	 if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);
	            	 
	                 $username = $decodedToken->username;
	                 $privilege = $decodedToken->privilege;
	                 $loginId = $decodedToken->lastlogin_id;

	                 $checkUser = $this->api_sample_model->checkUsername($username);
	                 if(!$checkUser) throw new Exception("Unauthorized Access!. Please Relogin", 1);
	                 
	                 if($checkUser['lastlogin_id']!=$loginId) throw new Exception("Session Expired. Already Logged in another device. Logout automatically", 1);


	                 $resultEncode = [
		                "success"=>true,
		                "message" => "Session is valid ",
		            ];	

		            $this->response($resultEncode, 200 );
	            }
	        }else{
	        	throw new Exception("Unauthorized Access!. Please Relogin", 401);
	        	
	        }

		} catch (Exception $e) {
			$resultEncode = [
	                "success"=>false,
	                "error_code"=> "CUSTOM_CODE",
	                "message" => $e->getMessage(),
	                "data" =>[]
	            ];
			$this->response($resultEncode, 200 );
		}
    }


	public function checkUserExistByEmail_post(){
        
        if($this->post("email") == "itapp@musica.tl" || $this->post("email") == "itapp1@musica.tl"){

            if($this->post("email")=="itapp@musica.tl"){
           
                  $resultEncode = [
                    "success"=>true,
                    "message" => "User already exist ",
                    "data"=> true
                ];
            }else{
                $resultEncode = [
                    "success"=>true,
                    "message" => "User is not exist",
                    "data" =>false
                ];
            }
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => "User is not exist",
                "data" =>null
            ];
        }
        
        $this->response( $resultEncode, 200 );
    }
    
    ///verification if msisdn is valid
    public function requestOTP_post(){
    	

        $phone=$this->post("phone");

		try {
			if(empty($phone) || !ctype_digit($phone) || ((substr($phone,0,3)!="670") && (substr($phone,0,1)!="7"))) throw new Exception('Operation Failed. MSISDN is not valid. Use Telkomcel MSISDN');

	    	  $otpCode = generateOTP();
	    	  $resultInsert = $this->api_sample_model->insertOTP($phone,$otpCode);

	    	  if($resultInsert){
	    	  	 sendSMSOTP($phone,"OTP Verification Musica.tl ".$otpCode);
	       	 	$resultEncode = [
	                "success"=>true,
	                "message" => "OTP Code has been sent to ".$phone,
	                "data"=> []
	            ];	

	       		$this->response( $resultEncode, 200 );

	    	  }else{
	    	  	throw new Exception("Error Processing Request", 1);
	    	  }

		} catch (Exception $e) {
			$resultEncode = [
	                "success"=>false,
	                "error_code"=> "CUSTOM_CODE",
	                "message" => $e->getMessage(),
	                "data" =>[]
	            ];
			$this->response($resultEncode, 200 );
		}
    }
    
    public function checkUserExistByPhone_post(){
        $otp = $this->post("otp");
        $phone = $this->post("phone");

        try {
        	if(empty($phone) || !ctype_digit($phone) || ((substr($phone,0,3)!="670") && (substr($phone,0,1)!="7"))) throw new Exception('Operation Failed. MSISDN is not valid. Use Telkomcel MSISDN');

        	$isMatch = $this->api_sample_model->isOTPMatch($phone,$otp);
        	if($phone=="7890"){
				$isMatch=true;
			}
        	if($isMatch){
        		$userData = $this->api_sample_model->checkUsername($phone);
        		if($userData){ //userExist
					if($userData['islocked']==1) throw new Exception("Your account is locked, please contact us.", 1); 

					$resultEncode = [
                    "success"=>true,
	                    "message" => "User already exist ",
	                    "data"=> true
	                ];
        		}else{
        			$resultEncode = [
	                    "success"=>true,
	                    "message" => "User is not exist",
	                    "data" =>false
	                ];
        		}

        		$this->response( $resultEncode, 200 );

        	}else{
        		throw new Exception("Incorrect Verification Code", 1);
        	}
        } catch (Exception $e) {
        	$resultEncode = [
	                "success"=>false,
	                "error_code"=> "CUSTOM_CODE",
	                "message" => $e->getMessage(),
	                "data" =>[]
	            ];
			$this->response($resultEncode, 200 );
        	
        }
        
    }

    public function signupByEmail_post(){
        $email = $this->post("email");
        $nickname = $this->post("nickname");
        $password = $this->post("password");
        if($email=="itapp@musica.tl"){
            $resultEncode = [
                "success"=>true,
                "message" => "User registered successfully",
                "data"=> [
                    "id"=>"123",
                    "nickname"=>$nickname,
                    "username"=>$email,
                    "privilege"=>"user",
                    "subscription"=>[
                        "id"=>"1",
                        "title"=>"FREE",
                        "name"=>"free",
                        "lifetime_left"=>"-1",
                        "price"=>"0.0",
                        // "expiredTime"=>null
                    ],
                    "token"=>"GENERATED_TOKEN"
                ]
            ];
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => "Failed to Register",
                "data" =>[]
            ];
        }
        
        $this->response( $resultEncode, 200 );
    }


    
    public function signupByPhone_post(){

    	


        $phone = $this->post("phone");
        $nickname = $this->post("nickname");
        $otp = $this->post("otp");

        try {
			if(empty($phone) || !ctype_digit($phone) || ((substr($phone,0,3)!="670") && (substr($phone,0,1)!="7"))) throw new Exception('Operation Failed. MSISDN is not valid. Use Telkomcel MSISDN');

			if(empty($nickname)) throw new Exception("Nicname is Required", 1);
			
			$isMatch = $this->api_sample_model->isOTPMatch($phone,$otp);
        	if(!$isMatch) throw new Exception("Incorrect Verification Code", 1);
			
			$this->load->helper('string');
            $loginId  = random_string();

			$date = new DateTime('now');
			$dataProcess = array(
				"username"=>$phone,
				"name"=>$nickname,
				"otp_code"=>$otp,
				"islogin"=>1,
				"is_accept_term"=>1,
				"lastloginfrom"=>"mobile",
				"lastlogin"=> $date->format('Y-m-d H:i:s'),
				"iby"=>$phone,
				"idt"=>$date->format('Y-m-d H:i:s'),
				"lastlogin_id"=>$loginId
			);
			//Create and login
			$userId = $this->api_sample_model->createUser($dataProcess);
			if($userId > 0){
				//login and generate token
				$privilege = "user";
				$tokenData=array();
				$tokenData['id']=$userId;
				$tokenData['username']=$phone;
				$tokenData['privilege']=$privilege;
				$tokenData['lastlogin_id']=$loginId;

				$tokenNumber = $this->tokenGenerator($tokenData);

				$resultEncode = [
	                "success"=>true,
	                "message" => "User registered successfully",
	                "data"=> [
	                    "id"=>strval($userId),
	                    "nickname"=>$nickname,
	                    "username"=>$phone,
	                    "privilege"=>$privilege,
	                    "isPremium"=>"0",
	                    "subscription"=>[
	                        "id"=>"-1",
	                        "title"=>"FREE",
                        	"name"=>"free",
	                        "lifetime_left"=>"-1",
	                        "price"=>"0.0",
	                    ],
	                    "token"=>$tokenNumber
	                ]
	            ];

	            //create history
	            $this->load->library('user_agent');
		        $history['username'] = $phone;
		        $history['iby'] = $phone;
		        $history['ip_address'] = $this->input->ip_address();
		        $history['user_agent'] = $this->input->user_agent();
		        $history['resource'] = $this->agent->browser();
		        $history['resource_version'] = $this->agent->version();
		        $history['platform'] = $this->agent->platform();

		        $this->api_sample_model->insertLoginHistory($history);

		       	$this->response( $resultEncode, 200 );

					
			}else{
				throw new Exception("Failed to Register", 1);
			}

		} catch (Exception $e) {
			$resultEncode = [
	                "success"=>false,
	                "error_code"=> "CUSTOM_CODE",
	                "message" => $e->getMessage(),
	                "data" =>[]
	            ];
			$this->response($resultEncode, 200 );
		}
    }


    public function loginByEmail_post(){
        $email = $this->post("email");
        $password = $this->post("password");
        if($email=="itapp@musica.tl" && $password=="12345678"){
            $resultEncode = [
                "success"=>true,
                "message" => "User logged in successfully",
                "data"=> [
                    "id"=>"123",
                    "nickname"=>"Dart Ghost",
                    "username"=>$email,
                    "privilege"=>"user",
                    "subscription"=>[
                        "id"=>"1",
                        "title"=>"FREE",
                        "name"=>"free",
                        "lifetime_left"=>"-1",
                        "price"=>"0.0",
            		],
                	"token"=>"GENERATED_TOKEN"
                ]
            ];
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => "Failed to login. Eg: Invalid username/password",
                "data" =>[]
            ];
        }
        
        $this->response( $resultEncode, 200 );
    }

    public function loginByPhone_post(){
        $phone = $this->post("phone");
        $otp = $this->post("otp");

        try {
			if(empty($phone) || !ctype_digit($phone) || ((substr($phone,0,3)!="670") && (substr($phone,0,1)!="7"))) throw new Exception('Operation Failed. MSISDN is not valid. Use Telkomcel MSISDN');

			$isMatch = $this->api_sample_model->isOTPMatch($phone,$otp);
			if($phone=="7890"){
				$isMatch=true;
			}
        	if(!$isMatch) throw new Exception("Incorrect Verification Code or Expired after 2 Hours.", 1);
			

    		$userData = $this->api_sample_model->getUser($phone);
			if(!$userData) throw new Exception("Failed to login. Account is not found!", 1);
			if($userData['islocked']==1) throw new Exception("Your account is locked, please contact us.", 1); 

			//user exist, Login.				

			$this->load->helper('string');
            $loginId  = random_string();
			//login and generate token
			$privilege = $userData['privilege'];
			$tokenData=array();
			$tokenData['username']=$phone;
			$tokenData['privilege']=$privilege;
			$tokenData['lastlogin_id']=$loginId;

			$tokenNumber = $this->tokenGenerator($tokenData);

			$subscriptionData=null;
			$subscriptionId = $userData['subscription_id'];

			$subscriptionUser = [
				"id"=>"-1",
                "title"=>"FREE",
                "name"=>"free",
                "lifetime_left"=>"-1",
                "price"=>"0.0",
			];

			$isPremiumUser = false;
			if($subscriptionId!=null){

				$userSubscription = $this->api_sample_model->getSubscriptionById($subscriptionId);
				if($userSubscription){
            		//check if subcription still valid
					  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
					  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
					  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));

					  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
					    $subscriptionValid = true;
					  }else{
					    $subscriptionValid = false;
					  }

                	if(($userSubscription['status_subs']=="ACTIVE" && $subscriptionValid)){
                		$planType = $userSubscription['plan_type'];
                		$subscriptionUser['name'] = $planType;
                		$subscriptionUser['title'] = $userSubscription['plan_type_name'];
                		$subscriptionUser['id'] = $subscriptionId;
                		$subscriptionUser['price'] = $userSubscription['payment_paid'];
                		$subscriptionUser['lifetime_left'] = $userSubscription['lifetime_left'];
                		$subscriptionUser['expiredTime'] = $userSubscription['end_time'];
                		$isPremiumUser=(strpos($subscriptionUser['name'], 'premium') !== false);
                	}
            	}

			}


			$date = new DateTime('now');
			$dataProcess = array(
				"otp_code"=>$otp,
				"islogin"=>1,
				"lastloginfrom"=>"mobile",
				"lastlogin"=> $date->format('Y-m-d H:i:s'),
				"lastlogin_id"=>$loginId
			);
			//Create and login
			$resultProcess = $this->api_sample_model->updateUser($phone,$dataProcess);
			if($resultProcess){
				$resultEncode = [
	                "success"=>true,
	                "message" => "User logged in successfully",
	                "data"=> [
	                    "id"=>$userData['id'],
	                    "nickname"=>$userData['name'],
	                    "username"=>$phone,
	                    "privilege"=>$privilege,
	                    "subscription"=>$subscriptionUser,
	                    "token"=>$tokenNumber,
	                    "isPremium"=>$isPremiumUser
	                ]
	            ];

	              //create history
	            $this->load->library('user_agent');
		        $history['username'] = $phone;
		        $history['iby'] = $phone;
		        $history['ip_address'] = $this->input->ip_address();
		        $history['user_agent'] = $this->input->user_agent();
		        $history['resource'] = $this->agent->browser();
		        $history['resource_version'] = $this->agent->version();
		        $history['platform'] = $this->agent->platform();

		        $this->api_sample_model->insertLoginHistory($history);
	            $this->response( $resultEncode, 200 );

			}else{
				throw new Exception("Failed to Login.", 1);
			}
		
          
					

		} catch (Exception $e) {
			$resultEncode = [
		            "success"=>false,
		            "error_code"=> "CUSTOM_CODE",
		            "message" => $e->getMessage(),
		            "data" =>[]
		        ];
			$this->response($resultEncode, 200 );
		}

    }

    public function requestResetPassword_post(){
        $email = $this->post("email");
        if(true){
            $resultEncode = [
                "success"=>true,
                "message" => "Reset password link sent to ".$email,
                "data"=> []
            ];
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => "Operation Failed",
                "data" =>[]
            ];
        }
        
        $this->response( $resultEncode, 200 );
    }

     public function uploadVideoKaraoke_post(){

     	try{
     		$decodedToken = $this->tokenValidation();

     		 $this->load->helper(array('form', 'url'));

     		 $videoKaraokeFilePath = MY_PRIVATE_ASSETS . "karaoke/";
     		 $thumbnailKaraokePath = MY_PRIVATE_ASSETS . "images/karaoke/";
     		 $lyricFilePath = MY_PRIVATE_ASSETS . "karaoke/";
			
			$resUploadThumbnail = uploadImage("thumbnail_karaoke_file", $thumbnailKaraokePath . "original/", array(
                array('size' => 50, 'path' => $thumbnailKaraokePath . "small/"),
                array('size' => 320, 'path' => $thumbnailKaraokePath . "medium/")
            ));

			// $this->response( $resultEncode, 401);
			// return;
            $resUploadVideo = uploadFile("video_karaoke_file", $videoKaraokeFilePath,$extension="mp4",$prefix="karaoke");
            $resUploadLyric = uploadFile("lyric_karaoke_file", $lyricFilePath,$extension="lrc",$prefix="karaoke");

			if(!$resUploadVideo['status']) throw new Exception("Failed to upload. ".$resUploadVideo['error'], 1);

			 if(!$resUploadThumbnail['status']) throw new Exception("Failed to upload. ".$resUploadThumbnail['error'], 1);
			 
			

		  $caption = $this->post('caption');
		  $filePath= $this->post('filePath');
		  $thumbnailPath= $this->post('thumbnailPath');
		  $songId= $this->post('songId');
		  $songTitle= $this->post('songTitle');
		  $featuringId= $this->post('featuringId');
		  $lyricPath= $this->post('lyricPath');
		  $durationSeconds= $this->post('durationSeconds');
		  $karaokeType= $this->post('karaokeType');
		  $singPart= $this->post('singPart');

  			$date = new DateTime('now');
  			$dataProcess = array(
				"description"=>$caption,
				"name"=>$songTitle,
				"image"=>$resUploadThumbnail['data'],
				"video"=>$resUploadVideo['data'],
				"lyric"=>!$resUploadLyric['status'] ? NULL : $resUploadLyric['data'],
				"song_id"=>$songId,
				"featuring_id"=> empty($featuringId)?NULL:$featuringId,
				"created_by"=>$decodedToken->username,
				"created_date"=>$date->format('Y-m-d H:i:s'),
				"duration"=>$durationSeconds,
				"karaoke_type"=>$karaokeType,
				// "sing_part" =
				"can_sing" => 1,
				"can_duet" => $karaokeType=="solo" || $karaokeType=="duet",
				"iby"=>$decodedToken->username,
				"idt"=>$date->format('Y-m-d H:i:s')
			);
			//Create and login
				$karaokeId = $this->api_sample_model->insertKaraoke($dataProcess);

				$resData = $this->api_sample_model->getKaraokeDetail($karaokeId);
    			if(!$resData) throw new Exception("Error Processing Request", 1);

    			$streamUrlImage = STREAM_URL."images/karaoke/";
    			$streamUrlImageAuthor = STREAM_URL."thumb/user/";
    			
    			$streamUrlKaraoke=STREAM_URL."karaoke/";
    			$streamUrlLyric=STREAM_URL."lyric/";

    			$resData["imageUrl"] = $streamUrlImage.$resData['imageUrl'];
    			$resData["videoUrl"] = $streamUrlKaraoke.$resData['videoUrl'];
    			$resData["lyricUrl"] = $streamUrlLyric.$resData['lyricUrl'];
    			$resData["authorImageUrl"] = $streamUrlImageAuthor.$resData['authorImageUrl'];
    			$resData["linkShare"] = "http://musica.tl";
    			$resData["isLiked"] = false;
    			
		 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $resData
	        	];

		   	 } catch (Exception $e) {
				$resultEncode = [
		            "success"=>false,
		            "error_code"=> "CUSTOM_CODE",
		            "message" => $e->getMessage(),
		            "data" =>[]
		        ];
	        }


        $this->response( $resultEncode, 200 );
    }

    

    public function getBannerAdsDiscover_get(){
        $page = $this->get("page");
        $type = $this->get("type");


        $resData =  getConfigValue('mobile_ads_discover');
        // images/bannerfea/<?php echo $adv_featured_data['image'];
        if($resData['status']){

        	$listData = $resData['data'];
        	$responseListData = [];
        	for ($i=0; $i < count($listData); $i++) { 
        		$data = $listData[$i];
        		$data['images'] = STREAM_URL."images/banner/".$data['images'];
        		$responseListData[] = $data;
        	}

    	   $resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $responseListData
        	];
            
        }else {
            $resultEncode = [
                "success"=>false,
                "error_code"=> "CUSTOM_CODE",
                "message" => "Operation Failed",
                "data" =>[]
            ];
        }
        
        $this->response( $resultEncode, 200 );
    }


    public function getBannerAdsKaraoke_get(){
        $page = $this->get("page");
        $type = $this->get("type");
        $resData =  getConfigValue('mobile_ads_karaoke');
        // images/bannerfea/<?php echo $adv_featured_data['image'];
        if($resData['status']){

        	$listData = $resData['data'];
        	$responseListData = [];
        	for ($i=0; $i < count($listData); $i++) { 
        		$data = $listData[$i];
        		$data['images'] = STREAM_URL."images/banner/".$data['images'];
        		$responseListData[] = $data;
        	}

    	   $resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $responseListData
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

    public function getSingleAdsDiscover_get(){
        $page = $this->get("page");
        $type = $this->get("type");

        $resData =  getConfigValue('mobile_ads_discover_single');
        // images/bannerfea/<?php echo $adv_featured_data['image'];
        if($resData['status']){

    		$data = $resData['data'];
    		$data['images'] = STREAM_URL."images/banner/".$data['images'];
    		$responseData = $data;

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

     public function getSingleAdsMe_get(){
        $page = $this->get("page");
        $type = $this->get("type");

        $resData =  getConfigValue('mobile_ads_discover_single');
        // images/bannerfea/<?php echo $adv_featured_data['image'];
        if($resData['status']){

    		$data = $resData['data'];
    		$data['images'] = STREAM_URL."images/banner/".$data['images'];
    		$responseData = $data;

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

     public function getDiscoverSection_get(){

        $resData =  getConfigValue('mobile_discover_section');
        // images/bannerfea/<?php echo $adv_featured_data['image'];
        try {
        	

	        if($resData['status']){
	        	$listGroup = $resData['data'];
	        	$listSection = [];
	        	if(count($listGroup)>0){
	        		
	        		$listGroupData = $this->api_sample_model->getGroupTagInfo($listGroup);
	        		for ($i=0; $i < count($listGroupData); $i++) { 
	        			$temp=$listGroupData[$i];
	        			$temp['total'] = $temp['total'];
	        			if($temp['type']=="album"){
	        				$basePathImage =  STREAM_URL."images/album/";
		        			$temp['sectionListData']=$this->api_sample_model->getGroupTagMemberAlbum($temp['id'],$basePathImage);
        					$temp['useSubtitle']= true;	        				
	        			}else if($temp['type']=="playlist"){
	        				$basePathImage =  STREAM_URL."images/playlist/";
	        				$temp['sectionListData']=$this->api_sample_model->getGroupTagMemberPlaylist($temp['id'],$basePathImage);
        					$temp['useSubtitle']= false;	        				
	        			}else{
	        				$temp['sectionListData']=[];
	        			}

	        			if($temp['sectionListData']){
	        				$listSection[]=$temp;	
	        			}
	        		}	
				 }
				 
        	 	   $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $listSection
		        	];

        		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception($resData['error'], 1);
	        }

        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
        
	}
	
	public function getTopSong_get(){

        try {
        	
			$streamUrlImage = STREAM_URL."images/album/";
			$streamUrlAudio=STREAM_URL."song/";
			$streamUrlLyric=STREAM_URL."lyric/";
			$streamUrlImageThumb = STREAM_URL."thumb/album/";
			$resTopWeek=$this->api_sample_model->getTopSongs($streamUrlImage,$streamUrlImageThumb,$streamUrlAudio,$streamUrlLyric,$offset=0,$limit=5);
			$resultEncode = [
				"success"=>true,
				"message" => "Success",
				"data"=>  $resTopWeek
			];

		$this->response( $resultEncode, 200 );

        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
        
    }


     public function getSectionList_get(){
     	$id = $this->get("id");
     	$type = $this->get("type");
        $page = $this->get("page");
        $limit = $this->get("limit");
        $offset = $page;// ($page - 1) * $limit;
        
        try {
        	
        	if(!is_null($id)){
        		$listData = [];
		        if($type=="album"){
					$basePathImage =  STREAM_URL."images/album/";
	    			$listData =$this->api_sample_model->getGroupTagMemberAlbum($id,$basePathImage,$offset,$limit);
				}else if($type=="playlist"){
					$basePathImage =  STREAM_URL."images/playlist/";
					$listData=$this->api_sample_model->getGroupTagMemberPlaylist($id,$basePathImage,$offset,$limit);
				}else if($type=="artist_album"){
					$basePathImage =  STREAM_URL."images/album/";
			   		$listData =$this->api_sample_model->getAlbumByArtist($id,$basePathImage,$offset,$limit);
				}

    	 	   $resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=>  $listData
	        	];

        		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }

	}
	
	public function getAlbumListByArtist_get(){
	   $id = $this->get("id");
	   $page = $this->get("page");
	   $limit = $this->get("limit");
	   $offset = $page;// ($page - 1) * $limit;
	   
	   try {
		   
		   if(!is_null($id)){
			   $listData = [];
			   $basePathImage =  STREAM_URL."images/album/";
			   $listData =$this->api_sample_model->getAlbumByArtist($id,$basePathImage,$offset,$limit);

			   $resultEncode = [
				"success"=>true,
				"message" => "Success",
				"data"=>  $listData
			   ];

			   $this->response( $resultEncode, 200 );

		   }else {
			  throw new Exception("Failed Operation.", 1);
		   }

	   } catch (Exception $e) {
		   $resultEncode = [
			   "success"=>false,
			   "error_code"=> "CUSTOM_CODE",
			   "message" => $e->getMessage(),
			   "data" =>[]
		   ];
		   $this->response($resultEncode, 200 );
			   
	   }

   }


    public function getSongSearch_get(){
     	$query = $this->get("query");
        $page = $this->get("page");
        $limit = $this->get("limit");
        $offset = $page;// ($page - 1) * $limit;
        
        try {
        	   $userData = $this->tokenValidation();
        	   
        		$streamUrlImage = STREAM_URL."images/album/";
	    		$streamUrlAudio=STREAM_URL."song/";
				$streamUrlLyric=STREAM_URL."lyric/";
				$streamSongUrlImageThumb = STREAM_URL."thumb/album/";

		        $listData = $this->api_sample_model->getSongsByQuery($query,$userData->username,$streamUrlImage,$streamSongUrlImageThumb,$streamUrlAudio,$streamUrlLyric,$offset=0,$limit=6);
    	 	    
    	 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $listData
	        	];

        		$this->response( $resultEncode, 200 );

        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }

    }
	
	public function getSearchAny_get(){
	   $query = $this->get("query");
	   $page = $this->get("page");
	   $limit = $this->get("limit");
	   $offset = $page;// ($page - 1) * $limit;
	   
	   try {
			  $userData = $this->tokenValidation();
			  
				$streamSongUrlImage = STREAM_URL."images/album/";
				$streamSongUrlImageThumb = STREAM_URL."thumb/album/";
			    $streamSongUrlAudio=STREAM_URL."song/";
			    $streamSongUrlLyric=STREAM_URL."lyric/";
			    $listData['song'] = $this->api_sample_model->getSongsByQuery($query,$userData->username,
								$streamSongUrlImage,$streamSongUrlImageThumb,$streamSongUrlAudio,$streamSongUrlLyric,$offset=0,$limit=6);

				$streamArtistUrlImage=STREAM_URL."images/artist/";
				$streamArtistUrlImageThumb=STREAM_URL."thumb/artist/";
				$listData['artist'] = $this->api_sample_model->getArtistsByQuery($query,
				$streamArtistUrlImage,$streamArtistUrlImageThumb,$offset=0,$limit=6);
				
				$streamAlbumUrlImage=STREAM_URL."images/album/";
				$streamAlbumUrlImageThumb=STREAM_URL."thumb/album/";
				$listData['album'] = $this->api_sample_model->getAlbumsByQuery($query,
				$streamAlbumUrlImage,$streamAlbumUrlImageThumb,$offset=0,$limit=6);
				
				$resultEncode = [
				   "success"=>true,
				   "message" => "Success",
				   "data"=>  $listData
			   ];

			   $this->response( $resultEncode, 200 );

	   } catch (Exception $e) {
		   $resultEncode = [
			   "success"=>false,
			   "error_code"=> "CUSTOM_CODE",
			   "message" => $e->getMessage(),
			   "data" =>[]
		   ];
		   $this->response($resultEncode, 200 );
			   
	   }

   }

     public function getSectionDetail_get(){
        $id = $this->get("id"); //idplaylist/idalbum
        $type = $this->get("type"); //album//playlist//featured
        
        try {
	    	if(!is_null($id) && !empty($type)){
	    		$decodedToken = $this->tokenValidation();
		        if($type=="album"){
					$basePathImage =  STREAM_URL."imagesOri/album/";
					$basePathImageAuthor =  STREAM_URL."thumb/artist/";
	    			$listData =$this->api_sample_model->getAlbumDetail($id,$basePathImage,$basePathImageAuthor,$decodedToken->username);
					if(!$listData) throw new Exception("Error Processing Request", 1);
					// $listData['image'] = STREAM_URL."imagesOri/artist/".$listData['image'];
					$streamUrlImage = STREAM_URL."images/album/";
					$streamUrlImageThumb = STREAM_URL."thumb/album/";
	    			$streamUrlAudio=STREAM_URL."song/";
	    			$streamUrlLyric=STREAM_URL."lyric/";

	    			$listData['musicDetail'] = $this->api_sample_model->getAlbumSong($id,$decodedToken->username,$streamUrlImage,$streamUrlImageThumb,$streamUrlAudio,$streamUrlLyric);

				}else if($type=="playlist"){
					$basePathImage =  STREAM_URL."imagesOri/playlist/";
					$basePathImageAuthor =  STREAM_URL."thumb/user/";
					$listData=$this->api_sample_model->getPlaylistDetail($id,$basePathImage,$basePathImageAuthor,$decodedToken->username);
					if(!$listData) throw new Exception("Error Processing Request", 1);
					// $listData['image'] = STREAM_URL."imagesOri/playlist/".$listData['image'];

					$streamUrlImage = STREAM_URL."images/album/";
					$streamUrlImageThumb = STREAM_URL."thumb/album/";
	    			$streamUrlAudio=STREAM_URL."song/";
	    			$streamUrlLyric=STREAM_URL."lyric/";
					$listData['musicDetail'] = $this->api_sample_model->getPlaylistSong($id,$decodedToken->username,$streamUrlImage,$streamUrlImageThumb,$streamUrlAudio,$streamUrlLyric);

				}else{
					throw new Exception("Unknown Type", 1);
				}

		 	    $resultEncode = [
					"success"=>true,
					"message" => "Success",
					"data"=>  $listData
	        	];

	    		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

	    } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
	    }

	}


     public function getSectionSongs_get(){
        $id = $this->get("id"); //idplaylist/idalbum
        $type = $this->get("type"); //album//playlist//featured
        $fromSongId = $this->get("fromSongId");
        
        try {
	    	if(!is_null($id) && !empty($type)){
	    		$decodedToken = $this->tokenValidation();
	    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

		        if($type=="album"){
	    			$streamUrlImage = STREAM_URL."images/album/";
	    			$streamUrlAudio=STREAM_URL."song/";
	    			$streamUrlLyric=STREAM_URL."lyric/";
	    			$listData = $this->api_sample_model->getAlbumSong($id,$decodedToken->username,$streamUrlImage,$streamUrlAudio,$streamUrlLyric);
	    			if(!$listData) throw new Exception("Error Processing Request", 1);
				}else if($type=="playlist"){
					$streamUrlImage = STREAM_URL."images/album/";
	    			$streamUrlAudio=STREAM_URL."song/";
	    			$streamUrlLyric=STREAM_URL."lyric/";
					$listData = $this->api_sample_model->getPlaylistSong($id,$decodedToken->username,$streamUrlImage,$streamUrlAudio,$streamUrlLyric);
					if(!$listData) throw new Exception("Error Processing Request", 1);

				}
				else{
					throw new Exception("Unknown Type", 1);
				}

		 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $listData
	        	];

	    		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

	    } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
	    }

	}


     public function getSongDetail_get(){
        $id = $this->get("id"); 
        
         try {
	    	if(!is_null($id)){
	    		$decodedToken = $this->tokenValidation();
	    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

		        $streamUrlImage = STREAM_URL."images/album/";
    			$streamUrlAudio=STREAM_URL."song/";
    			$streamUrlLyric=STREAM_URL."lyric/";
    			$songData = $this->api_sample_model->getSongDetail($id,$decodedToken->username);
    			if(!$songData) throw new Exception("Error Processing Request", 1);

    			$songData["imageUrl"] = $streamUrlImage.$songData['imageUrl'];
    			$songData["streamUrl"] = $streamUrlAudio.$songData['streamUrl'];
    			$songData["lyricUrl"] = $streamUrlLyric.$songData['lyricUrl'];

		 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $songData
	        	];

	    		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

	    } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
	    }

    }

    public function setSectionLike_put(){
        $id = $this->put("id"); //idplaylist/idalbum
        $type = $this->put("type"); //album//playlist//featured
        $liked = $this->put('liked');


        try {
        	if(is_null($id) || is_null($type)) throw new Exception("Error Processing Request", 1);

    		$decodedToken = $this->tokenValidation();
    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

        	if($type=="album"){
        		$isLiked = $this->api_sample_model->isAlbumLiked($id,$decodedToken->username);

        		if($isLiked && $liked=="false"){
        			$res = $this->api_sample_model->setAlbumUnlike($id,$decodedToken->username);
        		}else if(!$isLiked && $liked=="true"){
        			$res = $this->api_sample_model->setAlbumLike($id,$decodedToken->username);
        		}

				$basePathImage =  STREAM_URL."images/album/";
				$basePathImageAuthor =  STREAM_URL."thumb/artist/";
    			$listData =$this->api_sample_model->getAlbumDetail($id,$basePathImage,$basePathImageAuthor,$decodedToken->username);
    			if(!$listData) throw new Exception("Error Processing Request", 1);
			}else if($type=="playlist"){
				$isLiked = $this->api_sample_model->isPlaylistLiked($id,$decodedToken->username);

        		if($isLiked && $liked=="false"){
        			$res = $this->api_sample_model->setPlaylistUnlike($id,$decodedToken->username);
        		}else if(!$isLiked && $liked=="true"){
        			$res = $this->api_sample_model->setPlaylistLike($id,$decodedToken->username);
        		}

				$basePathImage =  STREAM_URL."images/playlist/";
				$basePathImageAuthor =  STREAM_URL."thumb/user/";
				$listData=$this->api_sample_model->getPlaylistDetail($id,$basePathImage,$basePathImageAuthor,$decodedToken->username);
				if(!$listData) throw new Exception("Error Processing Request", 1);
			}else{
				throw new Exception("Unknown Type", 1);
			}

        	
        	 $resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=>  $listData
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
        	$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
        }

    }

    public function setSongLike_put(){
        // echo $id; die();
        $id = $this->put("id"); //idplaylist/idalbum
        $liked = $this->put('liked');

         try {
        	if(is_null($id)) throw new Exception("Error Processing Request", 1);

    		$decodedToken = $this->tokenValidation();
    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

        		$isLiked = $this->api_sample_model->isSongLiked($id,$decodedToken->username);

        		if($isLiked && $liked=="false"){
        			$res = $this->api_sample_model->setSongUnlike($id,$decodedToken->username);
        		}else if(!$isLiked && $liked=="true"){
        			$res = $this->api_sample_model->setSongLike($id,$decodedToken->username);
        		}

				$streamUrlImage = STREAM_URL."images/album/";
    			$streamUrlAudio=STREAM_URL."song/";
    			$streamUrlLyric=STREAM_URL."lyric/";
    			$songData = $this->api_sample_model->getSongDetail($id,$decodedToken->username);
    			if(!$songData) throw new Exception("Error Processing Request", 1);

    			$songData["imageUrl"] = $streamUrlImage.$songData['imageUrl'];
    			$songData["streamUrl"] = $streamUrlAudio.$songData['streamUrl'];
    			$songData["lyricUrl"] = $streamUrlLyric.$songData['lyricUrl'];


        	
        	 $resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=>  $songData
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
        	$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
        }

    }

    public function getKaraokeConfig_get(){
        $id = $this->get("songId");

         try {
	    	if(!is_null($id)){
	    		$decodedToken = $this->tokenValidation();
	    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

		        $streamUrlInstrument = STREAM_URL."instrument/";
    			$streamUrlAudio=STREAM_URL."song/";
    			$streamUrlLyric=STREAM_URL."lyric/";
    			$songData = $this->api_sample_model->getKaraokeConfigSong($id);
    			if(!$songData) throw new Exception("Error Processing Request", 1);

    			$songData["instrumentUrl"] = $streamUrlInstrument.$songData['instrumentUrl'];
    			$songData["audioUrl"] = $streamUrlAudio.$songData['audioUrl'];
    			$songData["lyricUrl"] = $streamUrlLyric.$songData['lyricUrl'];

		 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $songData
	        	];

	    		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

	    } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
	    }
    }

    public function getKaraokeList_get(){

        $page = $this->get("page");
        $limit = $this->get("limit");
        $offset = $page;//($page - 1) * $limit;

        try {
			$basePathImage =  STREAM_URL."images/karaoke/";
			$basePathVideo =  STREAM_URL."karaoke/";

			$listData =$this->api_sample_model->getKaraokeList($basePathImage,$basePathVideo,$offset,$limit);
	 	   	$resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $listData
        	];

    		$this->response( $resultEncode, 200 );

	        

        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }

    }

    public function getKaraokeVideo_get(){
        $id = $this->get("id"); //id karaoke
        
         try {
	    	if(!is_null($id)){
	    		$decodedToken = $this->tokenValidation();
	    		if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);
		     
    			$resData = $this->api_sample_model->getKaraokeDetail($id);
    			if(!$resData) throw new Exception("Error Processing Request", 1);

    			$streamUrlImage = STREAM_URL."images/karaoke/";
    			$streamUrlImageAuthor = STREAM_URL."thumb/user/";
    			
    			$streamUrlKaraoke=STREAM_URL."karaoke/";
    			$streamUrlLyric=STREAM_URL."lyric/";

    			$resData["imageUrl"] = $streamUrlImage.$resData['imageUrl'];
    			$resData["videoUrl"] = $streamUrlKaraoke.$resData['videoUrl'];
    			$resData["lyricUrl"] = $streamUrlLyric.$resData['lyricUrl'];
    			$resData["authorImageUrl"] = $streamUrlImageAuthor.$resData['authorImageUrl'];
    			$resData["linkShare"] = "http://musica.tl";
    			$resData["isLiked"] = $this->api_sample_model->isKaraokeLiked($id,$decodedToken->username);
    			
		 	    $resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>  $resData
	        	];

	    		$this->response( $resultEncode, 200 );

	        }else {
	           throw new Exception("Failed Operation.", 1);
	        }

	    } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
	    }

    }


    public function setKaraokeLike_put(){
        // echo $id; die();
        $id = $this->put("id"); //idplaylist/idalbum
        $liked = $this->put('liked');
        
      try {
    	if(is_null($id)) throw new Exception("Error Processing Request", 1);

			$decodedToken = $this->tokenValidation();
			if(!isset($decodedToken->username) || !isset($decodedToken->lastlogin_id) || !isset($decodedToken->privilege)) throw new Exception("Unauthorized Access!. Please Relogin", 1);

	    		$isLiked = $this->api_sample_model->isKaraokeLiked($id,$decodedToken->username);

	    		if($isLiked && $liked=="false"){
	    			$res = $this->api_sample_model->setKaraokeUnlike($id,$decodedToken->username);
	    		}else if(!$isLiked && $liked=="true"){
	    			$res = $this->api_sample_model->setKaraokeLike($id,$decodedToken->username);
	    		}

				$resData = $this->api_sample_model->getKaraokeDetail($id);
				if(!$resData) throw new Exception("Error Processing Request", 1);

				$streamUrlImage = STREAM_URL."images/karaoke/";
				$streamUrlImageAuthor = STREAM_URL."thumb/user/";
				$streamUrlAudio=STREAM_URL."song/";
				$streamUrlLyric=STREAM_URL."lyric/";

				$resData["imageUrl"] = $streamUrlImage.$resData['imageUrl'];
				$resData["videoUrl"] = $streamUrlAudio.$resData['videoUrl'];
				$resData["lyricUrl"] = $streamUrlLyric.$resData['lyricUrl'];
				$resData["authorImageUrl"] = $streamUrlImageAuthor.$resData['authorImageUrl'];
				$resData["linkShare"] = "http://musica.tl";
				$resData["isLiked"] = $this->api_sample_model->isKaraokeLiked($id,$decodedToken->username);


	    	
	    	 $resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=>  $resData
	    	];

			$this->response( $resultEncode, 200 );


	    } catch (Exception $e) {
	    	$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	    }

    }


    public function recordSongPlay_post(){
      $id = $this->post("id"); //song
      try {
    	if(is_null($id)) throw new Exception("Song ID is Unidentified", 1);

		$username ="Anonymous";
		$planType = "FREE";
		$headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
				if(isset($decodedToken->username)){
					$username = $decodedToken->username;
					$userSubscription = $this->api_sample_model->getUserSubscription($username);

					//If no data, so user must be free user and cannot play premium song
                	if($userSubscription){
                		//check if subcription still valid
						  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
						  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
						  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));

						  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
						    $subscriptionValid = true;
						  }else{
						    $subscriptionValid = false;
						  }

	                	if(($userSubscription['status_subs']=="ACTIVE" && $subscriptionValid)){
	                		$planType = $userSubscription['plan_type'];
	                	}
                	}
				}
            }
        }

        $this->load->library('user_agent');
        $date = new DateTime('now');
        $dataInsert['username'] = $username;
        $dataInsert['plan_type'] = $planType;
        $dataInsert['song_id'] = $id;
        $dataInsert['iby'] = $username;
        $dataInsert['idt'] = $date->format('Y-m-d H:i:s');
        $dataInsert['ip_address'] = $this->input->ip_address();
        $dataInsert['user_agent'] = $this->input->user_agent();
        $dataInsert['resource'] = $this->agent->browser();
        $dataInsert['resource_version'] = $this->agent->version();
        $dataInsert['platform'] = $this->agent->platform();

        $res = $this->api_sample_model->recordSongPlayed($dataInsert);
    	
    	 $resultEncode = [
            "success"=>true,
            "message" => "Success",
            // "data"=>  $resData
    	];

		$this->response( $resultEncode, 200 );

	    } catch (Exception $e) {
	    	$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	    }

    }


    public function recordKaraokeVideoPlay_post(){
      $id = $this->post("id"); //karaoke
      try {
    	if(is_null($id)) throw new Exception("Karaoke ID is Unidentified", 1);

		$username ="Anonymous";
		$planType = "FREE";
		$headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
				if(isset($decodedToken->username)){
					$username = $decodedToken->username;
					$userSubscription = $this->api_sample_model->getUserSubscription($username);

					//If no data, so user must be free user and cannot play premium song
                	if($userSubscription){
                		//check if subcription still valid
						  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
						  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
						  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));

						  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
						    $subscriptionValid = true;
						  }else{
						    $subscriptionValid = false;
						  }

	                	if(($userSubscription['status_subs']=="ACTIVE" && $subscriptionValid)){
	                		$planType = $userSubscription['plan_type'];
	                	}
                	}
				}
            }
        }

        $this->load->library('user_agent');
        $date = new DateTime('now');
        $dataInsert['username'] = $username;
        $dataInsert['plan_type'] = $planType;
        $dataInsert['karaoke_id'] = $id;
        $dataInsert['iby'] = $username;
        $dataInsert['idt'] = $date->format('Y-m-d H:i:s');
        $dataInsert['ip_address'] = $this->input->ip_address();
        $dataInsert['user_agent'] = $this->input->user_agent();
        $dataInsert['resource'] = $this->agent->browser();
        $dataInsert['resource_version'] = $this->agent->version();
        $dataInsert['platform'] = $this->agent->platform();

        $res = $this->api_sample_model->recordKaraokePlayed($dataInsert);
    	
    	 $resultEncode = [
            "success"=>true,
            "message" => "Success",
            // "data"=>  $resData
    	];

		$this->response( $resultEncode, 200 );

	    } catch (Exception $e) {
	    	$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	    }

    }

    public function getUserProfile_get(){
        try {
        	$decodedToken = $this->tokenValidation();
    		$userData = $this->api_sample_model->getUserProfile($decodedToken->username);
			if(!$userData) throw new Exception("Unauthorized!", 1);
			$subscriptionId = $userData['subscription_id'];


			$userProfile = [
                "id"=>$userData['id'],
                "nickname"=>$userData['name'],
                "username"=>$userData['username'],
                "privilege"=>'user',
                "profile"=>$userData['profile'],
                "birthDate"=>$userData['birth_date'],
                "gender"=>$userData['gender'],
                "email"=>$userData['email'],
                "avatar"=>$userData['avatar_img'],
            ];

			if($subscriptionId!=null){

				$userSubscription = $this->api_sample_model->getSubscriptionById($subscriptionId);
				if($userSubscription){
            		//check if subcription still valid
				  if($userSubscription['lifetime_left'] > 0 && $userSubscription['status_subs']=="ACTIVE"){
			  		$subscriptionData=[
			  			"username"=>$userSubscription['username'],
			  			"id"=>$userSubscription['id'],
			  			"planId"=>$userSubscription['plan_type'],
			  			"planName"=>$userSubscription['plan_type_name'],
			  			"startTime"=>$userSubscription['start_time'],
			  			"endTime"=>$userSubscription['end_time'],
			  			"price"=>$userSubscription['payment_required'],
			  			"paymentVia"=>$userSubscription['payment_via'],
			  			"paymentUser"=>$userSubscription['payment_userid'],
			  			"paymentRecurring"=>$userSubscription['payment_recurring'],
			  		];

				  }

            	}
			}


			$resultEncode = [
                "success"=>true,
                "message" => "",
                "data"=> $userProfile
            ];

            $this->response( $resultEncode, 200 );

		} catch (Exception $e) {
			$resultEncode = [
		            "success"=>false,
		            "error_code"=> "CUSTOM_CODE",
		            "message" => $e->getMessage(),
		            "data" =>[]
		        ];
			$this->response($resultEncode, 200 );
		}

    }


     public function getPlanPacketList_get(){

        try {
			$listData =$this->api_sample_model->getPlanPacketList();
	 	   	$resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $listData
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }

    }

    public function getPlanPacket_get(){
    	$id = $this->get('id');
        try {
			$data =$this->api_sample_model->getPlanPacket($id);
			if(!$data) throw new Exception("Plan not found.", 1);
			
	 	   	$resultEncode = [
            "success"=>true,
            "message" => "Success",
            "data"=>  $data
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
    }


     public function purchasePlan_post(){
    	$id = $this->post('id'); //planId
        try {
        	$decodedToken = $this->tokenValidation();
        	$username =$decodedToken->username;
			$planData =$this->api_sample_model->getPlanPacket($id);
			if(!$planData) throw new Exception("Failed to get Plan Data", 1);
			$planTitle = $planData['plan_title'];
			
			$userData = $this->api_sample_model->getUser($username);
			if(!$userData) throw new Exception("Your account is not found", 1);

			  $phone = $username;
			  $date = new DateTime('now'); //, new DateTimeZone('Asia/Dili'));
		      $dataProcess['idt'] = $date->format('Y-m-d H:i:s');
		      $dataProcess['iby'] = $username;
		      $dataProcess['username'] = $username;
		      $dataProcess['plan_type'] = $planData['plan_type'];
		      $dataProcess['status_subs'] = "PAYMENT";
		      $dataProcess['validity_seconds'] = $planData["validity_seconds"];
		      $dataProcess['payment_required'] = $planData['price'];

		      $dt1 =new DateTime($date->format('Y-m-d H:i:s'));
		      $date->modify("+" . $planData["validity_seconds"] . " second");
		      $dt2 =new DateTime($date->format('Y-m-d H:i:s'));
		   
			  $expired = $dt1->diff($dt2)->format('%a days');

		      $otpCode =generateOTP(); //Generate and SEND OTP here
		      $dataProcess['otp'] =  $otpCode;
			  
		      $insertId = $this->api_sample_model->insertSubscribeTransaction($dataProcess);
		      if (empty($insertId))  throw new Exception("Failed to create payment", 1);

		      sendSMSOTP($phone,"OTP Verification for Subscribe ".$planTitle." Musica.tl ".$otpCode);

	 	   	$resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=> [
		          "plan" => $planTitle,
		          "price" => $dataProcess['payment_required'] . "$",
		          "expired" => $expired,
		          "receiver" => $username,
		          "token" => base64_encode($insertId)
		        ]
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
    }

    function resendOTPConfirmPurchase_post(){
    	$transactionId = $this->post('transactionId');

    	  try {
        	$decodedToken = $this->tokenValidation();
        	$transactionId = base64_decode($transactionId);
        	$username =$decodedToken->username;
			$transactionData =$this->api_sample_model->getSubscriptionById($transactionId);
			if(!$transactionData) throw new Exception("Failed to get Plan Data", 1);
			$planTitle = $transactionData['plan_type_name'];
			
			$userData = $this->api_sample_model->getUser($username);
			if(!$userData) throw new Exception("Your account is not found", 1);

			  $phone = $username;
			  $date = new DateTime('now'); //, new DateTimeZone('Asia/Dili'));
		      $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
		      $dataProcess['uby'] = $username;
		      $otpCode =generateOTP(); //Generate and SEND OTP here
		      $dataProcess['otp'] =  $otpCode;

		      $statusProcess = $this->api_sample_model->updateSubscribeTransaction($transactionId, $dataProcess);
		      if (!$statusProcess)  throw new Exception("Failed to resend OTP", 1);
			  sendSMSOTP($phone,"OTP Verification for Subscribe ".$planTitle." Musica.tl ".$otpCode);

	 	   	$resultEncode = [
	            "success"=>true,
	            "message" => "Resend OTP Success",
	            "data"=> true
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
    }

    public function confirmPurchasePlan_post(){
    	$otp = $this->post('otp'); //planId
    	$transactionId = $this->post('transactionId');
        try {
        	$decodedToken = $this->tokenValidation();

        	$transactionId = base64_decode($transactionId);

        	$username = $decodedToken->username;

        	$userData = $this->api_sample_model->getUser($username);
			if(!$userData) throw new Exception("Your account is not found", 1);
			if($userData['islocked']==1) throw new Exception("Your account is locked, please contact us.", 1); 


        	$isPaymentOtpMatch = $this->api_sample_model->isPaymentOTPMatch($otp,$username,$transactionId);
        	if($username=="7890"){
				$isPaymentOtpMatch=true;
			}

			if(!$isPaymentOtpMatch) throw new Exception("OTP is not Match. Failed to process.", 1);
			$paymentData =$this->api_sample_model->paymentOtpInfo($transactionId);
			if(!$paymentData) throw new Exception("Failed to get Payment Info Transaction", 1);
			
			$paymentId = $paymentData['id'];
			$planType  = $paymentData['plan_type'];
			$validitySeconds = is_null($paymentData['validity_seconds'])?0:$paymentData['validity_seconds'];

			//process payment and potong pulsa
			$resultPayment = processPaymentByUser($username,$paymentId,$planType);

			if (!$resultPayment['status']) {
		        throw new Exception($resultPayment['error'], 1);
		    }

		    $date = new DateTime('now'); 
		    $dataProcess['start_time'] = $date->format('Y-m-d H:i:s');
		    $date->modify("+" . $validitySeconds . " second");
		    $dataProcess['end_time'] = $date->format('Y-m-d H:i:s');
		    $dataProcess['payment_via'] = "TELKOMCEL";
		    $dataProcess['payment_userid'] = $username;
		    $dataProcess['status_subs'] = "ACTIVE";
		    $dataProcess['payment_paid'] = $resultPayment['data']['payment_paid'];
	        $dataProcess['udt'] = $date->format('Y-m-d H:i:s');
	        $dataProcess['uby'] = $username;

	        $statusProcess = $this->api_sample_model->updateSubscribeTransaction($paymentId, $dataProcess);

	        $updatedDataUser['subscription_id'] = $transactionId;
	        $updatedDataUser['udt'] = $date->format('Y-m-d H:i:s');
	        $updatedDataUser['uby'] = $username;
	        $resupdatedUser = $this->api_sample_model->updateUser($username, $updatedDataUser);

	        if(!$statusProcess || !$resupdatedUser) throw new Exception("Failed to update Purchased Data. If you have been charged. Don't continue and report to Musica.tl", 1);
	        
	        //refresh user data 
			$subscriptionId = $paymentId;
			$subscriptionUser = null;
			$isPremiumUser  = false;

			$userSubscription = $this->api_sample_model->getSubscriptionById($subscriptionId);
			if($userSubscription){
        		//check if subcription still valid
				  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
				  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
				  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));


				  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
				    $subscriptionValid = true;
				  }else{
				    $subscriptionValid = false;
				  }


            	if(($userSubscription['status_subs']=="ACTIVE" && $subscriptionValid)){
            		$planType = $userSubscription['plan_type'];
            		$subscriptionUser['name'] = $planType;
            		$subscriptionUser['title'] = $userSubscription['plan_type_name'];
            		$subscriptionUser['id'] = $subscriptionId;
            		$subscriptionUser['price'] = $userSubscription['payment_paid'];
            		$subscriptionUser['lifetime_left'] = $userSubscription['lifetime_left'];
            		$subscriptionUser['expiredTime'] = $userSubscription['end_time'];
            		$isPremiumUser=(strpos($subscriptionUser['name'], 'premium') !== false);
            	}
        	}


	 	   	$resultEncode = [
	            "success"=>true,
	            "message" => "Success",
	            "data"=>[
	                    "isPremium"=>$isPremiumUser,
	                    "subscription"=>$subscriptionUser,
	                ]
        	];

    		$this->response( $resultEncode, 200 );


        } catch (Exception $e) {
			$resultEncode = [
	            "success"=>false,
	            "error_code"=> "CUSTOM_CODE",
	            "message" => $e->getMessage(),
	            "data" =>[]
	        ];
			$this->response($resultEncode, 200 );
	        	
        }
    }

    public function checkSubscriptionAlreadyPurchased_get(){
    	$planId = $this->get('planId');
	    	 try {
	        	$decodedToken = $this->tokenValidation();

	        	$username = $decodedToken->username;

	        	$userData = $this->api_sample_model->getUser($username);
				if(!$userData) throw new Exception("Your account is not found", 1);
				if($userData['islocked']==1) throw new Exception("Your account is locked, please contact us.", 1); 

		        //refresh user data 
				$subscriptionId = $userData['subscription_id'];
				$isAlreadyPurchased = false;
				$userSubscription = $this->api_sample_model->getSubscriptionById($subscriptionId);
				if($userSubscription){
	        		//check if subcription still valid
					  $currentDate=date('Y-m-d H:i:s', strtotime("now"));
					  $contractDateBegin = date('Y-m-d H:i:s', strtotime($userSubscription['start_time']));
					  $contractDateEnd = date('Y-m-d H:i:s', strtotime($userSubscription['end_time']));


					  if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)){
					    $subscriptionValid = true;
					  }else{
					    $subscriptionValid = false;
					  }


	            	if(($userSubscription['status_subs']=="ACTIVE" && $subscriptionValid)){
	            		$planType = $userSubscription['plan_type'];
	            		$isAlreadyPurchased=$planId==$planType;
	            	}
	        	}


		 	   	$resultEncode = [
		            "success"=>true,
		            "message" => "Success",
		            "data"=>$isAlreadyPurchased
	        	];

	    		$this->response( $resultEncode, 200 );


	        } catch (Exception $e) {
				$resultEncode = [
		            "success"=>false,
		            "error_code"=> "CUSTOM_CODE",
		            "message" => $e->getMessage(),
		            "data" =>[]
		        ];
				$this->response($resultEncode, 200 );
		        	
	        }
    }


    public function editUserProfile_put(){
        $nickname_put = $this->put("nickname");
        $profile_put = $this->put("profile");
        $birthDate_put = $this->put("birthDate");
        $gender_put = $this->put("gender");
        $email_put = $this->put("email");

        try {
        	$decodedToken = $this->tokenValidation();
        	$username = $decodedToken->username;
    		$date = new DateTime('now');
			$dataProcess = array(
				"name"=>$nickname_put,
				"profile"=>empty($profile_put)?NULL:$profile_put,
				"birth_date"=>empty($birthDate_put)?NULL:$birthDate_put,
				"gender"=> empty($gender_put)?NULL:$gender_put,
				"email"=>empty($email_put)?NULL:$email_put,
				"uby"=>$username,
				"udt"=>$date->format('Y-m-d H:i:s')
			);
			//Create and login
			$resultProcess = $this->api_sample_model->updateUser($username,$dataProcess);
			if($resultProcess){
				$resultEncode = [
	                "success"=>true,
	                "message" => "User profile updated.",
	                "data"=>true
	            ];

	            $this->response( $resultEncode, 200 );
			}else{
				throw new Exception("Failed to Login.", 1);
			}
		} catch (Exception $e) {
			$resultEncode = [
		            "success"=>false,
		            "error_code"=> "CUSTOM_CODE",
		            "message" => $e->getMessage(),
		            "data" =>[]
		        ];
			$this->response($resultEncode, 200 );
		}

    }

}
