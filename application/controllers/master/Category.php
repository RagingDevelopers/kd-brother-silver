<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/category";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Category';
        switch ($action) {
            case "":
                checkPrivilege(privilege["category_view"]);
                $page_data['data'] = $this->dbh->getResultArray('category');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["category_edit"]);
                $this->validateId($id);
                $category = $this->dbh->find('category', $id);
                if (!$category) {
                    flash()->withError("Category Not Found")->to('master/category');
                }
                $page_data['data'] = $this->dbh->getResultArray('category');
                $page_data['update'] = $category;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["category_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('category', $data);
                flash()->withSuccess("Category Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["category_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('category', $id);
            //     flash()->withSuccess("Category Deleted Successfully")->back();

            //     break;
            case "update":
                checkPrivilege(privilege["category_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('category', $id, $data);
                flash()->withSuccess("Category Updated Successfully")->to("master/category");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/category");
    }
}
