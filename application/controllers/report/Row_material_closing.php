<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_material_closing extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/row_material_closing";

	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->model('stock/Row_material', "stock");
		// library("Joinhelper");
	}

	public function index()
	{
		checkPrivilege(privilege['row_material_closing_stock']);
		$page_data['page_title'] = 'Row Material Closing';
		$page_data['row_material'] = $this->stock->fetch_row_material();
		$page_data['garnu'] = $this->stock->fetch_garnu_name();
		$page_data['process'] = $this->stock->fetch_process();
		return view(self::View, $page_data);
	}

	public function fetchData()
	{
		$postData = $this->security->xss_clean($this->input->post());
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length'];
		// serching coding
		$columnIndex = $postData['order'][0]['column']; // Column index
		$searchValue = $postData['search']['value']; // Search value
		$todate = $postData['todate'] ?? null;
		$fromdate = $postData['fromdate'] ?? null;
		$row_material_id = $postData['row_material_id'] ?? null;
		$garnu_id = $postData['garnu_id'] ?? null;
		$process_id = $postData['process_id'] ?? null;

		$where = "";
		if (!empty($garnu_id)) {
			$where .= "garnu.id = " . $garnu_id . " AND ";
		}
		if (!empty($process_id)) {
			$where .= "process.id = " . $process_id . " AND ";
		}
		$where = rtrim($where, ' AND ');

		if (!empty($where)) {
			$where = "($where)";
		}
		$openingWeight = 0;
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$row_material = $this->stock->fetch_row_material();
		
		foreach ($row_material as $row) {
			$openingWeight = 0;
			$row_material_id = $row->id;
			$openingQuery = "
					SELECT
						SUM(touch) AS total_touch, SUM(weight) AS total_weight, type,row_material
					FROM (
						SELECT given_row_material.touch, given_row_material.weight, 'Debit' AS type,row_material.name as row_material
						FROM given_row_material
						LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
						LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
						LEFT JOIN given ON given_row_material.given_id = given.id
						LEFT JOIN process ON given.process_id = process.id
						WHERE " . (!empty($row_material_id) ? "given_row_material.row_material_id = $row_material_id" : "1") . "
						" . (!empty($garnu_id) ? "AND garnu.id = $garnu_id" : "") . "
						" . (!empty($process_id) ? "AND process.id = $process_id" : "") . "
						UNION ALL
						SELECT receive_row_material.touch, receive_row_material.weight, 'Credit' AS type,row_material.name as row_material
						FROM receive_row_material
						LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
						LEFT JOIN receive ON receive_row_material.received_id = receive.id
						LEFT JOIN garnu ON receive.garnu_id = garnu.id
						LEFT JOIN given ON receive.given_id = given.id
						LEFT JOIN process ON given.process_id = process.id
						WHERE " . (!empty($row_material_id) ? "receive_row_material.row_material_id = $row_material_id" : "1") . "
						" . (!empty($garnu_id) ? "AND garnu.id = $garnu_id" : "") . "
						" . (!empty($process_id) ? "AND process.id = $process_id" : "") . "
					) AS opening_records
					GROUP BY type";
			$openingResult = $this->db->query($openingQuery)->result_array();

			foreach ($openingResult as $key => $r) {
				if ($r['type'] == 'Credit') {
					$openingWeight += $r['total_weight'];
				} else if ($r['type'] == 'Debit') {
					$openingWeight -= $r['total_weight'];
				}
			}
			$records[] = [
				'id' => $row->id,
				'name' => $row->name,
				'weight' => $openingWeight,
				'date' => $row->created_at,
			];
		}
		$totalRecords = count($row_material);

		$data = array();
		$i = $start + 1;
		foreach ($records as $r) {
			$name = $r['name'];
			$id = $r['id'];
			$date = date('Y-m-d');

			$data[] = array(
				'id' => $i,
				'row_material' => "<a class='dropdown-item' target='_blank' href='".base_url('report/row_material_stock/index/'.$id.'/'.$date)."'><b>$name</d></a>",
				'closingWeight' => $r['weight'],
				'date' => $r['date'],
			);
			$i = $i + 1;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecords,
			"aaData" => $data,
		);
		echo json_encode($response);
		exit();
	}
}
