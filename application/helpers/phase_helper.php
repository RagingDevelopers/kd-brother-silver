<?php

function isFilingCompleted($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		$ci->load->library('Joinhelper');
		$dbh = $ci->joinhelper;
		$filing = $dbh->fetchJoinedTableWhereRow('filing_items', ['filing'], ['filing_items.code' => $barcode]);
		if (!empty($filing['filing_loss']) || $filing['filing_loss'] != NULL) {
			return true;
		}
	}
	return false;
}

function isFirstPolishCompleted($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT FP.* FROM first_polish FP
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE FP.loss IS NOT NULL AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
	}
	return false;
}

function isSheetingCompleted($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT S.* FROM sheeting S
			LEFT JOIN first_polish FP ON FP.id = S.first_polish_id
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE S.loss IS NOT NULL AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
	}
	return false;
}

function isSecondPolishCompleted($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT SP.* FROM second_polish SP
			LEFT JOIN sheeting S ON S.id = SP.sheeting_id
			LEFT JOIN first_polish FP ON FP.id = S.first_polish_id OR FP.id = SP.first_polish_id
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE SP.loss IS NOT NULL AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
	}
	return false;
}

//this function can also be used for checking if barcode is even exists or not.
function isReceivedCastingExists($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$ci->load->library('Joinhelper');
	$dbh = $ci->joinhelper;
	return $dbh->isDataExists('received_casting', ['code' => $barcode]);
}

function filingByBarcode($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$barcode = $ci->db->escape($barcode);
	return $ci->db->query(
		"SELECT 
			GFI.pcs,FI.received_pcs,GFI.weight,GFI.weight AS gross_weight,FI.received_weight,
			FI.item_id,FI.customer_id,FI.received_dhal,FI.received_at,
			GFI.remark,FI.received_remark,
			F.*,FI.code
		FROM filing_items FI
		LEFT JOIN filing F ON FI.filing_id = F.id
		LEFT JOIN given_filing_items GFI ON GFI.filing_id = FI.filing_id
		WHERE FI.code = $barcode"
	)->row_array();
}

function firstPolishByBarcode($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$barcode = $ci->db->escape($barcode);
	return $ci->db->query(
		"SELECT FP.* FROM first_polish FP
		LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
		WHERE FI.code = $barcode"
	)->row_array();
}

function sheetingByBarcode($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$barcode = $ci->db->escape($barcode);
	return $ci->db->query(
		"SELECT 
			SI.pcs,SI.received_pcs,SI.weight,SI.weight AS gross_weight,SI.received_weight,
			SI.item_id,SI.customer_id,SI.received_dhal,SI.received_at,
			SI.remark,SI.received_remark,
			S.* 
		FROM sheeting S
		LEFT JOIN sheeting_items SI ON SI.sheeting_id = S.id
		LEFT JOIN first_polish FP ON FP.id = S.first_polish_id
		LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
		WHERE FI.code = $barcode"
	)->row_array();
}

function secondPolishByBarcode($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$barcode = $ci->db->escape($barcode);
	return $ci->db->query(
		"SELECT SP.* FROM second_polish SP
		LEFT JOIN sheeting S ON S.id = SP.sheeting_id
		LEFT JOIN first_polish FP ON FP.id = S.first_polish_id OR FP.id = SP.first_polish_id
		LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
		WHERE FI.code = $barcode"
	)->row_array();
}

function isItemPlanForOH($barcode)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	$barcode = $ci->db->escape($barcode);

	$RC = $ci->db->query(
		"SELECT CD.phase_type
		FROM common_data CD
		WHERE CD.code = $barcode AND CD.phase_type = 'sheeting'"
	)->num_rows();

	if ($RC < 1) {
		return true;
	}

	$RC = $ci->db->query(
		"SELECT IC.name 
		FROM common_data CD
		LEFT JOIN purchase_items PII ON PII.code = CD.code
		LEFT JOIN item I ON I.id = PII.item_id
		LEFT JOIN item_category IC ON IC.id = I.item_category_id
		WHERE CD.code = $barcode"
	)->row_array();
	if (empty($RC))
		return false;
	return (strcasecmp("Plan", $RC['name']) === 0);
}

function isItemPlan($barcode = 0)
{
	if (strlen($barcode) == 8) {
		return isItemPlanForOH($barcode);
	} else {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		$barcode = $ci->db->escape($barcode);
		$RC = $ci->db->query(
			"SELECT IC.name 
		FROM filing_items FI
		LEFT JOIN filing F ON F.id = FI.filing_id
		LEFT JOIN received_casting RC ON RC.id = F.received_casting_id
		LEFT JOIN item I ON I.id = FI.item_id
		LEFT JOIN item_category IC ON IC.id = I.item_category_id
		WHERE FI.code = $barcode"
		)->row_array();
		if (empty($RC))
			return false;
		else if (strcasecmp("Plan", $RC['name']) === 0)
			return "Plan";
		else if ((strcasecmp("WEX", $RC['name']) === 0))
			return "WEX";
		else return false;
		// return () || (strcasecmp("WEX", $RC['name']) === 0);
	}
}
function isProcessItemPlan($barcode = 0)
{
	// if (strlen($barcode) == 8) {
	// 	return isItemPlanForOH($barcode);
	// } else {
		$ci = ci();
		$barcode = $ci->security->xss_clean($barcode);
		$barcode = $ci->db->escape($barcode);
		$RC = $ci->db->query(
			"SELECT I.name 
				FROM receive R
				LEFT JOIN given G ON G.id = R.given_id
				LEFT JOIN item I ON I.id = R.item_id
			WHERE R.code = $barcode"
		)->row_array();

		if (empty($RC))
			return false;
		else if (strcasecmp("Plan", $RC['name']) === 0)
			return "Plan";
		else if ((strcasecmp("WEX", $RC['name']) === 0))
			return "WEX";
		else return false;
		// return () || (strcasecmp("WEX", $RC['name']) === 0);
	// }
}

