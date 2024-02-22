<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jama_model extends CI_model
{

	public function getData($department_id)
	{
		$this->db->select('jama.*,bank.name as bank_name,party.name as party_name');
		$this->db->from('jama');
		$this->db->join('bank', 'bank.id = jama.bank_id', 'left');
		$this->db->join('party', 'party.id = jama.party_id', 'left');
		$this->db->where('jama.department_id', $department_id);
		$this->db->order_by('id', 'desc');
		return $this->db->get();
	}

	public function bank()
	{
		$this->db->select('*');
		$this->db->from('bank');
		return $this->db->get()->result_array();
	}

	public function party()
	{
		$this->db->select('*');
		$this->db->from('customer');
		return $this->db->get()->result_array();
	}


	public function insert($data)
	{
		$query = $this->db->insert('jama', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function rowdata($id)
	{
		$this->db->select('jama.*');
		$this->db->where('id', $id);
		$query = $this->db->get('jama');
		if ($query) {
			return $query->row_array();
		}
	}

	public function Update($data, $id)
	{
		$this->db->where('id', $id);
		$query = $this->db->update('jama', $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}
