<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stamp extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/stamp";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Stamp';
        switch ($action) {
            case "":
                // pre($_SESSION);die;
                checkPrivilege(privilege["stamp_view"]);
                $page_data['data'] = $this->dbh->getResultArray('stamp');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["stamp_edit"]);
                $this->validateId($id);
                $stamp = $this->dbh->find('stamp', $id);
                if (!$stamp) {
                    flash()->withError("bank Not Found")->to('master/stamp');
                }
                $page_data['data'] = $this->dbh->getResultArray('stamp');
                $page_data['update'] = $stamp;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["stamp_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('stamp', $data);
                flash()->withSuccess("stamp Added Successfully")->back();
                break;
            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["bank_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('bank', $id);
            //     flash()->withSuccess("bank Deleted Successfully")->back();
            //     break;
            case "update":
                checkPrivilege(privilege["stamp_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('stamp', $id, $data);
                flash()->withSuccess("stamp Updated Successfully")->to("master/stamp");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/stamp");
    }
}
