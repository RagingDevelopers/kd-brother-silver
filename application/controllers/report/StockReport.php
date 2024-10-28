<?php

class StockReport extends CI_Controller
{

	public $dbh;

	function __construct()
	{
		parent::__construct();
		$this->load->library('Joinhelper');
		$this->load->model('Stock_report_model', 'srm');
		$this->dbh = $this->joinhelper;
		check_login();
	}

    const View = "admin/report/stock_report";
	function index()
	{
        checkPrivilege(privilege["stock_report"]);
		$page_data['page_title'] = 'Stock Report';
		$page_data['item'] = $this->dbh->getResultArray('item');
		return view(self::View, $page_data);
	}

	function ajax()
	{
		$data = $this->input->post();
		$data = $this->security->xss_clean($data);

		$op = [];
		$path = '';

		$path = 'admin/report/tnx/stock_report/ajax_by_item';
		$op['data'] = $this->srm->generateStockReport($data,'item_id');
		$this->load->view($path, $op);
	}
}
