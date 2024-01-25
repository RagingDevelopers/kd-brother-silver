<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_meterial extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/row_meterial";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Row Meterial';
        switch ($action) {
            case "":
                checkPrivilege(privilege["row_meterial_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('row_meterial', ['row_meterial_type']);
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["row_meterial_edit"]);
                $this->validateId($id);
                $row_meterial = $this->joinhelper->fetchJoinedTableRow('row_meterial', ['row_meterial_type'], $id);
                if (!$row_meterial) {
                    flash()->withError("Row Meterial Not Found")->to('master/row_meterial');
                }
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('row_meterial', ['row_meterial_type']);
                $page_data['update'] = $row_meterial;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["row_meterial_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('row_meterial_type_id', 'row_meterial_type_id', 'required');
                $validation->set_rules('opening_stock', 'opening_stock', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('row_meterial', $data);
                flash()->withSuccess("Row Meterial Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["item_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('item', $id);
            //     flash()->withSuccess("Item Deleted Successfully")->back();

            //     break;
            case "update":
                checkPrivilege(privilege["row_meterial_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('row_meterial_type_id', 'row_meterial_type_id', 'required');
                $validation->set_rules('opening_stock', 'opening_stock', 'required');
                
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('row_meterial', $id, $data);
                flash()->withSuccess("Row Meterial Updated Successfully")->to("master/row_meterial");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/row_meterial");
    }
}