function isFilingSkipped($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		$ci->load->library('Joinhelper');
		$dbh = $ci->joinhelper;
		$filing = $dbh->fetchJoinedTableWhereRow('filing_items', ['filing'], ['filing_items.code' => $barcode]);
		if ($filing['pcs'] == 0 || $filing['received_pcs'] == 0 || $filing['weight'] == 0 || $filing['received_weight'] == 0) {
			return true;
		}
	}
	return false;
}

function isFirstPolishSkipped($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT FP.* FROM first_polish FP
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE FP.pcs = 0
				AND FP.received_pcs = 0
				AND FP.weight = 0
				AND FP.received_weight = 0
				AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
		pre($ci->db->last_query());
	}
	return false;
}

function isSheetingSkipped($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT SI.* FROM sheeting_items SI
			LEFT JOIN sheeting S ON S.id = SI.sheeting_id
			LEFT JOIN first_polish FP ON FP.id = S.first_polish_id
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE SI.pcs = 0 
				AND SI.weight = 0
				AND SI.received_pcs = 0
				AND SI.received_weight = 0
				AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
	}
	return false;
}

function isSecondPolishSkipped($barcode = 0)
{
	if ($barcode > 0) {
		$ci = &get_instance();
		$barcode = $ci->security->xss_clean($barcode);
		return $ci->db->query(
			"SELECT SP.* FROM second_polish SP
			LEFT JOIN sheeting S ON S.id = SP.sheeting_id
			LEFT JOIN first_polish FP ON FP.id = S.first_polish_id OR FP.id = SP.first_polish_id
			LEFT JOIN filing_items FI ON FI.id = FP.filing_items_id
			WHERE SP.pcs = 0
				AND SP.received_pcs = 0
				AND SP.weight = 0
				AND SP.received_weight = 0 
				AND FI.code = " . $ci->db->escape($barcode)
		)->num_rows() > 0;
	}
	return false;
}

function purity($barcode = 0)
{
	$ci = &get_instance();
	$res = $ci->db->query(
		"SELECT IG.group_name AS purity 
		FROM filing_items FI
		LEFT JOIN filing F ON F.id = FI.filing_id
		LEFT JOIN casting C ON C.id = F.casting_id
		LEFT JOIN melting M ON M.id = C.melting_id
		LEFT JOIN items_group IG ON IG.id = M.items_group_id
		WHERE FI.code = " . $ci->db->escape($barcode)
	)->row_array();
	if (isset($res['purity']))
		return $res['purity'];
	else
		return '';
}

function purityDecimal($barcode = 0)
{
	$ci = &get_instance();
	return $ci->db->query(
		"SELECT M.purity
		FROM filing_items FI
		LEFT JOIN filing F ON F.id = FI.filing_id
		LEFT JOIN casting C ON C.id = F.casting_id
		LEFT JOIN melting M ON M.id = C.melting_id
		WHERE FI.code = " . $ci->db->escape($barcode)
	)->row_array()['purity'];
}

//Will exclude skipped phase, Start From Filing 
function getLastDonePhase($barcode = 0)
{
	$ci = &get_instance();
	$barcode = $ci->security->xss_clean($barcode);
	if (!isSecondPolishSkipped($barcode)) {
		if (isSecondPolishCompleted($barcode)) {
			$data = secondPolishByBarcode($barcode);
			$data['last_phase'] = 'Second Polish';
			$data['purity'] = purity($barcode);
			return $data;
		} else {
			if (!isSheetingSkipped($barcode)) {
				if (isSheetingCompleted($barcode)) {
					$data = sheetingByBarcode($barcode);
					$data['last_phase'] = 'Sheeting';
					$data['purity'] = purity($barcode);
					return $data;
				} else {
					if (!isFirstPolishSkipped($barcode)) {
						if (isFirstPolishCompleted($barcode)) {
							$data = firstPolishByBarcode($barcode);
							$data['last_phase'] = 'First Polish';
							$data['purity'] = purity($barcode);
							return $data;
						} else {
							if (!isFilingSkipped($barcode)) {
								if (isFilingCompleted($barcode)) {
									$data = filingByBarcode($barcode);
									$data['last_phase'] = 'Filing';
									$data['purity'] = purity($barcode);
									return $data;
								}
							}
						}
					}
				}
			}
		}
	}
}

function updateCostingStatus($table, $ids, $house_type)
{
	$ci = &get_instance();
	if ($house_type == 'inhouse') {
		$ci->db->query(
			"UPDATE $table SET costing_status = '1' WHERE id IN ($ids)"
		);
		if ($table == 'filing') {
			$ci->db->query(
				"UPDATE filing_items SET costing_status = '1' WHERE filing_id IN ($ids)"
			);
		}
	} else if ($house_type == 'outhouse') {
		if ($table == 'rm_polish') {
			$ci->db->query(
				"UPDATE purchase_rm_polish SET costing_status = '1' WHERE id IN ($ids)"
			);
		} else {
			$ci->db->query(
				"UPDATE common_data SET costing_status = '1' WHERE phase_type = " . $ci->db->escape($table) . " AND id IN ($ids)"
			);
		}
	}
}
