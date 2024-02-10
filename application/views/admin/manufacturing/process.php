<style>
	.finalWeight {
		background-color: #ebebeb;
		color: black;
	}

	.totalRmWeight {
		background-color: #ebebeb;
		color: black;
	}

	.receivedfinalWeight {
		background-color: #ebebeb;
		color: black;
	}

	.receivedRmWeight {
		background-color: #ebebeb;
		color: black;
	}
</style>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="row">
				<div class="col-sm-12">
					<form
						action="<?= (isset($process_data)) ? base_url('manufacturing/process/update') : base_url('manufacturing/process/add') ?>"
						method="post" class="" novalidate>
						<div class="card-header">
							<div class="card-status-top bg-blue"></div>
							<h1 class="card-title"><b> Garnu </b></h1>
						</div>
						<div class="card-body border-bottom py-3">
							<div class="row mt-1">
								<input type="hidden" name="garnu_id" id="" class="form-control garnu_id"
									value="<?= $data['id'] ?? null ?>">
								<input type="hidden" name="given_id" id="" class="form-control given_id"
									value="<?= $process_data['id'] ?? null ?>">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Name: </label>
									<input type="text" name="name" id="" class="form-control"
										placeholder="Enter Garnu Name" value="<?= $data['name'] ?? null ?>"
										autocomplete="off">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Received Quantity: </label>
									<input type="text" name="rc_qty" id="" class="form-control"
										placeholder="Enter Quantity" autocomplete="off"
										value="<?= (isset($process_data)) ? $process_data['rc_qty'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Weight: </label>
									<input type="text" name="weight" id="" class="form-control"
										placeholder="Enter Weight" value="<?= $data['garnu_weight'] ?? null ?>"
										autocomplete="off">
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
											<option value="<?= $value->id ?? null ?>"
												data-workerId="<?= $process_data['worker_id'] ?? 0 ?>" <?php if (isset($process_data) && ($value->id == $process_data['process_id'])) {
														echo 'selected';
													} ?>>
												<?= $value->name; ?>
											</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="workers">Workers: </label>
									<select class="form-select select2 " name="workers" id="workers">
										<option value="">Select Worker:</option>
									</select>
								</div>

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Remark: </label>
									<input type="text" name="remarks" id="" class="form-control"
										placeholder="Enter Remark" autocomplete="off"
										value="<?= (isset($process_data)) ? $process_data['remarks'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Quantity: </label>
									<input type="text" name="given_qty" id="" class="form-control"
										placeholder="Enter Quantity" autocomplete="off"
										value="<?= (isset($process_data)) ? $process_data['given_qty'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Weight: </label>
									<input type="text" name="given_weight" id="" class="form-control given_weight"
										placeholder="Enter Weight" autocomplete="off"
										value="<?= (isset($process_data)) ? $process_data['given_weight'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Labour: </label>
									<input type="text" name="labour" id="" class="form-control"
										placeholder="Enter Labour" autocomplete="off"
										value="<?= (isset($process_data)) ? $process_data['labour'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Row Material Weight: </label>
									<input type="text" name="" id="" class="form-control totalRmWeight"
										autocomplete="off" value="0" readonly>
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Final Weight: </label>
									<input type="text" name="" id="" class="form-control finalWeight" autocomplete="off"
										value="0" readonly>
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">&nbsp </label>
									<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i
											class="fa fa-usb" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary ms-auto">
								<?= (isset($process_data)) ? 'Update' : 'Submit' ?>
							</button>
						</div>

						<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
							id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-scrollable" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Add Row Material</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
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
														'rmWeight'        => '',
														'rmTouch'         => 0,
														'rmQuantity'      => '',
														'id'              => 0
													];
												}
												foreach ($given_row_material as $row) { ?>
													<input type="hidden" class="ids" name="ids[]"
														value="<?= $row['id'] ?? "0"; ?>" />
													<tr class="mainRow">
														<td>
															<input type="hidden" class="rowid" name="rowid[]"
																value="<?= $row['id'] ?? "0"; ?>" />
															<select class="form-select select2 row_material" required
																name="row_material[]">
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
															<input type="number" name="rmTouch[]"
																value="<?= $row['touch'] ?? null ?>" required
																class="form-control touch" placeholder="Enter Touch"
																autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" name="rmWeight[]"
																value="<?= $row['weight'] ?? null ?>"
																class="form-control weight" placeholder="Enter Weight"
																autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" name="rmQuantity[]"
																value="<?= $row['quantity'] ?? null ?>"
																class="form-control quantity" placeholder="Enter Quantity"
																autocomplete="off">
														</td>
														<td>
															<button type="button"
																class="btn btn-danger deleteRow">X</button>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-success addButton">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<button type="button" class="btn btn-primary save">Save Changes</button>
									</div>
								</div>
							</div>
						</div>
					</form>

					<form id="received-garnu">
						<div class="modal modal-blur fade modal-xl" data-bs-backdrop="static" data-bs-keyboard="false"
							id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Received</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div id="receveData"></div>
									</div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-success receivedAddButton2">
											<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
										</button>
										<div>
											<button type="button" class="btn btn-secondary"
												data-bs-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Save</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

					<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
						id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-scrollable" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Received Row Material</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal"
										aria-label="Close"></button>
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
													'rmWeight'        => '',
													'rmTouch'         => 0,
													'rmQuantity'      => '',
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
														<input type="number" class="form-control touch2"
															placeholder="Enter Touch" autocomplete="off">
													</td>
													<td class="text-muted">
														<input type="number" class="form-control weight2"
															placeholder="Enter Weight" autocomplete="off">
													</td>
													<td class="text-muted">
														<input type="number" class="form-control quantity2"
															placeholder="Enter Quantity" autocomplete="off">
													</td>
													<td>
														<button type="button" class="btn btn-danger deleteRow2">X</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-success addButton2">
										<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
									</button>
									<div>
										<button type="button" class="btn btn-secondary"
											data-bs-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary saveRmData">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
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
									</tr>
								</thead>
								<tbody>
									<?php foreach ($table as $key => $result) { ?>
										<tr>
											<td>
												<?= $key + 1; ?>
											</td>
											<td>
												<div>
													<a class="btn btn-action bg-success text-white me-2 edit"
														data-bs-toggle="tooltip" data-bs-placement="top"
														data-bs-original-title="Edit"
														href="<?= base_url('manufacturing/process/manage/') . $id . '/' . $result->id; ?>">
														<i class="far fa-edit" aria-hidden="true"></i>
													</a>
													<a class="bg-purple btn btn-action text-purple-fg me-2 Received"
														data-demo-color data-bs-toggle="tooltip" data-bs-placement="top"
														data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>"
														data-bs-original-title="Received" href="#">
														<i class="fa fa-connectdevelop" aria-hidden="true"></i>
													</a>
												</div>
											</td>
											<td>
												<?= $result->creation_date; ?>
											</td>
											<td>
												<?= $result->process_name; ?>
											</td>
											<td>
												<?= $result->customer_name; ?>
											</td>
											<td>
												<?= $result->given_qty; ?>
											</td>
											<td>
												<?= $result->given_weight; ?>
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

<script class="javascript">
	$(document).ready(function () {
		var mainRow = $('.mainRow')[0].outerHTML;
		var mainRmRow = $('.main-row')[0]?.outerHTML;
		var ReceivedMainRow;
		var rmBtn = null;
		$('.process').change(function () {
			var process_id = $(this).val();
			var worker_id = $(this).find(":selected").data('workerid')
			if (process_id != '') {
				$.ajax({
					url: "<?php echo base_url(); ?>manufacturing/process/getWorkers",
					method: "POST",
					data: {
						process_id,
						worker_id
					},
					success: function (data) {
						$('#workers').html(data);
					}
				});
			} else {
				$('#workers').html('<option value="">Select Workers</option>');
			}
		}).trigger('change');

		function autoValueEnter() {
			var totalRmWeight = parseFloat($('.totalRmWeight').val()) || 0;
			var given_weight = parseFloat($('.given_weight').val()) || 0;

			var totalRmWeight = 0;
			$('.weight').each(function () {
				totalRmWeight += parseFloat($(this).val()) || 0;
			});
			$('.totalRmWeight').val(totalRmWeight);
			$('.finalWeight').val(parseFloat(given_weight) + parseFloat(totalRmWeight));
		}

		autoValueEnter();

		$("button[data-target='#exampleModal']").click(function (event) {
			event.preventDefault();
			var garnu_id = $('#garnu_id').val();
			var given_id = $('#given_id').val();
			if (garnu_id != "" && given_id != "") { }
			$("#modal-report").modal('show');
		});

		$('.addButton').click(function () {
			var metal = $('.row_material').last();
			if (metal.val() == '') {
				return metal.select2('open');
			}
			$('#TBody').append(mainRow);
			const lastTr = $('#TBody tr').last();
			lastTr.find('.rowid').val(0);
			lastTr.find('.weight, .touch, .quantity').val('');
			lastTr.find('.row_material').select2({
				width: '200',
				dropdownParent: $('#modal-report')
			});
			lastTr.find('.row_material').last().select2('open');
		});

		$(document).on('click', '.deleteRow', function () {
			if ($('.deleteRow').length > 1) {
				$(this).parents('tr').remove();
			}
		});

		$(document).on('click', '.save', function () {
			var count = 0;
			$('.row_material').each(function () {
				var row_material = $(this).val();
				if (row_material == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			$('.touch').each(function () {
				var touch = $(this).val();
				if (touch == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			(count == 0) ? $("#modal-report").modal('hide') : null;
			var totalRmWeight = 0;
			$('.weight').each(function () {
				totalRmWeight += parseFloat($(this).val()) || 0;
			});
			$('.totalRmWeight').val(totalRmWeight);
			autoValueEnter();
		});

		$(document).on('input', '.touch', function () {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
		});

		$(document).on('input', '.totalRmWeight,.given_weight', function () {
			autoValueEnter();
		});

		$('#modal-report').on('shown.bs.modal', function (e) {
			var modal = this;
			$('.row_material').each(function () {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
		});


		// second modal received
		$(document).on('click', '.Received', function () {
			event.preventDefault();
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
					success: function (response) {
						$('#receveData').html(response);
					}
				}).done(function (response) {
					$("#received1-report").modal('show');
				});
			}
		});

		$('#received1-report').on('shown.bs.modal', function (e) {
			ReceivedMainRow = $('.ReceivedMainRow')[0].outerHTML;
			var modal = this;
			$('.customer').each(function () {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
		});

		$('.receivedAddButton2').click(function () {
			$('#ReceivedBody').append(ReceivedMainRow);
			const lastTr = $('#ReceivedBody tr').last();
			lastTr.find('.rowid2').val(0);
			lastTr.find('.rcid').val("");
			lastTr.find('.rcid').val(0);
			lastTr.find('.Pcs, .receivedWeight, .receivedRemark,.rmdata').val('');
			lastTr.find('.receivedRmWeight').val(0);
			lastTr.find('.receivedfinalWeight').val(0);
		});

		$(document).on('click', '.receiveddeleteRow', function () {
			if ($('.receiveddeleteRow').length > 1) {
				$(this).parents('tr').remove();
			}
		});

		$('#received-report').on('shown.bs.modal', function (e) {
			var modal = this;
			$('.row_material2').each(function () {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
		});

		var mainRow2 = $('.mainRow2')[0].outerHTML;
		$('.addButton2').click(function () {
			var LastRm = $('.row_material2').last();
			if (LastRm.val() == '') {
				return LastRm.select2('open');
			}
			$('#JBody').append(mainRow2);
			const lastTr = $('#JBody tr').last();
			lastTr.find('.rowid2').val(0);
			lastTr.find('.weight2, .touch2, .quantity2').val('');
			lastTr.find('.row_material2').select2({
				width: '200',
				dropdownParent: $('#received-report')
			});
			lastTr.find('.row_material2').last().select2('open');
		});

		$(document).on('click', '.deleteRow2', function () {
			if ($('.deleteRow2').length > 1) {
				$(this).parents('tr').remove();
			}
		});

		$(document).on('input', '.touch2', function () {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
		});

		$(document).on('click', '.saveRmData', function () {
			var count = 0;
			$('.row_material2').each(function () {
				var row_material = $(this).val();
				if (row_material == 0 || row_material == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});

			$('.touch2').each(function () {
				var touch = $(this).val();
				if (touch == 0 || touch == "") {
					count += 1;
					SweetAlert('warning', 'Please Enter Row Material and Touch.');
				}
			});
			(count == 0) ? $("#received-report").modal('hide') : null;

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
		});

		function finalCalculation(i) {
			var container = i.parents('tr');
			var receivedWeight = container.find(".receivedWeight").val() || 0;
			var receivedRmWeight = container.find(".receivedRmWeight").val() || 0;
			container.find(".receivedfinalWeight").val(parseFloat(receivedWeight) + parseFloat(receivedRmWeight));
		}

		$(document).on('input', '.receivedWeight', function () {
			finalCalculation($(this));
		});

		$(document).on('click', '.Receivedmaterial', function () {
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
				}
			} else {
				modal.find("tbody").append(mainRmRow);
			}
			modal.modal("show");
		});

		$('#received-garnu').on('submit', function (e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: '<?php echo base_url('manufacturing/process/receiveGarnuAdd'); ?>',
				type: 'POST',
				data: formData,
				success: function (response) {
					var response = JSON.parse(response);
					if (response.success === true) {
						$('#received1-report').modal('hide');
						SweetAlert('success', response.message);
					} else {
						$('#received1-report').modal('hide');
						SweetAlert('error', response.message);
					}
				},
				error: function () {
					SweetAlert('error', "Error submitting form");
				}
			});
		});

	});
</script>
