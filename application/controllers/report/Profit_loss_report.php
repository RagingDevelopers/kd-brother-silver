<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profit_loss_report extends CI_Controller
{
	public $form_validation, $input, $db;

	const View = "admin/report/profit_loss_report";
	
	public function __construct()
	{
		parent::__construct();
		check_login();
		library("dbh");
		// library("Joinhelper");
	}
	
	public function index(){
	    $page_data['page_title'] = 'Profit Loss Report';
	    return view(self::View, $page_data);
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
		$todate = $postData['todate'];
		$fromdate = $this->db->escape($postData['fromdate']);

		# Search 
		$searchQuery = "";
		if ($searchValue != '') {
// 			$searchQuery = " (garnu.name like '%" . $searchValue . "%' or garnu.copper like '%" . $searchValue . "%') ";
		}

		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		
        // Sale query
        $this->db->select('
            s.id,
            s.date,
            sd.pre_touch as stouch,
            sd.wastage as swastage,
            "" as srtouch,
            "" as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            "" as prwastage,
            sd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Sale" as type
            ')
        ->from('sale s')
        ->join('sale_detail sd', 's.id = sd.sale_id', 'left')
        ->join('item i', 'sd.item_id = i.id', 'left')
        ->join('customer c', 's.party_id = c.id', 'left');
        if (!empty($fromdate)) {
			$this->db->where('s.created_at >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('s.created_at <=', $todate);
		}
        $saleQuery = $this->db->get_compiled_select();
        
        $this->db->select('
            s.id,
            s.date,
            sd.pre_touch as stouch,
            sd.wastage as swastage,
            "" as srtouch,
            "" as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            "" as prwastage,
            sd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Sale" as type
            ')
        ->from('sale s')
        ->join('sale_detail sd', 's.id = sd.sale_id', 'left')
        ->join('item i', 'sd.item_id = i.id', 'left')
        ->join('customer c', 's.party_id = c.id', 'left');
        $this->db->where("s.created_at <", "STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')", false);
        $openingsaleQuery = $this->db->get_compiled_select();
		
        // Sale Detail query
        $this->db->select('
            s.id,
            s.date,
            "" as stouch,
            "" as swastage,
            sd.pre_touch as srtouch,
            sd.wastage as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            "" as prwastage,
            sd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Sale Return" as type,
            ')
        ->from('sale_return s')
        ->join('sale_return_detail sd', 's.id = sd.sale_id', 'left')
        ->join('item i', 'sd.item_id = i.id', 'left')
        ->join('customer c', 's.party_id = c.id', 'left');
        if (!empty($fromdate)) {
			$this->db->where('s.created_at >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('s.created_at <=', $todate);
		}
        $saleReturnQuery = $this->db->get_compiled_select();
		
        $this->db->select('
            s.id,
            s.date,
            "" as stouch,
            "" as swastage,
            sd.pre_touch as srtouch,
            sd.wastage as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            "" as prwastage,
            sd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Sale Return" as type,
            ')
        ->from('sale_return s')
        ->join('sale_return_detail sd', 's.id = sd.sale_id', 'left')
        ->join('item i', 'sd.item_id = i.id', 'left')
        ->join('customer c', 's.party_id = c.id', 'left');
		$this->db->where("s.created_at <", "STR_TO_DATE(" . $fromdate . ", '%Y-%m-%d')", false);
        $openingsaleReturnQuery = $this->db->get_compiled_select();
		
        // Purchase query
        $this->db->select('
            p.id,
            p.date,
            "" as stouch,
            "" as swastage,
            "" as srtouch,
            "" as srwastage,
            pd.pre_touch as ptouch,
            pd.wastage as pwastage,
            "" as prtouch,
            "" as prwastage,
            pd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Purchase" as type,
            ')
        ->from('purchase p')
        ->join('purchase_detail pd', 'p.id = pd.purchase_id', 'left')
        ->join('item i', 'pd.item_id = i.id', 'left')
        ->join('customer c', 'p.party_id = c.id', 'left');
        if (!empty($fromdate)) {
			$this->db->where('p.created_at >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('p.created_at <=', $todate);
		}
        $purchaseQuery = $this->db->get_compiled_select();
        
        $this->db->select('
            p.id,
            p.date,
            "" as stouch,
            "" as swastage,
            "" as srtouch,
            "" as srwastage,
            pd.pre_touch as ptouch,
            pd.wastage as pwastage,
            "" as prtouch,
            "" as prwastage,
            pd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Purchase" as type,
            ')
        ->from('purchase p')
        ->join('purchase_detail pd', 'p.id = pd.purchase_id', 'left')
        ->join('item i', 'pd.item_id = i.id', 'left')
        ->join('customer c', 'p.party_id = c.id', 'left');
		$this->db->where("p.created_at <", "STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')", false);
        $openingpurchaseQuery = $this->db->get_compiled_select();
        
        // Purchase Detail query
        $this->db->select('
            p.id,
            p.date,
            "" as stouch,
            "" as swastage,
            "" as srtouch,
            "" as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            pd.wastage as prwastage,
            pd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Purchase Return" as type,
            ')
        ->from('purchase_return p')
        ->join('purchase_return_detail pd', 'p.id = pd.purchase_id', 'left')
        ->join('item i', 'pd.item_id = i.id', 'left')
        ->join('customer c', 'p.party_id = c.id', 'left');
        if (!empty($fromdate)) {
			$this->db->where('p.created_at >=', $fromdate);
		}
		if (!empty($todate)) {
			$this->db->where('p.created_at <=', $todate);
		}
        $purchaseReturnQuery = $this->db->get_compiled_select();
        
        $this->db->select('
            p.id,
            p.date,
            "" as stouch,
            "" as swastage,
            "" as srtouch,
            "" as srwastage,
            "" as ptouch,
            "" as pwastage,
            "" as prtouch,
            pd.wastage as prwastage,
            pd.net_weight,
            i.name as item_name,
            c.name as customer_name,
            "Purchase Return" as type,
            ')
        ->from('purchase_return p')
        ->join('purchase_return_detail pd', 'p.id = pd.purchase_id', 'left')
        ->join('item i', 'pd.item_id = i.id', 'left')
        ->join('customer c', 'p.party_id = c.id', 'left');
        $this->db->where("p.created_at <", "STR_TO_DATE(" . $this->db->escape($fromdate) . ", '%Y-%m-%d')", false);
        $openingpurchaseReturnQuery = $this->db->get_compiled_select();
        
        // Combining queries using UNION ALL
        $final_query = "($saleQuery) UNION ALL ($saleReturnQuery) UNION ALL ($purchaseQuery) UNION ALL ($purchaseReturnQuery) ORDER BY date ASC";
        $final_query1 = "($saleQuery) UNION ALL ($saleReturnQuery) UNION ALL ($purchaseQuery) UNION ALL ($purchaseReturnQuery) ORDER BY date ASC";
        $openingfinalQuery = "($openingsaleQuery) UNION ALL ($openingsaleReturnQuery) UNION ALL ($openingpurchaseQuery) UNION ALL ($openingpurchaseReturnQuery)";
        $openingfinalQuery1 = $this->db->query($openingfinalQuery);
        $openingResult = $openingfinalQuery1->result_array();
        $openingWeight = 0;
        foreach ($openingResult as $r) {
			$openingWeight += ((double)$r['stouch'] + (double)$r['swastage']) * (double)$r['net_weight'] / 100;
            $openingWeight -= ((double)$r['srtouch'] + (double)$r['srwastage']) * (double)$r['net_weight'] / 100;
            $openingWeight -= ((double)$r['ptouch'] + (double)$r['pwastage']) * (double)$r['net_weight'] / 100;
            $openingWeight += ((double)$r['prtouch'] + (double)$r['prwastage']) * (double)$r['net_weight'] / 100;
		}
        // Adding limit and offset
        $final_query1 .= " LIMIT $start, $rowperpage";
        
        // Executing the final query
        $query = $this->db->query($final_query);
        $query1 = $this->db->query($final_query1);
        
        // Fetching the result
        $records = $query->num_rows();

		## Total number of records without filtering
		$totalRecords = $records;

		## Total number of record with filtering
		$totalRecordwithFilter = $query->num_rows();

		## Fetch records
		$records = $query1->result_array();

		$data = array();
		$i = $start + 1;
		$closingWeight = 0;
		foreach ($records as $record) {
		    
    		$profite  = 0;
            $loss  = 0;
			
// 			if($record['stouch'] > 0){
			    $profite = ((double)$record['stouch'] + (double)$record['swastage']) * (double)$record['net_weight'] / 100;
// 			}else if($record['srtouch'] > 0){
                $loss = ((double)$record['srtouch'] + (double)$record['srwastage']) * (double)$record['net_weight'] / 100;
// 			}else if($record['ptouch'] > 0){
                $loss = ((double)$record['ptouch'] + (double)$record['pwastage']) * (double)$record['net_weight'] / 100;
// 			}else if($record['prtouch'] > 0){
                $profite = ((double)$record['prtouch'] + (double)$record['prwastage']) * (double)$record['net_weight'] / 100;
// 			}
			$closingWeight += ($profite - $loss);
			$data[] = array(
				'id' => $i,
				'profite' => $profite,
				'loss' => $loss,
				'closing' => round($closingWeight,2),
				'type' => $record['type'],
				'date' => $record['date'],
				'customer_name' => $record['customer_name'],
				'item_name' => $record['item_name'],
			);
			$i = $i + 1;
		}

		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"closingWeight" => round($closingWeight,2),
			"openingWeight" => round($openingWeight,2)
		);
		echo json_encode($response);
		exit();
	}
}