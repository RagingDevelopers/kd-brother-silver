<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sub_item extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/sub_item";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Sub Item';
        switch ($action) {
            case "":
                checkPrivilege(privilege["sub_item_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('sub_item', ['item']);
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["sub_item_edit"]);
                $this->validateId($id);
                $item = $this->joinhelper->fetchJoinedTableRow('sub_item', ['item'], $id);
                if (!$item) {
                    flash()->withError("Item Not Found")->to('master/sub_item');
                }
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('sub_item', ['item']);
                $page_data['update'] = $item;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["sub_item_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('item_id', 'item_id', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('sub_item', $data);
                flash()->withSuccess("Sub Item Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["item_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('item', $id);
            //     flash()->withSuccess("Item Deleted Successfully")->back();

            //     break;
            case "update":
                checkPrivilege(privilege["sub_item_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('item_id', 'item_id', 'required');

                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('sub_item', $id, $data);
                flash()->withSuccess("Sub Item Updated Successfully")->to("master/sub_item");
            break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/sub_item");
    }
}
