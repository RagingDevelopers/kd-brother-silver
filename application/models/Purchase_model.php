<?php
class Purchase_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function fetch_party()
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get_where("customer", array('account_type_id' => 1));
        return $query->result_array();
    }

    function fetch_item()
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get("item");
        return $query->result_array();
    }

    function fetch_stamp()
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get("stamp");
        return $query->result_array();
    }

    function fetch_unit()
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get("unit");
        return $query->result_array();
    }

    function getSalesGroupByItem($filterParams)
    {
        // Clean the filter params
        $filterParams = $this->security->xss_clean($filterParams);

        // Create the date variables
        $fromDate = $this->db->escape($filterParams['from_date']);
        $toDate = $this->db->escape($filterParams['to_date']);
        $customer = $this->db->escape($filterParams['customer']);
        $item = $this->db->escape($filterParams['item']);

        // Generate a search query based on the filter params
        $searchQuery = " TRUE AND "; // Starting where clause
        if (!empty($filterParams['customer']) && $filterParams['customer'] !== "0")
            $searchQuery .= " pl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " pd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause

$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        // Generate a query to get the sales group by item
        $rowItemQuery = "SELECT
						SUM(pd.gross_weight) AS gross_wt,
                        SUM(pd.less_weight) AS less_wt,
						SUM(pd.net_weight) AS net_wt,
						SUM(pd.sub_total) AS amount,
						SUM(pd.fine) AS fine,
						SUM(pd.pre_touch) AS pre_touch,
						SI.name AS item,
                        MAX(pd.created_at) as createdAt
					FROM purchase_detail pd
                    LEFT JOIN purchase pl ON pl.id = pd.purchase_id
					LEFT JOIN item SI ON SI.id = pd.item_id
					WHERE $searchQuery
					GROUP BY pd.item_id
					ORDER BY pl.id DESC";
        return $this->db->query($rowItemQuery)->result_array();
    }

    function getSalesGroupByCustomer($filterParams)
    {
        $filterParams = $this->security->xss_clean($filterParams);

        $fromDate = $this->db->escape($filterParams['from_date']);
        $toDate = $this->db->escape($filterParams['to_date']);
        $customer = $this->db->escape($filterParams['customer']);
        $item = $this->db->escape($filterParams['item']);

        $searchQuery = " TRUE AND "; // Starting where clause
        if (!empty($filterParams['customer']) && $filterParams['customer'] !== "0")
            $searchQuery .= " pl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " pd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause
        
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        // Generate a query to get the sales group by customer
        $rowCustomerQuery = "SELECT
						CUS.name AS customer,
						SUM(pd.gross_weight) AS gross_wt,
                        SUM(pd.less_weight) AS less_wt,
						SUM(pd.net_weight) AS net_wt,
						SUM(pd.sub_total) AS amount,
						SUM(pd.fine) AS fine,
						SUM(pd.pre_touch) AS pre_touch,
                        MAX(pd.created_at) as createdAt
					FROM purchase_detail pd
                    LEFT JOIN purchase pl ON pl.id = pd.purchase_id
                    LEFT JOIN customer CUS ON CUS.id = pl.party_id
					WHERE $searchQuery
					GROUP BY pl.party_id
					ORDER BY pl.id DESC";


        return $this->db->query($rowCustomerQuery)->result_array();
    }

    function getSalesGroupByBill($filterParams)
    {
        $filterParams = $this->security->xss_clean($filterParams);


        $fromDate = $this->db->escape($filterParams['from_date']);
        $toDate = $this->db->escape($filterParams['to_date']);
        $customer = $this->db->escape($filterParams['customer']);
        $item = $this->db->escape($filterParams['item']);

        $searchQuery = " TRUE AND "; // Starting where clause
        if (!empty($filterParams['customer']) && $filterParams['customer'] !== "0")
            $searchQuery .= " pl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " pd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause
        
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        // Generate a query to get the sales group by sale
        $rowBillQuery = "SELECT
                        pl.sequence_code,
                        pl.id AS purchase_id,
						CUS.name AS customer,
                        CI.name AS city,
                        DATE_FORMAT(pl.date, '%d-%m-%Y') AS date,
						SUM(pd.gross_weight) AS gross_wt,
                        SUM(pd.less_weight) AS less_wt,
						SUM(pd.net_weight) AS net_wt,
						SUM(pd.sub_total) AS amount,
						SUM(pd.fine) AS fine,
						SUM(pd.pre_touch) AS pre_touch,
                        MAX(pd.remark) as remark,
                        MAX(pd.created_at) as createdAt
					FROM purchase_detail pd
                    LEFT JOIN purchase pl ON pl.id = pd.purchase_id
                    LEFT JOIN customer CUS ON CUS.id = pl.party_id
                    LEFT JOIN city CI ON CI.id = CUS.city_id
					WHERE $searchQuery
					GROUP BY pl.id
					ORDER BY pl.id DESC";


        return $this->db->query($rowBillQuery)->result_array();
    }
    
    function getSalesGroupByMonth($filterParams)
    {
        $filterParams = $this->security->xss_clean($filterParams);

        $fromDate = $this->db->escape($filterParams['from_date']);
        $toDate = $this->db->escape($filterParams['to_date']);
        $customer = $this->db->escape($filterParams['customer']);
        $item = $this->db->escape($filterParams['item']);

        $searchQuery = " TRUE AND "; // Starting where clause
        if (!empty($filterParams['customer']) && $filterParams['customer'] !== "0")
            $searchQuery .= " pl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " pd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause
        if (empty($filterParams['from_date']) && $filterParams['from_date'] == NULL && empty($filterParams['from_date'])){
            $searchQuery .= " GROUP BY DATE_FORMAT(pl.created_at, '%Y-%m')"; // Ending where clause
        }else{
            $searchQuery .= " GROUP BY pl.id"; // Ending where clause
        }
        
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        
        $rowItemQuery = "SELECT
                        pl.sequence_code,
                        pl.id AS purchase_id,
                        DATE_FORMAT(pl.date, '%d-%m-%Y') AS date,
						SUM(pd.gross_weight) AS gross_wt,
                        SUM(pd.less_weight) AS less_wt,
						SUM(pd.net_weight) AS net_wt,
						SUM(pd.sub_total) AS amount,
						SUM(pd.fine) AS fine,
						SUM(pd.pre_touch) AS pre_touch,
						DATE_FORMAT(pl.created_at, '%Y-%M') AS year,
                        MAX(pd.created_at) as createdAt
					FROM purchase_detail pd
                    LEFT JOIN purchase pl ON pl.id = pd.purchase_id
					WHERE $searchQuery
					ORDER BY YEAR(pl.created_at) DESC, MONTH(pl.created_at) DESC, DAY(pl.date) DESC";
        return $this->db->query($rowItemQuery)->result_array();
    }

    function getSalesGroupByVoucher($filterParams)
    {
        $filterParams = $this->security->xss_clean($filterParams);

        $fromDate = $this->db->escape($filterParams['from_date']);
        $toDate = $this->db->escape($filterParams['to_date']);
        $customer = $this->db->escape($filterParams['customer']);
        $item = $this->db->escape($filterParams['item']);

        $searchQuery = " TRUE AND "; // Starting where clause
        if (!empty($filterParams['customer']) && $filterParams['customer'] !== "0")
            $searchQuery .= " pl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " pd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(pl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause
        
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        // Generate a query to get the sales group by sale-detail
        $rowVoucherQuery = "SELECT
                        I.name AS item_name,
						CUS.name AS customer,
                        CI.name AS city,
                        DATE_FORMAT(pl.date, '%d-%m-%Y') AS date,
						SUM(pd.gross_weight) AS gross_wt,
                        SUM(pd.less_weight) AS less_wt,
						SUM(pd.net_weight) AS net_wt,
						SUM(pd.sub_total) AS amount,
						SUM(pd.fine) AS fine,
						SUM(pd.pre_touch) AS pre_touch,
                        MAX(pd.remark) as remark,
                        MAX(pd.created_at) as createdAt
					FROM purchase_detail pd
                    LEFT JOIN purchase pl ON pl.id = pd.purchase_id
                    LEFT JOIN item I ON I.id = pd.item_id
                    LEFT JOIN customer CUS ON CUS.id = pl.party_id
                    LEFT JOIN city CI ON CI.id = CUS.city_id
					WHERE $searchQuery
					GROUP BY pd.id
					ORDER BY pl.id DESC";


        return $this->db->query($rowVoucherQuery)->result_array();
    }
}
