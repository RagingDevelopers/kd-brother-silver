<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bank extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/bank";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'bank';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["bank_view"]);
                $page_data['data'] = $this->dbh->getResultArray('bank');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["bank_edit"]);
                $this->validateId($id);
                $bank = $this->dbh->find('bank', $id);
                if (!$bank) {
                    flash()->withError("bank Not Found")->to('master/bank');
                }
                $page_data['data'] = $this->dbh->getResultArray('bank');
                $page_data['update'] = $bank;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["bank_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('bank', $data);
                flash()->withSuccess("bank Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["bank_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('bank', $id);
            //     flash()->withSuccess("bank Deleted Successfully")->back();
            //     break;
            case "update":
                // checkPrivilege(privilege["bank_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('bank', $id, $data);
                flash()->withSuccess("bank Updated Successfully")->to("master/bank");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/bank");
    }
}
