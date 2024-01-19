<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once('Dbh.php');

class Image extends Dbh {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('upload'); 
		$this->load->library('image_lib');
	}
	
	public function testing(){
	    echo "ImageUpload Class TESING";
	}
    
    public function upload_image($field_name,$path) {
        $config['upload_path']          = "./upload/images/$path";
        $config['allowed_types']        = "*";
        $config['encrypt_name']         = TRUE;  

        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload($field_name)) {
            $response = [
                    'status' => false,
                    'message' => strip_tags($this->upload->display_errors())
            ];
            return $response;
        } else {
            $uploadData =  $this->upload->data(); 
            $response = [
                'status' => true,
                'message' => "File Uploaded SuccessFully!!",
                'name'  => $uploadData['file_name']
            ];
           $this->compress_image($uploadData['full_path']);
           return $response;
        }
    }
    private function compress_image($path) {
        
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['quality'] = '60%';  // Compression Quality

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
    }
}