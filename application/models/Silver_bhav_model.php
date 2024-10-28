<?php

defined('BASEPATH') or exit('direct script not allowed!');
class Silver_bhav_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}


	function silverBhavReport($data = [])
	{
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$this->db->select('GB.*,SUM(GB.fine) AS fine, SUM(GB.rate) AS rate, SUM(GB.amount) AS amount,customer.name as customer_name');
		$this->db->from('jama GB')
		->join('customer', 'GB.customer_id = customer.id', 'left')
		->where_in("type", ['ratecutfine', 'ratecutrs']);
		if (@$data['from_date'] && $data['from_date'])
			$this->db->where("DATE(GB.date) >= DATE(" . $this->db->escape(xss_clean($data['from_date'])) . ")");
		if (@$data['to_date'] && $data['to_date'])
			$this->db->where("DATE(GB.date) <= DATE(" . $this->db->escape(xss_clean($data['to_date'])) . ")");
		if (@$data['group_by'] && $data['group_by'] != 'NONE') {
			if ($data['group_by'] == 'MONTH') {
				$this->db->group_by('MONTH(GB.date),GB.customer_id,GB.type');
			} else if ($data['group_by'] == 'DAY') {
				$this->db->group_by('DAY(GB.date),GB.customer_id,GB.type');
			} else {
				$this->db->group_by('GB.id');
			}
		} else {
			$this->db->group_by('GB.id');
		}
		$this->db->order_by('DATE(GB.date)', 'ASC');
		return $this->db->get()->result_array();
	}
}
