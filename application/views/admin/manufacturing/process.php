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
		max-width: 60%;
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
								<input type="hidden" name="garnu_id" id="" class="form-control garnu_id" value="<?= $data['id'] ?? null ?>">
								<input type="hidden" name="given_id" id="" class="form-control given_id" value="<?= $process_data['id'] ?? null ?>">
								<input type="hidden" id="gatnuTouch" value="<?= $data['touch'] ?? null ?>">
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Dhal Name: </label>
									<input type="text" name="name" id="" class="form-control readonly" placeholder="Enter Dhal Name" readonly value="<?= $data['name'] ?? null ?>" autocomplete="off">
								</div>
								<!-- <div class="col-md-4 col-md-3">
									<label class="form-label" for="">Received Quantity: </label>
									<input type="text" name="rc_qty" id="" class="form-control" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['rc_qty'] : '' ?>">
								</div> -->
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Dhal Weight: </label>
									<input type="text" name="weight" id="" class="form-control readonly" readonly placeholder="Enter Weight" value="<?= $data['garnu_weight'] ?? null ?>" autocomplete="off">
									<input type="hidden" id="receiveCode" value="<?= $receiveCode[0]['code'] ?? null ?>" autocomplete="off">
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Dhal Touch: </label>
									<input type="text" name="touch" id="" class="form-control readonly" readonly placeholder="Enter Touch" step="any" value="<?= $data['touch'] ?? null ?>" autocomplete="off">
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
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="process">Process: </label>
									<select class="form-select select2 process" name="process">
										<option value=''>Select Process</option>
										<?php
										foreach ($process as $value) {
										?>
											<option value="<?= $value->id ?? null ?>" data-workerId="<?= $process_data['worker_id'] ?? 0 ?>" <?php if (isset($process_data) && ($value->id == $process_data['process_id'])) {
																																					echo 'selected';
																																				} ?>>
												<?= $value->name; ?>
											</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-4 col-md-3">
									<label class="form-label" for="workers">Workers: </label>
									<select class="form-select select2 workers" name="workers" id="workers">
										<option value="">Select Worker:</option>
									</select>
								</div>

								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Remark: </label>
									<input type="text" name="remarks" id="" class="form-control" placeholder="Enter Remark" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['remarks'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Given Quantity: </label>
									<input type="text" name="given_qty" id="" class="form-control given-qty" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_qty'] : "0" ?>">
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Given Weight: </label>
									<input type="text" name="given_weight" class="form-control given_weight" placeholder="Enter Weight" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_weight'] : '0' ?>">
								</div>
								<!-- <div class="col-md-4 col-md-3">
									<label class="form-label" for="">Labour: </label>
									<input type="text" name="labour" id="" class="form-control labour" placeholder="Enter Labour" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['labour'] : 0 ?>">
								</div> -->
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Row Material Weight: </label>
									<input type="text" name="total-rm_weight" id="" class="form-control totalRmWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">Final Weight: </label>
									<input type="text" name="total_weight" id="" class="form-control finalWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-md-3">
									<label class="form-label" for="">&nbsp </label>
									<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-usb" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary ms-auto">
								<?= (isset($process_data)) ? 'Update' : 'Submit' ?>
							</button>
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
											<table class="table table-vcenter card-table table-striped">
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
																<select class="form-select select2 row_material" name="row_material[]">
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
																	<option value="">Select RM</option>
																	<?php
																	if (!empty($lot_wise_rm)) {
																		for ($j = 0; $j < count($lot_wise_rm); $j++) { ?>
																			<option value="<?= $lot_wise_rm[$j]['id']; ?>" data-weight="<?= $lot_wise_rm[$j]['rem_weight']; ?>" data-quantity="<?= $lot_wise_rm[$j]['rem_quantity']; ?>"
																			data-oldWeight="<?= $row['weight'] ?? 0 ?>" data-oldQuantity="<?= $row['quantity'] ?? 0 ?>"
																			<?php if (isset($row) && $lot_wise_rm[$j]['id'] == $row['lot_wise_rm_id']) {echo 'selected';} ?>><?= $lot_wise_rm[$j]['code']; ?> &nbsp; Weight: <?= $lot_wise_rm[$j]['rem_weight']; ?> Quantity : <?= $lot_wise_rm[$j]['rem_quantity']; ?>
																			</option>
																	<?php }
																	} ?>
																</select>
															</td>
															<td class="text-muted">
																<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? $data['touch'] ?? 0 ?>" step="any" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
															</td>
															<td class="text-muted">
																<input type="number" name="rmWeight[]" value="<?= $row['weight'] ?? 0 ?>" class="form-control weight" placeholder="Enter Weight" autocomplete="off">
															</td>
															<td class="text-muted">
																<input type="number" name="rmQuantity[]" value="<?= $row['quantity'] ?? 0 ?>" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
															</td>
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
												</tfoot>
											</table>
										</div>
										<div class="modal-footer justify-content-between">
											<button type="button" class="btn btn-success btn-success addButton">
												<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
											</button>
											<div>
												<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
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
						<div class="modal-dialog modal-rm-xl modal-dialog-scrollable" role="document">
							<div class="modal-content modal-rm-content">
								<div class="modal-header">
									<h5 class="modal-title">Received Row Material</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table table-vcenter card-table table-striped">
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
														'lot_wise_rm_id' => 0,
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
															<select class="form-select select2 row_material2">
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
																<?php
																if (!empty($receiveLot_wise_rm)) {
																	for ($j = 0; $j < count($receiveLot_wise_rm); $j++) { ?>
																		<option value="<?= $receiveLot_wise_rm[$j]['id']; ?>" data-weight="<?= $receiveLot_wise_rm[$j]['rem_weight']; ?>" data-quantity="<?= $receiveLot_wise_rm[$j]['rem_quantity']; ?>" <?php if (isset($row) && $receiveLot_wise_rm[$j]['id'] == $row['lot_wise_rm_id']) {
																																																																echo 'selected';
																																																															} ?>><?= $receiveLot_wise_rm[$j]['code']; ?> &nbsp;Weight: <?= $receiveLot_wise_rm[$j]['rem_weight']; ?> Quantity : <?= $receiveLot_wise_rm[$j]['rem_quantity']; ?>
																		</option>
																<?php }
																} ?>
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
															<select class="form-select select2 labour_type" name="labour_type">
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
																if (!empty($metal_type)) {
																	foreach ($metal_type as $mt) { ?>
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
	<div class="col-md-5 pb-3">
		<div class="card">
			<div class="row">
				<div class="col-md-12">
					<div class="card-header">
						<div class="card-status-top bg-blue"></div>
						<h1 class="card-title"><b> Row Materials </b></h1>
					</div>
					<div class="card-body border-bottom py-3">
						<!-- <div class="table-responsive"> -->
						<table class="table" id="row_materialTable">
							<thead>
								<tr>
									<th scope="col">Id</th>
									<th scope="col">Row Material</th>
									<th scope="col">Last Process</th>
									<th scope="col">Process</th>
									<th scope="col">Touch</th>
									<th scope="col">Weight</th>
									<th scope="col">Quantity</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!-- </div> -->
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
									<?php foreach ($table as $index => $result) {
										$receive = $this->db->select('id,pcs,weight,row_material_weight,total_weight')->get_where('receive', array('given_id' => $result->id))->result_array();
										$totalPcs = 0;
										$totalweight = 0;
										$totalRMweight = 0;
										$finalWeight = 0;
										foreach ($receive as $key => $rm) {
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
												if ($result->vadharo_dhatado > 0) { ?>
													<h4 class="text-danger">ધટાડો : <?= $result->vadharo_dhatado; ?></h4>
												<?php } else { ?>
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
					<p class="modal-title">Dhal Weight:- <span class="garnu_weight"></span></p>
				</div>
				<div class="col-md-4 text-center">
					<p class="modal-title">Dhal Name:- <span class="garnu_name"></span></p>
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

<?php $time = time() ?>
<script class="javascript" src="<?= base_url("assets") ?>/dist/js/process.js?v=<?= $time ?>"></script>
