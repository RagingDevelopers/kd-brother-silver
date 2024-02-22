<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/unit";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Unit';
        switch ($action) {
            case "":
                // pre($_SESSION);die;
                // checkPrivilege(privilege["unit_view"]);
                $page_data['data'] = $this->dbh->getResultArray('unit');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["unit_edit"]);
                $this->validateId($id);
                $unit = $this->dbh->find('unit', $id);
                if (!$unit) {
                    flash()->withError("Unit Not Found")->to('master/unit');
                }
                $page_data['data'] = $this->dbh->getResultArray('unit');
                $page_data['update'] = $unit;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["unit_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('unit', $data);
                flash()->withSuccess("Unit Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["bank_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('bank', $id);
            //     flash()->withSuccess("bank Deleted Successfully")->back();
            //     break;
            case "update":
                // checkPrivilege(privilege["unit_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('unit', $id, $data);
                flash()->withSuccess("Unit Updated Successfully")->to("master/unit");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/unit");
    }
}
