<style>
	.readonly {
		background-color: #ebebeb;
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
</style>
<div class="row">
	<div class="col-md-8 pb-3">
		<div class="card">
			<div class="row">
				<div class="col-sm-12">
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
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Name: </label>
									<input type="text" name="name" id="" class="form-control readonly" placeholder="Enter Garnu Name" readonly value="<?= $data['name'] ?? null ?>" autocomplete="off">
								</div>
								<!-- <div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Received Quantity: </label>
									<input type="text" name="rc_qty" id="" class="form-control" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['rc_qty'] : '' ?>">
								</div> -->
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Weight: </label>
									<input type="text" name="weight" id="" class="form-control readonly" readonly placeholder="Enter Weight" value="<?= $data['garnu_weight'] ?? null ?>" autocomplete="off">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
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

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="workers">Workers: </label>
									<select class="form-select select2 workers" name="workers" id="workers">
										<option value="">Select Worker:</option>
									</select>
								</div>

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Remark: </label>
									<input type="text" name="remarks" id="" class="form-control" placeholder="Enter Remark" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['remarks'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Quantity: </label>
									<input type="text" name="given_qty" id="" class="form-control given-qty" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_qty'] : "" ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Weight: </label>
									<input type="text" name="given_weight" id="" class="form-control given_weight" placeholder="Enter Weight" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_weight'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Labour: </label>
									<input type="text" name="labour" id="" class="form-control labour" placeholder="Enter Labour" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['labour'] : 0 ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Row Material Weight: </label>
									<input type="text" name="total-rm_weight" id="" class="form-control totalRmWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Final Weight: </label>
									<input type="text" name="total_weight" id="" class="form-control finalWeight readonly" autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">&nbsp </label>
									<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-usb" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary ms-auto">
								<?= (isset($process_data)) ? 'Update' : 'Submit' ?>
							</button>
						</div>

						<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-scrollable" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Add Row Material</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<table class="table table-vcenter card-table table-striped">
											<thead>
												<tr>
													<th>Row Material</th>
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
														'rmWeight'        => 0,
														'rmTouch'         => 0,
														'rmQuantity'      => 0,
														'id'              => 0
													];
												}
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
														<td class="text-muted">
															<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? 0 ?>" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
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
										<button type="button" class="btn btn-outline-success btn-success addButton">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<button type="button" class="btn btn-outline-primary btn-primary save">Save Changes</button>
									</div>
								</div>
							</div>
						</div>
					</form>

					<form id="received-garnu">
						<div class="modal modal-blur fade modal-xl" data-bs-backdrop="static" data-bs-keyboard="false" id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-xl" role="document">
								<div class="modal-content">
									<div id="receveData"></div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-outline-success btn-success receivedAddButton2">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<div>
											<button type="button" class="btn btn-outline-secondary btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-outline-primary btn-primary submit-btn">Save</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

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
												<th>Touch %</th>
												<th>Weight</th>
												<th>Quantity</th>
												<th></th>
											</tr>
										</thead>
										<tbody id="JBody">
											<?php
											if (empty($given_row_materials)) {
												$given_row_materials[] = [
													'row_material_id' => 0,
													'rmWeight'        => 0,
													'rmTouch'         => 0,
													'rmQuantity'      => 0,
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
													<td class="text-muted">
														<input type="number" class="form-control touch2" value="0" placeholder="Enter Touch" autocomplete="off">
													</td>
													<td class="text-muted">
														<input type="number" class="form-control weight2" value="0" placeholder="Enter Weight" autocomplete="off">
													</td>
													<td class="text-muted">
														<input type="number" class="form-control quantity2" value="0" placeholder="Enter Quantity" autocomplete="off">
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

					<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="metalType-report" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-scrollable" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Received Metal Type</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
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
														<input type="number" class="form-control metalTouch" value="0" placeholder="Enter Touch" autocomplete="off">
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
									<button type="button" class="btn btn-outline-success btn-success metalAddButton">
										<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
									</button>
									<div>
										<button type="button" class="btn btn-outline-secondary btn-secondary" data-bs-dismiss="modal">Close</button>
										<button type="button" class="btn btn-outline-primary btn-primary saveMetalData">Save</button>
									</div>
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
				<div class="col-sm-12">
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
										<th scope="col">Received Pcs</th>
										<th scope="col">Received Weight</th>
										<th scope="col">Received RM Weight</th>
										<th scope="col">Received Final Weight</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($table as $key => $result) {
										$receive = $this->db->select('pcs,weight,row_material_weight,total_weight')->get_where('receive', array('given_id' => $result->id))->result_array();
										$totalPcs = 0;
										$totalweight = 0;
										$totalRMweight = 0;
										$finalWeight = 0;
										foreach ($receive as $data) {
											$totalPcs += $data['pcs'];
											$totalweight += $data['weight'];
											$totalRMweight += $data['row_material_weight'];
											$finalWeight += $data['total_weight'];
										}
									?>
										<tr>
											<td class="given">
												<?= $key + 1; ?>
											</td>
											<td class="given">
												<div class="d-flex gap-2">
													<a class="btn btn-action bg-success text-white me-2 edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit" href="<?= base_url('manufacturing/process/manage/') . $id . '/' . $result->id; ?>">
														<i class="far fa-edit" aria-hidden="true"></i>
													</a>
													<a class="bg-warning btn btn-action text-warning-fg me-2 Received" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-original-title="Received" href="#">
														<i class="fa-brands fa-connectdevelop"></i>
													</a>
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
												<?= $result->row_material_weight; ?>
											</td>
											<td class="given">
												<?= $result->total_weight; ?>
											</td>
											<td class="received totalPcs"><?= $totalPcs; ?></td>
											<td class="received totalWeight"><?= $totalweight; ?></td>
											<td class="received rowMaterialWeight"><?= $totalRMweight; ?></td>
											<td class="received totalFinalWeight"><?= $finalWeight; ?></td>
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

<script class="javascript">
	$(document).ready(function() {
		$('.ManageProcess').submit(function(e) {
			e.preventDefault();
			var validator = new Validator();
			validator
				.addField('.process', "Please select process!", (el) =>
					el.select2("open")
				)
				.addField('#workers', "Please select Worker!", (el) =>
					el.select2("open")
				)
				.addField('.given-qty', "Please Enter Given Quantity!")
				.addField('.given_weight', "Please Enter Given Weight!")
			if (!validator.validate()) return;
			else $(this).off("submit").submit();
		});

		var garnuTouch = $('#gatnuTouch').val();
		var mainRow = $('.mainRow')[0].outerHTML;
		var mainRmRow = $('.main-row')[0]?.outerHTML;
		var metalRow = $('.metalRow')[0]?.outerHTML;
		var ReceivedMainRow;
		var rmBtn = null;
		var receiveBtn = null;

		$('.process').change(function() {
			var process_id = $(this).val();
			var selected_id = $(this).find(":selected").data('workerid');
			$(".workers").empty();
			if (process_id) {
				var optionHTML = "";
				var selected = "";
				optionHTML += `<option value=""> Select <option>`;

				$.ajax({
					type: "POST",
					dataType: "json",
					url: `${BaseUrl}manufacturing/process/getWorkers`,
					method: "POST",
					data: {
						process_id,
						selected_id
					},
					success: function(response) {
						$.each(response, function(key, value) {
							selected =
								selected_id != null && selected_id == value.id ? "selected" : " ";
							optionHTML += `<option value="${value["id"]}" ${selected}>${value["name"]}</option>`;
						});
						if (selected_id != null) {
							$(".workers").html(optionHTML);
						} else {
							$(".workers").html(optionHTML).select2('open');
						}
					},
				});
			} else {
				$(".workers").empty();
				$(".workers").append('<option value="">Select</option>');
			}
		}).trigger('change');

		function autoValueEnter() {
			var totalRmWeight = parseFloat($('.totalRmWeight').val()) || 0;
			var given_weight = parseFloat($('.given_weight').val()) || 0;

			var totalRmWeight = 0;
			$('.weight').each(function() {
				totalRmWeight += parseFloat($(this).val()) || 0;
			});
			$('.totalRmWeight').val(formatNumber(totalRmWeight));
			$('.finalWeight').val(formatNumber(parseFloat(given_weight) + parseFloat(totalRmWeight)));
		}

		autoValueEnter();

		$("button[data-target='#exampleModal']").click(function(event) {
			event.preventDefault();
			var garnu_id = $('#garnu_id').val();
			var given_id = $('#given_id').val();
			if (garnu_id != "" && given_id != "") {}
			$("#modal-report").modal('show');
			Rmcalculate();
		});

		$('.addButton').click(function() {
			var metal = $('.row_material').last();
			if (metal.val() == '') {
				return metal.select2('open');
			}
			$('#TBody').append(mainRow);
			const lastTr = $('#TBody tr').last();
			lastTr.find('.rowid').val(0);
			lastTr.find('.weight, .touch, .quantity').val(0);
			lastTr.find('.row_material').select2({
				width: '200',
				dropdownParent: $('#modal-report')
			});
			lastTr.find('.row_material').last().select2('open');
		});

		$(document).on('click', '.deleteRow', function() {
			if ($('.deleteRow').length > 1) {
				$(this).parents('tr').remove();
			}
			Rmcalculate();
		});

		function scrollEvent(target, pixel = 500) {
			var animated = target.animate({
				scrollTop: target.prop('scrollHeight')
			}, pixel);
		}

		$(document).on('click', '.save', function() {
			var count = 0;
			$('.row_material').each(function() {
				var row_material = $(this).val();
				if (row_material == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			$('.touch').each(function() {
				var touch = $(this).val();
				if (touch == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			(count == 0) ? $("#modal-report").modal('hide'): null;
			var totalRmWeight = 0;
			$('.weight').each(function() {
				totalRmWeight += parseFloat($(this).val()) || 0;
			});
			$('.totalRmWeight').val(totalRmWeight);
			autoValueEnter();
		});

		$(document).on('input', '.touch,.touch2,.metalTouch', function() {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
		});

		$(document).on('input', '.totalRmWeight,.given_weight', function() {
			autoValueEnter();
		});

		$('#modal-report').on('shown.bs.modal', function(e) {
			var modal = this;
			$('.row_material').each(function() {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
			var LastRm = $('.row_material').last();
			if (LastRm.val() == '' || LastRm.val() == 0 || LastRm.val() == null) {
				return LastRm.select2('open');
			}
		});

		// second modal received
		$(document).on('click', '.Received', function() {
			event.preventDefault();
			receiveBtn = $(this);
			var garnu_id = $(this).data('garnu_id');
			var given_id = $(this).data('given_id');

			if (garnu_id != "" && given_id != "") {
				$.ajax({
					url: "<?php echo base_url(); ?>manufacturing/process/receiveGarnu",
					method: "POST",
					showLoader: true,
					data: {
						garnu_id,
						given_id
					},
					success: function(response) {
						$('#receveData').html(response);
					}
				}).done(function(response) {
					$("#received1-report").modal('show');
				});
			}
		});

		$('#received1-report').on('shown.bs.modal', function(e) {
			ReceivedMainRow = $('.ReceivedMainRow')[0].outerHTML;
			TotalCalculation();

			var modal = this;
			$('.customer').each(function() {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
		});

		$('.receivedAddButton2').click(function() {
			$('#ReceivedBody').append(ReceivedMainRow);
			const lastTr = $('#ReceivedBody tr').last();
			lastTr.find('.rcid').val("");
			lastTr.find('.rcid,.Pcs, .receivedWeight').val(0);
			lastTr.find('.receivedRemark,.rmdata').val("");
			lastTr.find('.receivedRmWeight').val(0);
			lastTr.find('.receivedfinalWeight').val(0);
			var modalBody = $('#received1-report .modal-body');
			scrollEvent(modalBody, 550);
		});

		$(document).on('click', '.receiveddeleteRow', function() {
			if ($('.receiveddeleteRow').length > 1) {
				$(this).parents('tr').remove();
				TotalCalculation();
			}
		});

		$('#received-report').on('shown.bs.modal', function(e) {
			var modal = this;
			$('.row_material2').each(function() {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
			var LastRm = $('.row_material2').last();
			if (LastRm.val() == '' || LastRm.val() == 0 || LastRm.val() == null) {
				return LastRm.select2('open');
			}

		});

		$('.addButton2').click(function() {
			var LastRm = $('.row_material2').last();
			if (LastRm.val() == '') {
				return LastRm.select2('open');
			}
			$('#JBody').append(mainRmRow);
			const lastTr = $('#JBody tr').last();
			lastTr.find('.rowid2').val(0);
			lastTr.find('.weight2, .touch2, .quantity2').val(0);
			lastTr.find('.row_material2').select2({
				width: '200',
				dropdownParent: $('#received-report')
			});
			lastTr.find('.row_material2').last().select2('open');
			var modalBody = $('#received-report .modal-body');
			scrollEvent(modalBody, 550);
		});

		$(document).on('click', '.deleteRow2', function() {
			if ($('.deleteRow2').length > 1) {
				$(this).parents('tr').remove();
			}
			RmcalculateMain();
		});

		$(document).on('input', '.touch2,.weight2,.quantity2', function() {
			RmcalculateMain();
		});

		$(document).on('click', '.saveRmData', function() {
			var count = 0;
			$('.row_material2').each(function() {
				var row_material = $(this).val();
				if (row_material == 0 || row_material == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});

			$('.touch2').each(function() {
				var touch = $(this).val();
				if (touch == 0 || touch == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			(count == 0) ? $("#received-report").modal('hide'): null;

			var modal = $('#received-report');
			var container = rmBtn.parents('tr');
			var mainSection = modal.find(".main-row");
			var mainSectionLength = modal.find('tbody tr').length;
			let FilterVar = (el) => {
				if (el == "" || el == undefined || el == NaN) {
					return 0;
				}
				return el;
			};
			var string = "";
			var totalRmWeight = 0;
			for (var i = 0; i < mainSectionLength; i++) {
				var row = mainSection.eq(i);
				var rm = row.find(".row_material2 option:selected").val();
				var touch = FilterVar(row.find(".touch2").val());
				var weight = FilterVar(row.find(".weight2").val());
				var quantity = FilterVar(row.find(".quantity2").val());
				var received_detail_id = FilterVar(row.find(".received_detail_id").val());
				totalRmWeight += parseFloat(weight) || 0;
				string += [rm, touch, weight, quantity, received_detail_id].join(",");
				if (mainSectionLength > i + 1)
					string += "|";
			}
			container.find(".rmdata").val(string);
			container.find(".receivedRmWeight").val(totalRmWeight);
			finalCalculation(rmBtn);
			TotalCalculation();
		});

		function finalCalculation(i) {
			var container = i.parents('tr');
			var receivedWeight = container.find(".receivedWeight").val() || 0;
			var receivedRmWeight = container.find(".receivedRmWeight").val() || 0;
			container.find(".receivedfinalWeight").val(formatNumber(parseFloat(receivedWeight) + parseFloat(receivedRmWeight)));
		}

		$(document).on('input', '.receivedWeight', function() {
			finalCalculation($(this));
			TotalCalculation();
		});
		$(document).on('input', '.Pcs', function() {
			TotalCalculation();
		});

		$(document).on('click', '.Receivedmaterial', function() {
			rmBtn = $(this);
			var modal = $("#received-report");
			var givenContainer = rmBtn.parents('tr');;
			var mainSection = modal.find(".main-row");
			modal.find("tbody").html("");
			var string = givenContainer.find(".rmdata").val();
			var data = string?.split("|");
			mainSectionLength = data?.length ?? 0;
			if (mainSectionLength > 0) {
				for (var i = 0; i < mainSectionLength; i++) {
					modal.find("tbody").append(mainRmRow);
					var row = modal.find(".main-row").eq(i),
						splitByHash = data[i]?.split(","),
						row_material2 = splitByHash[0] ?? 0,
						touch2 = splitByHash[1] ?? 0,
						weight2 = splitByHash[2] ?? 0;
					quantity2 = splitByHash[3] ?? 0;
					received_detail_id = splitByHash[4] ?? 0;
					row.find(".row_material2 ").val(row_material2).trigger("change");;
					(row.find(".touch2").val(touch2));
					(row.find(".weight2").val(weight2));
					(row.find(".quantity2").val(quantity2));
					(row.find(".received_detail_id").val(received_detail_id));
					RmcalculateMain();
				}
			} else {
				modal.find("tbody").append(mainRmRow);
			}
			modal.modal("show");
		});

		function RmcalculateMain() {
			var Totaltouch = 0;
			var Totalweight = 0;
			var Totalqty = 0;

			$('.touch2').each(function() {
				Totaltouch += parseFloat($(this).val() || 0);
			});
			$('.weight2').each(function() {
				Totalweight += parseFloat($(this).val() || 0);
			});
			$('.quantity2').each(function() {
				Totalqty += parseFloat($(this).val() || 0);
			});

			$('.total-touch').text(formatNumber(Totaltouch));
			$('.total-weight').text(formatNumber(Totalweight));
			$('.total-qty').text(formatNumber(Totalqty));
		}

		$('#received-garnu').on('submit', function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: '<?php echo base_url('manufacturing/process/receiveGarnuAdd'); ?>',
				type: 'POST',
				data: formData,
				beforeSend: (data) => {
					ShowBlockUi('#received1-report');
				},
				success: function(response) {
					var response = JSON.parse(response);
					if (response.success === true) {
						receiveBtn.parents('tr').find('.totalPcs').text($('#totalPcs').text());
						receiveBtn.parents('tr').find('.totalWeight').text($('#TotalWeight').text());
						receiveBtn.parents('tr').find('.rowMaterialWeight').text($('#rowMaterialWeight').text());
						receiveBtn.parents('tr').find('.totalFinalWeight').text($('#totalFinalWeight').text());

						$('#received1-report').modal('hide');
						SweetAlert('success', response.message);
					} else {
						$('#received1-report').modal('hide');
						SweetAlert('error', response.message);
					}
				},
				error: function() {
					SweetAlert('error', "Error submitting form");
				}
			});
		});

		function TotalCalculation() {
			var totalPcs = 0;
			var receivedWeight = 0;
			var receivedRmWeight = 0;
			var receivedfinalWeight = 0;

			$('.Pcs').each(function() {
				totalPcs += parseFloat($(this).val() || 0);
			});
			$('.receivedWeight').each(function() {
				receivedWeight += parseFloat($(this).val() || 0);
			});
			$('.receivedRmWeight').each(function() {
				receivedRmWeight += parseFloat($(this).val() || 0);
			});
			$('.receivedfinalWeight').each(function() {
				receivedfinalWeight += parseFloat($(this).val() || 0);
			});

			$('#totalPcs').text('');
			$('#totalPcs').text(totalPcs);
			$('#TotalWeight').text('');
			$('#TotalWeight').text(formatNumber(receivedWeight));
			$('#rowMaterialWeight').text('');
			$('#rowMaterialWeight').text(formatNumber(receivedRmWeight));
			$('#totalFinalWeight').text('');
			$('#totalFinalWeight').text(formatNumber(receivedfinalWeight));

			var Total = $('#givenTotal_weight').text() - receivedfinalWeight;
			let formattedTotal = formatNumber(Total);
			var jamaBaki = "";
			if (formattedTotal > 0) {
				jamaBaki = `<h4 class='text-danger'>ધટાડો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			} else if (formattedTotal == 0) {
				jamaBaki = `<h4 class='text-success'>સરભર :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			} else {
				jamaBaki = `<h4 class='text-success'>વધારો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			}
			$('.jama_baki').val(formattedTotal);
			$('#jama_baki').html('');
			$('#jama_baki').html(jamaBaki);
		}

		$(document).on('input', '.touch,.weight,.quantity', function() {
			Rmcalculate();
		});

		function Rmcalculate() {
			var Totaltouch = 0;
			var Totalweight = 0;
			var Totalqty = 0;

			$('.weight').each(function() {
				Totalweight += parseFloat($(this).val() || 0);
			});
			$('.touch').each(function() {
				Totaltouch += parseFloat($(this).val() || 0);
			});
			$('.quantity').each(function() {
				Totalqty += parseFloat($(this).val() || 0);
			});

			$('.total-touch').text("");
			$('.total-weight').text("");
			$('.total-net_weight').text("");

			$('.total-touch').text(formatNumber(Totaltouch));
			$('.total-weight').text(formatNumber(Totalweight));
			$('.total-qty').text(formatNumber(Totalqty));
		}

		$(document).on('focus', '.touch,.weight,.quantity,.Pcs,.receivedWeight,.touch2, .weight2, .quantity2,.given-qty,.labour,.metalQuantity,.metalWeight,.metalTouch', function() {
			handleInputFocusAndBlur(this, 'focus');
		}).on('blur', '.touch,.weight,.quantity,.Pcs,.receivedWeight,.touch2, .weight2, .quantity2,.given-qty,.labour,.metalQuantity,.metalWeight,.metalTouch', function() {
			handleInputFocusAndBlur(this, 'blur');
		});

		function handleInputFocusAndBlur(element, eventType) {
			var $element = $(element);
			if (eventType === 'focus' && $element.val() == '0') {
				$element.val('');
			} else if (eventType === 'blur' && $element.val() == '') {
				$element.val('0');
			}
		}

		$(document).on('click', '.ProcessMetalType', function() {
			var modal = $("#metalType-report");
			var mainSection = modal.find(".metalRow");
			modal.find("tbody").html("");
			var string = $('.ProcessMetalType').parent().find(".metaldata").val();
			var data = string?.split("|");
			mainSectionLength = data?.length ?? 0;
			if (mainSectionLength > 0) {
				for (var i = 0; i < mainSectionLength; i++) {
					modal.find("tbody").append(metalRow);
					var row = modal.find(".metalRow").eq(i),
						splitByHash = data[i]?.split(","),
						metal_type = splitByHash[0] ?? 0,
						touch = splitByHash[1] ?? garnuTouch,
						weight = splitByHash[2] ?? 0;
					quantity = splitByHash[3] ?? 0;
					process_metal_type = splitByHash[4] ?? 0;
					row.find(".metal_type ").val(metal_type).trigger("change");;
					(row.find(".metalTouch").val(touch));
					(row.find(".metalWeight").val(weight));
					(row.find(".metalQuantity").val(quantity));
					(row.find(".process_metal_type").val(process_metal_type));
					Metalcalculate();
				}
			} else {
				modal.find("tbody").append(metalRow);
			}
			modal.modal("show");
		});

		$('#metalType-report').on('shown.bs.modal', function(e) {
			Metalcalculate();
			var modal = this;
			$('.metal_type').each(function() {
				$(this).select2({
					width: '250',
					dropdownParent: $(modal)
				});
			});
		});

		function Metalcalculate() {
			var metalTotalweight = 0;
			var metalTotaltouch = 0;
			var metalTotalqty = 0;

			$('.metalWeight').each(function() {
				metalTotalweight += parseFloat($(this).val() || 0);
			});
			$('.metalTouch').each(function() {
				metalTotaltouch += parseFloat($(this).val() || 0);
			});
			$('.metalQuantity').each(function() {
				metalTotalqty += parseFloat($(this).val() || 0);
			});

			$('.metal-total-touch').text("");
			$('.metal-total-weight').text("");
			$('.metal-total-net_weight').text("");

			$('.metal-total-weight').text(formatNumber(metalTotalweight));
			$('.metal-total-touch').text(formatNumber(metalTotaltouch));
			$('.metal-total-qty').text(formatNumber(metalTotalqty));
		}

		$('.metalAddButton').click(function() {
			var LastRm = $('.metal_type').last();
			if (LastRm.val() == '') {
				return LastRm.select2('open');
			}
			$('#MetalBody').append(metalRow);
			const lastTr = $('#MetalBody tr').last();
			lastTr.find('.metalTouch').val(garnuTouch);
			lastTr.find('.metalWeight,.metalQuantity').val(0);
			lastTr.find('.metal_type').select2({
				width: '250',
				dropdownParent: $('#metalType-report')
			});
			lastTr.find('.metal_type').last().select2('open');
			var modalBody = $('#metalType-report .modal-body');
			scrollEvent(modalBody, 550);
			Metalcalculate();
		});

		$(document).on('click', '.metalDeleteRow', function() {
			if ($('.metalDeleteRow').length > 1) {
				$(this).parents('tr').remove();
			}
			Metalcalculate();
		});

		$(document).on('input', '.metalWeight,.metalTouch,.metalQuantity', function() {
			Metalcalculate();
		});

		$(document).on('click', '.saveMetalData', function() {
			var count = 0;
			$('.metal_type').each(function() {
				var metal_type = $(this).val();
				if (metal_type == 0 || metal_type == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Metal Type and Touch.');
				}
			});

			$('.metalTouch').each(function() {
				var metalTouch = $(this).val();
				if (metalTouch == 0 || metalTouch == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Metal Type and Touch.');
				}
			});
			(count == 0) ? $("#metalType-report").modal('hide'): null;

			var modal = $('#metalType-report');
			var container = $('.ProcessMetalType').parent();
			console.log(container);
			var mainSection = modal.find(".metalRow");
			var mainSectionLength = modal.find('tbody tr').length;
			let FilterVar = (el) => {
				if (el == "" || el == undefined || el == NaN) {
					return 0;
				}
				return el;
			};
			var string = "";
			var totalRmWeight = 0;
			for (var i = 0; i < mainSectionLength; i++) {
				var row = mainSection.eq(i);
				var mt = row.find(".metal_type  option:selected").val();
				var metalTouch = FilterVar(row.find(".metalTouch").val());
				var metalWeight = FilterVar(row.find(".metalWeight").val());
				var metalQuantity = FilterVar(row.find(".metalQuantity").val());
				var process_metal_type = FilterVar(row.find(".process_metal_type").val());
				string += [mt, metalTouch, metalWeight, metalQuantity, process_metal_type].join(",");
				if (mainSectionLength > i + 1)
					string += "|";
			}
			container.find(".metaldata").val(string);
		});

	});
</script>
