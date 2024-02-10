<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baki_model extends CI_model
{

	public function getData($department_id)
	{
		$this->db->select('baki.*,bank.name as bank_name,customer.name as party_name');
		$this->db->from('baki');
		$this->db->join('bank', 'bank.id = baki.bank_id', 'left');
		$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
		$this->db->where('baki.department_id', $department_id);
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
		$query = $this->db->insert('baki', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function rowdata($id)
	{
		$this->db->select('baki.*');
		$this->db->where('id', $id);
		$query = $this->db->get('baki');
		if ($query) {
			return $query->row_array();
		}
	}

	public function Update($data, $id)
	{
		$this->db->where('id', $id);
		$query = $this->db->update('baki', $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}
