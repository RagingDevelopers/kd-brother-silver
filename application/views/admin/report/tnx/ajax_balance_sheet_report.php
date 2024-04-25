<?php
extract($data);
$totalOpeningFine = 0;
// pre($data);
$customers = [];
$account_categories = [];
foreach ($data['data'] as $di => $dv) {

	// if ($dv['customer_id'] == 0) {
	// 	pre($dv);
	// }

	if (!in_array($dv['party_id'], $customers)) {
		$customers[] = $dv['party_id'];
		$account_categories[] = $dv['account_category'];
	}
}
// pre($data);
$data['filtered_data'] = [];
$totalDebitFine = 0;
$totalCreditFine = 0;
$totalClosingFine = 0;
$totalDebitAmt = 0;
$totalCreditAmt = 0;
$totalClosingAmt = 0;
$totalOpeningFine = 0;
$totalOpeningAmt = 0;

// pre($data);
$totalLoss = 0;

foreach ($customers as $ci => $c) {

	$closingFine = 0;
	$closingAmt = 0;
	$openingFine = 0;
	$openingAmt = 0;
	$date = '';
	$customerName = '';
	$customerId = 0;
	$totalDebitFine2 = 0;
	$totalDebitAmt2 = 0;
	$totalCreditFine2 = 0;
	$totalCreditAmt2 = 0;

	$loss = 0;

	$isBank = false;

	$account_category = '';

	foreach ($data['data'] as $di => $v) {
		// pre($v);
		$account_category = $v['account_category'];

		if ($v['code'] == 'link(bank)') {
			$isBank = true;
		}
		if ($v['party_id'] == $c) {
			$loss += $v['loss'];
			$customerId = $c;
			$date = $v['date'];
			$customerName = $v['customer_name'];
            $typeDebitArr  = [
            'PUR',
            'SAL_RETURN',
            'FINE_CR',
            'BANK_CR',
            'ROOPU_CR',
            'TE_CR'
            
            ];
            $typeCreditArr = [
            'SAL',
            'PUR_RETURN',
            'FINE_DB',
            'BANK_DB',
            'ROOPU_DB',
            'TE_DB',
            'receive'
            ];
            $typeFineArr   = [
                'IN_GIVEN_FINE'
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
				$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;;
				// $totalClosingAmt -= abs($v['total_net_amt']);
			} else if ($v['type'] == 'PAY_REC' && !$isBank) {
				// $closingAmt += abs($v['total_net_amt']);
				if ($v['total_fine_gold'] <= 0) {
					$closingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']): 0;
					$totalCreditAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']): 0;
					$totalClosingAmt += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ? abs($v['total_net_amt']): 0;
				}
				$closingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
				$totalCreditFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
				// $totalCreditAmt2 += abs($v['total_net_amt']);
				$totalClosingFine += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ? abs($v['total_fine_gold']) : 0;
				// $totalClosingAmt += abs($v['total_net_amt']);
			} else if (in_array($v['type'], $typeCreditArr)) {
				$closingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ?  abs($v['total_net_amt']) : 0;
				$closingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ?  abs($v['total_fine_gold']) : 0;
				$totalDebitFine2 += (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ?  abs($v['total_fine_gold']) : 0;
				$totalDebitAmt2 += (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ?  abs($v['total_net_amt']) : 0;
				$totalClosingFine -= (isset($v['total_fine_gold']) && !empty($v['total_fine_gold'])) ?  abs($v['total_fine_gold']) : 0;
				$totalClosingAmt -= (isset($v['total_net_amt']) && !empty($v['total_net_amt'])) ?  abs($v['total_net_amt']) : 0;
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
		if ($c == $v['customer_id']) {
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
			} else if (in_array($v['type'], $typeCreditArr)) {
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
			} else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') {
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
		'date' => $dv['date'],
		'type' => '',
		'customer_name' => $customerName,
		'customer_id' => $customerId,
		'opening_fine' => $openingFine,
		'opening_amt' => $openingAmt,
		'total_debit_fine' => $totalDebitFine2,
		'total_debit_amt' => $totalDebitAmt2,
		'total_credit_fine' => $totalCreditFine2,
		'total_credit_amt' => $totalCreditAmt2,
		'closing_fine' => $closingFine,
		'closing_amt' => $closingAmt,
		'loss' => $loss,
		'isBank' => $isBank,
		'account_category' => $account_categories[$ci]
	];
}
?>
<?php
$filteredData = [];
$x = 0;
$y = 0;
?>
<?php foreach ($data['filtered_data'] as $fdi => $fdv) {

	// if ($fdv['isBank']) { // this was for all accounts by considering that all accounts are banks
	if ($fdv['customer_id'] < 0) {
		$url = site_url('report/account_ledger/ledgerReportByCustomer/' . abs($fdv['customer_id']) . '/bank');
	} else {
		if($fdv['account_category'] == "bank"){
			$url = site_url('report/account_ledger/ledgerReportByCustomer/' . abs($fdv['customer_id']) . '/bank');
		}else{
			$url = site_url('report/account_ledger/ledgerReportByCustomer/' . $fdv['customer_id']);
		}
	}

	$closingAmount = $fdv['closing_amt'] + $fdv['opening_amt'];
	$closingfine = $fdv['closing_fine'] + $fdv['opening_fine'];

	if ($closingAmount < 0 || $closingfine < 0) {
		$filteredData['debit'][$fdv['account_category']][$x] = [
			'account_category' => $fdv['account_category'],
			'account' => '<a href="' . $url . '" target="_blank">' . $fdv['customer_name'] . '</a>'
		];
		if ($closingfine < 0) {
			$filteredData['debit'][$fdv['account_category']][$x]['closing_fine'] = abs($closingfine);
		} else {
			$filteredData['debit'][$fdv['account_category']][$x]['closing_fine'] = '';
		}
		if ($closingAmount < 0) {
			$filteredData['debit'][$fdv['account_category']][$x]['closing_amt'] = abs($closingAmount);
		} else {
			$filteredData['debit'][$fdv['account_category']][$x]['closing_amt'] = '';
		}
		$x++;
	}

	if ($closingAmount > 0 || $closingfine > 0) {
		$filteredData['credit'][$fdv['account_category']][$y] = [
			'account_category' => $fdv['account_category'],
			'account' => '<a href="' . $url . '" target="_blank">' . $fdv['customer_name'] . '</a>'
		];
		if ($closingfine >= 0) {
			$filteredData['credit'][$fdv['account_category']][$y]['closing_fine'] = abs($closingfine);
		} else {
			$filteredData['credit'][$fdv['account_category']][$y]['closing_fine'] = '';
		}
		if ($closingAmount >= 0) {
			$filteredData['credit'][$fdv['account_category']][$y]['closing_amt'] = abs($closingAmount);
		} else {
			$filteredData['credit'][$fdv['account_category']][$y]['closing_amt'] = '';
		}
		$y++;
	}
?>
<?php } ?>
<?php // console_log($filteredData) 
?>
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Debit</th>
				<th>Credit</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Account</th>
									<th>Fine</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$acd = [];
								?>
								<?php foreach ($filteredData['debit'] as $k => $v) { ?>
									<?php if (!isset($acd[$k])) {
										$acd[$k] = $k;
										$total_fine = 0;
										$total_amt = 0;
									?>
										<th colspan="3"><?= $k ?></th>
									<?php } ?>
									<?php foreach ($v as $i => $vv) {
										$total_fine += isset($vv['closing_fine']) && !empty($vv['closing_fine']) ? $vv['closing_fine'] : 0;
										$total_amt += isset($vv['closing_amt']) && !empty($vv['closing_amt']) ? $vv['closing_amt'] : 0;										
									?>
										<tr>
											<td><?= $vv['account'] ?></td>
											<td><?= $vv['closing_fine'] ?></td>
											<td><?= $vv['closing_amt'] ?></td>
										</tr>
									<?php } ?>
									<tr>
										<th></th>
										<th><?= $total_fine; ?></th>
										<th><?= $total_amt; ?></th>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</td>
				<td>
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Account</th>
									<th>Fine</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$acc = [];
								?>
								<?php foreach ($filteredData['credit'] as $k => $v) { ?>
									<?php if (!isset($acc[$k])) {
										$acc[$k] = $k;
										$total_fine = 0;
										$total_amt = 0;
									?>
										<th colspan="3"><?= $k ?></th>
									<?php } ?>
									<?php foreach ($v as $i => $vv) {
										$total_fine += isset($vv['closing_fine']) && !empty($vv['closing_fine']) ? $vv['closing_fine'] : 0;
										$total_amt += isset($vv['closing_amt']) && !empty($vv['closing_amt']) ? $vv['closing_amt'] : 0;										
									?>

										<tr>
											<td><?= $vv['account'] ?></td>
											<td><?= $vv['closing_fine'] ?></td>
											<td><?= $vv['closing_amt'] ?></td>
										</tr>
									<?php } ?>
									<tr>
										<th></th>
										<th><?= $total_fine; ?></th>
										<th><?= $total_amt; ?></th>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
