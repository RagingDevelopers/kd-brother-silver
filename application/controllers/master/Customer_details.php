<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_details extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/customer/customer_details";
    const ADD = "admin/master/customer/customer";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");

    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Customer Details';
        switch ($action) {
            case "":
                // checkPrivilege(privilege["customer_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('customer', ['city', 'account_type']);
                return view(self::View, $page_data);
            case "add":
                // checkPrivilege(privilege["customer_view"]);
                $page_data['data'] = $this->joinhelper->fetchJoinedTable('customer', ['city', 'account_type']);
                return view(self::ADD, $page_data);

            case "edit":
                // checkPrivilege(privilege["customer_edit"]);
                $this->validateId($id);
                $customer = $this->joinhelper->fetchJoinedTable('customer', ['city', 'account_type'],$id);
                if (!$customer) {
                    flash()->withError("Customer type Not Found")->to('master/customer_details');
                }
                $page_data['data'] = $this->dbh->getResultArray('customer');
                $page_data['update'] = $customer;

                // pre($page_data,true);
                return view(self::View, $page_data);

            case "store":
                // checkPrivilege(privilege["customer_add"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('mobile', 'mobile', 'required')
                    ->set_rules('city_id', 'city_id', 'required')
                    ->set_rules('account_type_id', 'account_type_id', 'required')
                    ->set_rules('opening_amount', 'opening_amount', 'required')
                    ->set_rules('opening_amount_type', 'opening_amount_type', 'required')
                    ->set_rules('opening_fine', 'opening_fine', 'required')
                    ->set_rules('opening_fine_type', 'opening_fine_type', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $this->db->insert('customer', $data);
                flash()->withSuccess("Customer type Added Successfully")->to("master/customer_details");
                break;
            case "delete":
                die("not permission to delete");
                // checkPrivilege(privilege["customer_delete"]);
                $this->validateId($id);
                $this->dbh->deleteRow('customer', $id);
                flash()->withSuccess("Customer type Deleted Successfully")->back();
                break;
            case "update":
                // checkPrivilege(privilege["customer_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('mobile', 'mobile', 'required')
                    ->set_rules('city_id', 'city_id', 'required')
                    ->set_rules('account_type_id', 'account_type_id', 'required')
                    ->set_rules('opening_amount', 'opening_amount', 'required')
                    ->set_rules('opening_amount_type', 'opening_amount_type', 'required')
                    ->set_rules('opening_fine', 'opening_fine', 'required')
                    ->set_rules('opening_fine_type', 'opening_fine_type', 'required');

                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());

                $this->dbh->updateRow('customer', $id, $data);
                flash()->withSuccess("Customer type Updated Successfully")->to("master/customer");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("master/customer");
    }
}
