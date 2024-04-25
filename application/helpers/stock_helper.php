<?php

// function getMasterRMQuery()
// {
// 	return "SELECT 
// 				0 AS pcs,
// 				RM.`opening_weight` AS gross_weight,
// 				RM.opening_weight AS `net_weight`,
// 				RM.opening_fine AS fine_weight,
// 				RM.`opening_price` AS other_amt,
// 				'-' AS item_category,
// 				RM.name AS item_name,
// 				'raw_material' AS product_type,
// 				'-' AS item_group_name,
// 				DATE(RM.`created_at`) AS `date`,
// 				RM.id AS item_id,
// 				'0' AS item_category_id,
// 				'0' AS items_group_id,
// 				'0' AS `user_id`,
// 				'RAW_MATERIAL_MASTER' AS `type`,
// 				'OPENING' AS report_type,
// 					'' AS link
// 			FROM raw_material RM";
// }

function getSalesQuery()
{
	return "SELECT 
				(SI.`piece` * -1) AS piece,
				(SI.`gross_weight` * -1) AS gross_weight,
				(SI.`net_weight` * -1) AS net_weight,
				(SI.`fine` * -1) AS fine,
				(SI.`other_amount` * -1) AS other_amount,
				'' AS item_category,
				I.name AS item_name,
				'' AS item_group_name,
				DATE(S.`date`) AS `date`,
				I.id AS item_id,
				'' AS item_category_id,
				'' AS items_group_id,
				S.`user_id` AS user_id,
				
				'SALES' AS `type`,
				'SALES' AS report_type,
					'' AS link
			FROM sale_detail SI
			LEFT JOIN sale S ON S.id = SI.`sale_id`
			LEFT JOIN item I ON I.id = SI.`item_id`
			";
}

function getPurchaseReturnQuery()
{
	return "SELECT 
				(SUM(PII.piece) * -1) AS piece,
				(SUM(PII.gross_weight) * -1) AS gross_weight, 
				(SUM(PII.net_weight) * -1) AS net_weight,
				(SUM(PII.fine) * -1) AS fine,
				(SUM(PII.other_amount) * -1) AS other_amount,
				'' AS item_category,
				I.name AS item_name,
				'' AS item_group_name,
				DATE(P.date) AS `date`,
			    PII.item_id AS item_id,
				'' AS item_category_id,
				'' AS items_group_id,
				P.user_id AS user_id,
			
				'PURCHASE_RETURN' AS `type`,
				'PURCHASE_RETURN' AS report_type,
					'' AS link
			FROM purchase_return_detail PII
			LEFT JOIN purchase_return P ON P.id = PII.`purchase_id`
			LEFT JOIN item I ON I.id = PII.item_id
			GROUP BY PII.`item_id`,P.id";
}

function getPurchaseQuery()
{
	return "SELECT 
				SUM(PII.piece) AS piece,
				SUM(PII.gross_weight) AS gross_weight, 
				SUM(PII.net_weight) AS net_weight,
				SUM(PII.fine) AS fine,
				SUM(PII.other_amount) AS other_amount,
				'' AS item_category,
				I.name AS item_name,
				'' AS item_group_name,
				DATE(P.date) AS `date`,
				PII.item_id AS item_id,
			    '' AS item_category_id,
				'' AS items_group_id,
				P.user_id AS user_id,
			
				'PURCHASE' AS `type`,
				'PURCHASE' AS report_type,
					'' AS link
			FROM purchase_detail PII
			LEFT JOIN purchase P ON P.id = PII.`purchase_id`
			LEFT JOIN item I ON I.id = PII.item_id
			WHERE  PII.`id` NOT IN (SELECT item_id FROM lot_creation  WHERE item_id>0) 
			GROUP BY PII.`item_id`,P.id";
}

function getSalesReturnQuery()
{
	return "SELECT 
				SI.`piece` AS piece,
				SI.`gross_weight` AS gross_weight,
				SI.`net_weight` AS net_weight,
				SI.`fine` AS fine,
				SI.`other_amount` AS other_amount,
				'' AS item_category,
				I.name AS item_name,
				'' AS item_group_name,
				DATE(S.`date`) AS `date`,
				I.id AS item_id,
				'' AS item_category_id,
				'' AS items_group_id,
				S.`user_id` AS user_id,
				
				'SALES_RETURN' AS `type`,
				'SALES_RETURN' AS report_type,
					'' AS link
			FROM sale_return_detail SI
			LEFT JOIN sale_return S ON S.id = SI.`sale_id`
			LEFT JOIN item I ON I.id = SI.`item_id`
			GROUP BY S.id, I.id";
}

function getLotQuery()
{
	return "SELECT 
				(SI.`piece` * -1) AS piece,
				(SI.`gross_weight` * -1) AS gross_weight,
				(SI.`net_weight` * -1) AS net_weight,
				(SI.`l_weight` * -1) AS fine,
				(SI.`amt` * -1) AS other_amount,
				'' AS item_category,
				I.name AS item_name,
				'' AS item_group_name,
				DATE(SI.`creation_date`) AS `date`,
				I.id AS item_id,
				'' AS item_category_id,
				'' AS items_group_id,
				SI.`admin_id` AS user_id,
				
				'LOT' AS `type`,
				'LOT' AS report_type,
					'' AS link
			FROM lot_creation SI
			LEFT JOIN item I ON I.id = SI.`item_id`
			";
}
