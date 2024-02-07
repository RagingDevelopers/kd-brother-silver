<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jama extends CI_Controller
{
    const View = "admin/payment/jama";

	function __construct()
	{
		parent::__construct();
		$this->load->model('payment/Jama_model', 'jama');
		check_login();
	}

	public function index()
	{
		// checkPrivilege(privilege['jama_add']);
		$page_data['page_title'] = 'Jama';
		$page_data['bank'] = $this->jama->bank();
		$page_data['party'] = $this->jama->party();
		// $page_data['page_name'] = 'payment/jama';
        return view(self::View,$page_data);
	}
	public function edit($param,$param1){
	    $num_rows = $this->db->get_where('jama',array('jama_code'=>$param))->num_rows();
	    if($num_rows==0){
			redirect(base_url('payment/jama_report'), 'refresh');
	    }else{
    		$page_data['page_title'] = 'Jama';
    		$page_data['jama_code']= $param;
    		$page_data['party_id']= $param1;
    		$page_data['bank'] = $this->jama->bank();
    		$page_data['party'] = $this->jama->party();
    		return view(self::View,$page_data);
	    }
	}
	public function delete_row(){
	    checkPrivilege(privilege['jama_delete']);
	    $id = $this->security->xss_clean($this->input->post('jama_id'));
	    $this->db->where('id',$id);
	    $delete = $this->db->delete('jama');
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
	public function delete_jama_code($param){
	    // checkPrivilege(privilege['jama_delete']);
	    $num_rows = $this->db->get_where('jama',array('jama_code'=>$param))->num_rows();
	    if($num_rows==0){
			redirect(base_url('payment/jama_report'), 'refresh');
	    }else{
	        $this->db->where('jama_code',$param);
	        $this->db->delete('jama');
	        redirect(base_url('payment/jama_report'), 'refresh');
	    }
	}
	public function jama_data_ajax_update(){
			$condata = $this->security->xss_clean($this->input->post());
			$this->db->where('id',$condata['jama_id']);
            $update = $this->db->update('jama',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark']));
			
			if ($update == TRUE) {
				$res = [
					'status' => true,
					'message' => "Jama Updated Successfully",
					'jama_code'=>$condata['code'],
				];
			} else {
				$res = [
					'status' => false,
					'message' => "Jama Updated Failed",
				];
			}
		
    	echo json_encode($res);
	
	}
	public function jama_data_update(){
			$condata = $this->security->xss_clean($this->input->post());
			$this->db->where('id',$condata['jama_id']);
            $update = $this->db->update('jama',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark']));
			
			if ($update == TRUE) {
				$res = [
					'status' => true,
					'message' => "Jama Updated Successfully",
				];
			} else {
				$res = [
					'status' => false,
					'message' => "Jama Updated Failed",
				];
			}
		
    	echo json_encode($res);
	}
	public function jama_data(){
			$condata = $this->security->xss_clean($this->input->post());
			if($condata['code']=='0'){
    			$jama_code = $this->db->get_where('setting',array('id'=>1))->row('jama_code');
			    $condata['jama_code'] = 'JAMA_'.$jama_code;
    			$this->db->where('id', 1);
    			$this->db->update('setting', array('jama_code' => $jama_code + 1));
			}else{
			    if($condata['party_id']==$condata['check_party_id']){
    		        $condata['jama_code'] = $condata['code'];
			    }else{
    	        	$jama_code = $this->db->get_where('setting',array('id'=>1))->row('jama_code');
    			    $condata['jama_code'] = 'JAMA_'.$jama_code;
        			$this->db->where('id', 1);
        			$this->db->update('setting', array('jama_code' => $jama_code + 1));
			    }
			}
            $insert = $this->db->insert('jama',array('date'=>$condata['date'],'customer_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark'],'jama_code'=>$condata['jama_code']));

			if ($insert == TRUE) {
				$res = [
					'status' => true,
					'message' => "Jama Successfully",
					'jama_code'=>$condata['jama_code'],
					'party_id'=>$condata['party_id'],
				];
			} else {
				$res = [
					'status' => false,
					'message' => "Jama Adding Failed"
				];
			}
		
    	echo json_encode($res);
	}
	public function jama_edit_row(){
	    	$jama_id = $this->security->xss_clean($this->input->post('jama_id'));
	    	$row_data = $this->db->get_where('jama',array('id'=>$jama_id))->row_array();
	    	if(!empty($row_data)){
	    	    $res = [
					'status' => true,
					'data' => $row_data,
				];
	    	}else{
	    	    $res = [
					'status' => false,
					'data' => "Jama Adding Failed",
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
					'customer_id' => $this->security->xss_clean($this->input->post('party_id')),
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
	public function jama_edit_ajax(){
			$condata = $this->security->xss_clean($this->input->post());
			$this->db->where('id',$condata['jama_id']);
            $update = $this->db->update('jama',array('date'=>$condata['date'],'party_id'=>$condata['party_id'],'type'=>$condata['type'],'mode'=>$condata['mode']
            ,'gross'=>$condata['gross'],'purity'=>$condata['purity'],'wb'=>$condata['wb'],'fine'=>$condata['fine'],'rate'=>$condata['rate'],
            'amount'=>$condata['amount'],'remark'=>$condata['remark']));
			
			if ($update == TRUE) {
				$res = [
					'status' => true,
					'message' => "Jama Updated Successfully",
				];
			} else {
				$res = [
					'status' => false,
					'message' => "Jama Updated Failed"
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
        $jama_code = $postData['jama_code'];
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (customer.name like '%" . $searchValue . "%') ";
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


		$this->db->group_by('jama.id');
		$this->db->order_by('jama.id', 'desc');
		$records = $this->db->get();

		$totalRecordwithFilter = $records->num_rows();


		$this->db->select('jama.*,customer.name as pname');
		$this->db->from('jama');
		$this->db->join('customer', 'customer.id = jama.customer_id', 'left');
        if(!empty($jama_code)){
            $this->db->where('jama.jama_code',$jama_code);
        }else{
            $this->db->where('jama.id',0);
        }
		if ($searchQuery != '')
			$this->db->where($searchQuery);

		$this->db->group_by('jama.id');
		$this->db->order_by('jama.id', 'desc');
		$records = $this->db->get()->result();
		$data = array();
		$i = $start + 1;
		foreach ($records as $record) {
		    
            $edit = '<a class="btn btn-action bg-warning text-white me-2 jama_edit_row" href="javascript:void(0)" data-id="'.$record->id.'"><i class="far fa-edit"></i></a>   &nbsp;&nbsp;
            <a class="btn btn-action bg-danger text-white me-2 delete_row"  href="javascript:void(0)" data-id="'.$record->id.'"><i class="fa-solid fa-trash"></i></a>';
		
		
			$data[] = array(
				"sno" => $i,
				"action" => $edit,
				"party"=> $record->pname,
				"date" => date('d-m-Y', strtotime($record->date)),
				"type"=>$record->type,
				"purity" => $record->purity,
				"mode" => $record->mode,
				"wb" => $record->wb,
				"rate" => $record->rate,
				"remark" => $record->remark,
				"gross" => $record->gross,
				"fine" => $record->fine,
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
