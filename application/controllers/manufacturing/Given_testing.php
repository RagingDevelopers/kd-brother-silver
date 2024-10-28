<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Given_testing extends CI_Controller
{
	public $form_validation, $input, $db, $dbh;

	const View = "admin/manufacturing/given_testing_report";
	const ADD = "admin/manufacturing/given_testing";

	const RECEIVE = "admin/manufacturing/receive";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
	}

	public function index($action = "", $id = null)
	{
		$page_data['page_title'] = 'Given Testing';
		switch ($action) {
			case "":
				checkPrivilege(privilege["given_testing_view"]);
				$page_data['data'] = $this->dbh->getResultArray('given_testing');
				$page_data['metal_type'] = $this->dbh->findAll('metal_type');

				return view(self::View, $page_data);
			case "add":
				checkPrivilege(privilege["given_testing_add"]);
				$page_data['data'] = $this->dbh->getResultArray('given_testing');
				$page_data['workers'] = $this->db->where('account_type_id', 2)->get('customer')->result();
				return view(self::ADD, $page_data);

			case "edit":
				checkPrivilege(privilege["given_testing_edit"]);
				$this->validateId($id);
				$given = $this->dbh->find('given_testing', $id);
				if (!$given) {
					flash()->withError("Given Testing Not Found")->to('manufacturing/given_testing');
				}

				$this->db->select('*');
				$this->db->from('given_testing_item');
				$this->db->where('given_testing_id', $id);
				$page_data['items'] = $this->db->get()->result_array();
				$page_data['workers'] = $this->db->where('account_type_id', 2)->get('customer')->result();
				$page_data['update'] = $given;

				return view(self::ADD, $page_data);

			case "store":
				$post = xss_clean($this->input->post());
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
					->set_rules('fine[]', 'Fine', 'required')
					->set_rules('type[]', 'Type', 'required');

				if (!$validation->run()) {
					return flash()->withError(validation_errors())->back();
				}
				$post = xss_clean($this->input->post());
				$given = array();
				$given['name'] = $post['name'];
				$given['user_id'] = session('id');
				$given['garnu_weight'] = $post['garnu_weight'];
				$given['touch'] = $post['touchs'];
				$given['fine'] = $post['mfine'];
				$given['worker_id'] = $post['worker_id'];
				$given['creation_date'] = date('Y-m-d');

				$this->db->insert('given_testing', $given);
				$given_id = $this->db->insert_id();

				$length = count($post['metal_type_id']);

				$garnu_item = $new = array();
				for ($i = 0; $i < $length; $i++) {
					$garnu_item['user_id'] = session('id');
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['closing_touch'] = $post['closing_touch'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					$garnu_item['type'] = $post['type'][$i];
					$garnu_item['creation_date'] = date('Y-m-d');
					$garnu_item['given_testing_id'] = $given_id;
					$new[] = $garnu_item;
				}

				$this->db->insert_batch('given_testing_item', $new);

				flash()->withSuccess("Given Testing Added Successfully")->to("manufacturing/given_testing");
				break;

			case "update":
				checkPrivilege(privilege["given_testing_edit"]);
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

				$this->db->where('id', $id)->update('given_testing', $update);

				$oldIds = $this->db->select('id')->get_where('given_testing_item', ['given_testing_id' => $id])->result_array();
				$deleteIds = [];
				foreach ($oldIds as $row) {
					if (!in_array($row['id'], $post['rowid'])) {
						$deleteIds[] = $row['id'];
					}
				}

				if (count($deleteIds) > 0) {
					$this->dbh->deleteRow('given_testing_item', $deleteIds);
				}
				$length = count($post['metal_type_id']);

				for ($i = 0; $i < $length; $i++) {
					$garnu_item = array();
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['closing_touch'] = $post['closing_touch'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					if ($post['rowid'][$i] > 0) {
						if ($this->dbh->isDataExists('given_testing_item', ['id' => $post['rowid'][$i], 'given_testing_id' => $id])) {
							$this->db->where(['given_testing_id' => $id, 'id' => $post['rowid'][$i]])->update('given_testing_item', $garnu_item);
						}
					} else if ($post['rowid'][$i] == 0) {
						$garnu_item['given_testing_id'] = $id;
						$this->db->insert('given_testing_item', $garnu_item);
					}
				}
				flash()->withSuccess("Given Testing Updated Successfully")->to("manufacturing/given_testing");
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
		$records = $this->db->get('given_testing')->result();
		$totalRecords = $records[0]->allcount;


		## Total number of record with filtering
		$this->db->select('*');
		$this->db->from('given_testing');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(given_testing.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(given_testing.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('given_testing.recieved', $received);
		$records = $this->db->get();
		$totalRecordwithFilter = $records->num_rows();


		## Fetch records
		$where = [];
		$this->db->select('*');
		$this->db->from('given_testing');

		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(given_testing.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(given_testing.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('given_testing.recieved', $received);
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
				$is_receive = "";
			} else {
				$is_receive = "d-none";
			}
			$action = '
			<div class="d-flex gap-1">
				<a href="' . base_url('manufacturing/given_testing/index/edit/') . $record->id . '" class="btn btn-action bg-warning text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Edit Proccess"><i class="fas fa-edit"></i></a>
				<span data-receiveid="' . $record->id . '" class="btn btn-action bg-green text-white me-2 receive-btn" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Receive"><i class="fa-solid fa-receipt"></i></span>
				<span data-given_testing_id="' . $record->id . '" class="btn btn-action bg-info text-white me-2 is_receive ' . $is_receive . '" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Is Receive"><i class="fa-solid fa-check"></i></span>
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
				$data = $this->dbh->getWhereResultArray('receive_given_testing', ['given_testing_id' => $id]);
				$garnuData = $this->db->select('*')->from('given_testing')->where('id', $id)->get()->row_array();
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
				'success' => false, 'error' => $e->getMessage(), 'data' => []
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
			$this->db->delete('receive_given_testing');
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
					$rmData['given_testing_id'] = $post['given_testing_id'];
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
			$this->db->insert_batch('receive_given_testing', $insertBatch);
			$this->db->where('id', $post['given_testing_id'])->update('given_testing', ['recieved' => 'YES', 'is_kasar' => $is_kasar, 'transfer_account' => $transfer_account]);
			$response = ['success' => true, 'message' => 'Data Add Successfully.'];
		} else {
			$response = ['success' => false, 'message' => 'Please Fill Complate form..'];
		}
		if (!empty($updateBatch)) {
			$this->db->update_batch('receive_given_testing', $updateBatch, 'id');
			$this->db->where('id', $post['given_testing_id'])->update('given_testing', ['recieved' => 'YES', 'is_kasar' => $is_kasar, 'transfer_account' => $transfer_account]);
			$response = ['success' => true, 'message' => 'Data Update Successfully.'];
		}
		echo json_encode($response);
		return;
	}

	public function updateStatus()
	{
		try {
			$this->form_validation->set_rules('id', 'Given Testing Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'message' => strip_tags(validation_errors())];
				echo json_encode($response);
				return;
			} else {
				$postData = $this->input->post();
				$id = $postData['id'];
				$update = $this->db->where('id', $id)->update('given_testing', ['recieved' => 'YES']);
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
				'success' => false, 'error' => $e->getMessage(), 'data' => []
			];
			echo json_encode($response);
		}
	}

	private function validateId($id)
	{
		(!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/given_testing");
	}

	public function type()
	{
		$type = $this->input->post('type');
		$id = $this->input->post('selected_id');
		$data = '<option value=""> Select <option>';
		if ($type == 'Metal') {
			$records = $this->dbh->findAll('metal_type');
		} else {
			$records = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result();
		}
		if (!empty($records)) {
			foreach ($records as $value) {
				$selected = isset($value->id) && $value->id == $id ? 'selected' : '';
				$data .= '<option value="' . $value->id . '" ' . $selected . ' >' . $value->name . '</option>';
			}
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => $data];
		}
		echo json_encode($response);
	}

	public function rmClosing()
	{
		$metal_closing_stock = $this->db->select('id,touch,code,rem_weight,rem_quantity')->from('lot_wise_rm')->where(array('is_complated' => 'NO'))->order_by('id', 'DESC')->get()->result_array();
		$data = array_map(function ($entry) {
			return $entry['touch'] . ' - ' . abs($entry['rem_weight']) . ' KG';
		}, $metal_closing_stock);

		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
		}
		echo json_encode($response);
		return;
	}
}
