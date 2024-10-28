<?php

defined('BASEPATH') or exit("Direct Script Not Allowed");

class Lot extends CI_Controller
{
	private $dbh;

	const View = "admin/manufacturing/lot/lot";

	function __construct()
	{
		parent::__construct();
		$this->load->library('Joinhelper');
		$this->dbh = $this->joinhelper;
		$this->load->model('manufacturing/Lot_model', 'lot');
		$this->load->model('manufacturing/Lot_report_model', 'lrm');
		$this->load->helper('phase');
	}

	function index($barcode = "")
	{
		checkPrivilege(privilege['lot_creation_add']);
		$page_data['page_title']  = "Lot";
		$page_data['customer']    = $this->dbh->getResultArray('customer');
		$page_data['item']        = $this->dbh->getResultArray('item');
		$page_data['stamp']        = $this->dbh->getResultArray('stamp');
		$page_data['barcode'] = $barcode;
		view(self::View, $page_data);
	}

	public function receiveBarcode()
	{
		$validation = $this->form_validation;
        $validation->set_rules('barcode', 'Barcode', 'trim|required');

        if (!$validation->run()) {
            $response = [ 'success' => false, 'message' => validation_errors()];
            echo json_encode($response);
            return;
        }
        
        $barcode = $this->input->post('barcode');

		if ($this->dbh->isDataExists('receive', ['code' => $barcode])) {
			$this->db->select('*');
			$this->db->from('receive');
			$this->db->where(array('lot_creation' => 'YES', 'code' => $barcode));
			$responce = $this->db->get()->row_array();

			$this->db->select('lot_creation.*,item.name as item_name,sub_item.name as sub_item_name,stamp.name as stamp_name');
			$this->db->from('lot_creation');
			$this->db->join('item', 'lot_creation.item_id = item.id', 'left');
			$this->db->join('sub_item', 'lot_creation.sub_item_id = sub_item.id', 'left');
			$this->db->join('stamp', 'lot_creation.stamp_id = stamp.id', 'left');
			$this->db->where(array('lot_creation.barcode' => $barcode));
			$this->db->order_by('lot_creation.id', 'DESC');
			$lot_creation = $this->db->get()->result_array();
			
			$this->db->select('item_id,sub_item_id,stamp_id');
			$this->db->from('lot_creation');
			$this->db->where('barcode', $barcode);
			$this->db->order_by('id', 'DESC');
			$this->db->limit(1);
			$lastLotCreation = $this->db->get()->row();

			$data = [
				'data' => $responce,
				'lot_creation' => $lot_creation,
				'last_lot_creation' => $lastLotCreation
			];

			if (!empty($data['data']) || !empty($data['lot_creation'])) {
				$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
			} else {
				$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
			}
		} else {
			$response = ['success' => false, 'message' => 'Invalid Barcode.', 'data' => []];
		}
		echo json_encode($response);
		return;
	}

	function edit($id = 0)
	{
		checkPrivilege(privilege['lot_creation_edit']);
		if ($this->dbh->isDataExists('lot_creation', ['id' => $id])) {
			$id                     = $this->security->xss_clean($id);
			$data                   = $this->db->get_where('lot_creation', ['id' => $id])->row_array();
			echo json_encode([
				'status'          => true,
				'data'            => $data,
				'msg'             => 'fetch success',
				'csrf_token_name' => $this->security->get_csrf_token_name(),
				'csrf_hash'       => $this->security->get_csrf_hash()
			]);
		} else {
			echo json_encode(['status' => false, 'msg' => 'Lot not found!']);
		}
	}

	function saveLotCreation()
	{
		checkPrivilege(privilege['lot_creation_add']);
		$data = $this->security->xss_clean($this->input->post());
		if ($this->lot->saveLotCreation($data)) {
			echo "success";
		} else {
			echo "failed";
		}
	}

	function delete($id = 0)
	{
		checkPrivilege(privilege['lot_creation_delete']);
		$id = $this->security->xss_clean($id);
			$this->db->where('id', $id);
		$delete = $this->db->delete('lot_creation');
		if ($delete) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}

	function ajax_getList()
	{
		$data = $this->security->xss_clean($this->input->post());
		$data = $this->lot->datatable_getList($data);
		echo json_encode($data);
	}

	function getLotsByCustomer($customer = 0)
	{
		$customer = $this->security->xss_clean($customer);
		$data     = $this->lot->getLotsByCustomer($customer);
		$arr      = [
			'status'      => TRUE,
			'data'        => $data,
			'customer_id' => $customer
		];
		echo json_encode($arr);
	}

