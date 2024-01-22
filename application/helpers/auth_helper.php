<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    protected $CI;
    public $id;
    public $db;
    public $permission;
    public $user;

    public function __construct()
    {
        $this->CI = ci();
        $this->CI->load->library('session');
        $this->id = $this->CI->session->userdata('admin_id') ?? null;
        $this->db = $this->CI->db;
        $this->user = $this;
    }
    public function user()
    {
        $authUser = $this->db->get_where('user', ['id' => $this->id])->row();
        return $authUser;
    }

    public function check()
    {
        return ($this->id == null) ? false : true;
    }

    public function logout()
    {
        $this->CI->session->sess_destroy();
        return flash()->withSuccess('You are logout SuccessFully')->to('login');
    }

    public function can($permission)
    {
        $givenPermission = session('permission');
        if (!in_array(privilege[$permission], $givenPermission))
            flash()->withError("You Don't have access this resource")->to("dashboard");
        else
            return true;
    }
}
function auth()
{
    return new Auth();
}
