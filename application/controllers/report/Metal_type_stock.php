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
				FROM given_testing_item
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM receive_given_testing
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM garnu_item
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM receive_garnu
				UNION ALL
				SELECT COUNT(*) AS total_count
				FROM receive_garnu_dhal
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
							    SELECT touch, weight, 'given testing' AS type, given_testing_item.created_at AS created_at
								FROM given_testing_item
								WHERE given_testing_item.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND given_testing_item.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND given_testing_item.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND given_testing_item.given_testing_id = $garnu_id" : "") . "
								UNION ALL
								SELECT touch, weight, 'given testing receive' AS type, receive_given_testing.created_at AS created_at
								FROM receive_given_testing
								WHERE receive_given_testing.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND receive_given_testing.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND receive_given_testing.metal_type_id = $metal_type_id" : "")
				. "
								UNION ALL
							
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
									" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "")
				. "
								UNION ALL
								SELECT touch, weight, 'dhal receive' AS type, receive_garnu_dhal.created_at AS created_at
								FROM receive_garnu_dhal
								WHERE receive_garnu_dhal.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND receive_garnu_dhal.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND receive_garnu_dhal.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND receive_garnu_dhal.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT process_metal_type.touch, process_metal_type.weight, 'process given' AS type, process_metal_type.created_at AS created_at
								FROM process_metal_type
								LEFT JOIN given ON process_metal_type.given_id = given.id
								WHERE process_metal_type.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND process_metal_type.touch = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND process_metal_type.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND given.garnu_id = $garnu_id" : "") . "
								UNION ALL
								SELECT jama.purity AS touch, jama.gross AS weight, 'jama' AS type, jama.created_at AS created_at
								FROM jama
								WHERE  jama.type = 'fine' AND jama.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
									" . (!empty($touch) ? " AND jama.purity = $touch" : "") . "
									" . (!empty($metal_type_id) ? " AND jama.metal_type_id = $metal_type_id" : "") . "
									" . (!empty($garnu_id) ? " AND jama.id = 0 " : "") . "
								UNION ALL
								SELECT baki.purity AS touch, baki.gross AS weight, 'baki' AS type, baki.created_at AS created_at
								FROM baki
								WHERE baki.type = 'fine' AND baki.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($touch) ? " AND baki.purity = $touch" : "") . "
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
				if ($r['type'] == 'given testing receive' || $r['type'] == 'garnu receive' || $r['type'] == 'dhal receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
					$openingTouch += $r['total_touch'];
					$openingWeight += $r['total_weight'];
				}
				if ($r['type'] == 'given testing' || $r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
					$openingTouch -= $r['total_touch'];
					$openingWeight -= $r['total_weight'];
				}
			}
		}

		$sql = "SELECT COUNT(*) AS total_count_filtered FROM (
		
	            SELECT DISTINCT
                    given_testing_item.id AS id,
                    given_testing_item.created_at AS created_at,
                    'gitven testing' AS type
                FROM
                    given_testing_item
                LEFT JOIN given_testing ON given_testing_item.given_testing_id = given_testing.id
                LEFT JOIN metal_type ON given_testing_item.metal_type_id = metal_type.id
                LEFT JOIN given ON given_testing.id = given.garnu_id
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND given_testing_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND given_testing_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND given_testing_item.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND given_testing_item.metal_type_id = $metal_type_id" : "") . "
                UNION
                SELECT DISTINCT
                    receive_given_testing.id AS id,
                    receive_given_testing.created_at AS created_at,
                    'given testing receive' AS type
                FROM
                    receive_given_testing
                
                LEFT JOIN metal_type ON receive_given_testing.metal_type_id = metal_type.id
                
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND receive_given_testing.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_given_testing.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND receive_given_testing.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND receive_given_testing.metal_type_id = $metal_type_id" : "") . "
                UNION
		
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
                
                LEFT JOIN metal_type ON receive_garnu.metal_type_id = metal_type.id
                
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND receive_garnu.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_garnu.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
                UNION
                SELECT DISTINCT
                    receive_garnu_dhal.id AS id,
                    receive_garnu_dhal.created_at AS created_at,
                    'dhal receive' AS type
                FROM
                    receive_garnu_dhal
                LEFT JOIN garnu ON receive_garnu_dhal.garnu_id = garnu.id
                LEFT JOIN metal_type ON receive_garnu_dhal.metal_type_id = metal_type.id
                LEFT JOIN given ON garnu.id = given.garnu_id
                WHERE
                    $filteredQueryCondition" . (!empty($fromdate) ? " AND receive_garnu_dhal.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_garnu_dhal.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($touch) ? " AND receive_garnu_dhal.touch = $touch" : "") . "
					" . (!empty($metal_type_id) ? " AND receive_garnu_dhal.metal_type_id = $metal_type_id" : "") . "
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
					" . (!empty($touch) ? " AND jama.purity = $touch" : "") . "
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
				" . (!empty($touch) ? " AND baki.purity = $touch" : "") . "
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
                given_testing_item.id AS Id,
                metal_type.name AS metal_type,
                given_testing.name AS GarnuName,
                COALESCE(given_testing_item.touch, 0) AS Touch,
            	COALESCE(given_testing_item.weight, 0) AS Weight,
                given_testing_item.created_at AS created_at,
                'given testing' AS type
            FROM
                given_testing_item
            LEFT JOIN given_testing ON given_testing_item.given_testing_id = given_testing.id
            LEFT JOIN metal_type ON given_testing_item.metal_type_id = metal_type.id
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND given_testing_item.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND given_testing_item.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND given_testing_item.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND given_testing_item.metal_type_id = $metal_type_id" : "") . "
            UNION
            SELECT
                receive_given_testing.id AS Id,
                metal_type.name AS metal_type,
                given_testing.name AS GarnuName,
                COALESCE(receive_given_testing.touch, 0) AS Touch,
            	COALESCE(receive_given_testing.weight, 0) AS Weight,
                receive_given_testing.created_at AS created_at,
                'receive given testing' AS type
            FROM
                receive_given_testing
            LEFT JOIN given_testing ON receive_given_testing.given_testing_id = given_testing.id
            LEFT JOIN metal_type ON receive_given_testing.metal_type_id = metal_type.id
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND receive_given_testing.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND receive_given_testing.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND receive_given_testing.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND receive_given_testing.metal_type_id = $metal_type_id" : "") . "
            UNION
            
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
                main_garnu.name AS GarnuName,
                COALESCE(receive_garnu.touch, 0) AS Touch,
            	COALESCE(receive_garnu.weight, 0) AS Weight,
                receive_garnu.created_at AS created_at,
                'garnu receive' AS type
            FROM
                receive_garnu
            LEFT JOIN main_garnu ON receive_garnu.garnu_id = main_garnu.id
            LEFT JOIN metal_type ON receive_garnu.metal_type_id = metal_type.id
            
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND receive_garnu.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND receive_garnu.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND receive_garnu.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND receive_garnu.metal_type_id = $metal_type_id" : "") . "
            UNION
            SELECT
                receive_garnu_dhal.id AS Id,
                metal_type.name AS metal_type,
                garnu.name AS GarnuName,
                COALESCE(receive_garnu_dhal.touch, 0) AS Touch,
            	COALESCE(receive_garnu_dhal.weight, 0) AS Weight,
                receive_garnu_dhal.created_at AS created_at,
                'dhal receive' AS type
            FROM
                receive_garnu_dhal
            LEFT JOIN garnu ON receive_garnu_dhal.garnu_id = garnu.id
            LEFT JOIN metal_type ON receive_garnu_dhal.metal_type_id = metal_type.id
            
            WHERE 
                $fetchQueryCondition
                " . (!empty($fromdate) ? "AND receive_garnu_dhal.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND receive_garnu_dhal.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
				" . (!empty($touch) ? " AND receive_garnu_dhal.touch = $touch" : "") . "
				" . (!empty($metal_type_id) ? " AND receive_garnu_dhal.metal_type_id = $metal_type_id" : "") . "
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
                COALESCE(jama.purity, 0) AS Touch,
            	COALESCE(jama.gross, 0) AS Weight,
                jama.created_at AS created_at,
                jama.payment_type AS type
            FROM
                jama
            LEFT JOIN metal_type ON jama.metal_type_id = metal_type.id
            WHERE
				jama.type = 'fine'
                " . (!empty($fromdate) ? "AND jama.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($todate) ? " AND jama.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                " . (!empty($touch) ? " AND jama.purity = $touch" : "") . "
                " . (!empty($metal_type_id) ? " AND jama.metal_type_id = $metal_type_id" : "") . "
				" . (!empty($garnu_id) ? " AND jama.id = 0 " : "") . "
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
			// 			echo "<pre>";
			// 			print_r($r);
			$cweight = '--';
			$dweight = '--';

			if ($r['type'] == 'receive given testing' || $r['type'] == 'garnu receive' || $r['type'] == 'dhal receive' || $r['type'] == 'process given' || $r['type'] == 'CREDIT') {
				$cweight = $r['Weight'];
				$closingTouch += isset($r['Touch']) && !empty($r['Touch']) ? $r['Touch'] : 0;
				$closingWeight += isset($r['Weight']) && !empty($r['Weight']) ? $r['Weight'] : 0;
			}
			if ($r['type'] == 'given testing' || $r['type'] == 'garnu given' || $r['type'] == 'DEBIT' || $r['type'] == 'main garnu given') {
				$closingTouch -= isset($r['Touch']) && !empty($r['Touch']) ? $r['Touch'] : 0;
				$closingWeight -= isset($r['Weight']) && !empty($r['Weight']) ? $r['Weight'] : 0;
				$dweight = isset($r['Weight']) && !empty($r['Weight']) ? $r['Weight'] : 0;
			}

			$data[] = array(
				'id' => $i,
				'row_material' => isset($r['metal_type']) && !empty($r['metal_type']) ? $r['metal_type'] : '--',
				'garnu' => isset($r['GarnuName']) && !empty($r['GarnuName']) ? $r['GarnuName'] : '--',
				'process' => isset($r['type']) && !empty($r['type']) ? $r['type'] : '--',
				'type' => isset($r['type']) && !empty($r['type']) ? $r['type'] : '--',
				'touch' => isset($r['Touch']) && !empty($r['Touch']) ? $r['Touch'] : '--',
				'cweight' => $cweight,
				'dweight' => $dweight,
				'date' => date("d-m-Y g:i A", strtotime($r['created_at'])),
				'closingWeight' => number_format($closingWeight, 2, '.', ''),
			);
			$i++;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"closingWeight" => number_format($closingWeight, 4, '.', ''),
			"openingWeight" => number_format($openingWeight, 4, '.', ''),
		);
		echo json_encode($response);
		exit();
	}

	public function averagebhukon()
	{
		try {
			$metal_type_id = 8;

			$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
			// Build the SQL query
			$openingQuery = "SELECT 
                    *
                FROM (
                    SELECT garnu_item.id,'garnu_item' AS tableName, touch, weight, 'garnu given' AS type, garnu_item.created_at AS created_at
                    FROM garnu_item
                    WHERE " . (!empty($metal_type_id) ? "garnu_item.metal_type_id = $metal_type_id AND garnu_item.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT receive_garnu.id,'receive_garnu' AS tableName, touch, weight, 'garnu receive' AS type, receive_garnu.created_at AS created_at
                    FROM receive_garnu
                    WHERE " . (!empty($metal_type_id) ? "receive_garnu.metal_type_id = $metal_type_id  AND receive_garnu.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    
                    SELECT given_testing_item.id,'given_testing_item' AS tableName, touch, weight, 'testing given' AS type, given_testing_item.created_at AS created_at
                    FROM given_testing_item
                    WHERE " . (!empty($metal_type_id) ? "given_testing_item.metal_type_id = $metal_type_id  AND given_testing_item.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT receive_given_testing.id,'receive_given_testing' AS tableName, touch, weight, 'given testing receive' AS type, receive_given_testing.created_at AS created_at
                    FROM receive_given_testing
                    WHERE " . (!empty($metal_type_id) ? "receive_given_testing.metal_type_id = $metal_type_id  AND receive_given_testing.is_bhuko_used = 0" : "") . "
                    UNION ALL

                    SELECT common_bhuko.id,' ' AS tableName, touch, weight, ' ' AS type, '' AS created_at FROM common_bhuko

                    UNION ALL

                    SELECT receive_garnu_dhal.id,'receive_garnu_dhal' AS tableName, touch, weight, 'dhal receive' AS type, receive_garnu_dhal.created_at AS created_at
                    FROM receive_garnu_dhal
                    WHERE " . (!empty($metal_type_id) ? "receive_garnu_dhal.metal_type_id = $metal_type_id  AND receive_garnu_dhal.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT process_metal_type.id,'process_metal_type' AS tableName, touch, weight, 'process given' AS type, process_metal_type.created_at AS created_at
                    FROM process_metal_type
                    LEFT JOIN given ON process_metal_type.given_id = given.id
                    WHERE " . (!empty($metal_type_id) ? "process_metal_type.metal_type_id = $metal_type_id  AND process_metal_type.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT jama.id,'jama' AS tableName,  purity AS touch,gross AS weight, 'jama' AS type, created_at
                    FROM jama
                    WHERE type = 'fine' AND " . (!empty($metal_type_id) ? "jama.metal_type_id = $metal_type_id  AND jama.is_bhuko_used = 0" : "1") . "
                    UNION ALL
                    SELECT baki.id,'baki' AS tableName, purity AS touch,gross AS weight,  'baki' AS type, created_at
                    FROM baki
                    WHERE type = 'fine' AND " . (!empty($metal_type_id) ? "baki.metal_type_id = $metal_type_id  AND baki.is_bhuko_used = 0" : "1") . "
                    UNION ALL
                    SELECT main_garnu_item.id,'main_garnu_item' AS tableName, touch, weight, 'garnu given' AS type, main_garnu_item.created_at AS created_at
                    FROM main_garnu_item
                    WHERE " . (!empty($metal_type_id) ? "main_garnu_item.metal_type_id = $metal_type_id  AND main_garnu_item.is_bhuko_used = 0" : "") . "
                ) AS opening_records
                ORDER BY created_at ASC";

			$openingResult = $this->db->query($openingQuery)->result_array();
			$metal_closing_stock = [];
			foreach ($openingResult as $r) {
				$touch = abs($r['touch']);
				$weight = abs($r['weight']);

				if (in_array($touch, array_column($metal_closing_stock, 'touch'))) {
					$index = array_search($touch, array_column($metal_closing_stock, 'touch'));
					if ($r['type'] == 'given testing receive' || $r['type'] == 'garnu receive' || $r['type'] == 'dhal receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
						$metal_closing_stock[$index]['weight'] += $weight;
					} elseif ($r['type'] == 'testing given' || $r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
						$metal_closing_stock[$index]['weight'] -= $weight;
					}
				} else {
					if ($r['type'] == 'given testing receive' || $r['type'] == 'garnu receive' || $r['type'] == 'dhal receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
						$metal_closing_stock[] = ['touch' => $touch, 'weight' => $weight];
					} elseif ($r['type'] == 'testing given' || $r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
						$metal_closing_stock[] = ['touch' => $touch, 'weight' => '-' . $weight];
					}
				}
			}

			$fine = 0;
			$weight = 0;
			if ($metal_type_id == 8) {
				foreach ($metal_closing_stock as &$stock) {
					$fine += ($stock['weight'] * $stock['touch']) / 100;
					$weight += abs($stock['weight']);
				}
				$average_touch = ($weight ?? 0) !== 0 ? (($fine ?? 0) * 100) / $weight : 0;
				$metal_closing_stock = [];
				$stock['weight'] = $weight;
				$stock['touch'] = $average_touch;
				$metal_closing_stock[] = ['touch' => $average_touch, 'weight' => $weight];
			}

			$data = array_map(function ($entry) {
				if (isset($entry['fine']) && isset($entry['average_touch'])) {
					return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG (Fine: ' . $entry['fine'] . ', Average Touch: ' . $entry['average_touch'] . ')';
				}
				return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG';
			}, $metal_closing_stock);

			if (!empty($data)) {
				foreach ($openingResult as $r) {
					if($r['type'] != ' '){
						$this->db->where('id', $r['id']);
						$this->db->update($r['tableName'], ['is_bhuko_used' => 1]);
					}
				}
				foreach ($data as $value) {
					$parts = explode(" - ", $value);
					$touch = trim($parts[0]);
					$weight = trim(str_replace("KG", "", $parts[1]));
				}
				if($touch != 0 && !empty($touch) && $weight != 0 && !empty($weight)){
					$this->db->where('id', 1);
					$this->db->update('common_bhuko', ['touch' => $touch, 'weight' => $weight]);
					$response = ['success' => true, 'message' => 'Average Bhukon Updated successfully.', 'data' => ['touch' => $touch, 'weight' => $weight]];
				}else{
					$response = ['success' => false, 'message' => 'Already Updated.'];
				}
			} else {
				$response = ['success' => false, 'message' => 'Average Bhukon Updated Failed.'];
			}

			echo json_encode($response);
			return;
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error' => $e->getMessage(),
				'data' => []
			];
			echo json_encode($response);
		}
	}
}
