<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_material_type extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/row_material_type";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Row Material Type';
        switch ($action) {
            case "":
                checkPrivilege(privilege["row_material_type_view"]);
                $page_data['data'] = $this->dbh->getResultArray('row_material_type');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["row_material_type_edit"]);
                $this->validateId($id);
                $row_material_type = $this->dbh->find('row_material_type', $id);
                if (!$row_material_type) {
                    flash()->withError("Row Material Type Not Found")->to('master/row_material_type');
                }
                $page_data['data'] = $this->dbh->getResultArray('row_material_type');
                $page_data['update'] = $row_material_type;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["row_material_type_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('row_material_type', $data);
                flash()->withSuccess("Row Material Type Added Successfully")->back();
                break;
            // case "delete":
                // die("not permission to delete");
                // // checkPrivilege(privilege["process_delete"]);
                // $this->validateId($id);
                // $this->dbh->deleteRow('process', $id);
                // flash()->withSuccess("Process Deleted Successfully")->back();

                // break;
            case "update":
                checkPrivilege(privilege["row_material_type_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('row_material_type', $id, $data);
                flash()->withSuccess("Row Material Type Updated Successfully")->to("master/row_material_type");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/row_material_type");
    }
}
