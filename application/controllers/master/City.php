<?php

defined('BASEPATH') or exit('No direct script access allowed');

class City extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/city";
    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->library("dbh");
    }

    public function index($action, $id = null)
    {

        $page_data['page_title'] = 'City';
        switch ($action) {
            case "":
                // checkPrivilege("city_view");
                $page_data['data'] = $this->dbh->getResultArray('city');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege("city_edit");
                $city = $this->dbh->find('city', $id);
                if (!$city) {
                    flash()->withError("City Not Found")->back()->go();
                }

                $page_data['data'] = $this->dbh->find('city', $id);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege("city_add");
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back()->go();
                }
                $data = $this->input->post();
                $this->db->insert('city', $data);
                flash()->withSuccess("City Added Successfully")->back()->go();

                break;
            case "update":
                // checkPrivilege("city_edit");
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back()->go();
                }
                $data = $this->input->post();
                $this->dbh->updateRow('city', $id, $data);
                flash()->withSuccess("City Updated Successfully")->back()->go();
                break;
        }
    }

    public function store()
    {
        // checkPrivilege("city_add"); 
        $validation = $this->form_validation;

        $validation->set_rules('name', 'Name', 'required');
        if ($validation->run() == false) {
            return flash()->withError(validation_errors())->back()->go();
        }
        $data = $this->input->post();
        $this->dbh->insert('city', $data);
        flash()->withSuccess("City Added Successfully")->back()->go();
    }
}
