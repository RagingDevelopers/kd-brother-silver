<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/process";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Process';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["process_view"]);
                $page_data['data'] = $this->dbh->getResultArray('process');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["process_edit"]);
                $this->validateId($id);
                $process = $this->dbh->find('process', $id);
                if (!$process) {
                    flash()->withError("Process Not Found")->to('master/process');
                }
                $page_data['data'] = $this->dbh->getResultArray('process');
                $page_data['update'] = $process;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["process_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('process', $data);
                flash()->withSuccess("Process Added Successfully")->back();
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["process_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('process', $id);
                flash()->withSuccess("Process Deleted Successfully")->back();

                break;
            case "update":
                // checkPrivilege(privilege["process_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('process', $id, $data);
                flash()->withSuccess("Process Updated Successfully")->to("master/process");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/process");
    }
}
