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
                    ->set_rules('item_id[]', 'item_id', 'trim')
                    ->set_rules('extra_touch[]', 'extra_touch', 'trim')
                    ->set_rules('wastage[]', 'wastage', 'trim')
                    ->set_rules('label[]', 'label', 'trim')
                    ->set_rules('sub_total[]', 'sub_total', 'trim');

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
				$customer['process_id'] = $data['process_id'] ?? null; 
                $customer['opening_fine_type'] = $data['opening_fine_type'];

                $this->db->insert('customer', $customer);
                $customer_id = $this->db->insert_id();

                $customer_details = $new = array();
                for ($i = 0; $i < count($data['item_id']); $i++) {
                    $customer_details['item_id'] = $data['item_id'][$i];
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
                    ->set_rules('item_id[]', 'item_id', 'trim')
                    ->set_rules('extra_touch[]', 'extra_touch', 'trim')
                    ->set_rules('wastage[]', 'wastage', 'trim')
                    ->set_rules('label[]', 'label', 'trim')
                    ->set_rules('sub_total[]', 'sub_total', 'trim');


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
                $customer['process_id'] = $data['process_id'] ?? null; 
                $this->db->where('id', $id)->update('customer', $customer);

                $oldIds = $this->db->select('id')->get_where('customer_item', ['customer_id' => $id])->result_array();
                foreach ($oldIds as $row) {
                    if (!in_array($row['id'], $data['sdid'])) {
                        $this->db->where(['id' => $row['id']])->delete('customer_item');
                    }
                }

                for ($i = 0; $i < count($data['item_id']); $i++) {
                    $customer_item = array();
                    $customer_item['item_id'] = $data['item_id'][$i];
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

    public function getlist()
    {
        $postData = $this->security->xss_clean($this->input->post());
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        // serching coding
        $columnIndex = $postData['order'][0]['column']; // Column index
        $searchValue = $postData['search']['value']; // Search value
        # Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = "(name like '%" . $searchValue . "%'  or mobile like '%" . $searchValue . "%'  or city.name like '%" . $searchValue . "%' or account_type.name like'%" . $searchValue . "%' or opening_amount like'%" . $searchValue . "%' or opening_amount_type like'%" . $searchValue . "%'  or opening_fine like'%" . $searchValue . "%'  or opening_fine_type like'%" . $searchValue . "%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('customer')->result();
        $totalRecords = $records[0]->allcount;


        ## Total number of record with filtering
        $this->db->select('customer.*, city.name as city_name,account_type.name as account_type_name');
        $this->db->from('customer');
        $this->db->join('city', 'city.id = customer.city_id', 'left');
        $this->db->join('account_type', 'account_type.id = customer.account_type_id', 'left');

        if ($searchQuery != '')
            $this->db->where($searchQuery);

        $records = $this->db->get();
        $totalRecordwithFilter = $records->num_rows();


        ## Fetch records
        $this->db->select('customer.*, city.name as city_name,account_type.name as account_type_name');
        $this->db->from('customer');
        $this->db->join('city', 'city.id = customer.city_id', 'left');
        $this->db->join('account_type', 'account_type.id = customer.account_type_id', 'left');

        if ($searchQuery != '')
            $this->db->where($searchQuery);

        $this->db->limit($rowperpage, $start);
        $this->db->order_by('id', "desc");
        $records = $this->db->get()->result();


        $data = array();
        $i = $start + 1;
        foreach ($records as $record) {
            $action = '
            <a href="' . base_url('registration/customer/edit/') . $record->id . '" class="btn btn-action bg-success text-white me-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit " width="50" height="50" viewBox="0 0 25 25" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
            <path d="M16 5l3 3">
            </path>
            </svg>
            </a>
            ';

            $data[] = array(
                'id' => $i,
                'action' => $action,
                'name' => $record->name,
                'mobile' => $record->mobile,
                'city_name' => $record->city_name,
                'account_type_name' => $record->account_type_name,
                'opening_amount' => $record->opening_amount,
                'opening_amount_type' => $record->opening_amount_type,
                'opening_fine' => $record->opening_fine,
                'opening_fine_type' => $record->opening_fine_type,
                'created_at' => $record->created_at,
            );
            $i = $i + 1;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
        );
        echo json_encode($response);
        exit();
    }

    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("registration/customer");
    }
}
