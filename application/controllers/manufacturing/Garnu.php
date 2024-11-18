<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Garnu extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/manufacturing/garnu_report";
	const ADD = "admin/manufacturing/garnu";

	const RECEIVE = "admin/manufacturing/receive";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		// library("Joinhelper");
	}

	public function index($action = "", $id = null)
	{
		$page_data['page_title'] = 'Garnu';
		switch ($action) {
			case "":
				checkPrivilege(privilege["garnu_view"]);
				$page_data['data'] = $this->dbh->getResultArray('garnu');
				$page_data['metal_type'] = $this->dbh->findAll('metal_type');

				return view(self::View, $page_data);
			case "add":
				checkPrivilege(privilege["garnu_add"]);
				$page_data['data'] = $this->dbh->getResultArray('garnu');
				$page_data['metal_type'] = $this->dbh->findAll('metal_type');
				$page_data['workers'] = $this->db->where('account_type_id', 2)->get('customer')->result();
				return view(self::ADD, $page_data);

			case "edit":
				checkPrivilege(privilege["garnu_edit"]);
				$this->validateId($id);
				$garnu = $this->dbh->find('garnu', $id);
				if (!$garnu) {
					flash()->withError("Garnu type Not Found")->to('manufacturing/garnu');
				}

				$this->db->select('*');
				$this->db->from('garnu_item');
				$this->db->where('garnu_id', $id);
				$page_data['items'] = $this->db->get()->result_array();
				$page_data['metal_type'] = $this->dbh->findAll('metal_type');
				$page_data['workers'] = $this->db->where('account_type_id', 2)->get('customer')->result();
				$page_data['update'] = $garnu;

				return view(self::ADD, $page_data);

			case "store":
				$post = xss_clean($this->input->post());
				// pre($post);
				// die;
				$validation = $this->form_validation;
				$validation->set_rules('name', 'Name', 'required')
					->set_rules('worker_id', 'Worker', 'required')
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
				$garnu['name'] = $post['name'];
				$garnu['user_id'] = session('id');
				$garnu['garnu_weight'] = $post['garnu_weight'];
				$garnu['touch'] = $post['touchs'];
				$garnu['fine'] = $post['mfine'];
				$garnu['worker_id'] = $post['worker_id'];
				$garnu['creation_date'] = date('Y-m-d');

				$this->db->insert('garnu', $garnu);
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
					$garnu_item['is_bhuko_used'] = 1;
					$new[] = $garnu_item;

					if ($post['metal_type_id'][$i] == 8) {
						$current_record = $this->db->get('common_bhuko')->row_array();

						// $difference_touch = $post['touch'][$i] - $current_record['touch'];
						$difference_weight = $post['weight'][$i] - $current_record['weight'];

						$this->db->update('common_bhuko', ['weight' => $difference_weight]);
					}
				}

				$this->db->insert_batch('garnu_item', $new);

				flash()->withSuccess("Garnu type Added Successfully")->to("manufacturing/garnu");
				break;

				// case "delete":
				//     die("not permission to delete");
				//     // checkPrivilege(privilege["garnu_delete"]);
				//     $this->validateId($id);
				//     $this->dbh->deleteRow('garnu', $id);
				//     flash()->withSuccess("garnu type Deleted Successfully")->back();
				//     break;
			case "update":
				checkPrivilege(privilege["garnu_edit"]);
				$validation = $this->form_validation;
				$validation->set_rules('name', 'Name', 'required')
					->set_rules('worker_id', 'Worker', 'required')
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
				$update['worker_id'] = $post['worker_id'];
				$update['garnu_weight'] = $post['garnu_weight'];
				$update['touch'] = $post['touchs'];
				$update['fine'] = $post['mfine'];

				$this->db->where('id', $id)->update('garnu', $update);

				$oldIds = $this->db->select('id')->get_where('garnu_item', ['garnu_id' => $id])->result_array();
				$deleteIds = [];
				foreach ($oldIds as $row) {
					if (!in_array($row['id'], $post['rowid'])) {
						$deleteIds[] = $row['id'];
					}
				}

				if (count($deleteIds) > 0) {
					$this->dbh->deleteRow('garnu_item', $deleteIds);
				}
				$length = count($post['metal_type_id']);

				$rows = $this->db->get_where('garnu_item', ['garnu_id' => $id])->result_array();
				if (!empty($rows)) {
					$current_record = $this->db->get('common_bhuko')->row_array();

					$current_touch = $current_record['touch'] ?? 0;
					$current_weight = $current_record['weight'] ?? 0;

					foreach ($rows as $row) {
						if ($row['metal_type_id'] == 8) {
							$current_touch += $row['touch'];
							$current_weight += $row['weight'];
						}
					}

					// $this->db->update('common_bhuko', [
					// 	// 'touch' => $current_touch,
					// 	'weight' => $current_weight
					// ]);
				}

				for ($i = 0; $i < $length; $i++) {
					$garnu_item = array();
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					if ($post['rowid'][$i] > 0) {
						if ($this->dbh->isDataExists('garnu_item', ['id' => $post['rowid'][$i], 'garnu_id' => $id])) {
							$this->db->where(['garnu_id' => $id, 'id' => $post['rowid'][$i]])->update('garnu_item', $garnu_item);
						}
					} else if ($post['rowid'][$i] == 0) {
						$garnu_item['garnu_id'] = $id;
						$this->db->insert('garnu_item', $garnu_item);
					}

					if ($post['metal_type_id'][$i] == 8) {
						$current_record = $this->db->get('common_bhuko')->row_array();

						// $difference_touch = $post['touch'][$i] - $current_record['touch'];
						// $difference_weight = $post['weight'][$i] - $current_record['weight'];

						// $this->db->update('common_bhuko', ['weight' => $difference_weight]);
					}
				}
				flash()->withSuccess("Garnu Updated Successfully")->to("manufacturing/garnu");
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
		$records = $this->db->get('garnu')->result();
		$totalRecords = $records[0]->allcount;


		## Total number of record with filtering
		$this->db->select('*');
		$this->db->from('garnu');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('garnu.recieved', $received);
		$records = $this->db->get();
		$totalRecordwithFilter = $records->num_rows();


		## Fetch records
		$where = [];
		$this->db->select('*');
		$this->db->from('garnu');

		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('garnu.recieved', $received);
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
			$action = '
			<div class="d-flex gap-1">
			        <a href="' . base_url('manufacturing/garnu/edit/') . $record->id . '" class="btn btn-action bg-warning text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Edit Proccess"><i class="fas fa-edit"></i></a>
					<span data-receiveid="' . $record->id . '" class="btn btn-action bg-green text-white me-2 receive-btn" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Receive"><i class="fa-solid fa-receipt"></i></span>
					<a href="' . base_url('manufacturing/process/manage/') . $record->id . '" class="btn btn-action bg-indigo text-white ' . $dnone . '" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Proccess Manage"><i class="fa-solid fa-code-fork"></i></a>
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
				$data = $this->dbh->getWhereResultArray('receive_garnu_dhal', ['garnu_id' => $id]);
				$garnuData = $this->db->select('*')->from('garnu')->where('id', $id)->get()->row_array();
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
			$this->db->delete('receive_garnu_dhal');
		}

		foreach ($post['sdid'] as $key => $sdid) {
			if (!empty($post['metal_type_id'][$key]) && $post['touch'][$key] || $post['weight'][$key] || $post['net_weight'][$key]) {
				$rmData = [
					'metal_type_id' => isset($post['metal_type_id'][$key]) ? $post['metal_type_id'][$key] : null,
					'touch' => isset($post['touch'][$key]) ? $post['touch'][$key] : null,
					'weight' => isset($post['weight'][$key]) ? $post['weight'][$key] : null,
					'net_weight' => isset($post['net_weight'][$key]) ? $post['net_weight'][$key] : null,
				];

				if ($sdid == 0) {
					$rmData['user_id'] = session('id');
					$rmData['garnu_id'] = $post['garnu_id'];
					$rmData['creation_date'] = date('Y-m-d');
					$insertBatch[] = $rmData;
				} else if (in_array($sdid, $existingIds)) {
					$rmData['id'] = $sdid;
					$updateBatch[] = $rmData;
				}
			}
		}

		$is_kasar = isset($post['is_kasar']) && $post['is_kasar'] == 'on' ? "YES" : "NO";

		$transfer_account = NULL;

		if ($is_kasar == "YES") {
			$transfer_account = ($post['transfer_account'] ?? NULL);
		}
		if (!empty($insertBatch)) {
			$this->db->insert_batch('receive_garnu_dhal', $insertBatch);
			$this->db->where('id', $post['garnu_id'])->update('garnu', ['recieved' => 'YES', 'is_kasar' => $is_kasar, 'transfer_account' => $transfer_account]);
			$response = ['success' => true, 'message' => 'Data Add Successfully.'];
		} else {
			$response = ['success' => false, 'message' => 'Please Fill Complate form..'];
		}
		if (!empty($updateBatch)) {
			$this->db->update_batch('receive_garnu_dhal', $updateBatch, 'id');
			$this->db->where('id', $post['garnu_id'])->update('garnu', ['recieved' => 'YES', 'is_kasar' => $is_kasar, 'vadharo_garnu' => $post['jama_baki'], 'transfer_account' => $transfer_account]);
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
				$update = $this->db->where('id', $id)->update('garnu', ['recieved' => 'YES']);
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

	private function validateId($id)
	{
		(!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/garnu");
	}
}
