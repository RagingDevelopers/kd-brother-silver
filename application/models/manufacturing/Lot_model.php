<?php

defined('BASEPATH') or exit('Direct script not allowed');

class Lot_model extends CI_Model
{

	private $dbh;
	function __construct()
	{
		parent::__construct();
		// $this->load->helper('unkidgen');
		$this->load->library('Joinhelper');
		$this->dbh = $this->joinhelper;
	}

	function saveLotCreation($data)
	{
		// pre($data);exit;
		$adminId = session('id');
		if ($data['submit_type'] == 'insert') {
			$uniqueCode = $this->generateUniqueCode();
			$receive_id = $this->db->get_where('receive',array('code'=>$data['tag']))->row_array();

			$insertLotData = [
				'barcode'          => $data['tag'],
				'receive_id'       => $receive_id['id'],
				'tag'              => $uniqueCode,
				'piece'            => $data['pcs'],
				'touch'            => $data['touch'],
				'item_id'          => $data['item_id'],
				'sub_item_id'      => $data['sub_item'],
				'gross_weight'     => $data['gross_weight'],
				'stamp_id'         => $data['stamp'],
				'l_weight'         => $data['l_weight'],
				'net_weight'       => $data['net_weight'],
				'amt'              => $data['amt'],
				'session_id'       => $this->session->userdata('session_id'),
				'admin_id'         => $adminId,
				'creation_date'    => date('Y-m-d'),
			];
			$insert = $this->db->insert('lot_creation', $insertLotData);
			if (!$insert) {
				return false;
			}
			return true;
		} else if ($data['submit_type'] == 'update') {
			return $this->updateLotCreation($data);
		}
	}

	public function generateUniqueCode() {
		$exists = TRUE;
		$uniqueCode = 0;
		while($exists) {
			$uniqueCode = mt_rand(100000, 999999);
			$query = $this->db->get_where('lot_creation', array('tag' => $uniqueCode));
			if ($query->num_rows() == 0) {
				$exists = FALSE;
			}
		}
	
		return $uniqueCode;
	}
	

	function updateLotCreation($data)
	{
		$adminId = session('id');
		$updateLotData = [
			'piece'              => $data['pcs'],
			'touch'              => $data['touch'],
			'item_id'          => $data['item_id'],
			'sub_item_id'      => $data['sub_item'],
			'stamp_id'         => $data['stamp'],
			'gross_weight'     => $data['gross_weight'],
			'l_weight'         => $data['l_weight'],
			'net_weight'       => $data['net_weight'],
			'amt'              => $data['amt'],
			'session_id'       => $this->session->userdata('session_id'),
			'admin_id'         => $adminId
		];
		$this->db->where('id', $data['update_id']);
		$update = $this->db->update('lot_creation', $updateLotData);
		if (!$update) {
			return false;
		}
		return true;
	}

	function datatable_getList($postData)
	{
		$res = [];
		## Read value
		$draw            = $postData['draw'];
		$start           = $postData['start'];
		$rowperpage      = $postData['length']; // Rows display per page
		$columnIndex     = $postData['order'][0]['column']; // Column index
		$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue     = $postData['search']['value']; // Search value

		## Search 
		$searchQuery = "";
		if ($searchValue != '') {
			$searchValue = $this->db->escape('%' . $searchValue . '%');
			$searchQuery = "( LCR.weight LIKE " . $searchValue . " OR
			LCR.customer LIKE " . $searchValue . " OR  
			LCR.pcs LIKE " . $searchValue . " OR  
			LCR.tag LIKE " . $searchValue . " OR  
			LCR.gross_weight LIKE " . $searchValue . " OR  
			LCR.l_weight LIKE " . $searchValue . " OR  
			LCR.net_weight LIKE " . $searchValue . " OR  
			LCR.other_amt LIKE " . $searchValue . " OR  
			LCR.created_at LIKE " . $searchValue . " OR  
			LCR.pending_pcs LIKE " . $searchValue . " OR  
			LCR.done_pcs LIKE " . $searchValue . ") ";
		} else {
			$searchQuery = ' TRUE ';
		}

		## Total number of records without filtering
		$totalRecords = $this->db->query("SELECT COUNT(id) AS `count` FROM melting")->row()->count;

