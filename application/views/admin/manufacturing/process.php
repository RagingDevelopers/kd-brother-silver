<style>
	.readonly {
		background-color: #ebebeb;
		color: black;
	}

	.receivedWeight {
		background-color: #e9f7ff;
		color: black;
	}

	tr .given {
		background-color: #ffe1e1;
		color: black;
	}

	tr .totalSum {
		background-color: #3a4859;
		color: white;
	}

	tr .diffrence {
		background-color: #3a4859;
		color: white;
	}

	tr .received {
		background-color: #d9ffd9;
		color: black;
	}

	.table td {
		font-weight: bold;
	}

	.modal-xxl {
		max-width: 95%;
	}

	.modal-xxl-content {
		width: 100%;
	}

	.modal-lgg {
		max-width: 90%;
	}

	.modal-lgg-content {
		width: 100%;
	}

	.modal-rm-xl {
		max-width: 70%;
	}

	.modal-rm-content {
		width: 100%;
	}

	.lot_wise_rm_id {
		width: 20%;
	}

	td {
		white-space: nowrap;
		padding: 9px !important;
		align-items: center;
	}

	.AverageTouch {
		color: red;
		background-color: #3a4859;
	}

	/*.blinking-text {*/
	/*       animation: blink 4s linear infinite;*/
	/*   }*/
