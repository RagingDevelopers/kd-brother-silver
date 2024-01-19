<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
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
            $data['page_title'] = "Process";
            $data['page_name'] = "admin/master/process";

            $this->db->select('process.*,users.name as uname');
            $this->db->from('process');
            $this->db->join('users', 'users.id=process.user_id', 'left');
            $data['data'] = $this->db->get()->result_array();
            $this->load->view('common', $data);
        }
        if ($params == "add") {
            $this->form_validation->set_rules('name', 'name', 'required');

            if ($this->form_validation->run() == FALSE) {
                flash_message('danger', validation_errors(), 'master/Process');
            } else {
                $post = $this->input->post();
                $data = array();
                $data['name'] = $post['name'];
                $data['user_id'] = $this->session->userdata('id');
                $add = $this->db->insert('process', $data);
                if ($add == true) {
                    flash_message('success', 'Process Added Successfully !!', 'master/Process');

                } else {
                    flash_message('danger', 'Process Not  Added ', 'master/Process');
                }

            }
        }
        if ($params == "edit") {
            $query = $this->db->get_where('process', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $page_data['page_title'] = "Edit Process ";
                $page_data['page_name'] = "admin/master/process";
                $page_data['update_data'] = $this->db->get_where('process', ['id' => $id])->row_array();
                $this->db->select('process.*,users.name as uname');
                $this->db->from('process');
                $this->db->join('users', 'users.id=process.user_id', 'left');
                $page_data['data'] = $this->db->get()->result_array();

                $this->load->view('common', $page_data);
            } else {
                flash_message('danger', 'Process does not exist', 'master/Process');
            }
        }
        if ($params == "delete") {
            $query = $this->db->get_where('process', ['id' => $id]);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $id)->delete('process');
                flash_message('success', 'Process deleted successfully!', 'master/Process');
            } else {
                flash_message('danger', 'Process  not deleted!', 'master/Process');
            }
        }
        if ($params == "update") {
            $this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE) {
                flash_message('danger', validation_errors(), 'master/Process');

            }
            $id = $this->input->post('id');
            $post = $this->input->post();
            $data = array();
            $data['name'] = $post['name'];
            $data['user_id'] = $this->session->userdata('id');
            $query = $this->db->get_where('process', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $this->db->where('id', $id)->update('process', $data);
                flash_message('success', 'Process Updated Successfully !!', 'master/Process');
            } else {
                flash_message('danger', 'Process Does Not Updated  !!', 'master/Process');
            }
        }
    }
}