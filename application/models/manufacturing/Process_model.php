<?php
class Process_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		library("dbh");
	}

	function fetch_process()
	{
		$this->db->order_by("name", "DESC");
		$query = $this->db->get("process");
		return $query->result();
	}

	function fetch_workers($post)
	{
		$data = $this->dbh->getWhereResultArray('customer', [
			'process_id'        => $post['process_id'] ?? null,
			'account_type_id' => 7
		]);
		$output = '<option value="">Select Workers</option>';
		foreach ($data as $row) {
			$output .= '<option value="' . $row['id'] . '" ' . ($post['worker_id'] == $row['id'] ? "selected" : "") . '>' . $row['name'] . '</option>';
		}
		return $output;
	}

	function printGivenItemData($garnu_id = null, $given_id = null)
	{
		$data['givenData'] = $this->db->select('given.*,garnu.garnu_weight,garnu.name as garnu_name,garnu.touch as garnu_touch,customer.name AS worker_name,process.name as process_name')
			->from('given')
			->join('garnu', 'given.garnu_id = garnu.id', 'left')
			->join('process', 'given.process_id = process.id', 'left')
			->join('customer', 'given.worker_id = customer.id', 'left')
			->where(['given.garnu_id' => $garnu_id, 'given.id' => $given_id])
			->get()->row_array();
		$data['givenRowMaterial'] = $this->db->select('given_row_material.*,row_material.name AS row_material_name')
			->from('given_row_material')->where(['garnu_id' => $garnu_id, 'given_id' => $given_id])
			->join('row_material', 'given_row_material.row_material_id = row_material.id', 'left')
			->get()->result_array();
		
		return $data;
	}

	function mainPrintGivenItemData($garnu_id = null, $given_id = null)
	{
		$data['givenData'] = $this->db->select('given.*,main_garnu.garnu_weight,main_garnu.name as garnu_name,main_garnu.touch as garnu_touch,customer.name AS worker_name,process.name as process_name')
			->from('given')
			->join('main_garnu', 'given.garnu_id = main_garnu.id', 'left')
			->join('process', 'given.process_id = process.id', 'left')
			->join('customer', 'given.worker_id = customer.id', 'left')
			->where(['given.garnu_id' => $garnu_id, 'given.id' => $given_id])
			->get()->row_array();
		$data['givenRowMaterial'] = $this->db->select('given_row_material.*,row_material.name AS row_material_name')
			->from('given_row_material')->where(['garnu_id' => $garnu_id, 'given_id' => $given_id])
			->join('row_material', 'given_row_material.row_material_id = row_material.id', 'left')
			->get()->result_array();
		
		return $data;
	}
}
