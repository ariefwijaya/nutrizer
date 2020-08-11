<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class PagesRoute extends CI_Controller {

    public function index(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        
        $pageId  = 'home';
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Welcome !";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect("login", 'refresh');
    }

    public function login(){
        if(verifAccess(false)['status']){ redirect("home", 'refresh');}
        $pageId  = 'login';
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Login";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout_nosidebar',$data);
    }

    public function error_404(){
        $pageId  = 'error_404';
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Error! Access Denied";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout_nosidebar',$data);
    }
    
    public function error_403(){
        $pageId  = 'error_403';
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Error! Access Denied";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout_nosidebar',$data);
    }

	public function dashboard(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        
        $pageId  = 'dashboard';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Dashboard";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function manage_user(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'manage_user';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Manage Users";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function manage_kek(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'manage_kek';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Info Covid";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function manage_nutrition(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'manage_nutrition';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Nutritions";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function manage_food_cat(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'manage_food_cat';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Manage Food Category";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }


    public function manage_food(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'manage_food';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Manage Foods";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function setting_profile(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'setting_profile';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Setting Profile";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

    public function setting_mobile(){
        if(!verifAccess(false)['status']){ redirect("login", 'refresh');}
        $pageId  = 'setting_mobile';
        $checkPrivilege = checkPrivilegePage($pageId);
        if(!$checkPrivilege['status']){
            $pageId = $checkPrivilege['error_page'];
        }
        $pagePath = 'pages/'.$pageId;
        $data['pageId'] = $pageId;
        $data['pageTitle']="Settings Mobile App";
        $data['pageContent'] = $this->load->view($pagePath.'/view','',TRUE);
        $data['pageStyle'] = $this->load->view($pagePath.'/style','',TRUE);
        $data['pageScript'] = $this->load->view($pagePath.'/script','',TRUE);
        $this->load->view('layout/v_layout',$data);
        // $this->output->cache(1);
    }

}
        
    /* End of file  PagesRoute.php */
        
                            