<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Metal_type extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/metal_type";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Metal Type';
        switch ($action) {
            case "":
                checkPrivilege(privilege["metal_type_view"]);
                $page_data['data'] = $this->dbh->getResultArray('metal_type');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["metal_type_edit"]);
                $this->validateId($id);
                $row_material_type = $this->dbh->find('metal_type', $id);
                if (!$row_material_type) {
                    flash()->withError("Metal Type Not Found")->to('master/metal_type');
                }
                $page_data['data'] = $this->dbh->getResultArray('metal_type');
                $page_data['update'] = $row_material_type;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["metal_type_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('metal_type', $data);
                flash()->withSuccess("Metal Type Added Successfully")->back();
                break;
            // case "delete":
            // die("not permission to delete");
            // // checkPrivilege(privilege["process_delete"]);
            // $this->validateId($id);
            // $this->dbh->deleteRow('process', $id);
            // flash()->withSuccess("Process Deleted Successfully")->back();

            // break;
            case "update":
                checkPrivilege(privilege["metal_type_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('metal_type', $id, $data);
                flash()->withSuccess("Meteal Type Updated Successfully")->to("master/metal_type");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/metal_type");
    }
}
