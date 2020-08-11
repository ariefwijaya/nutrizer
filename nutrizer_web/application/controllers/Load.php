<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *
 * Controller Load
 *
 * This controller for ...
 */

class Load extends CI_Controller
{
  private $filePath;
  public function __construct()
  {
    parent::__construct();

    $this->filePath = MY_PRIVATE_ASSETS;
    // $this->filePath="D:/CMD/musica_files/";
    // $this->load->library('encryption');
  }

  public function advImage($imageFile)
  {
    $filePath = $this->filePath . "images/adv/";
    $filename = $filePath . base64_decode($imageFile);
    $fileDefault = $filePath . "default.jpg";
    if (file_exists($filename)) {
      $fileData = file_get_contents($filename);
      $mimeType = get_mime_by_extension($filename);
    } else {
      $fileData = file_get_contents($fileDefault);
      $mimeType = get_mime_by_extension($fileDefault);
    }
    $this->output->set_content_type($mimeType);
    $this->output->set_output($fileData);
  }

  public function getImage($type = "", $imageFile = "")
  {

    $imagePath = $this->filePath . "images/";
    $caseType = str_replace("_thumb","",strtolower($type)); 
    $thumbPath = ((strpos($type, "thumb")!==FALSE)? "thumb/":"");
    
    switch ($caseType) {
      case'user':
        $_filePath = $imagePath . "avatar/".$thumbPath;
        $filename = $_filePath . ($imageFile);
        $fileExist = file_exists($filename) ? $filename : ($_filePath . "default.png");
        break;
      case'nutrition':
          $_filePath = $imagePath . "nutrition/".$thumbPath;
          $filename = $_filePath . ($imageFile);
          $fileExist = file_exists($filename) ? $filename : ($_filePath . "default.png");
          break;
      case'guide':
        $_filePath = $imagePath . "guide/".$thumbPath;
        $filename = $_filePath . ($imageFile);
        $fileExist = file_exists($filename) ? $filename : ($_filePath . "default.png");
        break;
      default:
        $_filePath = $imagePath;
        $filename = $_filePath . ($imageFile);
        $fileExist = file_exists($filename) ? $filename : ($_filePath . "default.jpg");
        break;
    }

    $fileData = file_get_contents($fileExist);
    $mimeType = get_mime_by_extension($fileExist);
    $this->output->set_header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
    $this->output->set_content_type($mimeType);
    $this->output->set_output($fileData);
  }

}


/* End of file Load.php */
/* Location: ./application/controllers/Load.php */
