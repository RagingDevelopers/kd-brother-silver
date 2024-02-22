<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_material extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/row_material";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Row Material';
        switch ($action) {
            case "":
                checkPrivilege(privilege["row_material_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('row_material', ['row_material_type']);
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["row_material_edit"]);
                $this->validateId($id);
                $row_material = $this->joinhelper->fetchJoinedTableRow('row_material', ['row_material_type'], $id);
                if (!$row_material) {
                    flash()->withError("Row Material Not Found")->to('master/row_material');
                }
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('row_material', ['row_material_type']);
                $page_data['update'] = $row_material;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["row_material_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('row_material_type_id', 'row_material_type_id', 'required');
                $validation->set_rules('opening_stock', 'opening_stock', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('row_material', $data);
                flash()->withSuccess("Row Material Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["item_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('item', $id);
            //     flash()->withSuccess("Item Deleted Successfully")->back();

            //     break;
            case "update":
                checkPrivilege(privilege["row_material_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('row_material_type_id', 'row_material_type_id', 'required');
                $validation->set_rules('opening_stock', 'opening_stock', 'required');
                
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('row_material', $id, $data);
                flash()->withSuccess("Row Material Updated Successfully")->to("master/row_material");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/row_material");
    }
}
