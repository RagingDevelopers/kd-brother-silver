<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Garnu extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;
    const View = "admin/manufacturing/garnu";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index()
    {
       return view(self::View,[
        'page_title' => "Garnu"
       ]);
    }
}