		## Total number of record with filtering
		$totalRecordwithFilter = $this->db->query("SELECT *
													FROM lot_creation_report LCR
													WHERE " . $searchQuery)->num_rows();
		$query                 = "SELECT * FROM lot_creation_report LCR WHERE $searchQuery";
		if ($columnIndex == 0) {
			$query .= " ORDER BY LCR.id DESC ";
		} else {
			if ($columnName == 'weight') {
				$query .= " ORDER BY LCR.weight $columnSortOrder ";
			} else if ($columnName == 'customer') {
				$query .= " ORDER BY LCR.customer $columnSortOrder ";
			} else if ($columnName == 'pcs') {
				$query .= " ORDER BY LCR.piece $columnSortOrder ";
			} else if ($columnName == 'created_at') {
				$query .= " ORDER BY LCR.created_at $columnSortOrder ";
			} else if ($columnName == 'tag') {
				$query .= " ORDER BY LCR.tag $columnSortOrder ";
			} else if ($columnName == 'gross_weight') {
				$query .= " ORDER BY LCR.gross_weight $columnSortOrder ";
			} else if ($columnName == 'l_weight') {
				$query .= " ORDER BY LCR.l_weight $columnSortOrder ";
			} else if ($columnName == 'net_weight') {
				$query .= " ORDER BY LCR.net_weight $columnSortOrder ";
			} else if ($columnName == 'other_amt') {
				$query .= " ORDER BY LCR.other_amt $columnSortOrder ";
			} else if ($columnName == 'done_pcs') {
				$query .= " ORDER BY LCR.done_pcs $columnSortOrder ";
			} else if ($columnName == 'pending_pcs') {
				$query .= " ORDER BY LCR.pending_pcs $columnSortOrder ";
			}
		}
		$query .= " LIMIT $start,$rowperpage ";
		$records = $this->db->query($query)->result_array();
		$data = [];
		$i    = $start + 1;
		foreach ($records as $v) {
			if (!isRestrictedAll(['lot_creation_add', 'lot_creation_edit'])) {
				$link = '<a href="' . site_url('admin/lot/view/' . $v['code']) . '" class="btn btn-secondary btn-sm">View</a>';
			}
			$data[] = [
				'srno'         => $i++,
				'id'           => $link,
				'gross_weight' => $v['gross_weight'],
				'l_weight'     => $v['l_weight'],
				'net_weight'   => $v['net_weight'],
				'other_amt'    => $v['other_amt'],
				'pcs'          => $v['piece'],
				'customer'     => $v['customer'],
				'done_pcs'     => $v['done_pcs'],
				'pending_pcs'  => $v['pending_pcs'],
				'code'         => $v['code'],
				'created_at'   => date('d-m-Y', strtotime($v['created_at']))
			];
		}

		## Response
		$res = array(
			"draw"                 => intval($draw),
			"iTotalRecords"        => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData"               => $data
		);

		return $res;
	}

	function getLotsByCustomer($customer)
	{
		$q    = "SELECT * FROM customer_item WHERE customer_id = " . $this->db->escape($customer);
		$data = $this->db->query($q)->result_array();
		return $data;
	}

	function getDataByTagAndCustomer($lot = 0, $customer_id = 0)
	{
		$isItemExists = $this->dbh->isDataExists('lot_creation', [
			'tag' => $lot,
			// 'customer_id' => $customer_id
		]);

		if ($isItemExists) {
			$q = "SELECT 
			SUM(LC.gross_weight) AS gross_weight,
			SUM(LC.net_weight) AS net_weight,
			(SUM(LC.gross_weight) - SUM(LC.net_weight)) AS l_weight,
			SUM(LC.amt) AS other_amt,
			LC.item_id,
			LC.tag,
			LC.status,
			IG.group_name,
			IG.id AS items_group_id,
			LC.customer_id,
			LC.design_code
			FROM lot_creation LC
			LEFT JOIN items_group IG ON IG.id = LC.items_group_id
			WHERE LC.tag = " . $this->db->escape($lot);

			$LC = $this->db->query($q)->row_array();
			if ($LC['status'] == 0) {
				$data = [
					'status' => true,
					'data'   => $LC
				];
			} else {
				$data = [
					'status'  => false,
					'message' => 'Item is already sold!'
				];
			}
			return $data;
		} else {
			$data = [
				'status'  => false,
				'message' => 'item is not exists in lot'
			];
			return $data;
		}
	}

	function getDataByTagAndCustomerToEdit($lot = 0, $customer_id = 0)
	{
		$isItemExists = $this->dbh->isDataExists('lot_creation', [
			'tag'         => $lot,
			'customer_id' => $customer_id
		]);

		if ($isItemExists) {
			$q = "SELECT 
			SUM(LC.gross_weight) AS gross_weight,
			SUM(LC.net_weight) AS net_weight,
			(SUM(LC.gross_weight) - SUM(LC.net_weight)) AS l_weight,
			SUM(LC.amt) AS other_amt,
			LC.item_id,
			LC.tag,
			LC.status,
			IG.group_name,
			IG.id AS items_group_id,
			LC.customer_id,
			LC.design_code
			FROM lot_creation LC
			LEFT JOIN items_group IG ON IG.id = LC.items_group_id
			WHERE LC.tag = " . $this->db->escape($lot);

			$LC   = $this->db->query($q)->row_array();
			$data = [
				'status' => true,
				'data'   => $LC
			];
			return $data;
		} else {
			$data = [
				'status'  => false,
				'message' => 'item is not exists in lot'
			];
			return $data;
		}
	}

	function getDataByTagAndCustomerEvent($lot = 0, $customer_id = 0)
	{
		$isItemExists = $this->dbh->isDataExists('lot_creation', [
			'tag' => $lot,
		]);

		if ($isItemExists) {
			$q = "SELECT 
			SUM(LC.gross_weight) AS gross_weight,
			SUM(LC.net_weight) AS net_weight,
			(SUM(LC.gross_weight) - SUM(LC.net_weight)) AS l_weight,
			SUM(LC.amt) AS other_amt,
			LC.item_id,
			LC.tag,
			LC.status,
			IG.group_name,
			IG.id AS items_group_id,
			LC.customer_id,
			LC.design_code
			FROM lot_creation LC
			LEFT JOIN items_group IG ON IG.id = LC.items_group_id
			WHERE LC.tag = " . $this->db->escape($lot);

			$LC = $this->db->query($q)->row_array();
			if ($LC['status'] == 0 || 1 == 1) {
				$data = [
					'status' => true,
					'data'   => $LC
				];
			} else {
				$data = [
					'status'  => false,
					'message' => 'Item is already sold!'
				];
			}
			return $data;
		} else {
			$data = [
				'status'  => false,
				'message' => 'item is not exists in lot'
			];
			return $data;
		}
	}
}
