<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



// if (!function_exists('uploadImage')) {
//     function uploadImage($fieldName,$uploadPath,$resizeWidth = 240)
//     {
//         $CI =& get_instance();

//         $config = array();
//         $config['upload_path'] = $uploadPath;//"./upload/ebooks/images";
//         $config['allowed_types'] = 'jpg|png|jpeg';
//         $config['encrypt_name'] = TRUE;
//         $config['file_name'] = "img_" . time();
//         $config['min_width'] = '240';
//         $config['min_height'] = '240';
//         $config['max_width'] = '2000';
//         $config['max_height'] = '2000';
//         $config['max_size'] = '2000';

//         // initialize
//         $CI->load->library('upload', $config, 'uploadImgLib');
//         if ($CI->uploadImgLib->do_upload($fieldName)) {
//             $data = $CI->uploadImgLib->data();
//             $dataFileName = $data['file_name'];
//             //resizeImage
//             $config = array();
//             $config['image_library'] = 'gd2';
//             $config['source_image'] = $uploadPath. $dataFileName;
//             $config['create_thumb'] = TRUE;
//             $config['maintain_ratio'] = TRUE;
//             $config['quality'] = '80%';
//             $config['width'] =$resizeWidth;
//             // $config['height']= 400;
//             // $config['new_image'] = $uploadPath ."thumb_". $dataFileName;
//             $CI->load->library('image_lib', $config);
//             if (!$CI->image_lib->resize()) {
//                 $response['status'] = false;
//                 $response['error'] = $CI->image_lib->display_errors('<p class="text-warning error">', '</p>');
//             } else {
//                 $response['status'] = true;
//                 $response['data'] = $dataFileName;
//                 $CI->image_lib->clear();
//             }
//         } else {
//             $response['status'] = false;
//             $response['error'] = $CI->uploadImgLib->display_errors('<p class="text-warning error">', '</p>');
//         }

//         return $response;
//     }
// }


if (!function_exists('uploadImage')) {
    function uploadImage($fieldName, $uploadPath, $resizeWidth = array(),$newConfig=array())
    {
        $CI = &get_instance();

        $config = array();
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['file_name'] = "img_" . time();
        $config['min_width'] = '100';
        $config['min_height'] = '100';
        $config['max_width'] = '2000';
        $config['max_height'] = '2000';
        $config['max_size'] = '2000';

        foreach ($newConfig as $key => $value) {
            $config[$key]=$value;
        }

        // initialize
        $instance = 'instance' . $fieldName;
        $CI->load->library('upload', $config, $instance);
        if ($CI->$instance->do_upload($fieldName)) {
            $data = $CI->$instance->data();
            $dataFileName = $data['file_name'];

            $resizeError = array();
            $CI->load->library('image_lib');
            for ($i = 0; $i < count($resizeWidth); $i++) {
                $paramResize = $resizeWidth[$i];
                $config = array();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadPath . $dataFileName;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['quality'] = '90%';
                $config['width'] = $paramResize['size'];
                $config['new_image'] = $paramResize['path'] . $dataFileName;
                $CI->image_lib->clear();
                $CI->image_lib->initialize($config);
                if (!$CI->image_lib->resize()) {
                    $resizeError[] = $CI->image_lib->display_errors('', '');
                }
            }

            if (count($resizeError) > 0) {
                $response['status'] = false;
                $response['error'] =  implode(',', $resizeError);
                return $response;
            } else {
                $response['status'] = true;
                $response['data'] = $dataFileName;
                return $response;
            }
        } else {
            $response['status'] = false;
            $response['error'] = $CI->$instance->display_errors('', '');
            return $response;
        }
    }
}

  if(!function_exists('sendSMSOTP')){
        function sendSMSOTP($msisdn,$message){
            //for development purpose
            if($msisdn=="7890") return "";


            $msisdnFormat=(substr($msisdn,0,3)!="670")?("670".$msisdn):$msisdn;

            $data = array("views"=>"vwsentsms","task"=>"sentmessage","header"=>"MUSICA","msisdn"=>$msisdnFormat,"msg"=>$message);     
            $data_string = json_encode($data);                                                                                   
            $ch = curl_init('http://150.242.111.251:81/smsc/index/api');                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                                                                                                                                                                    
            $result = curl_exec($ch);
            $res=json_decode($result,true);
            return $res;
        }
}


 if (!function_exists('generateOTP')) {
        function generateOTP()
        {
            $CI = &get_instance();
            $CI->load->helper('string');
            $randomOTP  = random_string('nozero', 6);
            return $randomOTP;
        }
}

 if (!function_exists('processPaymentByUser')) {
    function processPaymentByUser($username,$trxId,$planType)
    {
        if(empty($username)){
            $data['status'] = false;
            $data['error'] = "Data transaction doesnt meet requirement. Username / MSISDN is missing";  
            return $data;
        }
        
        
        if($planType=="premium_daily"){
            $refillid = "MUSICA1D";
            $paymentPaid = 0.05;
        }else if($planType=="premium_monthly"){
            $refillid = "MUSICA30M";
            $paymentPaid = 1;
        }else if($planType=="premium_weekly"){
            $refillid = "MUSICA7W";
            $paymentPaid = 0.25;
        }else if($planType=="free"){
            $data['status'] = true;
            $data['data'] = array(
                "msisdn"=>$username,
                "trx"=>$trxId,
                "status"=>$planType,
                "payment_paid" =>0
            ); 
            return $data;
        }
        else{
            $data['status'] = false;
            $data['error'] = "Data transaction doesnt meet requirement. Plan Type is not defined $planType";  
            return $data;
        }

        //for testing
        if($username=="7890"){
             $data['status'] = true;
            $data['data'] = array(
                "msisdn"=>$username,
                "trx"=>$trxId,
                "status"=>$planType,
                 "payment_paid" =>$paymentPaid
            ); 
            return $data;
        }

        $msisdn=(substr($username,0,3)!="670")?("670".$username):$username;


        if((substr($msisdn,0,3)!="670")){
             $data['status'] = false;
             $data['error'] = "MSISDN is not valid";  
             return $data;
        }

        //MSISDN 67074085565
        $ch = curl_init('http://172.17.12.63:8280/deductBalanceService?msisdn='.$msisdn.'&trxid='.$trxId.'&refillid='.$refillid.'&sourceChannel=MUSICATL');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        $output = curl_exec($ch);

        if($output===false){
            $data['status'] = false;
            $data['error'] = curl_error($ch);
        }else{
            $sp=explode(' ',$output);
            if(is_array($sp) && isset($sp[2]) && $sp[2]=="0"){
                $data['status'] = true;
                $data['data'] = array(
                    "msisdn"=>$sp[0],
                    "trx"=>$sp[1],
                    "status"=>$sp[2],
                    "payment_paid" =>$paymentPaid
                );   
            }else{
                $data['status'] = false;
                $data['error'] = "Payment failed!. Please try again.";  
            }
        }

        return $data;
    }
}   


