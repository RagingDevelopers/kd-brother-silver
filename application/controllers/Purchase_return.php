<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_return extends CI_Controller
{
	public $form_validation, $input, $db, $purchase, $seq, $jama;
	const View = "admin/purchase/purchase_return_report";
	const Create = "admin/purchase/purchase_return_create";
	public function __construct()
	{
		parent::__construct();
		check_login();
		$this->load->model('Purchase_return_model', "purchase");
		$this->load->model('Sequence_model', 'seq');
		$this->load->model('payment/Jama_model', 'jama');
		$this->load->helper('lot_management');
	}

	public function index()
	{
		checkPrivilege(privilege['purchase_return_view']);
		$page_data['page_title'] = 'Purchase Return Report';
		$page_data['party'] = $this->purchase->fetch_party();
		$page_data['items'] = $this->purchase->fetch_item();
		return view(self::View, $page_data);
	}

	public function create()
	{
		checkPrivilege(privilege['purchase_return_add']);
		$page_data['page_title'] = 'Purchase Return';
		$page_data['items'] = $this->db->select('id,name')->from('item')->get()->result_array();
		$page_data['party'] = $this->purchase->fetch_party();
		$page_data['item'] = $this->purchase->fetch_item();
		$page_data['stamp'] = $this->purchase->fetch_stamp();
		$page_data['unit'] = $this->purchase->fetch_unit();
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
		// echo "<pre>";
		// print_r($data);exit;

		$this->db->trans_begin();

		$insert = [
			'date' => $data['date'],
			'user_id' => session('id'),
			'code' => 'PR' . $this->generate_unique_code(),
			'sequence_code' => $this->seq->getNextSequence('purchase_return'),
			'party_id' => $data['party_id'],
			'product_type' => 'item',
		];

		$this->db->insert('purchase_return', $insert);
		$id = $this->db->insert_id();
		if (!$id) {
			$this->db->trans_rollback();
			flash()->withError("Failed to insert purchase Return record.")->to("purchase_return/index");
			return;
		}

		for ($i = 0; $i < count($data['item']); $i++) {
			$purchaseDetail['purchase_id'] = $id;
			$purchaseDetail['user_id'] = session('id');
			$purchaseDetail['product_type'] = 'item';
			$purchaseDetail['item_id'] = $data['item'][$i];
			$purchaseDetail['lot'] = $data['lot'][$i] ?? '';
			$purchaseDetail['stamp_id'] = $data['stamp'][$i];
			$purchaseDetail['unit_id'] = $data['unit'][$i];
			$purchaseDetail['remark'] = $data['remark'][$i];
			$purchaseDetail['gross_weight'] = $data['gross_weight'][$i];
			$purchaseDetail['less_weight'] = $data['less_weight'][$i];
			$purchaseDetail['net_weight'] = $data['net_weight'][$i];
			$purchaseDetail['touch'] = $data['touch'][$i];
			$purchaseDetail['pre_touch'] = $data['pre_touch'][$i];
			$purchaseDetail['wastage'] = $data['wastage'][$i];
			$purchaseDetail['fine'] = $data['fine'][$i];
			$purchaseDetail['piece'] = $data['piece'][$i];
			$purchaseDetail['rate'] = $data['rate'][$i];
			$purchaseDetail['labour_type'] = $data['labour_type'][$i];
			$purchaseDetail['labour'] = $data['labour'][$i];
			$purchaseDetail['other_amount'] = $data['other_amount'][$i];
			$purchaseDetail['sub_total'] = $data['sub_total'][$i];
			$purchaseDetail['raw_material_data'] = $data['raw-material-data'][$i];
			$this->db->insert('purchase_return_detail', $purchaseDetail);
			$purchaseDetailId = $this->db->insert_id();

			$lotUpdated = $this->applyPurchaseReturnLotMovement([
				'lot_id' => $purchaseDetail['lot'],
				'row_material_id' => $purchaseDetail['item_id'],
				'weight' => $purchaseDetail['net_weight'],
				'quantity' => $purchaseDetail['piece'],
				'is_new_detail' => true,
			]);

			if (!$lotUpdated) {
				$this->db->trans_rollback();
				flash()->withError("Failed to update selected lot for purchase return.")->to("purchase_return/index");
				return;
			}

			$purchaseMaterialData = [];
			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					if (!empty($array1[0]) || !empty($array1[1]) || !empty($array1[2]) || !empty($array1[3])) {
						$purchaseMaterialData[] = [
							'user_id' => session('id'),
							'purchase_detail_id' => $purchaseDetailId,
							'row_material_id' => $array1[0] ?? 0,
							'quantity' => $array1[1] ?? 0,
							'rate' => $array1[2] ?? 0,
							'sub_total' => $array1[3] ?? 0,
						];
					}
				}
			}
			if (!empty($purchaseMaterialData)) {
				$this->db->insert_batch('purchase_return_material', $purchaseMaterialData);
			}
		}

		$payment = json_decode($data['paymentArray']);
		$jama_code = $this->db->get_where('setting', array('id' => 1))->row('jama_code');
		$jama = 'JAMA_' . $jama_code;
		$this->db->where('id', 1);
		$this->db->update('setting', array('jama_code' => $jama_code + 1));
		if (!empty($payment)) {
			for ($a = 0; $a < count($payment); $a++) {
				$sequence_code = $this->seq->getNextSequence('jama');
				if (isset($payment[$a]->type)) {
					$this->db->insert('jama', array(
						'sale_id' => 'purchase-return-' . $id,
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
						'jama_code' => $jama,
						'metal_type_id' => $payment[$a]->metal_type_id,
						'creation_date' => date('Y-m-d'),
						'payment_type' => $payment[$a]->payment,
						'bank_id' => $payment[$a]->bank,
						'sequence_code' => $sequence_code
					));
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			flash()->withError("Something went wrong while saving purchase return.")->to("purchase_return/index");
			return;
		}

		$this->db->trans_commit();

		flash()->withSuccess("Insert Successfully.")->to("purchase_return/index");
	}

	public function edit($id)
	{
		checkPrivilege(privilege['purchase_return_edit']);
		$data = $this->db->select('purchase_return.*')->from('purchase_return')->where('id', $id)->get()->row_array();

		if (empty($data)) {
			flash()->withError("Data Not Found")->to("purchase_return/index");
		}

		$data['purchase_detail'] = $this->db->select('purchase_return_detail.*, ' .
			($data['product_type'] === 'item' ? 'item.name' : 'row_material.name') . ' as item_name')
			->from('purchase_return_detail');
		if ($data['product_type'] === 'item') {
			$this->db->join('item', "purchase_return_detail.item_id = item.id AND purchase_return_detail.product_type = 'item'", 'left');
		} else if ($data['product_type'] === 'rowMaterial') {
			$this->db->join('row_material', "purchase_return_detail.item_id = row_material.id AND purchase_return_detail.product_type = 'rowMaterial'", 'left');
		}
		$data['purchase_detail'] = $this->db->where('purchase_id', $id)
			->get()
			->result_array();

		for ($i = 0; $i < count($data['purchase_detail']); $i++) {
			if (!empty($data['purchase_detail'][$i]['raw_material_data'])) {
				$array = explode("|", $data['purchase_detail'][$i]['raw_material_data']);
				$array2 = [];
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$material = $this->db->select('*')->from('purchase_return_material')->where([
						'purchase_detail_id' => $data['purchase_detail'][$i]['id'],
						'row_material_id' => $array1[0],
						'quantity' => $array1[1],
						'rate' => $array1[2],
						'sub_total' => $array1[3]
					])->get()->row_array();
					$array1[4] = isset($material['id']) ? $material['id'] : 0;
					$array2[] = implode(",", $array1);
				}
				$array3 = implode("|", $array2);
				$data['purchase_detail'][$i]['raw_material_data'] = $array3;
			} else {
				$data['purchase_detail'][$i]['raw_material_data'] = "";
			}
		}

		$this->db->select('jama.*, bank.name as bankname'); // Select all fields from both tables (you can specify fields if needed)
		$this->db->from('jama');
		$this->db->join('bank', 'bank.id = jama.bank_id'); // Joining the sale table based on sale_id
		$this->db->where('jama.sale_id', 'purchase-return-' . $id); // Filtering by the sale_id
		$query = $this->db->get(); // Running the query

		$page_data['payment'] = $query->result();
		$page_data['page_title'] = 'Purchase Return';
		$page_data['items'] = $this->db->select('id,name')->from('item')->get()->result_array();
		$page_data['party'] = $this->purchase->fetch_party();
		$page_data['item'] = $this->purchase->fetch_item();
		$page_data['stamp'] = $this->purchase->fetch_stamp();
		$page_data['unit'] = $this->purchase->fetch_unit();
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
		$update['product_type'] = 'item';

		$this->db->trans_begin();

		$this->db->where('id', $id)->update('purchase_return', $update);

		$existingIds = isset($data['rowid']) ? $data['rowid'] : [];
		$allids = isset($data['ids']) ? $data['ids'] : [];
		$idsNotExisting = array_diff($allids, $existingIds);

		if (!empty($idsNotExisting)) {
			$deletedRows = $this->db
				->select('id, item_id, lot, net_weight, piece')
				->from('purchase_return_detail')
				->where('purchase_id', $id)
				->where_in('id', $idsNotExisting)
				->get()
				->result_array();

			foreach ($deletedRows as $deletedRow) {
				$lotUpdated = $this->reversePurchaseReturnLotMovement([
					'lot_id' => $deletedRow['lot'],
					'row_material_id' => $deletedRow['item_id'],
					'weight' => $deletedRow['net_weight'],
					'quantity' => $deletedRow['piece'],
				]);

				if (!$lotUpdated) {
					$this->db->trans_rollback();
					flash()->withError("Failed to restore lot data for deleted purchase return row.")->to("purchase_return/index");
					return;
				}
			}

			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('purchase_return_detail');

			$this->db->where_in('purchase_detail_id', $idsNotExisting);
			$this->db->delete('purchase_return_material');
		}

		for ($i = 0; $i < count($data['item']); $i++) {
			$purchaseDetail['product_type'] = 'item';
			$purchaseDetail['item_id'] = $data['item'][$i];
			$purchaseDetail['lot'] = $data['lot'][$i] ?? '';
			$purchaseDetail['stamp_id'] = $data['stamp'][$i];
			$purchaseDetail['unit_id'] = $data['unit'][$i];
			$purchaseDetail['remark'] = $data['remark'][$i];
			$purchaseDetail['gross_weight'] = $data['gross_weight'][$i];
			$purchaseDetail['less_weight'] = $data['less_weight'][$i];
			$purchaseDetail['net_weight'] = $data['net_weight'][$i];
			$purchaseDetail['pre_touch'] = $data['pre_touch'][$i];
			$purchaseDetail['touch'] = $data['touch'][$i];
			$purchaseDetail['wastage'] = $data['wastage'][$i];
			$purchaseDetail['fine'] = $data['fine'][$i];
			$purchaseDetail['piece'] = $data['piece'][$i];
			$purchaseDetail['rate'] = $data['rate'][$i];
			$purchaseDetail['labour_type'] = $data['labour_type'][$i];
			$purchaseDetail['labour'] = $data['labour'][$i];
			$purchaseDetail['other_amount'] = $data['other_amount'][$i];
			$purchaseDetail['sub_total'] = $data['sub_total'][$i];
			$purchaseDetail['raw_material_data'] = $data['raw-material-data'][$i];

			if ($data['rowid'][$i] == 0) {
				$purchaseDetail['purchase_id'] = $id;
				$this->db->insert('purchase_return_detail', $purchaseDetail);
				$purchaseDetailId = $this->db->insert_id();

				$lotUpdated = $this->applyPurchaseReturnLotMovement([
					'lot_id' => $purchaseDetail['lot'],
					'row_material_id' => $purchaseDetail['item_id'],
					'weight' => $purchaseDetail['net_weight'],
					'quantity' => $purchaseDetail['piece'],
					'is_new_detail' => true,
				]);

				if (!$lotUpdated) {
					$this->db->trans_rollback();
					flash()->withError("Failed to update selected lot for new purchase return row.")->to("purchase_return/index");
					return;
				}
			} else {
				$oldDetail = $this->db
					->select('id, item_id, lot, net_weight, piece')
					->from('purchase_return_detail')
					->where(['id' => $data['rowid'][$i], 'purchase_id' => $id])
					->get()
					->row_array();

				if (!empty($oldDetail)) {
					$lotUpdated = $this->reversePurchaseReturnLotMovement([
						'lot_id' => $oldDetail['lot'],
						'row_material_id' => $oldDetail['item_id'],
						'weight' => $oldDetail['net_weight'],
						'quantity' => $oldDetail['piece'],
					]);

					if (!$lotUpdated) {
						$this->db->trans_rollback();
						flash()->withError("Failed to reverse old lot data for purchase return row.")->to("purchase_return/index");
						return;
					}

					$lotUpdated = $this->applyPurchaseReturnLotMovement([
						'lot_id' => $purchaseDetail['lot'],
						'row_material_id' => $purchaseDetail['item_id'],
						'weight' => $purchaseDetail['net_weight'],
						'quantity' => $purchaseDetail['piece'],
					]);

					if (!$lotUpdated) {
						$this->db->trans_rollback();
						flash()->withError("Failed to apply new lot data for purchase return row.")->to("purchase_return/index");
						return;
					}
				}

				$this->db->where(['id' => $data['rowid'][$i], 'purchase_id' => $id])->update('purchase_return_detail', $purchaseDetail);
			}
			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$purchaseMaterial = [
						'row_material_id' => $array1[0] ?? 0,
						'quantity' => $array1[1] ?? 0,
						'rate' => $array1[2] ?? 0,
						'sub_total' => $array1[3] ?? 0,
					];
					$purchaseMaterialId = (int) ($array1[4] ?? 0);
					if ($purchaseMaterialId === 0) {
						$purchaseMaterial['user_id'] = session('id');
						$purchaseMaterial['purchase_detail_id'] = $purchaseDetailId ?? $data['rowid'][$i];
						$this->db->insert('purchase_return_material', $purchaseMaterial);
					} else {
						$this->db->where(['purchase_detail_id' => $data['rowid'][$i], 'id' => $purchaseMaterialId])->update('purchase_return_material', $purchaseMaterial);
					}
				}
			}
		}

		$payment = json_decode($data['paymentArray']);
		$jama = $this->db->get_where('jama', ['sale_id' => 'purchase-return-' . $id])->row('jama_code');
		if ($jama == '') {
			$jama_code = $this->db->get_where('setting', array('id' => 1))->row('jama_code');
			$jama = 'JAMA_' . $jama_code;
			$this->db->where('id', 1);
			$this->db->update('setting', array('jama_code' => $jama_code + 1));
		}
		$saleId = [];
		if (!empty($payment)) {
			for ($b = 0; $b < count($payment); $b++) {
				if (isset($payment[$b]->saleid)) {
					$saleId[] = $payment[$b]->saleid;
				}
			}
		}
		// Deletion logic
		$deleteQ = $this->db->get_where('jama', ['sale_id' => 'purchase-return-' . $id])->result_array();
		foreach ($deleteQ as $deleteR) {
			// Check if the saleid from the current payment is in the deleteQ results
			if (!in_array($deleteR['id'], $saleId)) {
				$this->db->where('id', $deleteR['id'])->delete('jama');
			}
		}
		if (!empty($payment)) {
			for ($a = 0; $a < count($payment); $a++) {
				if (isset($payment[$a]->type)) {
					$sequence_code = $this->seq->getNextSequence('jama');
					$paymentSaleId = $payment[$a]->saleid ?? '';
					if ($paymentSaleId == '') {
						$this->db->insert('jama', array(
							'sale_id' => 'purchase-return-' . $id,
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
							'jama_code' => $jama,
							'metal_type_id' => $payment[$a]->metal_type_id,
							'creation_date' => date('Y-m-d'),
							'payment_type' => $payment[$a]->payment,
							'bank_id' => $payment[$a]->bank,
							'sequence_code' => $sequence_code
						));
					} else {
						$saleId[] = $paymentSaleId;
						$this->db->where(['id' => $paymentSaleId, 'sale_id' => 'purchase-return-' . $id]);
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
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			flash()->withError("Something went wrong while updating purchase return.")->to("purchase_return/index");
			return;
		}

		$this->db->trans_commit();

		flash()->withSuccess("Update Successfully.")->to("purchase_return/index");
	}

	public function getReport()
	{
		$request = $this->security->xss_clean($this->input->post());
		$report  = $request['group'];
		$url  = $request['url'];
		$table   = [];

		switch ($report) {
			case 'item':
				$table['data'] = $this->purchase->getSalesGroupByItem($request);
				$table['url'] = $url;
				break;
			case 'customer':
				$table['data'] = $this->purchase->getSalesGroupByCustomer($request);
				$table['url'] = $url;
				break;
			case 'bill':
				$table['data'] = $this->purchase->getSalesGroupByBill($request);
				$table['url'] = $url;
				break;
			case 'voucher':
				$table['data'] = $this->purchase->getSalesGroupByVoucher($request);
				$table['url'] = $url;
				break;
			case 'month':
				$table['data'] = $this->purchase->getSalesGroupByMonth($request);
				$table['url'] = $url;
				break;
		}

		$this->load->view("admin/purchase/table/{$report}_ajax", $table);
	}

	public function productType()
	{
		// Validation requirement (kept for reference only):
		// $validation = $this->form_validation;
		// $validation->set_rules('product_type', 'Product Type', 'trim|required|in_list[item,rowMaterial]');
		// if ($this->form_validation->run() == FALSE) {
		// 	$response = ['success' => false, 'error' => validation_errors()];
		// 	echo json_encode($response);
		// 	return;
		// }

		// Directly return item data.
		$data = $this->purchase->fetch_item();

		// Product type condition requirement (kept for reference only):
		// $postData = $this->input->post();
		// if ($postData['product_type'] == 'item') {
		// 	$data = $this->purchase->fetch_item();
		// } else if ($postData['product_type'] == 'rowMaterial') {
		// 	$data = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		// }

		// Data availability condition (kept for reference only):
		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data fetched successfully.', 'data' => []];
		}

		echo json_encode($response);
		return;
	}

	public function getItemLots()
	{
		$this->form_validation->set_rules('item_id', 'Item', 'trim|required|numeric');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(['success' => false, 'message' => validation_errors(), 'data' => []]);
			return;
		}

		$postData = xss_clean($this->input->post());
		$itemId = (int) ($postData['item_id'] ?? 0);
		$lotWiseRmId = (int) ($postData['lot_wise_rm_id'] ?? 0);
		$data = lot_data([
			'row_material_id' => $itemId,
			'lot_wise_rm_id' => $lotWiseRmId,
		]);

		if (!empty($data)) {
			echo json_encode(['success' => true, 'message' => 'Data fetched successfully.', 'data' => $data]);
			return;
		}

		echo json_encode(['success' => false, 'message' => 'Data not found.', 'data' => []]);
		return;
	}

	public function delete($id)
	{
		checkPrivilege(privilege['purchase_delete']);
		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->delete('purchase_return');

		if ($this->db->affected_rows() > 0) {
			$purchase_detail_ids = $this->db->select('id')->from('purchase_return_detail')->where('purchase_id', $id)->get()->result_array();
			if (!empty($purchase_detail_ids)) {
				$ids = array_map(function ($item) {
					return $item['id'];
				}, $purchase_detail_ids);

				$this->db->where_in('purchase_detail_id', $ids);
				$this->db->delete('purchase_return_material');

				$this->db->where_in('id', $ids);
				$this->db->delete('purchase_return_detail');
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				flash()->withError("Deletion failed.")->to("purchase_return");
				return FALSE;
			} else {
				flash()->withSuccess("Deleted successfully.")->to("purchase_return");
				return TRUE;
			}
		} else {
			$this->db->trans_complete();
			flash()->withError("Deletion failed.")->to("purchase_return");
			return FALSE;
		}
	}

	function generate_unique_code()
	{
		$unique_code = '';
		do {
			$unique_code = sprintf('%05d', mt_rand(0, 99999));
			$this->db->where('code', $unique_code);
			$count = $this->db->count_all_results('purchase_return');
		} while ($count > 0);
		return $unique_code;
	}

	function bill($purchase_id = 0)
	{
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$purchase_id = $this->security->xss_clean($purchase_id);
		$purchase_id = $this->db->escape($purchase_id);
		$q = "SELECT 
				SUM(SI.gross_weight) AS gross_weight, 
				SUM(SI.less_weight) AS less_weight, 
				SUM(SI.net_weight) AS net_weight, 
				SUM(SI.fine) AS fine_gold, 
				SUM(SI.sub_total) AS net_amt, 
				SUM(SI.piece) AS pcs,
				S.sequence_code,
				C.`id` AS customer_id,
				C.`name` AS customer_name,
				S.date,
				SI.remark,
				S.id AS purchase_id,
				S.`created_at` AS created_at,
				SI.touch,
				SI.pre_touch,
				I.`name` AS item_name,
				city.name AS city
			FROM purchase_return_detail SI 
			LEFT JOIN purchase_return S ON S.id = SI.purchase_id
			LEFT JOIN item I ON I.id = SI.item_id
			LEFT JOIN customer C ON C.id = S.party_id
			LEFT JOIN city ON city.id = C.city_id
			WHERE S.id = $purchase_id
			GROUP BY SI.item_id
			ORDER BY S.sequence_code";

		$res = $this->db->query($q)->result_array();
		$customerQ = "
        		SELECT 
        		S.sequence_code, 
        		city.name AS city, 
        		C.name AS customer_name,
        		S.date, 
        		S.created_at, 
        		S.id AS purchase_id 
        		FROM purchase_return S 
        		LEFT JOIN customer C ON C.id = S.party_id  
        		LEFT JOIN city ON city.id = C.city_id 
        		WHERE S.id = $purchase_id";
		$customer = $this->db->query($customerQ)->row_array();
		$page_data['url'] = 'purchase_return';
		$page_data['page_name'] = 'admin/print/purchase_bill';
		$page_data['page_title'] = 'Purchase Bill';
		$page_data['bill_data'] = $res;
		$page_data['customer'] = $customer;
		$this->load->view('common', $page_data);
	}

	private function applyPurchaseReturnLotMovement(array $data)
	{
		return $this->updatePurchaseReturnLotColumns($data, 1);
	}

	private function reversePurchaseReturnLotMovement(array $data)
	{
		return $this->updatePurchaseReturnLotColumns($data, -1);
	}

	private function updatePurchaseReturnLotColumns(array $data, $direction)
	{
		$lotId = isset($data['lot_id']) ? (int) $data['lot_id'] : 0;
		$rowMaterialId = isset($data['row_material_id']) ? (int) $data['row_material_id'] : 0;
		$weight = isset($data['weight']) ? (float) $data['weight'] : 0;
		$quantity = isset($data['quantity']) ? (float) $data['quantity'] : 0;

		if ($lotId <= 0 || $rowMaterialId <= 0) {
			return false;
		}

		$direction = ((float) $direction >= 0) ? 1 : -1;

		return lot_management([
			'id' => $lotId,
			'row_material_id' => $rowMaterialId,
			'given_weight_diff' => $direction * $weight,
			'given_quantity_diff' => $direction * $quantity,
			'rem_weight_diff' => -1 * $direction * $weight,
			'rem_quantity_diff' => -1 * $direction * $quantity,
			'update_lot_values' => false,
			'only_four_columns' => true,
			'strict_row_material_match' => true,
		]);
	}
}
