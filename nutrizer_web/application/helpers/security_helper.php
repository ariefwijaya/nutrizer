<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



if (!function_exists('encryptData')) {
    function encryptData($data)
    {
        $CI = &get_instance();
        $CI->load->library('encryption');
        return $CI->encryption->encrypt($data);
    }
}


if (!function_exists('decryptData')) {
    function decryptData($data)
    {
        $CI = &get_instance();
        $CI->load->library('encryption');
        return $CI->encryption->decrypt($data);
    }
}

if (!function_exists('unHexString')) {
    function unHexString($data)
    {
        return pack('H*', (string) $data);
    }
}

if (!function_exists('hexString')) {
    function hexString($data)
    {
        return bin2hex($data);
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        $CI = &get_instance();
        return $CI->session->has_userdata('username');
    }
}

if (!function_exists('isMenuActive')) {
    function isMenuActive($menu, $pageId)
    {
        if ($pageId == $menu) return true;
        else false;
    }
}

if (!function_exists('verifAccess')) {
    function verifAccess($printOutput = true)
    {
        $CI = &get_instance();
        if (!$CI->session->has_userdata('logged_in')) {
            $data = array();
            $data['status'] = false;
            $data['error'] = "You don't have access! Please ensure that You're logged in.";
            $data['code'] = 401;
            if ($printOutput) {
                $CI->output->set_content_type('application/json')
                    ->set_output(json_encode($data))
                    ->_display();
                exit;
            } else {
                return $data;
            }
        } else {
            if (!$printOutput) {
                $data = array();
                $data['status'] = true;
                $data['data'] = "Accessible";
                return $data;
            }
        }
    }
}

if (!function_exists('getUserInfo')) {
    function getUserInfo()
    {
        $CI = &get_instance();
        // $CI->load->helper('text');
        // word_limiter($userInfo[''], 80,"");
        if ($CI->session->has_userdata('logged_in') && $CI->session->userdata('logged_in') == true) {
            $username = $CI->session->userdata('username');
            $CI->load->model("admin_model");
            $userInfo = $CI->admin_model->getAdminUser($username);
            if ($userInfo) {
                return array(
                    "name" => $userInfo['name'],
                    "email" => $userInfo['email'],
                    "privilege" => $userInfo['privilege_id'],
                    "avatar" => $userInfo['avatar_img'],
                    "privilege_name" => $userInfo['privilege_name'],
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('getUserNameAcronym')) {
    function getUserNameAcronym()
    {
        $CI = &get_instance();
        if ($CI->session->has_userdata('name')) {
            $words = explode(" ", $CI->session->userdata('name'));
            $acronym = "";
            if (count($words) == 0) {
                $acronym = "-";
            } else if (count($words) == 1) {
                $acronym .= $words[0][0];
            } else if (count($words) == 2) {
                $acronym .= $words[0][0];
                $acronym .= $words[1][0];
            }

            return $acronym;
        } else {
            return "-";
        }
    }
}


if (!function_exists('checkPrivilegePage')) {
    function checkPrivilegePage($pageId)
    {
        $CI = &get_instance();
        $username = $CI->session->userdata('username');
        $CI->load->model("admin_model");
        $userInfo = ($CI->session->has_userdata('logged_in') && $CI->session->userdata('logged_in') == true) ? $CI->admin_model->getAdminUser($username) : false;

        $errorPageId= "error_403";

        if ($userInfo != false) {

            $data = array(
                "name" => $userInfo['name'],
                "email" => $userInfo['email'],
                "privilege" => $userInfo['privilege_id'],
                "avatar" => $userInfo['avatar_img'],
                "privilege_name" => $userInfo['privilege_name'],
            );

            $pageId =  strtolower($pageId);
            if ((strtolower($userInfo['privilege_name']) == "administrator" && ($pageId == "dashboard" ||
                    $pageId == "manage_user" ||
                    $pageId == "manage_kek" ||
                    $pageId == "manage_food_cat" ||
                    $pageId == "manage_food" ||
                    $pageId == "manage_nutrition" ||
                    $pageId=="setting_profile" ||
                    $pageId=="setting_mobile"))
                ||
                (strtolower($userInfo['privilege_name']) == "analyzer" && (
                    $pageId == "dashboard" ||
                    // $pageId == "dashboard_artist_admin" ||
                    $pageId == "manage_user" ||
                    $pageId == "manage_song" 
                    // ||
                    // $pageId == "manage_genre" ||
                    // $pageId == "manage_artist" ||
                    // $pageId == "manage_album" ||
                    // $pageId == "manage_group" ||
                    // $pageId == "manage_playlist" ||  $pageId=="manage_karaoke_video" 
                    
                    ))
            ) {
                $response = array();
                $response['status'] = true;
                $response['data'] = $data;
                return $response;
            }

            $response = array();
            $response['status'] = false;
            $response['error'] = "You don't have access!";
            $response['error_page'] = $errorPageId;
            return $response;
        } else {
            $response = array();
            $response['status'] = false;
            $response['error'] = "You don't have access! Please ensure that You're logged in.";
            $response['error_page'] = $errorPageId;
            return $response;
        }
    }
}
