<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
    }
    public function index()
    {
        view([
            "page_title" => "Dashboard",
            "page_name" => "admin/dashboard"
        ]);
    }

    public function logout()
    {
        $this->session->unset_userdata('admin_login');
        flash_message('danger', 'logout Successfully !', 'login');

    }
}
