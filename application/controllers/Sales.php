<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
	public $form_validation, $input, $db;
	const View = "admin/sales/sale_report";
	const Create = "admin/sales/create";
	public function __construct()
	{
		parent::__construct();
		check_login();
		$this->load->model('Sales_model', "sales");
		$this->load->model('Sequence_model', 'seq');
		$this->load->model('payment/Jama_model', 'jama');
	}

	public function index($date = "")
	{
		checkPrivilege(privilege['sale_view']);
		$page_data['date'] = $date ?? "";
		$page_data['page_title'] = 'Sales Report';
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['items'] = $this->sales->fetch_item();
		$page_data['bank'] = $this->jama->bank();
		$page_data['party'] = $this->jama->party();
		$page_data['metal_type'] = $this->jama->metal_type();
		return view(self::View, $page_data);
	}

	public function create()
	{
		checkPrivilege(privilege['sale_add']);
		$page_data['page_title'] = 'Sales';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['item'] = $this->sales->fetch_item();
		$page_data['stamp'] = $this->sales->fetch_stamp();
		$page_data['unit'] = $this->sales->fetch_unit();
		$page_data['bank'] = $this->jama->bank();
		$page_data['party'] = $this->jama->party();
		$page_data['metal_type'] = $this->jama->metal_type();
		return view(self::Create, $page_data);
	}

	public function store()
	{
		$validation = $this->form_validation;
		$validation->set_rules('party_id', 'Party', 'trim|required|numeric');
		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$data = xss_clean($this->input->post());
		$insert['date'] = $data['date'];
		$insert['user_id'] = session('id');
		$insert['code'] = 'S' . $this->generate_unique_code();
		$insert['sequence_code'] = $this->seq->getNextSequence('sale');
		$insert['party_id'] = $data['party_id'];
		$insert['closing_fine'] = $data['closing_fine'];
		$insert['closing_amount'] = $data['closing_amount'];
		$insert['total_fine'] = $data['total_fine'];
		$insert['total_amount'] = $data['total_amount'];
		$insert['ready_for_sale_code'] = $data['RFSCode'];
		$this->db->insert('sale', $insert);
		$id = $this->db->insert_id();
		$this->db->where('code', $data['RFSCode'])->update('ready_for_sale', ['isSale' => "YES"]);
		if (!$id) {
			flash()->withError("Failed to insert sale record.")->to("sales/index");
		}

		for ($i = 0; $i < count($data['item']); $i++) {

			$customer = $this->db->where(['customer_id' => $data['party_id'], 'item_id' => $data['item'][$i]])->get('customer_item')->num_rows();
			$customer_details = $new = array();
			if ($customer == 0) {
				$customer_details['item_id'] = $data['item'][$i] ?? "";
				$customer_details['extra_touch'] = $data['touch'][$i] ?? "";
				$customer_details['wastage'] = $data['wastage'][$i] ?? "";
				$customer_details['label'] = $data['labour_type'][$i] ?? "";
				$customer_details['rate'] = $data['labour'][$i] ?? "";
				$customer_details['sub_total'] = $data['touch'][$i] ?? "" + $data['wastage'][$i] ?? "";
				$customer_details['customer_id'] = $data['party_id'];
				$batchData[] = $customer_details;
				$this->db->insert_batch('customer_item', $batchData);
			}

			foreach (explode(',', $data['lot_creation_tag'][$i]) as $tag) {
				$this->db->where('tag', $tag)->update('lot_creation', ['status' => 2]);
			}
			$saleDetail['sale_id'] = $id;
			$saleDetail['user_id'] = session('id');
			$saleDetail['item_id'] = $data['item'][$i];
			// $saleDetail['sub_item_id'] = $data['sub_item'][$i];
			$saleDetail['stamp_id'] = $data['stamp'][$i] == "null" ? null : $data['stamp'][$i];
			$saleDetail['unit_id'] = $data['unit'][$i];
			$saleDetail['lot_creation_tag'] = $data['lot_creation_tag'][$i];
			$saleDetail['remark'] = $data['remark'][$i];
			$saleDetail['gross_weight'] = $data['gross_weight'][$i];
			$saleDetail['less_weight'] = $data['less_weight'][$i];
			$saleDetail['net_weight'] = $data['net_weight'][$i];
			$saleDetail['pre_touch'] = $data['pre_touch'][$i];
			$saleDetail['touch'] = $data['touch'][$i];
			$saleDetail['wastage'] = $data['wastage'][$i];
			$saleDetail['fine'] = $data['fine'][$i];
			$saleDetail['piece'] = $data['piece'][$i];
			$saleDetail['rate'] = $data['rate'][$i];
			$saleDetail['labour_type'] = $data['labour_type'][$i];
			$saleDetail['labour'] = $data['labour'][$i];
			$saleDetail['other_amount'] = $data['other_amount'][$i];
			$saleDetail['sub_total'] = $data['sub_total'][$i];
			$saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
			$this->db->insert('sale_detail', $saleDetail);
			$saleDetailId = $this->db->insert_id();
			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);
				for ($a = 0; $a < count($array); $a++) {
					$saleMaterialData = [];
					if (!empty($array1[0]) || !empty($array[1]) || !empty($array[2]) || !empty($array[3])) {
						$array1 = explode(",", $array[$a]);
						$saleMaterial['user_id'] = session('id');
						$saleMaterial['sale_detail_id'] = $saleDetailId;
						$saleMaterial['row_material_id'] = $array1[0];
						$saleMaterial['quantity'] = $array1[1];
						$saleMaterial['rate'] = $array1[2];
						$saleMaterial['sub_total'] = $array1[3];
						$saleMaterialData[] = $saleMaterial;
					}
				}
				if (!empty($saleMaterialData)) {
					$this->db->insert_batch('sale_material', $saleMaterialData);
				}
			}
		}

		$payment = json_decode($data['paymentArray']);
		$jama_code = $this->db->get_where('setting', array('id' => 1))->row('jama_code');
		$jama = 'JAMA_' . $jama_code;
		$this->db->where('id', 1);
		$this->db->update('setting', array('jama_code' => $jama_code + 1));
		for ($a = 0; $a < count($payment); $a++) {
			$sequence_code = $this->seq->getNextSequence('jama');
			if (isset($payment[$a]->type)) {
				$insert = $this->db->insert('jama', array(
					'sale_id' => $id,
					'date' => $data['date'],
					'customer_id' => $data['party_id'],
					'type' => $payment[$a]->type,
					'mode' => $payment[$a]->mode,
					'gross' => $payment[$a]->gross,
					'purity' => $payment[$a]->purity,
					'wb' => $payment[$a]->wb,
					'fine' => $payment[$a]->fine,
					'rate' => $payment[$a]->rate,
					'amount' => $payment[$a]->amount,
					'remark' => $payment[$a]->remark,
					'jama_code' => '',
					'metal_type_id' => $payment[$a]->metal_type_id,
					'creation_date' => date('Y-m-d'),
					'payment_type' => $payment[$a]->payment,
					'jama_code' => $jama,
					'bank_id' => $payment[$a]->bank,
					'sequence_code' => $sequence_code
				));
			}
		}
		flash()->withSuccess("Insert Successfully.")->to("sales/index");
	}

	public function edit($id)
	{
		checkPrivilege(privilege['sale_edit']);
		$data = $this->db->select('sale.*')->from('sale')->where('id', $id)->get()->row_array();
		if (empty($data)) {
			flash()->withError("Data Not Found")->to("sales/index");
		}
		$data['sale_detail'] = $this->db->select('sale_detail.*')->from('sale_detail')->where('sale_id', $id)->get()->result_array();
		for ($i = 0; $i < count($data['sale_detail']); $i++) {
			if (!empty($data['sale_detail'][$i]['raw_material_data'])) {
				$array = explode("|", $data['sale_detail'][$i]['raw_material_data']);
				$array2 = [];
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$material = $this->db->select('*')->from('sale_material')->where([
						'sale_detail_id' => $data['sale_detail'][$i]['id'],
						'row_material_id' => $array1[0],
						'quantity' => $array1[1],
						'rate' => $array1[2],
						'sub_total' => $array1[3]
					])->get()->row_array();
					$array1[4] = isset($material['id']) ? $material['id'] : 0;
					$array2[] = implode(",", $array1);
				}
				$array3 = implode("|", $array2);
				$data['sale_detail'][$i]['raw_material_data'] = $array3;
			} else {
				$data['sale_detail'][$i]['raw_material_data'] = "";
			}
		}
		$this->db->select('jama.*, bank.name as bankname'); // Select all fields from both tables (you can specify fields if needed)
		$this->db->from('jama');
		$this->db->join('bank', 'bank.id = jama.bank_id'); // Joining the sale table based on sale_id
		$this->db->where('jama.sale_id', $id); // Filtering by the sale_id
		$query = $this->db->get(); // Running the query

		$result = $query->result();
		$page_data['page_title'] = 'Sales';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['payment'] = $result;
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['item'] = $this->sales->fetch_item();
		$page_data['stamp'] = $this->sales->fetch_stamp();
		$page_data['unit'] = $this->sales->fetch_unit();
		$page_data['bank'] = $this->jama->bank();
		$page_data['party'] = $this->jama->party();
		$page_data['metal_type'] = $this->jama->metal_type();
		$page_data['data'] = $data;
		return view(self::Create, $page_data);
	}

	public function update($id)
	{
		$data = xss_clean($this->input->post());
		$update['date'] = $data['date'];
		$update['party_id'] = $data['party_id'];
		$update['total_fine'] = $data['total_fine'];
		$update['total_amount'] = $data['total_amount'];
		$this->db->where('id', $id)->update('sale', $update);

		$existingIds = isset($data['rowid']) ? $data['rowid'] : [];
		$allids = isset($data['ids']) ? $data['ids'] : [];
		$idsNotExisting = array_diff($allids, $existingIds);

		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('sale_detail');

			$this->db->where_in('sale_detail_id', $idsNotExisting);
			$this->db->delete('sale_material');
		}

		for ($i = 0; $i < count($data['item']); $i++) {

			$customer = $this->db->where(['customer_id' => $data['party_id'], 'item_id' => $data['item'][$i]])->get('customer_item')->num_rows();
			$customer_details = $new = array();
			if ($customer == 0) {
				$customer_details['item_id'] = $data['item'][$i] ?? "";
				$customer_details['extra_touch'] = $data['touch'][$i] ?? "";
				$customer_details['wastage'] = $data['wastage'][$i] ?? "";
				$customer_details['label'] = $data['labour_type'][$i] ?? "";
				$customer_details['rate'] = $data['labour'][$i] ?? "";
				$customer_details['sub_total'] = $data['touch'][$i] ?? "" + $data['wastage'][$i] ?? "";
				$customer_details['customer_id'] = $data['party_id'];
				$batchData[] = $customer_details;
				$this->db->insert_batch('customer_item', $batchData);
			}

			$saleDetail['item_id'] = $data['item'][$i];
			// $saleDetail['sub_item_id'] = $data['sub_item'][$i];
			$saleDetail['stamp_id'] = $data['stamp'][$i] == "null" ? null : $data['stamp'][$i];
			$saleDetail['unit_id'] = $data['unit'][$i];
			$saleDetail['remark'] = $data['remark'][$i];
			$saleDetail['gross_weight'] = $data['gross_weight'][$i];
			$saleDetail['less_weight'] = $data['less_weight'][$i];
			$saleDetail['net_weight'] = $data['net_weight'][$i];
			$saleDetail['pre_touch'] = $data['pre_touch'][$i];
			$saleDetail['touch'] = $data['touch'][$i];
			$saleDetail['wastage'] = $data['wastage'][$i];
			$saleDetail['fine'] = $data['fine'][$i];
			$saleDetail['piece'] = $data['piece'][$i];
			$saleDetail['rate'] = $data['rate'][$i];
			$saleDetail['labour_type'] = $data['labour_type'][$i];
			$saleDetail['labour'] = $data['labour'][$i];
			$saleDetail['other_amount'] = $data['other_amount'][$i];
			$saleDetail['sub_total'] = $data['sub_total'][$i];
			$saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
			if ($data['rowid'][$i] == 0) {
				$saleDetail['sale_id'] = $id;
				$saleDetail['lot_creation_tag'] = $data['lot_creation_tag'][$i];
				$this->db->insert('sale_detail', $saleDetail);
				$saleDetailId = $this->db->insert_id();

				foreach (explode(',', $data['lot_creation_tag'][$i]) as $tag) {
					$this->db->where('tag', $tag)->update('lot_creation', ['status' => 2]);
				}
				// $this->db->where('tag', $data['lot_creation_tag'][$i])->update('lot_creation', ['status'=>2]);
			} else {
				$this->db->where(['id' => $data['rowid'][$i], 'sale_id' => $id])->update('sale_detail', $saleDetail);
			}
			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$saleMaterial['row_material_id'] = $array1[0];
					$saleMaterial['quantity'] = $array1[1];
					$saleMaterial['rate'] = $array1[2];
					$saleMaterial['sub_total'] = $array1[3];
					if ($array1[4] == 0) {
						$saleMaterial['sale_detail_id'] = $saleDetailId ?? $data['rowid'][$i];
						$this->db->insert('sale_material', $saleMaterial);
					} else {
						$this->db->where(['sale_detail_id' => $data['rowid'][$i], 'id' => $array1[4]])->update('sale_material', $saleMaterial);
					}
				}
			}
		}

		$payment = json_decode($data['paymentArray']);
		$jama = $this->db->get_where('jama', ['sale_id' => $id])->row('jama_code');
		if ($jama == '') {
			$jama_code = $this->db->get_where('setting', array('id' => 1))->row('jama_code');
			$jama = 'JAMA_' . $jama_code;
			$this->db->where('id', 1);
			$this->db->update('setting', array('jama_code' => $jama_code + 1));
		}
		$saleId = [];
		for ($b = 0; $b < count($payment); $b++) {
			if (isset($payment[$b]->saleid)) {
				$saleId[] = $payment[$b]->saleid;
			}
		}
		// Deletion logic
		$deleteQ = $this->db->get_where('jama', ['sale_id' => $id])->result_array();
		foreach ($deleteQ as $deleteR) {
			// Check if the saleid from the current payment is in the deleteQ results
			if (!in_array($deleteR['id'], $saleId)) {
				$this->db->where('id', $deleteR['id'])->delete('jama');
			}
		}
		for ($a = 0; $a < count($payment); $a++) {
			if (isset($payment[$a]->type)) {
				$sequence_code = $this->seq->getNextSequence('jama');
				if ($payment[$a]->saleid == '') {
					$insert = $this->db->insert('jama', array(
						'sale_id' => $id,
						'date' => $data['date'],
						'customer_id' => $data['party_id'],
						'type' => $payment[$a]->type,
						'mode' => $payment[$a]->mode,
						'gross' => $payment[$a]->gross,
						'purity' => $payment[$a]->purity,
						'wb' => $payment[$a]->wb,
						'fine' => $payment[$a]->fine,
						'rate' => $payment[$a]->rate,
						'amount' => $payment[$a]->amount,
						'remark' => $payment[$a]->remark,
						'jama_code' => '',
						'metal_type_id' => $payment[$a]->metal_type_id,
						'creation_date' => date('Y-m-d'),
						'payment_type' => $payment[$a]->payment,
						'jama_code' => $jama,
						'bank_id' => $payment[$a]->bank,
						'sequence_code' => $sequence_code
					));
				} else {
					$saleId[] = $payment[$a]->saleid;
					$this->db->where(['id' => $payment[$a]->saleid, 'sale_id' => $id]);
					$this->db->update('jama', array(
						'date' => $data['date'],
						'customer_id' => $data['party_id'],
						'type' => $payment[$a]->type,
						'mode' => $payment[$a]->mode,
						'gross' => $payment[$a]->gross,
						'purity' => $payment[$a]->purity,
						'wb' => $payment[$a]->wb,
						'fine' => $payment[$a]->fine,
						'rate' => $payment[$a]->rate,
						'amount' => $payment[$a]->amount,
						'remark' => $payment[$a]->remark,
						'metal_type_id' => $payment[$a]->metal_type_id,
						'payment_type' => $payment[$a]->payment,
						'bank_id' => $payment[$a]->bank
					));
				}
			}
		}

		flash()->withSuccess("Update Successfully.")->to("sales/index");
	}

	public function getReport()
	{
		$request = $this->security->xss_clean($this->input->post());
		$report  = $request['group'];
		$url  = $request['url'];
		$table   = [];

		switch ($report) {
			case 'item':
				$table['data'] = $this->sales->getSalesGroupByItem($request);
				$table['url'] = $url;
				break;
			case 'customer':
				$table['data'] = $this->sales->getSalesGroupByCustomer($request);
				$table['url'] = $url;
				break;
			case 'bill':
				$table['data'] = $this->sales->getSalesGroupByBill($request);
				$table['url'] = $url;
				break;
			case 'voucher':
				$table['data'] = $this->sales->getSalesGroupByVoucher($request);
				$table['url'] = $url;
				break;
			case 'month':
				$table['data'] = $this->sales->getSalesGroupByMonth($request);
				$table['url'] = $url;
				break;
		}

		$this->load->view("admin/sales/table/{$report}_ajax", $table);
	}

	public function delete($id)
	{
		checkPrivilege(privilege['sale_delete']);
		$this->db->trans_start();

		$ready_for_sale_code = $this->db->select('id,ready_for_sale_code')->from('sale')->where(array('id' => $id))->get()->row_array();
		$ready_for_sale = $this->db->select('id')->from('ready_for_sale')->where(array('code' => $ready_for_sale_code['ready_for_sale_code']))->get()->row_array();
		$this->db->where('code', $ready_for_sale_code['ready_for_sale_code'])->update('ready_for_sale', ['isSale' => 'NO']);
		$lot_creation_tag = $this->db->select('lot_creation_tag')->from('ready_for_sale_detail')->where(array('sale_id' => $ready_for_sale['id']))->get()->result_array();

		foreach ($lot_creation_tag as $tag) {
			$this->db->where('tag', $tag['lot_creation_tag'])->update('lot_creation', ['status' => 1]);
		}
		$this->db->where('id', $id);
		$this->db->delete('sale');

		if ($this->db->affected_rows() > 0) {
			$sale_detail_ids = $this->db->select('id')->from('sale_detail')->where('sale_id', $id)->get()->result_array();
			if (!empty($sale_detail_ids)) {
				$ids = array_map(function ($item) {
					return $item['id'];
				}, $sale_detail_ids);

				$this->db->where_in('sale_detail_id', $ids);
				$this->db->delete('sale_material');

				$this->db->where_in('id', $ids);
				$this->db->delete('sale_detail');
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				flash()->withError("Deletion failed.")->to("sales");
				return FALSE;
			} else {
				flash()->withSuccess("Deleted successfully.")->to("sales");
				return TRUE;
			}
		} else {
			$this->db->trans_complete();
			flash()->withError("Deletion failed.")->to("sales");
			return FALSE;
		}
	}

	function generate_unique_code()
	{
		$unique_code = '';
		do {
			$unique_code = sprintf('%05d', mt_rand(0, 99999));
			$this->db->where('code', $unique_code);
			$count = $this->db->count_all_results('sale');
		} while ($count > 0);
		return $unique_code;
	}

	function bill($sales_id = 0)
	{
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$sales_id = $this->security->xss_clean($sales_id);
		$sales_id = $this->db->escape($sales_id);
		$q = "SELECT 
				SUM(SI.gross_weight) AS gross_weight, 
				SUM(SI.less_weight) AS less_weight, 
				SUM(SI.net_weight) AS net_weight, 
				SUM(SI.fine) AS fine_gold, 
				SUM(SI.sub_total) AS net_amt,
				SUM(SI.piece) AS pcs,
				S.sequence_code,
				S.`party_id`,
				C.`name` AS customer,
				S.date,
				SI.remark,
				S.id AS sales_id,
				S.`created_at` AS created_at,
				SI.touch,
				SI.pre_touch,
				I.name AS item_name,
				city.name AS city
			FROM sale_detail SI 
			LEFT JOIN sale S ON S.id = SI.sale_id
			LEFT JOIN item I ON I.id = SI.item_id
			LEFT JOIN customer C ON C.id = S.party_id
			LEFT JOIN city ON city.id = C.city_id
			WHERE S.id = $sales_id
			GROUP BY SI.item_id
			ORDER BY S.sequence_code";

		$res = $this->db->query($q)->result_array();
		$customerQ = "
        		SELECT S.sequence_code, 
        		city.name AS city,
        		S.date, 
        		C.name AS customer_name, 
        		C.id AS customer_id, 
        		S.created_at, 
        		S.id AS sales_id 
        		FROM sale S 
        		LEFT JOIN customer C ON C.id = S.party_id 
        		LEFT JOIN city ON city.id = C.city_id
        		WHERE S.id = $sales_id";
		$customer = $this->db->query($customerQ)->row_array();

		$page_data['page_name'] = 'admin/print/sale_bill';
		$page_data['page_title'] = 'Sales Bill';
		$page_data['bill_data'] = $res;
		$page_data['url'] = 'sales';
		$page_data['payments'] = $this->sales->getRelatedPayments($sales_id, $customer['customer_id']);
		$page_data['customer'] = $customer;
		$this->load->view('common', $page_data);
	}

	public function receiveTag($tag)
	{
		$lot_creation = $this->db->select('*')->from('lot_creation')->where(array('tag' => $tag))->get()->row_array();
		if (!empty($lot_creation)) {
			if ($lot_creation['status'] == "0") {
				$response = ['success' => false, 'message' => "$tag Is Not Ready For Sale", 'lot_creation' => []];
			} else if ($lot_creation['status'] == "2") {
				$response = ['success' => false, 'message' => "$tag Is Already Sale", 'lot_creation' => []];
			} else {
				$response = ['success' => true, 'message' => 'Data Fetch SuccessFully..', 'lot_creation' => $lot_creation];
			}
		} else {
			$response = ['success' => false, 'message' => 'Invalid Tag.', 'lot_creation' => []];
		}

		echo json_encode($response);
		return;
	}

	public function customerData($customer_id, $item_id)
	{
		$customer = $this->db->select('wastage,label,rate')->from('customer_item')->where(array('customer_id' => $customer_id, 'item_id' => $item_id))->get()->row_array();

		if (!empty($customer)) {
			$response = ['success' => true, 'message' => 'Data Fetch SuccessFully..', 'data' => $customer];
		} else {
			$response = ['success' => false, 'message' => 'Customer Data Not Found.', 'data' => []];
		}

		echo json_encode($response);
		return;
	}
	public function receiveRFSCode($code)
	{
		$ready_for_sale = $this->db->select('id,isSale')->from('ready_for_sale')->where(array('code' => $code))->get()->row_array();
		if (!empty($ready_for_sale)) {
			if ($ready_for_sale['isSale'] == "NO") {
				$data = $this->db->select('*')->from('ready_for_sale_detail')->where(array('sale_id' => $ready_for_sale['id']))->get()->result_array();
				if (!empty($data)) {
					$response = ['success' => true, 'message' => 'Data Fetch SuccessFully..', 'data' => $data];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found', 'data' => []];
				}
			} else {
				$response = ['success' => false, 'message' => "$code Is Already Sale", 'data' => []];
			}

			// if($ready_for_sale['id']){
			// 	$response = [ 'success' => false, 'message' => "$code Is Not Ready For Sale", 'lot_creation'=>[]];
			// }else if($ready_for_sale['status'] == "2"){
			// 	$response = [ 'success' => false, 'message' => "$code Is Already Sale", 'lot_creation'=>[]];
			// }else{
			// 	$response = [ 'success' => true, 'message' => 'Data Fetch SuccessFully..', 'lot_creation'=>$ready_for_sale ];
			// }
		} else {
			$response = ['success' => false, 'message' => 'Invalid Code.', 'data' => []];
		}

		echo json_encode($response);
		return;
	}
}
