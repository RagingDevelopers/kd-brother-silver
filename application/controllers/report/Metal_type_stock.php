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
		checkPrivilege(privilege['metal_type_stock']);
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

		$where2 = "";
		if (!empty($garnu_id)) {
			$where2 .= "main_garnu_item.garnu_id = $garnu_id AND ";
		}
		$where2 = rtrim($where2, ' AND ');
		if (!empty($where2)) {
			$where2 = "($where2)";
		}

		$query = "
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
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM jama
				WHERE jama.type = 'fine'
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM baki
				WHERE baki.type = 'fine'
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM main_garnu_item
			) AS total_records;
		";
		$result = $this->db->query($query)->row_array();
		$totalRecords = $result['total_records'];


		$filteredQueryCondition = !empty($where) ? $where : "TRUE";
		$filteredQueryCondition2 = !empty($where2) ? $where2 : "TRUE";
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		// Calculate opening amount
		$openingTouch = 0;
		$openingWeight = 0;
		if (!empty($fromdate)) {
			$openingQuery = "SELECT 
								SUM(touch) AS total_touch, SUM(weight) AS total_weight, type
							FROM (
								SELECT touch, weight, 'garnu given' AS type, garnu_item.created_at AS created_at
								FROM garnu_item
								WHERE garnu_item.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND garnu_item.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND garnu_item.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND garnu_item.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT touch, weight, 'garnu receive' AS type, receive_garnu.created_at AS created_at
								FROM receive_garnu
								WHERE receive_garnu.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND receive_garnu.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT process_metal_type.touch, process_metal_type.weight, 'process given' AS type, process_metal_type.created_at AS created_at
								FROM process_metal_type
								LEFT JOIN given ON process_metal_type.given_id = given.id
								WHERE process_metal_type.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND process_metal_type.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND process_metal_type.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND given.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT jama.gross AS touch, jama.purity AS weight, 'jama' AS type, jama.created_at AS created_at
								FROM jama
								WHERE  jama.type = 'fine' AND jama.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND jama.gross = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND jama.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND jama.id = 0 " : "") . "
								UNION ALL
								SELECT baki.gross AS touch, baki.purity AS weight, 'baki' AS type, baki.created_at AS created_at
								FROM baki
								WHERE baki.type = 'fine' AND baki.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($touch) ? " AND baki.gross = $touch" : "") . "
								" . (!empty($metal_type_id) ? " AND baki.metal_type_id = $metal_type_id" : "") . "
								" . (!empty($garnu_id) ? " AND baki.id = 0 " : "") . "
								UNION ALL
								SELECT touch, weight, 'garnu given' AS type, main_garnu_item.created_at AS created_at
								FROM main_garnu_item
								WHERE main_garnu_item.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND main_garnu_item.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND main_garnu_item.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND main_garnu_item.garnu_id = $garnu_id" : "") . "
							) AS opening_records
							GROUP BY type
							ORDER BY created_at ASC";
			$openingResult = $this->db->query($openingQuery)->result_array();

			$openingTouch = 0;
			$openingWeight = 0;

			foreach ($openingResult as $r) {
				if ($r['type'] == 'garnu receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
					$openingTouch += $r['total_touch'];
					$openingWeight += $r['total_weight'];
				}
				if ($r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
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
				UNION
				SELECT DISTINCT
					jama.id AS Id,
					jama.created_at AS created_at,
					'jama' AS type
				FROM
					jama
				LEFT JOIN metal_type ON jama.metal_type_id = metal_type.id
				WHERE
					jama.type = 'fine'
					" . (!empty($fromdate) ? "AND jama.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($todate) ? " AND jama.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND jama.gross = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND jama.metal_type_id = $metal_type_id" : "") . "
				UNION
				SELECT DISTINCT
					baki.id AS Id,
					baki.created_at AS created_at,
					'baki' AS type
				FROM
					baki
					LEFT JOIN metal_type ON baki.metal_type_id = metal_type.id
				WHERE
					baki.type = 'fine'
				" . (!empty($fromdate) ? "AND baki.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($todate) ? " AND baki.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND baki.gross = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND baki.metal_type_id = $metal_type_id" : "") . "
				UNION
				SELECT DISTINCT
                    main_garnu_item.id AS id,
                    main_garnu_item.created_at AS created_at,
                    'main garnu given' AS type
                FROM
                    main_garnu_item
					LEFT JOIN main_garnu ON main_garnu_item.garnu_id = main_garnu.id
					LEFT JOIN metal_type ON main_garnu_item.metal_type_id = metal_type.id
                WHERE
                    $filteredQueryCondition2" . (!empty($fromdate) ? " AND main_garnu_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND main_garnu_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND main_garnu_item.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND main_garnu_item.metal_type_id = $metal_type_id" : "") . "
            ) AS combined_results_filtered";

		$query = $this->db->query($sql);
		$row = $query->row();
		$totalRecordwithFilter = $row->total_count_filtered;

		## Fetch records
		$fetchQueryCondition = !empty($where) ? $where : "TRUE";
		$fetchQueryCondition2 = !empty($where2) ? $where2 : "TRUE";
		$fetchQuery = "
        SELECT
            *
        FROM
            (
            SELECT
                garnu_item.id AS Id,
                metal_type.name AS metal_type,
                garnu.name AS GarnuName,
                COALESCE(garnu_item.touch, 0) AS Touch,
            	COALESCE(garnu_item.weight, 0) AS Weight,
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
                COALESCE(receive_garnu.touch, 0) AS Touch,
            	COALESCE(receive_garnu.weight, 0) AS Weight,
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
                COALESCE(process_metal_type.touch, 0) AS Touch,
            	COALESCE(process_metal_type.weight, 0) AS Weight,
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
            UNION
            SELECT
                jama.id AS Id,
                metal_type.name AS metal_type,
                jama.mode AS GarnuName,
                COALESCE(jama.gross, 0) AS Touch,
            	COALESCE(jama.purity, 0) AS Weight,
                jama.created_at AS created_at,
                'jama' AS type
            FROM
                jama
            LEFT JOIN metal_type ON jama.metal_type_id = metal_type.id
            WHERE
				jama.type = 'fine'
                " . (!empty($fromdate) ? "AND jama.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND jama.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($touch) ? " AND jama.gross = $touch" : "") . "
                " . (!empty($metal_type_id) ? " AND jama.metal_type_id = $metal_type_id" : "") . "
				" . (!empty($garnu_id) ? " AND jama.id = 0 " : "") . "
            UNION
            SELECT
				baki.id AS Id,
				metal_type.name AS metal_type,
				baki.mode AS GarnuName,
				COALESCE(baki.gross, 0) AS Touch,
				COALESCE(baki.purity, 0) AS Weight,
				baki.created_at AS created_at,
                'baki' AS type
			FROM
                baki
				LEFT JOIN metal_type ON baki.metal_type_id = metal_type.id
				WHERE
				baki.type = 'fine'
                " . (!empty($fromdate) ? "AND baki.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND baki.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($touch) ? " AND baki.gross = $touch" : "") . "
                " . (!empty($metal_type_id) ? " AND baki.metal_type_id = $metal_type_id" : "") . "
				" . (!empty($garnu_id) ? " AND baki.id = 0 " : "") . "
			UNION
			SELECT
                main_garnu_item.id AS Id,
                metal_type.name AS metal_type,
                main_garnu.name AS GarnuName,
                COALESCE(main_garnu_item.touch, 0) AS Touch,
            	COALESCE(main_garnu_item.weight, 0) AS Weight,
                main_garnu_item.created_at AS created_at,
                'main garnu given' AS type
            FROM
                main_garnu_item
            LEFT JOIN main_garnu ON main_garnu_item.garnu_id = main_garnu.id
            LEFT JOIN metal_type ON main_garnu_item.metal_type_id = metal_type.id
            WHERE 
                $fetchQueryCondition2
                " . (!empty($fromdate) ? "AND main_garnu_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND main_garnu_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND main_garnu_item.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND main_garnu_item.metal_type_id = $metal_type_id" : "") . "
            ) AS combined_data
        ORDER BY
            created_at ASC
        LIMIT $rowperpage OFFSET $start";
		$query = $this->db->query($fetchQuery);
		$records = $query->result_array();

		// echo "<pre>";
		// print_r($records);exit;
		
		$data = array();
		$i = $start + 1;
		$closingTouch = 0;
		$closingWeight = 0;
		
		foreach ($records as $r) {
			// echo "<pre>";
			// print_r($r);
			$cweight = '--';
			$dweight = '--';

			if ($r['type'] == 'garnu receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
				$cweight = $r['Weight'];
				$closingTouch += $r['Touch'];
				$closingWeight += $r['Weight'];
			}
			if ($r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
				$closingTouch -= $r['Touch'];
				$closingWeight -= $r['Weight'];
				$dweight = $r['Weight'];
			}

			$data[] = array(
				'id' => $i,
				'row_material' => $r['metal_type'] ?? '--',
				'garnu' => $r['GarnuName'] ?? '--',
				'process' => $r['type'],
				'type' => $r['type'],
				'touch' => $r['Touch'] ??  '--',
				'cweight' => $cweight,
				'dweight' => $dweight,
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
			"closingWeight" => $closingWeight,
			"openingWeight" => $openingWeight,
		);
		echo json_encode($response);
		exit();
	}
}
