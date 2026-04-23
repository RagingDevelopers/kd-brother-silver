<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{
	public $form_validation, $input, $db, $purchase, $seq, $jama;
	const View = "admin/purchase/report";
	const Create = "admin/purchase/create";
	public function __construct()
	{
		parent::__construct();
		check_login();
		$this->load->model('purchase_model', "purchase");
		$this->load->model('Sequence_model', 'seq');
		$this->load->model('payment/Jama_model', 'jama');
	}

	public function index()
	{
		checkPrivilege(privilege['purchase_view']);
		$page_data['page_title'] = 'Purchase Report';
		$page_data['party'] = $this->purchase->fetch_party();
		$page_data['items'] = $this->purchase->fetch_item();
		return view(self::View, $page_data);
	}

	public function create()
	{
		checkPrivilege(privilege['purchase_add']);
		$page_data['page_title'] = 'Purchase';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
		$page_data['party'] = $this->purchase->fetch_party();
		$page_data['item'] = $this->purchase->fetch_item();
		$page_data['stamp'] = $this->purchase->fetch_stamp();
		$page_data['unit'] = $this->purchase->fetch_unit();
		$page_data['bank'] = $this->jama->bank();
		$page_data['party'] = $this->jama->party();
		$page_data['metal_type'] = $this->jama->metal_type();
		return view(self::Create, $page_data);
	}

	// public function store()
	// {
	// 	$validation = $this->form_validation;
	// 	$validation->set_rules('party_id', 'Party', 'trim|required|numeric');
	// 	if (!$validation->run()) {
	// 		return flash()->withError(validation_errors())->back();
	// 	}

	// 	// $batchData = $lot_wise_rm = $purchaseDetail = [];
	// 	// $data = xss_clean($this->input->post());
	// 	// // echo "<pre>";
	// 	// // print_r($data);exit;
	// 	// $insert['date'] = $data['date'];
	// 	// $insert['party_id'] = $data['party_id'];
	// 	// $insert['code'] = 'P' . $this->generate_unique_code();
	// 	// $insert['sequence_code'] = $this->seq->getNextSequence('purchase');
	// 	// $insert['user_id'] = session('id');
	// 	// $insert['product_type'] = $data['product_type'];
	// 	// $batchData[] = $insert;
	// 	// $this->db->insert_batch('purchase', $batchData);
	// 	// $id = $this->db->insert_id();
	// 	// new code add
	// 	$lot_wise_rm = $purchaseDetail = [];
	// 	$data = xss_clean($this->input->post());

	// 	$insert = [
	// 		'date' => $data['date'],
	// 		'party_id' => $data['party_id'],
	// 		'code' => 'P' . $this->generate_unique_code(),
	// 		'sequence_code' => $this->seq->getNextSequence('purchase'),
	// 		'user_id' => session('id'),
	// 		'product_type' => $data['product_type']
	// 	];

	// 	$this->db->insert('purchase', $insert);
	// 	$id = $this->db->insert_id();
	// 	// new code end

	// 	if (!$id) {
	// 		flash()->withError("Failed to insert purchase record.")->to("purchase/index");
	// 	}

	// 	for ($i = 0; $i < count($data['item']); $i++) {

	// 		// $customer = $this->db->where(['customer_id' => $data['party_id'], 'item_id' => $data['item'][$i]])->get('customer_item')->num_rows();
	// 		// $customer_details = $new = array();
	// 		// if ($customer == 0 && $data['product_type'] == 'item') {
	// 		// 	$customer_details['item_id'] = $data['item'][$i] ?? "";
	// 		// 	$customer_details['extra_touch'] = $data['touch'][$i] ?? "";
	// 		// 	$customer_details['wastage'] = $data['wastage'][$i] ?? "";
	// 		// 	$customer_details['label'] = $data['labour_type'][$i] ?? "";
	// 		// 	$customer_details['rate'] = $data['labour'][$i] ?? "";
	// 		// 	$customer_details['sub_total'] = $data['touch'][$i] ?? "" + $data['wastage'][$i] ?? "";
	// 		// 	$customer_details['customer_id'] = $data['party_id'];
	// 		// 	$batchData[] = $customer_details;
	// 		// 	$this->db->insert_batch('customer_item', $batchData);
	// 		// }
	// 		// new code add
	// 		$customer = $this->db
	// 			->where([
	// 				'customer_id' => $data['party_id'],
	// 				'item_id' => $data['item'][$i]
	// 			])
	// 			->get('customer_item')
	// 			->num_rows();

	// 		if ($customer == 0 && $data['product_type'] == 'item') {
	// 			$customer_details = [
	// 				'item_id' => $data['item'][$i] ?? "",
	// 				'extra_touch' => $data['touch'][$i] ?? "",
	// 				'wastage' => $data['wastage'][$i] ?? "",
	// 				'label' => $data['labour_type'][$i] ?? "",
	// 				'rate' => $data['labour'][$i] ?? "",
	// 				'sub_total' => ((float)($data['touch'][$i] ?? 0) + (float)($data['wastage'][$i] ?? 0)),
	// 				'customer_id' => $data['party_id']
	// 			];

	// 			$this->db->insert('customer_item', $customer_details);
	// 		}
	// 		// new code end

	// 		$purchaseDetail['purchase_id'] = $id;
	// 		$purchaseDetail['user_id'] = $lot_wise_rm['user_id'] = session('id');
	// 		$purchaseDetail['product_type'] = $data['product_type'];
	// 		$purchaseDetail['item_id'] = $lot_wise_rm['row_material_id'] = $data['item'][$i];
	// 		$purchaseDetail['stamp_id'] = $data['stamp'][$i];
	// 		$purchaseDetail['unit_id'] = $data['unit'][$i];
	// 		$purchaseDetail['remark'] = $data['remark'][$i];
	// 		$purchaseDetail['gross_weight'] =  $data['gross_weight'][$i];
	// 		$purchaseDetail['less_weight'] = $data['less_weight'][$i];
	// 		$purchaseDetail['net_weight'] = $lot_wise_rm['rem_weight'] = $lot_wise_rm['weight'] = $data['net_weight'][$i];
	// 		$purchaseDetail['touch'] = $lot_wise_rm['touch'] = $data['touch'][$i];
	// 		$purchaseDetail['pre_touch'] = $data['pre_touch'][$i];
	// 		$purchaseDetail['wastage'] = $data['wastage'][$i];
	// 		$purchaseDetail['fine'] = $data['fine'][$i];
	// 		$purchaseDetail['piece'] = $lot_wise_rm['rem_quantity'] = $lot_wise_rm['quantity'] = $data['piece'][$i];
	// 		$purchaseDetail['rate'] = $data['rate'][$i];
	// 		$purchaseDetail['labour_type'] = $data['labour_type'][$i];
	// 		$purchaseDetail['labour'] = $data['labour'][$i];
	// 		$purchaseDetail['other_amount'] = $data['other_amount'][$i];
	// 		$purchaseDetail['sub_total'] = $data['sub_total'][$i];
	// 		$purchaseDetail['raw_material_data'] = $data['raw-material-data'][$i];
	// 		$this->db->insert('purchase_detail', $purchaseDetail);
	// 		$purchaseDetailId = $lot_wise_rm['purchase_detail_id'] = $this->db->insert_id();

	// 		if ($data['product_type'] == "rowMaterial") {
	// 			$lot_wise_rm['code'] = "PR_" . session('id') . "_" . $purchaseDetailId;
	// 			$lot_wise_rm['creation_date'] = date('Y-m-d');
	// 			$lot_wise_rm['type'] = "PURCHASE";
	// 			$this->db->insert('lot_wise_rm', $lot_wise_rm);
	// 		}

	// 		$array = explode("|", $data['raw-material-data'][$i]);
	// 		if (!empty($data['raw-material-data'][$i])) {
	// 			// for ($a = 0; $a < count($array); $a++) {
	// 			// 	$purchaseMaterialData = [];
	// 			// 	$array1 = explode(",", $array[$a]);
	// 			// 	if (!empty($array1[0]) || !empty($array[1]) || !empty($array[2]) || !empty($array[3])) {
	// 			// 		$purchaseMaterial['user_id'] = session('id');
	// 			// 		$purchaseMaterial['purchase_detail_id'] = $purchaseDetailId;
	// 			// 		$purchaseMaterial['row_material_id'] = $array1[0];
	// 			// 		$purchaseMaterial['quantity'] = $array1[1];
	// 			// 		$purchaseMaterial['rate'] = $array1[2];
	// 			// 		$purchaseMaterial['sub_total'] = $array1[3];
	// 			// 		$purchaseMaterialData[] = $purchaseMaterial;
	// 			// 	}
	// 			// }
	// 			// new code add
	// 			$purchaseMaterialData = [];
	// 			$array = explode("|", $data['raw-material-data'][$i]);

	// 			if (!empty($data['raw-material-data'][$i])) {
	// 				for ($a = 0; $a < count($array); $a++) {
	// 					$array1 = explode(",", $array[$a]);

	// 					if (!empty($array1[0]) || !empty($array1[1]) || !empty($array1[2]) || !empty($array1[3])) {
	// 						$purchaseMaterial = [];
	// 						$purchaseMaterial['user_id'] = session('id');
	// 						$purchaseMaterial['purchase_detail_id'] = $purchaseDetailId;
	// 						$purchaseMaterial['row_material_id'] = $array1[0];
	// 						$purchaseMaterial['quantity'] = $array1[1];
	// 						$purchaseMaterial['rate'] = $array1[2];
	// 						$purchaseMaterial['sub_total'] = $array1[3];
	// 						$purchaseMaterialData[] = $purchaseMaterial;
	// 					}
	// 				}
	// 			}

	// 			if (!empty($purchaseMaterialData)) {
	// 				$this->db->insert_batch('purchase_material', $purchaseMaterialData);
	// 			}
	// 			// new code end
	// 		}
	// 		if (!empty($purchaseMaterialData)) {
	// 			$this->db->insert_batch('purchase_material', $purchaseMaterialData);
	// 		}
	// 	}

	// 	$payment = json_decode($data['paymentArray']);
	// 	$jama_code = $this->db->get_where('setting', array('id' => 1))->row('jama_code');
	// 	$jama = 'JAMA_' . $jama_code;
	// 	$this->db->where('id', 1);
	// 	$this->db->update('setting', array('jama_code' => $jama_code + 1));
	// 	for ($a = 0; $a < count($payment); $a++) {
	// 		$sequence_code = $this->seq->getNextSequence('jama');
	// 		if (isset($payment[$a]->type)) {
	// 			$insert = $this->db->insert('jama', array(
	// 				'sale_id' => 'purchase-' . $id,
	// 				'date' => $data['date'],
	// 				'customer_id' => $data['party_id'],
	// 				'type' => $payment[$a]->type,
	// 				'mode' => $payment[$a]->mode,
	// 				'gross' => $payment[$a]->gross,
	// 				'purity' => $payment[$a]->purity,
	// 				'wb' => $payment[$a]->wb,
	// 				'fine' => $payment[$a]->fine,
	// 				'rate' => $payment[$a]->rate,
	// 				'amount' => $payment[$a]->amount,
	// 				'remark' => $payment[$a]->remark,
	// 				'jama_code' => '',
	// 				'metal_type_id' => $payment[$a]->metal_type_id,
	// 				'creation_date' => date('Y-m-d'),
	// 				'payment_type' => $payment[$a]->payment,
	// 				'jama_code' => $jama,
	// 				'bank_id' => $payment[$a]->bank,
	// 				'sequence_code' => $sequence_code
	// 			));
	// 		}
	// 	}
	// 	flash()->withSuccess("Insert Successfully.")->to("purchase/index");
	// }

	public function store()
	{
		$validation = $this->form_validation;
		$validation->set_rules('party_id', 'Party', 'trim|required|numeric');

		if (!$validation->run()) {
			return flash()->withError(validation_errors())->back();
		}

		$data = xss_clean($this->input->post());
		$productType = 'item';

		$this->db->trans_start();

		$insert = [
			'date' => $data['date'],
			'party_id' => $data['party_id'],
			'code' => 'P' . $this->generate_unique_code(),
			'sequence_code' => $this->seq->getNextSequence('purchase'),
			'user_id' => session('id'),
			'product_type' => $productType
		];

		$this->db->insert('purchase', $insert);
		$id = $this->db->insert_id();

		if (!$id) {
			$this->db->trans_complete();
			flash()->withError("Failed to insert purchase record.")->to("purchase/index");
			return;
		}

		for ($i = 0; $i < count($data['item']); $i++) {

			$lot_wise_rm = [];
			$purchaseDetail = [];

			$customer = $this->db
				->where([
					'customer_id' => $data['party_id'],
					'item_id' => $data['item'][$i]
				])
				->get('customer_item')
				->num_rows();

			if ($customer == 0) {
				$customer_details = [
					'item_id' => $data['item'][$i] ?? "",
					'extra_touch' => $data['touch'][$i] ?? "",
					'wastage' => $data['wastage'][$i] ?? "",
					'label' => $data['labour_type'][$i] ?? "",
					'rate' => $data['labour'][$i] ?? "",
					'sub_total' => (float)($data['touch'][$i] ?? 0) + (float)($data['wastage'][$i] ?? 0),
					'customer_id' => $data['party_id']
				];

				$this->db->insert('customer_item', $customer_details);
			}

			$purchaseDetail['purchase_id'] = $id;
			$purchaseDetail['user_id'] = session('id');
			$purchaseDetail['product_type'] = $productType;
			$purchaseDetail['item_id'] = $data['item'][$i];
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

			$this->db->insert('purchase_detail', $purchaseDetail);
			$purchaseDetailId = $this->db->insert_id();

			// if ($data['product_type'] == "rowMaterial") {
				$lot_wise_rm['user_id'] = session('id');
				$lot_wise_rm['row_material_id'] = $data['item'][$i];
				$lot_wise_rm['weight'] = $data['net_weight'][$i];
				$lot_wise_rm['rem_weight'] = $data['net_weight'][$i];
				$lot_wise_rm['touch'] = $data['touch'][$i];
				$lot_wise_rm['quantity'] = $data['piece'][$i];
				$lot_wise_rm['rem_quantity'] = $data['piece'][$i];
				$lot_wise_rm['purchase_detail_id'] = $purchaseDetailId;
				$lot_wise_rm['code'] = "PR_" . session('id') . "_" . $purchaseDetailId;
				$lot_wise_rm['creation_date'] = date('Y-m-d');
				$lot_wise_rm['type'] = "PURCHASE";

				$this->db->insert('lot_wise_rm', $lot_wise_rm);
			// }

			$purchaseMaterialData = [];

			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);

				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);

					if (
						isset($array1[0], $array1[1], $array1[2], $array1[3]) &&
						($array1[0] !== '' || $array1[1] !== '' || $array1[2] !== '' || $array1[3] !== '')
					) {
						$purchaseMaterialData[] = [
							'user_id' => session('id'),
							'purchase_detail_id' => $purchaseDetailId,
							'row_material_id' => $array1[0],
							'quantity' => $array1[1],
							'rate' => $array1[2],
							'sub_total' => $array1[3]
						];
					}
				}
			}

			if (!empty($purchaseMaterialData)) {
				$this->db->insert_batch('purchase_material', $purchaseMaterialData);
			}
		}

		$payment = json_decode($data['paymentArray']);

		$jama_code = $this->db->get_where('setting', ['id' => 1])->row('jama_code');
		$jama = 'JAMA_' . $jama_code;

		$this->db->where('id', 1);
		$this->db->update('setting', ['jama_code' => $jama_code + 1]);

		if (!empty($payment)) {
			for ($a = 0; $a < count($payment); $a++) {
				if (isset($payment[$a]->type)) {
					$sequence_code = $this->seq->getNextSequence('jama');

					$this->db->insert('jama', [
						'sale_id' => 'purchase-' . $id,
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
						'creation_date' => date('Y-m-d'),
						'payment_type' => $payment[$a]->payment,
						'jama_code' => $jama,
						'bank_id' => $payment[$a]->bank,
						'sequence_code' => $sequence_code
					]);
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			flash()->withError("Something went wrong while saving purchase.")->to("purchase/index");
			return;
		}

		flash()->withSuccess("Insert Successfully.")->to("purchase/index");
	}

	public function edit($id)
	{
		checkPrivilege(privilege['purchase_edit']);
		$data = $this->db->select('purchase.*')->from('purchase')->where('id', $id)->get()->row_array();
		if (empty($data)) {
			flash()->withError("Data Not Found")->to("purchase/index");
		}

		$data['purchase_detail'] = $this->db->select('purchase_detail.*, item.name as item_name')
			->from('purchase_detail')
			->join('item', "purchase_detail.item_id = item.id", 'left');
		$data['purchase_detail'] = $this->db->where('purchase_id', $id)
			->get()
			->result_array();

		for ($i = 0; $i < count($data['purchase_detail']); $i++) {
			if (!empty($data['purchase_detail'][$i]['raw_material_data'])) {
				$array = explode("|", $data['purchase_detail'][$i]['raw_material_data']);
				$array2 = [];
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$material = $this->db->select('*')->from('purchase_material')->where([
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
		$this->db->where('jama.sale_id', 'purchase-' . $id); // Filtering by the sale_id
		$query = $this->db->get(); // Running the query

		$page_data['payment'] = $query->result();
		$page_data['page_title'] = 'Purchase';
		$page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
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
		$productType = 'item';
		$update['date'] = $data['date'];
		$update['party_id'] = $data['party_id'];
		$update['product_type'] = $productType;
		$this->db->where('id', $id)->update('purchase', $update);

		$existingIds = isset($data['rowid']) ? $data['rowid'] : [];
		$allids = isset($data['ids']) ? $data['ids'] : [];
		$idsNotExisting = array_diff($allids, $existingIds);

		if (!empty($idsNotExisting)) {
			$this->db->where_in('id', $idsNotExisting);
			$this->db->delete('purchase_detail');

			$this->db->where_in('purchase_detail_id', $idsNotExisting);
			$this->db->delete('purchase_material');

			$this->db->where_in('purchase_detail_id', $idsNotExisting);
			$this->db->delete('lot_wise_rm');
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
				$this->db->insert('customer_item', $customer_details);
			}

			$purchaseDetail['product_type'] = $productType;
			$purchaseDetail['item_id'] = $lot_wise_rm['row_material_id'] =  $data['item'][$i];
			$purchaseDetail['stamp_id'] = $data['stamp'][$i];
			$purchaseDetail['unit_id'] = $data['unit'][$i];
			$purchaseDetail['remark'] = $data['remark'][$i];
			$purchaseDetail['gross_weight'] = $data['gross_weight'][$i];
			$purchaseDetail['less_weight'] = $data['less_weight'][$i];
			$purchaseDetail['net_weight'] = $lot_wise_rm['rem_weight'] = $lot_wise_rm['weight'] = $data['net_weight'][$i];
			$purchaseDetail['touch'] = $lot_wise_rm['touch'] = $data['touch'][$i];
			$purchaseDetail['pre_touch'] = $data['pre_touch'][$i];
			$purchaseDetail['wastage'] = $data['wastage'][$i];
			$purchaseDetail['fine'] = $data['fine'][$i];
			$purchaseDetail['piece'] = $lot_wise_rm['rem_quantity'] = $lot_wise_rm['quantity'] = $data['piece'][$i];
			$purchaseDetail['rate'] = $data['rate'][$i];
			$purchaseDetail['labour_type'] = $data['labour_type'][$i];
			$purchaseDetail['labour'] = $data['labour'][$i];
			$purchaseDetail['other_amount'] = $data['other_amount'][$i];
			$purchaseDetail['sub_total'] = $data['sub_total'][$i];
			$purchaseDetail['raw_material_data'] = $data['raw-material-data'][$i];

			$purchaseDetailId = $data['rowid'][$i];
			if ($data['rowid'][$i] == 0) {
				$purchaseDetail['purchase_id'] = $id;
				$this->db->insert('purchase_detail', $purchaseDetail);
				$purchaseDetailId = $this->db->insert_id();
				// if ($productType == "rowMaterial") {
					$lot_wise_rm['purchase_detail_id'] = $purchaseDetailId;
					$lot_wise_rm['code'] = "PR_" . session('id') . "_" . $purchaseDetailId;
					$lot_wise_rm['creation_date'] = date('Y-m-d');
					$lot_wise_rm['type'] = "PURCHASE";
					$this->db->insert('lot_wise_rm', $lot_wise_rm);
				// }
			} else {
				$purchaseData = $this->db->get_where('purchase_detail', ['id' => $purchaseDetailId, 'purchase_id' => $id])->row_array();
				// if ($productType == "rowMaterial") {
					$weight = $purchaseDetail['net_weight'] - $purchaseData['net_weight'];
					$quantity = $purchaseDetail['piece'] - $purchaseData['piece'];
					if ($data['net_weight'][$i] != $purchaseData['net_weight']) {
						$this->db->where(array('purchase_detail_id' => $purchaseDetailId, 'type' => 'PURCHASE'))
							->set('weight', $purchaseDetail['net_weight'])
							->set('quantity', $purchaseDetail['piece'])
							->set('rem_weight', 'rem_weight +' . $weight, false);
						$this->db->update('lot_wise_rm');
					}
					if ($data['piece'][$i] != $purchaseData['piece']) {
						$this->db->where(array('purchase_detail_id' => $purchaseDetailId, 'type' => 'PURCHASE'))
							->set('weight', $purchaseDetail['net_weight'])
							->set('quantity', $purchaseDetail['piece'])
							->set('rem_quantity', 'rem_quantity +' . $quantity, false);
						$this->db->update('lot_wise_rm');
					}
				// }
				$this->db->where(['id' => $purchaseDetailId, 'purchase_id' => $id])->update('purchase_detail', $purchaseDetail);
			}

			if (!empty($data['raw-material-data'][$i])) {
				$array = explode("|", $data['raw-material-data'][$i]);
				for ($a = 0; $a < count($array); $a++) {
					$array1 = explode(",", $array[$a]);
					$purchaseMaterial['row_material_id'] = $array1[0];
					$purchaseMaterial['quantity'] = $array1[1];
					$purchaseMaterial['rate'] = $array1[2];
					$purchaseMaterial['sub_total'] = $array1[3];
					if ($array1[4] == 0) {
						$purchaseMaterial['purchase_detail_id'] = $purchaseDetailId ?? $data['rowid'][$i];
						$this->db->insert('purchase_material', $purchaseMaterial);
					} else {
						$this->db->where(['purchase_detail_id' => $data['rowid'][$i], 'id' => $array1[4]])->update('purchase_material', $purchaseMaterial);
					}
				}
			}
		}

		$payment = json_decode($data['paymentArray']);
		$jama = $this->db->get_where('jama', ['sale_id' => 'purchase-' . $id])->row('jama_code');
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
		$deleteQ = $this->db->get_where('jama', ['sale_id' => 'purchase-' . $id])->result_array();
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
						'sale_id' => 'purchase-' . $id,
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
					$this->db->where(['id' => $payment[$a]->saleid, 'sale_id' => 'purchase-' . $id]);
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

		flash()->withSuccess("Update Successfully.")->to("purchase/index");
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

	public function delete($id)
	{
		checkPrivilege(privilege['purchase_delete']);
		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->delete('purchase');

		if ($this->db->affected_rows() > 0) {
			$purchase_detail_ids = $this->db->select('id')->from('purchase_detail')->where('purchase_id', $id)->get()->result_array();
			if (!empty($purchase_detail_ids)) {
				$ids = array_map(function ($item) {
					return $item['id'];
				}, $purchase_detail_ids);

				$this->db->where_in('purchase_detail_id', $ids);
				$this->db->delete('purchase_material');

				$this->db->where_in('id', $ids);
				$this->db->delete('purchase_detail');
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				flash()->withError("Deletion failed.")->to("purchase");
				return FALSE;
			} else {
				flash()->withSuccess("Deleted successfully.")->to("purchase");
				return TRUE;
			}
		} else {
			$this->db->trans_complete();
			flash()->withError("Deletion failed.")->to("purchase");
			return FALSE;
		}
	}

	function generate_unique_code()
	{
		$unique_code = '';
		do {
			$unique_code = sprintf('%05d', mt_rand(0, 99999));
			$this->db->where('code', $unique_code);
			$count = $this->db->count_all_results('purchase');
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
			FROM purchase_detail SI 
			LEFT JOIN purchase S ON S.id = SI.purchase_id
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
        		FROM purchase S 
        		LEFT JOIN customer C ON C.id = S.party_id  
        		LEFT JOIN city ON city.id = C.city_id 
        		WHERE S.id = $purchase_id";
		$customer = $this->db->query($customerQ)->row_array();
		$page_data['url'] = 'purchase';
		$page_data['page_name'] = 'admin/print/purchase_bill';
		$page_data['page_title'] = 'Purchase Bill';
		$page_data['bill_data'] = $res;
		$page_data['customer'] = $customer;
		$this->load->view('common', $page_data);
	}
}
