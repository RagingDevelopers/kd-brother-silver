<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baki_report extends CI_Controller
{
    const View = "admin/payment/baki_report";

	function __construct()
	{
		parent::__construct();
		check_login();
	}

	public function index()
	{
		checkPrivilege(privilege['baki_view']);
		// $page_data['page_title'] = 'Baki Report';
		// $page_data['page_name'] = 'baki/baki_report.php';
		// $this->load->view('admin/common.php', $page_data);
        $page_data['page_title'] = 'Baki Report';
        return view(self::View,$page_data);
	}

	public function report()
	{
        $this->db->query(' SET SESSION sql_mode = "" ');
		$postData = $this->security->xss_clean($this->input->post());

		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value 
		$from = $postData['from'];
		$to = $postData['to'];
		$group_by = $postData['group_by'];
		$admin_id = $this->session->userdata('admin_ID');
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (customer.name like '%" . $searchValue . "%') or (baki.type like '%" . $searchValue . "%') or (baki.date like '%" . $searchValue . "%') or (baki.mode like '%" . $searchValue . "%')  or (baki.remark like '%" . $searchValue . "%')";
		}


		$this->db->select('baki.*');
		$this->db->from('baki');
// 		$this->db->group_by('baki.id');
		$this->db->group_by('baki.baki_code');
		$this->db->order_by('baki.id', 'desc');
		$records = $this->db->get();
		$totalRecords = $records->num_rows();

		## Total number of record with filtering
		$this->db->select('baki.*,customer.name as pname');
		$this->db->from('baki');
		$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		if (!empty($from)) {
			$this->db->where('baki.date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('baki.date <=', $to);
		}

		if (!empty($group_by)) {
			$this->db->where("baki.type", $group_by);
		}

// 		$this->db->group_by('baki.id');
		$this->db->group_by('baki.baki_code');
		$this->db->order_by('baki.id', 'desc');
		$records = $this->db->get();

		$totalRecordwithFilter = $records->num_rows();


		$this->db->select('baki.*,customer.name as pname,metal_type.name as metal_type');
		$this->db->from('baki');
		$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
		$this->db->join('metal_type', 'metal_type.id = baki.metal_type_id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		if (!empty($from)) {
			$this->db->where('baki.date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('baki.date <=', $to);
		}

		if (!empty($group_by)) {
			$this->db->where("baki.type", $group_by);
		}

		$this->db->group_by('baki.baki_code');
		$this->db->order_by('baki.id', 'desc');

		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result();
		$data = array();
		$uniquePartyNames = array();
		$i = $start + 1;
		foreach ($records as $record) {

			$this->db->select("baki.*,customer.name as pname,metal_type.name as metal_type");
			$this->db->from("baki");
			$this->db->where('baki.baki_code', $record->baki_code);
			if (!empty($group_by)) {
    			$this->db->where("baki.type", $group_by);
    		}
			$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
			$this->db->join('metal_type', 'metal_type.id = baki.metal_type_id', 'left');

			$query = $this->db->get();
			$mk = $query->result_array();
			$date = "";
			$party = "";
			$type = "";
			$purity = "";
			$mode = "";
			$wb = "";
			$rate = "";
			$remark = "";
			$gross = "";
			$fine = "";
			$metal_type = "";
			$amount = "";

			foreach ($mk as $rm) {

				if (!isset($uniquePartyNames[$record->baki_code])) {
					$party = "<span>" . $record->pname . "</span><br>";
					$uniquePartyNames[$record->baki_code] = $record->pname;
				} else {
					$party = "<span>" . $uniquePartyNames[$record->baki_code] . "</span><br>";
				}
				
				// $party .= "<span>" . $rm['pname'] . "</span></br>";
				$date .= "<span>" . $rm['date'] . "</span></br>";
				$type .= "<span>" . $rm['type'] . "</span></br>";
				$purity .= "<span>" . $rm['purity'] . "</span></br>";
				$mode .= "<span>" . $rm['mode'] . "</span></br>";
				$wb .= "<span>" . $rm['wb'] . "</span></br>";
				$rate .= "<span>" . $rm['rate'] . "</span></br>";
				$remark .= "<span>" . $rm['remark'] . "</span></br>";
				$gross .= "<span>" . $rm['gross'] . "</span></br>";
				$fine .= "<span>" . $rm['fine'] . "</span></br>";
				$metal_type .= "<span>" . $rm['metal_type'] . "</span></br>";
				$amount .= "<span>" . $rm['amount'] . "</span></br>";
			}

			$url = base_url("payment/baki/edit/") . $record->baki_code . '/' . $record->customer_id;
			$url_delete = base_url("payment/baki/delete_baki_code/") . $record->baki_code;
			$edit = '<a class="btn btn-action bg-warning text-white me-2" href="' . $url . '"><i class="far fa-edit"></i></a> &nbsp;&nbsp;
            <a class="btn btn-action bg-danger text-white me-2" href="' . $url_delete . '"><i class="fa-solid fa-trash"></i></a>';
			$data[] = array(
				"sno" => $i,
				"action" => $edit,
				"party" => $party,
				"date" => $record->date,
				"type" => $type,
				"purity" => $purity,
				"mode" => $mode,
				"wb" => $wb,
				"rate" => $rate,
				"remark" => $remark,
				"gross" => $gross,
				"fine" => $fine,
				"metal_type" => $metal_type,
				"amount" => $amount,

			);
			$i = $i + 1;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
		exit();
	}
}
