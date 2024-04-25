<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lot_wise_rm extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/lot_wise_rm";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		// library("Joinhelper");
	}

	public function index($action = "", $id = null)
	{
		checkPrivilege(privilege["lot_wise_rm_report"]);
		$page_data['page_title'] = 'Lot Wise Row Material Report';
		$page_data['rm'] = $this->db->select('id,name')->get('row_material')->result_array();
		return view(self::View, $page_data);
	}


	public function getlist()
	{
		$postData = $this->security->xss_clean($this->input->post());
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length'];
		// serching coding
		$columnIndex = $postData['order'][0]['column']; // Column index
		$searchValue = $postData['search']['value']; // Search value
		$todate = $postData['todate'];
		$fromdate = $postData['fromdate'];
		$isComplated = $postData['isComplated'];
		$type = $postData['type'];
		$rm = $postData['row_material'];

		# Search 
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (garnu.name like '%" . $searchValue . "%' or garnu.copper like '%" . $searchValue . "%' or garnu.garnu_weight like '%" . $searchValue . "%' or garnu.touch like'%" . $searchValue . "%' or garnu.silver like'%" . $searchValue . "%' or garnu.creation_date like'%" . $searchValue . "%'  or garnu.recieved like'%" . $searchValue . "%' or garnu.recieved like '%" . $searchValue . "%') ";
		}

		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('garnu')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('lot_wise_rm.id')
			->from('lot_wise_rm')
			->join('row_material', 'lot_wise_rm.row_material_id = row_material.id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate)) {
			$this->db->where('lot_wise_rm.creation_date >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('lot_wise_rm.creation_date <=', $todate);
		}
		if (!empty($type)) {
			$this->db->where('lot_wise_rm.type', $type);
		}
		if (!empty($rm)) {
			$this->db->where('lot_wise_rm.row_material_id', $rm);
		}
		if (!empty($isComplated)) {
			$this->db->where('lot_wise_rm.is_complated', $isComplated);
		}
		$this->db->group_by('id');
		$totalRecordwithFilter = $this->db->get()->num_rows();
		// $totalRecordwithFilter = $records->num_rows();

		## Fetch records
		$this->db->select('lot_wise_rm.*,row_material.name as rm_name')
			->from('lot_wise_rm')
			->join('row_material', 'lot_wise_rm.row_material_id = row_material.id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate)) {
			$this->db->where('lot_wise_rm.creation_date >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('lot_wise_rm.creation_date <=', $todate);
		}
		if (!empty($type)) {
			$this->db->where('lot_wise_rm.type', $type);
		}
		if (!empty($rm)) {
			$this->db->where('lot_wise_rm.row_material_id', $rm);
		}
		if (!empty($isComplated)) {
			$this->db->where('lot_wise_rm.is_complated', $isComplated);
		}
		$this->db->limit($rowperpage, $start);
		$this->db->group_by('lot_wise_rm.id');
		$this->db->order_by('lot_wise_rm.creation_date', "desc");
		$records = $this->db->get()->result_array();

		$data = array();
		$i = $start + 1;
		$FinalReceivePcs = 0;
		$TotalReceiveWeight = 0;
		$FinalReceiveRMWeight = 0;
		$FinalReceiveWeight = 0;
		$FinalGivenTotalWeight = 0;
		$FinalGivenRMWeight = 0;
		$FinalGivenWeight = 0;
		$FinalGivenPcs = 0;
				
		foreach ($records as $record) {
		    $class = ($record['is_complated'] == "YES") ? 'indigo' : 'danger';
			$received = "<span class='badge bg-$class'>".$record['is_complated']."</span>";
			
			$data[] = array(
				'id' => $i,
				'code' => '<span data-id="' . $record['id'] . '" class="text-success me-2 showUsed" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Used Row Material">' . $record['code'] . '</span>',
				'type' => $record['type'],
				'rm' => $record['rm_name'],
				'touch' => $record['touch'],
				'weight' => $record['weight'],
				'quantity' => $record['quantity'],
				'given_weight' => $record['given_weight'],
				'given_quantity' => $record['given_quantity'],
				'receive_weight' => $record['receive_weight'],
				'receive_quantity' => $record['receive_quantity'],
				'rem_weight' => $record['rem_weight'],
				'rem_quantity' => $record['rem_quantity'],
				'is_complated' => $received,
				'created_at' => date('d-m-Y g:i A', strtotime($record['created_at'])),
			);
			$i = $i + 1;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		    "givenPcs" => $FinalGivenPcs,
		    "givenWeight" => $FinalGivenWeight,
		    "givenRmWeight" => $FinalGivenRMWeight,
		    "givenTotalWeight" => $FinalGivenTotalWeight,
		    "receivePcs" => $FinalReceivePcs,
		    "receiveWeight" => $FinalReceiveWeight,
		    "receiveRmWeight" => $FinalReceiveRMWeight,
		    "receiveTotalWeight" => $TotalReceiveWeight,
		);
		echo json_encode($response);
		exit();
	}

	private function validateId($id)
	{
		(!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/receive_garnu");
	}
	
	public function UsedRowMaterial(){
	     $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $response = ['success' => false, 'error' => validation_errors() ];
            echo json_encode($response);
            return;
        } else {
	        $id = $this->input->post('id');
    	    $sql      = "SELECT 'Given' AS type, given_row_material.id, row_material.name as row_material, 
    				given_row_material.touch, given_row_material.weight, given_row_material.quantity, 
    				given_row_material.garnu_id,process.name as Process_name,given_row_material.creation_date AS date
    				FROM given_row_material
    				JOIN row_material ON given_row_material.row_material_id = row_material.id
    				JOIN given ON given_row_material.given_id = given.id
    				JOIN process ON given.process_id = process.id
    				WHERE given_row_material.lot_wise_rm_id = $id 
    				UNION ALL
    				SELECT 'Received' AS type, receive_row_material.id, row_material.name as row_material, 
    						receive_row_material.touch, receive_row_material.weight, 
    						receive_row_material.quantity, receive_row_material.garnu_id,process.name as Process_name,receive_row_material.creation_date AS date
    				FROM receive_row_material
    				JOIN row_material ON receive_row_material.row_material_id = row_material.id
    				JOIN receive ON receive_row_material.received_id = receive.id
    				JOIN given ON receive.given_id = given.id
    				JOIN process ON given.process_id = process.id
    				WHERE receive_row_material.lot_wise_rm_id = $id ORDER BY date DESC";
            $query    = $this->db->query($sql);
            $records  = $query->result_array();
            
            if (!empty($records)) {
                $response = [ 'success' => true, 'message' => 'Data Fetched successfully.', 'data' => $records];
            } else {
                $response = [ 'success' => false, 'message' => 'Data Not Found.', 'data' => [] ];
            }
	    }
	    
        echo json_encode($response);
        return;
	}
}