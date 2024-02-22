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
	}

	public function manage($id = null, $pid = null)
	{
		$page_data['id'] = $id;
		$page_data['data'] = $this->db->select('*')->from('garnu')->where('id', $id)->get()->row_array();
		$page_data['process_data'] = $this->db->select('*')->from('given')->where('id', $pid)->get()->row_array();
		$page_data['given_row_material'] = $this->db->select('*')->from('given_row_material')->where(array('given_id' => $pid, 'garnu_id' => $id))->get()->result_array();
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['metal_type'] = $this->db->select('id,name')->from('metal_type')->get()->result_array();
		$page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where('garnu_id', $id)->order_by('code', 'DESC')->get()->result_array();
		// echo "<pre>"; print_r($page_data);;exit;
		$page_data['table'] = $this->db->select('given.*,customer.name AS customer_name, process.name AS process_name')->from('given')->where('garnu_id', $id)->join('process', 'given.process_id = process.id', 'left')->join('customer', 'given.worker_id = customer.id', 'left')->get()->result();
		$page_data['page_title'] = 'Process';
		$page_data['process'] = $this->modal->fetch_process();
		return view(self::View, $page_data);
	}

	public function getWorkers()
	{
		$post = $this->input->post();
		$data = $this->db->select('id,name')->from('customer')->where(array('process_id' => $post['process_id'], 'account_type_id' => 7))->get()->result_array();
		echo json_encode($data);
	}

	public function fechWeight()
	{
		$post = $this->input->post();
		$data = $this->db->select('total_weight')->get_where('receive', array('code' => $post['code']))->row_array();
		echo json_encode($data);
	}

	public function add()
	{
		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			// ->set_rules('rc_qty', 'Received Quantity', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'trim')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('labour', 'Labour', 'required')
			->set_error_delimiters('<div class="text-danger">', '</div>');

		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$post = xss_clean($this->input->post());

		$data = array();
		$data['garnu_id'] = $post['garnu_id'];
		// $data['rc_qty'] = $post['rc_qty'];
		$data['process_id'] = $post['process'];
		$data['worker_id'] = $post['workers'];
		$data['remarks'] = $post['remarks'];
		$data['given_qty'] = $post['given_qty'];
		$data['given_weight'] = $post['given_weight'];
		$data['labour'] = $post['labour'];
		$data['row_material_weight'] = $post['total-rm_weight'];
		$data['total_weight'] = $post['total_weight'];
		$data['receive_code'] = isset($post['receive_code']) ? $post['receive_code'] : '';
		$data['creation_date'] = date('Y-m-d');
		$this->db->insert('given', $data);
		$given_id = $this->db->insert_id();

		$batchData = [];
		foreach ($post['rowid'] as $key => $rowid) {
			$rmData = [
				'given_id' => $given_id,
				'garnu_id' => isset($post['garnu_id']) ? $post['garnu_id'] : null,
				'row_material_id' => isset($post['row_material'][$key]) ? $post['row_material'][$key] : null,
				'touch' => isset($post['rmTouch'][$key]) ? $post['rmTouch'][$key] : null,
				'weight' => isset($post['rmWeight'][$key]) ? $post['rmWeight'][$key] : null,
				'quantity' => isset($post['rmQuantity'][$key]) ? $post['rmQuantity'][$key] : null,
				'creation_date' => date('Y-m-d'),
			];
			$batchData[] = $rmData;
		}
		if (!empty($batchData)) {
			$this->db->insert_batch('given_row_material', $batchData);
		}
		flash()->withSuccess("Garnu Added Successfully.")->back();
	}

	public function update()
	{
		$id = $this->input->post('given_id');

		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			// ->set_rules('rc_qty', 'Received Quantity', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'trim')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('total-rm_weight', 'Row Material Weight', 'trim')
			->set_rules('total_weight', 'Final Weight', 'trim')
			->set_rules('labour', 'Labour', 'required')
			->set_error_delimiters('<div class="text-danger">', '</div>');

		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}
		$post = xss_clean($this->input->post());

		$data = array();
		$data['garnu_id'] = $post['garnu_id'];
		// $data['rc_qty'] = $post['rc_qty'];
		$data['process_id'] = $post['process'];
		$data['worker_id'] = $post['workers'];
		$data['remarks'] = $post['remarks'];
		$data['given_qty'] = $post['given_qty'];
		$data['given_weight'] = $post['given_weight'];
		$data['row_material_weight'] = $post['total-rm_weight'];
		$data['total_weight'] = $post['total_weight'];
		$data['labour'] = $post['labour'];
		$data['receive_code'] = $post['receive_code'];
		$this->db->where('id', $id)->update('given', $data);

		$insertBatch = [];
		$updateBatch = [];
		$existingIds = isset($post['rowid']) ? $post['rowid'] : [];
		$allids = isset($post['ids']) ? $post['ids'] : [];

		$idsNotExisting = array_diff($allids, $existingIds);
		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('given_row_material');
		}

		foreach ($post['rowid'] as $key => $rowid) {
			$rmData = [
				'row_material_id' => isset($post['row_material'][$key]) ? $post['row_material'][$key] : null,
				'touch' => isset($post['rmTouch'][$key]) ? $post['rmTouch'][$key] : null,
				'weight' => isset($post['rmWeight'][$key]) ? $post['rmWeight'][$key] : null,
				'quantity' => isset($post['rmQuantity'][$key]) ? $post['rmQuantity'][$key] : null,
			];

			if ($rowid == 0) {
				$rmData['given_id'] = $post['given_id'];
				$rmData['creation_date'] = date('Y-m-d');
				$rmData['garnu_id'] = isset($post['garnu_id']) ? $post['garnu_id'] : null;
				$insertBatch[] = $rmData;
			} else if (in_array($rowid, $existingIds)) {
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
		flash()->withSuccess("Update Successfully.")->to("manufacturing/process/manage/" . $post['garnu_id']);
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

		$post = $this->input->post();
		$garnu_id = $post['garnu_id'];
		$given_id = $post['given_id'];

		$page_data['garnuData'] = $this->dbh->getWhereRowArray('garnu', ['id' => $garnu_id]);
		$page_data['receivedData'] = $this->dbh->getWhereResultArray('receive', ['given_id' => $given_id, 'garnu_id' => $garnu_id]);
		if(!empty($page_data['receivedData']) || !empty($page_data['garnuData'])){
			$page_data['metalData'] = $this->dbh->getWhereResultArray('process_metal_type', ['given_id' => $given_id]);
			$page_data['givenData']  = $this->db->select('given.*,process.name AS process_name')
			->from('given')
			->join('process', 'given.process_id = process.id', 'left')
			->where('given.id', $given_id)
			->get()->row_array();
			
			$page_data['customer'] = $this->dbh->getWhereResultArray('customer', ['account_type_id' => 7, 'process_id' => $page_data['givenData']['process_id']]);
			// echo "<pre>";
			// print_r($page_data);
			// exit;
			if (!empty($page_data['receivedData']) || !empty($page_data['metalData']) || !empty($page_data['garnuData']) && !empty($page_data['customer']) && !empty($page_data['givenData'])) {
				$view = $this->load->view(self::receiveGarnu, $page_data, true);
				$response = ['success' => true, 'message' => 'Data Feched Successfully', 'data' => $view];
			} else {
				$response = ['success' => false, 'message' => 'Invalid Request'];
			}
		}else{
			$response = ['success' => false, 'message' => 'Invalid Id'];
		}

		echo json_encode($response);
		return;
	}

	public function receiveGarnuAdd()
	{
		$post = $this->input->post();
		// echo "<pre>";
		// print_r($post);
		// exit;
		$existingIds = isset($post['rcid']) ? $post['rcid'] : [];
		$allids = isset($post['ids']) ? $post['ids'] : [];
		$idsNotExisting = array_diff($allids, $existingIds);
		$given_id = $post['given_id'];

		$is_completed = isset($post['is_completed']) && $post['is_completed'] == 'on' ? "YES" : "NO";
		$this->db->where('id', $given_id)->update('given', ['vadharo_dhatado' => $post['jama_baki'], 'is_completed' => $is_completed]);

		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('receive');

			$this->db->where_in('received_id', $idsNotExisting);
			$this->db->delete('receive_row_material');
		}

		if ($post['pcs'][0] != 0 || $post['total_weight'][0] != 0 || $post['weight'][0] != 0 || $post['rm_weight'][0] != 0) {
			foreach ($post['rcid'] as $key => $rcid) {

				if ($post['pcs'][$key] != 0 || $post['rcid'][$key] != 0 || $post['raw-material-data'][$key] != "" || !empty($post['pcs'][$key]) || !empty($post['weight'][$key] || !empty($post['total_weight'][$key]) || !empty($post['remark'][$key]))) {
					$receivedData = [
						'pcs' => isset($post['pcs'][$key]) ? $post['pcs'][$key] : 0,
						'weight' => isset($post['weight'][$key]) ? $post['weight'][$key] : 0,
						'row_material_weight' => isset($post['rm_weight'][$key]) ? $post['rm_weight'][$key] : 0,
						'total_weight' => isset($post['total_weight'][$key]) ? $post['total_weight'][$key] : 0,
						'remark' => isset($post['remark'][$key]) ? $post['remark'][$key] : null,
					];

					if ($rcid == 0) {
						$receivedData['given_id'] = $given_id;
						$receivedData['garnu_id'] = $post['garnu_id'];
						$receivedData['creation_date'] = date('Y-m-d');
						$this->db->insert('receive', $receivedData);
						$receive_id = $this->db->insert_id();
						$code = date('M') . "_R$receive_id" . "_G$given_id";
						$this->db->where('id', $receive_id)->update('receive', ['code' => $code]);
					} else if (in_array($rcid, $existingIds)) {
						$receive_id = $rcid;
						$this->dbh->updateRow('receive', $rcid, $receivedData);
					}

					$rawMaterialData = $post['raw-material-data'][$key];
					$updateArray['rcdid'] = [];
					$updateData = [];
					$updateArray['rm']['insert'] = [];
					$updateArray['rm']['update'] = [];
					$updateArray['rm']['delete'] = [];

					if (isset($rawMaterialData) && !empty($rawMaterialData) && $rawMaterialData !== NULL) {
						$rm_data = explode('|', $rawMaterialData);
						$rmDelete = $this->db->select('id')->where('received_id', $receive_id)->get('receive_row_material')->result();
						foreach ($rm_data as $rcD) {
							$rm = explode(',', $rcD);
							$updateArray['rcdid'][] = $rm[4];
							if (!empty($rm[0]) || !empty($rm[1]) || !empty($rm[2]) || !empty($rm[3])) {
								$updateData = [
									'received_id'      => $receive_id,
									'row_material_id'  => $rm[0],
									'touch'            => $rm[1] ?? 0,
									'weight'           => $rm[2] ?? 0,
									'quantity'         => $rm[3] ?? 0,
								];
							}
							if ($rm[4] > 0) {
								$updateData['id'] = $rm[4];
								$updateArray['rm']['update'][] = $updateData;
							} else {
								$updateData['creation_date'] = date('Y-m-d');
								$updateArray['rm']['insert'][] = $updateData;
							}
						}

						if (!empty($updateArray['rm']['insert'])) {
							$this->db->insert_batch('receive_row_material', $updateArray['rm']['insert']);
							$response = ['success' => true, 'message' => 'Data Add Successfully.'];
						} else {
							$response = ['success' => false, 'message' => 'Data Add Failed.'];
						}
						if (!empty($updateArray['rm']['update'])) {
							$this->db->update_batch('receive_row_material', $updateArray['rm']['update'], 'id');
							$response = ['success' => true, 'message' => 'Data Update Successfully.'];
						}


						if ($rmDelete) {
							array_walk($rmDelete, function ($rmD) use (&$updateArray) {
								if (!in_array($rmD->id, $updateArray['rcdid'])) {
									$updateArray['rm']['delete'][] = $rmD->id;
								}
							});
							($updateArray['rm']['delete'] && $this->db->where_in('id', $updateArray['rm']['delete'])->delete('receive_row_material'));
						}
					}
				}
			}

			$metalType = $post['metalType-data'];
			if (!empty($metalType) && $metalType !== NULL) {
				$rm_data = explode('|', $metalType);
				$rmDelete = $this->db->select('id')->where('given_id', $given_id)->get('process_metal_type')->result();
				foreach ($rm_data as $rcD) {
					$rm = explode(',', $rcD);
					$updateArray['pmtid'][] = $rm[4];
					$updateData = [
						'given_id'      => $given_id,
						'metal_type_id'  => $rm[0],
						'touch'            => $rm[1] ?? 0,
						'weight'           => $rm[2] ?? 0,
						'quantity'         => $rm[3] ?? 0,
					];
					if ($rm[4] > 0) {
						$updateData['id'] = $rm[4];
						$updateArray['mt']['update'][] = $updateData;
					} else {
						$updateData['creation_date'] = date('Y-m-d');
						$updateArray['mt']['insert'][] = $updateData;
					}
				}

				if (!empty($updateArray['mt']['insert'])) {
					$this->db->insert_batch('process_metal_type', $updateArray['mt']['insert']);
					$response = ['success' => true, 'message' => 'Data Add Successfully.'];
				} else {
					$response = ['success' => false, 'message' => 'Data Add Failed.'];
				}
				if (!empty($updateArray['mt']['update'])) {
					$this->db->update_batch('process_metal_type', $updateArray['mt']['update'], 'id');
					$response = ['success' => true, 'message' => 'Data Update Successfully.'];
				}


				if ($rmDelete) {
					array_walk($rmDelete, function ($rmD) use (&$updateArray) {
						if (!in_array($rmD->id, $updateArray['pmtid'])) {
							$updateArray['mt']['delete'][] = $rmD->id;
						}
					});
					(isset($updateArray['mt']['delete']) && $this->db->where_in('id', $updateArray['mt']['delete'])->delete('process_metal_type'));
				}
			}else{
			    $response = ['success' => true, 'message' => 'Data Add Successfully.'];
			}
		} else {
			$response = ['success' => false, 'message' => 'Please Fill Complate form.'];
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
				$postData = $this->input->post();
				$garnu_id = $postData['garnu_id'];
				$given_id = $postData['given_id'];
				$data = $this->dbh->getWhereResultArray('given_row_material', ['garnu_id' => $garnu_id, 'given_id' => $given_id]);
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
				'success' => false, 'error' => $e->getMessage(), 'data' => []
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
				$postData = $this->input->post();
				$garnu_id = $postData['garnu_id'];
				$given_id = $postData['given_id'];
				$receive = $this->dbh->getWhereResultArray('receive', ['garnu_id' => $garnu_id, 'given_id' => $given_id]);
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
				'success' => false, 'error' => $e->getMessage(), 'data' => []
			];
			echo json_encode($response);
		}
	}

	public function given_print($garnu_id = null, $given_id = null)
	{
		$garnu_id = $this->security->xss_clean($garnu_id);
		$given_id = $this->security->xss_clean($given_id);
		$page_data['page_title'] = 'Print Given Filing Data';
		$page_data['data'] = $this->modal->printGivenItemData($garnu_id, $given_id);
		// echo "<pre>";print_r($page_data);exit;
		$this->load->view("admin/manufacturing/print/print_given_data.php", $page_data);
	}
}
