<?php

defined('BASEPATH') or exit('Direct script not allowed');

class Lot_report_model extends CI_Model
{

	private $dbh;
	function __construct()
	{
		parent::__construct();
		// $this->load->helper('unkidgen');
		$this->load->library('Joinhelper');
		$this->dbh = $this->joinhelper;
	}

	function getLotReportByLot($data)
	{
		$fromDate   = $this->db->escape($this->security->xss_clean($data['fromDate']));
		$toDate     = $this->db->escape($this->security->xss_clean($data['toDate']));
		$itemId      = $this->db->escape($this->security->xss_clean($data['itemId']));
		$tagNo       = $this->db->escape($this->security->xss_clean($data['tagNo']));
		$grossWeight = $this->db->escape($this->security->xss_clean($data['grossWeight']));
		$netWeight   = $this->db->escape($this->security->xss_clean($data['netWeight']));

		$searchQuery = " TRUE AND ";
		$havingQuery = " TRUE AND ";
		if (!empty($data['grossWeight']) && $data['grossWeight'] !== NULL)
			$searchQuery .= " LC.gross_weight = $grossWeight AND ";
		if (!empty($data['netWeight']) && $data['netWeight'] !== NULL)
			$searchQuery .= " `LC`.`net_weight` = $netWeight AND ";
		if (!empty($data['tagNo']) && $data['tagNo'] !== NULL)
			$searchQuery .= " LC.tag = $tagNo AND ";
		if (!empty($data['fromDate']) && $data['fromDate'] !== NULL)
			$searchQuery .= "LC.creation_date >= DATE($fromDate) AND ";
		if (!empty($data['toDate']) && $data['toDate'] !== NULL)
			$searchQuery .= "LC.creation_date <= DATE($toDate) AND ";
		if (!empty($data['itemId']) && $data['itemId'] !== "0")
			$searchQuery .= " LC.item_id = $itemId AND ";
		$searchQuery .= " TRUE ";
		$havingQuery .= " TRUE ";

		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		$q = "SELECT 
				LC.item_id,
				LC.id AS lot_creation_id,
				LC.piece,
				LC.net_weight,
				LC.created_at,
				LC.updated_at,
				LC.gross_weight,
				LC.l_weight,
				LC.amt,
				LC.tag AS `tag`,
				LC.barcode AS `barcode`,
				item.name AS `item_name`,
				LC.status
				FROM lot_creation LC
			LEFT JOIN item ON LC.item_id = item.id
			WHERE $searchQuery  
			GROUP BY `barcode` 
			HAVING $havingQuery";
		return $this->db->query($q)->result_array();
	}

	function getLotReportByTag($data)
	{
		$fromDate   = $this->db->escape($this->security->xss_clean($data['fromDate']));
		$toDate     = $this->db->escape($this->security->xss_clean($data['toDate']));
		$itemId      = $this->db->escape($this->security->xss_clean($data['itemId']));
		$tagNo       = $this->db->escape($this->security->xss_clean($data['tagNo']));
		$grossWeight = $this->db->escape($this->security->xss_clean($data['grossWeight']));
		$netWeight   = $this->db->escape($this->security->xss_clean($data['netWeight']));

		$searchQuery = " TRUE AND ";
		if (!empty($data['grossWeight']) && $data['grossWeight'] !== NULL)
			$searchQuery .= " LC.gross_weight = $grossWeight AND ";
		if (!empty($data['netWeight']) && $data['netWeight'] !== NULL)
			$searchQuery .= " `LC`.`net_weight` = $netWeight AND ";
		if (!empty($data['tagNo']) && $data['tagNo'] !== NULL)
			$searchQuery .= " LC.tag = $tagNo AND ";
		if (!empty($data['fromDate']) && $data['fromDate'] !== NULL)
			$searchQuery .= "LC.creation_date >= DATE($fromDate) AND ";
		if (!empty($data['toDate']) && $data['toDate'] !== NULL)
			$searchQuery .= "LC.creation_date <= DATE($toDate) AND ";
		if (!empty($data['itemId']) && $data['itemId'] !== "0")
			$searchQuery .= " LC.item_id = $itemId AND ";
		$searchQuery .= " TRUE ";
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		$q = "SELECT 
				LC.item_id,
				LC.id AS lot_creation_id,
				LC.piece,
				LC.net_weight,
				LC.created_at,
				LC.updated_at,
				LC.gross_weight,
				LC.l_weight,
				LC.amt,
				LC.tag AS `tag`,
				LC.barcode AS `barcode`,
				item.name AS `item_name`,
				LC.status
				FROM lot_creation LC
			LEFT JOIN item ON LC.item_id = item.id
			WHERE $searchQuery  
			GROUP BY LC.id ";

		return $this->db->query($q)->result_array();
	}

