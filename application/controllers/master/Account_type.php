<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Account_type extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_login();
    }

    public function index($params = "", $params1 = "")
    {
        $id = $params1;
        if ($params == "") {
            $data['page_title'] = "Account Type";
            $data['page_name'] = "admin/master/account_type";

            $this->db->select('account_type.*,users.name as uname');
            $this->db->from('account_type');
            $this->db->join('users', 'users.id=account_type.user_id', 'left');
            $data['data'] = $this->db->get()->result_array();
            $this->load->view('common', $data);
        }
        if ($params == "add") {
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('opening_amount', 'opening_amount', 'required');

            if ($this->form_validation->run() == FALSE) {
                flash_message('danger', validation_errors(), 'master/Account_type');
            } else {
                $post = $this->input->post();
                $data = array();
                $data['name'] = $post['name'];
                $data['opening_amount'] = $post['opening_amount'];
                $data['user_id'] = $this->session->userdata('id');
                $add = $this->db->insert('account_type', $data);
                if ($add == true) {
                    flash_message('success', 'Account Type Added Successfully !!', 'master/Account_type');

                } else {
                    flash_message('danger', 'Account Type Not  Added ', 'master/Account_type');
                }
            }
        }
        if ($params == "edit") {
            $query = $this->db->get_where('account_type', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $page_data['page_title'] = "Edit Account Type";
                $page_data['page_name'] = "admin/master/account_type";
                $page_data['update_data'] = $this->db->get_where('account_type', ['id' => $id])->row_array();
                $this->db->select('account_type.*,users.name as uname');
                $this->db->from('account_type');
                $this->db->join('users', 'users.id=account_type.user_id', 'left');
                $page_data['data'] = $this->db->get()->result_array();

                $this->load->view('common', $page_data);
            } else {
                flash_message('danger', 'Account Type does not exist', 'master/Account_type');
            }
        }
        if ($params == "delete") {
            $query = $this->db->get_where('account_type', ['id' => $id]);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $id)->delete('account_type');
                flash_message('success', 'Account Type deleted successfully!', 'master/Account_type');
            } else {
                flash_message('danger', 'Account Type  not deleted!', 'master/Account_type');
            }
        }
        if ($params == "update") {
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('opening_amount', 'opening_amount', 'required');

            if ($this->form_validation->run() == FALSE) {
                flash_message('danger', validation_errors(), 'master/Account_type');

            }
            $id = $this->input->post('id');
            $post = $this->input->post();
            $data = array();
            $data['name'] = $post['name'];
            $data['opening_amount'] = $post['opening_amount'];
            $data['user_id'] = $this->session->userdata('id');
            $query = $this->db->get_where('account_type', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $this->db->where('id', $id)->update('account_type', $data);
                flash_message('success', 'Account Type Updated Successfully !!', 'master/Account_type');
            } else {
                flash_message('danger', 'Account Type Does Not Updated  !!', 'master/Account_type');
            }
        }
    }
}