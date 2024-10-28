<?php

class Sequence extends CI_Controller
{

	public $models;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Sequence_model', 'seq');
		check_login();
		$this->models = array(
			'sale' => 'Sale',
			'sale_return' => 'Sale Return',
			'purchase' => 'Purchase',
			'purchase_return' => 'Purchase Return',
			'jama' => 'Payment',
			'ready_for_sale' => 'Ready For Sale',
		);
	}

	public function index()
	{
		checkPrivilege(privilege['sequence_view']);
		$page_data['page_name'] = 'admin/setting/sequence';
		$page_data['page_title'] = 'Sequence';
		$page_data['data'] = $this->seq->sequence();
		$page_data['models'] = $this->models;
		$this->load->view('common', $page_data);
	}

	public function create()
	{
		checkPrivilege(privilege['sequence_add']);
		$this->form_validation->set_rules('number', 'Start With', 'required');
		$this->form_validation->set_rules('model', 'Model', 'required');
		if ($this->form_validation->run() == false) {
		    return flash()->withError(validation_errors())->to("setting/sequence");
		} else {
			$data = $this->security->xss_clean($this->input->post());
			$data['increment'] = $data['number'];
			$data['admin_id'] = session('admin_id');
			$padding = '';
			$number_length = strlen($data['number'] . '');
			if ($number_length >= 1 && $data['padding'] >= $number_length) {
				for ($i = 0; $i < $data['padding'] - $number_length; $i++) {
					$padding .= '0';
				}
			}
			$data['sequence']  = $data['prefix'] . $padding . $data['number'] . ($data['suffix'] ?? "");
			if ($this->db->insert('sequence', $data)) {
				return flash()->withSuccess("Sequence Added Successfully.")->to("setting/sequence");
			} else {
				return flash()->withError("Failed to Add Sequence.")->to("setting/sequence");
			}
		}
	}

	public function sequence($param = '', $param1 = '', $param2 = '')
	{
		$param = $this->security->xss_clean($param);
		$param1 = $this->security->xss_clean($param1);
		$param2 = $this->security->xss_clean($param2);
		if ($param == 'edit') {
			checkPrivilege(privilege['sequence_edit']);
			$page_data['row_data'] = $this->seq->sequence($param1);
			$page_data['page_name'] = 'admin/setting/sequence';
			$page_data['page_title'] = 'Sequence';
			$page_data['data'] = $this->seq->sequence();
			$page_data['models'] = $this->models;
			$this->load->view('common', $page_data);
		}
		if ($param == 'update') {
			checkPrivilege(privilege['sequence_edit']);
			$this->form_validation->set_rules('model', 'Model', 'required');
			if ($this->form_validation->run() == false) {
				return flash()->withError(validation_errors())->to("setting/sequence");
			} else {
				$row = $this->db->get_where('sequence', array('id' => $param1))->row_array();
				$data = $this->security->xss_clean($this->input->post());
				if ($data['model'] == 'filing_remark') {
					$data['increment'] = $data['number'];
					$row['increment'] = $data['number'];
				}
				$padding = '';
				$number_length = strlen($row['increment'] . '');
				if ($number_length >= 1 && $data['padding'] >= $number_length) {
					for ($i = 0; $i < $data['padding'] - $number_length; $i++) {
						$padding .= '0';
					}
				}
				$data['sequence']  = $data['prefix'] . $padding . $row['increment'] . $data['suffix'];
				$this->db->where('id', $param1);
				$update = $this->db->update('sequence', $data);
				if ($update) {
				    return flash()->withSuccess("Sequence Update Successfully.")->to("setting/sequence");
				} else {
				    return flash()->withError("Failed to Update Sequence.")->to("setting/sequence");
				}
			}
		}
		if ($param == 'delete') {
			checkPrivilege(privilege['sequence_delete']);
			$this->db->where('id', $param1);
			$delete = $this->db->delete('sequence');
			if ($delete) {
			    return flash()->withSuccess("Sequence Delete Successfully.")->to("setting/sequence");
			} else {
			    return flash()->withError("Failed to Delete Sequence.")->to("setting/sequence");
			}
		}
	}

	// function test()
	// {
	// 	echo $this->seq->getNextSequence('filing_remark');
	// }
}
