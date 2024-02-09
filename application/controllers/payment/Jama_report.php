<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jama_report extends CI_Controller
{
    const View = "admin/payment/jama_report";

	function __construct()
	{
		parent::__construct();
		check_login();
	}

	public function index()
	{
		// checkPrivilege(privilege['jama_view']);
		// $page_data['page_title'] = 'Jama Report';
		// $page_data['page_name'] = 'admin/jama/jama_report.php';
		// $this->load->view('admin/common.php', $page_data);
        $page_data['page_title'] = 'Jama Report';
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
		$admin_id = $this->session->userdata('admin_id');
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (customer.name like '%" . $searchValue . "%') or (jama.type like '%" . $searchValue . "%') or (jama.date like '%" . $searchValue . "%') or (jama.mode like '%" . $searchValue . "%')  or (jama.remark like '%" . $searchValue . "%')";
		}


		$this->db->select('jama.*');
		$this->db->from('jama');
		$this->db->group_by('jama.id');
		$this->db->order_by('jama.id', 'desc');
		$records = $this->db->get();
		$totalRecords = $records->num_rows();

		## Total number of record with filtering
		$this->db->select('jama.*,customer.name as pname');
		$this->db->from('jama');
		$this->db->join('customer', 'customer.id = jama.customer_id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		if (!empty($from)) {
			$this->db->where('jama.date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('jama.date <=', $to);
		}

		if (!empty($group_by)) {
			$this->db->where("jama.type", $group_by);
		}

		$this->db->group_by('jama.id');
		$this->db->order_by('jama.id', 'desc');
		$records = $this->db->get();

		$totalRecordwithFilter = $records->num_rows();


		$this->db->select('jama.*,customer.name as pname');
		$this->db->from('jama');
		$this->db->join('customer', 'customer.id = jama.customer_id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		if (!empty($from)) {
			$this->db->where('jama.date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('jama.date <=', $to);
		}

		if (!empty($group_by)) {
			$this->db->where("jama.type", $group_by);
		}

		$this->db->group_by('jama.jama_code');
		$this->db->order_by('jama.id', 'desc');

		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result();
		$data = array();
		$partyNames = array();
		$i = $start + 1;
		foreach ($records as $record) {

			$this->db->select("jama.*,customer.name as pname");
			$this->db->from("jama");
			$this->db->where('jama.jama_code', $record->jama_code);
            $this->db->join('customer', 'customer.id = jama.customer_id', 'left');


			if (!isset($partyNames[$record->jama_code])) {
				$partyNames[$record->jama_code] = $record->pname;
			}

			$query = $this->db->get();
			$mk = $query->result_array();
			$party = "";
			$date = "";
			$type = "";
			$purity = "";
			$mode = "";
			$wb = "";
			$rate = "";
			$remark = "";
			$gross = "";
			$fine = "";
			$amount = "";

			foreach ($mk as $rm) {

				$date .= "<span>" . $rm['date'] . "</span></br>";
				$party .= "<span>" . $rm['pname'] . "</span></br>";
				$type .= "<span>" . $rm['type'] . "</span></br>";
				$purity .= "<span>" . $rm['purity'] . "</span></br>";
				$mode .= "<span>" . $rm['mode'] . "</span></br>";
				$wb .= "<span>" . $rm['wb'] . "</span></br>";
				$rate .= "<span>" . $rm['rate'] . "</span></br>";
				$remark .= "<span>" . $rm['remark'] . "</span></br>";
				$gross .= "<span>" . $rm['gross'] . "</span></br>";
				$fine .= "<span>" . $rm['fine'] . "</span></br>";
				$amount .= "<span>" . $rm['amount'] . "</span></br>";
			}

			$url = base_url("payment/jama/edit/") . $record->jama_code . '/' . $record->customer_id;
			$url_delete = base_url("payment/jama/delete_jama_code/") . $record->jama_code;
			$edit = '<a class="btn btn-action bg-warning text-white me-2" href="' . $url . '"><i class="far fa-edit"></i></a> &nbsp;&nbsp;
            <a class="btn btn-action bg-danger text-white me-2" href="' . $url_delete . '"><i class="fa-solid fa-trash"></i></a>';
			$data[] = array(
				"sno" => $i,
				"party" => $partyNames[$record->jama_code],
				"action" => $edit,
				"date" => $date,
				"type" => $type,
				"purity" => $purity,
				"mode" => $mode,
				"wb" => $wb,
				"rate" => $rate,
				"remark" => $remark,
				"gross" => $gross,
				"fine" => $fine,
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
