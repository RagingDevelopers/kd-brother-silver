<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
{
    const View = "admin/manufacturing/process";
    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model('manufacturing/Process_model', "modal");
    }

    public function manage($id=null)
    {
        $page_data['data'] = $this->db->select('*')->from('garnu')->where('id',$id)->get()->row_array();
        $page_data['table'] = $this->db->select('*')->from('given')->where('garnu_id',$id)->get()->result();
        $page_data['page_title'] = 'Process';
        $page_data['process'] = $this->modal->fetch_process();
        return view(self::View, $page_data);
    }

    public function getWorkers()
    {     
        $workerdata = $this->modal->fetch_workers($this->input->post('process_id'));
        echo $workerdata;       
    }

    public function add()
    {
        $validation = $this->form_validation;
        $validation->set_rules('name','Garnu Name','required')
                   ->set_rules('rc_qty','Received Quantity','required')
                   ->set_rules('weight','Garnu Weight','required')
                   ->set_rules('process','Process','required')
                   ->set_rules('workers','Workers','required')
                   ->set_rules('remarks','Remark','required')
                   ->set_rules('given_qty','Given Quantity','required')
                   ->set_rules('given_weight','Given Weight','required')
                   ->set_rules('labour','Labour','required')
                   ->set_rules('receive_qty','Received Quantity','required')
                   ->set_rules('receive_weight','Received Weight','required')
                   ->set_rules('total','Total','required')
                   ->set_error_delimiters('<div class="text-danger">', '</div>');

        if(!$validation->run()){
            return flash()->withError(validation_errors())->back();
        }
        $post = xss_clean($this->input->post());
        $data = array();
        $data['garnu_id'] = $post['garnu_id'];
        $data['rc_qty'] = $post['rc_qty'];
        $data['process'] = $post['process'];
        $data['workers'] = $post['workers'];
        $data['remarks'] = $post['remarks'];
        $data['given_qty'] = $post['given_qty'];
        $data['given_weight'] = $post['given_weight'];
        $data['labour'] = $post['labour'];
        $data['receive_qty'] = $post['receive_qty'];
        $data['receive_weight'] = $post['receive_weight'];
        $data['total'] = $post['total'];
        $data['creation_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert('given', $data);
        flash()->withSuccess("Garnu Added Successfully.")->back();
    }   
}