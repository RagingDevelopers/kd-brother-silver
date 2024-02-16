<?php
class Row_material extends CI_Model
{
    public function __construct(){
        parent::__construct();
        // library("dbh");
    }
    function fetch_row_material()
    {
        $this->db->order_by("name", "DESC");
        $query = $this->db->get("row_material");
        return $query->result();
    }

    function fetch_garnu_name()
    {
        $this->db->order_by("name", "DESC");
        $query = $this->db->get("garnu");
        return $query->result();
    }

    function fetch_process()
    {
        $this->db->order_by("name", "DESC");
        $query = $this->db->get("process");
        return $query->result();
    }
}