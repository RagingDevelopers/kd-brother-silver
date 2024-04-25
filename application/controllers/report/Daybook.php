<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Daybook extends CI_Controller
{
    const View = "admin/report/daybook";
    const pageTitle = "Daybook Report";

    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model('Sales_model', "sales");
        $this->load->model('DaybookModel', "daybook");
    }

    public function index()
    {
		checkPrivilege(privilege["daybook_report"]);
		$postRequest = [
			'fromDate' => date('Y-m-d'),
			'toDate' => date('Y-m-d'),
		];
		$process = $this->db->select('id,name')->get('process')->result_array();
		$processData = $this->daybook->processData($postRequest, $process);
		$page_data['processData'] = $processData;
        $page_data['page_title'] = self::pageTitle;
        $page_data['party'] = $this->sales->fetch_party();
        return view(self::View, $page_data);
    }
	
    public function search()
    {
		$postRequest = $this->security->xss_clean($this->input->post());
		$process = $this->db->select('id,name')->get('process')->result_array();
		$processData = $this->daybook->processData($postRequest, $process);
		
        $purchases = $this->daybook->getPurchase($postRequest);
        $purchasesReturn = $this->daybook->getPurchaseReturn($postRequest);

        $sales = $this->daybook->getSale($postRequest);
        $salesReturn = $this->daybook->getSaleReturn($postRequest);

        $page_data['page_title'] = self::pageTitle;
        $page_data['postData'] = $postRequest;
        $page_data['purchases'] = $purchases;
        $page_data['purchasesReturn'] = $purchasesReturn;
        $page_data['sales'] = $sales;
        $page_data['salesReturn'] = $salesReturn;
        $page_data['processData'] = $processData;

        return view(self::View, $page_data);
    }
}
