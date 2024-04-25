<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lot extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/tnx/lot/report/lot_report";

	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->model('manufacturing/Lot_report_model', 'lrm');
	}

	public function index()
	{
		checkPrivilege(privilege['lot_report']);
		$page_data['page_title']    = 'Lot Report';
		$page_data['customer']      = $this->dbh->getResultArray('customer');
		$page_data['item']          = $this->dbh->getResultArray('item');
		$page_data['group_by']      = [
			'tag'             => 'Tag',
			'lot'             => 'Lot',
			'item'            => 'Item',
		];
		view(self::View, $page_data);
	}

	function ajax_report()
	{
		$data = $this->input->post();
		$data = $this->security->xss_clean($data);
		$op   = [];
		$path = '';
		switch ($data['groupBy']) {
			case 'item':
				$path = 'admin/report/tnx/lot/report/ajax_by_item';
				$op['data'] = $this->lrm->getLotReportByItem($data);
				break;
			case 'lot':
				$path = 'admin/report/tnx/lot/report/ajax_by_lot';
				$op['data'] = $this->lrm->getLotReportByLot($data);
				break;
			case 'tag':
				$path = 'admin/report/tnx/lot/report/ajax_by_tag';
				$op['is_with_ad'] = true;
				$op['data'] = $this->lrm->getLotReportByTag($data);
				break;
			default:
				$path = 'admin/report/tnx/lot/report/ajax_by_tag';
				$op['data'] = $this->lrm->getLotReportByTag($data);
				break;
		}
		$this->load->view($path, $op);
	}

}