if (!function_exists('getConfigValue')) {
    function getConfigValue($configId)
    {
        $CI = &get_instance();
        $CI->load->model('api_sample_model');
        $optVal = $CI->api_sample_model->getConfig($configId);
        if ($optVal) {
            // print_r($optVal['value'],);
          $decoded_val = json_decode($optVal['value'], true);
          if (!is_null($decoded_val) && is_array($decoded_val)) {
            $data=array();
            $data['status'] = true;
            $data['data']=$decoded_val;
            return $data;
          }
        }
        
        $data=array();
        $data['status'] = false;
        $data['error']="Failed to get data!";
        return $data;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($fieldName, $uploadPath,$extension="",$prefix="file")
    {
        $CI = &get_instance();

        $config = array();
        $config['upload_path'] = $uploadPath;
        if($extension!=""){
            $config['allowed_types'] = $extension;    
        }
        
        $config['encrypt_name'] = TRUE;
        $config['file_name'] = $prefix."_" . time();
        // $config['max_size'] = '40000';

        // initialize
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config); 
        if ($CI->upload->do_upload($fieldName)) {
            $data = $CI->upload->data();
            $dataFileName = $data['file_name'];

            $response['status'] = true;
            $response['data'] = $dataFileName;
            return $response;
        } else {
            $response['status'] = false;
            $response['error'] = $CI->upload->display_errors('', '');
            return $response;
        }
    }
}

/**
 * cast_fields
 *
 * Casts string values provided from database into
 * the appropriate datatype of possible.
 */
if ( ! function_exists('cast_fields'))
{
    function cast_fields($fields, $results)
    {
        for($i=0; $i<count($fields); $i++) {
            $name = $fields[ $i ]->name;
            $type = $fields[ $i ]->type;
            $int_types = array(
                'int',
                'tinyint',
                'smallint',
                'mediumint',
                'bigint'
            );
            if(in_array($type, $int_types)) {
                for($j=0; $j<count($results); $j++) {
                    $casted = intval( $results[ $j ]->{ $name } );
                    $results[ $j ]->{ $name } = $casted;
                }
            }
        }
        return $results;
    }

}

/* End of file field_data_helper.php */
/* Location: ./application/helpers/field_data_helper.php */