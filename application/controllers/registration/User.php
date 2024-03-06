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
                checkPrivilege(privilege["users_view"]);
                $page_data['data'] = $this->dbh->getResultArray('users');
                return view(self::View, $page_data);

            case "edit":
                checkPrivilege(privilege["users_edit"]);
                $this->validateId($id);
                $users = $this->dbh->find('users', $id);
                if (!$users) {
                    flash()->withError("Users type Not Found")->to('registration/user');
                }
                $page_data['data'] = $this->dbh->getResultArray('users');
                $page_data['update'] = $users;

                // pre($_SESSION,true);
                return view(self::View, $page_data);

            case "store":
                checkPrivilege(privilege["users_add"]);
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
                checkPrivilege(privilege["users_edit"]);
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
            $searchQuery = " (name like '%" . $searchValue . "%'  or mobile like '%" . $searchValue . "%'  or type like '%" . $searchValue . "%' or status like'%" . $searchValue . "%' or opening_amount like'%" . $searchValue . "%' or opening_fine like'%" . $searchValue . "%' or created_at like'%" . $searchValue . "%' ) ";
        }
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount;


        ## Total number of record with filtering

        $this->db->select('*');
        $this->db->from('users');

        if ($searchQuery != '')
            $this->db->where($searchQuery);

        $records = $this->db->get();
        $totalRecordwithFilter = $records->num_rows();


        ## Fetch records
        $this->db->select('*');
        $this->db->from('users');


        if ($searchQuery != '')
            $this->db->where($searchQuery);

        $this->db->limit($rowperpage, $start);
        $this->db->order_by('id', "desc");
        $records = $this->db->get()->result();


        $data = array();
        $i = $start + 1;
        foreach ($records as $record) {
            $action = '
            <a href="' . base_url('registration/user/edit/') . $record->id . '" class="btn btn-action bg-success text-white me-2">
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
                'type' => $record->type,
                'status' => $record->status,
                'opening_amount' => $record->opening_amount,
                'opening_fine' => $record->opening_fine,
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
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("registration/user");
    }
}
