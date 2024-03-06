<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_material_stock extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/row_material_stock";

	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->model('stock/Row_material', "stock");
		// library("Joinhelper");
	}

	public function index($row_material_id = null, $from_date = null)
	{
		checkPrivilege(privilege['row_material_stock']);
		$page_data['page_title'] = 'Row Material Stock';
		$page_data['row_material'] = $this->stock->fetch_row_material();
		$page_data['garnu'] = $this->stock->fetch_garnu_name();
		$page_data['process'] = $this->stock->fetch_process();
		$page_data['row_material_id'] = $row_material_id;
		$page_data['from_date'] = $from_date;
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
		if (!empty($fromdate)) {
			$openingQuery = "SELECT 
								SUM(touch) AS total_touch, SUM(weight) AS total_weight, type
							FROM (
								SELECT given_row_material.touch, given_row_material.weight,'Debit' AS type
								FROM given_row_material
								LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
								LEFT JOIN given ON given_row_material.given_id = given.id
								LEFT JOIN process ON given.process_id = process.id
								WHERE given_row_material.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
								" . (!empty($garnu_id) ? " AND garnu.id = $garnu_id" : "") . "
								" . (!empty($process_id) ? " AND process.id = $process_id" : "") . "
								UNION ALL
								SELECT receive_row_material.touch, receive_row_material.weight,'Credit' AS type
								FROM receive_row_material
								LEFT JOIN receive ON receive_row_material.received_id = receive.id
								LEFT JOIN garnu ON receive.garnu_id = garnu.id
								LEFT JOIN given ON receive.given_id = given.id
								LEFT JOIN process ON given.process_id = process.id
								WHERE receive_row_material.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
								" . (!empty($garnu_id) ? " AND garnu.id = $garnu_id" : "") . "
								" . (!empty($process_id) ? " AND process.id = $process_id" : "") . "
							) AS opening_records
							GROUP BY type";
			$openingResult = $this->db->query($openingQuery)->result_array();

			$openingWeight = 0;

			foreach ($openingResult as $r) {
				if ($r['type'] == 'Credit') {
					$openingWeight += $r['total_weight'];
				}
				if ($r['type'] == 'Debit') {
					$openingWeight -= $r['total_weight'];
				}
			}
		}

		## Total number of records without filtering
		$q = $this->db->query("
                SELECT COUNT(*) as total_count FROM (
                SELECT
                1
                FROM
                receive_row_material
                LEFT JOIN receive ON receive_row_material.received_id = receive.id
                LEFT JOIN garnu ON receive.garnu_id = garnu.id
                LEFT JOIN given ON receive.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
                UNION ALL
                SELECT
                1
                FROM
                given_row_material
                LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                LEFT JOIN given ON given_row_material.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
        ) AS combined_results");

		$records = $q->row_array();
		$totalRecords = $records['total_count'];

		## Total number of record with filtering
		$filteredQueryCondition = !empty($where) ? $where : "TRUE";
		$filteredQuery = $this->db->query("
                    SELECT COUNT(*) as total_count_filtered FROM (
                    SELECT
                        receive_row_material.id                                 
                    FROM
                        receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE
					$filteredQueryCondition
					" . (!empty($fromdate) ? "AND receive_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
                    UNION ALL
                    SELECT
                        given_row_material.id                        
                    FROM
                        given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE 
					$filteredQueryCondition
					" . (!empty($fromdate) ? "AND given_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND given_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
        ) AS combined_results_filtered");

		$records = $filteredQuery->row_array();
		$totalRecordwithFilter = $records['total_count_filtered'];

		## Fetch records
		$fetchQueryCondition = !empty($where) ? $where : "TRUE";
		$fetchQuery = "
                    SELECT * FROM (
                    SELECT
                    receive_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    receive_row_material.touch as Touch,
                    receive_row_material.weight as Weight,
                    receive_row_material.quantity as Quantity,
                    receive_row_material.created_at as Date,               
                    'Credit' as Type   
                    FROM
                    receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE
					$fetchQueryCondition
					" . (!empty($fromdate) ? "AND receive_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
                    UNION
                    SELECT
                    given_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    given_row_material.touch as Touch,
                    given_row_material.weight as Weight,
                    given_row_material.quantity as Quantity,
                    given_row_material.created_at as Date,
                    'Debit' as Type   
                    FROM
                    given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE 
					$fetchQueryCondition
					" . (!empty($fromdate) ? "AND given_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND given_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
                    ) AS combined_results
					ORDER BY
           			Date ASC
                LIMIT $rowperpage OFFSET $start";

		$query = $this->db->query($fetchQuery);
		$records = $query->result_array();

		$data = array();
		$i = $start + 1;
		$closingTouch = 0;
		$closingWeight = 0;

		foreach ($records as $r) {
			$type = $r['Type'];
			if ($type == "Credit") {
				$closingWeight += $r['Weight'];
			}
			if ($type == 'Debit') {
				$closingWeight -= $r['Weight'];
			}

			$data[] = array(
				'id' => $i,
				'row_material' => $r['RowMaterial'],
				'garnu' => $r['GarnuName'],
				'process' => $r['ProcessName'],
				'touch' => $r['Touch'] ?? '--',
				'quantity' => $r['Quantity'] ?? '--',
				'credit' => ($type == 'Credit') ? $r['Weight'] : '--',
				'debit' => ($type == 'Debit') ? $r['Weight'] : '--',
				'date' => date("d-m-Y g:i A", strtotime($r['Date'])),
				'closingWeight' => $closingWeight,
			);
			$i = $i + 1;
		}


		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"closingWeight" => $closingWeight,
			"openingWeight" => $openingWeight,
		);
		echo json_encode($response);
		exit();
	}
}