	function getLotReportByItem($data)
	{
		$fromDate   = $this->db->escape($this->security->xss_clean($data['fromDate']));
		$toDate     = $this->db->escape($this->security->xss_clean($data['toDate']));
		$itemId      = $this->db->escape($this->security->xss_clean($data['itemId']));
		$tagNo       = $this->db->escape($this->security->xss_clean($data['tagNo']));
		$grossWeight = $this->db->escape($this->security->xss_clean($data['grossWeight']));
		$netWeight   = $this->db->escape($this->security->xss_clean($data['netWeight']));

		$searchQuery = " TRUE AND ";
		$havingQuery = " TRUE AND ";
		if (!empty($data['grossWeight']) && $data['grossWeight'] !== NULL)
			$searchQuery .= " LC.gross_weight = $grossWeight AND ";
		if (!empty($data['netWeight']) && $data['netWeight'] !== NULL)
			$searchQuery .= " `LC`.`net_weight` = $netWeight AND ";
		if (!empty($data['tagNo']) && $data['tagNo'] !== NULL)
			$searchQuery .= " LC.tag = $tagNo AND ";
		if (!empty($data['fromDate']) && $data['fromDate'] !== NULL)
			$searchQuery .= "LC.creation_date >= DATE($fromDate) AND ";
		if (!empty($data['toDate']) && $data['toDate'] !== NULL)
			$searchQuery .= "LC.creation_date <= DATE($toDate) AND ";
		if (!empty($data['itemId']) && $data['itemId'] !== "0")
			$searchQuery .= " LC.item_id = $itemId AND ";
		$searchQuery .= " TRUE ";
		$havingQuery .= " TRUE ";
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		$q = "SELECT 
				LC.item_id,
				LC.id AS lot_creation_id,
				LC.piece,
				LC.net_weight,
				LC.created_at,
				LC.updated_at,
				LC.gross_weight,
				LC.l_weight,
				LC.amt,
				LC.tag AS `tag`,
				LC.barcode AS `barcode`,
				item.name AS `item_name`,
				LC.status
				FROM lot_creation LC
			LEFT JOIN item ON LC.item_id = item.id
			WHERE $searchQuery  
			GROUP BY item.id 
			HAVING $havingQuery";

		return $this->db->query($q)->result_array();
	}

	function getLotReportByallad($data)
	{
		$itemCatId  = $this->db->escape($this->security->xss_clean($data['itemCat']));
		$customerId = $this->db->escape($this->security->xss_clean($data['customerId']));
		$fromDate   = $this->db->escape($this->security->xss_clean($data['fromDate']));
		$toDate     = $this->db->escape($this->security->xss_clean($data['toDate']));
		// $groupBy = 			$this->db->escape($this->security->xss_clean($data['groupBy']));
		$itemGroupId = $this->db->escape($this->security->xss_clean($data['itemGroupId']));
		$itemId      = $this->db->escape($this->security->xss_clean($data['itemId']));
		$userId      = $this->db->escape($this->security->xss_clean($data['userId']));
		$tagNo       = $this->db->escape($this->security->xss_clean($data['tagNo']));
		$isSales     = $this->db->escape($this->security->xss_clean($data['isSold']));
		$grossWeight = $this->db->escape($this->security->xss_clean($data['grossWeight']));
		$netWeight   = $this->db->escape($this->security->xss_clean($data['netWeight']));

		$searchQuery = " TRUE AND ";
		// $havingQuery = " TRUE AND ";
		if ($data['isSold'] != "" && $data['isSold'] != NULL)
			$searchQuery .= " LC.status = $isSales AND ";

		if ($data['itemCat'] !== "0")
			$searchQuery .= "IC.id = $itemCatId AND ";
		if ($data['customerId'] !== "0")
			$searchQuery .= " LC.customer_id = $customerId AND ";
		if ($data['grossWeight'] !== NULL && !empty($data['grossWeight']))
			$searchQuery .= " LC.gross_weight = $grossWeight AND ";
		if ($data['netWeight'] !== NULL && !empty($data['netWeight']))
			$searchQuery .= " `LC`.`net_weight` = $netWeight AND ";
		if ($data['tagNo'] !== NULL && !empty($data['tagNo']))
			$searchQuery .= " LC.tag = $tagNo AND ";
		if ($data['fromDate'] !== NULL)
			$searchQuery .= " DATE(LC.created_at) >= DATE($fromDate) AND ";
		if ($data['toDate'] !== NULL)
			$searchQuery .= " DATE(LC.created_at) <= DATE($toDate) AND ";
		if ($data['itemGroupId'] !== "0")
			$searchQuery .= " LC.items_group_id = $itemGroupId AND ";
		if ($data['itemId'] !== "0") {
			$searchQuery .= " LC.item_id = $itemId AND ";
		} else {
			$searchQuery .= " LC.item_id = 0 AND ";
		}
		if ($data['userId'] !== "0")
			$searchQuery .= " LC.admin_id = $userId AND ";
		$searchQuery .= " TRUE ";


		$q = "SELECT 
				
				`LC`.`items_group_id` AS `group_id`,
				`LC`.`item_id` AS `item_id`,
				`LC`.`customer_id` AS `customer_id`,
				`customer`.`name` AS `customer`,
				
				 LC.id AS lot_creation_id,
				'1' AS piece,
				`LC`.`net_weight` AS `net_weight`,
				`LC`.`created_at` AS `created_at`,
				
				LC.gross_weight,
				LC.gross_weight - `LC`.`net_weight` AS `l_weight`,
				LC.amt `other_amt`,
				`LC`.`tag` AS `tag`,
				`item`.`name` AS `item_name`,
				LC.status,
				IC.name AS item_category,
				IG.group_name,
				LC.rose,
				LC.yellow,
				LC.design_code
				
			FROM lot_creation LC
			LEFT JOIN customer ON customer.id = LC.`customer_id`
			LEFT JOIN item ON LC.item_id = item.id
			LEFT JOIN item_category IC ON IC.id = item.item_category_id
			LEFT JOIN items_group IG ON IG.id = LC.items_group_id
			WHERE $searchQuery
			GROUP BY LC.design_code
			order by LC.id desc
			";
		return $this->db->query($q)->result_array();
	}
}
