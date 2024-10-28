<style>
	.silver-input {
		background-color: #ebebeb;
		color: black;
	}

	.weight-input {
		background-color: #e6fdff;
	}

	/*.select2 {*/
	/*	width: 140px !important;*/
	/*}*/

	/* .inputBox {
		width: 70px !important;
	} */

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
		background: #333333bf;
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
</style>
<div class="row element">
	<div class="col-sm-12">
		<div class="card">
			<form action="<?= (isset($data)) ? base_url("manufacturing/ready_for_sale/update/{$data['id']}") : base_url('manufacturing/ready_for_sale/store') ?>" method="post" class="main-form" novalidate>
				<div class="card-header">
					<div class="card-status-top bg-blue"></div>
					<h1 class="card-title"><b> Ready For Sale </b></h1>
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
							<!-- <div class="col-md-2">
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
								</div>
							</div> -->
							<div class="col-md-2">
								<div class="form-group">
								    <label class="form-label">Tag</label>
									<input type="number" id="tag" class="form-control tag" placeholder="Right-click to paste (use Ctrl+V)" autocomplete="off">
								</div>
							</div>

							<div class="card mt-5">
								<div class="row">
									<div class="table-responsive">
										<table class="table card-table table-vcenter  table-scroll text-center text-nowrap">
											<thead>
												<tr>
													<th>Item</th>
													<th>Sub Item</th>
													<th>Stamp</th>
													<th>Touch</th>
													<th>Pcs</th>
													<th>Gross</th>
													<th>Less</th>
													<th>Net</th>
													<th>Remarks</th>
													<th></th>
												</tr>
											</thead>

											<tbody class="paste append-here">
												<?php
												if (empty($data['sale_detail'])) {
													$data['sale_detail'][] = [
														'item'               => 0,
														'sub_item_id'        => 0,
														'stamp'              => 0,
														'touch'              => 0,
														'piece'       		 => 0,
														'gross_weight'       => 0,
														'less_weight'        => 0,
														'net_weight'         => 0,
														'remark'             => '',
														'lot_creation_tag'   => '',
														'id'          		 => 0
													];
												}
												foreach ($data['sale_detail'] as $row) { ?>
													<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? null; ?>" />
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
														<td>
															<select class="form-select select2 sub_item" required name="sub_item[]">
															</select>
														</td>
														<td><select name="stamp[]" id="" class="form-control w65 select2 stamp">
																<option value="">Select Stamp</option>
																<?php foreach ($stamp as $s) { ?>
																	<option value="<?= $s['id']; ?>" <?php if (isset($row['stamp_id']) && $s['id'] == $row['stamp_id']) {
																											echo 'selected';
																										} ?>> <?= $s['name']; ?> </option>
																<?php } ?>
															</select></td>
															<td><input type="text" class="form-control inputBox touchData" name="touch[]" placeholder="Touch" value="<?= $row['touch'] ?? null ?>"></td>
															<td><input type="text" class="form-control inputBox piece" name="piece[]" placeholder="Piece" value="<?= $row['piece'] ?? 0 ?>"></td>
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
														<td><input type="text" class="form-control remark inputBox" name="remark[]" placeholder="Remark" value="<?= $row['remark'] ?? null ?>"></td>
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
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalTouchData'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalPiece'>0</span></h4>
														</div>
													</td>
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
													<td></td>
												</tr>
												<tr>
													<td colspan="1" class="d-flex border border-0 align-content-start flex-wrap">
														<button type="button" class="btn btn-success " id="add">
															Add row <i class="ms-2 fa-solid fa-plus"></i>
														</button>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary ms-auto">Submit</button>
				</div>
			</form>
		</div>
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


<?php $time = time() ?>
<script class="javascript" src="<?= base_url("assets") ?>/dist/js/ready_for_sale.js?v=<?= $time ?>"></script>
