<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_meterial_type extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/row_meterial_type";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Row Meterial Type';
        switch ($action) {
            case "":
                checkPrivilege(privilege["row_meterial_type_view"]);
                $page_data['data'] = $this->dbh->getResultArray('row_meterial_type');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["row_meterial_type_edit"]);
                $this->validateId($id);
                $row_meterial_type = $this->dbh->find('row_meterial_type', $id);
                if (!$row_meterial_type) {
                    flash()->withError("Row Meterial Type Not Found")->to('master/row_meterial_type');
                }
                $page_data['data'] = $this->dbh->getResultArray('row_meterial_type');
                $page_data['update'] = $row_meterial_type;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["row_meterial_type_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('row_meterial_type', $data);
                flash()->withSuccess("Row Meterial Type Added Successfully")->back();
                break;
            // case "delete":
                // die("not permission to delete");
                // // checkPrivilege(privilege["process_delete"]);
                // $this->validateId($id);
                // $this->dbh->deleteRow('process', $id);
                // flash()->withSuccess("Process Deleted Successfully")->back();

                // break;
            case "update":
                checkPrivilege(privilege["row_meterial_type_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('row_meterial_type', $id, $data);
                flash()->withSuccess("Row Meterial Type Updated Successfully")->to("master/row_meterial_type");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/row_meterial_type");
    }
}
