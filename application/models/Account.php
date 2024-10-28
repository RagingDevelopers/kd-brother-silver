<?php
class Account extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function account_type()
    {
        $this->db->order_by("id", "ASC");
        $query = $this->db->get("account_type");
        return $query->result_array();
    }

    function bank()
    {
        $this->db->order_by("name", "DESC");
        $query = $this->db->get("bank");
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
            $searchQuery .= " sl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " sd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause

        // Generate a query to get the sales group by item
        $rowItemQuery = "SELECT
						SUM(sd.gross_weight) AS gross_wt,
                        SUM(sd.less_weight) AS less_wt,
						SUM(sd.net_weight) AS net_wt,
						SUM(sd.sub_total) AS amount,
						SUM(sd.fine) AS fine,
						SI.name AS item,
                        MAX(sd.created_at) as createdAt
					FROM sale_detail sd
                    LEFT JOIN sale sl ON sl.id = sd.sale_id
					LEFT JOIN item SI ON SI.id = sd.item_id
					WHERE $searchQuery
					GROUP BY sd.item_id";


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
            $searchQuery .= " sl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " sd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause

        // Generate a query to get the sales group by customer
        $rowCustomerQuery = "SELECT
						CUS.name AS customer,
						SUM(sd.gross_weight) AS gross_wt,
                        SUM(sd.less_weight) AS less_wt,
						SUM(sd.net_weight) AS net_wt,
						SUM(sd.sub_total) AS amount,
						SUM(sd.fine) AS fine,
                        MAX(sd.created_at) as createdAt
					FROM sale_detail sd
                    LEFT JOIN sale sl ON sl.id = sd.sale_id
                    LEFT JOIN customer CUS ON CUS.id = sl.party_id
					WHERE $searchQuery
					GROUP BY sl.party_id";


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
            $searchQuery .= " sl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " sd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause

        // Generate a query to get the sales group by sale
        $rowBillQuery = "SELECT
                        sl.id AS sale_id,
						CUS.name AS customer,
                        CI.name AS city,
                        DATE_FORMAT(sl.date, '%d-%m-%Y') AS date,
						SUM(sd.gross_weight) AS gross_wt,
                        SUM(sd.less_weight) AS less_wt,
						SUM(sd.net_weight) AS net_wt,
						SUM(sd.sub_total) AS amount,
						SUM(sd.fine) AS fine,
                        MAX(sd.remark) as remark,
                        MAX(sd.created_at) as createdAt
					FROM sale_detail sd
                    LEFT JOIN sale sl ON sl.id = sd.sale_id
                    LEFT JOIN customer CUS ON CUS.id = sl.party_id
                    LEFT JOIN city CI ON CI.id = CUS.city_id
					WHERE $searchQuery
					GROUP BY sl.id";


        return $this->db->query($rowBillQuery)->result_array();
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
            $searchQuery .= " sl.party_id = $customer AND "; // Add customer condition
        if (!empty($filterParams['item']) && $filterParams['item'] !== "0")
            $searchQuery .= " sd.item_id = $item AND "; // Add item condition
        if (!empty($filterParams['from_date']) && $filterParams['from_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) >= DATE($fromDate) AND "; // Add from date condition
        if (!empty($filterParams['to_date']) && $filterParams['to_date'] !== NULL && !empty($filterParams['from_date']))
            $searchQuery .= " DATE(sl.date) <= DATE($toDate) AND "; // Add to date condition
        $searchQuery .= " TRUE "; // Ending where clause

        // Generate a query to get the sales group by sale-detail
        $rowVoucherQuery = "SELECT
                        MAX(SI.name) AS item_name,
						CUS.name AS customer,
                        CI.name AS city,
                        DATE_FORMAT(sl.date, '%d-%m-%Y') AS date,
						SUM(sd.gross_weight) AS gross_wt,
                        SUM(sd.less_weight) AS less_wt,
						SUM(sd.net_weight) AS net_wt,
						SUM(sd.sub_total) AS amount,
						SUM(sd.fine) AS fine,
                        MAX(sd.remark) as remark,
                        MAX(sd.created_at) as createdAt
					FROM sale_detail sd
                    LEFT JOIN sale sl ON sl.id = sd.sale_id
                    LEFT JOIN item SI ON SI.id = sd.item_id
                    LEFT JOIN customer CUS ON CUS.id = sl.party_id
                    LEFT JOIN city CI ON CI.id = CUS.city_id
					WHERE $searchQuery
					GROUP BY sd.id";


        return $this->db->query($rowVoucherQuery)->result_array();
    }
}
