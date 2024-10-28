<style>
	.silver-input {
		background-color: #ebebeb;
		color: black;
	}

	.weight-input {
		background-color: #e6fdff;
	}

	.copper-input {
		background-color: #ffeeee;
	}

	/*.select2 {*/
	/*	width: 140px !important;*/
	/*}*/

	.inputBox {
		width: 70px !important;
	}

	.inputBox100 {
		width: 100px !important;
	}

	.readonly {
		background-color: #ebebeb;
		color: black;
	}

	.w65 {
		width: 60px !important;
	}

	.element {
		overflow: hidden;
		/* or overflow: visible; */
	}

	/* Chrome, Safari, Edge, Opera */
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	/* Firefox */
	input[type=number] {
		-moz-appearance: textfield;
	}





	.table-wrap {
		position: relative;
	}

	.table-scroll th,
	.table-scroll td {
		padding: 2px 1px;
		border: 1px solid #0000004d;
		background: #fff;
		vertical-align: top;
	}

	.table-scroll thead th {
		background: #4f4f4fbf;
		color: #fff;
		position: -webkit-sticky;
		position: sticky;
		top: 0;
	}

	.form-control {
		padding-right: 1px !important;
		padding-left: 1px !important;
		border: none !important;
		border-bottom: 1px solid black !important;
	}
	    .no-spinners::-webkit-outer-spin-button,
    .no-spinners::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .no-spinners {
        -moz-appearance: textfield;
    }

    .table-responsive {
        position: relative;
        max-height: 300px;
        overflow-y: auto;

    }

    .table-responsive thead {
        position: sticky;
        top: 0;
        z-index: 100;
        background-color: white;
        /* Ensures the header has a background */
    }

    .table-responsive tfoot {
        position: sticky;
        bottom: 0;
        z-index: 100;
        background-color: white;
        /* Ensures the header has a background */
    }
