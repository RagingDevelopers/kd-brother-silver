<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
    	library("dbh");
    	
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY',''));");

    }
    public function index()
    {
		// $page_data['process_data'] = $this->db->select('*')->from('given')->where('id', $pid)->get()->row_array();
		// $page_data['given_row_material'] = $this->db->select('*')->from('given_row_material')->where(array('given_id' => $pid, 'garnu_id' => $id))->get()->result_array();
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['metal_type'] = $this->db->select('id,name')->from('metal_type')->get()->result_array();
		// $page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where('garnu_id', $id)->order_by('code', 'DESC')->get()->result_array();
		// echo "<pre>"; print_r($page_data);;exit;
		// $page_data['table'] = $this->db->select('given.*,customer.name AS customer_name, process.name AS process_name')->from('given')->where('garnu_id', $id)->join('process', 'given.process_id = process.id', 'left')->join('customer', 'given.worker_id = customer.id', 'left')->get()->result();
        // return view("admin/dashboard", [
        //     "page_title" => "Dashboard"
        // ]);

		$page_data['page_title'] = 'Dashboard';
		return view('admin/dashboard', $page_data);
    }

    public function logout()
    {
        return auth()->logout();
    }
}
