<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/master/customer/customer_report";
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
        $page_data['page_title'] = 'Customer Report';
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
                $customer = $this->joinhelper->fetchJoinedTableRow('customer', ['city', 'account_type'], $id);
                if (!$customer) {
                    flash()->withError("Customer type Not Found")->to('registration/customer');
                }
                // $page_data['data'] = $this->joinhelper->fetchJoinedTable('customer', ['city', 'account_type']);
                $page_data['items'] = $this->dbh->getWhereResultArray('customer_item', ['customer_id' => $id]);
                $page_data['update'] = $customer;

                // pre($page_data, true);
                return view(self::ADD, $page_data);

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
                    ->set_rules('item_id[]', 'item_id', 'required')
                    ->set_rules('touch[]', 'touch', 'required')
                    ->set_rules('extra_touch[]', 'extra_touch', 'required')
                    ->set_rules('wastage[]', 'wastage', 'required')
                    ->set_rules('label[]', 'label', 'required')
                    ->set_rules('sub_total[]', 'sub_total', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $customer = array();
                $customer['name'] = $data['name'];
                $customer['mobile'] = $data['mobile'];
                $customer['city_id'] = $data['city_id'];
                $customer['account_type_id'] = $data['account_type_id'];
                $customer['opening_amount'] = $data['opening_amount'];
                $customer['opening_amount_type'] = $data['opening_amount_type'];
                $customer['opening_fine'] = $data['opening_fine'];
                $customer['opening_fine_type'] = $data['opening_fine_type'];

                $this->db->insert('customer', $customer);
                $customer_id = $this->db->insert_id();

                $customer_details = $new = array();
                for ($i = 0; $i < count($data['item_id']); $i++) {
                    $customer_details['item_id'] = $data['item_id'][$i];
                    $customer_details['touch'] = $data['touch'][$i];
                    $customer_details['extra_touch'] = $data['extra_touch'][$i];
                    $customer_details['wastage'] = $data['wastage'][$i];
                    $customer_details['label'] = $data['label'][$i];
                    $customer_details['rate'] = $data['rate'][$i];
                    $customer_details['sub_total'] = $data['sub_total'][$i];
                    $customer_details['customer_id'] = $customer_id;
                    $new[] = $customer_details;
                }

                $this->db->insert_batch('customer_item', $new);

                flash()->withSuccess("Customer type Added Successfully")->to("registration/customer");
                break;

            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["customer_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('customer', $id);
            //     flash()->withSuccess("Customer type Deleted Successfully")->back();
            //     break;
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
                    ->set_rules('opening_fine_type', 'opening_fine_type', 'required')
                    ->set_rules('item_id[]', 'item_id', 'required')
                    ->set_rules('touch[]', 'touch', 'required')
                    ->set_rules('extra_touch[]', 'extra_touch', 'required')
                    ->set_rules('wastage[]', 'wastage', 'required')
                    ->set_rules('label[]', 'label', 'required')
                    ->set_rules('sub_total[]', 'sub_total', 'required');


                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());

                // pre($data);
                // die;
                $customer = array();
                $customer['name'] = $data['name'];
                $customer['mobile'] = $data['mobile'];
                $customer['city_id'] = $data['city_id'];
                $customer['account_type_id'] = $data['account_type_id'];
                $customer['opening_amount'] = $data['opening_amount'];
                $customer['opening_amount_type'] = $data['opening_amount_type'];
                $customer['opening_fine'] = $data['opening_fine'];
                $customer['opening_fine_type'] = $data['opening_fine_type'];
                $this->db->where('id', $id)->update('customer', $customer);
                $oldIds = $this->db->select('id')->get_where('customer_item', ['customer_id' => $id])->result_array();
                foreach ($oldIds as $row) {
                    if (!in_array($row['id'], $data['sdid'])) {
                        $this->db->where(['id' => $row['id']])->delete('customer_item');
                    }
                }
                for ($i = 0; $i < count($data['touch']); $i++) {
                    $customer_item = array();
                    $customer_item['item_id'] = $data['item_id'][$i];
                    $customer_item['touch'] = $data['touch'][$i];
                    $customer_item['extra_touch'] = $data['extra_touch'][$i];
                    $customer_item['wastage'] = $data['wastage'][$i];
                    $customer_item['label'] = $data['label'][$i];
                    $customer_item['rate'] = $data['rate'][$i];
                    $customer_item['sub_total'] = $data['sub_total'][$i];
                    if ($data['sdid'][$i] > 0) {
                        $query = $this->db->get_where('customer_item', ['id' => $data['sdid'][$i]]);
                        if ($query->num_rows() == 1) {
                            $this->db->where(['customer_id' => $id, 'id' => $data['sdid'][$i]])->update('customer_item', $customer_item);
                        } 
                    } else if ($data['sdid'][$i] == 0) {                       
                        $customer_item['customer_id'] = $id;
                        $this->db->insert('customer_item', $customer_item);
                    }
                }
                flash()->withSuccess("Customer type Updated Successfully")->to("registration/customer");
                break;
            default:
                flash()->withError("Invalid Arguments")->back();
        }
    }


    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("registration/customer");
    }
}
