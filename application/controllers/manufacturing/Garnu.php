<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Garnu extends CI_Controller
{
    public $form_validation, $dbh, $input, $db;

    const View = "admin/manufacturing/garnu";
    // const ADD = "admin/manufacturing/customer/customer";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");

    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Garnu';
        switch ($action) {
            case "":
                $page_data['data'] = $this->dbh->getResultArray('garnu');
                return view(self::View, $page_data);
            case "add":
                $page_data['data'] = $this->dbh->getResultArray('garnu');
                return view(self::ADD, $page_data);

            case "edit":
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
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('garnu_weight', 'garnu_weight', 'required')
                    ->set_rules('tunch', 'tunch', 'required')
                    ->set_rules('silver', 'silver', 'required')
                    ->set_rules('copper', 'copper', 'required')
                    ->set_rules('total_used_weight', 'total_used_weight', 'required')
                    ->set_rules('total_unused_weight', 'total_unused_weight', 'total_unused_weight')
                    ->set_rules('total_used_silver', 'total_used_silver', 'required')
                    ->set_rules('remaining_silver', 'remaining_silver', 'required')
                    ->set_rules('total_used_copper', 'total_used_copper', 'required')
                    ->set_rules('remaining_copper', 'remaining_copper', 'required')
                    ->set_rules('garnu_id[]', 'garnu_id', 'required')
                    ->set_rules('metal_type_id[]', 'metal_type_id', 'required')
                    ->set_rules('weight[]', 'weight', 'required')
                    ->set_rules('tunch[]', 'tunch', 'required')
                    ->set_rules('copper[]', 'copper', 'required')
                    ->set_rules('silver[]', 'silver', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());
                $garnu = array();
                $garnu['name'] = $data['name'];
                $garnu['garnu_weight'] = $data['garnu_weight'];
                $garnu['tunch'] = $data['tunch'];
                $garnu['silver'] = $data['silver'];
                $garnu['copper'] = $data['copper'];
                $garnu['total_used_weight'] = $data['total_used_weight'];
                $garnu['total_unused_weight'] = $data['total_unused_weight'];
                $garnu['total_used_silver'] = $data['total_used_silver'];
                $garnu['remaining_silver'] = $data['remaining_silver'];
                $garnu['total_used_copper'] = $data['total_used_copper'];
                $garnu['remaining_copper'] = $data['remaining_copper'];
                $garnu['creation_date'] = $data['creation_date'];


                $this->db->insert('garnu', $garnu);
                $garnu_id = $this->db->insert_id();

                $garnu_item = $new = array();
                for ($i = 0; $i < count($data['metal_type_id']); $i++) {
                    $garnu_item['metal_type_id'] = $data['metal_type_id'][$i];
                    $garnu_item['weight'] = $data['weight'][$i];
                    $garnu_item['tunch'] = $data['tunch'][$i];
                    $garnu_item['silver'] = $data['silver'][$i];
                    $garnu_item['copper'] = $data['copper'][$i];
                    $garnu_item['customer_id'] = $garnu_id;
                    $new[] = $garnu_item;
                }

                $this->db->insert_batch('garnu_item', $new);

                flash()->withSuccess("Garnu type Added Successfully")->to("registration/garnu");
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
                    ->set_rules('garnu_weight', 'garnu_weight', 'required')
                    ->set_rules('tunch', 'tunch', 'required')
                    ->set_rules('silver', 'silver', 'required')
                    ->set_rules('copper', 'copper', 'required')
                    ->set_rules('total_used_weight', 'total_used_weight', 'required')
                    ->set_rules('total_unused_weight', 'total_unused_weight', 'total_unused_weight')
                    ->set_rules('total_used_silver', 'total_used_silver', 'required')
                    ->set_rules('remaining_silver', 'remaining_silver', 'required')
                    ->set_rules('total_used_copper', 'total_used_copper', 'required')
                    ->set_rules('remaining_copper', 'remaining_copper', 'required')
                    ->set_rules('garnu_id[]', 'garnu_id', 'required')
                    ->set_rules('metal_type_id[]', 'metal_type_id', 'required')
                    ->set_rules('weight[]', 'weight', 'required')
                    ->set_rules('tunch[]', 'tunch', 'required')
                    ->set_rules('copper[]', 'copper', 'required')
                    ->set_rules('silver[]', 'silver', 'required');


                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $data = xss_clean($this->input->post());

                // pre($data);
                // die;
                $garnu = array();
                $garnu['name'] = $data['name'];
                $garnu['garnu_weight'] = $data['garnu_weight'];
                $garnu['tunch'] = $data['tunch'];
                $garnu['silver'] = $data['silver'];
                $garnu['copper'] = $data['copper'];
                $garnu['total_used_weight'] = $data['total_used_weight'];
                $garnu['total_unused_weight'] = $data['total_unused_weight'];
                $garnu['total_used_silver'] = $data['total_used_silver'];
                $garnu['remaining_silver'] = $data['remaining_silver'];
                $garnu['total_used_copper'] = $data['total_used_copper'];
                $garnu['remaining_copper'] = $data['remaining_copper'];
                $garnu['creation_date'] = $data['creation_date'];
                $this->db->where('id', $id)->update('garnu', $garnu);

                $oldIds = $this->db->select('id')->get_where('garnu_item', ['garnu__id' => $id])->result_array();
                foreach ($oldIds as $row) {
                    if (!in_array($row['id'], $data['sdid'])) {
                        $this->db->where(['id' => $row['id']])->delete('garnu_item');
                    }
                }

                for ($i = 0; $i < count($data['metal_type_id']); $i++) {
                    $garnu_item = array();
                    $garnu_item['metal_type_id'] = $data['metal_type_id'][$i];
                    $garnu_item['weight'] = $data['weight'][$i];
                    $garnu_item['tunch'] = $data['tunch'][$i];
                    $garnu_item['silver'] = $data['silver'][$i];
                    $garnu_item['copper'] = $data['copper'][$i];
                    if ($data['sdid'][$i] > 0) {
                        $query = $this->db->get_where('garnu_item', ['id' => $data['sdid'][$i]]);
                        if ($query->num_rows() == 1) {
                            $this->db->where(['garnu_id' => $id, 'id' => $data['sdid'][$i]])->update('customer_item', $customer_item);
                        }
                    } else if ($data['sdid'][$i] == 0) {
                        $garnu_item['garnu_id'] = $id;
                        $this->db->insert('garnu_item', $garnu_item);
                    }
                }
                flash()->withSuccess("Garnu type Updated Successfully")->to("registration/garnu");
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
