<?php
class Process_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        library("dbh");
    }

    function fetch_process()
    {
        $this->db->order_by("name", "DESC");
        $query = $this->db->get("process");
        return $query->result();
    }

    function fetch_workers($process_id)
    {
        $data = $this->dbh->getWhereResultArray('customer',[
            'process_id'        => $process_id ?? null ,
            'account_type_id' => 7
        ]);       
        $output = '<option value="">Select Workers</option>';
        foreach($data as $row)  {
            $output .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        return $output;
    }
}