	function getDataByTagAndCustomer($lot = 0, $customer_id = 0)
	{
		$customer_id = $this->security->xss_clean($customer_id);
		$lot         = $this->security->xss_clean($lot);
		$data        = $this->lot->getDataByTagAndCustomer($lot, $customer_id);
		echo json_encode($data);
	}
	function getDataByTagAndCustomerEvent($lot = 0, $customer_id = 0)
	{
		$customer_id = $this->security->xss_clean($customer_id);
		$lot         = $this->security->xss_clean($lot);
		$data        = $this->lot->getDataByTagAndCustomerEvent($lot, $customer_id);
		echo json_encode($data);
	}
	function getDataByTagAndCustomerToEdit($lot = 0, $customer_id = 0)
	{
		$customer_id = $this->security->xss_clean($customer_id);
		$lot         = $this->security->xss_clean($lot);
		$data        = $this->lot->getDataByTagAndCustomerToEdit($lot, $customer_id);
		echo json_encode($data);
	}

	function printTags($tagCode = 0)
	{
		// $this->load->model('QR_model', 'qrQR');
		$tagCode = $this->security->xss_clean($tagCode);
		if ($tagCode > 0) {
			$LC = $this->dbh->fetchJoinedTableWhereRow('lot_creation', ['items_group', 'client_logo', 'item'], ['lot_creation.tag' => $tagCode]);
			// $LC = $this->db->query()
			$this->db->select('RM.id AS rm_id, LCA.pcs AS qty');
			$this->db->where('lot_creation_id', $LC['id']);
			$this->db->join('ad', 'ad.id = LCA.ad_id', 'left');
			$this->db->join('raw_material RM', 'RM.id = ad.raw_material_id', 'left');
			$LCA     = $this->db->get('lot_creation_ad LCA')->result_array();
			$sideArr = ['B' => 0, 'S' => 0, "BU" => 0];
			foreach ($LCA as $lca) {
				if ($lca['rm_id'] == 14) {
					$sideArr['B'] += $lca['qty'];
				} else if ($lca['rm_id'] == 15) {
					$sideArr['S'] += $lca['qty'];
				} else if ($lca['rm_id'] == 31 || $lca['rm_id'] == 32 || $lca['rm_id'] == 18) {
					$sideArr['BU'] += $lca['qty'];
				}
			}
			if (!empty($LC)) {
				$data['LC']    = $LC;
				$data['title'] = "Tag Print";
				if (empty($LC['term'])) {
					$term = 0;
				} else {
					$term = $LC['term'];
				}
				// $data['qrCodePath'] = $this->qr->generateQR(
				// 	$LC['tag'] . '/' . number_format((float)$LC['gross_weight'], 3, '.', '') . '/' . number_format((float)($LC['gross_weight'] - $LC['net_weight']), 3, '.', '') . '/' . number_format((float)$LC['net_weight'], 3, '.', '') . '/' . number_format((float)$LC['amt'], 3, '.', '') . '/' . $LC['items_group_group_name'] . '/' . $term
				// );
				$data['qrCodePath'] = $LC['tag'] . '/' . number_format((float) $LC['gross_weight'], 3, '.', '') . '/' . number_format((float) ($LC['gross_weight'] - $LC['net_weight']), 3, '.', '') . '/' . number_format((float) $LC['net_weight'], 3, '.', '') . '/' . number_format((float) $LC['amt'], 3, '.', '') . '/' . $LC['items_group_group_name'] . '/' . $term;
				$data['sideArr']    = $sideArr;
				$this->load->view('admin/tns/lot/print_lot_data', $data);
			}
		}
	}

	function printTagsAll($barcode)
	{
		// $this->load->model('QR_model', 'qr');
		$barcode = $this->security->xss_clean($barcode);
		if ($barcode > 0) {
			if (strlen($barcode) == 6) {
				$spQ  = "SELECT SP.id FROM second_polish SP
				LEFT JOIN sheeting S ON S.id = SP.sheeting_id
				LEFT JOIN first_polish FP ON FP.id = S.first_polish_id OR FP.id = SP.first_polish_id
				LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
				WHERE FI.code = " . $this->db->escape($barcode);
				$spId = $this->db->query($spQ)->row_array()['id'];
				$LC   = $this->dbh->fetchJoinedTableWhere('lot_creation', ['items_group', 'client_logo', 'item'], ['lot_creation.second_polish_id' => $spId]);
				if (!empty($LC)) {
					$data['LC']    = $LC;
					$data['title'] = "Tag Print";
					$this->load->view('admin/tns/lot/print_lot_data_all', $data);
				}
			} else if (strlen($barcode) == 8) {
				$piQ  = "SELECT PII.id FROM purchase_items PII
				WHERE PII.code = " . $this->db->escape($barcode);
				$piId = $this->db->query($piQ)->row_array()['id'];
				$LC   = $this->dbh->fetchJoinedTableWhere('lot_creation', ['items_group', 'client_logo', 'item'], ['lot_creation.purchase_items_id' => $piId]);
				if (!empty($LC)) {
					$data['LC']    = $LC;
					$data['title'] = "Tag Print";
					$this->load->view('admin/tns/lot/print_lot_data_custom', $data);
				}
			}
		}
	}

