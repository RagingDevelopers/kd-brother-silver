<?php
class Sales_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function fetch_party()
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get("customer");
        return $query->result_array();
    }

    function fetch_item()
    {
        $this->db->order_by("name","ASC");
        $query = $this->db->get("item");
        return $query->result_array();
    }
    
    function fetch_stamp()
    {
        $this->db->order_by("name","ASC");
        $query = $this->db->get("stamp");
        return $query->result_array();
    }

    function fetch_unit()
    {
        $this->db->order_by("name","ASC");
        $query = $this->db->get("unit");
        return $query->result_array();
    }
}