<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/user";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'User';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["account_type_view"]);
                $page_data['data'] = $this->dbh->getResultArray('users');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["account_type_edit"]);
                $this->validateId($id);
                $users = $this->dbh->find('users', $id);
                if (!$users) {
                    flash()->withError("Users type Not Found")->to('master/user');
                }
                $page_data['data'] = $this->dbh->getResultArray('users');
                $page_data['update'] = $users;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["account_type_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('mobile', 'mobile', 'required');
                $validation->set_rules('password', 'password', 'required');
                $validation->set_rules('type', 'type', 'required');
                $validation->set_rules('status', 'status', 'required');
                $validation->set_rules('balance', 'balance', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());

                $password = sha1($this->security->xss_clean($this->input->post('password')));
                $data['password'] = $password;


                $this->db->insert('users', $data);
                flash()->withSuccess("Users type Added Successfully")->back();
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["account_type_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('users', $id);
                flash()->withSuccess("Users type Deleted Successfully")->back();
                break;
            case "update":
                // checkPrivilege(privilege["city_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('mobile', 'mobile', 'required');
                // $validation->set_rules('password', 'password', 'required');
                $validation->set_rules('type', 'type', 'required');
                $validation->set_rules('status', 'status', 'required');
                $validation->set_rules('balance', 'balance', 'required');

                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $password = sha1($this->security->xss_clean($this->input->post('password')));
                $data['password'] = $password;

                $this->dbh->updateRow('users', $id, $data);
                flash()->withSuccess("Users type Updated Successfully")->to("master/user");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/user");
    }
}
