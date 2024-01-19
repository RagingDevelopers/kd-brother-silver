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
                $message = ['class' => 'danger', 'message' => validation_errors()];
                $this->session->set_flashdata('flash', $message);
                redirect(base_url('admin/party'));
            } else {

                $post = $this->input->post();
                $data = array();
                $data['name'] = $post['name'];
                $data['user_id'] = $this->session->userdata('id');
                $add = $this->db->insert('process', $data);
                if ($add == true) {
                    flash_message('success', 'Process Added Successfully !!', 'master/process');

                } else {
                    flash_message('danger', 'Process Not  Added ', 'master/process');

                }

            }
        }
        if ($params == "edit") {
            $query = $this->db->get_where('process', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $page_data['page_title'] = "Edit Process ";
                $page_data['page_name'] = "master/process";
                $page_data['update_data'] = $this->db->get_where('process', ['id' => $id])->row_array();
                $this->db->select('process.*,users.name as uname');
                $this->db->from('process');
                $this->db->join('users', 'users.id=process.user_id', 'left');
                $page_data['data'] = $this->db->get()->result_array();

                $this->load->view('common', $page_data);
            } else {
                flash_message('danger', 'Process does not exist', 'master/process');
            }
        }
        if ($params == "delete") {
            $query = $this->db->get_where('process', ['id' => $id]);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $id)->delete('process');
                flash_message('danger', 'Process deleted successfully!', 'master/process');

            } else {
                flash_message('danger', 'Process  not deleted!', 'master/process');

            }
        }
        if ($params == "update") {
            $this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE) {

                $message = ['class' => 'danger', 'message' => validation_errors()];
                $this->session->set_flashdata('flash', $message);
                redirect(base_url('admin/party'));
            }
            $id = $this->input->post('id');
            $post = $this->input->post();
            $data = array();
            $data['name'] = $post['name'];
            $data['mobile_no'] = $post['mobile_no'];
            $data['city'] = $post['city'];
            $data['user_id'] = $this->session->userdata('id');
            $query = $this->db->get_where('party', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $this->db->where('id', $id)->update('party', $data);
                $message = ['class' => 'success my-3', 'message' => 'party updated successfully!'];
                $this->session->set_flashdata('flash', $message);
                redirect(base_url('admin/party/'));
            } else {
                $message = ['class' => 'danger my-3', 'message' => 'party does not updated'];
                $this->session->set_flashdata('flash', $message);
                redirect(base_url('admin/party/'));
            }
        }
    }
}