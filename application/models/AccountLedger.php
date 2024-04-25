<?php

defined('BASEPATH') or exit('Direct Script not allowed!');

class AccountLedger extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    } 
    
    function getSearchQuery($table, $dateField, $fromDate, $toDate, $cid = 0, $ac_cat = 0, $sqother = []) {
        
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
                if($table == "F" || $table == "B" || $table == "RCR" || $table == "RCF" || $table == "ROOPU" || $table == "TE"){
                    $q .= " AND $table.customer_id = $cid ";
                }else{
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
                if($table == "F" || $table == "B" || $table == "RCR" || $table == "RCF" || $table == "ROOPU" || $table == "TE"){
                    $q .= " AND $table.customer_id = $cid ";
                }else{
                    $q .= " AND $table.party_id = $cid ";
                }
                
            }
        }
        return $q;
    }

    function getLedgerQuery($opening = false, $fromDate = null, $toDate = null, $cid = 0, $ac_cat = 0, $lother = [])
    {
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
           
            $FPaymentWhere                      = $this->getSearchQuery('F', 'created_at', $fromDate,$toDate, $cid, $ac_cat, $lother);
            $BPaymentWhere                      = $this->getSearchQuery('B', 'created_at', $fromDate,$toDate, $cid, $ac_cat, $lother);
            $RCFPaymentWhere                      = $this->getSearchQuery('RCF', 'created_at', $fromDate,$toDate , $cid, $ac_cat, $lother);
            $RCRPaymentWhere                      = $this->getSearchQuery('RCR', 'created_at', $fromDate,$toDate ,$cid, $ac_cat, $lother);
            $ROOPUPaymentWhere                      = $this->getSearchQuery('ROOPU', 'created_at', $fromDate,$toDate ,$cid, $ac_cat, $lother);
        }
        
        
        
        // pre($karigarTransferEntryTransferWhere);

        if ($ac_cat !== NULL && $ac_cat > 0) {
            $transferEntryTransferWhere .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
            // $karigarTransferEntryTransferWhere .= " AND C.account_type_id = " . $this->db->escape($ac_cat) . " ";
        }
        if ($cid > 0) {
            $c_id                              = $this->db->escape($cid);
            $transferEntryTransferWhere .= " AND TE.transfer_customer_id = $c_id ";
            // $karigarTransferEntryTransferWhere .= " AND KTE.transfer_customer_id = $c_id ";
        }

        $q = "SELECT PII.verification,PII.id,PII.date,'PUR' AS `type`,'PII' AS `code`,C.`name` as customer_name,PII.`party_id`,
                    '' as process,
                    '' as remark,
                    '' as bank_name,
					SUM(Pd.`sub_total`) AS total_net_amt,
					SUM(Pd.`fine`) AS total_fine_gold,
					CONCAT('purchase/edit/',PII.id) AS link,
					'0' AS loss
				FROM purchase PII 
				LEFT JOIN purchase_detail Pd ON PII.id = Pd.purchase_id
				LEFT JOIN customer C ON C.id = PII.party_id
				$purchaseWhere
				GROUP BY PII.id
            UNION ALL
				SELECT PRI.verification,PRI.id,PRI.date,'PUR_RETURN' AS `type`,'PRI' AS `code`,C.`name` as customer_name,PRI.`party_id`,
                    '' as process,
                    '' as remark,
                    '' as bank_name,
					SUM(PRd.sub_total) AS total_net_amt,
					SUM(PRd.fine) AS total_fine_gold,
					CONCAT('purchase_return/edit/',PRI.id) AS link,
					'0' AS loss
				FROM `purchase_return` PRI
				LEFT JOIN purchase_return_detail PRd ON PRI.id = PRd.purchase_id
				LEFT JOIN customer C ON C.id = PRI.party_id
				$purchaseReturnWhere
				GROUP BY PRI.id
            UNION ALL
				SELECT SI.verification,SI.id,SI.date,'SAL' AS `type`,'SI' AS `code`,C.`name` as customer_name,SI.`party_id`,
                    '' as process,
                    '' as remark,
                    '' as bank_name,
					SUM(SD.`sub_total`) AS total_net_amt,
					SUM(SD.`fine`) AS total_fine_gold,
					CONCAT('sales/edit/',SI.id) AS link,
					'0' AS loss
				FROM `sale` SI
				LEFT JOIN sale_detail SD ON SI.id = SD.sale_id
				LEFT JOIN customer C ON C.id = SI.party_id
				$salesWhere
				GROUP BY SI.id
            UNION ALL
				SELECT SRI.verification,SRI.ID,SRI.date,'SAL_RETURN' AS `type`,'SRI' AS `code`,C.`name` as customer_name,SRI.`party_id`,
                    '' as process,
                    '' as remark,
                    '' as bank_name,
					SUM(SRD.`sub_total`) AS total_net_amt,
					SUM(SRD.`fine`) AS total_fine_gold,
					CONCAT('sales_return/edit/',SRI.id) AS link,
					'0' AS loss
				FROM `sale_return` SRI
				LEFT JOIN sale_return_detail SRD ON SRI.id = SRD.sale_id
				LEFT JOIN customer C ON C.id = SRI.party_id
				$salesReturnWhere
                GROUP BY SRI.id

            UNION ALL
				SELECT F.verification as verification,F.id,F.date,
                CASE WHEN F.payment_type = 'CREDIT' THEN 'FINE_CR'
						WHEN F.payment_type = 'DEBIT' THEN 'FINE_DB'
					END AS `type`,
                F.jama_code AS `code`,C.`name` as customer_name,F.`customer_id` as party_id,
					'' as process,
                    F.remark,
                    bank.name as bank_name,
					F.`amount` AS total_net_amt,
					F.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',F.jama_code,'/',F.customer_id) AS link,
					'0' AS loss
				FROM `jama` F
				LEFT JOIN customer C ON C.id = F.customer_id
                LEFT JOIN bank ON bank.id = F.bank_id
				$FPaymentWhere AND F.`type`='fine'
				GROUP BY F.id

                UNION ALL
				SELECT B.verification as verification,B.id,B.date,
                CASE WHEN B.payment_type = 'CREDIT' THEN 'BANK_CR'
						WHEN B.payment_type = 'DEBIT' THEN 'BANK_DB'
					END AS `type`,
                B.jama_code AS `code`,C.`name` as customer_name,B.`customer_id` as party_id,
					'' as process,
                    B.remark,
                    bank.name as bank_name,
					B.`amount` AS total_net_amt,
					B.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',B.jama_code,'/',B.customer_id) AS link,
					'0' AS loss
				FROM `jama` B
				LEFT JOIN customer C ON C.id = B.customer_id
                LEFT JOIN bank ON bank.id = B.bank_id
				$BPaymentWhere AND B.`type`='bank'
				GROUP BY B.id

                UNION ALL
				SELECT RCF.verification as verification,RCF.id,RCF.date,
                CASE WHEN RCF.payment_type = 'CREDIT' THEN 'RATEFINE_CR'
						WHEN RCF.payment_type = 'DEBIT' THEN 'RATEFINE_DB'
					END AS `type`,
                RCF.jama_code AS `code`,C.`name` as customer_name,RCF.`customer_id` as party_id,
					'' as process,
                    RCF.remark,
                    bank.name as bank_name,
					RCF.`amount` AS total_net_amt,
					RCF.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',RCF.jama_code,'/',RCF.customer_id) AS link,
					'0' AS loss
				FROM `jama` RCF
				LEFT JOIN customer C ON C.id = RCF.customer_id
                LEFT JOIN bank ON bank.id = RCF.bank_id
				$RCFPaymentWhere AND RCF.`type`='ratecutfine'
				GROUP BY RCF.id

                UNION ALL
				SELECT RCR.verification as verification,RCR.id,RCR.date,
                CASE WHEN RCR.payment_type = 'CREDIT' THEN 'RATERS_CR'
						WHEN RCR.payment_type = 'DEBIT' THEN 'RATERS_DB'
					END AS `type`,
                RCR.jama_code AS `code`,C.`name` as customer_name,RCR.`customer_id` as party_id,
					'' as process,
                    RCR.remark,
                    bank.name as bank_name,
					RCR.`amount` AS total_net_amt,
					RCR.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',RCR.jama_code,'/',RCR.customer_id) AS link,
					'0' AS loss
				FROM `jama` RCR
				LEFT JOIN customer C ON C.id = RCR.customer_id
                LEFT JOIN bank ON bank.id = RCR.bank_id
				$RCRPaymentWhere AND RCR.`type`='ratecutrs'
				GROUP BY RCR.id  

                UNION ALL
				SELECT ROOPU.verification as verification,ROOPU.id,ROOPU.date,
                CASE WHEN ROOPU.payment_type = 'CREDIT' THEN 'ROOPU_CR'
						WHEN ROOPU.payment_type = 'DEBIT' THEN 'ROOPU_DB'
					END AS `type`,
                ROOPU.jama_code AS `code`,C.`name` as customer_name,ROOPU.`customer_id` as party_id,
					'' as process,
                    ROOPU.remark,
                    bank.name as bank_name,
					ROOPU.`amount` AS total_net_amt,
					ROOPU.`fine` AS total_fine_gold,
					CONCAT('payment/jama/edit/',ROOPU.jama_code,'/',ROOPU.customer_id) AS link,
					'0' AS loss
				FROM `jama` ROOPU
				LEFT JOIN customer C ON C.id = ROOPU.customer_id
                LEFT JOIN bank ON bank.id = ROOPU.bank_id
				$ROOPUPaymentWhere AND ROOPU.`type`='roopu'
				GROUP BY ROOPU.id

                UNION ALL
				SELECT TE.verification as verification,TE.id,TE.date,
					CASE WHEN TE.payment_type = 'credit' THEN 'TE_CR'
						WHEN TE.payment_type = 'debit' THEN 'TE_DB'
					END AS `type`,
					CASE WHEN TE.narration IS NOT NULL THEN CONCAT('link','#',TE.narration)
						ELSE 'link' END AS `code`,C.name AS customer_name,TE.transfer_customer_id AS party_id,
                    '' as process,
                    '' as remark,
                    '' as bank_name,
                    TE.total_amount AS total_net_amt,
					TE.gold AS total_fine_gold,
					CONCAT('payment/transfer_entry/edit/',TE.id) AS link,
					'0' AS loss
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

        if (!empty ($cid) && $cid > 0) {
            $this->db->select('AC.name,AC.id');
            $this->db->from('customer C');
            $this->db->join('account_type AC', 'AC.id = C.account_type_id', 'left');
            $this->db->where('C.id', $cid);
            if (!empty ($ac_cat) && $ac_cat > 0) {
                $this->db->where('AC.id', $ac_cat);
            }
            $AC = $this->db->get()->row_array();
            $this->session->set_userdata('account_type', $AC['name']);
            if ($AC['name'] == 'karigar') {
                // $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $AC['id'], $ig_id, $other);
                $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $lother);
                $q .= " UNION ALL $additionalKarigarLedgerQuery ";
            }
        } else if (!empty ($ac_cat) && $ac_cat > 0) {
            $AC = $this->db->get_where('account_type', [ 'id' => $ac_cat ])->row_array();
            $this->session->set_userdata('account_type', $AC['name']);
            if ($AC['name'] == 'karigar') {
                // $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $ig_id, $other);
                $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat,$lother);
                $q .= " UNION ALL $additionalKarigarLedgerQuery ";
            }
        } else if ($ac_cat === -111) {
            $additionalKarigarLedgerQuery = $this->getKarigarLedgerData($opening, $fromDate, $toDate, $cid, $ac_cat, $ig_id, $lother);
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
                    'TR'
                ];
                if (in_array($table, $karigarTableForColumn)) {
                    if ($table == "G") {
                        $q .= " AND G.worker_id = $cid AND G.worker_id IS NOT NULL ";
                        // 		$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";

                    } else if ($table == "TR") {
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

        // if ($ac_cat !== NULL && $ac_cat > 0) {
        //     $q .= " AND K.account_type_id = " . $this->db->escape($ac_cat) . " ";
        // }
        // pre($karigarField);
        // if ($cid > 0) {
        //     $cid                   = $this->db->escape($cid);
        //     $karigarTableForColumn = [
                
        //         'R',
        //         'G',
        //         "TR"
        //     ];
        //     if (in_array($table, $karigarTableForColumn)) {
        //             if ($table == "G") {
        //                 $q .= " AND G.worker_id = $cid AND G.worker_id IS NOT NULL ";
        //                 // 		$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";

        //             } else if ($table == "TR") {
        //                 $q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";
        //             } else {
        //                 $table == 'R' ? $table = "G" : $table = $table;
        //                 $q .= " AND $table.worker_id = $cid AND $table.worker_id IS NOT NULL ";
        //             }
        //         } else {
        //             $q .= " AND $table.party_id = $cid ";
        //         }
        // }
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
                    
                    'TR'
                ];
               if (in_array($table, $karigarTableForColumn)) {
                    if ($table == "G") {
                        $q .= " AND G.worker_id = $cid AND G.worker_id IS NOT NULL ";
                        // 		$q .= " AND $table.transfer_account = $cid AND $table.transfer_account IS NOT NULL ";

                    } else if ($table == "TR") {
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
        } else {
            $R     = $this->getKarigarSearchQuery('R', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
            $given = $this->getKarigarSearchQuery('G', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
            $tr    = $this->getKarigarSearchQuery('TR', 'created_at', $fromDate, $toDate, $cid, $ac_cat, $other);
        }

        if (($other['run_time_loss'] ?? "false" ) === 'true') {
            $run_time_loss = 'true';
        } else {
            $run_time_loss = 'false';
        }
        $run_time_loss = $this->db->escape($run_time_loss);

        $q = "SELECT
				G.verification,G.id,
				G.`creation_date` AS `date`,
				'IN_GIVEN_FINE' AS `type`,
				'given' AS `code`,
				K.name AS customer_name,
				K.id AS `party_id`,
				P.name AS process,
				'' as remark,
                '' as bank_name,
				'0' AS total_net_amt,
				
				CASE 
					WHEN G.vadharo_dhatado IS NULL THEN (SUM(COALESCE(G.total_weight))*garnu.touch)/100
					ELSE (SUM(COALESCE(G.vadharo_dhatado))*garnu.touch)/100 
					
				END AS total_fine_gold,
				CONCAT('manufacturing/process/manage/',G.garnu_id) AS link,
				G.vadharo_dhatado AS loss
				FROM given G
				LEFT JOIN process P ON P.id = G.process_id
				LEFT JOIN garnu ON garnu.id = G.garnu_id
				LEFT JOIN given_row_material GRM ON GRM.given_id = G.id
				LEFT JOIN customer K ON K.id = G.`worker_id` $given AND G.worker_id > 0 AND is_kasar='NO'
				GROUP BY G.id
		UNION ALL
			SELECT
				R.verification,R.id,
				R.created_at AS `date`,
				'receive' AS `type`,
				R.code,
				K.name AS customer_name,
				K.id AS party_id,
				P.name AS process,
				'' as remark,
                '' as bank_name,
				R.final_labour AS total_net_amt,
				'0' AS total_fine_gold,

				CONCAT('manufacturing/process/manage/',R.garnu_id) AS link,
				CASE WHEN G.vadharo_dhatado IS NULL THEN SUM(COALESCE(G.given_weight)) ELSE G.vadharo_dhatado
				END AS loss
				FROM `receive` R
				LEFT JOIN given G ON G.id = R.given_id
				LEFT JOIN customer K ON G.worker_id = K.id
				LEFT JOIN process P ON G.process_id = P.id $R";
				
        return $q;
    }

    function getLedgerReprot($fromDate, $toDate, $cid = 0, $ac_cat = 0, $nother = [])
    {
        // pre($nother);
        // die;
        $data        = $this->getLedgerQuery(false, $fromDate, $toDate, $cid, $ac_cat, $nother)->result_array();
        $openingData = $this->getLedgerQuery(true, $fromDate, $toDate, $cid, $ac_cat, $nother)->result_array();
        // pre($openingData);
        return [
            'data'         => $data,
            'opening_data' => $openingData,
            'other'        => $nother
        ];
    }

    function getCustomerAndKarigarLedgerTotals($customer_id = 0)
    {
        $fromDate    = '';
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
        $totalOpeningFine   = 0;
        $customers          = [];
        $account_categories = [];
        if (!empty($data['data'])) {
            foreach ($data['data'] as $di => $dv) {
                if (!in_array($dv['party_id'], $customers)) {
                    $customers[]          = $dv['party_id'];
                    $account_categories[] = $dv['account_category'];
                }
            }
        }
        $data['filtered_data'] = [];
        $totalDebitFine        = 0;
        $totalCreditFine       = 0;
        $totalClosingFine      = 0;
        $totalDebitAmt         = 0;
        $totalCreditAmt        = 0;
        $totalClosingAmt       = 0;
        $totalOpeningFine      = 0;
        $totalOpeningAmt       = 0;
        $totalLoss             = 0;

        foreach ($customers as $ci => $c) {
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

            $loss             = 0;
            $isBank           = false;
            $account_category = '';

            $typeDebitArr  = [
                'PUR',
                'SAL_RETURN',
                'FINE_CR',
                'BANK_CR',
                'RATEFINE_CR',
                'RATERS_CR',
                'ROOPU_CR',
                'TE_CR',
                
                
            ];
            $typeCreditArr = [
                'SAL',
                'PUR_RETURN',
                'FINE_DB',
                'BANK_DB',
                'RATEFINE_DB',
                'RATERS_DB',
                'ROOPU_DB',
                'TE_DB',
                'receive'
            ];
            $typeFineArr   = [
                'IN_GIVEN_FINE'
                
            ];
            foreach ($data['data'] as $di => $v) {
                $account_category = $v['account_category'];
                if ($v['code'] == 'link(bank)') {
                    $isBank = true;
                }
                if ($v['party_id'] == $c) {
                    $loss += $v['loss'];
                    $customerId   = $c;
                    $date         = $v['date'];
                    $customerName = $v['customer_name'];

                    if ($v['type'] == 'PAY_PAY' && !$isBank) {
                        if ($v['total_fine_gold'] <= 0) {
                            $closingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                            $totalDebitAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                            $totalClosingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        }
                        $closingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalDebitFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                    } else if ($v['type'] == 'PAY_REC' && !$isBank) {
                        if ($v['total_fine_gold'] <= 0) {
                            $closingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                            $totalCreditAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                            $totalClosingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        }
                        $closingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalCreditFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                    } else if (in_array($v['type'], $typeCreditArr)) {
                        
                        $closingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $closingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        
                        $totalDebitFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalDebitAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        
                        $totalClosingFine -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                    
                    } else if (in_array($v['type'], $typeDebitArr)) {
                        $closingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $closingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalCreditFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalCreditAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $totalClosingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                    } else if (in_array($v['type'], $typeFineArr)) {
                        if ($v['total_fine_gold'] < 0) {
                            $closingAmt += 0;
                            $closingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                            $totalCreditFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                            $totalCreditAmt2 += 0;
                            $totalClosingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                            $totalClosingAmt += 0;
                        } else {
                            $closingAmt -= 0;
                            $closingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                            $totalDebitFine2 += abs($v['total_fine_gold']);
                            $totalDebitAmt2 += 0;
                            $totalClosingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                            $totalClosingAmt -= 0;
                        }
                    } else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') {
                        $closingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $closingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalDebitAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $totalCreditFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingAmt -= isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $totalClosingFine += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                    } else if ($v['type'] == 'RATEFINE_CR' || $v['type'] == 'RATERS_CR') {
                        $closingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $closingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalDebitAmt2 += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $totalCreditFine2 += isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                        $totalClosingAmt += isset($v['total_net_amt']) && !empty($v['total_net_amt']) ? abs($v['total_net_amt']) : 0;
                        $totalClosingFine -= isset($v['total_fine_gold']) && !empty($v['total_fine_gold']) ? abs($v['total_fine_gold']) : 0;
                    }
                }
            }

            foreach ($data['opening_data'] as $odi => $v) {
                if ($c == $v['party_id']) {
                    if ($v['code'] == 'link(bank)') {
                        $isBankO = true;
                    } else {
                        $isBankO = false;
                    }
                    if ($v['type'] == 'PAY_PAY' && !$isBankO) {
                        // $openingAmt -= abs($v['total_net_amt']);
                        if ($v['total_fine_gold'] <= 0) {
                            $openingAmt -= abs($v['total_net_amt']);
                            $totalOpeningAmt -= abs($v['total_net_amt']);
                        }
                        $openingFine -= abs($v['total_fine_gold']);
                        // $totalOpeningAmt -= abs($v['total_net_amt']);
                        $totalOpeningFine -= abs($v['total_fine_gold']);
                    } else if ($v['type'] == 'PAY_REC' && !$isBankO) {
                        // $openingAmt += abs($v['total_net_amt']);
                        if ($v['total_fine_gold'] <= 0) {
                            $openingAmt += abs($v['total_net_amt']);
                            $totalOpeningAmt += abs($v['total_net_amt']);
                        }
                        $openingFine += abs($v['total_fine_gold']);
                        // $totalOpeningAmt += abs($v['total_net_amt']);
                        $totalOpeningFine += abs($v['total_fine_gold']);
                    } else  if (in_array($v['type'], $typeCreditArr)) {
                        $openingAmt -= abs($v['total_net_amt']);
                        $openingFine -= abs($v['total_fine_gold']);
                        $totalOpeningAmt -= abs($v['total_net_amt']);
                        $totalOpeningFine -= abs($v['total_fine_gold']);
                    } else if (in_array($v['type'], $typeDebitArr)) {
                        $openingAmt += abs($v['total_net_amt']);
                        $openingFine += abs($v['total_fine_gold']);
                        $totalOpeningAmt += abs($v['total_net_amt']);
                        $totalOpeningFine += abs($v['total_fine_gold']);
                    } else if (in_array($v['type'], $typeFineArr)) {
                        if ($v['total_fine_gold'] < 0) {
                            $openingAmt += abs($v['total_net_amt']);
                            $openingFine += abs($v['total_fine_gold']);
                            $totalOpeningAmt += abs($v['total_net_amt']);
                            $totalOpeningFine += abs($v['total_fine_gold']);
                        } else {
                            $openingAmt -= abs($v['total_net_amt']);
                            $openingFine -= abs($v['total_fine_gold']);
                            $totalOpeningAmt -= abs($v['total_net_amt']);
                            $totalOpeningFine -= abs($v['total_fine_gold']);
                        }
                    }  else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') {
                        $openingAmt -= abs($v['total_net_amt']);
                        $openingFine += abs($v['total_fine_gold']);
                        $totalOpeningAmt -= abs($v['total_net_amt']);
                        $totalOpeningFine += abs($v['total_fine_gold']);
                    } else if ($v['type'] == 'RATEFINE_CR' || $v['type'] == 'RATERS_CR') {
                        $openingAmt += abs($v['total_net_amt']);
                        $openingFine -= abs($v['total_fine_gold']);
                        $totalOpeningAmt += abs($v['total_net_amt']);
                        $totalOpeningFine -= abs($v['total_fine_gold']);
                    }
                }
            }

            $totalDebitAmt += $totalDebitAmt2;
            $totalDebitFine += $totalDebitFine2;
            $totalCreditAmt += $totalCreditAmt2;
            $totalCreditFine += $totalCreditFine2;

            // $cust = $dbh->getWhereRowArray('customer', [
            // 	'id' => $customerId
            // ]);
            $this->db->where('id', $customerId);
            $cust = $this->db->get('customer')->row_array();

            if (!empty($cust)) {
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

            $totalLoss += $loss;
            $data['filtered_data'][] = [
                'date'              => $dv['date'],
                'type'              => '',
                'customer_name'     => $customerName,
                'party_id'          => $customerId,
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
                'account_category'  => $account_categories[$ci]
            ];
        }
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
