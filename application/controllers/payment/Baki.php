<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baki extends CI_Controller
{
    const View = "admin/payment/baki";
	function __construct()
	{
		parent::__construct();
		$this->load->model('payment/Baki_model', 'baki');
		check_login();
	}

	public function index()
	{
		checkPrivilege(privilege['baki_add']);
		$page_data['page_title'] = 'Baki';
		$page_data['bank'] = $this->baki->bank();
		$page_data['party'] = $this->baki->party();
		$page_data['metal_type'] = $this->baki->metal_type();
		// $page_data['page_name'] = 'baki/baki.php';
		// $this->load->view('admin/common.php', $page_data);
        return view(self::View,$page_data);
	}
	public function edit($param,$param1){
	    $num_rows = $this->db->get_where('baki',array('baki_code'=>$param))->num_rows();
	    if($num_rows==0){
			redirect(base_url('payment/baki_report'), 'refresh');
	    }else{
	        
		$page_data['page_title'] = 'baki';
		$page_data['baki_code']= $param;
		$page_data['party_id']= $param1;
		$page_data['bank'] = $this->baki->bank();
		$page_data['party'] = $this->baki->party();
		$page_data['metal_type'] = $this->baki->metal_type();
		return view(self::View,$page_data);
	    }
	}
	public function delete_baki_code($param){
	    checkPrivilege(privilege['baki_delete']);
	    $num_rows = $this->db->get_where('baki',array('baki_code'=>$param))->num_rows();
	    if($num_rows==0){
			redirect(base_url('payment/baki_report'), 'refresh');
	    }else{
	        $this->db->where('baki_code',$param);
	        $this->db->delete('baki');
	        redirect(base_url('payment/baki_report'), 'refresh');
	    }
	}
	public function delete_row(){
	    checkPrivilege(privilege['baki_delete']);
	    $id = $this->security->xss_clean($this->input->post('baki_id'));
	    $this->db->where('id',$id);
	    $delete = $this->db->delete('baki');
	    if ($delete == TRUE) {
			$res = [
				'status' => true,
			];
		} else {
			$res = [
				'status' => false,
			];
		}
		
    	echo json_encode($res);
	}
	public function baki_data_ajax_update(){
		$condata = $this->security->xss_clean($this->input->post());
		$this->db->where('id',$condata['baki_id']);
        $update = $this->db->update('baki',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
        ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
        'amount'=>$condata['amount'],'remark'=>$condata['remark'],'metal_type_id'=>$condata['metal_type_id']));
		
		if ($update == TRUE) {
			$res = [
				'status' => true,
				'message' => "baki Updated Successfully",
				'baki_code'=>$condata['code'],
			];
		} else {
			$res = [
				'status' => false,
				'message' => "baki Updated Failed",
			];
		}
	
	    echo json_encode($res);
	
	}
	public function baki_data_update(){
			$condata = $this->security->xss_clean($this->input->post());
			$this->db->where('id',$condata['baki_id']);
            $update = $this->db->update('baki',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark']));
			
			if ($update == TRUE) {
				$res = [
					'status' => true,
					'message' => "baki Updated Successfully",
				];
			} else {
				$res = [
					'status' => false,
					'message' => "baki Updated Failed",
				];
			}
		
    	echo json_encode($res);
	}
	public function baki_data(){
			$condata = $this->security->xss_clean($this->input->post());
			if($condata['code']=='0'){
    			$baki_code = $this->db->get_where('setting',array('id'=>1))->row('baki_code');
			    $condata['baki_code'] = 'BAKI_'.$baki_code;
    			$this->db->where('id', 1);
    			$this->db->update('setting', array('baki_code' => $baki_code + 1));
			}else{
			    if($condata['party_id']==$condata['check_party_id']){
		            $condata['baki_code'] = $condata['code'];
			    }else{
			        $baki_code = $this->db->get_where('setting',array('id'=>1))->row('baki_code');
    			    $condata['baki_code'] = 'BAKI_'.$baki_code;
        			$this->db->where('id', 1);
        			$this->db->update('setting', array('baki_code' => $baki_code + 1)); 
			    }
			}
            $insert = $this->db->insert('baki',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark'],'baki_code'=>$condata['baki_code'],'metal_type_id'=>$condata['metal_type_id'],'creation_date'=>date('Y-m-d')));
			

			if ($insert == TRUE) {
				$res = [
					'status' => true,
					'message' => "baki Successfully",
					'baki_code'=>$condata['baki_code'],
					'party_id'=>$condata['party_id'],
				];
			} else {
				$res = [
					'status' => false,
					'message' => "baki Adding Failed"
				];
			}
		
    	echo json_encode($res);
	}
	public function baki_edit_row(){
	    	$baki_id = $this->security->xss_clean($this->input->post('baki_id'));
	    	$row_data = $this->db->get_where('baki',array('id'=>$baki_id))->row_array();
	    	if(!empty($row_data)){
	    	    $res = [
					'status' => true,
					'data' => $row_data,
				];
	    	}else{
	    	    $res = [
					'status' => false,
					'data' => "baki Adding Failed",
				];
	    	}
	    	echo json_encode($res);
	}

	public function insert()
	{
		checkPrivilege(privilege['payment_add']);
		$this->form_validation->set_rules('party_id', 'Party', 'trim|required|xss_clean');
		$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('bank_id', 'Bank', 'trim|required|xss_clean');
		$this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required|xss_clean|numeric');
// 		$this->form_validation->set_rules('metal_rate', 'Metal Rate', 'trim|required|xss_clean|numeric');
// 		$this->form_validation->set_rules('fine_silver', 'Fine Silver', 'trim|required|xss_clean|numeric');

		try {
			if ($this->form_validation->run() == false) {
				$message = array('message' => validation_errors(), 'class' => 'danger');
				$this->session->set_flashdata('flash_message', $message);
				redirect(base_url('admin/payment/payment'), 'refresh');
			} else {
				$condata = $this->security->xss_clean($this->input->post());
				$insertarray = [
					'date' => $this->security->xss_clean($this->input->post('date')),
					'payment_type' => $this->security->xss_clean($this->input->post('payment_type')),
					'party_id' => $this->security->xss_clean($this->input->post('party_id')),
					'bank_id' => $this->security->xss_clean($this->input->post('bank_id')),
					'total_amount' => $this->security->xss_clean($this->input->post('total_amount')),
					'metal_rate' => $this->security->xss_clean($this->input->post('metal_rate')),
					'fine_silver' => $this->security->xss_clean($this->input->post('fine_silver')),
					'remark' => $this->security->xss_clean($this->input->post('remark')),
					'admin_id' => $this->session->userdata('admin_ID'),
					'department_id' => $this->session->userdata('department_id'),
				];

				$result = $this->payment->insert($insertarray);

				if ($result == TRUE) {
					$message = array('message' => "Payment Added Successfully !!", 'class' => 'success');
					$this->session->set_flashdata('flash_message', $message);
					redirect(base_url('admin/payment/payment_report'), 'refresh');
				} else {
					$message = array('message' => "Payment Adding Failed !!", 'class' => 'danger');
					$this->session->set_flashdata('flash_message', $message);
					redirect(base_url('admin/payment/payment'), 'refresh');
				}
			}
		} catch (Exception $e) {
			$message = array('message' => $e->getMessage(), 'class' => 'danger');
			$this->session->set_flashdata('flash_message', $message);
			redirect(base_url('admin/payment/payment', 'refresh'));
		}
	}
	public function baki_edit_ajax(){
			$condata = $this->security->xss_clean($this->input->post());
			$this->db->where('id',$condata['baki_id']);
            $update = $this->db->update('baki',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark']));
			
			if ($update == TRUE) {
				$res = [
					'status' => true,
					'message' => "baki Updated Successfully",
				];
			} else {
				$res = [
					'status' => false,
					'message' => "baki Updated Failed"
				];
			}
		
    	echo json_encode($res);
	}
	public function report(){

		$postData = $this->security->xss_clean($this->input->post());

		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value 
		$admin_id = $this->session->userdata('admin_ID');
        $baki_code = $postData['baki_code'];
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (customer.name like '%" . $searchValue . "%') ";
		}


		$this->db->select('baki.*');
		$this->db->from('baki');
		$this->db->group_by('baki.id');
		$this->db->order_by('baki.id', 'desc');
		$records = $this->db->get();
		$totalRecords = $records->num_rows();

		## Total number of record with filtering
		$this->db->select('baki.*,customer.name as pname');
		$this->db->from('baki');
		$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
		if ($searchQuery != '')
			$this->db->where($searchQuery);


		$this->db->group_by('baki.id');
		$this->db->order_by('baki.id', 'desc');
		$records = $this->db->get();

		$totalRecordwithFilter = $records->num_rows();


		$this->db->select('baki.*,customer.name as pname,metal_type.name as metal_type');
		$this->db->from('baki');
		$this->db->join('customer', 'customer.id = baki.customer_id', 'left');
		$this->db->join('metal_type', 'metal_type.id = baki.metal_type_id', 'left');
        if(!empty($baki_code)){
            $this->db->where('baki.baki_code',$baki_code);
        }else{
            $this->db->where('baki.id',0);
        }
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		$this->db->group_by('baki.id');
		$this->db->order_by('baki.id', 'desc');
		$records = $this->db->get()->result();
		$data = array();
		$i = $start + 1;
		foreach ($records as $record) {
		    
            $edit = '<a class="btn btn-action bg-warning text-white me-2 baki_edit_row" href="javascript:void(0)" data-id="'.$record->id.'"><i class="far fa-edit"></i></a>
             &nbsp;&nbsp;
            <a class="btn btn-action bg-danger text-white me-2 delete_row"  href="javascript:void(0)" data-id="'.$record->id.'"><i class="fa-solid fa-trash"></i></a>';
		
			$data[] = array(
				"sno" => $i,
				"action" => $edit,
				"party"=>$record->pname,
				"date" => date('d-m-Y', strtotime($record->date)),
				"type"=>$record->type,
				"purity" => $record->purity,
				"mode" => $record->mode,
				"wb" => $record->wb,
				"rate" => $record->rate,
				"remark" => $record->remark,
				"gross" => $record->gross,
				"fine" => $record->fine,
				"metal_type" => $record->metal_type,
				"amount" => $record->amount,

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
	public function update($id)
	{
		checkPrivilege(privilege['payment_edit']);
		$this->form_validation->set_rules('party_id', 'Party', 'trim|required|xss_clean');
		$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('bank_id', 'Bank', 'trim|required|xss_clean');
		$this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('metal_rate', 'Metal Rate', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('fine_silver', 'Fine Silver', 'trim|required|xss_clean|numeric');

		try {
			if ($this->form_validation->run() == false) {
				$message = array('message' => validation_errors(), 'class' => 'danger');
				$this->session->set_flashdata('flash_message', $message);
				redirect(base_url('admin/payment/payment_report'), 'refresh');
			} else {


				$updatearray = [
					'date' => $this->security->xss_clean($this->input->post('date')),
					'payment_type' => $this->security->xss_clean($this->input->post('payment_type')),
					'party_id' => $this->security->xss_clean($this->input->post('party_id')),
					'bank_id' => $this->security->xss_clean($this->input->post('bank_id')),
					'total_amount' => $this->security->xss_clean($this->input->post('total_amount')),
					'metal_rate' => $this->security->xss_clean($this->input->post('metal_rate')),
					'remark' => $this->security->xss_clean($this->input->post('remark')),
					'fine_silver' => $this->security->xss_clean($this->input->post('fine_silver')),
				];

				$result = $this->payment->Update($updatearray, $id);

				if ($result == TRUE) {
					$message = array('message' => "Payment Updated Successfully !!", 'class' => 'success');
					$this->session->set_flashdata('flash_message', $message);
					redirect(base_url('admin/payment/payment_report'), 'refresh');
				} else {
					$message = array('message' => "Payment Updating Failed !!", 'class' => 'danger');
					$this->session->set_flashdata('flash_message', $message);
					redirect(base_url('admin/payment/payment'), 'refresh');
				}
			}
		} catch (Exception $e) {
			$message = array('message' => $e->getMessage(), 'class' => 'danger');
			$this->session->set_flashdata('flash_message', $message);
			redirect(base_url('admin/payment/payment', 'refresh'));
		}
	}
}
