<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->session->userdata('admin_login')) {
			return redirect('dashboard');
		}
		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
		$this->form_validation->set_rules('password', 'Password ', 'trim|required');

		if ($this->form_validation->run() == true) {
			$login_user = [
				'mobile' => $this->security->xss_clean($this->input->post("mobile")),
				'password' => sha1($this->security->xss_clean($this->input->post("password")))
			];
			if ($this->db->get_where('users', ['mobile' => $login_user['mobile']])->num_rows() == 1) {

				if ($this->db->get_where('users', $login_user)->num_rows() == 1) {

					$user = $this->db->get_where('users', $login_user)->row_array();
					$loged_user = array(
						'id' => $user['id'],
						'mobile' => $user['mobile'],
						'name' => $user['name'],
						'user_type' => $user['type'],
						'login' => true
					);
					// $this->session->set_userdata('admin_login', $loged_user);
					$this->session->set_userdata('id', $loged_user['id']);
					setSession('admin_login', $loged_user);
					setSession('is_admin', $user['type']);
					// setSession('is_admin', ($user['type'] == "ADMIN"));
					setSession("admin_id", $user["id"]);
					setSession('permission', explode(',', $user['permission']));
					$this->session->set_flashdata('success', "You are logged in successfully : {$user['name']}");
					return redirect('dashboard');
				} else {
					$this->session->set_flashdata('error', 'Enter valid Password');
					return redirect('login');
				}
			} else {
				$this->session->set_flashdata('error', 'Invalid Mobile Number');
				return redirect('login');
			}
		} else {
			$this->load->view('login');
		}
	}
	public function logout()
	{
		return auth()->logout();
	}
}
