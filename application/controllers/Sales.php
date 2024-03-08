<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public $form_validation, $input, $db;
    const View = "admin/sales/sale_report";
    const Create = "admin/sales/create";
    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model('Sales_model', "sales");
    }

    public function index()
    {
        $page_data['page_title'] = 'Sales Report';
        $page_data['party'] = $this->sales->fetch_party();
        return view(self::View, $page_data);
    }

    public function create()
    {
        $page_data['page_title'] = 'Sales';
        $page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
        $page_data['party'] = $this->sales->fetch_party();
        $page_data['item'] = $this->sales->fetch_item();
        $page_data['stamp'] = $this->sales->fetch_stamp();
        $page_data['unit'] = $this->sales->fetch_unit();
        return view(self::Create, $page_data);
    }

    public function store()
    {
        $batchData = [];
        $data = xss_clean($this->input->post());
        print_r($data);
        $insert['date'] = $data['date'];
        $insert['party_id'] = $data['party_id'];
        $batchData[] = $insert;
        $this->db->insert_batch('sale', $batchData);
        $id = $this->db->insert_id();
        for ($i = 0; $i < count($data['item']); $i++) {
            $saleDetail['item_id'] = $data['item'][$i];
            $saleDetail['stamp_id'] = $data['stamp'][$i];
            $saleDetail['unit_id'] = $data['unit'][$i];
            $saleDetail['remark'] = $data['remark'][$i];
            $saleDetail['gross_weight'] = $data['gross_weight'][$i];
            $saleDetail['less_weight'] = $data['less_weight'][$i];
            $saleDetail['net_weight'] = $data['net_weight'][$i];
            $saleDetail['touch'] = $data['touch'][$i];
            $saleDetail['wastage'] = $data['wastage'][$i];
            $saleDetail['fine'] = $data['fine'][$i];
            $saleDetail['piece'] = $data['piece'][$i];
            $saleDetail['labour'] = $data['labour'][$i];
            $saleDetail['rate'] = $data['rate'][$i];
            $saleDetail['sub_total'] = $data['sub_total'][$i];
            $saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
            $saleDetail['sale_id'] = $id;
            $this->db->insert('sale_detail', $saleDetail);
            $saleDetailId = $this->db->insert_id();
            $array = explode("|", $data['raw-material-data'][$i]);
            for ($a = 0; $a < count($array); $a++) {
                $saleMaterialData = [];
                $array1 = explode(",", $array[$a]);
                $saleMaterial['sale_detail_id'] = $saleDetailId;
                $saleMaterial['row_material_id'] = $array1[0];
                $saleMaterial['quantity'] = $array1[1];
                $saleMaterial['rate'] = $array1[2];
                $saleMaterial['sub_total'] = $array1[3];
                $saleMaterialData[] = $saleMaterial;
            }
            $this->db->insert_batch('sale_material', $saleMaterialData);
        }
        flash()->withSuccess("Insert Successfully.")->to("sales/index");
    }

    public function edit($id)
    {
        $data = $this->db->select('sale.*')
            ->from('sale')
            ->where('id', $id)
            ->get()->row_array();
        $data['sale_detail'] = $this->db->select('sale_detail.*')
            ->from('sale_detail')
            ->where('sale_id', $id)
            ->get()->result_array();
        for ($i = 0; $i < count($data['sale_detail']); $i++) {
            $array = explode("|", $data['sale_detail'][$i]['raw_material_data']);
            for ($a = 0; $a < count($array); $a++) {
                $array1 = explode(",", $array[$a]);
                $material = $this->db->select('*')->from('sale_material')->where([
                    'sale_detail_id' => $data['sale_detail'][$i]['id'],
                    'row_material_id' => $array1[0], 'quantity' => $array1[1], 'rate' => $array1[2], 'sub_total' => $array1[3]
                ])->get()->row_array();
                $array1[4] = isset($material['id']) ? $material['id'] : 0;
                $array2[] = implode(",", $array1);
            }
            $array3 = implode("|", $array2);
            $data['sale_detail'][$i]['raw_material_data'] = $array3;
        }
        $page_data['page_title'] = 'Sales';
        $page_data['row_material'] = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
        $page_data['party'] = $this->sales->fetch_party();
        $page_data['item'] = $this->sales->fetch_item();
        $page_data['stamp'] = $this->sales->fetch_stamp();
        $page_data['unit'] = $this->sales->fetch_unit();
        $page_data['data'] = $data;
        return view(self::Create, $page_data);
    }

    public function update($id)
    {
        $data = xss_clean($this->input->post());
        print_r($data);
        $update['date'] = $data['date'];
        $update['party_id'] = $data['party_id'];
        $this->db->where('id', $id)->update('sale', $update);
        for ($i = 0; $i < count($data['item']); $i++) {
            $saleDetail['item_id'] = $data['item'][$i];
            $saleDetail['stamp_id'] = $data['stamp'][$i];
            $saleDetail['unit_id'] = $data['unit'][$i];
            $saleDetail['remark'] = $data['remark'][$i];
            $saleDetail['gross_weight'] = $data['gross_weight'][$i];
            $saleDetail['less_weight'] = $data['less_weight'][$i];
            $saleDetail['net_weight'] = $data['net_weight'][$i];
            $saleDetail['touch'] = $data['touch'][$i];
            $saleDetail['wastage'] = $data['wastage'][$i];
            $saleDetail['fine'] = $data['fine'][$i];
            $saleDetail['piece'] = $data['piece'][$i];
            $saleDetail['labour'] = $data['labour'][$i];
            $saleDetail['rate'] = $data['rate'][$i];
            $saleDetail['sub_total'] = $data['sub_total'][$i];
            $saleDetail['raw_material_data'] = $data['raw-material-data'][$i];
            $this->db->where(['id' => $data['rowid'][$i], 'sale_id' => $id])->update('sale_detail', $saleDetail);
            $array = explode("|", $data['raw-material-data'][$i]);
            for ($a = 0; $a < count($array); $a++) {
                $array1 = explode(",", $array[$a]);
                $saleMaterial['row_material_id'] = $array1[0];
                $saleMaterial['quantity'] = $array1[1];
                $saleMaterial['rate'] = $array1[2];
                $saleMaterial['sub_total'] = $array1[3];
                $this->db->where(['sale_detail_id' => $data['rowid'][$i], 'id' => $array1[4]])->update('sale_material', $saleMaterial);
            }
        }
        flash()->withSuccess("Update Successfully.")->to("sales/index");
    }
}
