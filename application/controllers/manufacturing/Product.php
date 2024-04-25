<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/manufacturing/product_report";
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		// library("Joinhelper");
	}

	public function index($action = "", $id = null)
	{
		checkPrivilege(privilege["main_report"]);
		$page_data['page_title'] = 'Product Report';
		$page_data['process'] = $this->db->select('id,name')->get('process')->result_array();
		$page_data['worker'] = $this->db->select('id,name')->get_where('customer', array('account_type_id' => 2))->result_array();
		$page_data['product'] = $this->db->select('id,name')->get('garnu')->result_array();
		$page_data['metal_type'] = $this->dbh->findAll('metal_type');
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
		$process_id = $postData['process_id'];
		$worker_id = $postData['worker_id'];
		$product_id = $postData['product_id'];

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
		$this->db->select('garnu.*')
			->from('garnu')
			->join('given', 'given.garnu_id = garnu.id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);
		if (!empty($fromdate)) {
			$this->db->where('garnu.creation_date >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('garnu.creation_date <=', $todate);
		}
		if (!empty($product_id)) {
			$this->db->where('garnu.id', $product_id);
		}
		if (!empty($process_id)) {
			$this->db->where('given.process_id', $process_id);
		}
		if (!empty($worker_id)) {
			$this->db->where('given.worker_id', $worker_id);
		}
		$this->db->group_by('id');
		$totalRecordwithFilter = $this->db->get()->num_rows();
		// $totalRecordwithFilter = $records->num_rows();

		## Fetch records
		$this->db->select('garnu.*')
			->from('garnu')
			->join('given', 'given.garnu_id = garnu.id', 'left');
		if ($searchQuery != '') {
			$this->db->where($searchQuery);
		}
		if (!empty($fromdate)) {
			$this->db->where('garnu.creation_date >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('garnu.creation_date <=', $todate);
		}
		if (!empty($product_id)) {
			$this->db->where('garnu.id', $product_id);
		}
		if (!empty($process_id)) {
			$this->db->where('given.process_id', $process_id);
		}
		if (!empty($worker_id)) {
			$this->db->where('given.worker_id', $worker_id);
		}
		$this->db->limit($rowperpage, $start);
		$this->db->group_by('id');
		$this->db->order_by('id', "desc");
		$records = $this->db->get()->result();

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
			$process = "";
		    $garnu_name = $record->name;
		    $id = $record->id;
			$this->db->select('given.*,customer.name AS customer_name, process.name AS process_name')
				->from('given')
				->where('garnu_id', $record->id);
			if (!empty($process_id)) {
				$this->db->where('given.process_id', $process_id);
			}
			if (!empty($worker_id)) {
				$this->db->where('given.worker_id', $worker_id);
			}
			if (!empty($isComplated)) {
    			$this->db->where('given.is_completed', $isComplated);
    		}
			$this->db->join('process', 'given.process_id = process.id', 'left')
				->join('customer', 'given.worker_id = customer.id', 'left');
			$given = $this->db->get()->result();
			$process .= '<div class="table-responsive">
			<table class="table table-bordered" style="width: 100%;">
				<thead>
					<tr>
						<th>Given Date &nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th>Process Name</th>
						<th>Worker Name</th>
						<th>Given Quantity</th>
						<th>Given Weight</th>
						<th>Row Material Weight</th>
						<th>Total Weight</th>
						<th>Receive Pcs</th>
						<th>Receive Weight</th>
						<th>Receive RM Weight</th>
						<th>Receive Final Weight</th>
					</tr>
				</thead>
				<tbody>';

			foreach ($given as $value) {
				$receive = $this->db->select("pcs, weight, row_material_weight, total_weight")
					->get_where("receive", ["given_id" => $value->id])
					->result_array();
				$totalPcs = 0;
				$totalWeight = 0;
				$totalRMWeight = 0;
				$finalWeight = 0;

				foreach ($receive as $row) {
					$totalPcs += $row['pcs'];
					$totalWeight += $row['weight'];
					$totalRMWeight += $row['row_material_weight'];
					$finalWeight += $row['total_weight'];
				}
				
				$FinalReceivePcs += $totalPcs;
        		$TotalReceiveWeight += $finalWeight;
        		$FinalReceiveWeight += $totalWeight;
        		$FinalReceiveRMWeight += $totalRMWeight;
        		$FinalGivenTotalWeight += $value->total_weight;
        		$FinalGivenRMWeight += $value->row_material_weight;
        		$FinalGivenWeight += $value->given_weight;
        		$FinalGivenPcs += $value->given_qty;

				$process .= '<tr>
								<td class="given">' . $value->creation_date . '</td>
								<td class="given">' . $value->process_name . '</td>
								<td class="given">' . $value->customer_name . '</td>
								<td class="given">' . $value->given_qty . '</td>
								<td class="given">' . $value->given_weight . '</td>
								<td class="given">' . $value->row_material_weight . '</td>
								<td class="given">' . $value->total_weight . '</td>
								<td class="received totalPcs">' . $totalPcs . '</td>
								<td class="received totalWeight">' . $totalWeight . '</td>
								<td class="received rowMaterialWeight">' . $totalRMWeight . '</td>
								<td class="received totalFinalWeight">' . $finalWeight . '</td>
							</tr>';
			}

			$process .= '</tbody>
						</table>
					</div>';

			$class = ($record->recieved == "YES") ? 'indigo' : 'danger';
			$received = "<span class='badge bg-$class'>$record->recieved</span>";

			$data[] = array(
				'id' => $i,
				'garnu_name' => "<a class='badge bg-indigo text-indigo-fg' target='_blank' href='".base_url('manufacturing/process/manage/'.$id)."'><b>$garnu_name</d></a>",
				'garnu_weight' => $record->garnu_weight,
				'garnu_touch' => $record->touch,
				'garnu_silver' => $record->silver,
				'garnu_copper' => $record->copper,
				'process' => $process,
				'recieved' => $received,
				'created_at' => date('d-m-Y g:i A', strtotime($record->created_at)),
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
}
