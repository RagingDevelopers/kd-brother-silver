<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Change_password extends CI_Controller
{
	public $form_validation, $input, $db, $purchase;
	public function __construct()
	{
		parent::__construct();
		check_login();
	}

	public function index()
	{
		$validation = $this->form_validation;
		$validation->set_rules('old_password', 'Old Password', 'trim|required');
		$validation->set_rules('new_password', 'New Password', 'trim|required');
		$validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');
		if ($this->form_validation->run() == FALSE) {
			$response = ['success' => false, 'message' => strip_tags(validation_errors())];
			echo json_encode($response);
			return;
		} else {
			$postRequest = $this->input->post();
			$customerArray = [];
			$old_password = sha1($postRequest['old_password']);
            $new_password = sha1($postRequest['new_password']);
            $customerArray['password'] = sha1($postRequest['confirm_password']);
			if($new_password == $customerArray['password']){
				$id = session('id');
				$num_rows = $this->db->select("password")->get_where('users',array('id'=>$id,'password'=>$old_password))->num_rows();
				if($num_rows==1){
					$update = $this->db->where(['id' => $id])->update('users', $customerArray);
					if($update){
						$response = ['success' => true, 'message' => 'Password Change SuccessFully'];
					}else{
						$response = ['success' => false, 'message' => 'Password Change Failed'];
					}
				}else{
					$response = ['success' => false, 'message' => 'Old Password Not Matched'];
				}
			}else{
				$response = ['success' => false, 'message' => 'Old Password and New Password Not Matched'];
			}
		}
		echo json_encode($response);
		return;
	}
}
