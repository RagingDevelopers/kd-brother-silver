<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Metal_type_stock extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/metal_type_stock";

	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->model('stock/Metal_type', "metal");
	}

	public function index()
	{
		$page_data['page_title'] = 'Row Material Stock';
		$page_data['metal_type'] = $this->metal->fetch_metal_type();
		$page_data['garnu'] = $this->metal->fetch_garnu_name();
		$page_data['process'] = $this->metal->fetch_process();
		return view(self::View, $page_data);
	}

	function getProcessIdCondition($process_id)
	{
		if (!empty($process_id)) {
			switch ($process_id) {
				case "garnu given":
					return 'AND receive_garnu.id < 0 AND process_metal_type.id < 0';
				case "garnu receive":
					return 'AND garnu_item.id < 0 AND process_metal_type.id < 0';
				case "process receive":
					return 'AND receive_garnu.id < 0 AND garnu_item.id < 0';
				default:
					return '';
			}
		}
		return '';
	}

	public function getData()
	{
		$postData = $this->security->xss_clean($this->input->post());
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length'];

		// Serching coding
		$columnIndex = $postData['order'][0]['column']; // Column index
		$searchValue = $postData['search']['value']; // Search value
		$fromdate = $postData['fromdate'] ?? null;
		$todate = $postData['todate'] ?? null;
		$metal_type_id = $postData['metal_type_id'] ?? null;
		$garnu_id = $postData['garnu_id'] ?? null;
		$touch = $postData['touch'] ?? null;

		$where = "";

		if (!empty($garnu_id)) {
			$where .= "garnu.id = $garnu_id AND ";
		}
		$where = rtrim($where, ' AND ');

		if (!empty($where)) {
			$where = "($where)";
		}

		$q = $this->db->query("
            SELECT SUM(total_count) AS total_records
            FROM (
                SELECT COUNT(*) AS total_count
                FROM garnu_item
                UNION ALL
                SELECT COUNT(*) AS total_count
                FROM receive_garnu
                UNION ALL
                SELECT COUNT(*) AS total_count
                FROM process_metal_type
            ) AS total_records;
        ");
		$records = $q->row_array();
		$totalRecords = $records['total_records'];

		$filteredQueryCondition = !empty($where) ? $where : "TRUE";

		// Calculate opening amount
		$openingTouch = 0;
		$openingWeight = 0;
		if (!empty($fromdate)) {
			$openingQuery = "SELECT 
								SUM(touch) AS total_touch, SUM(weight) AS total_weight, type
							FROM (
								SELECT touch, weight,'garnu given' AS type
								FROM garnu_item
								WHERE garnu_item.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($touch) ? " AND garnu_item.touch = $touch" : "") . "
								" . (!empty($metal_type_id) ? " AND garnu_item.metal_type_id = $metal_type_id" : "") . "
								" . (!empty($garnu_id) ? " AND garnu_item.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT touch, weight,'garnu receive' AS type
								FROM receive_garnu
								WHERE receive_garnu.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
								" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
								" . (!empty($garnu_id) ? " AND receive_garnu.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT process_metal_type.touch, process_metal_type.weight,'process given' AS type
								FROM process_metal_type
								LEFT JOIN given ON process_metal_type.given_id = given.id
								WHERE process_metal_type.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($touch) ? " AND process_metal_type.touch = $touch" : "") . "
								" . (!empty($metal_type_id) ? " AND process_metal_type.metal_type_id = $metal_type_id" : "") . "
								" . (!empty($garnu_id) ? " AND given.garnu_id = $garnu_id" : "") . "
							) AS opening_records
							GROUP BY type";
			$openingResult = $this->db->query($openingQuery)->result_array();
		
			$openingTouch = 0;
			$openingWeight = 0;

			foreach ($openingResult as $r) {
				if ($r['type'] == 'garnu receive' || $r['type'] == 'process given') {
					$openingTouch += $r['total_touch'];
					$openingWeight += $r['total_weight'];
				}
				if ($r['type'] == 'garnu given') {
					$openingTouch -= $r['total_touch'];
					$openingWeight -= $r['total_weight'];
				}
			}
		}

		$sql = "SELECT COUNT(*) AS total_count_filtered FROM (
                SELECT DISTINCT
                    garnu_item.id AS id,
                    garnu_item.created_at AS created_at,
                    'garnu given' AS type
                FROM
                    garnu_item
                LEFT JOIN garnu ON garnu_item.garnu_id = garnu.id
                LEFT JOIN metal_type ON garnu_item.metal_type_id = metal_type.id
                LEFT JOIN given ON garnu.id = given.garnu_id
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND garnu_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND garnu_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND garnu_item.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND garnu_item.metal_type_id = $metal_type_id" : "") . "
                UNION
                SELECT DISTINCT
                    receive_garnu.id AS id,
                    receive_garnu.created_at AS created_at,
                    'garnu receive' AS type
                FROM
                    receive_garnu
                LEFT JOIN garnu ON receive_garnu.garnu_id = garnu.id
                LEFT JOIN metal_type ON receive_garnu.metal_type_id = metal_type.id
                LEFT JOIN given ON garnu.id = given.garnu_id
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND receive_garnu.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_garnu.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
                UNION
                SELECT DISTINCT
                    process_metal_type.id AS id,
                    process_metal_type.created_at AS created_at,
                    'process given' AS type
                FROM
                    process_metal_type
                LEFT JOIN given ON process_metal_type.given_id = given.id
                LEFT JOIN garnu ON given.garnu_id = garnu.id
                LEFT JOIN metal_type ON process_metal_type.metal_type_id = metal_type.id
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND process_metal_type.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND process_metal_type.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND process_metal_type.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND process_metal_type.metal_type_id = $metal_type_id" : "") . "
            ) AS combined_results_filtered";

		$query = $this->db->query($sql);
		$row = $query->row();
		$totalRecordwithFilter = $row->total_count_filtered;

		## Fetch records
		$fetchQueryCondition = !empty($where) ? $where : "TRUE";
		$fetchQuery = "
        SELECT
            *
        FROM
            (
            SELECT
                garnu_item.id AS Id,
                metal_type.name AS metal_type,
                garnu.name AS GarnuName,
                garnu_item.touch AS Touch,
                garnu_item.weight AS Weight,
                garnu_item.created_at AS created_at,
                'garnu given' AS type
            FROM
                garnu_item
            LEFT JOIN garnu ON garnu_item.garnu_id = garnu.id
            LEFT JOIN metal_type ON garnu_item.metal_type_id = metal_type.id
            LEFT JOIN given ON garnu.id = given.garnu_id
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND garnu_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND garnu_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND garnu_item.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND garnu_item.metal_type_id = $metal_type_id" : "") . "
            UNION
            SELECT
                receive_garnu.id AS Id,
                metal_type.name AS metal_type,
                garnu.name AS GarnuName,
                receive_garnu.touch AS Touch,
                receive_garnu.weight AS Weight,
                receive_garnu.created_at AS created_at,
                'garnu receive' AS type
            FROM
                receive_garnu
            LEFT JOIN garnu ON receive_garnu.garnu_id = garnu.id
            LEFT JOIN metal_type ON receive_garnu.metal_type_id = metal_type.id
            LEFT JOIN given ON garnu.id = given.garnu_id
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND receive_garnu.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND receive_garnu.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
            UNION
            SELECT
                process_metal_type.id AS Id,
                metal_type.name AS metal_type,
                garnu.name AS GarnuName,
                process_metal_type.touch AS Touch,
                process_metal_type.weight AS Weight,
                process_metal_type.created_at AS created_at,
                'process given' AS type
            FROM
                process_metal_type
            LEFT JOIN given ON process_metal_type.given_id = given.id
            LEFT JOIN garnu ON given.garnu_id = garnu.id
            LEFT JOIN metal_type ON process_metal_type.metal_type_id = metal_type.id
            WHERE
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND process_metal_type.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND process_metal_type.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($touch) ? " AND process_metal_type.touch = $touch" : "") . "
                " . (!empty($metal_type_id) ? " AND process_metal_type.metal_type_id = $metal_type_id" : "") . "
            ) AS combined_data
        ORDER BY
            created_at ASC
        LIMIT $rowperpage OFFSET $start";
		$query = $this->db->query($fetchQuery);
		$records = $query->result_array();


		$data = array();
		$i = $start + 1;
		$closingTouch = 0;
		$closingWeight = 0;

		foreach ($records as $r) {
			$cweight = '--';

			if ($r['type'] == 'garnu receive' || $r['type'] == 'process given') {
				$cweight = $r['Weight'];
				$closingTouch += $r['Touch'];
				$closingWeight += $r['Weight'];
			}
			if ($r['type'] == 'garnu given') {
				$closingTouch -= $r['Touch'];
				$closingWeight -= $r['Weight'];
			}

			$data[] = array(
				'id' => $i,
				'row_material' => $r['metal_type'] ?? '--',
				'garnu' => $r['GarnuName'] ?? '--',
				'process' => $r['type'],
				'type' => $r['type'],
				'touch' => $r['Touch'] ??  '--',
				'cweight' => $cweight,
				'dweight' => ($r['type'] == 'garnu given') ? $r['Weight'] : '--',
				'date' => date("d-m-Y g:i A", strtotime($r['created_at'])),
				'closingWeight' => $closingWeight,
			);
			$i++;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"closingTouch" => $closingTouch,
			"closingWeight" => $closingWeight,
			"openingTouch" => $openingTouch,
			"openingWeight" => $openingWeight,
		);
		echo json_encode($response);
		exit();
	}
}
