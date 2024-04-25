<style>
	.span-fine-ans,
	.span-alloy {
		background-color: yellow;
	}

	.username,
	.customer,
	.items_group,
	.item,
	.item_cat {
		cursor: pointer;
		color: #0062cc;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Account Ledger</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<label>From Date</label>
						<input type="date" value="<?= ($this->session->userdata('ledger_from_date')) ? $this->session->userdata('ledger_from_date') : NULL; ?>" class="form-control from" id="from_date" />
					</div>
					<div class="col-md-2">
						<label>To Date</label>
						<input type="date" value="<?= ($this->session->userdata('ledger_to_date')) ? $this->session->userdata('ledger_to_date') : date('Y-m-d'); ?>" class="form-control to" id="to_date" />
					</div>
					<div class="col-md-2 mt-3">
						<button class="btn btn-primary" id="search_btn" type="button">Search</button>
					</div>
				</div>
				<?php
				?>
				<div class="row mt-3">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive" id="set_table_here">
									<?php
									// pre($data);
									$totalOpeningFine   = $total_opening_fine;
									$totalOpeningAmount = $total_opening_amt;
									$typeDebitArr  = [
                                        'PUR',
                                        'SAL_RETURN',
                                        'FINE_CR',
                                        'BANK_CR',
                                        'ROOPU_CR',
                                        'TE_CR',
                                        
                                        
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
									// pre($data);
									foreach ($data['opening_data'] as $oIndex => $ov) {
										$totalOpeningAmount = number_format($totalOpeningAmount, 3, '.', '');
										$totalOpeningFine   = number_format($totalOpeningFine, 3, '.', '');
										// if ($ov['code'] == 'link(bank)') {
										// 	$isBank = true;
										// }
										if ($ov['type'] == 'PAY_PAY' && $ov['code'] != 'link(bank)') {
											// $totalOpeningAmount -= abs($ov['total_net_amt']);
											if ($ov['total_fine_gold'] <= 0) {
												$totalOpeningAmount -= abs($ov['total_net_amt']);
											}
											$totalOpeningFine -= abs($ov['total_fine_gold']);
										} else if ($ov['type'] == 'PAY_REC' && $ov['code'] != 'link(bank)') {
											// $totalOpeningAmount += abs($ov['total_net_amt']);
											if ($ov['total_fine_gold'] <= 0) {
												$totalOpeningAmount += abs($ov['total_net_amt']);
											}
											$totalOpeningFine += abs($ov['total_fine_gold']);
										} else if (in_array($ov['type'], $typeDebitArr)) {
											$totalOpeningAmount += abs($ov['total_net_amt']);
											$totalOpeningFine += abs($ov['total_fine_gold']);
										} else if (in_array($ov['type'], $typeCreditArr)) {
											$totalOpeningAmount -= abs($ov['total_net_amt']);
											$totalOpeningFine -= abs($ov['total_fine_gold']);
										}else if (in_array($ov['type'], $typeFineArr)) {
											if ($ov['total_fine_gold'] < 0) {
												$totalOpeningAmount += 0;
												$totalOpeningFine += abs($ov['total_fine_gold']);
											} else {
												$totalOpeningAmount -= 0;
												$totalOpeningFine -= abs($ov['total_fine_gold']);
											}
										}else if ($ov['type'] == 'RATEFINE_DB' || $ov['type'] == 'RATERS_DB') {
											$totalOpeningAmount -= abs($ov['total_net_amt']);
											$totalOpeningFine += abs($ov['total_fine_gold']);
										} else if ($ov['type'] == 'RATEFINE_CR' || $ov['type'] == 'RATERS_CR') {
											$totalOpeningAmount += abs($ov['total_net_amt']);
											$totalOpeningFine -= abs($ov['total_fine_gold']);
										}
										// console_log($ov['total_fine_gold']."---".$totalOpeningFine);

									}

								// 	pre($data);exit;

									$closingFine      = $totalOpeningFine;
									$closingAmt       = $totalOpeningAmount;
									$totalDebitFine   = 0;
									$totalCreditFine  = 0;
									$totalClosingFine = $totalOpeningFine;
									$totalDebitAmt    = 0;
									$totalCreditAmt   = 0;
									$totalClosingAmt  = $totalOpeningAmount;
									$totalLoss        = 0;
									?>

									<table class="table table-bordered ledger-table">
										<thead>
											<tr>
												<th></th>
												<th></th>
												<th></th>
												<?php if ($isBank == "bank") { ?>
													<th></th>
													<th></th>
												<?php } ?>
												<th colspan="2">Debit</th>
												<th colspan="2">Credit</th>
												<th colspan="2">Closing</th>
											</tr>
											<tr>
												<th>Code</th>
												<th>Date</th>
												<th>Particulars</th>
												<?php if ($isBank == "bank") { ?>
													<th>Loss</th>
													<th>Customer</th>
												<?php } ?>
												<th>Debit Fine</th>
												<th>Debit Amt.</th>
												<th>Credit Fine</th>
												<th>Credit Amt.</th>
												<th>Balance Fine</th>
												<th>Balance Amt.</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td></td>
												<td></td>
												<th>Opening</th>
												<?php if ($isBank == "bank") { ?>
													<td></td>
													<td></td>
												<?php } ?>
												<?php if ($totalOpeningAmount < 0 && $totalOpeningFine < 0) { ?>
													<th>
														<?= abs($totalOpeningFine) ?>
													</th>
													<th>
														<?= abs($totalOpeningAmount) ?>
													</th>
													<?php $totalDebitFine += abs($totalOpeningFine) ?>
													<?php $totalDebitAmt += abs($totalOpeningAmount) ?>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												<?php } else if ($totalOpeningAmount < 0) { ?>
													<?php $totalDebitAmt += abs($totalOpeningAmount) ?>
													<?php $totalCreditFine += abs($totalOpeningFine) ?>
													<td></td>
													<th>
														<?= abs($totalOpeningAmount) ?>
													</th>
													<th>
														<?= abs($totalOpeningFine) ?>
													</th>
													<td></td>
													<td></td>
													<td></td>
												<?php } else if ($totalOpeningFine < 0) { ?>
													<?php $totalDebitFine += abs($totalOpeningFine) ?>
													<?php $totalCreditAmt += abs($totalOpeningAmount) ?>
													<th>
														<?= abs($totalOpeningFine) ?>
													</th>
													<td></td>
													<td></td>
													<th>
														<?= abs($totalOpeningAmount) ?>
													</th>
													<td></td>
													<td></td>
												<?php } else { ?>
													<?php $totalCreditFine += abs($totalOpeningFine) ?>
													<?php $totalCreditAmt += abs($totalOpeningAmount) ?>
													<td></td>
													<td></td>
													<th>
														<?= abs($totalOpeningFine) ?>
													</th>
													<th>
														<?= abs($totalOpeningAmount) ?>
													</th>
													<td></td>
													<td></td>
												<?php } ?>
											</tr>
											<?php
								// 			pre($data);exit;
											$checkType = [
												'PUR' => 'purchase',
												'PUR_RETURN' => 'purchase_return',
												'SAL' => 'sale',
												'SAL_RETURN' => 'sale_return',
												'IN_GIVEN_FINE' => 'given',
												'receive' => 'receive',
												"KASAR" => "given",
												"FINE_CR"=>"jama",
												"FINE_DB"=>"jama",
												"BANK_CR"=>"jama",
												"BANK_DB"=>"jama",
												"RATEFINE_CR"=>"jama",
												"RATEFINE_DB"=>"jama",
												"RATERS_CR"=>"jama",
												"RATERS_DB"=>"jama",
												"ROOPU_CR"=>"jama",
												"ROOPU_DB"=>"jama",
												"TE_CR"=>"transfer_entry",
												"TE_DB"=>"transfer_entry",
												'IN_GIVEN_FINE'=>"given",
                                                'receive'=>"receive",
											];
											foreach ($data['data'] as $i => $v) {
												// pre($v);exit;
												$closingAmt       = number_format($closingAmt, 3, '.', '');
												$closingFine      = number_format($closingFine, 3, '.', '');
												$totalDebitAmt    = number_format($totalDebitAmt, 3, '.', '');
												$totalCreditFine  = number_format($totalCreditFine, 3, '.', '');
												$totalClosingFine = number_format($totalClosingFine, 3, '.', '');
												$totalClosingAmt  = number_format($totalClosingAmt, 3, '.', '');

												if ($v['code'] == 'link(bank)') {
														$url = site_url('admin/report/ledgerReportByCustomer/' . $v['party_id'] . '/bank');
												} else {
													$url = site_url('admin/report/ledgerReportByCustomer/' . $v['party_id']);
												}


											?>
												<?php $totalLoss += $v['loss']; ?>
												<tr class="change_color">
													<td>
														<div class="d-flex gap-3">
															<a href="<?= site_url($v['link']) ?>" target="_blank">
																<?= $i + 1; ?>
															</a>
															<?php
															if ($v['type'] != "BANK_CR" && $v['type'] != "BANK_DB") { ?>
																<input type="checkbox" <?= $v['verification'] == 'YES' ? "checked" : ""; ?> data-id="<?= (isset($v['id']) && !empty($v['id']) ? $v['id'] : "") ?>" data-user_id="<?= $v['party_id']; ?>" data-type="<?= $checkType[$v['type']] ?>" class="varification" />
															<?php } ?>
													</td>
								</div>
								<td>
									<?php if (!empty($v['date'])) {
													echo date('d-m-Y', strtotime($v['date']));
												} ?>
								</td>
								<td>
									<?= $v['type'];
												if ($v['type'] === "IN_GIVEN_FINE") echo " ( {$v['process']} )" ?>
								</td>
								<?php if ($isBank == "bank") { ?>
									<td>
										<?= $v['loss'] ?>
									</td>
									<td><a href="<?= $url ?>" target="_blank">
											<?= $v['customer_name'] ?>
										</a></td>
								<?php } ?>
								<?php if ($v['type'] == 'PAY_PAY' && $v['code'] != 'link(bank)') { ?>
									<?php
													if ($v['total_fine_gold'] <= 0) {
														$closingAmt -= abs($v['total_net_amt']);
														$totalDebitAmt += abs($v['total_net_amt']);
														$totalClosingAmt -= abs($v['total_net_amt']);
													}
									?>
									<?php $closingFine -= abs($v['total_fine_gold']) ?>
									<?php $totalDebitFine += abs($v['total_fine_gold']) ?>
									<?php //$totalDebitAmt += abs($v['total_net_amt']) 
									?>
									<?php $totalClosingFine -= abs($v['total_fine_gold']) ?>
									<?php //$totalClosingAmt -= abs($v['total_net_amt']) 
									?>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td></td>
									<td>
										<?= ($v['total_fine_gold'] > 0) ? abs($v['total_net_amt']) : '' ?>
									</td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
									<!-- // $totalOpeningFine -= abs($ov['total_fine_gold']); -->
								<?php } else if ($v['type'] == 'PAY_REC' && $v['code'] != 'link(bank)') { ?>
									<?php
													if ($v['total_fine_gold'] <= 0) {
														$closingAmt += abs($v['total_net_amt']);
														$totalCreditAmt += abs($v['total_net_amt']);
														$totalClosingAmt += abs($v['total_net_amt']);
													}

									?>
									<?php $closingFine += abs($v['total_fine_gold']) ?>
									<?php $totalCreditFine += abs($v['total_fine_gold']) ?>
									<?php //$totalCreditAmt += abs($v['total_net_amt']) 
									?>
									<?php $totalClosingFine += abs($v['total_fine_gold']) ?>
									<?php //$totalClosingAmt += abs($v['total_net_amt']) 
									?>
									<td></td>
									<td>
										<?= ($v['total_fine_gold'] > 0) ? abs($v['total_net_amt']) : '' ?>
									</td>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
									<!-- // $totalOpeningFine += abs($ov['total_fine_gold']); -->
								<?php } else if (in_array($v['type'], $typeCreditArr)) { ?>
									<?php $closingAmt -= abs($v['total_net_amt']) ?>
									<?php $closingFine -= abs($v['total_fine_gold']) ?>
									<?php $totalDebitFine += abs($v['total_fine_gold']) ?>
									<?php $totalDebitAmt += abs($v['total_net_amt']) ?>
									<?php $totalClosingFine -= abs($v['total_fine_gold']) ?>
									<?php $totalClosingAmt -= abs($v['total_net_amt']) ?>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td></td>
									<td></td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
								<?php } else if (in_array($v['type'], $typeDebitArr)) { ?>
									<?php $closingAmt += abs($v['total_net_amt']) ?>
									<?php $closingFine += abs($v['total_fine_gold']) ?>
									<?php $totalCreditFine += abs($v['total_fine_gold']) ?>
									<?php $totalCreditAmt += abs($v['total_net_amt']) ?>
									<?php $totalClosingFine += abs($v['total_fine_gold']) ?>
									<?php $totalClosingAmt += abs($v['total_net_amt']) ?>
									<td></td>
									<td></td>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
								<?php } else if (in_array($v['type'], $typeFineArr)) { ?>
									<?php if ($v['total_fine_gold'] < 0) { ?>
										<?php $closingAmt += abs($v['total_net_amt']) ?>
										<?php $closingFine += abs($v['total_fine_gold']) ?>
										<?php $totalCreditFine += abs($v['total_fine_gold']) ?>
										<?php $totalCreditAmt += abs($v['total_net_amt']) ?>
										<?php $totalClosingFine += abs($v['total_fine_gold']) ?>
										<?php $totalClosingAmt += abs($v['total_net_amt']) ?>
										<td></td>
										<td></td>
										<td>
											<?= abs($v['total_fine_gold']) ?>
										</td>
										<td>
											<?= abs($v['total_net_amt']) ?>
										</td>
										<td>
											<?= sprintf('%.3f', $closingFine) ?>
										</td>
										<td>
											<?= round($closingAmt) ?>
										</td>
									<?php } else { ?>
										<?php $closingAmt -= abs($v['total_net_amt']) ?>
										<?php $closingFine -= abs($v['total_fine_gold']) ?>
										<?php $totalDebitFine += abs($v['total_fine_gold']) ?>
										<?php $totalDebitAmt += abs($v['total_net_amt']) ?>
										<?php $totalClosingFine -= abs($v['total_fine_gold']) ?>
										<?php $totalClosingAmt -= abs($v['total_net_amt']) ?>
										<td>
											<?= abs($v['total_fine_gold']) ?>
										</td>
										<td>
											<?= abs($v['total_net_amt']) ?>
										</td>
										<td></td>
										<td></td>
										<td>
											<?= sprintf('%.3f', $closingFine) ?>
										</td>
										<td>
											<?= round($closingAmt) ?>
										</td>
									<?php } ?>
								<?php } else if ($v['type'] == 'RATEFINE_CR'  || $v['type'] == 'RATERS_CR') { ?>
									<?php $closingAmt -= abs($v['total_net_amt']) ?>
									<?php $closingFine += abs($v['total_fine_gold']) ?>
									<?php $totalDebitAmt += abs($v['total_net_amt']) ?>
									<?php $totalCreditFine += abs($v['total_fine_gold']) ?>
									<?php $totalClosingFine += abs($v['total_fine_gold']) ?>
									<?php $totalClosingAmt -= abs($v['total_net_amt']) ?>
									<td></td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td></td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
								<?php } else if ($v['type'] == 'RATEFINE_DB' || $v['type'] == 'RATERS_DB') { ?>
									<?php $closingAmt += abs($v['total_net_amt']) ?>
									<?php $closingFine -= abs($v['total_fine_gold']) ?>
									<?php $totalDebitFine += abs($v['total_fine_gold']) ?>
									<?php $totalCreditAmt += abs($v['total_net_amt']) ?>
									<?php $totalClosingFine -= abs($v['total_fine_gold']) ?>
									<?php $totalClosingAmt += abs($v['total_net_amt']) ?>
									<td>
										<?= abs($v['total_fine_gold']) ?>
									</td>
									<td></td>
									<td></td>
									<td>
										<?= abs($v['total_net_amt']) ?>
									</td>
									<td>
										<?= sprintf('%.3f', $closingFine) ?>
									</td>
									<td>
										<?= round($closingAmt) ?>
									</td>
								<?php } ?>
								</tr>
							<?php } ?>
							<tr>
								<th></th>
								<th></th>
								<th>Summary</th>
								<?php if ($isBank == "bank") { ?>
									<th>
										<?= $totalLoss ?>
									</th>
									<th></th>
								<?php } ?>
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
									<?= sprintf('%.3f', $totalClosingFine) ?>
								</th>
								<th>
									<?= round($totalClosingAmt) ?>
								</th>
							</tr>
							</tbody>
							<tfoot>

								<tr>
									<th></th>
									<th></th>
									<th></th>
									<?php if ($isBank == "bank") { ?>
										<th></th>
										<th></th>
									<?php } ?>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th class="<?= ($totalClosingFine < 0) ? 'text-danger' : 'text-success' ?>">
										<?= abs(sprintf('%.3f', $totalClosingFine)) ?>
										<?= ($totalClosingFine < 0) ? 'DR' : 'CR' ?>
									</th>
									<th class="<?= ($totalClosingAmt < 0) ? 'text-danger' : 'text-success' ?>">
										<?= abs(round($totalClosingAmt)) ?>
										<?= ($totalClosingAmt < 0) ? 'DR' : 'CR' ?>
									</th>
								</tr>
							</tfoot>
							</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
</div>
</div>
<input type="file" id="image" style="display:none" />
<input type="hidden" id="img_field" />
<input type="hidden" id="req_status" />
<?php $time = time(); ?>
<script class="javascript">
	$(document).ready(function() {
		<?php if ($isBank == 'bank') {
			$url = site_url('report/account_ledger/getLedgerCustomerReport/bank');
		} else {
			$url = site_url('report/account_ledger/getLedgerCustomerReport');
		} ?>
		$('#search_btn').click(search);

		function search() {
			var fromDate = $('#from_date').val();
			var toDate = $('#to_date').val();
			var ig_id = $('#select-items_group').val();
			var run_time_loss = $('#run_time_loss').prop('checked');
			$.ajax({
				beforeSend: function() {
					$("#set_table_here").html("Please Wait...");
				},
				url: "<?= $url ?>",
				method: 'POST',
				showLoader: true,
				data: {
					fromDate: fromDate,
					toDate: toDate,
					items_group_id: ig_id,
					customer_id: "<?= $cid ?>",
					run_time_loss: run_time_loss
				},
				success: function(data) {
					$("#set_table_here").html(data);
					$(".varification").each(function() {
						IsChecked(this);
					});
				},
			});
		}

		$(document).on("change", ".varification", function() {
			IsChecked(this);
			var isChecked = $(this).is(':checked');
			var customer_id = $(this).data('user_id');
			var type = $(this).data('type');
			var id = $(this).data('id');
			$.ajax({
				url: `${BaseUrl}report/account_ledger/verification`,
				type: 'POST',
				data: {
					id: id,
					status: isChecked ? 'checked' : 'unchecked',
					customer_id: customer_id,
					type: type,
				},
				success: function(response) {
					var response = JSON.parse(response);
					if (response.success) {
						SweetAlert("success", response.message);
					} else {
						SweetAlert("warning", response.message);
					}
				},
				error: function(xhr, status, error) {
					alert("An error occurred.");
				}
			});
		});

		$(".varification").each(function() {
			IsChecked(this);
		});

		function IsChecked(verification) {
			var checkbox = verification instanceof jQuery ? verification[0] : verification;
			if (checkbox.checked) {
				$(checkbox).closest('tr').find('td').css('background-color', '#ffbcbc');
			} else {
				$(checkbox).closest('tr').find('td').css('background-color', '');
			}
		}
	});
</script>