</style>
<div class="row element">
	<div class="col-sm-12">
		<div class="card">
			<form action="<?= (isset($data)) ? base_url("sales/update/{$data['id']}") : base_url('sales/store') ?>" method="post" class="main-form" novalidate>
				<div class="card-header">
					<div class="card-status-top bg-blue"></div>
					<h1 class="card-title"><b> Sale </b></h1>
				</div>
				<div class="card-body border-bottom py-3">
					<div class="col-md-12 mb-5 ">
						<div class="row ms-1">
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Date <span class="text-danger">*</span></label>
									<input type="date" name="date" class="form-control from" id="date" value="<?php if (isset($data['date'])) {
																													echo $data['date'];
																												} else {
																													echo date('Y-m-d');
																												} ?>" />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Party<span class="text-danger">*</span></label>
									<select name="party_id" class="form-select select2" id="party_id">
										<option value="">Select Customer</option>
										<?php foreach ($party as $c) { ?>
											<option value="<?= $c['id']; ?>" <?php if (isset($data['party_id']) && $data['party_id'] == $c['id']) {
																					echo "selected";
																				} ?>>
												<?= $c['name']; ?>
											</option>
										<?php } ?>
									</select>
									<h4 class="text-blue pt-1" id="closing-label"></h4>
								</div>
							</div>
							<!-- <div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Tag</label>
									<input type="number" id="tag" class="form-control tag" placeholder="Right-click to paste (use Ctrl+V)" autocomplete="off">
								</div>
							</div> -->
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">RFS Code</label>
									<input type="text" id="RFSCode" class="form-control" placeholder="Right-click to paste (use Ctrl+V)" autocomplete="off">
									<input type="hidden" name="RFSCode" class="form-control RFSCode" autocomplete="off">
									<input type="hidden" id="paymentArray" name="paymentArray" value="" />
									<input type="hidden" id="closing_fine" name="closing_fine" value="" />
									<input type="hidden" id="closing_amount" name="closing_amount" value="" />
									<input type="hidden" id="total_fine" name="total_fine" value="" />
									<input type="hidden" id="total_amount" name="total_amount" value="" />
								</div>
							</div>

							<div class="card mt-5">
								<div class="row">
									<div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
										<table class="table card-table table-vcenter  table-scroll text-center text-nowrap">
											<thead>
												<tr>
													<th>Item</th>
													<!-- <th>Sub Item</th> -->
													<th>Stamp</th>
													<th>Unit</th>
													<th>Remarks</th>
													<th>Gross</th>
													<th>Less</th>
													<th>Net</th>
													<th>Pre Touch</th>
													<th>Touch</th>
													<th>Wastage</th>
													<th>Fine</th>
													<th>Pcs</th>
													<th>Rate</th>
													<th>Labour</th>
													<th>Type</th>
													<th>O Amt</th>
													<th>T Amt</th>
													<th></th>
												</tr>
											</thead>

											<tbody class="paste append-here">
												<?php
												if (empty($data['sale_detail'])) {
													$data['sale_detail'][] = [
														'item'               => 0,
														// 'sub_item_id'		 => 0,
														'stamp'              => 0,
														'unit'               => 0,
														'remark'             => '',
														'gross_weight'       => 0,
														'less_weight'        => 0,
														'net_weight'         => 0,
														'touch'       		 => 0,
														'wastage'     		 => 0,
														'fine'       		 => 0,
														'piece'       		 => 0,
														'labour'      		 => 0,
														'labour_type'      	 => 0,
														'rate'        		 => 0,
														'sub_total'   		 => 0,
														'id'          		 => 0
													];
												}
												foreach ($data['sale_detail'] as $row) { ?>
													<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
													<tr class="main-row">
														<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? null; ?>" />
														<input type="hidden" name="lot_creation_tag[]" value="<?= $row['lot_creation_tag'] ?? null; ?>" class="form-control hiddenTag" autocomplete="off">
														<td>
															<select class="form-select select2 item" data-sub_item="<?= isset($row) && !empty($row['sub_item_id']) ? $row['sub_item_id'] : ""?>" required name="item[]">
																<option value="">Select Item</option>
																<?php foreach ($item as $i) { ?>
																	<option value="<?= $i['id']; ?>" <?php if (isset($row['item_id']) && $i['id'] == $row['item_id']) {
																											echo 'selected';
																										} ?>> <?= $i['name']; ?>
																	</option>
																<?php } ?>
															</select>
														</td>
														<!-- <td>
															<select class="form-select select2 sub_item" required name="sub_item[]">
															</select>
														</td> -->
														<td>
															<select name="stamp[]" class="form-control w65 select2 stamp">
																<option value="">Select Stamp</option>
																<option value="null">None</option>
																<?php foreach ($stamp as $s) { ?>
																	<option value="<?= $s['id']; ?>" <?php if (isset($row['stamp_id']) && $s['id'] == $row['stamp_id']) {echo 'selected';} ?>> <?= $s['name']; ?> </option>
																<?php } ?>
															</select>
														</td>
														<td><select name="unit[]" id="" class="form-control w65 unit">
																<?php foreach ($unit as $u) { ?>
																	<option value="<?= $u['id']; ?>" <?php if (isset($row['unit_id']) && $u['id'] == $row['unit_id']) {
																											echo 'selected';
																										}else if($u['name'] == 'KG' && !isset($row['unit_id'])){ echo 'selected'; } ?>><?= $u['name']; ?></option>
																<?php } ?>
															</select></td>
														<td><input type="text" class="form-control remark inputBox" name="remark[]" placeholder="Remark" value="<?= $row['remark'] ?? null ?>"></td>
														<td>
															<input type="text" class="form-control gross_weight inputBox" name="gross_weight[]" placeholder="Gross Weight" value="<?= $row['gross_weight'] ?? null ?>">
														</td>
														<td>
															<input type="hidden" name="raw-material-data[]" value="<?= $row['raw_material_data'] ?? null; ?>" class="form-control rmdata" placeholder="Enter Weight" autocomplete="off">
															<div class="d-flex gap-2">
																<input type="text" class="form-control inputBox less_weight" name="less_weight[]" placeholder="Less Weight" value="<?= $row['less_weight'] ?? null ?>">
																<button type="button" class="bg-danger btn btn-action text-danger-fg me-2 Receivedmaterial" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Received">
																	<i class="fa-solid fa-hashtag"></i>
																</button>
															</div>
														</td>
														<td><input type="text" class="form-control inputBox net_weight readonly" name="net_weight[]" readonly placeholder="Net Weight" value="<?= $row['net_weight'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox touchPreData" name="pre_touch[]" placeholder="Pre Touch" value="<?= $row['touch'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox touchData" name="touch[]" placeholder="Touch" value="<?= $row['touch'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox wastage" name="wastage[]" placeholder="Wastage" value="<?= $row['wastage'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox fine readonly" name="fine[]" readonly placeholder="Fine" value="<?= $row['fine'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox piece" name="piece[]" placeholder="Piece" value="<?= $row['piece'] ?? null ?>"></td>
														<td><input type="text" class="form-control rate inputBox" name="rate[]" placeholder="Rate" value="<?= $row['rate'] ?? null ?>"></td>
														<td><input type="number" step="any" class="form-control labour inputBox" name="labour[]" placeholder="Labour Amount" value="<?= $row['labour'] ?? null; ?>" /></td>
														<td><select name="labour_type[]" class="form-control w65 labour_type">
																<option value="">Select Labour</option>
																<option value="net" <?php if ($row['labour_type'] == 'net') {
																						echo 'selected';
																					} ?>>Net</option>
																<option value="pcs" <?php if ($row['labour_type'] == 'pcs') {
																						echo 'selected';
																					} ?>>Pcs</option>
																<option value="fixed" <?php if ($row['labour_type'] == 'fixed') {
																							echo 'selected';
																						} ?>>Fixed</option>
																<option value="gross" <?php if ($row['labour_type'] == 'gross') {
																							echo 'selected';
																						} ?>>Gross</option>
															</select>
														</td>
														<td><input type="number" step="any" class="form-control other_amount inputBox" name="other_amount[]" placeholder="Other Amount" value="<?= $row['other_amount'] ?? 0; ?>" /></td>
														<td><input type="text" class="form-control sub_total inputBox100" name="sub_total[]" placeholder="Sub Total" value="<?= $row['sub_total'] ?? 0 ?>"></td>
														<td>
															<button type="button" class="btn btn-danger remove-btn">X</button>
														</td>
													</tr>
												<?php } ?>
											</tbody>
											<tfoot>
												<tr>
													<td>
														<h3>Total :</h3>
													</td>
													<td></td>
													<td></td>
													<td></td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalGross_weight'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalLess_weight'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalNet_weight'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalPreTouchData'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalTouchData'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalWastage'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalFine'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalPiece'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalRate'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalLabourAmount'>0</span></h4>
														</div>
													</td>
													<td></td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalOtherAmount'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 Sub_total'>0</span></h4>
														</div>
													</td>
												</tr>
												<!--<tr>-->
												<!--	<td colspan="1" class="d-flex border border-0 align-content-start flex-wrap">-->
												<!--		<button type="button" class="btn btn-success " id="add">-->
												<!--			Add row <i class="ms-2 fa-solid fa-plus"></i>-->
												<!--		</button>-->
												<!--	</td>-->
												<!--</tr>-->
											</tfoot>
										</table>
									</div>
									<div class="text-left pt-1">
									    <button type="button" class="btn btn-success " id="add">Add row <i class="ms-2 fa-solid fa-plus"></i></button>
								    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
				    <div class="row col-md-12">
				        <div class="col-md-1">
					        <button type="submit" class="btn btn-primary ms-auto">Submit</button>
					    </div>
					    <div class="col-md-1">
					        <button type="button" class="btn btn-success me-2 paymentAdd" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Received">Payment Add</button>
				        </div>
				        <div class="col-md-5"></div>
				        <div class="col-md-5" style="padding-left: 145px;padding-right: 130px;">
        				    <table class="table card-table table-vcenter  table-scroll text-center text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Net</th>
                                        <th style="width: 70px !important;">Fine</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="TotalNet_weight">0</td>
                                        <td class="TotalFine">0</td>
                                        <td class="Sub_total">0</td>
                                    </tr>
                                    <tr>
                                        <td style="color:red;">Closing Amount</td>
                                        <td class="fineClosing"><?php if (isset($data['closing_fine'])) { echo $data['closing_fine'];}else{ echo 0; } ?></td>
                                        <td class="amountClosing"><?php if (isset($data['closing_amount'])) { echo $data['closing_amount'];}else{ echo 0; } ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
			</form>
		</div>
	</div>
	<div class="col-sm-7 pt-2"></div>
	<div class="col-sm-5 pt-2" style="padding-right: 44px">
	    <table id="dataTableExample" class="table card-table table-vcenter table-scroll text-center text-nowrap">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Type</th>
                    <th>Payment Type</th>
                    <th style="width: 70px !important;">Fine</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $ii=1; foreach($payment ?? [] as $p){?>
                    <tr>
                        <td class="sid" data-rate="<?=$p->rate?>" data-bankname="<?=$p->bankname?>" data-bankid="<?=$p->bank_id?>"><?=$ii?></td>
                        <td class="saleId" data-gross="<?=$p->gross?>" data-saleid="<?=$p->id?>"><?=$p->type?></td>
                        <td><?=$p->payment_type?></td>
                        <td class="fine" data-amount="<?=$p->amount?>" data-metal_type_id="<?=$p->metal_type_id?>" data-payment_type="<?=$p->payment_type?>"><?=$p->payment_type == 'CREDIT' ? $p->fine : '-'.$p->fine?></td>
                        <td class="amount" data-purity="<?=$p->purity?>" data-wb="<?=$p->wb?>" data-remark="<?=$p->remark?>"><?=$p->payment_type == 'CREDIT' ? $p->amount :'-'.$p->amount?></td>
                        <td><button class='btn btn-success editPayment' data-id="<?=$ii?>"><i class='fas fa-edit'></i></button><button class='btn btn-danger removePayment' data-id="<?=$ii?>"><i class='fa fa-trash' aria-hidden='true'></i></button></td>
                    </tr>
                <?php $ii++; } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="border:none;"></td>
                    <td style="border:none;"></td>
                    <td style="border:none;"></td>
                    <td class="finalFine"><?php if (isset($data['total_fine'])) { echo $data['total_fine'];}else{ echo 0; } ?></td>
                    <td class="finalAmount"><?php if (isset($data['total_amount'])) { echo $data['total_amount'];}else{ echo 0; } ?></td>
                    <td style="border:none;"></td>
                </tr>
            </tfoot>
        </table>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Row Material</th>
							<th>Quantity</th>
							<th>Rate</th>
							<th>Sub Total</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="JBody">
						<?php
						if (empty($given_row_materials)) {
							$given_row_materials[] = [
								'row_material_id' => 0,
								'quantity'        => 0,
								'rate'            => 0,
								'sub_total'       => 0,
								'id'              => 0
							];
						}
						foreach ($given_row_materials as $row) { ?>
							<tr class="mainRow main-row">
								<td>
									<input type="hidden" class="received_detail_id" />
									<select class="form-select select2 row_material">
										<option value="">Select RM</option>
										<?php
										if (!empty($row_material)) {
											foreach ($row_material as $rm) { ?>
												<option value="<?= $rm['id']; ?>" <?php if (isset($row) && $rm['id'] == $row['row_material_id']) {
																						echo 'selected';
																					} ?>><?= $rm['name']; ?></option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control rmrate" placeholder="Enter rate" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control rmsub_total readonly" readonly placeholder="Enter sub_total" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger deleteRow2">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-qty'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-rmrate'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-rmsub_total'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-outline-success btn-success addButton2">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<div>
					<button type="button" class="btn btn-outline-secondary btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-outline-primary btn-primary saveRmData">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="payment-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Payment</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mainRow" style="margin-top:1%;">
				    <div class="col-md-2">
						<div class="form-group">
							<label class="form-label">Payment Type<span class="text-danger">*</span></label>
							<select name="payment_type" id="payment_type" class="form-select select2" required>
								<option value="">Select</option>
								<option value="CREDIT">CREDIT / PURCHASE / RECEIPT</option>
								<option value="DEBIT">DEBIT / SALE / PAYMENT</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="form-label">Type<span class="text-danger">*</span></label>
							<select name="type" id="type" class="form-select select2" required>
								<option data-mode="" value="">select</option>
								<option value="fine" data-mode="Chorsa,Silli">Fine</option>
								<!-- <option value="cash" data-mode="">Cash</option> -->
								<option value="bank" data-mode="bank">Bank</option>
								<option value="ratecutfine" data-mode="Cash,RTGS">Rate Cut - Fine</option>
								<option value="ratecutrs" data-mode="Cash,RTGS">Rate Cut - Rs</option>
								<option value="roopu" data-mode="">Roopu</option>
							</select>
						</div>
					</div>
					<div class="col-md-1 allinone dfine dratecutfine dratecutrs">
						<div class="form-group">
							<label class="form-label">Mode<span class="text-danger">*</span></label>
							<select name="mode" id="mode" class="form-select">
							</select>
						</div>
					</div>
					<div class="col-md-1 allinone dratecutrs">
						<div class="form-group">
							<label class="form-label">Amount<span class="text-danger">*</span></label>
							<input type="text" name="amount" class="form-control" id="amount" value="" placeholder="amount" />
						</div>
					</div>
					<div class="col-md-1 allinone dfine dratecutfine droopu">
						<div class="form-group">
							<label class="form-label">Gross <span class="text-danger">*</span></label>
							<input type="text" name="gross" class="form-control" id="gross" value="0" placeholder="gross" />
						</div>
					</div>
					<div class="col-md-1 allinone dfine dratecutfine dratecutrs droopu">
						<div class="form-group">
							<label class="form-label">Purity<span class="text-danger">*</span></label>
							<input type="text" name="purity" class="form-control" id="purity" value="100" placeholder="purity" />
						</div>
					</div>
					<div class="col-md-1 allinone dfine dratecutfine dratecutrs droopu">
						<div class="form-group">
							<label class="form-label">W/B<span class="text-danger">*</span></label>
							<input type="text" name="wb" class="form-control" id="wb" value="" placeholder="wb" />
						</div>
					</div>
					<div class="col-md-1 allinone dfine dratecutfine dratecutrs droopu">
						<div class="form-group">
							<label class="form-label">Fine<span class="text-danger">*</span></label>
							<input type="text" name="fine" class="form-control" id="fine" value="0" placeholder="fine" />
						</div>
					</div>
					<div class="col-md-1 allinone dratecutfine dratecutrs">
						<div class="form-group">
							<label class="form-label">Rate<span class="text-danger">*</span></label>
							<input type="text" name="rate" class="form-control" id="rate" value="" placeholder="rate" />
						</div>
					</div>
					<div class="col-md-2 allinone dbank">
						<div class="form-group">
							<label class="form-label">Bank<span class="text-danger">*</span></label>
							<select name="bank" id="bank" class="form-select select2">
								<option value="">select Bank</option>
								<?php foreach ($bank as $bank) { ?>
									<option value="<?= $bank['id']; ?>"><?= $bank['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-1 allinone dcash dbank dratecutfine">
						<div class="form-group">
							<label class="form-label">Amount<span class="text-danger">*</span></label>
							<input type="number" name="amount" class="form-control" id="amount2" value="" placeholder="amount" />
						</div>
					</div>
					<div class="col-md-1  allinone dfine dratecutfine dratecutrs">
						<div class="form-group">
							<label class="form-label">Metal Type<span class="text-danger">*</span></label>
							<select name="metal_type_id" id="metal_type_id" class="form-select select2">
								<option value="">select Metal Type</option>
								<?php foreach ($metal_type as $r) { ?>
									<option value="<?= $r['id']; ?>"><?= $r['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="form-label">Remark</label>
							<input type="text" name="remark" class="form-control" id="remark" value="" placeholder="remark" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<div>
					<button type="button" class="btn btn-outline-secondary btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-outline-primary btn-primary savePmData">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $time = time() ?>
<script class="javascript" src="<?= base_url("assets") ?>/dist/js/sale.js?v=<?= $time ?>"></script>
<script class="javascript">
	$('#RFSCode').on('contextmenu', function(e) {
        e.preventDefault();
        navigator.clipboard.readText().then((text) => {
            $('#RFSCode').val(text);
            if (text != "") {
                SweetAlert("success", `${text}  Past Successfully!!`);
            } else {
                SweetAlert("error", `Text Not Found`);
            }
        }).catch((err) => {
            SweetAlert("error", `Failed to read clipboard contents: ${err}`);
        });
    });
</script>
<script class="javascript">
    if ($("#party_id").val()) {
        setTimeout(() => {
            $("#party_id").trigger('change');
		}, 300);
    }
	var modal = $("#payment-report");
    $(document).on("click", ".paymentAdd", function () {
		$('input',modal).val('');
		window.edit = false;
		$('select',modal).val('').trigger('change');
		modal.modal("show");
	});
	
    $(document).on("change", "#type", function () {
		var mode = $(this).find(':selected').data('mode');
		var type = $(this).val();
		$(".allinone").hide();
		$("#mode").html('');
		if (mode != "") {
			var modearray = mode.split(",");
			if (modearray.length > 0) {
				var html = '';
				$.each(modearray, function(index, value) {
					html += '<option value="' + value + '">' + value + '</option>';
				});
				$("#mode").html(html);
			}
		}
		var newtype = 'd' + type;
		$("." + newtype).show();

	});
	
	$(document).ready(function() {
        var allDataArray = [];
        var rowIndex = 1;
    
        function reverseDataFromTable() {
            $('#dataTableExample tbody tr').each(function(index) {
                var row = $(this);
                var saleid = row.find('.saleId').data('saleid');
                var gross = row.find('.saleId').data('gross');
                
                var rate = row.find('.sid').data('rate');
                var bankName = row.find('.sid').data('bankname');
                var bankid = row.find('.sid').data('bankid');
                
                var amount = row.find('.fine').data('amount');
                var metal_type_id = row.find('.fine').data('metal_type_id');
                var payment = row.find('.fine').data('payment_type');
                
                var purity = row.find('.amount').data('purity');
                var wb = row.find('.amount').data('wb');
                var remark = row.find('.amount').data('remark');
                
                allDataArray[index + 1] = {
                    type: row.find('td').eq(1).text(),
                    mode: row.find('td').eq(2).text(),
                    gross: gross,
                    purity: purity,
                    wb: wb,
                    fine: row.find('td').eq(6).text(),
                    rate: rate,
                    bank: bankid,
                    bankName: bankName,
                    amount: amount,
                    metal_type_id: metal_type_id,
                    remark: remark,
                    payment: payment,
                    id: rowIndex,
                    saleid: saleid
                };
                rowIndex++;
            });
            $('#paymentArray').val(JSON.stringify(allDataArray));
            setTimeout(function () {
                total();
            }, 1000);
        }
    
        <?php if(isset($payment)){ ?>
            reverseDataFromTable();
        <?php } ?>
    
        $('.savePmData').on('click', function() {
            
            if(!$('#payment_type').val()){
                SweetAlert("error", `Payment Type Field Is Required`);
                return;
            }
            if(!$('#type').val()){
                SweetAlert("error", `Type Field Is Required`);
                return;
            }
            
            if(window.edit == true) {
                handleEditCase(window.index);
                return;
            }
    
            var type = $('#type').val();
            var mode = $('#mode').val();
            var gross = $('#gross').val();
            var purity = $('#purity').val();
            var wb = $('#wb').val();
            var fine = $('#fine').val();
            var rate = $('#rate').val();
            var bank = $('#bank').val();
            var bankName = $('#bank option:selected').text();
            var amount = $('#amount2').val();
            var metal_type_id = $('#metal_type_id').val();
            var remark = $('#remark').val();
            var paymentType = $('#payment_type').val();
    
            var newRow = `<tr data-id="${rowIndex}">
                <td>${rowIndex}</td>
                <td>${type}</td>
                <td>${paymentType ?? ''}</td>
                <td class="fine">${paymentType == 'CREDIT' ? fine : '-'+fine}</td>
                <td class="amount">${paymentType == 'CREDIT' ? amount : '-'+amount}</td>
                <td>
                    <button class='btn btn-success editPayment' data-id="${rowIndex}"><i class='fas fa-edit'></i></button>
                    <button class='btn btn-danger removePayment' data-id="${rowIndex}"><i class='fa fa-trash' aria-hidden='true'></i></button>
                </td>
            </tr>`;
            $('#dataTableExample tbody').append(newRow);
    
            allDataArray[rowIndex] = {
                type: type, 
                mode: mode,
                amount: amount,
                gross: gross,
                purity: purity,
                wb: wb,
                fine: fine,
                rate: rate,
                bank: bank,
                bankName: bankName,
                metal_type_id: metal_type_id,
                remark: remark,
                payment: paymentType,
                saleid: null
            };
    
            $('#payment-report input').val('');
            $('#payment-report select').val('').trigger('change');
            $('#payment-report').modal('hide');
            rowIndex++;
            $('#paymentArray').val(JSON.stringify(allDataArray));
            setTimeout(function () {
                total();
            }, 1000);
        });
    
        $('#dataTableExample tbody').on('click', '.editPayment', function() {
            $('input',modal).val('');
            $('select',modal).val('').trigger('change');
            window.edit = true;
            var id = $(this).data('id');
            window.index = id;
            var rowData = allDataArray[id];
    
            if (rowData) {
                $('#type').val(rowData.type).trigger('change');
                $('#mode').val(rowData.mode);
                $('#amount2').val(rowData.amount);
                $('#gross').val(rowData.gross);
                $('#purity').val(rowData.purity);
                $('#wb').val(rowData.wb);
                $('#fine').val(rowData.fine);
                $('#rate').val(rowData.rate);
                $('#bank').val(rowData.bank);
                $('#metal_type_id').val(rowData.metal_type_id);
                $('#remark').val(rowData.remark);
                $('#payment_type').val(rowData.payment);
                $('#payment-report').modal('show');
            }
            setTimeout(function () {
                total();
            }, 1000);
        });
    
        $(document).on('click', '.removePayment', function(){
            var row = $(this).closest('tr');
            var id = $(this).data('id');
            allDataArray[id] = null;
            row.remove();
            $('#paymentArray').val(JSON.stringify(allDataArray));
            setTimeout(function () {
                total();
            }, 1000);
        });
    
        function handleEditCase(id) {
            var updatedType = $('#type').val();
            var updatedMode = $('#mode').val();
            var updatedAmount = $('#amount2').val();
            var updatedGross = $('#gross').val();
            var updatedPurity = $('#purity').val();
            var updatedWb = $('#wb').val();
            var updatedFine = $('#fine').val();
            var updatedRate = $('#rate').val();
            var updatedBank = $('#bank').val();
            var updatedBankName = $('#bank option:selected').text();
            var updatedMetalTypeId = $('#metal_type_id').val();
            var updatedRemark = $('#remark').val();
            var updatedPaymentType = $('#payment_type').val();
    
            var row = $('tr[data-id="' + id + '"]');
            row.html(`
                <td>${id}</td>
                <td>${updatedType}</td>
                <td>${updatedPaymentType ?? ''}</td>
                <td class="fine">${updatedPaymentType == 'CREDIT' ? updatedFine : '-'+updatedFine}</td>
                <td class="amount">${updatedPaymentType == 'CREDIT' ? updatedAmount : '-'+updatedAmount}</td>
                <td>
                    <button class='btn btn-success editPayment' data-id="${id}"><i class='fas fa-edit'></i></button>
                    <button class='btn btn-danger removePayment' data-id="${id}"><i class='fa fa-trash' aria-hidden='true'></i></button>
                </td>
            `);
    
            allDataArray[id] = {
                type: updatedType,  
                mode: updatedMode,  
                amount: updatedAmount,
                gross: updatedGross, 
                purity: updatedPurity,
                wb: updatedWb, 
                fine: updatedFine,  
                rate: updatedRate,  
                bank: updatedBank,
                bankName: updatedBankName,
                metal_type_id: updatedMetalTypeId,
                remark: updatedRemark,
                payment: updatedPaymentType,
                saleid: allDataArray[id].saleid
            };
            
            $('#paymentArray').val(JSON.stringify(allDataArray));
            $('#payment-report').modal('hide');
            
            setTimeout(function () {
                total();
            }, 1000);
        }
    });

	
	$(document).on("keyup", ".allinone", function() {
		var type = $("#type").val();

		var gross = parseFloat($("#gross").val()) || 0;
		var purity = parseFloat($("#purity").val()) || 0;
		var wb = parseFloat($("#wb").val()) || 0;
		var fine = parseFloat($("#fine").val()) || 0;
		var rate = parseFloat($("#rate").val()) || 0;
		var amount = parseFloat($("#amount").val()) || 0;
		var amount2 = parseFloat($("#amount2").val()) || 0;
		if (type == "fine") {
			fine = gross * purity / 100;
			$("#fine").val(fine.toFixed(2));
			if (wb > 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			if (wb < 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			$("#fine").val(Math.round(fine));
		} else if (type == "ratecutfine") {
			fine = gross * purity / 100;
			$("#fine").val(fine.toFixed(2));
			if (wb > 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			if (wb < 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			if (rate > 0) {
				var amount = (fine / 1000) * rate;
				$("#amount").val(amount.toFixed(2));
			}

			$("#fine").val(Math.round(fine));
		} else if (type == "ratecutrs") {
			if (rate > 0) {
				fine = (amount2 / rate) * (purity * 10);
			} else {
				fine = 0;
			}



			if (wb > 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			if (wb < 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}



			$("#fine").val(Math.round(fine));
		} else if (type == "roopu") {
			fine = gross * purity / 100;
			if (wb > 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			if (wb < 0 && fine > 0) {
				var fineincrease = (fine * wb) / 1000;
				fine += fineincrease;
			}
			$("#fine").val(Math.round(fine));
		}
        setTimeout(function () {
            total();
        }, 1000);
	});
	
	$(document).on('change', '#party_id', function() {
		var select = $(this);
		var customer_id = select.val();
		$.ajax({
			url: `${BaseUrl}report/account_ledger/customerAmtAndFine_CR_DB/${customer_id}`,
			type: "GET",
			success: function(data) {
				try {
					var data = JSON.parse(data);
					var fine = data.fine;
					var amount = data.amount;
                    $('.fineClosing').html(fine);
                    $('.amountClosing').html(amount);
                    $('#closing_fine').val(fine);
				    $('#closing_amount').val(amount);
                    var color = "";
					if (fine < 0) {
						fineStr = 'Dr: ' + Math.abs(fine);
						colorF = "<span style='color: red;'>" + fineStr + "</span>";
					} else {
						fineStr = 'Cr: ' + fine;
						colorF = "<span style='color: green;'>" + fineStr + "</span>";
					}

					if (amount < 0) {
						amountStr = 'Dr: ' + Math.abs(amount);
						colorA = "<span style='color: read;'>" + amountStr + "</span>";
					} else {
						amountStr = 'Cr: ' + amount;
						colorA = "<span style='color: green;'>" + amountStr + "</span>";
					}
                    
					$('#closing-label').html('Fine ' + colorF + '</span> &amp; Amt ' + colorA);
					
				} catch (e) {
					SweetAlert('error',e);
				}
			}
		});
		setTimeout(function () {
            total();
        }, 1000);
	});
	
	function total() {
        var TotalFine = parseFloat($('.card-footer .TotalFine').text().trim()) || 0;
        var Sub_total = parseFloat($('.card-footer .Sub_total').text().trim()) || 0;
        var fineClosing = parseFloat($('.card-footer .fineClosing').text().trim()) || 0;
        var amountClosing = parseFloat($('.card-footer .amountClosing').text().trim()) || 0;
    
        var fine = fineClosing - TotalFine;
        var amount = amountClosing - Sub_total;
        
        var TotalAmounts = amount;
        var TotalFines = fine;
    
        $('.amount').each(function () {
            var value = parseFloat($(this).text().trim()) || 0;
            TotalAmounts += value;
        });
    
        $('.fine').each(function () {
            var value = parseFloat($(this).text().trim()) || 0;
            TotalFines += value;
        });
        
        $('.finalFine').text(TotalFines.toFixed(2));
        $('.finalAmount').text(TotalAmounts.toFixed(2));
        $('#total_fine').val(TotalFines.toFixed(2));
        $('#total_amount').val(TotalAmounts.toFixed(2));
    }
    
    function scrollToBottom() {
        var $tableContainer = $('.table-responsive');
        $tableContainer.animate({
            scrollTop: $tableContainer.prop("scrollHeight")
        }, 1000);
    }
</script>
