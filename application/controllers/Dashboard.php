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
        return view("admin/dashboard", [
            "page_title" => "Dashboard"
        ]);
    }

    public function logout()
    {
        return auth()->logout();
    }
}
