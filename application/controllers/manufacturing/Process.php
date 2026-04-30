<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
{
	const View = "admin/manufacturing/process";
	const receiveGarnu = "admin/manufacturing/receive_garnu";
	const printGivenData = "admin/manufacturing/print/print_given_data";

	public function __construct()
	{
		parent::__construct();
		check_login();
		$this->load->model('manufacturing/Process_model', "modal");
		$this->load->helper('lot_management');
	}

	public function manage($id = null, $pid = null)
	{
		$page_data['id']   = $id;
		$page_data['data'] = $this->db->select('*')->from('garnu')->where('id', $id)->get()->row_array();
		if (empty($page_data['data'])) {
			$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('dashboard');
			flash()->withError("Data Not Found .")->to($referrer);
		}
		$page_data['process_data']       = $this->db->select('*')->from('given')->where('id', $pid)->get()->row_array();
		$page_data['given_row_material'] = $this->db->select('*')->from('given_row_material')->where(array('given_id' => $pid, 'garnu_id' => $id))->get()->result_array();
		// $page_data['row_material']       = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['row_material']       = $this->db->select('id,name')->from('item')->get()->result_array();
		$page_data['metal_type']         = $this->db->select('id,name')->from('metal_type')->get()->result_array();
		if (!empty($pid)) {
			$page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where(array('garnu_id' => $id))->order_by('code', 'DESC')->get()->result_array();
		} else {
			$page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where(array('garnu_id' => $id, 'is_full' => 'NO'))->order_by('code', 'DESC')->get()->result_array();
		}
		if (!empty($pid)) {
			$page_data['lot_wise_rm'] = $this->db->select('id,touch,code,rem_weight,rem_quantity')->from('lot_wise_rm')->order_by('id', 'DESC')->get()->result_array();
		} else {
			$page_data['lot_wise_rm'] = $this->db->select('id,touch,code,rem_weight,rem_quantity')->from('lot_wise_rm')->where(array('is_complated' => 'NO'))->order_by('id', 'DESC')->get()->result_array();
		}
		$page_data['item']     = $this->db->select('*')->from('item')->get()->result_array();
		$page_data['receiveLot_wise_rm'] = $this->db->select('id,touch,code,weight,quantity,rem_weight,rem_quantity')->from('lot_wise_rm')->order_by('id', 'DESC')->get()->result_array();
		$page_data['table']              = $this->db->select('given.*,customer.name AS customer_name,process.finished_good, process.name AS process_name,process.show_or_not,process.labour_type')->from('given')->where('garnu_id', $id)->join('process', 'given.process_id = process.id', 'left')->join('customer', 'given.worker_id = customer.id', 'left')->get()->result();
		$page_data['page_title']         = 'Process';
		$page_data['process']            = $this->modal->fetch_process();
		return view(self::View, $page_data);
	}

	public function getWorkers()
	{
		$post = xss_clean($this->input->post());
		$data = $this->db->select('id,name')->from('customer')->where('find_in_set("' . $post['process_id'] . '", process_id) <> 0')->get()->result_array();
		echo json_encode($data);
	}

	public function getRMWiseLot()
	{
		$post = xss_clean($this->input->post());

		$given_id = $post['given_id'] ?? "";
		$garnu_id = $post['garnu_id'] ?? "";
		$detail_id = isset($post['detail_id']) ? (int) $post['detail_id'] : 0;
		$detail_type = isset($post['detail_type']) ? (string) $post['detail_type'] : 'given';

		// 		if(!empty($post['lot_wise_rm_id'])){
		// 	        $data = $this->db->select('id,touch,code,rem_weight,rem_quantity')->from('lot_wise_rm')
		// 	        ->where('id !=', $post['lot_wise_rm_id'])
		// 	        ->where('is_complated !=', 'NO')
		// 	        ->or_where('row_material_id', $post['row_material_id'])
		// 	        ->order_by('id', 'DESC')
		// 	        ->get()->result_array();

		// 		}else{
		if (!empty($given_id) && !empty($garnu_id)) {
			$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
			$query = "
                    SELECT * FROM (
                        SELECT l.id, l.touch, l.code, l.rem_weight, l.rem_quantity
                        FROM lot_wise_rm l
                        JOIN given_row_material g ON l.id = g.lot_wise_rm_id
                        WHERE l.row_material_id = ? AND g.lot_wise_rm_id != 0 AND g.given_id = $given_id AND g.garnu_id = $garnu_id
                        UNION
                        SELECT l.id, l.touch, l.code, l.rem_weight, l.rem_quantity
                        FROM lot_wise_rm l
                        WHERE l.row_material_id = ? AND l.is_complated = 'NO'
                    ) AS result_set
                    ORDER BY id DESC
                ";

			$params = array($post['row_material_id'], $post['row_material_id']);
			$data = $this->db->query($query, $params)->result_array();
			if (!empty($post['lot_wise_rm_id'])) {
				$data2 = $this->db->select('id, touch, code, rem_weight, rem_quantity')->from('lot_wise_rm')->where(array('id' => $post['lot_wise_rm_id'], 'row_material_id' => $post['row_material_id']))->order_by('id', 'DESC')->get()->result_array();
				$all_data = array_merge($data, $data2);
				$data = array_intersect_key($all_data, array_unique(array_column($all_data, 'id')));
			}
		} else {
			$data = $this->db->select('id,touch,code,rem_weight,rem_quantity')->from('lot_wise_rm')->where(array('row_material_id' => $post['row_material_id'], 'is_complated' => 'NO'))->order_by('id', 'DESC')->get()->result_array();

			if (!empty($post['lot_wise_rm_id'])) {
				$data2 = $this->db->select('id, touch, code, rem_weight, rem_quantity')->from('lot_wise_rm')->where(array('id' => $post['lot_wise_rm_id'], 'row_material_id' => $post['row_material_id']))->order_by('id', 'DESC')->get()->result_array();
				$all_data = array_merge($data, $data2);
				$data = array_intersect_key($all_data, array_unique(array_column($all_data, 'id')));
			}
		}
		// 		}
		if ($detail_id > 0 && !empty($data)) {
			$detailTable = ($detail_type === 'receive') ? 'receive_row_material' : 'given_row_material';
			$oldDetail = $this->db
				->select('lot_wise_rm_id, weight, quantity')
				->where('id', $detail_id)
				->get($detailTable)
				->row_array();

			if (!empty($oldDetail)) {
				foreach ($data as &$lotRow) {
					if ((int) $lotRow['id'] === (int) $oldDetail['lot_wise_rm_id']) {
						$lotRow['old_weight'] = (float) ($oldDetail['weight'] ?? 0);
						$lotRow['old_quantity'] = (float) ($oldDetail['quantity'] ?? 0);
					}
				}
				unset($lotRow);
			}
		}

		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Fetched Failed.'];
		}
		echo json_encode($response);
		return;
	}

	public function getGRDiffrence()
	{
		$post = xss_clean($this->input->post());

		$givenData = $this->db->select('given_row_material.*,row_material.name as row_material_name,CONCAT(lot_wise_rm.id, " - ", lot_wise_rm.code, " Weight: ", lot_wise_rm.weight, " Quantity: ", lot_wise_rm.quantity) as lot_wise')
			->from('given_row_material')
			->join('row_material', 'given_row_material.row_material_id = row_material.id', 'left')
			->join('lot_wise_rm', 'given_row_material.lot_wise_rm_id = lot_wise_rm.id', 'left')
			->where(array(
				'given_row_material.given_id' => $post['given_id'],
				'given_row_material.garnu_id' => $post['garnu_id']
			))
			->order_by('row_material.id', 'ASC')
			->get()->result_array();

		$receive = $this->db->select('*')->from('receive')->where(array('given_id' => $post['given_id'], 'garnu_id' => $post['garnu_id']))->order_by('id', 'DESC')->get()->result_array();
		$allReceivedData = [];

		foreach ($receive as $data) {
			$receivedData = $this->db->select('receive_row_material.*,row_material.name as row_material_name,CONCAT(lot_wise_rm.id, " - ", lot_wise_rm.code, " Weight: ", lot_wise_rm.weight, " Quantity: ", lot_wise_rm.quantity) as lot_wise')
				->from('receive_row_material')
				->join('row_material', 'receive_row_material.row_material_id = row_material.id', 'left')
				->join('lot_wise_rm', 'receive_row_material.lot_wise_rm_id = lot_wise_rm.id', 'left')
				->where(array('receive_row_material.received_id' => $data['id'], 'receive_row_material.garnu_id' => $post['garnu_id']))
				->order_by('row_material.id', 'ASC')
				->get()->result_array();

			$allReceivedData = array_merge($allReceivedData, $receivedData);
		}

		$maxCount = max(count($givenData), count($allReceivedData));
		$maxData = (count($givenData) > count($allReceivedData)) ? 'givenData' : 'receiveData';
		$data = ['givenData' => $givenData, 'receiveData' => $allReceivedData, 'maxCount' => $maxCount, 'maxData' => $maxData];

		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Fetched Failed.'];
		}
		echo json_encode($response);
		return;
	}

	public function fechWeight()
	{
		$post = xss_clean($this->input->post());
		$data = $this->db->select('total_weight')->get_where('receive', array('code' => $post['code']))->row_array();
		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Fetched Failed.'];
		}
		echo json_encode($response);
		return;
	}

	public function fechCode()
	{
		$post = xss_clean($this->input->post());
		$id   = $post['garnu_id'];
		if (!empty($pid)) {
			$data = $this->db->select('id, code')->from('receive')->where(array('garnu_id' => $id))->order_by('code', 'DESC')->get()->result_array();
		} else {
			$data = $this->db->select('id, code')->from('receive')->where(array('garnu_id' => $id, 'is_full' => 'NO'))->order_by('code', 'DESC')->get()->result_array();
		}
		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Fetched Failed.'];
		}
		echo json_encode($response);
	}

	public function add()
	{
		ob_start();

		$post = xss_clean($this->input->post());

		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'trim')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('given_touch', 'Given Touch', 'required')
			->set_error_delimiters('<div class="text-danger">', '</div>');


		// 		if ($post['process'] == 1) {
		// 			$validation->set_rules('material_type_id', 'Material Type', 'required')
		// 				->set_rules('closing_touch', 'Closing Touch', 'required');
		// 		}


		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$data                        = array();
		$givenQtyInput               = isset($post['given_qty']) ? (float) $post['given_qty'] : 0;
		$rmQtyTotal                  = 0;
		if (isset($post['rmQuantity']) && is_array($post['rmQuantity'])) {
			foreach ($post['rmQuantity'] as $qty) {
				$rmQtyTotal += (float) $qty;
			}
		}
		$data['user_id']             = session('id');
		$data['garnu_id']            = $post['garnu_id'];
		$data['process_id']          = $post['process'];
		$data['material_type_id']    = $post['material_type_id'];
		$data['closing_touch']        = $post['closing_touch'] ?? "";
		$data['worker_id']           = $post['workers'];
		$data['item_id']             = $post['item_id'] ?? 0;
		$data['remarks']             = $post['remarks'];
		$data['given_qty']           = ($givenQtyInput != 0) ? $givenQtyInput : $rmQtyTotal;
		$data['given_weight']        = $post['given_weight'];
		$data['given_touch']         = $post['given_touch'];
		$data['row_material_weight'] = $post['total-rm_weight'];
		$data['total_weight']  		 = $post['total_weight'];
		$data['receive_code']  		 = isset($post['receive_code']) ? $post['receive_code'] : '';
		$data['creation_date'] 		 = date('Y-m-d');
		$data['is_full']       = isset($post['is_full']) ? $post['is_full'] : 'NO';

		$this->db->trans_begin();

		$this->db->insert('given', $data);
		$given_id = $this->db->insert_id();

		if (!$given_id) {
			$this->db->trans_rollback();
			return flash()->withError("Process save failed.")->back();
		}

		if (!empty($post['receive_id'])) {
			$receive_id = $post['receive_id'];
			$this->db->where('id', $receive_id)->update('receive', ['isGiven' => 'YES']);
		}

		if (!$this->syncGivenMainLot($data)) {
			$this->db->trans_rollback();
			return flash()->withError("Main given lot update failed. Please check Material Type and Closing Touch lot.")->back();
		}

		$batchData = [];
		$fallbackGivenQty = isset($post['given_qty']) ? (float) $post['given_qty'] : 0;
		$rmRowCount = (isset($post['rowid']) && is_array($post['rowid'])) ? count($post['rowid']) : 0;
		$rowIds = isset($post['rowid']) && is_array($post['rowid']) ? $post['rowid'] : [];

		foreach ($rowIds as $key => $rowid) {
			$rowMaterialId = $post['row_material'][$key] ?? '';
			$rmTouch = $post['rmTouch'][$key] ?? '';
			$rmWeight = $post['rmWeight'][$key] ?? '';
			$rmQuantityValue = $post['rmQuantity'][$key] ?? '';

			if (!empty($rowMaterialId) || (float) $rmWeight != 0 || (float) $rmQuantityValue != 0) {
				$rmQuantity = $rmQuantityValue !== '' ? (float) $rmQuantityValue : 0;

				if ($rmQuantity == 0 && $rmRowCount === 1 && $fallbackGivenQty != 0) {
					$rmQuantity = $fallbackGivenQty;
				}
				$rmData = [
					'user_id'  		  => session('id'),
					'given_id' 		  => $given_id,
					'garnu_id' 		  => isset($post['garnu_id']) ? $post['garnu_id'] : null,
					'row_material_id' => $rowMaterialId ?: null,
					'lot_wise_rm_id'  => isset($post['lot_wise_rm_id'][$key]) ? (int) $post['lot_wise_rm_id'][$key] : 0,
					'touch'    		  => $rmTouch !== '' ? $rmTouch : null,
					'weight'   		  => $rmWeight !== '' ? (float) $rmWeight : 0,
					'quantity' 		  => $rmQuantity,
					'creation_date'   => date('Y-m-d'),
				];

				if ($rmData['lot_wise_rm_id'] > 0) {
					$lotUpdated = lot_management([
						'id' => $rmData['lot_wise_rm_id'],
						'row_material_id' => $rmData['row_material_id'],
						'weight' => $rmData['weight'],
						'quantity' => $rmData['quantity'],
						'movement' => 'given',
						'update_lot_values' => false,
						'is_new_detail' => true,
						'strict_row_material_match' => true,
					]);

					if (!$lotUpdated) {
						$this->db->trans_rollback();
						return flash()->withError("Lot update failed for given row material.")->back();
					}
				}
				$batchData[] = $rmData;
			}
		}
		if (!empty($batchData)) {
			$this->db->insert_batch('given_row_material', $batchData);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return flash()->withError("Process save failed. Please try again.")->back();
		}

		$this->db->trans_commit();

		ob_clean();

		// 		if ($post['action'] == "save_and_print") {
		// Redirect to print URL in a new tab


		echo "<script>
			window.open('" . site_url('manufacturing/process/given_print/' . $post['garnu_id'] . '/' . $given_id) . "', '_blank');
                   
		</script>";

		$this->session->set_flashdata('flash', [
			'class' => 'success',
			'message' => 'Data save Successfully.'
		]);

		redirect('manufacturing/process/manage/' . $post['garnu_id'], 'refresh');

		// 		$garnu_id                = $post['garnu_id'];
		// 		$page_data['page_title'] = 'Print Given Filing Data';
		// 		$page_data['data']       = $this->modal->printGivenItemData($garnu_id, $given_id);
		// 		$page_data['garnu_id']   = $garnu_id;
		// 		$page_data['given_id']   = $given_id;

		// 		if (!empty($page_data['data']['givenData']) || !empty($page_data['data']['givenRowMaterial'])) {
		// 			$this->load->view("admin/manufacturing/print/print_given_data.php", $page_data);
		// 		} else {
		// 		    flash()->withError("Data Not Found.")->back();
		// 		}
	}

	public function update()
	{
		ob_start();

		$id = (int) $this->input->post('given_id');
		$post = xss_clean($this->input->post());

		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'trim')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('given_touch', 'Given Touch', 'required')
			->set_rules('total-rm_weight', 'Row Material Weight', 'trim')
			->set_rules('total_weight', 'Final Weight', 'trim')
			->set_error_delimiters('<div class="text-danger">', '</div>');

		if (isset($post['process']) && $post['process'] == 1) {
			$validation->set_rules('material_type_id', 'Material Type', 'required')
				->set_rules('closing_touch', 'Closing Touch', 'required');
		}
		
		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$oldGivenData = $this->db
			->where('id', $id)
			->get('given')
			->row_array();

		if (empty($oldGivenData)) {
			return flash()->withError("Given data not found.")->back();
		}

		$data = [];

		$givenQtyInput = isset($post['given_qty']) ? (float) $post['given_qty'] : 0;

		$rmQtyTotal = 0;
		if (isset($post['rmQuantity']) && is_array($post['rmQuantity'])) {
			foreach ($post['rmQuantity'] as $qty) {
				$rmQtyTotal += (float) $qty;
			}
		}

		$data['garnu_id'] 		     = $post['garnu_id'];
		$data['process_id'] 	     = $post['process'];
		$data['material_type_id']    = $post['material_type_id'] ?? 0;
		$data['closing_touch'] 	     = $post['closing_touch'] ?? 0;
		$data['worker_id'] 		     = $post['workers'];
		$data['remarks'] 		     = $post['remarks'];
		$data['given_qty'] 		     = ($givenQtyInput != 0) ? $givenQtyInput : $rmQtyTotal;
		$data['item_id'] 		     = $post['item_id'] ?? 0;
		$data['given_weight'] 	     = $post['given_weight'];
		$data['given_touch'] 	     = $post['given_touch'];
		$data['row_material_weight'] = $post['total-rm_weight'];
		$data['total_weight'] 		 = $post['total_weight'];
		$data['receive_code'] 		 = $post['receive_code'] ?? '';

		$this->db->trans_begin();

		$this->db->where('id', $id)->update('given', $data);

		/*
		* Main Process Given lot update:
		* given_weight / given_qty => PLUS in given_weight / given_quantity
		* given_weight / given_qty => MINUS from rem_weight / rem_quantity
		* touch / weight / quantity main lot values are not overwritten.
		*/
		if (!$this->syncGivenMainLot($data, $oldGivenData)) {
			$this->db->trans_rollback();
			return flash()->withError("Main given lot update failed. Please check Material Type and Closing Touch lot.")->back();
		}

		$insertBatch = [];
		$updateBatch = [];

		$existingIds = [];
		if (isset($post['rowid']) && is_array($post['rowid'])) {
			foreach ($post['rowid'] as $rowid) {
				if ((int) $rowid > 0) {
					$existingIds[] = (int) $rowid;
				}
			}
		}

		$allids = [];
		if (isset($post['ids']) && is_array($post['ids'])) {
			foreach ($post['ids'] as $oldId) {
				if ((int) $oldId > 0) {
					$allids[] = (int) $oldId;
				}
			}
		}

		$idsNotExisting = array_diff($allids, $existingIds);

		/*
		* Deleted Add Row Material modal rows:
		* restore old given_weight / given_quantity
		* restore rem_weight / rem_quantity
		*/
		if (!empty($idsNotExisting)) {
			$deletedRows = $this->db
				->where('given_id', $id)
				->where('garnu_id', $post['garnu_id'])
				->where_in('id', $idsNotExisting)
				->get('given_row_material')
				->result_array();

			foreach ($deletedRows as $deletedRow) {
				if (!empty($deletedRow['lot_wise_rm_id'])) {
					$lotUpdated = lot_management([
						'id' => (int) $deletedRow['lot_wise_rm_id'],
						'row_material_id' => (int) $deletedRow['row_material_id'],
						'old_row_material_id' => (int) $deletedRow['row_material_id'],
						'weight' => 0,
						'quantity' => 0,
						'old_weight' => (float) $deletedRow['weight'],
						'old_quantity' => (float) $deletedRow['quantity'],
						'movement' => 'given',
						'update_lot_values' => false,
						'strict_row_material_match' => true,
					]);

					if (!$lotUpdated) {
						$this->db->trans_rollback();
						return flash()->withError("Lot update failed while deleting given row material.")->back();
					}
				}
			}

			$this->db
				->where('given_id', $id)
				->where('garnu_id', $post['garnu_id'])
				->where_in('id', $idsNotExisting)
				->delete('given_row_material');
		}

		$oldRows = [];
		if (!empty($existingIds)) {
			$oldGivenRows = $this->db
				->where('given_id', $id)
				->where('garnu_id', $post['garnu_id'])
				->where_in('id', $existingIds)
				->get('given_row_material')
				->result_array();

			foreach ($oldGivenRows as $oldGivenRow) {
				$oldRows[(int) $oldGivenRow['id']] = $oldGivenRow;
			}
		}

		$fallbackGivenQty = isset($post['given_qty']) ? (float) $post['given_qty'] : 0;
		$rmRowCount = (isset($post['rowid']) && is_array($post['rowid'])) ? count($post['rowid']) : 0;
		$rowIds = isset($post['rowid']) && is_array($post['rowid']) ? $post['rowid'] : [];

		foreach ($rowIds as $key => $rowid) {
			$rowid = (int) $rowid;

			$rowMaterialId = $post['row_material'][$key] ?? '';
			$rmTouch = $post['rmTouch'][$key] ?? '';
			$rmWeight = $post['rmWeight'][$key] ?? '';
			$rmQuantityValue = $post['rmQuantity'][$key] ?? '';

			$hasRmData = !empty($rowMaterialId)
				|| (float) $rmWeight != 0
				|| (float) $rmQuantityValue != 0;

			$oldRow = $rowid > 0 && isset($oldRows[$rowid]) ? $oldRows[$rowid] : null;

			/*
			* Empty row:
			* if old row exists, remove its effect from lot and delete row.
			*/
			if (!$hasRmData) {
				if ($rowid > 0 && $oldRow !== null) {
					if (!empty($oldRow['lot_wise_rm_id'])) {
						$lotUpdated = lot_management([
							'id' => (int) $oldRow['lot_wise_rm_id'],
							'row_material_id' => (int) $oldRow['row_material_id'],
							'old_row_material_id' => (int) $oldRow['row_material_id'],
							'weight' => 0,
							'quantity' => 0,
							'old_weight' => (float) $oldRow['weight'],
							'old_quantity' => (float) $oldRow['quantity'],
							'movement' => 'given',
							'update_lot_values' => false,
							'strict_row_material_match' => true,
						]);

						if (!$lotUpdated) {
							$this->db->trans_rollback();
							return flash()->withError("Lot update failed while removing empty given row material.")->back();
						}
					}

					$this->db
						->where('given_id', $id)
						->where('garnu_id', $post['garnu_id'])
						->where('id', $rowid)
						->delete('given_row_material');
				}

				continue;
			}

			if ($rowid > 0 && $oldRow === null) {
				$this->db->trans_rollback();
				return flash()->withError("Invalid given row material detail.")->back();
			}

			$rmQuantity = $rmQuantityValue !== '' ? (float) $rmQuantityValue : 0;

			if ($rmQuantity == 0 && $rmRowCount === 1 && $fallbackGivenQty != 0) {
				$rmQuantity = $fallbackGivenQty;
			}

			$rmData = [
				'row_material_id' => $rowMaterialId ?: null,
				'lot_wise_rm_id' => isset($post['lot_wise_rm_id'][$key]) ? (int) $post['lot_wise_rm_id'][$key] : 0,
				'touch' => $rmTouch !== '' ? $rmTouch : null,
				'weight' => $rmWeight !== '' ? (float) $rmWeight : 0,
				'quantity' => $rmQuantity,
			];

			/*
			* Add Row Material modal lot update:
			* weight / quantity are added to given_weight / given_quantity
			* weight / quantity are subtracted from rem_weight / rem_quantity
			* touch is not changed in lot_wise_rm.
			*/
			if ($rmData['lot_wise_rm_id'] > 0 || !empty($oldRow['lot_wise_rm_id'])) {
				$currentLotId = (int) $rmData['lot_wise_rm_id'];

				if ($currentLotId <= 0 && !empty($oldRow['lot_wise_rm_id'])) {
					$currentLotId = (int) $oldRow['lot_wise_rm_id'];
				}

				$currentRowMaterialId = (int) $rmData['row_material_id'];

				if ($currentRowMaterialId <= 0 && !empty($oldRow['row_material_id'])) {
					$currentRowMaterialId = (int) $oldRow['row_material_id'];
				}

				$oldLotId = $currentLotId;
				if (!empty($oldRow['lot_wise_rm_id'])) {
					$oldLotId = (int) $oldRow['lot_wise_rm_id'];
				}

				$oldRowMaterialId = $currentRowMaterialId;
				if (!empty($oldRow['row_material_id'])) {
					$oldRowMaterialId = (int) $oldRow['row_material_id'];
				}

				$lotUpdated = lot_management([
					'id' => $currentLotId,
					'row_material_id' => $currentRowMaterialId,
					'old_row_material_id' => $oldRowMaterialId,
					'weight' => (float) $rmData['weight'],
					'quantity' => (float) $rmData['quantity'],
					'old_weight' => (float) ($oldRow['weight'] ?? 0),
					'old_quantity' => (float) ($oldRow['quantity'] ?? 0),
					'old_lot_wise_rm_id' => $oldLotId,
					'movement' => 'given',
					'update_lot_values' => false,
					'is_new_detail' => empty($oldRow),
					'strict_row_material_match' => true,
				]);

				if (!$lotUpdated) {
					$this->db->trans_rollback();
					return flash()->withError("Lot update failed for given row material.")->back();
				}
			}

			if ($rowid === 0) {
				$rmData['given_id'] = $id;
				$rmData['user_id'] = session('id');
				$rmData['creation_date'] = date('Y-m-d');
				$rmData['garnu_id'] = isset($post['garnu_id']) ? $post['garnu_id'] : null;
				$insertBatch[] = $rmData;
			} else {
				$rmData['id'] = $rowid;
				$updateBatch[] = $rmData;
			}
		}

		if (!empty($insertBatch)) {
			$this->db->insert_batch('given_row_material', $insertBatch);
		}

		if (!empty($updateBatch)) {
			$this->db->update_batch('given_row_material', $updateBatch, 'id');
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return flash()->withError("Process update failed. Please try again.")->back();
		}

		$this->db->trans_commit();

		ob_clean();

		if (isset($post['action']) && $post['action'] == "save_and_print") {
			echo "<script>
				window.open('" . site_url('manufacturing/process/given_print/' . $post['garnu_id'] . '/' . $id) . "', '_blank');
			</script>";
		}

		$this->session->set_flashdata('flash', [
			'class' => 'success',
			'message' => 'Update Successfully.'
		]);

		redirect('manufacturing/process/manage/' . $post['garnu_id'], 'refresh');
	}

	public function receiveGarnu()
	{
		$validation = $this->form_validation;
		$validation->set_rules('garnu_id', 'Garnu Id', 'required')
			->set_rules('given_id', 'Given Id', 'required');

		if (!$validation->run()) {
			$response = ['success' => false, 'message' => validation_errors()];
			echo json_encode($response);
			return;
		}

		$post     = $this->input->post();
		$garnu_id = $post['garnu_id'];
		$given_id = $post['given_id'];

		$page_data['garnuData'] = $this->dbh->getWhereRowArray('garnu', ['id' => $garnu_id]);
		if (!empty($page_data['garnuData'])) {
			$page_data['givenData'] = $this->db->select('given.*,process.name AS process_name,process.labour_type')->from('given')->join('process', 'given.process_id = process.id', 'left')->where('given.id', $given_id)->get()->row_array();
			if (!empty($page_data['givenData'])) {
				$page_data['metalData']    = $this->dbh->getWhereResultArray('process_metal_type', ['given_id' => $given_id]);
				$page_data['GivenRMData'] = $this->dbh->getWhereResultArray('given_row_material', ['given_id' => $given_id, 'garnu_id' => $garnu_id]);
				$page_data['receivedData'] = $this->dbh->getWhereResultArray('receive', ['given_id' => $given_id, 'garnu_id' => $garnu_id]);
				if (!empty($page_data['receivedData']) || !empty($page_data['metalData']) || !empty($page_data['garnuData']) && !empty($page_data['givenData'])) {
					$page_data['item']     = $this->db->select('*')->from('item')->get()->result_array();
					$page_data['customer'] = $this->dbh->getWhereResultArray('customer', ['account_type_id' => 2, 'process_id' => $page_data['givenData']['process_id']]);
					$view                  = $this->load->view(self::receiveGarnu, $page_data, true);
					$response              = ['success' => true, 'message' => 'Data Feched Successfully', 'data' => $view];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found.'];
				}
			} else {
				$response = ['success' => false, 'message' => 'Invalid Given Id'];
			}
		} else {
			$response = ['success' => false, 'message' => 'Invalid Garnu Id'];
		}

		echo json_encode($response);
		return;
	}

	public function receiveGarnuAdd()
	{
		$post = $this->input->post();
		$postedReceiveIds = isset($post['rcid']) && is_array($post['rcid']) ? $post['rcid'] : [];
		$existingIds = [];
		foreach ($postedReceiveIds as $receiveId) {
			if ((int) $receiveId > 0) {
				$existingIds[] = (int) $receiveId;
			}
		}

		$allids = [];
		if (isset($post['ids']) && is_array($post['ids'])) {
			foreach ($post['ids'] as $oldReceiveId) {
				if ((int) $oldReceiveId > 0) {
					$allids[] = (int) $oldReceiveId;
				}
			}
		}
		$idsNotExisting = array_diff($allids, $existingIds);
		$given_id = $post['given_id'];
		$garnu_id = $post['garnu_id'];
		$user_id = session('id');

		$is_completed = isset($post['is_completed']) && $post['is_completed'] == 'on' ? "YES" : "NO";
		$is_kasar     = isset($post['is_kasar']) && $post['is_kasar'] == 'on' ? "YES" : "NO";

		$transfer_account = NULL;

		if ($is_kasar == "YES") {
			$transfer_account = ($post['transfer_account'] ?? NULL);
		}

		$this->db->trans_begin();

		$this->db->where('id', $given_id)->update('given', ['vadharo_dhatado' => $post['jama_baki'], 'is_completed' => $is_completed, 'is_kasar' => $is_kasar, "transfer_account" => $transfer_account]);


		if (!empty($idsNotExisting)) {
			$deletedReceiveRows = $this->db
				->where_in('received_id', $idsNotExisting)
				->get('receive_row_material')
				->result_array();

			foreach ($deletedReceiveRows as $deletedReceiveRow) {
				if (!empty($deletedReceiveRow['lot_wise_rm_id'])) {
					$lotUpdated = lot_management([
						'id' => (int) $deletedReceiveRow['lot_wise_rm_id'],
						'row_material_id' => (int) $deletedReceiveRow['row_material_id'],
						'weight' => 0,
						'quantity' => 0,
						'old_weight' => (float) $deletedReceiveRow['weight'],
						'old_quantity' => (float) $deletedReceiveRow['quantity'],
						'movement' => 'receive',
						'update_lot_values' => false,
					]);

					if (!$lotUpdated) {
						$this->db->trans_rollback();
						echo json_encode(['success' => false, 'message' => 'Lot update failed while deleting receive row material.']);
						return;
					}
				}
			}

			$this->db->where_in('id', $idsNotExisting)->delete('receive');
			$this->db->where_in('received_id', $idsNotExisting)->delete('receive_row_material');
		}

		foreach ($postedReceiveIds as $key => $rcid) {
			$rcid = (int) $rcid;
			$rawMaterialData = $post['raw-material-data'][$key] ?? '';
			$hasReceiveData = $rcid > 0
				|| $rawMaterialData !== ''
				|| (float) ($post['pcs'][$key] ?? 0) != 0
				|| (float) ($post['weight'][$key] ?? 0) != 0
				|| (float) ($post['total_weight'][$key] ?? 0) != 0
				|| (float) ($post['rm_weight'][$key] ?? 0) != 0
				|| trim((string) ($post['remark'][$key] ?? '')) !== '';

			if (!$hasReceiveData) {
				continue;
			}

			$lot_creation = isset($post['lot_creation_value'][$key]) && $post['lot_creation_value'][$key] == 'YES' ? "YES" : "NO";
			$receivedData = [
				'item_id'             => isset($post['item_id'][$key]) ? $post['item_id'][$key] : 0,
				'pcs'                 => isset($post['pcs'][$key]) ? $post['pcs'][$key] : 0,
				'weight'              => isset($post['weight'][$key]) ? $post['weight'][$key] : 0,
				'labour_type'         => isset($post['labour_type'][$key]) ? $post['labour_type'][$key] : null,
				'labour'              => isset($post['labour'][$key]) ? $post['labour'][$key] : 0,
				'total_labour'        => isset($post['totalLabour'][$key]) ? $post['totalLabour'][$key] : 0,
				'final_labour'        => isset($post['finalLabour'][$key]) ? $post['finalLabour'][$key] : 0,
				'row_material_weight' => isset($post['rm_weight'][$key]) ? $post['rm_weight'][$key] : 0,
				'total_weight'        => isset($post['total_weight'][$key]) ? $post['total_weight'][$key] : 0,
				'touch'               => isset($post['touch'][$key]) ? $post['touch'][$key] : 0,
				'fine'                => isset($post['fine'][$key]) ? $post['fine'][$key] : 0,
				'remark'              => isset($post['remark'][$key]) ? $post['remark'][$key] : null,
				'lot_creation'        => $lot_creation,
			];

			if ($rcid === 0) {
				$receivedData['given_id']      = $given_id;
				$receivedData['garnu_id']      = $garnu_id;
				$receivedData['user_id']       = $user_id;
				$receivedData['creation_date'] = date('Y-m-d');
				$this->db->insert('receive', $receivedData);
				$receive_id = $this->db->insert_id();
				$code       = date('M') . "_R$receive_id" . "_G$given_id";
				$this->db->where('id', $receive_id)->update('receive', ['code' => $code]);
			} else {
				$receive_id = $rcid;
				$this->dbh->updateRow('receive', $rcid, $receivedData);
			}

			$updateArray = [
				'rcdid' => [],
				'rm' => [
					'insert' => [],
					'update' => [],
					'delete' => [],
				],
			];
			$oldReceiveRows = $this->db
				->where('received_id', $receive_id)
				->get('receive_row_material')
				->result_array();
			$oldReceiveRowsById = [];
			foreach ($oldReceiveRows as $oldReceiveRow) {
				$oldReceiveRowsById[(int) $oldReceiveRow['id']] = $oldReceiveRow;
			}

			if ($rawMaterialData !== '' && $rawMaterialData !== null) {
				$rm_data = explode('|', $rawMaterialData);
				foreach ($rm_data as $rcD) {
					$rm = array_pad(explode(',', $rcD), 9, '');
					$rowMaterialId = (int) $rm[0];
					$lotInput = trim((string) $rm[1]);
					$touch = ($rm[2] === '' || $rm[2] === null) ? 0 : (float) $rm[2];
					$rmWeight = ($rm[3] === '' || $rm[3] === null) ? 0 : (float) $rm[3];
					$rmQuantity = ($rm[4] === '' || $rm[4] === null) ? 0 : (float) $rm[4];
					if ($rmQuantity == 0 && isset($post['pcs'][$key]) && (float) $post['pcs'][$key] != 0) {
						$rmQuantity = (float) $post['pcs'][$key];
					}
					$detailId = (int) $rm[8];

					if (empty($rowMaterialId) && $rmWeight == 0 && $rmQuantity == 0) {
						continue;
					}

					if ($detailId > 0) {
						$updateArray['rcdid'][] = $detailId;
					}

					$oldRow = $detailId > 0 && isset($oldReceiveRowsById[$detailId]) ? $oldReceiveRowsById[$detailId] : null;

					if ($lotInput !== '' && is_numeric($lotInput) && (int) $lotInput > 0) {
						$lot_wise_rm_id = (int) $lotInput;
						$oldLotId = $lot_wise_rm_id;
						if (!empty($oldRow['lot_wise_rm_id'])) {
							$oldLotId = (int) $oldRow['lot_wise_rm_id'];
						}

						$lotUpdated = lot_management([
							'id' => $lot_wise_rm_id,
							'row_material_id' => $rowMaterialId,
							'weight' => $rmWeight,
							'quantity' => $rmQuantity,
							'old_weight' => (float) ($oldRow['weight'] ?? 0),
							'old_quantity' => (float) ($oldRow['quantity'] ?? 0),
							'old_lot_wise_rm_id' => $oldLotId,
							'movement' => 'receive',
							'update_lot_values' => false,
							'is_new_detail' => empty($oldRow),
						]);

						if (!$lotUpdated) {
							$this->db->trans_rollback();
							echo json_encode(['success' => false, 'message' => 'Lot update failed for receive row material.']);
							return;
						}
					} else {
						if (!empty($oldRow['lot_wise_rm_id'])) {
							$lotUpdated = lot_management([
								'id' => (int) $oldRow['lot_wise_rm_id'],
								'row_material_id' => (int) $oldRow['row_material_id'],
								'weight' => 0,
								'quantity' => 0,
								'old_weight' => (float) $oldRow['weight'],
								'old_quantity' => (float) $oldRow['quantity'],
								'movement' => 'receive',
								'update_lot_values' => false,
							]);

							if (!$lotUpdated) {
								$this->db->trans_rollback();
								echo json_encode(['success' => false, 'message' => 'Lot update failed while moving receive row material.']);
								return;
							}
						}

						if ($lotInput !== '') {
							$lotCode = $lotInput;
						} else {
							$rowM = $this->db->get_where('row_material', ['id' => $rowMaterialId])->row_array();
							$rowMaterialName = $rowM['name'] ?? ('RM_' . $rowMaterialId);
							$lotCode = explode(' ', trim($rowMaterialName))[0] . '_' . $touch;
						}

						$lot_wise_rm_id = lot_management([
							'user_id' => $user_id,
							'code' => $lotCode,
							'type' => 'RECEIVE',
							'touch' => $touch,
							'row_material_id' => $rowMaterialId,
							'weight' => $rmWeight,
							'quantity' => $rmQuantity,
							'receive_weight' => $rmWeight,
							'receive_quantity' => $rmQuantity,
							'rem_weight' => $rmWeight,
							'rem_quantity' => $rmQuantity,
							'creation_date' => date('Y-m-d'),
						]);

						if (!$lot_wise_rm_id) {
							$this->db->trans_rollback();
							echo json_encode(['success' => false, 'message' => 'Receive lot creation failed.']);
							return;
						}
					}

					$updateData = [
						'received_id'     => $receive_id,
						'garnu_id'        => $garnu_id,
						'row_material_id' => $rowMaterialId,
						'lot_wise_rm_id'  => $lot_wise_rm_id,
						'touch'           => $touch,
						'weight'          => $rmWeight,
						'quantity'        => $rmQuantity,
						'labour_type'     => $rm[5] !== '' ? $rm[5] : null,
						'labour'          => $rm[6] !== '' ? $rm[6] : 0,
						'total_labour'    => $rm[7] !== '' ? $rm[7] : 0,
					];

					if ($detailId > 0 && $oldRow !== null) {
						$updateData['id'] = $detailId;
						$updateArray['rm']['update'][] = $updateData;
					} else {
						$updateData['user_id'] = session('id');
						$updateData['creation_date'] = date('Y-m-d');
						$updateArray['rm']['insert'][] = $updateData;
					}
				}
			}

			foreach ($oldReceiveRowsById as $oldDetailId => $oldReceiveRow) {
				if (!in_array($oldDetailId, $updateArray['rcdid'], true)) {
					if (!empty($oldReceiveRow['lot_wise_rm_id'])) {
						$lotUpdated = lot_management([
							'id' => (int) $oldReceiveRow['lot_wise_rm_id'],
							'row_material_id' => (int) $oldReceiveRow['row_material_id'],
							'weight' => 0,
							'quantity' => 0,
							'old_weight' => (float) $oldReceiveRow['weight'],
							'old_quantity' => (float) $oldReceiveRow['quantity'],
							'movement' => 'receive',
							'update_lot_values' => false,
						]);

						if (!$lotUpdated) {
							$this->db->trans_rollback();
							echo json_encode(['success' => false, 'message' => 'Lot update failed while deleting receive row material.']);
							return;
						}
					}
					$updateArray['rm']['delete'][] = $oldDetailId;
				}
			}

			if (!empty($updateArray['rm']['insert'])) {
				$this->db->insert_batch('receive_row_material', $updateArray['rm']['insert']);
			}
			if (!empty($updateArray['rm']['update'])) {
				$this->db->update_batch('receive_row_material', $updateArray['rm']['update'], 'id');
			}
			if (!empty($updateArray['rm']['delete'])) {
				$this->db->where_in('id', $updateArray['rm']['delete'])->delete('receive_row_material');
			}
		}

		$metalType = $post['metalType-data'] ?? '';
		if (!empty($metalType) && $metalType !== NULL) {
			$rm_data                     = explode('|', $metalType);
			$rmDelete                    = $this->db->select('id')->where('given_id', $given_id)->get('process_metal_type')->result();
			$updateArray['pmtid']        = [];
			$updateArray['mt']['insert'] = [];
			$updateArray['mt']['update'] = [];
			$updateArray['mt']['delete'] = [];
			foreach ($rm_data as $rcD) {
				$rm = array_pad(explode(',', $rcD), 5, '');

				if (!empty($rm[0]) || !empty($rm[1]) || !empty($rm[2]) || !empty($rm[3])) {
					$processMetalTypeId = (int) $rm[4];
					if ($processMetalTypeId > 0) {
						$updateArray['pmtid'][] = $processMetalTypeId;
					}
					$updateData = [
						'given_id'      => $given_id,
						'metal_type_id' => $rm[0],
						'touch'         => $rm[1] ?? 0,
						'weight'        => $rm[2] ?? 0,
						'quantity'      => $rm[3] ?? 0,
					];
					if ($processMetalTypeId > 0) {
						$updateData['id']              = $processMetalTypeId;
						$updateArray['mt']['update'][] = $updateData;
					} else {
						$updateData['user_id']         = session('id');
						$updateData['creation_date']   = date('Y-m-d');
						$updateArray['mt']['insert'][] = $updateData;
					}
				}
			}

			if (!empty($updateArray['mt']['insert'])) {
				$this->db->insert_batch('process_metal_type', $updateArray['mt']['insert']);
			}
			if (!empty($updateArray['mt']['update'])) {
				$this->db->update_batch('process_metal_type', $updateArray['mt']['update'], 'id');
			}

			if ($rmDelete) {
				array_walk($rmDelete, function ($rmD) use (&$updateArray) {
					if (!in_array($rmD->id, $updateArray['pmtid'])) {
						$updateArray['mt']['delete'][] = $rmD->id;
					}
				});
				(!empty($updateArray['mt']['delete']) && $this->db->where_in('id', $updateArray['mt']['delete'])->delete('process_metal_type'));
			}
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$response = ['success' => false, 'message' => 'Receive data save failed.'];
		} else {
			$this->db->trans_commit();
			$response = ['success' => true, 'message' => 'Data Save Successfully.'];
		}

		echo json_encode($response);
		return;
	}

	public function givenRowMaterial()
	{
		try {
			$this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
			$this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'error' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData  = $this->input->post();
				$garnu_id  = $postData['garnu_id'];
				$given_id  = $postData['given_id'];
				$data      = $this->dbh->getWhereResultArray('given_row_material', ['garnu_id' => $garnu_id, 'given_id' => $given_id]);
				$garnuData = $this->db->select('name,garnu_weight')->from('garnu')->where('id', $garnu_id)->get()->row_array();
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
				'error'   => $e->getMessage(),
				'data'    => []
			];
			echo json_encode($response);
		}
	}

	public function givenRowMaterialView()
	{
		try {
			$this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
			$this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'error' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData  = $this->input->post();
				$garnu_id  = $postData['garnu_id'];
				$given_id  = $postData['given_id'];
				$data      = $this->dbh->getWhereResultArray('given_row_material', ['garnu_id' => $garnu_id, 'given_id' => $given_id]);
				$garnuData = $this->db->select('name,garnu_weight')->from('garnu')->where('id', $garnu_id)->get()->row_array();
				if (!empty($data) || !empty($garnuData)) {
					$view = $this->load->view("admin/manufacturing/row_meterial", ['data' => $data], true);
					$response = ['success' => true, 'message' => 'given row meterial fetched', "view" => $view];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
				}
				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error'   => $e->getMessage(),
				'data'    => []
			];
			echo json_encode($response);
		}
	}

	public function receiveRowMaterial()
	{
		try {
			$this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
			$this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'error' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData     = $this->input->post();
				$garnu_id     = $postData['garnu_id'];
				$given_id     = $postData['given_id'];
				$receive      = $this->dbh->getWhereResultArray('receive', ['garnu_id' => $garnu_id, 'given_id' => $given_id]);
				$receivedData = array_map(function ($row) {
					return $this->dbh->getWhereResultArray('receive_row_material', ['received_id' => $row['id']]);
				}, $receive);

				$data = array_reduce($receivedData, function ($carry, $item) {
					return array_merge($carry, $item);
				}, []);

				$garnuData = $this->db->select('name,garnu_weight')->from('garnu')->where('id', $garnu_id)->get()->row_array();
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
				'error'   => $e->getMessage(),
				'data'    => []
			];
			echo json_encode($response);
		}
	}

	public function given_print($garnu_id = null, $given_id = null)
	{
		$garnu_id                = $this->security->xss_clean($garnu_id);
		$given_id                = $this->security->xss_clean($given_id);
		$page_data['page_title'] = 'Print Given Filing Data';
		$page_data['data']       = $this->modal->printGivenItemData($garnu_id, $given_id);
		$page_data['garnu_id']   = $garnu_id;
		$page_data['given_id']   = $given_id;

		if (!empty($page_data['data']['givenData']) || !empty($page_data['data']['givenRowMaterial'])) {
			$this->load->view("admin/manufacturing/print/print_given_data.php", $page_data);
		} else {
			flash()->withError("Data Not Found.")->to("manufacturing/process/manage/" . $garnu_id);
		}
	}

	public function given_print2()
	{

		$postData = $this->security->xss_clean($this->input->post());
		$garnu_id                = $postData['garnu_id'];
		$given_id                = $postData['given_id'];
		$page_data['page_title'] = 'Print Given Filing Data';
		$page_data['data']       = $this->modal->printGivenItemData($garnu_id, $given_id);
		$page_data['garnu_id']   = $garnu_id;
		$page_data['given_id']   = $given_id;

		if (!empty($page_data['data']['givenData']) || !empty($page_data['data']['givenRowMaterial'])) {
			$this->load->view("admin/manufacturing/print/print_given_data.php", $page_data);
		} else {
			flash()->withError("Data Not Found.")->to("manufacturing/process/manage/" . $garnu_id);
		}
	}

	public function printThermal($garnu_id = null, $given_id = null)
	{

		$garnu_id                = $this->security->xss_clean($garnu_id);
		$given_id                = $this->security->xss_clean($given_id);
		$page_data['page_title'] = 'Print Given Filing Data';
		$page_data['data']       = $this->modal->printGivenItemData($garnu_id, $given_id);
		$page_data['garnu_id']   = $garnu_id;
		$page_data['given_id']   = $given_id;

		if (!empty($page_data['data']['givenData']) || !empty($page_data['data']['givenRowMaterial'])) {
			return view('admin/manufacturing/print/thermal_print.php', $page_data);
			// return $this->load->view("admin/manufacturing/print/thermal_print.php", $page_data);
		} else {
			flash()->withError("Data Not Found.")->to("manufacturing/process/manage/" . $garnu_id);
		}
	}

	public function getRowMaterials()
	{
		$this->db->query(' SET SESSION sql_mode = "" ');
		$postData = $this->security->xss_clean($this->input->post());

		$draw            = $postData['draw'];
		$rowperpage      = (int) $postData['length']; // Rows display per page, cast to int for safety
		$start           = (int) $postData['start'];
		$columnIndex     = $postData['order'][0]['column']; // Column index
		$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue     = '%' . $this->db->escape_like_str($postData['search']['value']) . '%';
		$garnu_id        = $postData['garnu_id'];
		$searchQuery     = "";
		$searchQuery2    = "";
		if ($searchValue != '') {
			$searchQuery  = " (row_material.name like '%" . $searchValue . "%') or (given_row_material.touch like '%" . $searchValue . "%') or (given_row_material.weight like '%" . $searchValue . "%') or (given_row_material.quantity like '%" . $searchValue . "%')";
			$searchQuery2 = " (row_material.name like '%" . $searchValue . "%') or (receive_row_material.touch like '%" . $searchValue . "%') or (receive_row_material.weight like '%" . $searchValue . "%') or (receive_row_material.quantity like '%" . $searchValue . "%')";
		}
		$totalRecords          = $this->getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id);
		$totalRecordwithFilter = $this->getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id);

		$sql      = "(SELECT 'Given' AS type, given_row_material.id, row_material.name as row_material, 
				given_row_material.touch, given_row_material.weight, given_row_material.quantity, 
				given_row_material.garnu_id,process.name as Process_name
				FROM given_row_material
				JOIN row_material ON given_row_material.row_material_id = row_material.id
				JOIN given ON given_row_material.given_id = given.id
				JOIN process ON given.process_id = process.id
				WHERE given_row_material.garnu_id = ? AND 
					(row_material.name LIKE ? OR 
						given_row_material.touch LIKE ? OR 
						given_row_material.weight LIKE ? OR 
						given_row_material.quantity LIKE ?))
				UNION ALL
				(SELECT 'Received' AS type, receive_row_material.id, row_material.name as row_material, 
						receive_row_material.touch, receive_row_material.weight, 
						receive_row_material.quantity, receive_row_material.garnu_id,process.name as Process_name
				FROM receive_row_material
				JOIN row_material ON receive_row_material.row_material_id = row_material.id
				JOIN receive ON receive_row_material.received_id = receive.id
				JOIN given ON receive.given_id = given.id
				JOIN process ON given.process_id = process.id
				WHERE receive_row_material.garnu_id = ? AND 
					(row_material.name LIKE ? OR 
						receive_row_material.touch LIKE ? OR 
						receive_row_material.weight LIKE ? OR 
						receive_row_material.quantity LIKE ?))
				ORDER BY id ASC
				LIMIT ? OFFSET ?";
		$bindings = array(
			$garnu_id,
			$searchValue,
			$searchValue,
			$searchValue,
			$searchValue,
			$garnu_id,
			$searchValue,
			$searchValue,
			$searchValue,
			$searchValue,
			$rowperpage,
			$start
		);
		$query    = $this->db->query($sql, $bindings);
		$records  = $query->result_array();

		$data = array();
		$i    = $start + 1;
		foreach ($records as $record) {

			$data[] = array(
				"id"           => $i,
				"row_material" => $record['row_material'],
				"process_name" => $record['Process_name'],
				"type"         => $record['type'],
				"touch"        => $record['touch'],
				"weight"       => $record['weight'],
				"quantity"     => $record['quantity'],
			);
			$i      = $i + 1;
		}

		$response = array(
			"draw"                 => intval($draw),
			"iTotalRecords"        => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData"               => $data
		);
		echo json_encode($response);
		exit();
	}

	public function getReceiveData()
	{
		$this->db->query(' SET SESSION sql_mode = "" ');
		$postData = $this->security->xss_clean($this->input->post());

		$draw            = $postData['draw'];
		$rowperpage      = (int) $postData['length']; // Rows display per page, cast to int for safety
		$start           = (int) $postData['start'];
		$columnIndex     = $postData['order'][0]['column']; // Column index
		$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue     = '%' . $this->db->escape_like_str($postData['search']['value']) . '%';
		$garnu_id        = $postData['garnu_id'];
		$searchQuery     = "";
		if ($searchValue != '') {
			$searchQuery = " (item.name like '%" . $searchValue . "%') or (receive.touch like '%" . $searchValue . "%') or (receive.weight like '%" . $searchValue . "%') or (receive.pcs like '%" . $searchValue . "%')";
		}
		$totalRecords          = $this->getReceiveTotalRecordsWithFilter($searchQuery, $garnu_id);
		$totalRecordwithFilter = $this->getReceiveTotalRecordsWithFilter($searchQuery, $garnu_id);

		$sql      = "
				(SELECT receive.id, 
						receive.garnu_id,process.name as Process_name,receive.item_id,given.process_id,given.worker_id,
						receive.pcs,receive.total_weight,receive.touch,receive.row_material_weight,item.name AS item_name,receive.weight,
						process.finished_good
				FROM receive
				JOIN given ON receive.given_id = given.id
				JOIN process ON given.process_id = process.id
				LEFT JOIN item ON receive.item_id = item.id
				WHERE receive.garnu_id = ? AND receive.lot_creation = ? AND receive.isGiven = ? AND 
					(item.name LIKE ? OR 
						receive.touch LIKE ? OR 
						receive.weight LIKE ? OR 
						receive.pcs LIKE ?))
				ORDER BY id ASC
				LIMIT ? OFFSET ?";
		$bindings = array(
			$garnu_id,
			'NO',
			'NO',
			$searchValue,
			$searchValue,
			$searchValue,
			$searchValue,
			$rowperpage,
			$start
		);
		$query    = $this->db->query($sql, $bindings);
		$records  = $query->result_array();

		$data = array();
		$i    = $start + 1;
		foreach ($records as $record) {
			$checked = (isset($row['isGiven']) && $row['isGiven'] == "YES") ? 'checked' : '';
			$index = '
			<div class="d-flex gap-2">
			<label class="form-check">
			<input class="form-check-input isReceive" type="checkbox" data-finished_good="' . $record['finished_good'] . '" data-receiveId="' . $record['id'] . '" data-worker="' . $record['worker_id'] . '" data-process_id="' . $record['process_id'] . '"  data-item_id="' . $record['item_id'] . '"  data-touch="' . $record['touch'] . '"  data-weight="' . $record['weight'] . '" data-rmWeight="' . $record['row_material_weight'] . '"  data-total_weight="' . $record['total_weight'] . '"  data-pcs="' . $record['pcs'] . '" value="YES" ' . $checked . ' >
			</label>
			' . $i . '
			</div>';

			$data[] = array(
				"id"           => $index,
				"item" => $record['item_name'],
				"process_name" => $record['Process_name'],
				"touch"        => $record['touch'],
				"weight"       => $record['weight'],
				"row_material_weight"       => $record['row_material_weight'],
				"total_weight"       => $record['total_weight'],
				"quantity"     => $record['pcs'],
			);
			$i      = $i + 1;
		}

		$response = array(
			"draw"                 => intval($draw),
			"iTotalRecords"        => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData"               => $data
		);
		echo json_encode($response);
		exit();
	}

	function getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id)
	{
		$sql         = "SELECT SUM(total) AS total_records FROM (
					(SELECT COUNT(*) AS total
					 FROM given_row_material
					 JOIN row_material ON given_row_material.row_material_id = row_material.id
					 WHERE given_row_material.garnu_id = ? AND ($searchQuery))
					UNION ALL
					(SELECT COUNT(*) AS total
					 FROM receive_row_material
					 JOIN row_material ON receive_row_material.row_material_id = row_material.id
					 WHERE receive_row_material.garnu_id = ? AND ($searchQuery2))
				) AS counts";

		$query  = $this->db->query($sql, array($garnu_id, $garnu_id));
		$result = $query->row_array();
		return isset($result['total_records']) ? (int) $result['total_records'] : 0;
	}

	function getReceiveTotalRecordsWithFilter($searchQuery, $garnu_id)
	{
		$sql         = "SELECT SUM(total) AS total_records FROM (
					(SELECT COUNT(*) AS total
					 FROM receive
					 JOIN item ON receive.item_id = item.id
					 WHERE receive.garnu_id = ? AND receive.lot_creation = ? AND receive.isGiven = ? AND ($searchQuery))
				) AS counts";

		$query  = $this->db->query($sql, array($garnu_id, 'NO', 'NO'));
		$result = $query->row_array();
		return isset($result['total_records']) ? (int) $result['total_records'] : 0;
	}

	public function updateToAfterAll()
	{
		try {
			$this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
			$this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
			$this->form_validation->set_rules('process_id', 'Process Id', 'trim|required|numeric');
			$this->form_validation->set_rules('AverageTouch', 'Average Touch', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'error' => validation_errors()];
				echo json_encode($response);
				return;
			} else {
				$postData  = $this->input->post();
				$garnu_id  = $postData['garnu_id'];
				$given_id  = $postData['given_id'];
				$process_id  = $postData['process_id'];
				$AverageTouch  = $postData['AverageTouch'];
				$update = false;


				$this->db->trans_start();

				$givenData = $this->db->select('id, garnu_id, given_touch, process_id,total_weight')->from('given')->where(array('garnu_id' => $garnu_id, 'process_id >=' => $process_id))->order_by('id', 'ASC')->get()->result_array();
				foreach ($givenData as $row) {
					// $givenFine = $AverageTouch / $row['total_weight'];
					if (empty($row['given_touch']) || round($row['given_touch'])==0) {
						$this->db->where('id', $row['id'])->update('given', array('given_touch' => $AverageTouch));
					}

					$receive = $this->db->select('id,touch,total_weight')->from('receive')->where(array('given_id' => $row['id'], 'garnu_id' => $garnu_id))->get()->result_array();
					foreach ($receive as $receiveRow) {
						$receiveFine = ($AverageTouch * $row['total_weight']) / 100;
						if (empty($receiveRow['touch']) || round($receiveRow['touch'])==0) {
							$this->db->where('id', $receiveRow['id'])->update('receive', array('touch' => $AverageTouch, 'fine' => $receiveFine));
						}
					}
				}

				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response = ['success' => false, 'message' => 'Avrage Touch Update Failed.'];
				} else {
					$response = ['success' => true, 'message' => 'Avrage Touch Update Successfully'];
				}

				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false,
				'error'   => $e->getMessage(),
				'data'    => []
			];
			echo json_encode($response);
		}
	}

	private function syncGivenMainLot(array $newData, array $oldData = [])
	{
		$newLotId = isset($newData['closing_touch']) ? (int) $newData['closing_touch'] : 0;
		$newRowMaterialId = isset($newData['material_type_id']) ? (int) $newData['material_type_id'] : 0;

		$oldLotId = isset($oldData['closing_touch']) ? (int) $oldData['closing_touch'] : 0;
		$oldRowMaterialId = isset($oldData['material_type_id']) ? (int) $oldData['material_type_id'] : 0;

		$newWeight = isset($newData['given_weight']) ? (float) $newData['given_weight'] : 0;
		$newQuantity = isset($newData['given_qty']) ? (float) $newData['given_qty'] : 0;

		$oldWeight = isset($oldData['given_weight']) ? (float) $oldData['given_weight'] : 0;
		$oldQuantity = isset($oldData['given_qty']) ? (float) $oldData['given_qty'] : 0;

		if ($newLotId <= 0 && $oldLotId <= 0) {
			return true;
		}

		$lotId = $newLotId > 0 ? $newLotId : $oldLotId;
		$rowMaterialId = $newRowMaterialId > 0 ? $newRowMaterialId : $oldRowMaterialId;

		if ($lotId <= 0 || $rowMaterialId <= 0) {
			return false;
		}

		if ($newLotId <= 0) {
			$newWeight = 0;
			$newQuantity = 0;
		}

		return lot_management([
			'id' => $lotId,
			'row_material_id' => $rowMaterialId,
			'old_row_material_id' => $oldRowMaterialId,
			'weight' => $newWeight,
			'quantity' => $newQuantity,
			'old_weight' => $oldWeight,
			'old_quantity' => $oldQuantity,
			'old_lot_wise_rm_id' => $oldLotId,
			'movement' => 'given',
			'update_lot_values' => false,
			'is_new_detail' => empty($oldData),
			'strict_row_material_match' => true,
		]);
	}
}
