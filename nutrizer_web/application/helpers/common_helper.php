<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getConfigValue')) {
    function getConfigValue($configId)
    {
        $CI = &get_instance();
        $CI->load->model('api_model');
        $optVal = $CI->api_model->getConfig($configId);
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