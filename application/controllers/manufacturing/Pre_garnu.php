<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pre_garnu extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/manufacturing/pre_garnu_report";
	const ADD = "admin/manufacturing/pre_garnu";

	const RECEIVE = "admin/manufacturing/receive";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->helper('lot_management');
		// library("Joinhelper");
	}

	public function index($action = "", $id = null)
	{
		$page_data['page_title'] = 'Garnu';
		switch ($action) {
			case "":
				checkPrivilege(privilege["garnu_view"]);
				$page_data['data'] = $this->dbh->getResultArray('pre_garnu');
				$page_data['metal_type'] = $this->dbh->findAll('item');

				return view(self::View, $page_data);
			case "add":
				checkPrivilege(privilege["garnu_add"]);
				$page_data['data'] = $this->dbh->getResultArray('pre_garnu');
				$page_data['metal_type'] = $this->dbh->findAll('item');
				$page_data['workers'] = $this->db->where('account_type_id', 2)->get('customer')->result();
				return view(self::ADD, $page_data);

			case "edit":
				checkPrivilege(privilege["garnu_edit"]);
				$this->validateId($id);
				$garnu = $this->dbh->find('pre_garnu', $id);
				if (!$garnu) {
					flash()->withError("Garnu type Not Found")->to('manufacturing/pre_garnu');
				}

				$this->db->select('*');
				$this->db->from('pre_garnu_item');
				$this->db->where('garnu_id', $id);
				$page_data['items'] = $this->db->get()->result_array();
				$page_data['metal_type'] = $this->dbh->findAll('item');
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

				$this->db->insert('pre_garnu', $garnu);
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
					$this->updateLotWeight(
						(int) $post['closing_touch'][$i],
						(int) $post['metal_type_id'][$i],
						-1 * (float) $post['weight'][$i]
					);

					if ($post['metal_type_id'][$i] == 8) {
						$current_record = $this->db->get('common_bhuko')->row_array();

						// $difference_touch = $post['touch'][$i] - $current_record['touch'];
						$difference_weight = $post['weight'][$i] - $current_record['weight'];

						$this->db->update('common_bhuko', ['weight' => $difference_weight]);
					}
				}

				$this->db->insert_batch('pre_garnu_item', $new);

				flash()->withSuccess("Garnu type Added Successfully")->to("manufacturing/pre_garnu");
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

				$this->db->trans_begin();
				$this->db->where('id', $id)->update('pre_garnu', $update);

				$oldItems = $this->db
					->select('id, metal_type_id, closing_touch, weight')
					->where('garnu_id', $id)
					->get('pre_garnu_item')
					->result_array();
				$oldItemsById = [];
				foreach ($oldItems as $oldItem) {
					$oldItemsById[(int) $oldItem['id']] = $oldItem;
				}

				$postedRowIds = isset($post['rowid']) && is_array($post['rowid'])
					? array_map('intval', $post['rowid'])
					: [];

				$deleteIds = [];
				foreach ($oldItemsById as $oldId => $oldItem) {
					if (!in_array($oldId, $postedRowIds, true)) {
						$deleteIds[] = $oldId;
						$this->updateLotWeight(
							(int) ($oldItem['closing_touch'] ?? 0),
							(int) ($oldItem['metal_type_id'] ?? 0),
							(float) ($oldItem['weight'] ?? 0)
						);
					}
				}

				if (!empty($deleteIds)) {
					$this->db->where('garnu_id', $id)->where_in('id', $deleteIds)->delete('pre_garnu_item');
				}

				$length = count($post['metal_type_id']);

				for ($i = 0; $i < $length; $i++) {
					$garnu_item = array();
					$garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
					$garnu_item['closing_touch'] = $post['closing_touch'][$i];
					$garnu_item['weight'] = $post['weight'][$i];
					$garnu_item['touch'] = $post['touch'][$i];
					$garnu_item['fine'] = $post['fine'][$i];
					$rowId = isset($post['rowid'][$i]) ? (int) $post['rowid'][$i] : 0;
					$newMetalTypeId = (int) $post['metal_type_id'][$i];
					$newLotId = (int) $post['closing_touch'][$i];
					$newWeight = (float) $post['weight'][$i];

					if ($rowId > 0) {
						if (isset($oldItemsById[$rowId])) {
							$oldItem = $oldItemsById[$rowId];
							$oldMetalTypeId = (int) ($oldItem['metal_type_id'] ?? 0);
							$oldLotId = (int) ($oldItem['closing_touch'] ?? 0);
							$oldWeight = (float) ($oldItem['weight'] ?? 0);

							if ($oldLotId === $newLotId && $oldMetalTypeId === $newMetalTypeId) {
								$this->updateLotWeight($newLotId, $newMetalTypeId, $oldWeight - $newWeight);
							} else {
								$this->updateLotWeight($oldLotId, $oldMetalTypeId, $oldWeight);
								$this->updateLotWeight($newLotId, $newMetalTypeId, -1 * $newWeight);
							}

							$this->db->where(['garnu_id' => $id, 'id' => $rowId])->update('pre_garnu_item', $garnu_item);
						}
					} else if ($rowId === 0) {
						$garnu_item['garnu_id'] = $id;
						$garnu_item['user_id'] = session('id');
						$garnu_item['creation_date'] = date('Y-m-d');
						$garnu_item['is_bhuko_used'] = 1;
						$this->db->insert('pre_garnu_item', $garnu_item);
						$this->updateLotWeight($newLotId, $newMetalTypeId, -1 * $newWeight);
					}
				}

				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
					return flash()->withError("Garnu update failed. Please try again.")->back();
				}

				$this->db->trans_commit();
				flash()->withSuccess("Garnu Updated Successfully")->to("manufacturing/pre_garnu");
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
		$records = $this->db->get('pre_garnu')->result();
		$totalRecords = $records[0]->allcount;


		## Total number of record with filtering
		$this->db->select('*');
		$this->db->from('pre_garnu');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(pre_garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(pre_garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('pre_garnu.recieved', $received);
		$records = $this->db->get();
		$totalRecordwithFilter = $records->num_rows();


		## Fetch records
		$where = [];
		$this->db->select('*');
		$this->db->from('pre_garnu');

		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate))
			$this->db->where('DATE(pre_garnu.creation_date) >=', $fromdate);
		if (!empty($todate))
			$this->db->where('DATE(pre_garnu.creation_date) <=', $todate);
		if (!empty($received))
			$this->db->where('pre_garnu.recieved', $received);
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
			        <a href="' . base_url('manufacturing/pre_garnu/edit/') . $record->id . '" class="btn btn-action bg-warning text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Edit Proccess"><i class="fas fa-edit"></i></a>
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
				$receiveLotColumn = $this->getReceiveLotColumn();
				$this->db->select('pre_receive_garnu_dhal.*, ' . $receiveLotColumn . ' AS lot');
				$data = $this->db->get_where('pre_receive_garnu_dhal', ['garnu_id' => $id])->result_array();
				$garnuData = $this->db->select('*')->from('pre_garnu')->where('id', $id)->get()->row_array();
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
		$post = xss_clean($this->input->post());
		$garnuId = isset($post['garnu_id']) ? (int) $post['garnu_id'] : 0;
		$receiveLotColumn = $this->getReceiveLotColumn();
		$existingIds = isset($post['sdid']) && is_array($post['sdid']) ? array_map('intval', $post['sdid']) : [];
		$allids = isset($post['ids']) && is_array($post['ids']) ? array_map('intval', $post['ids']) : [];
		$idsNotExisting = array_values(array_diff($allids, $existingIds));
		$hasValidRows = false;

		$is_kasar = isset($post['is_kasar']) && $post['is_kasar'] == 'on' ? "YES" : "NO";
		$transfer_account = $is_kasar == "YES" ? ($post['transfer_account'] ?? NULL) : NULL;

		$oldRows = $this->db
			->select('id, metal_type_id, touch, weight, net_weight, ' . $receiveLotColumn . ' AS lot')
			->where('garnu_id', $garnuId)
			->get('pre_receive_garnu_dhal')
			->result_array();
		$oldRowsById = [];
		foreach ($oldRows as $oldRow) {
			$oldRowsById[(int) $oldRow['id']] = $oldRow;
		}

		$this->db->trans_begin();

		if (!empty($idsNotExisting)) {
			foreach ($idsNotExisting as $deleteId) {
				if (isset($oldRowsById[$deleteId])) {
					if (!$this->removeReceiveFromLot($oldRowsById[$deleteId])) {
						$this->db->trans_rollback();
						echo json_encode(['success' => false, 'message' => 'Lot update failed while removing old receive rows.']);
						return;
					}
				}
			}

			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('pre_receive_garnu_dhal');
		}

		$rowCount = isset($post['sdid']) && is_array($post['sdid']) ? count($post['sdid']) : 0;

		for ($key = 0; $key < $rowCount; $key++) {
			$sdid = isset($post['sdid'][$key]) ? (int) $post['sdid'][$key] : 0;
			$metalTypeId = isset($post['metal_type_id'][$key]) ? (int) $post['metal_type_id'][$key] : 0;
			$touch = isset($post['touch'][$key]) ? $post['touch'][$key] : 0;
			$weight = isset($post['weight'][$key]) ? $post['weight'][$key] : 0;
			$netWeight = isset($post['net_weight'][$key]) ? $post['net_weight'][$key] : 0;
			$lotId = isset($post['lot'][$key]) && $post['lot'][$key] !== '' ? (int) $post['lot'][$key] : 0;
			$oldRow = $sdid > 0 && isset($oldRowsById[$sdid]) ? $oldRowsById[$sdid] : null;
			$oldLotId = isset($oldRow['lot']) ? (int) $oldRow['lot'] : 0;
			$oldMetalTypeId = isset($oldRow['metal_type_id']) ? (int) $oldRow['metal_type_id'] : 0;

			if ($metalTypeId <= 0 && $touch == 0 && $weight == 0 && $netWeight == 0) {
				continue;
			}

			if ($metalTypeId <= 0) {
				continue;
			}

			if ($lotId <= 0 && $oldLotId > 0 && $oldMetalTypeId === $metalTypeId) {
				$lotId = $oldLotId;
			}

			$hasValidRows = true;
			$finalLotId = $this->saveReceiveLot($metalTypeId, $touch, $weight, $lotId, $oldRow);

			if ($finalLotId === false) {
				$this->db->trans_rollback();
				echo json_encode(['success' => false, 'message' => 'Lot update failed. Please check selected lot data.']);
				return;
			}

			$rmData = [
				'metal_type_id' => $metalTypeId,
				'touch' => $touch,
				'weight' => $weight,
				'net_weight' => $netWeight,
				$receiveLotColumn => $finalLotId ?: null,
			];

			if ($sdid === 0) {
				$rmData['user_id'] = session('id');
				$rmData['garnu_id'] = $garnuId;
				$rmData['creation_date'] = date('Y-m-d');
				$this->db->insert('pre_receive_garnu_dhal', $rmData);
			} else {
				$this->db->where(['id' => $sdid, 'garnu_id' => $garnuId])->update('pre_receive_garnu_dhal', $rmData);
			}
		}

		$this->db->where('id', $garnuId)->update('pre_garnu', [
			'recieved' => $hasValidRows ? 'YES' : 'NO',
			'is_kasar' => $is_kasar,
			'vadharo_garnu' => $post['jama_baki'] ?? 0,
			'transfer_account' => $transfer_account,
		]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			echo json_encode(['success' => false, 'message' => 'Receive update failed. Please try again.']);
			return;
		}

		$this->db->trans_commit();

		if (!$hasValidRows) {
			echo json_encode(['success' => false, 'message' => 'Please Fill Complate form..']);
			return;
		}

		echo json_encode(['success' => true, 'message' => 'Data Update Successfully.']);
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
				$update = $this->db->where('id', $id)->update('pre_garnu', ['recieved' => 'YES']);
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
		(!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/pre_garnu");
	}

	private function updateLotWeight($lotId, $rowMaterialId, $remWeightDiff)
	{
		$lotId = (int) $lotId;
		$rowMaterialId = (int) $rowMaterialId;
		$remWeightDiff = (float) $remWeightDiff;

		if ($lotId <= 0 || $rowMaterialId <= 0 || $remWeightDiff == 0) {
			return false;
		}

		$lotData = $this->db
			->select('id, row_material_id, weight, quantity, touch')
			->where([
				'id' => $lotId,
				'row_material_id' => $rowMaterialId
			])
			->get('lot_wise_rm')
			->row_array();

		if (empty($lotData)) {
			return false;
		}

		return lot_management([
			'id' => (int) $lotData['id'],
			'row_material_id' => (int) $lotData['row_material_id'],
			'weight' => (float) $lotData['weight'],
			'quantity' => (float) $lotData['quantity'],
			'touch' => (float) $lotData['touch'],
			'given_weight_diff' => -1 * $remWeightDiff,
			'rem_weight_diff' => $remWeightDiff,
			'given_quantity_diff' => 0,
			'rem_quantity_diff' => 0,
		]);
	}

	private function saveReceiveLot($metalTypeId, $touch, $weight, $selectedLotId = 0, $oldRow = null)
	{
		$metalTypeId = (int) $metalTypeId;
		$selectedLotId = (int) $selectedLotId;
		$touch = (float) $touch;
		$weight = (float) $weight;
		$oldLotId = isset($oldRow['lot']) ? (int) $oldRow['lot'] : 0;
		$oldMetalTypeId = isset($oldRow['metal_type_id']) ? (int) $oldRow['metal_type_id'] : 0;
		$oldWeight = isset($oldRow['weight']) ? (float) $oldRow['weight'] : 0;

		if ($metalTypeId <= 0) {
			return $selectedLotId > 0 ? $selectedLotId : 0;
		}

		if ($weight == 0) {
			$result = $this->updateReceiveLotWeight($oldLotId, $oldMetalTypeId, $oldWeight, 0);
			return $result ? ($selectedLotId > 0 ? $selectedLotId : $oldLotId) : false;
		}

		if ($selectedLotId <= 0) {
			if (!$this->updateReceiveLotWeight($oldLotId, $oldMetalTypeId, $oldWeight, 0)) {
				return false;
			}

			return lot_management([
				'user_id' => session('id'),
				'row_material_id' => $metalTypeId,
				'weight' => $weight,
				'rem_weight' => $weight,
				'touch' => $touch,
				'quantity' => 0,
				'rem_quantity' => 0,
				'receive_weight' => $weight,
				'receive_quantity' => 0,
				'code' => $this->generateReceiveLotCode($metalTypeId, $touch),
				'creation_date' => date('Y-m-d'),
				'type' => 'RECEIVE',
			]);
		}

		if ($oldLotId === $selectedLotId && $oldMetalTypeId === $metalTypeId) {
			$result = $this->updateReceiveLotWeight($selectedLotId, $metalTypeId, $oldWeight, $weight);
			return $result ? $selectedLotId : false;
		}

		if (!$this->updateReceiveLotWeight($oldLotId, $oldMetalTypeId, $oldWeight, 0)) {
			return false;
		}

		$result = $this->updateReceiveLotWeight($selectedLotId, $metalTypeId, 0, $weight);
		return $result ? $selectedLotId : false;
	}

	private function removeReceiveFromLot($row = null)
	{
		if (empty($row)) {
			return true;
		}

		$lotId = isset($row['lot']) ? (int) $row['lot'] : 0;
		$metalTypeId = isset($row['metal_type_id']) ? (int) $row['metal_type_id'] : 0;
		$weight = isset($row['weight']) ? (float) $row['weight'] : 0;

		if ($lotId <= 0 || $metalTypeId <= 0 || $weight == 0) {
			return true;
		}

		return $this->updateReceiveLotWeight($lotId, $metalTypeId, $weight, 0);
	}

	private function updateReceiveLotWeight($lotId, $metalTypeId, $oldWeight, $newWeight)
	{
		$lotId = (int) $lotId;
		$metalTypeId = (int) $metalTypeId;
		$oldWeight = (float) $oldWeight;
		$newWeight = (float) $newWeight;

		// No change in receive weight, nothing to update.
		if ($newWeight == $oldWeight) {
			return true;
		}

		if ($lotId <= 0 || $metalTypeId <= 0) {
			return $newWeight == 0;
		}

		$lotData = $this->db
			->select('id, row_material_id, weight, quantity, touch')
			->where([
				'id' => $lotId,
				'row_material_id' => $metalTypeId,
			])
			->get('lot_wise_rm')
			->row_array();

		if (empty($lotData)) {
			return false;
		}

		/*
		 * movement=receive handles both cases:
		 * 1) Existing row: receive/rem = old - oldWeight + newWeight
		 * 2) New row:      receive/rem = old + newWeight
		 */
		return lot_management([
			'id' => $lotId,
			'row_material_id' => $metalTypeId,
			'weight' => (float) $newWeight,
			'quantity' => 0,
			'touch' => (float) ($lotData['touch'] ?? 0),
			'old_weight' => (float) $oldWeight,
			'old_quantity' => 0,
			'movement' => 'receive',
			'update_lot_values' => false,
			'receive_quantity_diff' => 0,
			'rem_quantity_diff' => 0,
			'is_new_detail' => ($oldWeight == 0),
		]);
	}

	private function getReceiveLotColumn()
	{
		if ($this->db->field_exists('lot', 'pre_receive_garnu_dhal')) {
			return 'lot';
		}

		return 'closing_touce';
	}

	private function generateReceiveLotCode($rowMaterialId, $touch)
	{
		$rowMaterial = $this->db
			->select('name')
			->where('id', (int) $rowMaterialId)
			->get('item')
			->row_array();

		$prefix = 'LOT';
		if (!empty($rowMaterial['name'])) {
			$nameParts = preg_split('/\s+/', trim($rowMaterial['name']));
			if (!empty($nameParts[0])) {
				$prefix = strtoupper($nameParts[0]);
			}
		}

		return $prefix . '_' . $touch;
	}
}
