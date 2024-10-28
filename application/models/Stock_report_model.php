<?php

defined('BASEPATH') or exit('Direct Script not allowed!');
class Stock_report_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('stock');
	}

	function opening($data, $groupBy = 'item_id')
	{
		$fromDate = 		$this->db->escape($this->security->xss_clean($data['fromDate']));
		$userId = 			$this->db->escape($this->security->xss_clean($data['userId']));
		$where = " TRUE AND ";
		if ($data['fromDate'] !== NULL) $where .= " DATE(SR.`date`) < DATE($fromDate) AND ";

		if ($data['userId'] !== "0") $where .= " SR.user_id = $userId AND ";
		$where .= " TRUE ";

		//SALES
		$getSalesQuery = getSalesQuery();
		$salesReturn = getSalesReturnQuery();

        // PURCHASE
		$purchase = getPurchaseQuery();
		$purchaseReturn = getPurchaseReturnQuery();
		
		// Lot Manage
		$lotManage = getLotQuery();

		$q = "SELECT 
			SUM(piece) AS piece,
			SUM(gross_weight) AS gross_weight, 
			SUM(net_weight) AS net_weight,
			SUM(fine) AS fine,
			SUM(other_amount) AS other_amount,
			item_category,
			item_name,
			item_group_name,
			`date`,
			item_id,
			item_category_id,
			items_group_id,
			user_id,
			`type`
		FROM
		(
			$purchase
			UNION ALL $salesReturn
			UNION ALL $purchaseReturn
			UNION ALL $getSalesQuery
			UNION ALL $lotManage
			
		) SR
		WHERE $where
		GROUP BY $groupBy"; // group by item_id or items_group_id
		return $q;
	}

	function data($data, $groupBy = 'item_id')
	{
		$fromDate = 		$this->db->escape($this->security->xss_clean($data['fromDate']));
		$toDate = 			$this->db->escape($this->security->xss_clean($data['toDate']));
		$userId = 			$this->db->escape($this->security->xss_clean($data['userId']));
		$where = " TRUE AND ";
		if ($data['fromDate'] !== NULL) $where .= " DATE(SR.`date`) >= DATE($fromDate) AND ";
		if ($data['toDate'] !== NULL) $where .= " DATE(SR.`date`) <= DATE($toDate) AND ";
		if ($data['userId'] !== "0") $where .= " SR.user_id = $userId AND ";
		$where .= " TRUE ";

		//SALES
		$getSalesQuery = getSalesQuery();
		$salesReturn = getSalesReturnQuery();

        // PURCHASE
		$purchase = getPurchaseQuery();
		$purchaseReturn = getPurchaseReturnQuery();
		
		// Lot Manage
		$lotManage = getLotQuery();

		$q = "SELECT 
			SUM(piece) AS piece,
			SUM(gross_weight) AS gross_weight, 
			SUM(net_weight) AS net_weight,
			SUM(fine) AS fine,
			SUM(other_amount) AS other_amount,
			item_name,
			`date`,
			item_id,
			user_id,
			(SUM(CASE WHEN report_type = 'SALES' THEN piece ELSE 0 END) * -1) AS s_pcs,
			(SUM(CASE WHEN report_type = 'SALES' THEN gross_weight ELSE 0 END) * -1) AS s_gross_weight,
			(SUM(CASE WHEN report_type = 'SALES' THEN net_weight ELSE 0 END) * -1) AS s_net_weight,
			(SUM(CASE WHEN report_type = 'SALES' THEN fine ELSE 0 END) * -1) AS s_fine_weight,
			(SUM(CASE WHEN report_type = 'SALES' THEN other_amount ELSE 0 END) * -1) AS s_other_amt,
			SUM(CASE WHEN report_type = 'SALES_RETURN' THEN piece ELSE 0 END) AS sr_pcs,
			SUM(CASE WHEN report_type = 'SALES_RETURN' THEN gross_weight ELSE 0 END) AS sr_gross_weight,
			SUM(CASE WHEN report_type = 'SALES_RETURN' THEN net_weight ELSE 0 END) AS sr_net_weight,
			SUM(CASE WHEN report_type = 'SALES_RETURN' THEN fine ELSE 0 END) AS sr_fine_weight,
			SUM(CASE WHEN report_type = 'SALES_RETURN' THEN other_amount ELSE 0 END) AS sr_other_amt,
			SUM(CASE WHEN report_type = 'PURCHASE' THEN piece ELSE 0 END) AS p_pcs,
			SUM(CASE WHEN report_type = 'PURCHASE' THEN gross_weight ELSE 0 END) AS p_gross_weight,
			SUM(CASE WHEN report_type = 'PURCHASE' THEN net_weight ELSE 0 END) AS p_net_weight,
			SUM(CASE WHEN report_type = 'PURCHASE' THEN fine ELSE 0 END) AS p_fine_weight,
			SUM(CASE WHEN report_type = 'PURCHASE' THEN other_amount ELSE 0 END) AS p_other_amt,
			(SUM(CASE WHEN report_type = 'PURCHASE_RETURN' THEN piece ELSE 0 END) * -1) AS pr_pcs,
			(SUM(CASE WHEN report_type = 'PURCHASE_RETURN' THEN gross_weight ELSE 0 END) * -1) AS pr_gross_weight,
			(SUM(CASE WHEN report_type = 'PURCHASE_RETURN' THEN net_weight ELSE 0 END) * -1) AS pr_net_weight,
			(SUM(CASE WHEN report_type = 'PURCHASE_RETURN' THEN fine ELSE 0 END) * -1) AS pr_fine_weight,
			(SUM(CASE WHEN report_type = 'PURCHASE_RETURN' THEN other_amount ELSE 0 END) * -1) AS pr_other_amt
		FROM
		(
			$purchase
			UNION ALL $salesReturn
			UNION ALL $purchaseReturn
			UNION ALL $getSalesQuery
			UNION ALL $lotManage
		) SR
		WHERE $where
		GROUP BY $groupBy"; // group by item_id or items_group_id

		return $q;
	}

	function generateStockReport($data, $groupBy = 'item_id')
	{
		$openingQ = $this->opening($data, $groupBy);
		$dataQ = $this->data($data, $groupBy);
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$q = "SELECT 
		            I.id,
		            I.name AS item_name,
			        OP.piece AS o_pcs,
					OP.gross_weight AS o_gross_weight,
					OP.fine AS o_fine_weight,
					OP.net_weight AS o_net_weight,
					OP.other_amount AS o_other_amt,
					DP.s_pcs,
					DP.s_gross_weight,
					DP.s_net_weight,
					DP.s_fine_weight,
					DP.s_other_amt,
					DP.sr_pcs,
					DP.sr_gross_weight,
					DP.sr_net_weight,
					DP.sr_fine_weight,
					DP.sr_other_amt,
					DP.p_pcs,
					DP.p_gross_weight,
					DP.p_net_weight,
					DP.p_fine_weight,
					DP.p_other_amt,
					DP.pr_pcs,
					DP.pr_gross_weight,
					DP.pr_net_weight,
					DP.pr_fine_weight,
					DP.pr_other_amt,
					DP.piece AS lr_pcs,
					DP.gross_weight AS lr_gross_weight,
					DP.net_weight AS lr_net_weight,
					DP.fine AS lr_fine_weight,
					DP.other_amount AS lr_other_amt,
				(COALESCE(DP.piece,0) + COALESCE(OP.piece,0)) AS c_pcs,
				(COALESCE(DP.gross_weight,0) + COALESCE(OP.gross_weight,0)) AS c_gross_weight,
				(COALESCE(DP.fine,0) + COALESCE(OP.fine,0)) AS c_fine_weight,
				(COALESCE(DP.net_weight,0) + COALESCE(OP.net_weight,0)) AS c_net_weight,
				(COALESCE(DP.other_amount,0) + COALESCE(OP.other_amount,0)) AS c_other_amt
			FROM item I
			LEFT JOIN ($openingQ) OP ON I.id = OP.item_id
			LEFT JOIN ($dataQ) DP ON I.id = DP.item_id
			WHERE TRUE ";
		if (!empty($data['itemId'])) {
			$q .= " AND I.id = " . $this->db->escape($data['itemId']);
		}
		$res = $this->db->query($q)->result_array();
	    return $res;
	}
}
