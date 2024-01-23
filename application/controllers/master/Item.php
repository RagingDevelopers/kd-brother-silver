<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/item";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Item';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["item_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('item', ['category']);
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["item_edit"]);
                $this->validateId($id);
                $item = $this->joinhelper->fetchJoinedTableRow('item', ['category'], $id);
                if (!$item) {
                    flash()->withError("Item Not Found")->to('master/item');
                }
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('item', ['category']);
                $page_data['update'] = $item;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["item_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('item', $data);
                flash()->withSuccess("Item Added Successfully")->back();
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["item_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('item', $id);
                flash()->withSuccess("Item Deleted Successfully")->back();

                break;
            case "update":
                // checkPrivilege(privilege["item_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('item', $id, $data);
                flash()->withSuccess("Item Updated Successfully")->to("master/item");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/item");
    }
}
