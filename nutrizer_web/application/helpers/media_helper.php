<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('smartReadFile')) {
    /**
     * Reads the requested portion of a file and sends its contents to the client with the appropriate headers.
     * 
     * This HTTP_RANGE compatible read file function is necessary for allowing streaming media to be skipped around in.
     * 
     * @param string $location
     * @param string $filename
     * @param string $mimeType
     * @return void
     * 
     * @link https://groups.google.com/d/msg/jplayer/nSM2UmnSKKA/Hu76jDZS4xcJ
     * @link http://php.net/manual/en/function.readfile.php#86244
     */
    function smartReadFile($location, $filename, $mimeType = 'application/octet-stream')
    {
        if (!file_exists($location)) {
            header("HTTP/1.1 404 Not Found");
            return;
        }

        $size    = filesize($location);
        $time    = date('r', filemtime($location));

        $fm        = @fopen($location, 'rb');
        if (!$fm) {
            header("HTTP/1.1 505 Internal server error");
            return;
        }

        $begin    = 0;
        $end    = $size - 1;

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
                $begin    = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end    = intval($matches[2]);
                }
            }
        }

        if (isset($_SERVER['HTTP_RANGE'])) {
            header('HTTP/1.1 206 Partial Content');
        } else {
            header('HTTP/1.1 200 OK');
        }

        header("Content-Type: $mimeType");
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Accept-Ranges: bytes');
        header('Content-Length:' . (($end - $begin) + 1));
        if (isset($_SERVER['HTTP_RANGE'])) {
            header("Content-Range: bytes $begin-$end/$size");
        }
        header("Content-Disposition: inline; filename=$filename");
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: $time");

        $cur    = $begin;
        fseek($fm, $begin, 0);
        $sample = 16;
        while (!feof($fm) && $cur <= $end && (connection_status() == 0)) {
            print fread($fm, min(1024 * $sample, ($end - $cur) + 1));
            $cur += 1024 * $sample;
        }
    }
}


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

if (!function_exists('compressImage')) {
    function compressImage($uploadPath,$filename, $resizeWidth = array(),$newConfig=array())
    {
        $CI = &get_instance();
        // initialize
        $resizeError = array();
        $CI->load->library('image_lib');
        for ($i = 0; $i < count($resizeWidth); $i++) {
            $paramResize = $resizeWidth[$i];
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $uploadPath . $filename;
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = '90%';
            $config['width'] = $paramResize['size'];
            $config['new_image'] = $paramResize['path'] . $filename;
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
            $response['data'] = "Compressed Success";
            return $response;
        }
        
    }
}


if (!function_exists('uploadAudio')) {
    function uploadAudio($fieldName, $uploadPath)
    {
        $CI = &get_instance();

        $config = array();
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'mp3';
        $config['encrypt_name'] = TRUE;
        $config['file_name'] = "audio_" . time();
        $config['max_size'] = '40000';

        // initialize
        $CI->load->library('upload', $config, 'uploadAudioLib');
        if ($CI->uploadAudioLib->do_upload($fieldName)) {
            $data = $CI->uploadAudioLib->data();
            $dataFileName = $data['file_name'];

            $response['status'] = true;
            $response['data'] = $dataFileName;
            return $response;
        } else {
            $response['status'] = false;
            $response['error'] = $CI->uploadAudioLib->display_errors('', '');
            return $response;
        }
    }
}

if (!function_exists('uploadLyric')) {
    function uploadLyric($fieldName, $uploadPath)
    {
        $CI = &get_instance();

        $config = array();
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = TRUE;
        $config['file_name'] = "lyric_" . time();
        $config['max_size'] = '500';

        // initialize
        $CI->load->library('upload', $config, 'uploadLyricLib');
        if ($CI->uploadLyricLib->do_upload($fieldName)) {
            $data = $CI->uploadLyricLib->data();
            $dataFileName = $data['file_name'];

            $response['status'] = true;
            $response['data'] = $dataFileName;
            return $response;
        } else {
            $response['status'] = false;
            $response['error'] = $CI->uploadLyricLib->display_errors('', '');
            return $response;
        }
    }


    if (!function_exists('uploadVideo')) {
        function uploadVideo($fieldName, $uploadPath)
        {
            $CI = &get_instance();
    
            $config = array();
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'mp4';
            $config['encrypt_name'] = TRUE;
            $config['file_name'] = "video_" . time();
            $config['max_size'] = '50000'; //kbytes
    
            // initialize
            $CI->load->library('upload', $config, 'uploadVideoLib');
            if ($CI->uploadVideoLib->do_upload($fieldName)) {
                $data = $CI->uploadVideoLib->data();
                // $dataFileName = $data['file_name'];
    
                $response['status'] = true;
                $response['data'] = $data;
                return $response;
            } else {
                $response['status'] = false;
                $response['error'] = $CI->uploadVideoLib->display_errors('', '');
                return $response;
            }
        }
    }
}
