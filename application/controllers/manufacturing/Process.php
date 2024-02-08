<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
{
	const View = "admin/manufacturing/process";
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
		$page_data['given_row_meterial'] = $this->db->select('*')->from('given_row_meterial')->where(array('given_id' => $pid, 'garnu_received_id' => $id))->get()->result_array();
		$page_data['row_material'] = $this->db->select('id,name')->from('row_meterial')->where('status', "ACTIVE")->get()->result_array();
		$page_data['table'] = $this->db->select('given.*,customer.name AS customer_name, process.name AS process_name')->from('given')->where('garnu_id', $id)->join('process', 'given.process_id = process.id', 'left')->join('customer', 'given.worker_id = customer.id', 'left')->get()->result();
		$page_data['page_title'] = 'Process';
		$page_data['process'] = $this->modal->fetch_process();
		return view(self::View, $page_data);
	}

	public function getWorkers()
	{
		$workerdata = $this->modal->fetch_workers($this->input->post());
		echo $workerdata;
	}

	public function add()
	{
		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			->set_rules('rc_qty', 'Received Quantity', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'required')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('labour', 'Labour', 'required')
			->set_error_delimiters('<div class="text-danger">', '</div>');

		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$post = xss_clean($this->input->post());

		$data = array();
		$data['garnu_id'] = $post['garnu_received_id'];
		$data['rc_qty'] = $post['rc_qty'];
		$data['process_id'] = $post['process'];
		$data['worker_id'] = $post['workers'];
		$data['remarks'] = $post['remarks'];
		$data['given_qty'] = $post['given_qty'];
		$data['given_weight'] = $post['given_weight'];
		$data['labour'] = $post['labour'];
		$data['creation_date'] = date('Y-m-d');
		$this->db->insert('given', $data);
		$given_id = $this->db->insert_id();

		$batchData = [];
		foreach ($post['rowid'] as $key => $rowid) {
			$rmData = [
				'given_id' => $given_id,
				'garnu_received_id' => isset($post['garnu_received_id']) ? $post['garnu_received_id'] : null,
				'row_material_id' => isset($post['row_material'][$key]) ? $post['row_material'][$key] : null,
				'touch' => isset($post['rmTouch'][$key]) ? $post['rmTouch'][$key] : null,
				'weight' => isset($post['rmWeight'][$key]) ? $post['rmWeight'][$key] : null,
				'quantity' => isset($post['rmQuantity'][$key]) ? $post['rmQuantity'][$key] : null,
				'creation_date' => date('Y-m-d'),
			];
			$batchData[] = $rmData;
		}
		if (!empty($batchData)) {
			$this->db->insert_batch('given_row_meterial', $batchData);
		}
		flash()->withSuccess("Garnu Added Successfully.")->back();
	}

	public function update()
	{
		$id = $this->input->post('given_id');

		$validation = $this->form_validation;
		$validation->set_rules('name', 'Garnu Name', 'required')
			->set_rules('rc_qty', 'Received Quantity', 'required')
			->set_rules('weight', 'Garnu Weight', 'required')
			->set_rules('process', 'Process', 'required')
			->set_rules('workers', 'Workers', 'required')
			->set_rules('remarks', 'Remark', 'required')
			->set_rules('given_qty', 'Given Quantity', 'required')
			->set_rules('given_weight', 'Given Weight', 'required')
			->set_rules('labour', 'Labour', 'required')
			->set_error_delimiters('<div class="text-danger">', '</div>');

		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}
		$post = xss_clean($this->input->post());
		$data = array();
		$data['garnu_id'] = $post['garnu_received_id'];
		$data['rc_qty'] = $post['rc_qty'];
		$data['process_id'] = $post['process'];
		$data['worker_id'] = $post['workers'];
		$data['remarks'] = $post['remarks'];
		$data['given_qty'] = $post['given_qty'];
		$data['given_weight'] = $post['given_weight'];
		$data['labour'] = $post['labour'];
		$this->db->where('id', $id)->update('given', $data);

		$insertBatch = [];
		$updateBatch = [];
		$existingIds = isset($post['rowid']) ? $post['rowid'] : [];
		$allids = isset($post['ids']) ? $post['ids'] : [];

		$idsNotExisting = array_diff($allids, $existingIds);
		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('given_row_meterial');
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
				$rmData['garnu_received_id'] = isset($post['garnu_received_id']) ? $post['garnu_received_id'] : null;
				$insertBatch[] = $rmData;
			} else if (in_array($rowid, $existingIds)) {
				$rmData['id'] = $rowid;
				$updateBatch[] = $rmData;
			}
		}

		if (!empty($insertBatch)) {
			$this->db->insert_batch('given_row_meterial', $insertBatch);
		}
		if (!empty($updateBatch)) {
			$this->db->update_batch('given_row_meterial', $updateBatch,'id');
		}
		flash()->withSuccess("Update Successfully.")->to("manufacturing/garnu");
	}
}
