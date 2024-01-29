<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('system/core/Model.php');

class Dbh extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function getResultArray($table)
	{
		return $this->db->get($table)->result_array();
	}

	public function findAll($table, $type = 'object')
	{
		return $this->db->get($table)->result($type);
	}

	public function getResultArrayDesc($table)
	{
		$this->db->order_by($table . '.id', 'DESC');
		return $this->db->get($table)->result_array();
	}

	public function getRowArray($table, $id)
	{
		return $this->db->get_where($table, ['id' => $id])->row_array();
	}

	public function getWhereRowArray($table, $arr)
	{
		return $this->db->get_where($table, $arr)->row_array();
	}

	public function getWhereResultArray($table, $arr)
	{
		return $this->db->get_where($table, $arr)->result_array();
	}

	public function passwordVerify($table, $arr)
	{
		$data = $this->db->get_where($table, $arr);
		if ($data->num_rows() > 0) {
			return $data->row_array();
		} else {
			return false;
		}
	}

	public function deleteRow($table, $id)
	{
		if (is_array($id)) {
			$this->db->where_in('id', $id);
		} else {
			$this->db->where('is', $id);
		}
		if ($this->db->delete($table)) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteAllWhere($table, array $arr)
	{
		$this->db->where($arr);
		$delete = $this->db->delete($table);
		if (!$delete) {
			return false;
		}
		return true;
	}

	public function getLastRow($table)
	{
		return $this->db->order_by('id', "DESC")->get($table)->row_array();
	}

	public function findOrFail($table, $array, $type = "result")
	{
		$query = $this->db->get_where($table, $array);
		if ($query->num_rows() > 0) :
			switch ($type) {
				case 'row':
					return $query->row_array();
				case 'result':
					return $query->result_array();
			}
		endif;
		return false;
	}

	public function updateWhere($table, $where, $data)
	{
		return $this->db->where($where)->update($table, $data);
	}

	public function updateRow($table, $id, $data)
	{
		return $this->db->where('id', $id)->update($table, $data);
	}

	public function find($table, $id)
	{
		$query = $this->db->get_where($table, ['id' => $id]);
		return $query->num_rows() > 0 ? $query->row_array() : false;
	}

	public function getLastRowWhere($table, $where, $notWhere = [])
	{
		foreach ($where as $key => $value) {
			$this->db->where($key, $value);
		}
		foreach ($notWhere as $v) {
			$this->db->where($v);
		}
		$this->db->order_by('id', "DESC");
		return $this->db->get($table)->row_array();
	}

	public function isDataExists($table, $arr)
	{
		if ($this->db->get_where($table, $arr)->num_rows() > 0) {
			return true;
		}
		return false;
	}
}
