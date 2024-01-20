<?php

defined('BASEPATH') or exit('No direct script access allowed');

class City extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/city";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'City';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["city_view"]);
                $page_data['data'] = $this->dbh->getResultArray('city');
                return view(self::View, $page_data);

            case "edit":
                // checkPrivilege(privilege["city_edit"]);
                $this->validateId($id);
                $city = $this->dbh->find('city', $id);
                if (!$city) {
                    flash()->withError("City Not Found")->to('master/city');
                }
                $page_data['data'] = $this->dbh->getResultArray('city');
                $page_data['update'] = $city;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["city_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('city', $data);
                flash()->withSuccess("City Added Successfully")->back();
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["city_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('city', $id);
                flash()->withSuccess("City Deleted Successfully")->back();

                break;
            case "update":
                checkPrivilege(privilege["city_delete"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required');
                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->dbh->updateRow('city', $id, $data);
                flash()->withSuccess("City Updated Successfully")->to("master/city");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/city");
    }
}
