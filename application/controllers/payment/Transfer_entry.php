<?php

defined('BASEPATH') or exit('Direch script not allowed');

class Transfer_entry extends CI_Controller
{

	private $dbh;
	function __construct()
	{
		parent::__construct();
		check_login();

		$this->load->model('Transfer_entry_model', 'mpm');
				$this->load->library('Joinhelper');

		$this->dbh = $this->joinhelper;
	}

	function index()
	{
		$page_data['page_name'] = 'admin/transfer_entry/report';
		$page_data['page_title'] = 'Metal Payment Report';
		$page_data['customer'] = $this->dbh->getResultArray('customer');
		$this->load->view('/common', $page_data);
	}

	function add()
	{
// 		checkPrivilege(privilege['transfer_entry_add']);
		$page_data['page_name'] = 'admin/transfer_entry/transfer_entry';
		$page_data['page_title'] = 'Metal Payment';
		// $page_data['customers'] = customerWithClosing();
		$page_data['customers'] = $this->db->get('customer')->result_array();
		$page_data['touch_group'] = []; //$this->dbh->getResultArray('items_group');
		$page_data['rms'] = []; //$this->dbh->getResultArray('raw_material');
		$this->load->view('common', $page_data);
	}

	function edit($id = 0)
	{
// 		checkPrivilege(privilege['transfer_entry_edit']);
		$id = $this->security->xss_clean($id);
		$page_data['page_name'] = 'admin/transfer_entry/transfer_entry';
		$page_data['page_title'] = 'Metal Payment Edit';
		// $page_data['customers'] = customerWithClosing();
		$page_data['customers'] = $this->db->get('customer')->result_array();
		$page_data['touch_group'] = []; //$this->dbh->getResultArray('items_group');
		$page_data['rms'] = []; //$this->dbh->getResultArray('raw_material');
		$page_data['row_data'] = $this->dbh->getRowArray('transfer_entry', $id);
		// pre($page_data);
		$this->load->view('common', $page_data);
	}

	function insert()
	{
// 		checkPrivilege(privilege['transfer_entry_add']);
		$data = $this->security->xss_clean($this->input->post());
		$insertData = [
			'payment_type' => $data['payment_type'],
			'customer_id' => $data['customer'],
			'transfer_customer_id' => $data['transfer_customer'],
			'narration' => $data['narration'],
			'total_amount' => $data['total_amount'],
			'date' => $data['date'],
			'gold' => $data['gold'],
			'created_by' => $data['created_by'],
			'admin_id' => session('id'),
// 			'session_id' => $this->session->userdata('session_id')
		];
		$insert = $this->db->insert('transfer_entry', $insertData);

		if ($insert) {
			$message = ['message' => 'Insert Success', 'class' => 'success'];
			$this->session->set_flashdata('flash_message', $message);
			$submitType = $data['submit_type'];
			$id = $this->db->insert_id();
// 			auditLog('transfer_entry', $this->db->escape($this->db->insert_id()), 'insert', $insertData);
			if ($submitType == 'Save') {
				$redirect = (site_url('payment/transfer_entry/add'));
			} else if ($submitType == 'Save & Exit') {
				$redirect = (site_url('payment/transfer_entry'));
			} else if ($submitType == 'Save & Print') {
				$redirect = (site_url('payment/transfer_entry/printPayment/' . $id));
			}
			flash()->withSuccess('Insert Success')->to($redirect);
		} else {
			$message = ['message' => 'Insert Failed', 'class' => 'danger'];
			$this->session->set_flashdata('flash_message', $message);
			redirect(site_url('payment/transfer_entry'), 'refresh');
		}
	}

	function update($id = 0)
	{
// 		checkPrivilege(privilege['transfer_entry_edit']);
		$id = $this->security->xss_clean($id);
		$data = $this->security->xss_clean($this->input->post());

		$this->db->where('id', $id);
		$update = $this->db->update('transfer_entry', [
			'payment_type' => $data['payment_type'],
			'customer_id' => $data['customer'],
			'transfer_customer_id' => $data['transfer_customer'],
			'narration' => $data['narration'],
			'total_amount' => $data['total_amount'],
			'date' => $data['date'],
			'gold' => $data['gold'],
			'created_by' => $data['created_by'],
			'admin_id' => session('id'),
		]);
		if ($update) {
			$submitType = $data['submit_type'];
			if ($submitType == 'Save') {
				$redirect = (site_url('payment/transfer_entry/add'));
			} else if ($submitType == 'Save & Exit') {
				$redirect = (site_url('payment/transfer_entry'));
			} else if ($submitType == 'Save & Print') {
				$redirect = (site_url('payment/transfer_entry/printPayment/' . $id));
			}
			flash()->withSuccess('Insert Success')->to($redirect);
		} else {
			flash()->withError('Update Failed')->to('payment/transfer_entry');

		}
	}

	function printPayment($id = 0)
	{
		$id = $this->security->xss_clean($id);
		$this->db->where('TE.id', $id);
		$this->db->select('TE.date, TE.total_amount, TE.narration, TE.gold, TE.payment_type, TE.created_by, C.name AS customer_name, TC.name AS transfer_customer_name');
		$this->db->join('customer C', 'C.id = TE.customer_id', 'left');
		$this->db->join('customer TC', 'TC.id = TE.transfer_customer_id', 'left');
		$payment = $this->db->get('transfer_entry TE')->row_array();
		// pre($payment);
		$page_data['data'] = $payment;
		$this->load->view('admin/print/print_transfer_entry', $page_data);
	}


	function ajax_getList()
	{
		$data = $this->security->xss_clean($this->input->post());
		$data = $this->mpm->datatable_getList($data);
		echo json_encode($data);
	}

	function delete($id = 0)
	{
		$id = $this->security->xss_clean($id);
		$redirect = flash();
		if ($this->dbh->deleteRow('transfer_entry', $id)) {
			$redirect->withSuccess('Delete Success');
		} else {
			$redirect->withError('Delete Failed');
		}
		$redirect->to('payment/transfer_entry');
	}
}