	public function printCustomTags()
	{
		$barcodes      = $this->db->escape(xss_clean($this->input->post('checked_tags')));
		$q             = "SELECT LC.*,
				I.name AS item_name
			FROM lot_creation LC
			LEFT JOIN item I ON I.id = LC.item_id
			WHERE FIND_IN_SET(LC.tag,$barcodes) > 0";
		$LC            = $this->db->query($q)->result_array();
		$data['LC']    = $LC;
		$data['title'] = "Tag Print";
		$this->load->view('admin/report/tnx/lot/print_lot_data_custom', $data);
	}
	public function printCustomoldTags()
	{
		$barcodes      = $this->db->escape(xss_clean($this->input->post('checked_old_tags')));
		$q             = "SELECT LC.*,
				I.name AS item_name
			FROM lot_creation LC
			LEFT JOIN item I ON I.id = LC.item_id
			WHERE FIND_IN_SET(LC.tag,$barcodes) > 0";
		$LC            = $this->db->query($q)->result_array();
		$data['LC']    = $LC;
		$data['title'] = "Tag Print";
		$this->load->view('admin/report/tnx/lot/print_lot_data_custome_old_tag', $data);
	}

	public function printCustomoldBarcode()
	{
		$barcodes      =$this->input->post('barcode');
		$q= $this->db->get_where('lot_creation_barcode',array('barcode'=>trim($barcodes)))->row_array();
		$data['LC']    = $q;
		$data['title'] = "Tag Print";
		$this->load->view('admin/report/tnx/lot/print_lot_data_custome_old_barcode', $data);
	}
	
	public function lotCreationAdd(){
	    $validation = $this->form_validation;
        $validation->set_rules('grossWeight', 'Gross Weight', 'trim|required|greater_than[0]')
            ->set_rules('lessWeight', 'Less Weight', 'trim|required|greater_than[0]')
            ->set_rules('netWeight', 'Net Weight', 'trim|required')
            ->set_rules('barcode', 'barcode', 'trim|required');

        if (!$validation->run()) {
            $response = [ 'success' => false, 'message' => validation_errors()];
            echo json_encode($response);
            return;
        }
        
        $post = $this->input->post();
        $isExist = $this->db->get_where('lot_creation_barcode',array('barcode'=>$post['barcode']))->num_rows();
        $data = [];
        $data['gross_weight'] = $post['grossWeight'];
        $data['less_weight'] = $post['lessWeight'];
        $data['net_weight'] = $post['netWeight'];
        
        if($isExist > 0){
            $insert = $this->db->where(array('barcode'=>$post['barcode']))->update('lot_creation_barcode',$data);
            if($insert){
                $response = [ 'success' => true, 'message' => "Data Update SuccessFully.."];
            }else{
                $response = [ 'success' => false, 'message' => "Data Update Failed.."];
            }
        }else{
            $data['barcode'] = $post['barcode'];
            $data['creation_date'] = date('Y-m-d');
            $insert = $this->db->insert('lot_creation_barcode',$data);
            if($insert){
                $response = [ 'success' => true, 'message' => "Data Add SuccessFully.."];
            }else{
                $response = [ 'success' => false, 'message' => "Data Add Failed.."];
            }
        }
        
        echo json_encode($response);
        return;
	}
	
	public function lotCreationBarcode()
	{
		$validation = $this->form_validation;
        $validation->set_rules('barcode', 'Barcode', 'trim|required');

        if (!$validation->run()) {
            $response = [ 'success' => false, 'message' => validation_errors()];
            echo json_encode($response);
            return;
        }
        
        $post = $this->input->post();
        $data = $this->db->get_where('lot_creation_barcode',array('barcode'=>$post['barcode']))->row_array();

		if (!empty($data)) {
			$response = ['success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data];
		} else {
			$response = ['success' => false, 'message' => 'Data Not Found.', 'data' => []];
		}
		echo json_encode($response);
		return;
	}
}
