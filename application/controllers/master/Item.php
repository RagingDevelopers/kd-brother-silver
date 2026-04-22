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
                checkPrivilege(privilege["item_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('item', ['category']);
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["item_edit"]);
                $this->validateId($id);
                $item = $this->joinhelper->fetchJoinedTableRow('item', ['category'], $id);
                if (!$item) {
                    flash()->withError("Item Not Found")->to('master/item');
                }
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('item', ['category']);
                $page_data['update'] = $item;
                return view(self::View, $page_data);

            case "list":
                checkPrivilege(privilege["item_view"]);
                $items = $this->joinhelper->fetchJoinedTable('item', ['category']);
                header('Content-Type: application/json');
                echo json_encode(['data' => array_values($items)]);
                exit;

            case "store":
                checkPrivilege(privilege["item_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('category_id', 'Category', 'required');
                $validation->set_rules('type', 'Type', 'required|in_list[raw_material,metal_type,finish_goods]');
                $validation->set_rules('opening_stock', 'Opening Stock', 'required|numeric');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('item', $data);
                flash()->withSuccess("Item Added Successfully")->back();
                break;

            case "update":
                checkPrivilege(privilege["item_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('category_id', 'Category', 'required');
                $validation->set_rules('type', 'Type', 'required|in_list[raw_material,metal_type,finish_goods]');
                $validation->set_rules('opening_stock', 'Opening Stock', 'required|numeric');

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
