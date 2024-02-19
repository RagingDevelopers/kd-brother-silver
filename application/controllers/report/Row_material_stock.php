<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Row_material_stock extends CI_Controller
{
    public $form_validation, $input, $db;

    const View = "admin/report/row_material_stock";

    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        $this->load->model('stock/Row_material', "stock");
        // library("Joinhelper");
    }

    public function index()
    {
        $page_data['page_title'] = 'Row Material Stock';
        $page_data['row_material'] = $this->stock->fetch_row_material();
        $page_data['garnu'] = $this->stock->fetch_garnu_name();
        $page_data['process'] = $this->stock->fetch_process();
        return view(self::View, $page_data);
    }

    public function fetchData()
    {
        $postData = $this->security->xss_clean($this->input->post());
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        // serching coding
        $columnIndex = $postData['order'][0]['column']; // Column index
        $searchValue = $postData['search']['value']; // Search value
        $todate = $postData['todate'] ?? null;
        $fromdate = $postData['fromdate'] ?? null;
        $row_material_id = $postData['row_material_id'] ?? null;
        $garnu_id = $postData['garnu_id'] ?? null;
        $process_id = $postData['process_id'] ?? null;
        // $types = $postData['types'] ?? null;

        # Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchValue = $this->db->escape('%' . $searchValue . '%');
            $searchQuery = " (row_material.name like '%" . $searchValue . "%'  OR
            garnu.name like '%" . $searchValue . "%'  OR
            process.name like '%" . $searchValue . "%' OR
            given_row_material.touch like'%" . $searchValue . "%' OR 
            given_row_material.weight like'%" . $searchValue . "%' OR 
            given_row_material.quantity like'%" . $searchValue . "%'  OR
            given_row_material.creation_date like'%" . $searchValue . "%' OR 
            receive_row_material.touch like'%" . $searchValue . "%' OR 
            receive_row_material.weight like'%" . $searchValue . "%' OR
            receive_row_material.quantity like'%" . $searchValue . "%'  OR
            receive_row_material.creation_date like'%" . $searchValue . "%') ";
        } else {
            $searchQuery = ' TRUE ';
        }

        $where = "";
        if (!empty($fromdate)) {
            $fromdate = $this->db->escape($fromdate);
            $where .= "receive_row_material.creation_date >= " . $fromdate . " AND ";
        }
        if (!empty($todate)) {
            $todate = $this->db->escape($todate);
            $where .= "receive_row_material.creation_date <= " . $todate . " AND ";
        }
        if (!empty($row_material_id)) {
            // $todate = $this->db->escape($row_material_id);
            $where .= "row_material.id = " . $row_material_id . " AND ";
        }
        if (!empty($garnu_id)) {
            // $todate = $this->db->escape($garnu_id);
            $where .= "garnu.id = " . $garnu_id . " AND ";
        }
        if (!empty($process_id)) {
            // $todate = $this->db->escape($process_id);
            $where .= "process.id = " . $process_id . " AND ";
        }
        // if (!empty($types)) {
        //     // $todate = $this->db->escape($types);
        //     $where .= "process.id = " . $types . " AND ";
        // }

        $where = rtrim($where, ' AND ');

        if (!empty($where)) {
            $where = " AND ($where)";
        }

        ## Total number of records without filtering
        $q = $this->db->query("
                SELECT COUNT(*) as total_count FROM (
                SELECT
                1
                FROM
                receive_row_material
                LEFT JOIN receive ON receive_row_material.received_id = receive.id
                LEFT JOIN garnu ON receive.garnu_id = garnu.id
                LEFT JOIN given ON receive.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
                UNION ALL
                SELECT
                1
                FROM
                given_row_material
                LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                LEFT JOIN given ON given_row_material.given_id = given.id
                LEFT JOIN process ON given.process_id = process.id
        ) AS combined_results");

        $records = $q->row_array();
        $totalRecords = $records['total_count'];

        ## Total number of record with filtering
        $filteredQuery = $this->db->query("
                    SELECT COUNT(*) as total_count_filtered FROM (
                    SELECT
                        receive_row_material.id
                    FROM
                        receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE TRUE $where
                    UNION ALL
                    SELECT
                        given_row_material.id
                    FROM
                        given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE TRUE " . str_replace("receive_row_material.creation_date", "given_row_material.creation_date", $where) . "
        ) AS combined_results_filtered");

        $records = $filteredQuery->row_array();
        $totalRecordwithFilter = $records['total_count_filtered'];

        ## Fetch records
        $fetchQuery = "
                    SELECT * FROM (
                    SELECT
                    receive_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    receive_row_material.touch as Touch,
                    receive_row_material.weight as Weight,
                    receive_row_material.quantity as Quantity,
                    receive_row_material.created_at as Date,               
                    'Credit' as Type   
                    FROM
                    receive_row_material
                    LEFT JOIN receive ON receive_row_material.received_id = receive.id
                    LEFT JOIN row_material ON receive_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON receive.garnu_id = garnu.id
                    LEFT JOIN given ON receive.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE $searchQuery " . (!empty($where) ? " $where" : '') . "
                    UNION ALL
                    SELECT
                    given_row_material.id as Id,
                    row_material.name as RowMaterial,
                    garnu.name as GarnuName,
                    process.name as ProcessName,
                    given_row_material.touch as Touch,
                    given_row_material.weight as Weight,
                    given_row_material.quantity as Quantity,
                    given_row_material.created_at as Date,
                    'Debit' as Type   
                    FROM
                    given_row_material
                    LEFT JOIN row_material ON given_row_material.row_material_id = row_material.id
                    LEFT JOIN garnu ON given_row_material.garnu_id = garnu.id
                    LEFT JOIN given ON given_row_material.given_id = given.id
                    LEFT JOIN process ON given.process_id = process.id
                    WHERE $searchQuery " . (!empty($where) ? str_replace("receive_row_material", "given_row_material", $where) : '') . "
                    ) AS combined_results
                LIMIT $rowperpage OFFSET $start";

        $query = $this->db->query($fetchQuery);
        $records = $query->result_array();

        $data = array();
        $i = $start + 1;
        foreach ($records as $r) {
            $row_material = $r['RowMaterial'];
            $garnu_name = $r['GarnuName'];
            $process_name = $r['ProcessName'];
            $touch = $r['Touch'];
            $weight = $r['Weight'];
            $quantity = $r['Quantity'];
            $type = $r['Type'];
            $date = $r['Date'];

            $data[] = array(
                'id' => $i,
                'row_material' => $row_material,
                'garnu' => $garnu_name,
                'process' => $process_name,
                "type" => $type,
                'ctouch' => ($type == 'Credit') ? $touch : '--',
                'cweight' => ($type == 'Credit') ? $weight : '--',
                'cquantity' => ($type == 'Credit') ? $quantity : '--',
                'dtouch' => ($type == 'Debit') ? $touch : '--',
                'dweight' => ($type == 'Debit') ? $weight : '--',
                'dquantity' => ($type == 'Debit') ? $quantity : '--',
                'date' => date("d-m-Y g:i A", strtotime($date)),
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
}
