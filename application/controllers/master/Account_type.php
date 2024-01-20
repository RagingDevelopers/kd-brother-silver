<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Account_type extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/account_type";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Account Type';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["account_type_view"]);
                $page_data['data'] = $this->dbh->getResultArray('account_type');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["account_type_edit"]);
                $this->validateId($id);
                $account_type = $this->dbh->find('account_type', $id);
                if (!$account_type) {
                    flash()->withError("Account type Not Found")->to('master/account_type');
                }
                $page_data['data'] = $this->dbh->getResultArray('account_type');
                $page_data['update'] = $account_type;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["account_type_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('opening_amount', 'opening_amount', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('account_type', $data);
                flash()->withSuccess("Account type Added Successfully")->back();
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["account_type_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('account_type', $id);
                flash()->withSuccess("Account type Deleted Successfully")->back();
                break;
            case "update":
                // checkPrivilege(privilege["city_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('opening_amount', 'Opening Amount', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('account_type', $id, $data);
                flash()->withSuccess("Account type Updated Successfully")->to("master/account_type");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/account_type");
    }
}
