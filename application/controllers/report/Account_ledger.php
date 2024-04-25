<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Account_ledger extends CI_Controller
{
	public $form_validation, $input, $db, $account_ledger;

	const View = "admin/report/account_ledger";
	const viewBalanceSheet = "admin/report/tnx/balance_sheet_report";

	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		$this->load->model('Account', 'account_ledger');
		$this->load->model('AccountLedger', 'alm');
	}

	public function index($row_material_id = null, $from_date = null)
	{
		checkPrivilege(privilege['account_ledger']);
		$page_data['page_title'] = 'Account Ledger';
		$page_data['account_type'] = $this->account_ledger->account_type();
		$page_data['bank'] = $this->account_ledger->bank();
		$page_data['from_date'] = $from_date;
		return view(self::View, $page_data);
	}

	public function getCustomer()
	{
		$post = xss_clean($this->input->post());

		$account_type = $post['account_type'];
		$master_type = $post['master_type'];

		if ($master_type === "account_category") {
			$data = $this->db->select('id,name')->from('customer')->where(array('account_type_id' => $account_type))->get()->result_array();
			if (!empty($data)) {
				$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
			} else {
				$response = ['success' => false, 'message' => 'Data Fetched Failed.', 'data' => []];
			}
		} else if ($master_type === "bank") {
			$data = $this->db->select('id,name')->from('bank')->get()->result_array();
			if (!empty($data)) {
				$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
			} else {
				$response = ['success' => false, 'message' => 'Data Fetched Failed.', 'data' => []];
			}
		} else {
			$response = ['success' => false, 'message' => 'invalid master type', 'data' => []];
		}
		echo json_encode($response);
		return;
	}

	function getLedgerReport()
	{
		checkPrivilege(privilege['account_ledger']);
		$fromDate    = $this->input->post('fromDate');
		$toDate      = $this->input->post('toDate');
		$customer_id = $this->input->post('customer_id');
		$ac_cat      = $this->input->post('ac_cat');
		// $ig_id       = $this->input->post('items_group_id');
		$master_type = $this->input->post('master_type');

		$fromDate    = $this->security->xss_clean($fromDate ?? "");
		$toDate      = $this->security->xss_clean($toDate ?? "");
		$customer_id = $this->security->xss_clean($customer_id ?? "");
		$ac_cat      = $this->security->xss_clean($ac_cat ?? "");
		// $ig_id       = $this->security->xss_clean($ig_id ?? "");
		$master_type = $this->security->xss_clean($master_type ?? "");

		// $this->load->model('Account_ledger_model', 'alm');
		$this->load->model('AccountLedger', 'alm');
		$data        = [];
		$data['dbh'] = $this->dbh;

		$other['master_type']               = $master_type;
		$data['customer_check_master_type'] = $master_type;
// 		pre($this->input->post());die;
		$data['data'] = $this->alm->getLedgerReprot($fromDate, $toDate, $customer_id, $ac_cat, $other);

		// echo count($this->alm->getLedgerReprot($fromDate, $toDate, 6, $ac_cat)['data']);

		if (!empty($fromDate)) {
			$this->session->set_userdata('ledger_from_date', $fromDate);
		}

		if (!empty($master_type)) {
			$this->session->set_userdata('master_type', $master_type);
		}

		if (!empty($toDate)) {
			$this->session->set_userdata('ledger_to_date', $toDate);
		}

		if (!empty($ac_cat) && $ac_cat > 0) {
			$this->session->set_userdata('ledger_ac_cat', $ac_cat);
		}

		// if (!empty ($ac_cat) && $ac_cat > 0) {
		// 	$this->session->set_userdata('items_group_id', $ig_id);
		// }
		// pre($data);die;
		return $this->load->view('admin/report/tnx/ajax_account_ledger_report', $data);
	}

	function ledgerReportByCustomer($cid = 0, $isBank = '')
	{
		checkPrivilege(privilege['account_ledger']);
		$cid    = $this->security->xss_clean($cid);
		$isBank = $this->security->xss_clean($isBank);

		// $this->load->model('Account_ledger_model', 'alm');
		$this->load->model('AccountLedger', 'alm');
		$page_data['isBank'] = $isBank;
		$fromDate            = ($this->session->userdata('ledger_from_date')) ? $this->session->userdata('ledger_from_date') : NULL;
		$toDate              = ($this->session->userdata('ledger_to_date')) ? $this->session->userdata('ledger_to_date') : NULL;
		$ac_cat              = ($this->session->userdata('ledger_ac_cat') && $this->session->userdata('ledger_ac_cat') > 0) ? $this->session->userdata('ledger_ac_cat') : 0;

		if (!empty($cid) && $isBank == '') {
			$customerData = $this->db->select('account_type_id')->get_where('customer', array('id' => $cid))->row_array();
			$this->session->set_userdata('ledger_ac_cat', $customerData['account_type_id']);
			$ac_cat              = ($this->session->userdata('ledger_ac_cat') && $this->session->userdata('ledger_ac_cat') > 0) ? $this->session->userdata('ledger_ac_cat') : 0;
		} else {
		}

		if (empty($fromDate)) {
			$fromDate = date('Y-m-01');
			$this->session->set_userdata('ledger_from_date', $fromDate);
		}
		$master_type = 'account_category';
		if ($isBank == 'bank') {
			$master_type = 'bank';
		}
		$other             = [
			'master_type' => $master_type
		];
		$page_data['data'] = $this->alm->getLedgerReprot($fromDate, $toDate, $cid, $ac_cat, $other);
		// echo count($page_data['data']['data']);
		// pre($page_data['data']);
		$page_data['dbh'] = $this->dbh;
		if ($master_type == "bank") {
			$cust = $this->dbh->getWhereRowArray('bank', ['id' => $cid]);
		} else if ($master_type == 'account_category') {
			$cust = $this->dbh->getWhereRowArray('customer', ['id' => $cid]);
		}
		$page_data['cid'] = $cid;

		$totalOpeningAmt  = 0;
		$totalOpeningFine = 0;
		if ($isBank != "bank") {
			if ($cust['opening_amount_type'] == 'JAMA') {
				$totalOpeningAmt -= $cust['opening_amount'];
			} else {
				$totalOpeningAmt += $cust['opening_amount'];
			}

			if ($cust['opening_fine_type'] == 'JAMA') {
				$totalOpeningFine -= $cust['opening_fine'];
			} else {
				$totalOpeningFine += $cust['opening_fine'];
			}
		}

		$page_data['page_name']  = 'admin/report/tnx/account_ledger_customer';
		$page_data['page_title'] = 'Customer Account Ledger | ' . $cust['name'];
		$page_data['total_opening_fine'] = $totalOpeningFine;
		$page_data['total_opening_amt']  = $totalOpeningAmt;
		$this->load->view('common', $page_data);
	}

	function getLedgerCustomerReport($isBank = '')
	{
		checkPrivilege(privilege['account_ledger']);
		$fromDate      = $this->input->post('fromDate');
		$toDate        = $this->input->post('toDate');
		$customer_id   = $this->input->post('customer_id');
		$run_time_loss = $this->input->post('run_time_loss');
		$fromDate      = $this->security->xss_clean($fromDate);
		$toDate        = $this->security->xss_clean($toDate);
		$customer_id   = $this->security->xss_clean($customer_id);
		// $run_time_loss = $this->security->xss_clean($run_time_loss);
		$ig_id         = $this->input->post('items_group_id');
		// pre($ig_id);
		$this->load->model('AccountLedger', 'alm');
		// $this->load->model('Account_ledger_model', 'alm');
		$data        = [];
		$data['dbh'] = $this->dbh;

		$master_type = 'account_category';
		if ($isBank == 'bank') {
			$master_type = 'bank';
		}
		$other          = [
			'master_type'   => $master_type,
			'run_time_loss' => $run_time_loss
		];
		$data['isBank'] = $isBank;
		$data['data']   = $this->alm->getLedgerReprot($fromDate, $toDate, $customer_id, 0, $other);
		// pre($data['data']);die;
		$cust             = $this->dbh->getWhereRowArray('customer', [
			'id' => $customer_id
		]);
		$page_data['cid'] = $customer_id;

		$totalOpeningAmt  = 0;
		$totalOpeningFine = 0;
		if ($isBank != "bank") {
			if ($cust['opening_amount_type'] == 'JAMA') {
				$totalOpeningAmt -= $cust['opening_amount'];
			} else {
				$totalOpeningAmt += $cust['opening_amount'];
			}

			if ($cust['opening_fine_type'] == 'JAMA') {
				$totalOpeningFine -= $cust['opening_fine'];
			} else {
				$totalOpeningFine += $cust['opening_fine'];
			}
		}
		$data['total_opening_fine'] = $totalOpeningFine;
		$data['total_opening_amt']  = $totalOpeningAmt;
		if (!empty($fromDate)) {
			$this->session->set_userdata('ledger_from_date', $fromDate);
		}

		if (!empty($toDate)) {
			$this->session->set_userdata('ledger_to_date', $toDate);
		}

		return $this->load->view('admin/report/tnx/ajax_account_ledger_customer_report', $data);
	}

	public function fetchData()
	{
		$postData = $this->security->xss_clean($this->input->post());
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length'];
		// serching coding
		$columnIndex = $postData['order'][0]['column']; // Column index
		$searchValue = $postData['search']['value']; // Search value
		$todate = $postData['todate'] ?? null;
		$fromdate = $postData['fromdate'] ?? null;
		$ac_cat = $postData['ac_cat'] ?? null;
		$master_type = $postData['master_type'] ?? null;
		$customer_id = $postData['customer_id'] ?? null;

		$where = "";
		if (!empty($garnu_id)) {
			$where .= "garnu.id = " . $garnu_id . " AND ";
		}
		if (!empty($process_id)) {
			$where .= "process.id = " . $process_id . " AND ";
		}
		$where = rtrim($where, ' AND ');

		if (!empty($where)) {
			$where = "($where)";
		}
		$openingWeight = 0;
		if (!empty($fromdate)) {
			$openingQuery = "SELECT 
								SUM(touch) AS total_touch, SUM(weight) AS total_weight, type
							FROM (
								SELECT given_row_material.touch, given_row_material.weight,'Debit' AS type
								FROM given_row_material
								LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
								LEFT JOIN given ON given_row_material.given_id = given.id
								LEFT JOIN process ON given.process_id = process.id
								WHERE given_row_material.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
								" . (!empty($garnu_id) ? " AND garnu.id = $garnu_id" : "") . "
								" . (!empty($process_id) ? " AND process.id = $process_id" : "") . "
								UNION ALL
								SELECT receive_row_material.touch, receive_row_material.weight,'Credit' AS type
								FROM receive_row_material
								LEFT JOIN receive ON receive_row_material.received_id = receive.id
								LEFT JOIN garnu ON receive.garnu_id = garnu.id
								LEFT JOIN given ON receive.given_id = given.id
								LEFT JOIN process ON given.process_id = process.id
								WHERE receive_row_material.creation_date < STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')
								" . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
								" . (!empty($garnu_id) ? " AND garnu.id = $garnu_id" : "") . "
								" . (!empty($process_id) ? " AND process.id = $process_id" : "") . "
							) AS opening_records
							GROUP BY type";
			$openingResult = $this->db->query($openingQuery)->result_array();

			$openingWeight = 0;

			foreach ($openingResult as $r) {
				if ($r['type'] == 'Credit') {
					$openingWeight += $r['total_weight'];
				}
				if ($r['type'] == 'Debit') {
					$openingWeight -= $r['total_weight'];
				}
			}
		}

		## Total number of records without filtering
		$q = $this->db->query("
                SELECT COUNT(*) as total_count FROM (
                SELECT
                1
                FROM
                receive_row_material
                LEFT JOIN receive ON receive_row_material.received_id = receive.id
                LEFT JOIN garnu ON receive.garnu_id = garnu.id
                LEFT JOIN given ON receive.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
                UNION ALL
                SELECT
                1
                FROM
                given_row_material
                LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                LEFT JOIN given ON given_row_material.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
        ) AS combined_results");

		$records = $q->row_array();
		$totalRecords = $records['total_count'];

		## Total number of record with filtering
		$filteredQueryCondition = !empty($where) ? $where : "TRUE";
		$filteredQuery = $this->db->query("
                    SELECT COUNT(*) as total_count_filtered FROM (
                    SELECT
                        receive_row_material.id                                 
                    FROM
                        receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE
					$filteredQueryCondition
					" . (!empty($fromdate) ? "AND receive_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
                    UNION ALL
                    SELECT
                        given_row_material.id                        
                    FROM
                        given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE 
					$filteredQueryCondition
					" . (!empty($fromdate) ? "AND given_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND given_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
        ) AS combined_results_filtered");

		$records = $filteredQuery->row_array();
		$totalRecordwithFilter = $records['total_count_filtered'];

		## Fetch records
		$fetchQueryCondition = !empty($where) ? $where : "TRUE";
		$fetchQuery = "
                    SELECT * FROM (
                    SELECT
                    receive_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    receive_row_material.touch as Touch,
                    receive_row_material.weight as Weight,
                    receive_row_material.quantity as Quantity,
                    receive_row_material.created_at as Date,               
                    'Credit' as Type   
                    FROM
                    receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE
					$fetchQueryCondition
					" . (!empty($fromdate) ? "AND receive_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND receive_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND receive_row_material.row_material_id = $row_material_id" : "") . "
                    UNION
                    SELECT
                    given_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    given_row_material.touch as Touch,
                    given_row_material.weight as Weight,
                    given_row_material.quantity as Quantity,
                    given_row_material.created_at as Date,
                    'Debit' as Type   
                    FROM
                    given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE 
					$fetchQueryCondition
					" . (!empty($fromdate) ? "AND given_row_material.creation_date >= STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')" : "") . "
                    " . (!empty($todate) ? " AND given_row_material.creation_date <= STR_TO_DATE(" . $this->db->escape($todate) . ", '%Y-%m-%d')" : "") . "
					" . (!empty($row_material_id) ? " AND given_row_material.row_material_id = $row_material_id" : "") . "
                    ) AS combined_results
					ORDER BY
           			Date ASC
                LIMIT $rowperpage OFFSET $start";

		$query = $this->db->query($fetchQuery);
		$records = $query->result_array();

		$data = array();
		$i = $start + 1;
		$closingTouch = 0;
		$closingWeight = 0;

		foreach ($records as $r) {
			$type = $r['Type'];
			if ($type == "Credit") {
				$closingWeight += $r['Weight'];
			}
			if ($type == 'Debit') {
				$closingWeight -= $r['Weight'];
			}

			$data[] = array(
				'id' => $i,
				'row_material' => $r['RowMaterial'],
				'garnu' => $r['GarnuName'],
				'process' => $r['ProcessName'],
				'touch' => $r['Touch'] ?? '--',
				'quantity' => $r['Quantity'] ?? '--',
				'credit' => ($type == 'Credit') ? $r['Weight'] : '--',
				'debit' => ($type == 'Debit') ? $r['Weight'] : '--',
				'date' => date("d-m-Y g:i A", strtotime($r['Date'])),
				'closingWeight' => $closingWeight,
			);
			$i = $i + 1;
		}


		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"closingWeight" => $closingWeight,
			"openingWeight" => $openingWeight,
		);
		echo json_encode($response);
		exit();
	}

	function balanceSheetReport()
	{
		checkPrivilege(privilege["balance_sheet_report"]);
		$fromDate    = '';
		$toDate      = date('Y-m-d');
		$customer_id = 0;
		$ac_cat      = 0;
		$ig_id       = 0;
		$fromDate    = $this->security->xss_clean($fromDate);
		$toDate      = $this->security->xss_clean($toDate);
		$customer_id = $this->security->xss_clean($customer_id);
		$ac_cat      = $this->security->xss_clean($ac_cat);
		$ig_id       = $this->security->xss_clean($ig_id);
		$data             = [];
		$page_data['dbh'] = $this->dbh;
		$finalData        = [];
		$AC               = $this->db->get('account_type')->result_array();
		foreach ($AC as $k => $v) {
			$ac_cat = $v['id'];
			$other['master_type']   = 'account_category';
			$other['run_time_loss'] = 'false';
			$data['data']           = $this->alm->getLedgerReprot($fromDate, $toDate, $customer_id, $ac_cat, $other);
			foreach ($data['data']['data'] as $i => $d) {
				$d['account_category']       = $v['name'];
				$finalData['data']['data'][] = $d;
			}
		}
		$ac_cat               = 'bank';
		$other['master_type'] = 'bank';
		$data['bankdata']     = $this->alm->getLedgerReprot($fromDate, $toDate, $customer_id, $ac_cat, $other);

		foreach ($data['bankdata']['data'] as $k => $v) {
			$v['customer_id']            = 0 - $v['party_id'];
			$v['account_category']       = 'bank';
			$finalData['data']['data'][] = $v;
		}

		$finalData['data']['opening_data'] = [];
		$page_data['page_title'] = 'Balance Sheet Report';
		$page_data['data']       = $data;
		$page_data['data']       = $finalData;
		return view(self::viewBalanceSheet, $page_data);
	}

	public function verification()
	{
		$validation = $this->form_validation;
		$validation->set_rules('status', 'Status', 'trim|required');
		$validation->set_rules('customer_id', 'Customer Id', 'trim|required');
		$validation->set_rules('type', 'Account Type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$response = ['success' => false, 'message' => validation_errors()];
			echo json_encode($response);
			return;
		} else {
			$postRequest = $this->input->post();
			$status = ($postRequest['status'] == 'checked') ? 'YES' : 'NO';
			$customer_id = $postRequest['customer_id'];
			$id = $postRequest['id'];

			switch ($postRequest['type']) {
				case 'given':
					$update = $this->db->where(['id' => $id, 'worker_id' => $customer_id])->update('given', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'receive':
					$update = $this->db->where(['given_id' => $id])->update('receive', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'purchase':
					$update = $this->db->where(['id' => $id, 'party_id' => $customer_id])->update('purchase', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'purchase_return':
					$update = $this->db->where(['id' => $id, 'party_id' => $customer_id])->update('purchase_return', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'sale':
					$update = $this->db->where(['id' => $id, 'party_id' => $customer_id])->update('sale', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'sale_return':
					$update = $this->db->where(['id' => $id, 'party_id' => $customer_id])->update('sale_return', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				case 'jama':
					$update = $this->db->where(['id' => $id, 'customer_id' => $customer_id])->update('jama', array('verification' => $status));
					if ($update) {
						$response = ['success' => true, 'message' => "Verification successful"];
					} else {
						$response = ['success' => false, 'message' => "Verification failed"];
					}
					break;
				default:
					$response = ['success' => false, 'message' => "Invalid Account Type."];
					break;
			}

			echo json_encode($response);
			return;
		}
	}

	function customerAmtAndFine_CR_DB($customer_id = 0)
	{
		if ($customer_id > 0) {
			$this->load->model('Account_ledger_model', 'alm');
			$customerLedgerData = $this->alm->getCustomerAndKarigarLedgerTotals($customer_id);
			if (isset($customerLedgerData[$customer_id])) {
				$fine = (float) number_format((float) $customerLedgerData[$customer_id]['fine'], 3, '.', '');
				$amount = (float) number_format((float) $customerLedgerData[$customer_id]['amount'], 3, '.', '');
			} else {
				$fine = 0;
				$amount = 0;
			}
			$response = array(
				'status'  => true,
				'message' => '',
				'fine'    => $fine,
				'amount'  => $amount
			);
		} else {
			$response = array(
				'status'  => false,
				'message' => 'Something went wrong!',
				'fine'    => 0,
				'amount'  => 0
			);
		}
		echo json_encode($response);
	}
}
