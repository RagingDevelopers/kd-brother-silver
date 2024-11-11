<?php

defined('BASEPATH') or exit('Direct Script not allowed!');

class AccountLedger extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getSearchQuery($table, $dateField, $fromDate, $toDate, $cid = 0, $ac_cat = 0, $sqother = [])
	{

		//  pre([$sqother,$table]);

		$q = " WHERE TRUE ";
		if (!empty($fromDate) && !empty($toDate)) {
			$fromDate = $this->db->escape($fromDate);
			$toDate   = $this->db->escape($toDate);
			$q .= " AND DATE($table.$dateField) >= DATE($fromDate) AND DATE($table.$dateField) <= DATE($toDate) ";
		} else if (!empty($toDate)) {
			$toDate = $this->db->escape($toDate);
			$q .= " AND DATE($table.$dateField) <= DATE($toDate) ";
		} else if (!empty($fromDate)) {
			$fromDate = $this->db->escape($fromDate);
			$q .= " AND DATE($table.$dateField) >= DATE($fromDate) ";
		}

		if ($sqother['master_type'] == 'bank' && $table == 'B') {
			if ($cid > 0 && $sqother['master_type'] != 'account_category') {
				$q .= " AND B.bank_id = " . $this->db->escape($cid);
			}
		} else if ($sqother['master_type'] == 'bank' && $table != 'B') {
			$q .= ' AND FALSE ';
		} else if ($sqother['master_type'] == 'account_category') {
			if ($ac_cat !== NULL && $ac_cat > 0 && $sqother['master_type'] == 'account_category') {
				$q .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
			}
			if ($cid > 0) {
				$cid = $this->db->escape($cid);
				if ($table == "F" || $table == "B" || $table == "RCR" || $table == "RCF" || $table == "ROOPU" || $table == "TE") {
					$q .= " AND $table.customer_id = $cid ";
				} else {
					$q .= " AND $table.party_id = $cid ";
				}
			}
		}
		return $q;
	}

	function getOpeningSearchQuery($table, $dateField, $fromDate, $cid = 0, $ac_cat = 0, $other = [])
	{

		$q = " WHERE TRUE ";
		if (!empty($fromDate)) {
			$fromDate = $this->db->escape($fromDate);
			$q .= " AND DATE($table.$dateField) < DATE($fromDate) ";
		} else {
			$q .= " AND FALSE ";
		}

		if ($other['master_type'] == 'bank' && $table == 'B') {
			if ($cid > 0) {
				$q .= " AND B.bank_id = " . $this->db->escape($cid);
			}
		} else if ($other['master_type'] == 'bank' && $table != 'B') {
			$q .= ' AND FALSE ';
		} else if ($other['master_type'] == 'account_category') {
			if ($ac_cat !== NULL && $ac_cat > 0 && $other['master_type'] == 'account_category') {
				$q .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
			}
			if ($cid > 0) {
				$cid = $this->db->escape($cid);
				if ($table == "F" || $table == "B" || $table == "RCR" || $table == "RCF" || $table == "ROOPU" || $table == "TE") {
					$q .= " AND $table.customer_id = $cid ";
				} else {
					$q .= " AND $table.party_id = $cid ";
				}
			}
		}
		return $q;
	}

	function getLedgerQuery($opening = false, $fromDate = null, $toDate = null, $cid = 0, $ac_cat = 0, $lother = [])
	{
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$cid = (int) $cid;
		$fn  = $opening ? "getOpeningSearchQuery" : "getSearchQuery";
		if ($opening) {
			$purchaseWhere                     = $this->getOpeningSearchQuery('PII', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$purchaseReturnWhere               = $this->getOpeningSearchQuery('PRI', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$salesWhere                        = $this->getOpeningSearchQuery('SI', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$salesReturnWhere                  = $this->getOpeningSearchQuery('SRI', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$transferEntryTransferWhere        = $this->getOpeningSearchQuery('TE', 'created_at', $fromDate, 0, 0, $lother);
			$transferEntryWhere                = $this->getOpeningSearchQuery('TE', 'created_at', $fromDate, $cid, $ac_cat, $lother);

			$FPaymentWhere                      = $this->getOpeningSearchQuery('F', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$BPaymentWhere                      = $this->getOpeningSearchQuery('B', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$RCFPaymentWhere                    = $this->getOpeningSearchQuery('RCF', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$RCRPaymentWhere                    = $this->getOpeningSearchQuery('RCR', 'created_at', $fromDate, $cid, $ac_cat, $lother);
			$ROOPUPaymentWhere                  = $this->getOpeningSearchQuery('ROOPU', 'created_at', $fromDate, $cid, $ac_cat, $lother);
		} else {
			$purchaseWhere                     = $this->getSearchQuery('PII', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$purchaseReturnWhere               = $this->getSearchQuery('PRI', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$salesWhere                        = $this->getSearchQuery('SI', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$salesReturnWhere                  = $this->getSearchQuery('SRI', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$transferEntryWhere                = $this->getSearchQuery('TE', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$transferEntryTransferWhere        = $this->getSearchQuery('TE', 'created_at', $fromDate, $toDate, 0, 0, $lother);

			$FPaymentWhere                      = $this->getSearchQuery('F', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$BPaymentWhere                      = $this->getSearchQuery('B', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$RCFPaymentWhere                      = $this->getSearchQuery('RCF', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$RCRPaymentWhere                      = $this->getSearchQuery('RCR', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
			$ROOPUPaymentWhere                      = $this->getSearchQuery('ROOPU', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $lother);
		}

		if ($ac_cat !== NULL && $ac_cat > 0) {
			$transferEntryTransferWhere .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
			// $karigarTransferEntryTransferWhere .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
		}
		if ($cid > 0) {
			$c_id                              = $this->db->escape($cid);
			$transferEntryTransferWhere .= " AND TE.transfer_customer_id = $c_id ";
			// $karigarTransferEntryTransferWhere .= " AND KTE.transfer_customer_id = $c_id ";
		}

		$q = "SELECT PII.verification,PII.id,PII.date,'PUR' AS `type`,'PII' AS `code`,C.`name` AS customer_name,PII.`party_id`,
                    '' AS process,
                    '' AS remark,
                    '' AS bank_name,
					SUM(Pd.`sub_total`) AS total_net_amt,
					SUM(Pd.`fine`) AS total_fine_gold,
					CONCAT('purchase/edit/',PII.id) AS link,
					'0' AS loss,
					'0' AS is_not_shown,
					SUM(Pd.gross_weight) AS gross,
					SUM(Pd.less_weight) AS less,
					SUM(Pd.net_weight) AS net,
					SUM(Pd.rate) AS rate,
					(SUM(Pd.touch) + SUM(Pd.wastage)) AS purity
				FROM purchase PII 
				LEFT JOIN purchase_detail Pd ON PII.id = Pd.purchase_id
				LEFT JOIN customer C ON C.id = PII.party_id
				$purchaseWhere
				GROUP BY PII.id
            UNION ALL
				SELECT PRI.verification,PRI.id,PRI.date,'PUR_RETURN' AS `type`,'PRI' AS `code`,C.`name` AS customer_name,PRI.`party_id`,
                    '' AS process,
                    '' AS remark,
                    '' AS bank_name,
					SUM(PRd.sub_total) AS total_net_amt,
					SUM(PRd.fine) AS total_fine_gold,
					CONCAT('purchase_return/edit/',PRI.id) AS link,
					'0' AS loss,
					'0' AS is_not_shown,
					SUM(PRd.gross_weight) AS gross,
					SUM(PRd.less_weight) AS less,
					SUM(PRd.net_weight) AS net,
					SUM(PRd.rate) AS rate,
					(SUM(PRd.touch) + SUM(PRd.wastage)) AS purity
				FROM `purchase_return` PRI
				LEFT JOIN purchase_return_detail PRd ON PRI.id = PRd.purchase_id
				LEFT JOIN customer C ON C.id = PRI.party_id
				$purchaseReturnWhere
				GROUP BY PRI.id
            UNION ALL
				SELECT SI.verification,SI.id,SI.date,'SAL' AS `type`,'SI' AS `code`,C.`name` AS customer_name,SI.`party_id`,
                    '' AS process,
                    '' AS remark,
                    '' AS bank_name,
					SUM(SD.`sub_total`) AS total_net_amt,
					SUM(SD.`fine`) AS total_fine_gold,
					CONCAT('sales/edit/',SI.id) AS link,
					'0' AS loss,
					'0' AS is_not_shown,
					SUM(SD.gross_weight) AS gross,
					SUM(SD.less_weight) AS less,
					SUM(SD.net_weight) AS net,
					SUM(SD.rate) AS rate,
					(SUM(SD.touch) + SUM(SD.wastage)) AS purity
				FROM `sale` SI
				LEFT JOIN sale_detail SD ON SI.id = SD.sale_id
				LEFT JOIN customer C ON C.id = SI.party_id
				$salesWhere
				GROUP BY SI.id
            UNION ALL
				SELECT SRI.verification,SRI.ID,SRI.date,'SAL_RETURN' AS `type`,'SRI' AS `code`,C.`name` AS customer_name,SRI.`party_id`,
                    '' AS process,
                    '' AS remark,
                    '' AS bank_name,
					SUM(SRD.`sub_total`) AS total_net_amt,
					SUM(SRD.`fine`) AS total_fine_gold,
					CONCAT('sales_return/edit/',SRI.id) AS link,
					'0' AS loss,
					'0' AS is_not_shown,
					SUM(SRD.gross_weight) AS gross,
					SUM(SRD.less_weight) AS less,
					SUM(SRD.net_weight) AS net,
					SUM(SRD.rate) AS rate,
					(SUM(SRD.touch) + SUM(SRD.wastage)) AS purity
				FROM `sale_return` SRI
				LEFT JOIN sale_return_detail SRD ON SRI.id = SRD.sale_id
				LEFT JOIN customer C ON C.id = SRI.party_id
				$salesReturnWhere
                GROUP BY SRI.id

            UNION ALL
				SELECT F.verification AS verification,F.id,F.date,
                CASE WHEN F.payment_type = 'CREDIT' THEN 'FINE_CR'
						WHEN F.payment_type = 'DEBIT' THEN 'FINE_DB'
					END AS `type`,
                F.jama_code AS `code`,C.`name` AS customer_name,F.`customer_id` AS party_id,
					'' AS process,
                    F.remark,
                    bank.name AS bank_name,
					F.`amount` AS total_net_amt,
					F.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',F.jama_code,'/',F.customer_id) AS link,
					'0' AS loss,
					F.is_not_show AS is_not_shown,
					F.gross AS gross,
					'0' AS less,
					'0' AS net,
					F.rate AS rate,
					F.purity AS purity
				FROM `jama` F
				LEFT JOIN customer C ON C.id = F.customer_id
                LEFT JOIN bank ON bank.id = F.bank_id
				$FPaymentWhere AND F.`type`='fine'
				GROUP BY F.id

                UNION ALL
				SELECT B.verification AS verification,B.id,B.date,
                CASE WHEN B.payment_type = 'CREDIT' THEN 'BANK_CR'
						WHEN B.payment_type = 'DEBIT' THEN 'BANK_DB'
					END AS `type`,
                B.jama_code AS `code`,C.`name` AS customer_name,B.`customer_id` AS party_id,
					'' AS process,
                    B.remark,
                    bank.name AS bank_name,
					B.`amount` AS total_net_amt,
					B.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',B.jama_code,'/',B.customer_id) AS link,
					'0' AS loss,
					B.is_not_show AS is_not_shown,
					B.gross AS gross,
					'0' AS less,
					'0' AS net,
					B.rate AS rate,
					B.purity AS purity
				FROM `jama` B
				LEFT JOIN customer C ON C.id = B.customer_id
                LEFT JOIN bank ON bank.id = B.bank_id
				$BPaymentWhere AND B.`type`='bank'
				GROUP BY B.id

                UNION ALL
				SELECT RCF.verification AS verification,RCF.id,RCF.date,
                CASE WHEN RCF.payment_type = 'CREDIT' THEN 'RATEFINE_CR'
						WHEN RCF.payment_type = 'DEBIT' THEN 'RATEFINE_DB'
					END AS `type`,
                RCF.jama_code AS `code`,C.`name` AS customer_name,RCF.`customer_id` AS party_id,
					'' AS process,
                    RCF.remark,
                    bank.name AS bank_name,
					RCF.`amount` AS total_net_amt,
					RCF.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',RCF.jama_code,'/',RCF.customer_id) AS link,
					'0' AS loss,
					RCF.is_not_show AS is_not_shown,
					RCF.gross AS gross,
					'0' AS less,
					'0' AS net,
					RCF.rate AS rate,
					RCF.purity AS purity
				FROM `jama` RCF
				LEFT JOIN customer C ON C.id = RCF.customer_id
                LEFT JOIN bank ON bank.id = RCF.bank_id
				$RCFPaymentWhere AND RCF.`type`='ratecutfine'
				GROUP BY RCF.id

                UNION ALL
				SELECT RCR.verification AS verification,RCR.id,RCR.date,
                CASE WHEN RCR.payment_type = 'CREDIT' THEN 'RATERS_CR'
						WHEN RCR.payment_type = 'DEBIT' THEN 'RATERS_DB'
					END AS `type`,
                RCR.jama_code AS `code`,C.`name` AS customer_name,RCR.`customer_id` AS party_id,
					'' AS process,
                    RCR.remark,
                    bank.name AS bank_name,
					RCR.`amount` AS total_net_amt,
					RCR.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',RCR.jama_code,'/',RCR.customer_id) AS link,
					'0' AS loss,
					RCR.is_not_show AS is_not_shown,
					RCR.gross AS gross,
					'0' AS less,
					'0' AS net,
					RCR.rate AS rate,
					RCR.purity AS purity
				FROM `jama` RCR
				LEFT JOIN customer C ON C.id = RCR.customer_id
                LEFT JOIN bank ON bank.id = RCR.bank_id
				$RCRPaymentWhere AND RCR.`type`='ratecutrs'
				GROUP BY RCR.id  

                UNION ALL
				SELECT ROOPU.verification AS verification,ROOPU.id,ROOPU.date,
                CASE WHEN ROOPU.payment_type = 'CREDIT' THEN 'ROOPU_CR'
						WHEN ROOPU.payment_type = 'DEBIT' THEN 'ROOPU_DB'
					END AS `type`,
                ROOPU.jama_code AS `code`,C.`name` AS customer_name,ROOPU.`customer_id` AS party_id,
					'' AS process,
                    ROOPU.remark,
                    bank.name AS bank_name,
					ROOPU.`amount` AS total_net_amt,
					ROOPU.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',ROOPU.jama_code,'/',ROOPU.customer_id) AS link,
					'0' AS loss,
					ROOPU.is_not_show AS is_not_shown,
					ROOPU.gross AS gross,
					'0' AS less,
					'0' AS net,
					ROOPU.rate AS rate,
					ROOPU.purity AS purity
				FROM `jama` ROOPU
				LEFT JOIN customer C ON C.id = ROOPU.customer_id
                LEFT JOIN bank ON bank.id = ROOPU.bank_id
				$ROOPUPaymentWhere AND ROOPU.`type`='roopu'
				GROUP BY ROOPU.id

                UNION ALL
				SELECT TE.verification AS verification,TE.id,TE.date,
					CASE WHEN TE.payment_type = 'credit' THEN 'TE_CR'
						WHEN TE.payment_type = 'debit' THEN 'TE_DB'
					END AS `type`,
					CASE WHEN TE.narration IS NOT NULL THEN CONCAT('link','#',TE.narration)
						ELSE 'link' END AS `code`,C.name AS customer_name,TE.transfer_customer_id AS party_id,
                    '' AS process,
                    '' AS remark,
                    '' AS bank_name,
                    TE.total_amount AS total_net_amt,
					TE.gold AS total_fine_gold,
					CONCAT('payment/transfer_entry/edit/',TE.id) AS link,
					'0' AS loss,
					'0' AS is_not_shown,
					'0' AS gross,
					'0' AS less,
					'0' AS net,
					'0' AS rate,
					'0' AS purity
				FROM `transfer_entry` TE
				LEFT JOIN customer C ON C.id = TE.transfer_customer_id
				$transferEntryTransferWhere";


		// if (!empty($cid) && $cid > 0) {
		//     if ($other['master_type'] != 'bank') {
		//         $this->db->select('AC.name,AC.id');
		//         $this->db->from('customer C');
		//         $this->db->join('account_type AC', 'AC.id = C.account_type_id', 'left');
		//         $this->db->where('C.id', $cid);
		//         if (!empty($ac_cat) && $ac_cat > 0) {
		//             $this->db->where('AC.id', $ac_cat);
		//         }
		//         $AC = $this->db->get()->row_array();
		//         if (!empty($AC)) {
		//             $this->session->set_userdata('account_type', $AC['name']);
		//             if ($AC['name'] == 'karigar') {
		//                 $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, ((int) $AC['id']), $other);
		//                 $q .= " UNION ALL $additionalKarigarLedgerQuery ";
		//             }
		//         }
		//     }
		// } else if (!empty($ac_cat) && $ac_cat > 0) {
		//     if ($other['master_type'] != 'bank') {
		//         $AC = $this->db->get_where('account_type', [ 'id' => $ac_cat ])->row_array();
		//         $this->session->set_userdata('account_type', $AC['name']);
		//         if ($AC['name'] == 'karigar') {
		//             $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $other);
		//             $q .= " UNION ALL $additionalKarigarLedgerQuery ";
		//         }
		//     }
		// } else if ($ac_cat === -111) {
		//     $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $other);
		//     $q .= " UNION ALL $additionalKarigarLedgerQuery ";
		// }

		if (!empty($cid) && $cid > 0) {
			$this->db->select('AC.name,AC.id');
			$this->db->from('customer C');
			$this->db->join('account_type AC', 'AC.id = C.account_type_id', 'left');
			$this->db->where('C.id', $cid);
			if (!empty($ac_cat) && $ac_cat > 0) {
				$this->db->where('AC.id', $ac_cat);
			}
			$AC = $this->db->get()->row_array();
			$this->session->set_userdata('account_type', $AC['name']);
			// if ($AC['name'] == 'karigar') {
			// $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $AC['id'], $ig_id, $other);
			$additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $lother);
			$q .= " UNION ALL $additionalKarigarLedgerQuery ";
			// }
		} else if (!empty($ac_cat) && $ac_cat > 0) {
			$AC = $this->db->get_where('account_type', ['id' => $ac_cat])->row_array();
			if (!empty($AC)) {
				$this->session->set_userdata('account_type', $AC['name']);
				// if ($AC['name'] == 'karigar') {
				// $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $ig_id, $other);
				$additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $lother);
				$q .= " UNION ALL $additionalKarigarLedgerQuery ";
				// }
			}
		} else if ($ac_cat === -111) {
			$additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $lother);
			$q .= " UNION ALL $additionalKarigarLedgerQuery ";
		}

		$q .= " ORDER BY `date`";
		$query = $this->db->query($q);
		// $last_query = $this->db->last_query();
		// pre($last_query);exit;
		return $query;
	}

	function getKarigarSearchQuery($table, $dateField, $fromDate, $toDate, $cid, $ac_cat, $other = [])
	{
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$fromDate = $this->db->escape($fromDate);
		$toDate   = $this->db->escape($toDate);
		$q = " WHERE TRUE ";
		if (!empty($fromDate) && !empty($toDate)) {
			$q .= " AND DATE($table.$dateField) >= DATE($fromDate) AND DATE($table.$dateField) <= DATE($toDate) ";
		} else if (!empty($toDate)) {
			$toDate = $this->db->escape($toDate);
			$q .= " AND DATE($table.$dateField) <= DATE($toDate) ";
		} else if (!empty($fromDate)) {
			$fromDate = $this->db->escape($fromDate);
			$q .= " AND DATE($table.$dateField) >= DATE($fromDate) ";
		}

		if ($other['master_type'] == 'bank' && $table == 'PY') {
			// if ($cid > 0) {
			//     $q .= " AND PY.bank_id = " . $this->db->escape($cid);
			// }
		} else if ($other['master_type'] == 'bank' && $table != 'PY') {
			$q .= ' AND FALSE ';
		} else if ($other['master_type'] == 'account_category') {

			if ($ac_cat !== NULL && $ac_cat > 0) {
				$q .= " AND K.account_type_id = " . $this->db->escape($ac_cat) . " ";
			}

			if ($cid > 0) {
				$cid                   = $this->db->escape($cid);
				$karigarTableForColumn = [
					"G",
					'R',
					'TR',
					'GRNU',
					'GRNUTR'
				];
				if (in_array($table, $karigarTableForColumn)) {
					if ($table == "G" || $table == "GRNU") {
						$q .= " AND $table.worker_id = $cid AND $table.worker_id IS NOT NULL ";
						// 		$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";

					} else if ($table == "TR" || $table == "GRNUTR") {
						$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";
					} else {
						$table == 'R' ? $table = "G" : $table = $table;
						$q .= " AND $table.worker_id = $cid AND $table.worker_id IS NOT NULL";
					}
				} else {
					$q .= " AND $table.party_id = $cid ";
				}
			}
		}
		return $q;
	}

	function getKarigarSearchOpeningQuery($table, $dateField, $fromDate, $cid, $ac_cat, $other = [])
	{
		$q = " WHERE TRUE ";
		if (!empty($fromDate)) {
			$fromDate = $this->db->escape($fromDate);
			$q .= " AND DATE($table.$dateField) < DATE($fromDate) ";
		} else {
			$q .= " AND FALSE ";
		}


		if ($other['master_type'] == 'bank' && $table == 'PY') {
			if ($cid > 0) {
				$q .= " AND PY.bank_id = " . $this->db->escape($cid);
			}
		} else if ($other['master_type'] == 'bank' && $table != 'PY') {
			$q .= ' AND FALSE ';
		} else if ($other['master_type'] == 'account_category') {
			if ($ac_cat !== NULL && $ac_cat > 0) {
				$q .= " AND K.account_type_id = " . $this->db->escape($ac_cat) . " ";
			}
			// 	pre($cid > 0 ? "Y" : "N");;
			// 	pre($cid);;

			if ($cid > 0) {
				$cid                   = $this->db->escape($cid);
				$karigarTableForColumn = [

					"G",

					'R',

					'TR',
					'GRNU',
					'GRNUTR'
				];
				if (in_array($table, $karigarTableForColumn)) {
					if ($table == "G" || $table == "GRNU") {
						$q .= " AND $table.worker_id = $cid AND $table.worker_id IS NOT NULL ";
						// 		$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";

					} else if ($table == "TR" || $table == "GRNUTR") {
						$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";
					} else {
						$table == 'R' ? $table = "G" : $table = $table;
						$q .= " AND $table.worker_id = $cid AND $table.worker_id IS NOT NULL ";
					}
				} else {
					$q .= " AND $table.party_id = $cid ";
				}
			}
		}
		// 		pre($q);;
		return $q;
	}

	function getKarigarLedgerData($opening = false, $fromDate = NULL, $toDate = NULL, $cid = 0, $ac_cat = 0, $other = [])
	{

		if ($opening) {
			$R     = $this->getKarigarSearchOpeningQuery('R', 'created_at', $fromDate, $cid, $ac_cat, $other);
			$given = $this->getKarigarSearchOpeningQuery('G', 'created_at', $fromDate, $cid, $ac_cat, $other);
			$tr    = $this->getKarigarSearchOpeningQuery('TR', 'created_at', $fromDate, $cid, $ac_cat, $other);

			$GRNU = $this->getKarigarSearchOpeningQuery('GRNU', 'created_at', $fromDate, $cid, $ac_cat, $other);
			$GRNUTR    = $this->getKarigarSearchOpeningQuery('GRNUTR', 'created_at', $fromDate, $cid, $ac_cat, $other);
		} else {
			$R     = $this->getKarigarSearchQuery('R', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
			$given = $this->getKarigarSearchQuery('G', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
			$tr    = $this->getKarigarSearchQuery('TR', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);

			$GRNU = $this->getKarigarSearchQuery('GRNU', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
			$GRNUTR    = $this->getKarigarSearchQuery('GRNUTR', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
		}

		if (($other['run_time_loss'] ?? "false") === 'true') {
			$run_time_loss = 'true';
		} else {
			$run_time_loss = 'false';
		}
		$run_time_loss = $this->db->escape($run_time_loss);

		$q = "
		SELECT
				TR.verification,TR.id,
				TR.`creation_date` AS `date`,
				'KASAR' AS `type`,
				'given' AS `code`,
				K.name AS customer_name,
				K.id AS `party_id`,
				P.name AS process,
				'' AS remark,
                '' AS bank_name,
				'0' AS total_net_amt,
				
				CASE 
					WHEN TR.vadharo_dhatado IS NULL THEN (SUM(COALESCE(TR.vadharo_dhatado))*TR.given_touch)/100
					ELSE (SUM(COALESCE(TR.vadharo_dhatado))*TR.given_touch)/100 
					
				END AS total_fine_gold,
				CONCAT('manufacturing/process/manage/',TR.garnu_id) AS link,
				TR.vadharo_dhatado AS loss,
				'0' AS is_not_shown,
				CASE 
					WHEN TR.vadharo_dhatado IS NULL THEN TR.total_weight
					ELSE TR.vadharo_dhatado 
					
				END AS gross,
				'0' AS less,
				'0' AS net,
				'0' AS rate,
				TR.given_touch AS purity
				FROM given TR
				LEFT JOIN process P ON P.id = TR.process_id
				LEFT JOIN garnu ON garnu.id = TR.garnu_id
			
				LEFT JOIN customer K ON K.id = TR.`transfer_account` $tr AND TR.transfer_account > 0 AND TR.is_kasar='YES'
				GROUP BY TR.id
		UNION ALL
		SELECT
				GRNUTR.verification,GRNUTR.id,
				GRNUTR.`creation_date` AS `date`,
				'KASAR' AS `type`,
				'garnu' AS `code`,
				K.name AS customer_name,
				K.id AS `party_id`,
				'garnu' AS process,
				'' AS remark,
                '' AS bank_name,
				'0' AS total_net_amt,
				
				CASE 
					WHEN GRNUTR.vadharo_garnu IS NULL THEN (SUM(COALESCE(GRNUTR.garnu_weight))*GRNUTR.touch)/100
					ELSE (SUM(COALESCE(GRNUTR.vadharo_garnu))*GRNUTR.touch)/100 
					
				END AS total_fine_gold,
				CONCAT('manufacturing/garnu/edit/',GRNUTR.id) AS link,
				GRNUTR.vadharo_garnu AS loss,
				'0' AS is_not_shown,
				CASE 
					WHEN GRNUTR.vadharo_garnu IS NULL THEN GRNUTR.garnu_weight
					ELSE GRNUTR.vadharo_garnu 
					
				END AS gross,
				'0' AS less,
				'0' AS net,
				'0' AS rate,
				GRNUTR.touch AS purity
				FROM garnu GRNUTR
				LEFT JOIN customer K ON K.id =GRNUTR.transfer_account $GRNUTR AND GRNUTR.transfer_account > 0 AND GRNUTR.is_kasar='YES'
				GROUP BY GRNUTR.id
		UNION ALL
		SELECT
				G.verification,G.id,
				G.`creation_date` AS `date`,
				'IN_GIVEN_FINE' AS `type`,
				'given' AS `code`,
				K.name AS customer_name,
				K.id AS `party_id`,
				P.name AS process,
				'' AS remark,
                '' AS bank_name,
				'0' AS total_net_amt,
				
				CASE 
					WHEN G.vadharo_dhatado IS NULL THEN (SUM(COALESCE(G.total_weight))*G.given_touch)/100
					ELSE (SUM(COALESCE(G.vadharo_dhatado))*G.given_touch)/100 
					
				END AS total_fine_gold,
				CONCAT('manufacturing/process/manage/',G.garnu_id) AS link,
				G.vadharo_dhatado AS loss,
				'0' AS is_not_shown,
				CASE 
					WHEN G.vadharo_dhatado IS NULL THEN G.total_weight
					ELSE G.vadharo_dhatado 
					
				END AS gross,
				'0' AS less,
				'0' AS net,
				'0' AS rate,
				G.given_touch AS purity
				FROM given G
				LEFT JOIN process P ON P.id = G.process_id
				LEFT JOIN garnu ON garnu.id = G.garnu_id
			
				LEFT JOIN customer K ON K.id = G.`worker_id` $given AND G.worker_id > 0 AND G.is_kasar='NO'
				GROUP BY G.id
		UNION ALL
		
		SELECT
				GRNU.verification,GRNU.id,
				GRNU.`creation_date` AS `date`,
				'IN_GIVEN_FINE' AS `type`,
				'garnu' AS `code`,
				K.name AS customer_name,
				K.id AS `party_id`,
				'garnu' AS process,
				'' AS remark,
                '' AS bank_name,
				'0' AS total_net_amt,
				
				CASE 
					WHEN GRNU.vadharo_garnu IS NULL THEN (SUM(COALESCE(GRNU.garnu_weight))*GRNU.touch)/100
					ELSE (SUM(COALESCE(GRNU.vadharo_garnu))*GRNU.touch)/100 
					
				END AS total_fine_gold,
				CONCAT('manufacturing/garnu/edit/',GRNU.id) AS link,
				GRNU.vadharo_garnu AS loss,
				'0' AS is_not_shown,
				CASE 
					WHEN GRNU.vadharo_garnu IS NULL THEN GRNU.garnu_weight
					ELSE GRNU.vadharo_garnu 
					
				END AS gross,
				'0' AS less,
				'0' AS net,
				'0' AS rate,
				GRNU.touch AS purity
				FROM garnu GRNU
				LEFT JOIN customer K ON K.id = GRNU.`worker_id` $GRNU AND GRNU.worker_id > 0 AND GRNU.is_kasar='NO'
				GROUP BY GRNU.id
		UNION ALL
			SELECT
				R.verification,R.id,
				R.created_at AS `date`,
				'LABOUR' AS `type`,
				R.code,
				K.name AS customer_name,
				K.id AS party_id,
				P.name AS process,
				'' AS remark,
                '' AS bank_name,
				R.final_labour AS total_net_amt,
				'0' total_fine_gold,

				CONCAT('manufacturing/process/manage/',R.garnu_id) AS link,
				
				G.vadharo_dhatado AS loss,
				'0' AS is_not_shown,
				R.total_weight AS gross,
				'0' AS less,
				'0' AS net,
				R.labour AS rate,
				R.touch AS purity
				FROM `receive` R
				LEFT JOIN given G ON G.id = R.given_id
				LEFT JOIN customer K ON G.worker_id = K.id
				LEFT JOIN process P ON G.process_id = P.id $R AND G.worker_id > 0 AND G.is_completed='YES' ";

		return $q;
	}

	function getLedgerReprot($fromDate, $toDate, $cid = 0, $ac_cat = 0, $nother = [])
	{
		// pre($nother);
		// die;
		$openingData = $this->getLedgerQuery(true, $fromDate, $toDate, $cid, $ac_cat, $nother)->result_array();


		$data        = $this->getLedgerQuery(false, $fromDate, $toDate, $cid, $ac_cat, $nother)->result_array();



		return [
			'data'         => $data,
			'opening_data' => $openingData,
			'other'        => $nother
		];
	}

	function getCustomerAndKarigarLedgerTotals($customer_id = 0)
	{
		$fromDate    = date('1970-m-01');
		$toDate      = date('Y-m-d');
		$ac_cat      = 0;
		$fromDate    = $this->security->xss_clean($fromDate);
		$toDate      = $this->security->xss_clean($toDate);
		$customer_id = $this->security->xss_clean($customer_id);
		$ac_cat      = $this->security->xss_clean($ac_cat);
		$data        = [];
		$finalData   = [];
		$AC          = $this->db->get('account_type')->result_array();
		foreach ($AC as $k => $v) {
			$ac_cat                 = $v['id'];
			$other['master_type']   = 'account_category';
			$other['run_time_loss'] = 'false';
			$data['data']           = $this->getLedgerReprot($fromDate, $toDate, $customer_id, $ac_cat, $other);
			if (!empty($data['data'])) {
				foreach ($data['data']['data'] as $i => $d) {
					$d['account_category']       = $v['name'];
					$finalData['data']['data'][] = $d;
				}
			}
		}

		$finalData['data']['opening_data'] = [];
		$page_data['data']                 = $finalData;

		extract($page_data);
		extract($data);


		$totalOpeningFine = 0;

		$customers = [];
		foreach ($data['data'] as $di => $dv) {
			if (!in_array($dv['party_id'], $customers)) {
				$customers[] = $dv['party_id'];
			}
		}


		// pre($data);exit;
		$data['filtered_data'] = [];
		$totalDebitFine        = 0;
		$totalCreditFine       = 0;
		$totalClosingFine      = 0;
		$totalDebitAmt         = 0;
		$totalCreditAmt        = 0;
		$totalClosingAmt       = 0;
		$totalOpeningFine      = 0;
		$totalOpeningAmt       = 0;

		// pre($data);
		$totalLoss = 0;

		foreach ($customers as $abc => $c) {
			$closingFine      = 0;
			$closingAmt       = 0;
			$openingFine      = 0;
			$openingAmt       = 0;
			$date             = '';
			$customerName     = '';
			$customerId       = 0;
			$totalDebitFine2  = 0;
			$totalDebitAmt2   = 0;
			$totalCreditFine2 = 0;
			$totalCreditAmt2  = 0;

			$loss = 0;

			$isBank = false;
			$bank['bank_name'] = [];


			foreach ($data['data'] as $di => $v) {

				$closingFine = number_format($closingFine, 3, '.', '');
				$closingAmt  = number_format($closingAmt, 3, '.', '');

				$totalDebitFine2  = number_format($totalDebitFine2, 3, '.', '');
				$totalDebitAmt2   = number_format($totalDebitAmt2, 3, '.', '');
				$totalCreditFine2 = number_format($totalCreditFine2, 3, '.', '');
				$totalCreditAmt2  = number_format($totalCreditAmt2, 3, '.', '');
				// $loss = number_format($loss, 3, '.', '');
				// if ($v['code'] == 'link(bank)') {
				// 	$isBank = true;
				// }
				if ($v['party_id'] == $c) {
					$loss += $v['loss'];
					$customerId    = $c;
					$date          = $v['date'];
					$customerName  = $v['customer_name'];
					$typeDebitArr  = [
						'PUR',
						'SAL_RETURN',
						'FINE_CR',
						'BANK_CR',
						'ROOPU_CR',
						'TE_CR',
						'receive',
						'LABOUR'

					];
					$typeCreditArr = [
						'SAL',
						'PUR_RETURN',
						'FINE_DB',
						'BANK_DB',
						'ROOPU_DB',
						'TE_DB',


					];
					$typeFineArr   = [
						'IN_GIVEN_FINE',
						'KASAR'
					];
					if ($v['type'] == 'PAY_PAY' && !$isBank) {
						// $closingAmt -= abs($v['total_net_amt']);
						if ($v['total_fine_gold'] <= 0) {
							$closingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalDebitAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalClosingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						}
						$closingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalDebitFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalDebitAmt2 += abs($v['total_net_amt']);
						$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalClosingAmt -= abs($v['total_net_amt']);
					} else if ($v['type'] == 'PAY_REC' && !$isBank) {
						// $closingAmt += abs($v['total_net_amt']);
						if ($v['total_fine_gold'] <= 0) {
							$closingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalCreditAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalClosingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						}
						$closingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalCreditAmt2 += abs($v['total_net_amt']);
						$totalClosingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalClosingAmt += abs($v['total_net_amt']);
					} else if (in_array($v['type'], $typeCreditArr)) {
						$closingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$closingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalDebitFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalDebitAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalClosingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
					} else if (in_array($v['type'], $typeDebitArr)) {
						$closingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$closingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalCreditAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalClosingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalClosingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
					} else if (in_array($v['type'], $typeFineArr)) {
						if ($v['total_fine_gold'] < 0) {
							$closingAmt += 0;
							$closingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalCreditAmt2 += 0;
							$totalClosingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalClosingAmt += 0;
						} else {
							$closingAmt -= 0;
							$closingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalDebitFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalDebitAmt2 += 0;
							$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalClosingAmt -= 0;
						}
					} else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') {
						$closingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$closingFine      = number_format($closingFine, 3, '.', '');
						$closingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalDebitAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalClosingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalClosingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if ($v['type'] == 'RATEFINE_CR' || $v['type'] == 'RATERS_CR') {
						$closingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$closingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalDebitAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalClosingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;

						// $openingAmt += abs($v['total_net_amt']);
						// $openingFine -= abs($v['total_fine_gold']);
						// $totalOpeningAmt += abs($v['total_net_amt']);
						// $totalOpeningFine -= abs($v['total_fine_gold']);
					}
					// else if (in_array($v['type'], $typeLossArr)) {
					// 	// $loss = ($v['total_fine_gold'] != NULL || $v['total_fine_gold'] != "") ? $v['total_fine_gold'] : $v['total_net_amt'];
					// }
				}
			}

			foreach ($data['opening_data'] as $odi => $v) {
				$openingAmt       = number_format($openingAmt, 3, '.', '');
				$openingFine      = number_format($openingFine, 3, '.', '');
				$totalOpeningAmt  = number_format($totalOpeningAmt, 3, '.', '');
				$totalOpeningFine = number_format($totalOpeningFine, 3, '.', '');
				if ($c == $v['party_id']) {
					if ($v['code'] == 'link(bank)') {
						$isBankO = true;
					} else {
						$isBankO = false;
					}
					if ($v['type'] == 'PAY_PAY' && !$isBankO) {
						// $openingAmt -= abs($v['total_net_amt']);
						if ($v['total_fine_gold'] <= 0) {
							$openingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalOpeningAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						}
						$openingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalOpeningAmt -= abs($v['total_net_amt']);
						$totalOpeningFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if ($v['type'] == 'PAY_REC' && !$isBankO) {
						// $openingAmt += abs($v['total_net_amt']);
						if ($v['total_fine_gold'] <= 0) {
							$openingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalOpeningAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						}
						$openingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						// $totalOpeningAmt += abs($v['total_net_amt']);
						$totalOpeningFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if (in_array($v['type'], $typeCreditArr)) {
						$openingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$openingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalOpeningAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalOpeningFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if (in_array($v['type'], $typeDebitArr)) {
						$openingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$openingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalOpeningAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalOpeningFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if (in_array($v['type'], $typeFineArr)) {
						if ($v['total_fine_gold'] < 0) {
							$openingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$openingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalOpeningAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalOpeningFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						} else {
							$openingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$openingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
							$totalOpeningAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
							$totalOpeningFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						}
					} else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') {
						$openingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$openingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalOpeningAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalOpeningFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					} else if ($v['type'] == 'RATEFINE_CR' || $v['type'] == 'RATERS_CR') {
						$openingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$openingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
						$totalOpeningAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']) : 0;
						$totalOpeningFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
					}
				}
			}

			$totalDebitAmt += $totalDebitAmt2;
			$totalDebitFine += $totalDebitFine2;
			$totalCreditAmt += $totalCreditAmt2;
			$totalCreditFine += $totalCreditFine2;

			if ($customerId > 0) {
				// 		$cust = $dbh->getWhereRowArray('customer', [
				// 			'id' => $customerId
				// 		]);
				$this->db->where('id', $customerId);
				$cust = $this->db->get('customer')->row_array();
				if ($cust['opening_amount_type'] == 'JAMA') {
					$openingAmt -= $cust['opening_amount'];
					$totalOpeningAmt -= $cust['opening_amount'];
				} else {
					$openingAmt += $cust['opening_amount'];
					$totalOpeningAmt += $cust['opening_amount'];
				}

				if ($cust['opening_fine_type'] == 'JAMA') {
					$openingFine -= $cust['opening_fine'];
					$totalOpeningFine -= $cust['opening_fine'];
				} else {
					$openingFine += $cust['opening_fine'];
					$totalOpeningFine += $cust['opening_fine'];
				}
			}

			$bank_name = "";
			// 	if($data['other']['master_type'] == "bank" && !empty($bank['bank_name'][$abc])){
			// 	    $bank_name = $bank['bank_name'][$abc];
			// 	}
			$totalLoss += $loss;
			$data['filtered_data'][] = [
				'date'              => $dv['date'],
				'type'              => '',
				'customer_name'     => $customerName,
				'party_id'       => $customerId,
				'opening_fine'      => $openingFine,
				'opening_amt'       => $openingAmt,
				'total_debit_fine'  => $totalDebitFine2,
				'total_debit_amt'   => $totalDebitAmt2,
				'total_credit_fine' => $totalCreditFine2,
				'total_credit_amt'  => $totalCreditAmt2,
				'closing_fine'      => $closingFine,
				'closing_amt'       => $closingAmt,
				'loss'              => $loss,
				'isBank'            => $isBank,
				'bank_name'         => $bank_name
			];
		}

		// pre($data['filtered_data']);
		$finalData = [];
		foreach ($data['filtered_data'] as $fdi => $fdv) {
			$CF = $fdv['closing_fine'] + $fdv['opening_fine'];
			$CA = $fdv['closing_amt'] + $fdv['opening_amt'];

			$finalData[$fdv['party_id']] = [
				'fine'   => $CF,
				'amount' => $CA
			];
		}

		return $finalData;
	}
}
