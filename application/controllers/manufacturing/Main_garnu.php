<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main_garnu extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/manufacturing/main_garnu_report";
	const ADD = "admin/manufacturing/main_garnu";

	const RECEIVE = "admin/manufacturing/main_receive";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		// library("Joinhelper");
	}

	public function index($action = "", $id = null)
	{
		$page_data['page_title'] = 'Main Garnu';
		flash()->withSuccess("Here is the garnu")->to("manufacturing/garnu");
		exit;

		switch ($action) {
			case "":
				checkPrivilege(privilege["main_garnu_view"]);
				$page_data['data'] = $this->dbh->getResultArray('main_garnu');
				$page_data['metal_type'] = $this->dbh->findAll('metal_type');

				return view(self::View, $page_data);
			case "add":
				checkPrivilege(privilege["main_garnu_add"]);
				$page_data['data'] = $this->dbh->getResultArray('main_garnu');
				return view(self::ADD, $page_data);

			case "edit":
				checkPrivilege(privilege["main_garnu_edit"]);
				$this->validateId($id);
				$garnu = $this->dbh->find('main_garnu', $id);
				if (!$garnu) {
					flash()->withError("Garnu type Not Found")->to('manufacturing/main_garnu');
				}

				$this->db->select('*');
				$this->db->from('main_garnu_item');
				$this->db->where('garnu_id', $id);
				$page_data['items'] = $this->db->get()->result_array();
				$page_data['update'] = $garnu;
				return view(self::ADD, $page_data);

			case "store":
				$post = xss_clean($this->input->post());
				$validation = $this->form_validation;
				$validation->set_rules('name', 'Name', 'required')
					->set_rules('garnu_weight', 'garnu_weight', 'required')
					->set_rules('touchs', 'touch', 'required')
					->set_rules('mfine', 'Total Fine', 'required')
					->set_rules('metal_type_id[]', 'metal_type_id', 'required')
					->set_rules('closing_touch[]', 'closing touch', 'required')
					->set_rules('weight[]', 'weight', 'required')
					->set_rules('touch[]', 'touch', 'required')
					->set_rules('fine[]', 'Fine', 'required');

				if (!$validation->run()) {
					return flash()->withError(validation_errors())->back();
				}
				$post = xss_clean($this->input->post());
				$garnu = array();
				$garnu['user_id'] = session('id');
				$garnu['name'] = $post['name'];
				$garnu['garnu_weight'] = $post['garnu_weight'];
				$garnu['touch'] = $post['touchs'];
				$garnu['fine'] = $post['mfine'];
				$garnu['creation_date'] = date('Y-m-d');

				$this->db->insert('main_garnu', $garnu);
				$garnu_id = $this->db->insert_id();


				$length = count($post['metal_type_id']);

				$garnu_item = $new = array();
				for ($i = 0; $i < $length; $i++) {
					$garnu_item['user_id'] = session('id');
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['closing_touch'] = $post['closing_touch'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					$garnu_item['creation_date'] = date('Y-m-d');
					$garnu_item['garnu_id'] = $garnu_id;
					$new[] = $garnu_item;
				}

				$this->db->insert_batch('main_garnu_item', $new);

				flash()->withSuccess("Garnu type Added Successfully")->to("manufacturing/main_garnu");
				break;
			case "update":
				checkPrivilege(privilege["main_garnu_edit"]);
				$validation = $this->form_validation;
				$validation->set_rules('name', 'Name', 'required')
					->set_rules('garnu_weight', 'garnu_weight', 'required')
					->set_rules('touchs', 'touch', 'required')
					->set_rules('mfine', 'Total Fine', 'required')
					->set_rules('metal_type_id[]', 'metal_type_id', 'required')
					->set_rules('weight[]', 'weight', 'required')
					->set_rules('touch[]', 'touch', 'required')
					->set_rules('fine[]', 'Fine', 'required');


				if ($validation->run() == false) {
					return flash()->withError(validation_errors())->back();
				}
				$post = xss_clean($this->input->post());
				$update = array();
				$update['name'] = $post['name'];
				$update['garnu_weight'] = $post['garnu_weight'];
				$update['touch'] = $post['touchs'];
				$update['fine'] = $post['mfine'];
				$this->db->where('id', $id)->update('main_garnu', $update);

				$oldIds = $this->db->select('id')->get_where('main_garnu_item', ['garnu_id' => $id])->result_array();
				$deleteIds = [];
				foreach ($oldIds as $row) {
					if (!in_array($row['id'], $post['rowid'])) {
						$deleteIds[] = $row['id'];
					}
				}

				if (count($deleteIds) > 0) {
					$this->dbh->deleteRow('main_garnu_item', $deleteIds);
				}
				$length = count($post['metal_type_id']);

				for ($i = 0; $i < $length; $i++) {
					$garnu_item = array();
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					if ($post['rowid'][$i] > 0) {
						if ($this->dbh->isDataExists('main_garnu_item', ['id' => $post['rowid'][$i], 'garnu_id' => $id])) {
							$this->db->where(['garnu_id' => $id, 'id' => $post['rowid'][$i]])->update('main_garnu_item', $garnu_item);
						}
					} else if ($post['rowid'][$i] == 0) {
						$garnu_item['garnu_id'] = $id;
						$this->db->insert('main_garnu_item', $garnu_item);
					}
				}
				flash()->withSuccess("Garnu Updated Successfully")->to("manufacturing/main_garnu");
				break;
			default:
				return flash()->withError("Invalid Arguments")->back();
		}
	}


	public function getlist()
	{
		$postData = $this->security->xss_clean($this->input->post());
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length'];
		// serching coding
		$columnIndex = $postData['order'][0]['column']; // Column index
		$searchValue = $postData['search']['value']; // Search value
		$todate = $postData['todate'];
		$fromdate = $postData['fromdate'];
		$received = $postData['received'];

		# Search 
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (name like '%" . $searchValue . "%'  or garnu_weight like '%" . $searchValue . "%'  or touch like '%" . $searchValue . "%' or silver like'%" . $searchValue . "%' or copper like'%" . $searchValue . "%' or creation_date like'%" . $searchValue . "%'  or recieved like'%" . $searchValue . "%' ) ";
		}
		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('main_garnu')->result();
		$totalRecords = $records[0]->allcount;


		## Total number of record with filtering

		$this->db->select('*');
		$this->db->from('main_garnu');

		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(main_garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(main_garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('main_garnu.recieved', $received);
		$records = $this->db->get();
		$totalRecordwithFilter = $records->num_rows();


		## Fetch records
		$where = [];
		$this->db->select('*');
		$this->db->from('main_garnu');

		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(main_garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(main_garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('main_garnu.recieved', $received);
		$this->db->limit($rowperpage, $start);
		$this->db->order_by('id', "desc");
		$records = $this->db->get()->result();


		$data = array();
		$i = $start + 1;
		foreach ($records as $record) {
			$this->db->select_max('id');
			$this->db->where('garnu_id', $record->id);
			$query1 = $this->db->get('given')->row_array();
			if ($query1) {
				$this->db->select('process.name AS process_name,customer.name as worker_name')
					->from('given')
					->join('process', 'given.process_id = process.id', 'left')
					->join('customer', 'given.worker_id = customer.id', 'left')
					->where('given.id', $query1['id']);
				$query2 = $this->db->get()->row_array();
			} else {
				$query2 = array();
			}

			if ($record->recieved == 'NO') {
				$dnone = "d-none";
				$is_receive = "";
			} else {
				$dnone = "";
				$is_receive = "d-none";
			}
			// <a href="' . base_url('manufacturing/main_process/manage/') . $record->id . '" class="btn btn-action bg-indigo text-white ' . $dnone . '" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Proccess Manage"><i class="fa-solid fa-code-fork"></i></a>
			$action = '
			<div class="d-flex gap-1">
			        <a href="' . base_url('manufacturing/main_garnu/index/edit/') . $record->id . '" class="btn btn-action bg-warning text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Edit Proccess"><i class="fas fa-edit"></i></a>
					<span data-receiveid="' . $record->id . '" class="btn btn-action bg-green text-white me-2 receive-btn" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Receive"><i class="fa-solid fa-receipt"></i></span>
					<span data-garnu_id="' . $record->id . '" class="btn btn-action bg-info text-white me-2 is_receive ' . $is_receive . '" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Is Receive"><i class="fa-solid fa-check"></i></span>
				</div>
			';

			if (!empty($query2)) {
				$processName = "<span class='text-danger'>{$query2['process_name']}</span>";
				$worker_name = "<span>{$query2['worker_name']}</span>";
			} else {
				$processName = "<span class='text-center text-danger'> -- </span>";
				$worker_name = "<span class='text-center'> -- </span>";
			}

			$class = ($record->recieved == "YES") ? 'indigo' : 'danger';
			$received = "<span class='badge bg-$class'>$record->recieved</span>";

			$data[] = array(
				'id' => $i,
				'action' => $action,
				'name' => $record->name,
				'garnu_weight' => $record->garnu_weight,
				'touch' => $record->touch,
				'fine' => $record->fine,
				'process_name' => $processName,
				'worker_name' => $worker_name,
				'recieved' => $received,
				'is_recieved' => $record->recieved,
				'created_at' => date('d-m-Y g:i A', strtotime($record->created_at)),
			);
			$i = $i + 1;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		);
		echo json_encode($response);
		exit();
	}


	public function checkReceive()
	{
		try {
			$this->form_validation->set_rules('id', 'Garnu Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'error' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData = $this->input->post();
				$id = $postData['id'];
				$data = $this->dbh->getWhereResultArray('receive_garnu', ['garnu_id' => $id]);
				$garnuData = $this->db->select('*')->from('main_garnu')->where('id', $id)->get()->row_array();
				// $garnuData = $this->dbh->getWhereRowArray('garnu', ['id' => $id]);
				if (!empty($data) || !empty($garnuData)) {
					$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data, 'garnuData' => $garnuData];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
				}
				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error' => $e->getMessage(),
				'data' => []
			];
			echo json_encode($response);
		}
	}

	public function receive()
	{
		$post = $this->input->post();
		$insertBatch = [];
		$updateBatch = [];
		$existingIds = isset($post['sdid']) ? $post['sdid'] : [];
		$allids = isset($post['ids']) ? $post['ids'] : [];

		$idsNotExisting = array_diff($allids, $existingIds);
		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('receive_garnu');
		}

		foreach ($post['sdid'] as $key => $sdid) {
			$rmData = [
				'metal_type_id' => isset($post['metal_type_id'][$key]) ? $post['metal_type_id'][$key] : null,
				'touch' => isset($post['touch'][$key]) ? $post['touch'][$key] : null,
				'weight' => isset($post['weight'][$key]) ? $post['weight'][$key] : null,
				'net_weight' => isset($post['net_weight'][$key]) ? $post['net_weight'][$key] : null,
			];

			if ($sdid == 0) {
				$rmData['garnu_id'] = $post['garnu_id'];
				$rmData['creation_date'] = date('Y-m-d');
				$insertBatch[] = $rmData;
			} else if (in_array($sdid, $existingIds)) {
				$rmData['id'] = $sdid;
				$updateBatch[] = $rmData;
			}
		}

		if (!empty($insertBatch)) {
			$this->db->insert_batch('receive_garnu', $insertBatch);
			$this->db->where('id', $post['garnu_id'])->update('main_garnu', ['recieved' => 'YES']);
			$response = ['success' => true, 'message' => 'Data Add Successfully.'];
		} else {
			$response = ['success' => false, 'message' => 'Data Add Failed.'];
		}
		if (!empty($updateBatch)) {
			$this->db->update_batch('receive_garnu', $updateBatch, 'id');
			$this->db->where('id', $post['garnu_id'])->update('main_garnu', ['recieved' => 'YES']);
			$response = ['success' => true, 'message' => 'Data Update Successfully.'];
		}
		echo json_encode($response);
		return;
	}

	public function updateStatus()
	{
		try {
			$this->form_validation->set_rules('id', 'Garnu Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'message' => strip_tags(validation_errors())];
				echo json_encode($response);
				return;
			} else {
				$postData = $this->input->post();
				$id = $postData['id'];
				$update = $this->db->where('id', $id)->update('main_garnu', ['recieved' => 'YES']);
				if ($update) {
					$response = ['success' => true, 'message' => 'Status Update successfully.'];
				} else {
					$response = ['success' => false, 'message' => 'Status Update Failed'];
				}
				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error' => $e->getMessage(),
				'data' => []
			];
			echo json_encode($response);
		}
	}

	// public function getClosingstock()
	// {
	// 	try {
	// 		$this->form_validation->set_rules('touch', 'Touch', 'trim|required|numeric');
	// 		if ($this->form_validation->run() == FALSE) {
	// 			$response = ['success' => false, 'message' => validation_errors()];
	// 			echo json_encode($response);
	// 			return;
	// 		} else {
	// 			$postData = $this->input->post();
	// 			$touch = $postData['touch'];

	// 			$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
	// 			$openingTouch = 0;
	// 			$openingWeight = 0;
	// 			$openingQuery = "SELECT 
	// 							SUM(touch) AS total_touch, SUM(weight) AS total_weight, type
	// 						FROM (
	// 							SELECT touch, weight, 'garnu given' AS type, garnu_item.created_at AS created_at
	// 							FROM garnu_item
	// 							WHERE 
	// 								" . (!empty($touch) ? "garnu_item.touch = $touch" : "") . "
	// 							UNION ALL
	// 							SELECT touch, weight, 'garnu receive' AS type, receive_garnu.created_at AS created_at
	// 							FROM receive_garnu
	// 							WHERE 
	// 								" . (!empty($touch) ? " receive_garnu.touch = $touch" : "") . "
	// 							UNION ALL
	// 							SELECT process_metal_type.touch, process_metal_type.weight, 'process given' AS type, process_metal_type.created_at AS created_at
	// 							FROM process_metal_type
	// 							LEFT JOIN given ON process_metal_type.given_id = given.id
	// 							WHERE
	// 								" . (!empty($touch) ? " process_metal_type.touch = $touch" : "") . "
	// 							UNION ALL
	// 							SELECT jama.gross AS touch, jama.purity AS weight, 'jama' AS type, jama.created_at AS created_at
	// 							FROM jama
	// 							WHERE
	// 								jama.type = 'fine'  
	// 								" . (!empty($touch) ? " AND jama.gross = $touch" : "") . "
	// 							UNION ALL
	// 							SELECT baki.gross AS touch, baki.purity AS weight, 'baki' AS type, baki.created_at AS created_at
	// 							FROM baki
	// 							WHERE
	// 								baki.type = 'fine' 
	// 							" . (!empty($touch) ? " AND baki.gross = $touch" : "") . "
	// 							UNION ALL
	// 							SELECT touch, weight, 'garnu given' AS type, main_garnu_item.created_at AS created_at
	// 							FROM main_garnu_item
	// 							WHERE 
	// 								" . (!empty($touch) ? " main_garnu_item.touch = $touch" : "") . "
	// 						) AS opening_records
	// 						GROUP BY type
	// 						ORDER BY created_at ASC";
	// 			$openingResult = $this->db->query($openingQuery)->result_array();

	// 			$openingTouch = 0;
	// 			$openingWeight = 0;

	// 			foreach ($openingResult as $r) {
	// 				if ($r['type'] == 'garnu receive' || $r['type'] == 'process given' || $r['type'] == 'jama') {
	// 					$openingTouch += $r['total_touch'];
	// 					$openingWeight += $r['total_weight'];
	// 				}
	// 				if ($r['type'] == 'garnu given' || $r['type'] == 'baki' || $r['type'] == 'main garnu given') {
	// 					$openingTouch -= $r['total_touch'];
	// 					$openingWeight -= $r['total_weight'];
	// 				}
	// 			}

	// 			if (!empty($openingWeight)) {
	// 				$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $openingWeight];
	// 			} else {
	// 				$response = ['success' => false, 'message' => 'Data Fetched successfully.', 'data' => "0"];
	// 			}
	// 			echo json_encode($response);
	// 			return;
	// 		}
	// 	} catch (Exception $e) {
	// 		$response = [
	// 			'success' => false, 'error' => $e->getMessage(), 'data' => []
	// 		];
	// 		echo json_encode($response);
	// 	}
	// }

	public function getStockTouch()
	{
		try {
			$this->form_validation->set_rules('metal_type_id', 'Metal Type', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'message' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData = $this->input->post();
				$metal_type_id = $postData['metal_type_id'];

				$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
				$openingTouch = 0;
				$openingWeight = 0;
				// Build the SQL query
				$openingQuery = "SELECT 
                    *
                FROM (
                    SELECT touch, weight, 'garnu given' AS type, garnu_item.created_at AS created_at
                    FROM garnu_item
                    WHERE " . (!empty($metal_type_id) ? "garnu_item.metal_type_id = $metal_type_id  AND garnu_item.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT touch, weight, 'garnu receive' AS type, receive_garnu.created_at AS created_at
                    FROM receive_garnu
                    WHERE " . (!empty($metal_type_id) ? "receive_garnu.metal_type_id = $metal_type_id  AND receive_garnu.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    
                    SELECT touch, weight, 'testing given' AS type, given_testing_item.created_at AS created_at
                    FROM given_testing_item
                    WHERE " . (!empty($metal_type_id) ? "given_testing_item.metal_type_id = $metal_type_id  AND given_testing_item.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT touch, weight, 'given testing receive' AS type, receive_given_testing.created_at AS created_at
                    FROM receive_given_testing
                    WHERE " . (!empty($metal_type_id) ? "receive_given_testing.metal_type_id = $metal_type_id  AND receive_given_testing.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    
                    SELECT touch, weight, 'dhal receive' AS type, receive_garnu_dhal.created_at AS created_at
                    FROM receive_garnu_dhal
                    WHERE " . (!empty($metal_type_id) ? "receive_garnu_dhal.metal_type_id = $metal_type_id  AND receive_garnu_dhal.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT touch, weight, 'process given' AS type, process_metal_type.created_at AS created_at
                    FROM process_metal_type
                    LEFT JOIN given ON process_metal_type.given_id = given.id
                    WHERE " . (!empty($metal_type_id) ? "process_metal_type.metal_type_id = $metal_type_id  AND process_metal_type.is_bhuko_used = 0" : "") . "
                    UNION ALL
                    SELECT  purity AS touch,gross AS weight, 'jama' AS type, created_at
                    FROM jama
                    WHERE type = 'fine'
                      AND " . (!empty($metal_type_id) ? "jama.metal_type_id = $metal_type_id AND jama.is_bhuko_used = 0" : "1") . "
                    UNION ALL
                    SELECT purity AS touch,gross AS weight,  'baki' AS type, created_at
                    FROM baki
                    WHERE type = 'fine'
                      AND " . (!empty($metal_type_id) ? "baki.metal_type_id = $metal_type_id AND baki.is_bhuko_used = 0" : "1") . "
                    UNION ALL
                    SELECT touch, weight, 'garnu given' AS type, main_garnu_item.created_at AS created_at
                    FROM main_garnu_item
                    WHERE " . (!empty($metal_type_id) ? "main_garnu_item.metal_type_id = $metal_type_id  AND main_garnu_item.is_bhuko_used = 0" : "") . "
                ) AS opening_records
                ORDER BY created_at ASC";

				$openingResult = $this->db->query($openingQuery)->result_array();
				// pre($openingResult);
				// pre($openingResult);
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

				// Add fine and average touch calculation if metal_type_id is 8
				$fine = 0;
				$weight = 0;
				if ($metal_type_id == 8) {
					// foreach ($metal_closing_stock as &$stock) {
					// 	$fine += ($stock['weight'] * $stock['touch']) / 100;
					// 	$weight += abs($stock['weight']);
					// }
					// $average_touch = ($fine * 100) / $weight;
					// $metal_closing_stock = [];
					// $stock['weight'] = $weight;
					// $stock['touch'] = $average_touch;
					$bhuko = $this->db->get('common_bhuko')->row_array();
					$metal_closing_stock[] = ['touch' => $bhuko['touch'], 'weight' => $bhuko['weight']];
				}

				$data = array_map(function ($entry) {
					if (isset($entry['fine']) && isset($entry['average_touch'])) {
						return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG (Fine: ' . $entry['fine'] . ', Average Touch: ' . $entry['average_touch'] . ')';
					}
					return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG';
				}, $metal_closing_stock);

				if (!empty($data)) {
					$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
				}

				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error' => $e->getMessage(),
				'data' => []
			];
			echo json_encode($response);
		}
	}

	private function validateId($id)
	{
		(!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/main_garnu");
	}
}
