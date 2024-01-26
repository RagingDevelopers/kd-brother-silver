<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/user/user";
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
                // checkPrivilege(privilege["users_view"]);
                $page_data['data'] = $this->dbh->getResultArray('users');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["users_edit"]);
                $this->validateId($id);
                $users = $this->dbh->find('users', $id);
                if (!$users) {
                    flash()->withError("Users type Not Found")->to('registration/user');
                }
                $page_data['data'] = $this->dbh->getResultArray('users');
                $page_data['update'] = $users;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["users_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('mobile', 'mobile', 'required')
                    ->set_rules('password', 'password', 'required')
                    ->set_rules('type', 'type', 'required')
                    ->set_rules('status', 'status', 'required')
                    ->set_rules('opening_amount', 'opening_amount', 'required')
                    ->set_rules('opening_fine', 'opening_fine', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());

                $password = sha1($this->security->xss_clean($this->input->post('password')));
                $data['password'] = $password;

                $this->db->insert('users', $data);
                flash()->withSuccess("Users type Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["users_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('users', $id);
            //     flash()->withSuccess("Users type Deleted Successfully")->back();
            //     break;
            case "update":
                // checkPrivilege(privilege["users_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                $validation->set_rules('mobile', 'mobile', 'required');
                $validation->set_rules('type', 'type', 'required');
                $validation->set_rules('status', 'status', 'required');
                $validation->set_rules('opening_amount', 'opening_amount', 'required');
                $validation->set_rules('opening_fine', 'opening_fine', 'required');

                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                unset($data['password']);
                $this->dbh->updateRow('users', $id, $data);
                flash()->withSuccess("Users type Updated Successfully")->to("registration/user");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }
    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("registration/user");
    }
}
