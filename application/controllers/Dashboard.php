<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->load->view("common", [
            "page_title" => "Dashboard",
            "page_name" => "admin/dashboard"
        ]);
    }
}
