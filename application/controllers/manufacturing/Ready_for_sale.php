<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ready_for_sale extends CI_Controller
{
	public $form_validation, $input, $db;
	const View = "admin/manufacturing/sale/ready_sale_report";
	const Create = "admin/manufacturing/sale/ready_sale_create";
	public function __construct()
	{
		parent::__construct();
		check_login();
		$this->load->model('manufacturing/Ready_for_sale_model', "sales");
		$this->load->model('Sequence_model', 'seq');
	}

	public function index($date = "")
	{
		checkPrivilege(privilege['ready_for_sale_view']);
		$page_data['date'] = $date ?? "";
		$page_data['page_title'] = 'Ready For Sale Report';
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['items'] = $this->sales->fetch_item();
		return view(self::View, $page_data);
	}

	public function create()
	{
		checkPrivilege(privilege['ready_for_sale_add']);
		$page_data['page_title'] = 'Ready For Sale';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['item'] = $this->sales->fetch_item();
		$page_data['stamp'] = $this->sales->fetch_stamp();
		$page_data['unit'] = $this->sales->fetch_unit();
		return view(self::Create, $page_data);
	}

	public function store()
	{
		// $validation = $this->form_validation;
		// $validation->set_rules('party_id', 'Party', 'trim|required|numeric');
		// if (!$validation->run()) {
		// 	return flash()->withError(validation_errors())->back();
		// }

		$batchData = [];
		$data = xss_clean($this->input->post());
		$insert['date'] = $data['date'];
		$insert['user_id'] = session('id');
		$insert['code'] = 'RFS' . $this->generate_unique_code();
		$insert['sequence_code'] = $this->seq->getNextSequence('ready_for_sale');
		// $insert['party_id'] = $data['party_id'];
		$batchData[] = $insert;
		$this->db->insert_batch('ready_for_sale', $batchData);
		$id = $this->db->insert_id();
		if (!$id) {
			flash()->withError("Failed to insert sale record.")->to("sales/index");
		}


		for ($i = 0; $i < count($data['item']); $i++) {
			$this->db->where('tag', $data['lot_creation_tag'][$i])->update('lot_creation', ['status' => 1]);
			$saleDetail['sale_id'] = $id;
			$saleDetail['user_id'] = session('id');
			$saleDetail['item_id'] = $data['item'][$i];
			$saleDetail['remark'] = $data['remark'][$i];
			$saleDetail['sub_item_id'] = $data['sub_item'][$i];
			$saleDetail['stamp_id'] = $data['stamp'][$i];
			$saleDetail['piece'] = $data['piece'][$i];
			$saleDetail['touch'] = $data['touch'][$i];
			$saleDetail['lot_creation_tag'] = $data['lot_creation_tag'][$i];
			$saleDetail['gross_weight'] = $data['gross_weight'][$i];
			$saleDetail['less_weight'] = $data['less_weight'][$i];
			$saleDetail['net_weight'] = $data['net_weight'][$i];
			$saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
			$this->db->insert('ready_for_sale_detail', $saleDetail);
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
					$this->db->insert_batch('ready_for_sale_material', $saleMaterialData);
				}
			}
		}
		flash()->withSuccess("Insert Successfully.")->to("manufacturing/ready_for_sale/create");
	}

	public function edit($id)
	{
		checkPrivilege(privilege['ready_for_sale_edit']);
		$data = $this->db->select('ready_for_sale.*')->from('ready_for_sale')->where('id', $id)->get()->row_array();
		if (empty($data)) {
			flash()->withError("Data Not Found")->to("manufacturing/ready_for_sale");
		}
		$data['sale_detail'] = $this->db->select('ready_for_sale_detail.*')->from('ready_for_sale_detail')->where('sale_id', $id)->get()->result_array();
		for ($i = 0; $i < count($data['sale_detail']); $i++) {
			if (!empty($data['sale_detail'][$i]['raw_material_data'])) {
				$array = explode("|", $data['sale_detail'][$i]['raw_material_data']);
				$array2 = [];
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$material = $this->db->select('*')->from('ready_for_sale_material')->where([
						'sale_detail_id' => $data['sale_detail'][$i]['id'],
						'row_material_id' => $array1[0], 'quantity' => $array1[1], 'rate' => $array1[2], 'sub_total' => $array1[3]
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
		$page_data['page_title'] = 'Ready For Sale';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['party'] = $this->sales->fetch_party();
		$page_data['item'] = $this->sales->fetch_item();
		$page_data['stamp'] = $this->sales->fetch_stamp();
		$page_data['unit'] = $this->sales->fetch_unit();
		$page_data['data'] = $data;
		return view(self::Create, $page_data);
	}

	public function update($id)
	{
		// $validation = $this->form_validation;
		// $validation->set_rules('party_id', 'Party', 'trim|required|numeric');
		// if (!$validation->run()) {
		// 	return flash()->withError(validation_errors())->back();
		// }

		$data = xss_clean($this->input->post());
		$update['date'] = $data['date'];
		// $update['party_id'] = $data['party_id'];
		$this->db->where('id', $id)->update('ready_for_sale', $update);

		$existingIds = isset($data['rowid']) ? $data['rowid'] : [];
		$allids = isset($data['ids']) ? $data['ids'] : [];
		$idsNotExisting = array_diff($allids, $existingIds);

		if (!empty($idsNotExisting)) {
			$lot_creation_tag = $this->db->select('lot_creation_tag')->from('ready_for_sale_detail')->where_in('id', $idsNotExisting)->get()->result_array();
			foreach ($lot_creation_tag as $tag) {
				$this->db->where('tag', $tag['lot_creation_tag'])->update('lot_creation', ['status' => 0]);
			}

			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('ready_for_sale_detail');

			$this->db->where_in('sale_detail_id', $idsNotExisting);
			$this->db->delete('ready_for_sale_material');
		}

		for ($i = 0; $i < count($data['item']); $i++) {
			$saleDetail['item_id'] = $data['item'][$i];
			$saleDetail['sub_item_id'] = $data['sub_item'][$i];
			$saleDetail['stamp_id'] = $data['stamp'][$i];
			$saleDetail['piece'] = $data['piece'][$i];
			$saleDetail['touch'] = $data['touch'][$i];
			$saleDetail['lot_creation_tag'] = $data['lot_creation_tag'][$i];
			$saleDetail['remark'] = $data['remark'][$i];
			$saleDetail['gross_weight'] = $data['gross_weight'][$i];
			$saleDetail['less_weight'] = $data['less_weight'][$i];
			$saleDetail['net_weight'] = $data['net_weight'][$i];
			$saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
			if ($data['rowid'][$i] == 0) {
				$saleDetail['sale_id'] = $id;
				$this->db->insert('ready_for_sale_detail', $saleDetail);
				$saleDetailId = $this->db->insert_id();

				$this->db->where('tag', $data['lot_creation_tag'][$i])->update('lot_creation', ['status' => 1]);
			} else {
				$this->db->where(['id' => $data['rowid'][$i], 'sale_id' => $id])->update('ready_for_sale_detail', $saleDetail);
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
						$this->db->insert('ready_for_sale_material', $saleMaterial);
					} else {
						$this->db->where(['sale_detail_id' => $data['rowid'][$i], 'id' => $array1[4]])->update('ready_for_sale_material', $saleMaterial);
					}
				}
			}
		}
		flash()->withSuccess("Update Successfully.")->to("manufacturing/ready_for_sale");
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

		$this->load->view("admin/manufacturing/sale/table/{$report}_ajax", $table);
	}

	public function delete($id)
	{
		checkPrivilege(privilege['ready_for_sale_delete']);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('ready_for_sale');

		if ($this->db->affected_rows() > 0) {
			$sale_detail_ids = $this->db->select('id,lot_creation_tag')->from('ready_for_sale_detail')->where('sale_id', $id)->get()->result_array();
			if (!empty($sale_detail_ids)) {
				$ids = array_map(function ($item) {
					$this->db->where('tag', $item['lot_creation_tag'])->update('lot_creation', ['status' => 0]);
					return $item['id'];
				}, $sale_detail_ids);

				$this->db->where_in('sale_detail_id', $ids);
				$this->db->delete('ready_for_sale_material');

				$this->db->where_in('id', $ids);
				$this->db->delete('ready_for_sale_detail');
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				flash()->withError("Deletion failed.")->to("manufacturing/ready_for_sale");
				return FALSE;
			} else {
				flash()->withSuccess("Deleted successfully.")->to("manufacturing/ready_for_sale");
				return TRUE;
			}
		} else {
			$this->db->trans_complete();
			flash()->withError("Deletion failed.")->to("manufacturing/ready_for_sale");
			return FALSE;
		}
	}

	function generate_unique_code()
	{
		$unique_code = '';
		do {
			$unique_code = sprintf('%05d', mt_rand(0, 99999));
			$this->db->where('code', $unique_code);
			$count = $this->db->count_all_results('ready_for_sale');
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
				S.sequence_code,
				S.date,
				SI.remark,
				S.id AS sales_id,
				S.`created_at` AS created_at,
				I.name AS item_name,
				RSI.name AS sub_item_name,
				city.name AS city
			FROM ready_for_sale_detail SI 
			LEFT JOIN ready_for_sale S ON S.id = SI.sale_id
			LEFT JOIN item I ON I.id = SI.item_id
			LEFT JOIN sub_item RSI ON RSI.id = SI.sub_item_id
			LEFT JOIN city ON city.id = C.city_id
			WHERE S.id = $sales_id
			GROUP BY SI.item_id
			ORDER BY S.sequence_code";

		$res = $this->db->query($q)->result_array();
		$customerQ = "
        		SELECT S.sequence_code, 
        		city.name AS city,
        		S.date, 
        		S.created_at, 
        		S.id AS sales_id 
        		FROM ready_for_sale S 
        		LEFT JOIN city ON city.id = C.city_id
        		WHERE S.id = $sales_id";
		$customer = $this->db->query($customerQ)->row_array();

		$page_data['page_name'] = 'admin/manufacturing/print/sale_bill';
		$page_data['page_title'] = 'Sales Bill';
		$page_data['bill_data'] = $res;
		$page_data['url'] = 'sales';
		$page_data['payments'] = $this->sales->getRelatedPayments($sales_id, $customer['customer_id']);
		$page_data['customer'] = $customer;
		$this->load->view('common', $page_data);
	}

	public function getSubItem()
	{
		try {
			$this->form_validation->set_rules('item_id', 'Item Id', 'trim|required|numeric');
			if ($this->form_validation->run() == FALSE) {
				$response = ['success' => false, 'message' => trim(validation_errors())];
				echo json_encode($response);
				return;
			} else {
				$postData = $this->input->post();
				$item_id = $postData['item_id'];
				$data = $this->db->select('*')->from('sub_item')->where('item_id', $item_id)->get()->result_array();
				if (!empty($data)) {
					$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
				} else {
					$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
				}
				echo json_encode($response);
				return;
			}
		} catch (Exception $e) {
			$response = [
				'success' => false, 'message' => $e->getMessage(), 'data' => []
			];
			echo json_encode($response);
		}
	}

	public function receiveTag($tag)
	{
		$lot_creation = $this->db->select('*')->from('lot_creation')->where(array('tag' => $tag))->get()->row_array();
		if (!empty($lot_creation)) {
			if ($lot_creation['status'] == "1") {
				$response = ['success' => false, 'message' => "$tag Is Already Ready For Sale", 'lot_creation' => []];
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
}
