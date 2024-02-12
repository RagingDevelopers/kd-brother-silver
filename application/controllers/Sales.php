<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public $form_validation, $input, $db;
	const View = "admin/sales/sale_report";
    const Create = "admin/sales/create";
    public function __construct()
    {
        parent::__construct();
		check_login();
        $this->load->model('Sales_model', "sales");
    }

    public function index()
	{
        $page_data['page_title'] = 'Sales Report';
        $page_data['party'] = $this->sales->fetch_party();
        return view(self::View, $page_data);
    }

    public function create()
    {
        $page_data['page_title'] = 'Sales';
        $page_data['party'] = $this->sales->fetch_party();
        $page_data['item'] = $this->sales->fetch_item();
        $page_data['stamp'] = $this->sales->fetch_stamp();
        $page_data['unit'] = $this->sales->fetch_unit();
        return view(self::Create, $page_data);
    }
}