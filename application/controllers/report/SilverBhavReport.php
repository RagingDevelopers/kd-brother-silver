<?php

defined('BASEPATH') or exit('No direct script access allowed');

class silverBhavReport extends CI_Controller
{
    const View = "admin/report/silver_bhar_report";

    public function __construct()
    {
        parent::__construct();
        check_login();
        // $this->load->model('Sales_model', "sales");
        // $this->load->model('DaybookModel', "daybook");
    }

    public function index()
    {
		checkPrivilege(privilege["silver_bhav_report"]);
		$postRequest = [
			'fromDate' => date('Y-m-d'),
			'toDate' => date('Y-m-d'),
		];
        $page_data['page_title'] = "Silver Bhav Report";
        return view(self::View, $page_data);
    }
	
    public function ajax_silverBhavReport()
	{
		$data = xss_clean($this->input->post());
		$this->load->model('Silver_bhav_model', 'gbm');
		$page_data['data'] = $this->gbm->silverBhavReport($data);
		$this->load->view('admin/report/ajax_silver_bhav_report', $page_data);
	}
}