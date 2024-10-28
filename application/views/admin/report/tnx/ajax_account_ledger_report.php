<?php

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
	    if(!in_array($v['bank_name'],$bank['bank_name']) && $data['other']['master_type'] == "bank"){
    	    $bank['bank_name'][] = $v['bank_name'];
    	}
//     	if($v['loss'] > 0){
// 		    $v['total_fine_gold'] += abs($v['loss']);
// 		}else if($v['loss'] < 0){
// 		    $v['total_fine_gold'] += abs($v['loss']);
// 		}
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

	if ($customer_check_master_type != "bank" && $customerId > 0) {
		$cust = $dbh->getWhereRowArray('customer', [
			'id' => $customerId
		]);
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
	if($data['other']['master_type'] == "bank" && !empty($bank['bank_name'][$abc])){
	    $bank_name = $bank['bank_name'][$abc];
	}
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
?>

<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th></th>
			<!--<th></th>-->
			<th colspan="2" class="text-center">Opening</th>
			<th colspan="2" class="text-center">Debit</th>
			<th colspan="2" class="text-center">Credit</th>
			<th colspan="2" class="text-center">Closing</th>
		</tr>
		<tr>
			<th>Customer</th>
			<!--<th>Loss</th>-->
			<th>Fine</th>
			<th>Amt.</th>
			<th>Fine</th>
			<th>Amt.</th>
			<th>Fine</th>
			<th>Amt.</th>
			<th>Fine</th>
			<th>Amt.</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// pre($data['filtered_data']);exit;
		foreach ($data['filtered_data'] as $fdi => $fdv) {
			if ($fdv['isBank']) {
				$bankname             = $this->db->get_where('bank', array('id' => $fdv['party_id']))->row_array()['name'];
				$fdv['customer_name'] = $bankname;
				$url                  = site_url('report/account_ledger/ledgerReportByCustomer/' . $fdv['party_id'] . '/bank');
			} else if (!empty($fdv['party_id']) && isset($data['other']['master_type']) && $data['other']['master_type'] == 'bank') {
				// $bankname             = $this->db->get_where('bank', array('id' => $fdv['party_id']))->row_array()['name'];
				$fdv['customer_name'] = $fdv['bank_name'];
				$url                  = site_url('report/account_ledger/ledgerReportByCustomer/' . $fdv['party_id'] . '/bank');
			} else {
				$fdv['customer_name'] = $fdv['customer_name'];
				$url = site_url('report/account_ledger/ledgerReportByCustomer/' . $fdv['party_id']);
			}
		?>
			<tr>
				<td><a href="<?= $url ?>" target="_blank">
						<?= isset($fdv['customer_name']) && !empty($fdv['customer_name']) ? $fdv['customer_name'] : ""; ?>
					</a></td>
				<!--<td>-->
				<!--	<?= $fdv['loss'] ?>-->
				<!--</td>-->
				<td>
					<?= $fdv['opening_fine'] ?>
				</td>
				<td>
					<?= $fdv['opening_amt'] ?>
				</td>
				<td>
					<?= abs($fdv['total_debit_fine']) ?>
				</td>
				<td>
					<?= abs($fdv['total_debit_amt']) ?>
				</td>
				<td>
					<?= abs($fdv['total_credit_fine']) ?>
				</td>
				<td>
					<?= abs($fdv['total_credit_amt']) ?>
				</td>
				<td>
					<?= number_format($fdv['closing_fine'], 2, '.', '') + number_format($fdv['opening_fine'], 2, '.', '') ?>
				</td>
				<td>
					<?= number_format($fdv['closing_amt'], 2, '.', '') + number_format($fdv['opening_amt'], 2, '.', '') ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Summary</th>
			<!--<th>-->
			<!--	<?= $totalLoss ?>-->
			<!--</th>-->
			<th>
				<?= $totalOpeningFine ?>
			</th>
			<th>
				<?= $totalOpeningAmt ?>
			</th>
			<th>
				<?= $totalDebitFine ?>
			</th>
			<th>
				<?= $totalDebitAmt ?>
			</th>
			<th>
				<?= $totalCreditFine ?>
			</th>
			<th>
				<?= $totalCreditAmt ?>
			</th>
			<th>
				<?= number_format($totalClosingFine, 2, '.', '') + number_format($totalOpeningFine, 2, '.', '') ?>
			</th>
			<th>
				<?= number_format($totalClosingAmt, 2, '.', '') + number_format($totalOpeningAmt, 2, '.', '') ?>
			</th>
		</tr>
	</tfoot>
</table>
