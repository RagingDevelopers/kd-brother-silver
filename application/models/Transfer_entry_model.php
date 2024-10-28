<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transfer_entry_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function datatable_getList($postData)
	{
		$res = [];
		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value
		$from = $postData['from'] ?? null;
        $to = $postData['to'] ?? null;
        $customer_id = $postData['customer_id'] ?? null;
		$payment_type = $postData['payment_type'] ?? null;

		## Search 
		$searchQuery = "";
		if ($searchValue != '') {
			$searchValue = $this->db->escape('%' . $searchValue . '%');
			$searchQuery = "( TE.payment_type LIKE " . $searchValue . " OR
			C.name LIKE " . $searchValue . " OR  
			TC.name LIKE " . $searchValue . " OR  
			TE.narration LIKE " . $searchValue . " OR  
			TE.total_amount LIKE " . $searchValue . " OR  
			TE.created_at LIKE " . $searchValue . " OR  
			TE.gold LIKE " . $searchValue . " OR  
			TE.created_by LIKE " . $searchValue . " OR  
			TE.date LIKE " . $searchValue . ") ";
		} else {
			$searchQuery = ' TRUE ';
		}

		$where = "";
        if ($from != null) {
			$where .= "TE.created_at >=  '$from'  AND  ";
        }
        if ($to != null) {
			$where .= "TE.created_at <= '$to' AND";
        }
        if ($customer_id != null) {
			$where .= " customer_id = '$customer_id'  AND  ";
        }
		if ($payment_type != null) {
			$where .= "payment_type = '$payment_type'  AND  ";
		}

		## Total number of records without filtering
		$totalRecords = $this->db->query("SELECT COUNT(id) AS `count` FROM transfer_entry TE")->row()->count;

		## Total number of record with filtering
		$totalRecordwithFilter = $this->db->query("SELECT COUNT(TE.id) AS `count`
													FROM transfer_entry TE
													LEFT JOIN customer C ON C.id = TE.customer_id
													LEFT JOIN customer TC ON TC.id = TE.transfer_customer_id
													WHERE 1 = 1 AND " . $where . $searchQuery)->row()->count;
		$query = "SELECT TE.id,
				TE.payment_type,
				C.name AS customer,
				TC.name AS transfer_customer,
				TE.narration,
				TE.total_amount,
				TE.date,
				TE.created_by,
				TE.gold,
				TE.created_at
				FROM transfer_entry TE
				LEFT JOIN customer C ON C.id = TE.customer_id
				LEFT JOIN customer TC ON TC.id = TE.transfer_customer_id
				WHERE 1 = 1  AND ". $where . $searchQuery;
		if ($columnIndex == 0) {
			$query .= " ORDER BY TE.id DESC ";
		} else {
			if ($columnName == 'payment_type') {
				$query .= " ORDER BY TE.payment_type $columnSortOrder ";
			} else if ($columnName == 'narration') {
				$query .= " ORDER BY TE.narration $columnSortOrder ";
			} else if ($columnName == 'total_amount') {
				$query .= " ORDER BY TE.total_amount $columnSortOrder ";
			} else if ($columnName == 'customer') {
				$query .= " ORDER BY C.customer $columnSortOrder ";
			} else if ($columnName == 'date') {
				$query .= " ORDER BY TE.date $columnSortOrder ";
			} else if ($columnName == 'transfer_customer') {
				$query .= " ORDER BY TC.transfer_customer $columnSortOrder ";
			} else if ($columnName == 'created_by') {
				$query .= " ORDER BY TE.created_by $columnSortOrder ";
			} else if ($columnName == 'created_at') {
				$query .= " ORDER BY TE.created_at $columnSortOrder ";
			} else if ($columnName == 'gold') {
				$query .= " ORDER BY TE.gold $columnSortOrder ";
			}
		}
		$query .= " LIMIT $start,$rowperpage ";
		$records = $this->db->query($query)->result_array();

		// pre($this->db->last_query());
		// pre($records);

		$data = [];
		$i = $start + 1;
		foreach ($records as $v) {
// 			if (!isRestricted(privilege['transfer_entry_edit'])) {
				$link = '<a href="' . site_url('payment/transfer_entry/edit/' . $v['id']) . '"><i class="fa fa-pencil-alt text-blue"></i></a>&nbsp;&nbsp;';
// 			}
// 			if (!isRestricted(privilege['transfer_entry_delete'])) {
				$link .= '<a onclick="return confirm(\'are you sure ?\')" href="' . site_url('payment/transfer_entry/delete/' . $v['id']) . '"><i class="fa fa-trash text-red"></i></a>&nbsp;&nbsp;';
// 			}
			$data[] = [
				'srno' => $i++,
				'id' => $link,
				'customer' => $v['customer'],
				'transfer_customer' => $v['transfer_customer'],
				'payment_type' => $v['payment_type'] == 'credit' ? 'Credit' : 'Debit',
				'date' => date('d-m-Y', strtotime($v['date'])),
				'gold' => $v['gold'],
				'total_amount' => $v['total_amount'],
				'narration' => $v['narration'],
				'created_by' => $v['created_by'],
				'created_at' => date('d-m-Y', strtotime($v['created_at']))
			];
		}

		## Response
		$res = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);

		// pre($res);

		return $res;
	}
}