</style>
<?php $time = time() ?>
<div class="row">
	<div class="col-md-7 pb-3">
		<div class="card">
			<div class="row">
				<div class="col-md-12">
					<form action="<?= (isset($process_data)) ? base_url('manufacturing/process/update') : base_url('manufacturing/process/add') ?>" method="post" class="ManageProcess">
						<div class="card-header">
							<div class="card-status-top bg-blue"></div>
							<h1 class="card-title"><b> Process Manage ( <?= $id; ?> ) </b></h1>

						</div>
						<div class="card-body border-bottom py-3">
							<div class="row mt-1">
								<input type="hidden" name="garnu_id" class="form-control garnu_id" value="<?= $data['id'] ?? null ?>">
								<input type="hidden" name="given_id" class="form-control given_id" value="<?= $process_data['id'] ?? null ?>">
								<input type="hidden" id="gatnuTouch" value="<?= $process_data['given_touch'] ?? 0 ?>">
								<input class="form-control" type="hidden" name="action" id="save_print">

								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Garnu Name: </label>
									<input type="text" name="name" class="form-control readonly" placeholder="Enter Garnu Name" readonly value="<?= $data['name'] ?? null ?>" autocomplete="off">
								</div>
								<!-- <div class="col-md-4 col-md-3">
									<label class="form-label" for="">Received Quantity: </label>
									<input type="text" name="rc_qty"  class="form-control" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['rc_qty'] : '' ?>">
								</div> -->
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Garnu Weight: </label>
									<input type="text" name="weight" step="any" class="form-control readonly" readonly placeholder="Enter Weight" value="<?= $data['garnu_weight'] ?? null ?>" autocomplete="off">
									<input type="hidden" id="receiveCode" value="<?= $receiveCode[0]['code'] ?? null ?>" autocomplete="off">
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Garnu Touch: </label>
									<input type="text" name="touch" step="any" class="form-control readonly" readonly placeholder="Enter Touch" step="any" value="<?= $data['touch'] ?? null ?>" autocomplete="off">
								</div>
								<!-- <div class="col-md-4 col-md-3 <?php // echo (empty($receiveCode[0]['code']) || !empty($process_data) && empty($process_data['receive_code'])) ? "d-none" : " "; 
																	?>"> -->
								<div class="col-md-4 col-md-3 d-none">
									<label class="form-label" for="process">Receive Code : </label>
									<select class="form-select select2 receiveCode" name="receive_code">
										<option value=''>Select Code</option>
										<?php
										foreach ($receiveCode as $value) {
										?>
											<option value="<?= $value['code'] ?? null ?>" <?php if (isset($process_data) && ($value['code'] == $process_data['receive_code'])) {
													echo 'selected';
												} ?>>
												<?= $value['code']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-md-3 mb-2">
									<label class="form-label" for="process">Process: </label>
									<select class="form-select select2 process" name="process">
										<option value=''>Select Process</option>
										<?php
										foreach ($process as $value) {
										?>
											<option value="<?= $value->id ?? null ?>" data-autofilltouch="<?= $value->autofill_given_touch; ?>" data-workerId="<?= $process_data['worker_id'] ?? 0 ?>" <?php if (isset($process_data) && ($value->id == $process_data['process_id'])) {
													echo 'selected';
												} ?>>
												<?= $value->name; ?>
											</option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-4 col-md-3 material-type mb-2">
									<label class="form-label" for="meterial_type_id">Material Type: </label>
									<select name="material_type_id" class="form-select select2 col-md-2 col-sm-3" id="material-type">
										<option value=''>Select Material Type</option>
										<?php
										if (!empty($row_material)) {
											foreach ($row_material as $row) { ?>
												<option value="<?= $row['id']; ?>" <?php if (isset($process_data) && ($row['id'] == $process_data['material_type_id'])) {
													echo 'selected';
												} ?>> <?= $row['name']; ?> </option>
										<?php }
										} ?>
									</select>
								</div>
								<div class="col-md-4 col-md-3 material-type">
									<label class="form-label" for="closingTouch">Closing Touch: </label>
									<select class="form-select select2 closingTouch" name="closing_touch"></select>
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="workers">Workers: </label>
									<select class="form-select select2 workers" name="workers" id="workers">
										<option value="">Select Worker:</option>
									</select>
								</div>
								<div class="col-md-4 col-md-3 d-none">
									<label class="form-label" for="item">Item: </label>
									<select class="form-select select2 item" name="item_id">
										<option value=''>Select Item</option>
										<?php
										foreach ($item as $val) {
										?>
											<option value="<?= $val['id'] ?? 0 ?>" <?php if (isset($process_data) && ($val['id'] == $process_data['item_id'])) {
													echo 'selected';
												} ?>>
												<?= $val['name']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Given Quantity: </label>
									<input type="text" name="given_qty" class="form-control given-qty" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_qty'] : "0" ?>">
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Given Weight: </label>
									<input type="text" name="given_weight" step="any" class="form-control given_weight" placeholder="Enter Weight" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_weight'] : '0' ?>">
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Given Touch: </label>
									<input type="text" name="given_touch" step="any" class="form-control given_touch" placeholder="Enter Touch" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_touch'] : '0' ?>">
								</div>
								<!-- <div class="col-md-4 col-md-3">
									<label class="form-label" for="">Labour: </label>
									<input type="text" name="labour"  class="form-control labour" placeholder="Enter Labour" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['labour'] : 0 ?>">
								</div> -->
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Row Material Weight: </label>
									<input type="text" name="total-rm_weight" step="any" class="form-control totalRmWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-3 col-md-2">
									<label class="form-label" for="">Final Weight: </label>
									<input type="text" name="total_weight" step="any" class="form-control finalWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-md-3?>">
									<label class="form-label" for="">Remark: </label>
									<input type="text" name="remarks" class="form-control" placeholder="Enter Remark" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['remarks'] : '' ?>">
									<input type="hidden" name="receive_id" class="form-control receiveId" placeholder="Enter Remark" autocomplete="off">
								</div>
								<div class="col-md-1 col-md-1">
									<label class="form-label" for="">&nbsp </label>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-bs-toggle="modal" data-bs-target="#modal-report"><i class="fa fa-usb" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="action" value="save" class="btn btn-primary ms-auto" onclick="setActionType('save')"><?= (isset($process_data)) ? 'Update' : 'Submit' ?></button>
							<button type="submit" name="action" value="save_and_print" class="btn btn-success ms-auto" onclick="setActionType('save_and_print')"><?= (isset($process_data)) ? 'Update' : 'Submit' ?> & Print</button>

							<!-- <a target="_blank" class="bg-warning btn text-warning-fg me-2">Submit & Print</a> -->
						</div>

						<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-scrollable modal-lgg" role="document">
								<div class="modal-content modal-lgg-content">
									<div class="modal-header">
										<h5 class="modal-title">Add Row Material</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="table-responsive">
											<table class="table table-vcenter card-table table-striped rowMaterial">
												<thead>
													<tr>
														<th>Row Material</th>
														<th>Lot Wise RM</th>
														<th>Touch %</th>
														<th>Weight</th>
														<th>Quantity</th>
														<th></th>
													</tr>
												</thead>
												<tbody id="TBody">
													<?php
													if (empty($given_row_material)) {
														$given_row_material[] = [
															'row_material_id' => 0,
															'lot_wise_rm_id'  => 0,
															'rmWeight'        => 0,
															'rmTouch'         => 0,
															'rmQuantity'      => 0,
															'id'              => 0
														];
													}
													// pre($given_row_material);exit;
													foreach ($given_row_material as $row) { ?>
														<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
														<tr class="mainRow">
															<td>
																<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? "0"; ?>" />
																<select class="form-select select2 row_material rowMateralWiseLot" data-lot_wise_rm_id="<?php if (isset($row) && $row['lot_wise_rm_id']) {
																																							echo $row['lot_wise_rm_id'];
																																						} ?>" name="row_material[]">
																	<option value="">Select RM</option>
																	<?php
																	if (!empty($row_material)) {
																		for ($i = 0; $i < count($row_material); $i++) { ?>
																			<option value="<?= $row_material[$i]['id']; ?>" <?php if (isset($row) && $row_material[$i]['id'] == $row['row_material_id']) {
																																echo 'selected';
																															} ?>><?= $row_material[$i]['name']; ?>
																			</option>
																	<?php }
																	} ?>
																</select>
															</td>
															<td>
																<select class="form-select select2 lot_wise_rm_id" name="lot_wise_rm_id[]">
																</select>
															</td>
															<td class="text-muted">
																<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? 0 ?>" step="any" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
															</td>
															<td class="text-muted">
																<input type="number" name="rmWeight[]" step="any" value="<?= $row['weight'] ?? 0 ?>" class="form-control weight" placeholder="Enter Weight" autocomplete="off">
															</td>
															<td class="text-muted">
																<input type="number" name="rmQuantity[]" value="<?= $row['quantity'] ?? 0 ?>" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
															</td>
															<input type="hidden" value="" class="form-control fineTotal" placeholder="Enter Quantity" autocomplete="off">
															<td>
																<button type="button" class="btn btn-danger deleteRow">X</button>
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
														</td>
														<td>
															<div class="d-flex">
																<h4><span class='text-end ms-3 total-touch'>0</span></h4>
															</div>
														</td>
														<td>
															<div class="d-flex">
																<h4><span class='text-end ms-3 total-weight'>0</span></h4>
															</div>
														</td>
														<td>
															<div class="d-flex">
																<h4><span class='text-end ms-3 total-qty'>0</span></h4>
															</div>
														</td>
														<td></td>
													</tr>
													<tr>
														<td>
														</td>
														<td>
															<h3>Garnu Touch (%):</h3>
														</td>
														<td>
															<div class="d-flex">
																<h4><span class='text-end ms-3 garnuTouch'>0</span></h4>
															</div>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
										<div class="modal-footer justify-content-between">
											<button type="button" class="btn btn-success btn-success addButton">
												<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
											</button>
											<div>
												<button type="button" class="btn btn-danger btn-danger close" data-bs-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary btn-primary save">Save Changes</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

					<form action="#" id="received-garnu">
						<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-xxl" role="document">
								<div class="modal-content modal-xxl-content">
									<div id="receveData"></div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-success btn-success receivedAddButton2">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<div>
											<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
											<button type="submit" class="input-icon btn btn-primary btn-primary submit-btn">Save Changes
												<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

					<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
							<div class="modal-content modal-rm-content">
								<div class="modal-header">
									<h5 class="modal-title">Received Row Material</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12 text-center">
											<h3> Given Row Material</h3>
										</div>
										<div class="col-md-12">
											<div class="row-material-section">

											</div>
										</div>
									</div>
									<div class="table-responsive">
										<table class="table table-vcenter card-table table-striped receivedRmTable">
											<thead>
												<tr>
													<th>Row Material</th>
													<th>Lot Wise RM</th>
													<th>Touch %</th>
													<th>Weight</th>
													<th>Pcs</th>
													<th class="hide_labour">Labour</th>
													<th class="hide_labour">Labour</th>
													<th class="hide_labour">Total Labour</th>
													<th></th>
												</tr>
											</thead>
											<tbody id="JBody">
												<?php
												if (empty($given_row_materials)) {
													$given_row_materials[] = [
														'row_material_id' => 0,
														'lot_wise_rm_id'  => 0,
														'rmWeight'        => 0,
														'rmTouch'         => 0,
														'rmQuantity'      => 0,
														'labour_type'     => "",
														'labour'          => 0,
														'total_labour'    => 0,
														'id'              => 0
													];
												}
												foreach ($given_row_materials as $row) { ?>
													<input type="hidden" class="ids" value="0" />
													<tr class="mainRow2 main-row">
														<td>
															<input type="hidden" class="received_detail_id" />
															<select class="form-select select2 row_material2 rowMateralWiseLot2" data-given_id="<?= $process_data['id'] ?? null ?>" data-garnu_id="<?= $data['id'] ?? null ?>" data-lot_wise_rm_id="<?php if (isset($row) && $row['lot_wise_rm_id']) {
																																																														echo $row['lot_wise_rm_id'];
																																																													} ?>">
																<option value="">Select RM</option>
																<?php
																if (!empty($row_material)) {
																	foreach ($row_material as $rm) { ?>
																		<option value="<?= $rm['id']; ?>" <?php if (isset($row) && $rm['id'] == $row['row_material_id']) {
																												echo 'selected';
																											} ?>><?= $rm['name']; ?>
																		</option>
																<?php }
																} ?>
															</select>
														</td>
														<td>
															<select class="form-select select2 lot_wise_rm_id2">
																<option value="">Select LW RM</option>
															</select>
														</td>
														<td class="text-muted">
															<input type="number" class="form-control touch2" value="0" placeholder="Enter Touch" step="any" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" class="form-control weight2" value="0" placeholder="Enter Weight" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" class="form-control quantity2" value="0" placeholder="Enter Quantity" autocomplete="off">
														</td>
														<td class="text-muted hide_labour">
															<select class="form-select select2 labour_type defualt_labour_type" name="labour_type">
																<option value="">Select Labour</option>
																<option value="PCS">PCS</option>
																<option value="WEIGHT">WEIGHT</option>
															</select>
														</td>
														<td class="text-muted hide_labour">
															<input type="number" class="form-control labour" value="0" placeholder="Enter Labour" autocomplete="off">
														</td>
														<td class="text-muted hide_labour">
															<input type="number" class="form-control total_labour readonly" value="0" readonly placeholder="Total Labour" autocomplete="off">
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
													</td>
													<td>
														<h3>Total :</h3>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 total-touch'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 total-weight'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 total-qty'>0</span></h4>
														</div>
													</td>
													<td></td>
													<td class="hide_labour">
														<div class="d-flex">
															<h4><span class='text-end ms-3 total-labour'>0</span></h4>
														</div>
													</td>
													<td class="hide_labour">
														<div class="d-flex">
															<h4><span class='text-end ms-3 final-labour'>0</span></h4>
														</div>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-success btn-success addButton2">
										<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
									</button>
									<div>
										<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary btn-primary saveRmData">Save Changes</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="metalType-report" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-scrollable" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Received Metal Type</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table table-vcenter card-table table-striped">
											<thead>
												<tr>
													<th>Metal Type</th>
													<th>Touch %</th>
													<th>Weight</th>
													<th>Quantity</th>
													<th></th>
												</tr>
											</thead>
											<tbody id="MetalBody">
												<?php
												if (empty($metalData)) {
													$metalData[] = [
														'metal_type_id' => 0,
														'rmWeight'        => 0,
														'rmTouch'         => 0,
														'rmQuantity'      => 0,
														'id'              => 0
													];
												}
												foreach ($metalData as $row) { ?>
													<!-- <input type="hidden" class="ids" value="0" /> -->
													<tr class="metalRow">
														<td>
															<input type="hidden" class="process_metal_type" value="0" />
															<select class="form-select select2 metal_type">
																<option value="">Select Metal Type</option>
																<?php
																if (!empty($row_material)) {
																	foreach ($row_material as $mt) { ?>
																		<option value="<?= $mt['id']; ?>" <?php if (isset($row) && $mt['id'] == $row['metal_type_id']) {
																												echo 'selected';
																											} ?>><?= $mt['name']; ?>
																		</option>
																<?php }
																} ?>
															</select>
														</td>
														<td class="text-muted">
															<input type="number" class="form-control metalTouch" step="any" value="0" placeholder="Enter Touch" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" class="form-control metalWeight" value="0" placeholder="Enter Weight" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" class="form-control metalQuantity" value="0" placeholder="Enter Quantity" autocomplete="off">
														</td>
														<td>
															<button type="button" class="btn btn-danger metalDeleteRow">X</button>
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
															<h4><span class='text-end ms-3 metal-total-touch'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 metal-total-weight'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 metal-total-qty'>0</span></h4>
														</div>
													</td>
													<td></td>
												</tr>
											</tfoot>
										</table>
									</div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-success btn-success metalAddButton">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<div>
											<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary btn-primary saveMetalData">Save Changes</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5 row">
		<div class="accordion" id="accordion-example">
			<div class="col-md-12 pb-3">
				<div class="card">
					<button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">
						<h1 class="accordion-button collapsed card-title text-center mb-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false">
							<b>Pending issue</b>
						</h1>
					</button>

					<div id="collapse-1" class="accordion-collapse collapse show" data-bs-parent="#accordion-example">
						<div class="accordion-body pt-0">
							<div class="table-responsive">
								<table class="table w-100" id="receiveTable">
									<thead>
										<tr>
											<th scope="col">Id</th>
											<th scope="col">Item</th>
											<th scope="col">Process</th>
											<th scope="col">Touch</th>
											<th scope="col">Weight</th>
											<th scope="col">Rm Weight</th>
											<th scope="col">Total Weight</th>
											<th scope="col">Quantity</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item col-md-12 mb-3">
				<div class="card">
					<button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="true">
						<h1 class="accordion-button collapsed card-title text-center mb-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false">
							<b class="">Row Materials</b>
						</h1>
					</button>
					<div id="collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
						<div class="accordion-body pt-0">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table w-100" id="row_materialTable">
										<thead>
											<tr>
												<th scope="col">Id</th>
												<th scope="col">Row Material</th>
												<th scope="col">Process</th>
												<th scope="col">Type</th>
												<th scope="col">Touch</th>
												<th scope="col">Weight</th>
												<th scope="col">Quantity</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="card">
			<div class="row">
				<div class="col-md-12">
					<div class="card-header">
						<div class="card-status-top bg-blue"></div>
						<h1 class="card-title"><b> Process Report </b></h1>
					</div>

					<div class="card-body border-bottom py-3">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th scope="col">Id</th>
										<th scope="col">Action</th>
										<th scope="col">Given Date</th>
										<th scope="col">Process Name</th>
										<th scope="col">Worker Name</th>
										<th scope="col">Given Touch</th>
										<th scope="col">Given Quantity</th>
										<th scope="col">Given Weight</th>
										<th scope="col">Row Material Weight</th>
										<th scope="col">Total Weight</th>
										<th scope="col">Vadharo Ghatado</th>
										<th scope="col">Received Pcs</th>
										<th scope="col">Received Weight</th>
										<th scope="col">Received RM Weight</th>
										<th scope="col">Received Final Weight</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$totalTouch = 0;
									$totalGivenPcs = 0;
									$totalGivenweight = 0;
									$totalGivenRMweight = 0;
									$finalGivenWeight = 0;
									$totalReceivePcs = 0;
									$totalReceiveweight = 0;
									$totalReceiveRMweight = 0;
									$finalReceiveWeight = 0;
									$vadharoGhatado = 0;

									// pre($table);exit;
									foreach ($table as $index => $result) {
										$receive = $this->db->select('id,pcs,weight,row_material_weight,total_weight')->get_where('receive', array('given_id' => $result->id))->result_array();
										$totalTouch += $result->given_touch;
										$totalGivenPcs += $result->given_qty;
										$totalGivenweight += $result->given_weight;
										$totalGivenRMweight += $result->row_material_weight;
										$finalGivenWeight += $result->total_weight;

										$totalPcs = 0;
										$totalweight = 0;
										$totalRMweight = 0;
										$finalWeight = 0;
										foreach ($receive as $key => $rm) {

											$totalReceivePcs += $rm['pcs'];
											$totalReceiveweight += $rm['weight'];
											$totalReceiveRMweight += $rm['row_material_weight'];
											$finalReceiveWeight += $rm['total_weight'];

											$totalPcs += $rm['pcs'];
											$totalweight += $rm['weight'];
											$totalRMweight += $rm['row_material_weight'];
											$finalWeight += $rm['total_weight'];
										}
									?>
										<tr>
											<td class="given">
												<?= $index + 1; ?>
											</td>
											<td class="given">
												<div class="d-flex gap-2">
													<a class="btn btn-action bg-success text-white me-2 edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit" href="<?= base_url('manufacturing/process/manage/') . $id . '/' . $result->id; ?>">
														<i class="far fa-edit" aria-hidden="true"></i>
													</a>
													<a class="bg-warning btn btn-action text-warning-fg me-2 Received" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-original-title="Received" href="#">
														<i class="fa-brands fa-connectdevelop"></i>
													</a>
													<a target="_blank" class="bg-primary btn btn-action text-warning-fg me-2 given_print" href="<?= base_url('manufacturing/process/given_print/') . $id . '/' . $result->id; ?>"><i class="fa fa-print"></i></a>
													<a class="btn btn-action text-warning-fg me-2 showDiffrence" style="background-color:#182433;" type="button" data-show_or_not="<?= $result->show_or_not; ?>" data-process_id="<?= $result->process_id; ?>" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-toggle="modal" data-target="#showDiffrence" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Show Row Material Diffrence" href="#">
														<i class="fa fa-random" aria-hidden="true"></i>
													</a>
													<a target="_blank" class="bg-danger btn btn-action text-warning-fg me-2 printButton" href="<?= base_url('manufacturing/process/printThermal/') . $id . '/' . $result->id; ?>"><i class="fa fa-print"></i></a>
													<input class="form-check-input mt-2 is_completed" disabled <?php if (isset($result->is_completed) && $result->is_completed == "YES") {
																													echo 'checked';
																												} ?> type="checkbox" style="transform: scale(1.2);">
													<?php
													$metalData = $this->dbh->getWhereResultArray('process_metal_type', ['given_id' => $result->id]);
													$rm_string_array = [];
													foreach ($metalData as $rm) {
														$rm_string_array[] = implode(',', [
															$rm['metal_type_id'],
															$rm['touch'],
															$rm['weight'],
															$rm['quantity'],
															$rm['id']
														]);
													}
													$row['metal_string'] = implode('|', $rm_string_array ?? []) ?? ""; ?>
													<input type="hidden" value="<?= $row['metal_string'] ?? null; ?>" class="form-control rowMetalData" autocomplete="off">
													<button type="button" class="btn btn-action text-white bg-primary total-metal-type" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Used Metal Type"><i class="fa-solid fa-info"></i></button>
												</div>
											</td>
											<td class="given">
												<?= $result->creation_date; ?>
											</td>
											<td class="given">
												<?= $result->process_name; ?>
											</td>
											<td class="given">
												<?= $result->customer_name; ?>
											</td>
											<td class="given">
												<?= $result->given_touch; ?>
											</td>
											<td class="given">
												<?= $result->given_qty; ?>
											</td>
											<td class="given">
												<?= $result->given_weight; ?>
											</td>
											<td class="given">
												<div class="d-flex gap-3">
													<button type="button" class="btn btn-action text-white bg-primary given-row-material" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Given Row Material"><i class="fa-solid fa-info"></i></button>
													<?= $result->row_material_weight; ?>
												</div>
											</td>
											<td class="given">
												<?= $result->total_weight; ?>
											</td>
											<td class="received vadharo_dhatado">
												<?php
												if ($result->vadharo_dhatado > 0) {
													$vadharoGhatado += $result->vadharo_dhatado;
												?>
													<h4 class="text-danger">ધટાડો : <?= $result->vadharo_dhatado; ?></h4>
												<?php } else {
													$vadharoGhatado -= abs($result->vadharo_dhatado || 0);
												?>
													<h4 class="text-success">વધારો : <?= $result->vadharo_dhatado; ?></h4>
												<?php } ?>
											</td>
											<td class="received totalPcs"><?= $totalPcs; ?></td>
											<td class="received totalWeight"><?= $totalweight; ?></td>
											<td class="received">
												<div class="d-flex gap-3">
													<button type="button" class="btn btn-action text-white bg-primary receive-row-material" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Receive Row Material"><i class="fa-solid fa-info"></i></button>
													<span class="rowMaterialWeight"><?= $totalRMweight; ?></span>
												</div>
											</td>
											<td class="received ">
												<span class="totalFinalWeight"><?= $finalWeight; ?></span>
											</td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td class="text-center text-primary" colspan="6">
											<h3 class="blinking-text">Totals ==></h3>
										</td>
										<td><?= $totalGivenPcs ?></td>
										<td><?= $totalGivenweight ?></td>
										<td><?= $totalGivenRMweight ?></td>
										<td><?= $finalGivenWeight ?></td>
										<td>
											<?php
											if ($vadharoGhatado > 0) { ?>
												<h4 class="text-danger pt-3">ધટાડો : <?= $vadharoGhatado; ?></h4>
											<?php } else { ?>
												<h4 class="text-success pt-3">વધારો : <?= $vadharoGhatado; ?></h4>
											<?php } ?>
										</td>
										<td><?= $totalReceivePcs ?></td>
										<td><?= $totalReceiveweight ?></td>
										<td><?= $totalReceiveRMweight ?></td>
										<td><?= $finalReceiveWeight ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal modal-blur fade" id="givenRowMaterial" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Issue Weight </p>
				</div>
				<div class="col-md-4">
					<p class="modal-title">Garnu Weight:- <span class="garnu_weight"></span></p>
				</div>
				<div class="col-md-4 text-center">
					<p class="modal-title">Garnu Name:- <span class="garnu_name"></span></p>
				</div>
				<!-- <div class="col-md-1"> -->
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<!-- </div> -->
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table card-table table-vcenter text-center text-nowrap ">
						<thead class="thead-light">
							<th>Row Material</th>
							<th scope="col">Touch (%)</th>
							<th scope="col">Weight(Gm)</th>
							<th scope="col">Quantity</th>
						</thead>

						<tbody class="paste append-here">
							<tr class="sectiontocopy">
								<td>
									<select class="form-select select2 given-row_material_id" disabled readonly>
										<option value="">Select Metal</option>
										<?php
										$row_material = $this->db->get('row_material')->result();
										foreach ($row_material as $value) { ?>
											<option value="<?= $value->id; ?>">
												<?= $value->name; ?>
											</option>
										<?php } ?>
									</select>
								</td>

								<td>
									<input class="form-control given-touch" type="number" step="any" disabled readonly placeholder="Enter touch(%)" value="0">
								</td>
								<td>
									<input class="form-control given-weight" type="number" disabled readonly placeholder="Enter Weight" value="0">
								</td>
								<td>
									<input class="form-control given-quantity" type="number" value="0" disabled readonly>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td>
									<h3>Total :</h3>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 given-total-touch'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 given-total-weight'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 given-total-quantity'>0</span></h4>
									</div>
								</td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="modal-footer justify-content-between">
					<span></span>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="showDiffrence" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lgg" role="document">
		<div class="modal-content modal-lgg-content">
			<div class="modal-header">
				<h5 class="modal-title">Given & Received Row Material Diffrence</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-vcenter card-table table-striped rowMaterial">
						<thead>
							<tr>
								<th>Row Material</th>
								<th>Lot wise RM</th>
								<th>Touch %</th>
								<th>Weight</th>
								<th>Quantity</th>
								<th>Row Material</th>
								<th>Lot wise RM</th>
								<th>Touch %</th>
								<th>Weight</th>
								<th>Quantity</th>
								<!--<th>Touch Diff</th>-->
								<th>Weight Diff</th>
								<th>Quantity Diff</th>
								<th>Fine Diff</th>
							</tr>
						</thead>
						<tbody id="diffTBody">
							<tr class="diffmainRow">
								<td class="given givenRmDiff"></td>
								<td class="given givenLotRmDiff"></td>
								<td class="given givenTouchDiff"></td>
								<td class="given givenWeightDiff"></td>
								<td class="given givenQtyDiff"></td>
								<td class="received receiveRmDiff"></td>
								<td class="received receiveLotRmDiff"></td>
								<td class="received receiveTouchDiff"></td>
								<td class="received receiveWeightDiff"></td>
								<td class="received receiveQtyDiff"></td>
								<!--<td class="diffrence TouchDiff"></td>-->
								<td class="diffrence WeightDiff"></td>
								<td class="diffrence QtyDiff"></td>
								<td class="diffrence FineDiff"></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class=""> Total =></td>
								<td></td>
								<td class="givenTotaltouchDiff"></td>
								<td class="givenTotalweightDiff "></td>
								<td class="givenTotalqtyDiff "></td>
								<td class=""></td>
								<td class=""></td>
								<td class="receiveTotaltouchDiff"></td>
								<td class="receiveTotalweightDiff"></td>
								<td class="receiveTotalqtyDiff"></td>
								<td class="TotalWeightDiff"></td>
								<td colspan="1"></td>
								<td class="TotalFineDiff"></td>
							</tr>
							<tr>
								<td colspan='2' class="diffrence"> Total Diffrence =></td>
								<!--<td colspan='2' class="diffrence TotaltouchDiff"></td>-->
								<td colspan='1' class="diffrence TotalweightDiff"></td>
								<td colspan='1' class="diffrence TotalqtyDiff"></td>
								<td colspan='2' class="AverageTouch diffrence" style="color:red;"></td>
								<td colspan='3' class="diffrence">
									<button type="button" class="btn btn-primary btn-primary update_to_after_all" data-garnu_id="" data-given_id="">Update to After All</button>
								</td>
								<td class="diffrence"></td>
								<td class="diffrence"></td>
								<td class="diffrence"></td>
								<td class="diffrence"></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="modal-footer justify-content-between">
					<div></div>
					<div>
						<button type="button" class="btn btn-danger btn-danger close" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<button id="connect">Connect to Printer</button>-->
<!--<button id="print">Print</button>-->

<!--<script>-->
<!--  let printerDevice;-->
<!--  let gattServer;-->
<!--  let characteristic;-->

<!--  document.getElementById('connect').addEventListener('click', async () => {-->
<!--    try {-->
<!--      printerDevice = await navigator.bluetooth.requestDevice({-->
<!--        filters: [{ name: 'RichPos 80 BT' }],-->
<!--        optionalServices: [-->
<!--          BluetoothUUID.canonicalUUID("0x110A")-->
<!--        ]-->
<!--      });-->

<!--      gattServer = await printerDevice.gatt.connect();-->
<!--      printerDevice.addEventListener('gattserverdisconnected', onDisconnected);-->
<!--      const serviceUUID = BluetoothUUID.canonicalUUID("0x110A");-->
<!--      const service = await gattServer.getPrimaryService(serviceUUID);-->

<!--// Replace 'apparent_wind_direction' with the correct characteristic UUID for your printer-->
<!--      const characteristicUUID = BluetoothUUID.getCharacteristic("apparent_wind_direction");-->
<!--      characteristic = await service.getCharacteristic(characteristicUUID);-->

<!--      SweetAlert('success', 'Connected to printer');-->
<!--    } catch (error) {-->
<!--      SweetAlert('warning', `Connection failed: ${error}`);-->
<!--    }-->
<!--  });-->

<!--  document.getElementById('print').addEventListener('click', async () => {-->
<!--    if (!printerDevice) {-->
<!--      SweetAlert('warning', 'Please connect to a printer first.');-->
<!--      return;-->
<!--    }-->

<!--    try {-->
<!--      if (!gattServer.connected) {-->
<!--        gattServer = await printerDevice.gatt.connect();-->
<!--      }-->

<!--   if (!characteristic) {-->
<!--        const serviceUUID = BluetoothUUID.canonicalUUID("0x110A");-->
<!--        const service = await gattServer.getPrimaryService(serviceUUID);-->
<!--        const characteristicUUID = BluetoothUUID.getCharacteristic("apparent_wind_direction");-->
<!--        characteristic = await service.getCharacteristic(characteristicUUID);-->
<!--      }-->

<!--      const encoder = new TextEncoder();-->
<!--const printData = encoder.encode('\x1B\x40Hello, world!\n\x0A\x1D\x56\x42\x00'); // ESC/POS commands to print text-->
<!--      await characteristic.writeValue(printData);-->

<!--      SweetAlert('success', 'Print command sent');-->
<!--      console.log('Print command sent');-->
<!--    } catch (error) {-->
<!--      SweetAlert('warning', `Print failed: ${error}`);-->
<!--    }-->
<!--  });-->

<!--  function onDisconnected(event) {-->
<!--    const device = event.target;-->
<!--    console.log(`Device ${device.name} is disconnected, trying to reconnect...`);-->
<!--    connectDeviceAndCacheCharacteristics(device)-->
<!--      .then(() => {-->
<!--        SweetAlert('success', 'Reconnected');-->
<!--      })-->
<!--      .catch(error => {-->
<!--        SweetAlert('warning', `Reconnection failed: ${error}`);-->
<!--      });-->
<!--  }-->

<!--  function connectDeviceAndCacheCharacteristics(device) {-->
<!--    return device.gatt.connect()-->
<!--      .then(server => {-->
<!--          console.log(server);-->
<!--        SweetAlert('success', `Gatt server connected: ${device.name}`);-->
<!--        gattServer = server;-->
<!--        const serviceUUID = BluetoothUUID.canonicalUUID("0x110A");-->
<!--        return server.getPrimaryService(serviceUUID);-->
<!--      })-->
<!--      .then(service => {-->
<!--        const characteristicUUID = BluetoothUUID.getCharacteristic("apparent_wind_direction");-->
<!--        return service.getCharacteristic(characteristicUUID);-->
<!--      })-->
<!--      .then(char => {-->
<!--        characteristic = char;-->
<!--      });-->
<!--  }-->
<!--</script>-->

<?php $time = time() ?>
<script class="javascript" src="<?= base_url("assets") ?>/dist/js/process.js?v=<?= $time ?>"></script>
<script>
	$(document).ready(function() {
		metal_type_id = $("#material-type").val();
		if (metal_type_id) {
			const closingTouchEl = $(".closingTouch");
			$.ajax({
				type: "POST",
				showloader: true,
				dataType: "json",
				url: `${BaseUrl}manufacturing/main_garnu/getStockTouch`,
				method: "POST",
				data: {
					metal_type_id,
				},
				success: function(response) {

					if (response.success) {
						var selected_id = '<?= isset($process_data['closing_touch']) ? $process_data['closing_touch'] : '' ?>';
						var getTouch = getClosingTouchOptions(response.data, selected_id);
						if (closingTouchEl.data('select2')) {
							closingTouchEl.select2('destroy');
						}
						if (selected_id) {
							closingTouchEl.html(getTouch);
							closingTouchEl.select2();
						} else {
							closingTouchEl.html(getTouch);
							closingTouchEl.select2().select2("open");
						}
					} else {
						SweetAlert('warning', response.message);
					}
				},
			});
		}
	})
</script>