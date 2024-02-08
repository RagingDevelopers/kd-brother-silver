<div class="row">
<<<<<<< HEAD
    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="modal modal-blur fade modal-lg" id="modal-report" tabindex="-1" role="dialog"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
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
                                            <th>Touch</th>
                                            <th>Weight</th>
                                            <th>Quantity</th>
                                            <th></th>
                                        </tr>                                   
                                    </thead>
                                    <tbody id="TBody">
                                        <tr class="mainRow">
                                            <td><select name="" id="" class="form-select">
                                                    <option value="">Select RM</option>
                                                    <option value="">1</option>
                                                    <option value="">2</option>
                                                </select></td>
                                            <td class="text-muted">
                                            <p></p>
                                            </td>
                                            <td class="text-muted">
                                            <p></p>
                                            </td>
                                            <td class="text-muted"><p></p></td>
                                            <td>
                                                <a href="#"> <button type="button" class="btn btn-danger deleteRow">X</button></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                           
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-success addButton">
                                    <span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
=======
	<div class="col-md-6">
		<div class="card">
			<div class="row">
				<div class="col-sm-12">
					<form action="<?= (isset($process_data)) ? base_url('manufacturing/process/update') : base_url('manufacturing/process/add') ?>" method="post" class="" novalidate>
						<div class="card-header">
							<div class="card-status-top bg-blue"></div>
							<h1 class="card-title"><b> Garnu </b></h1>
						</div>
						<div class="card-body border-bottom py-3">
							<div class="row mt-1">
								<input type="hidden" name="garnu_received_id" id="" class="form-control garnu_received_id" value="<?= $data['id'] ?? null ?>">
								<input type="hidden" name="given_id" id="" class="form-control given_id" value="<?= $process_data['id'] ?? null ?>">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Name: </label>
									<input type="text" name="name" id="" class="form-control" placeholder="Enter Garnu Name" value="<?= $data['name'] ?? null ?>" autocomplete="off">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Received Quantity: </label>
									<input type="text" name="rc_qty" id="" class="form-control" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['rc_qty'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Garnu Weight: </label>
									<input type="text" name="weight" id="" class="form-control" placeholder="Enter Weight" value="<?= $data['garnu_weight'] ?? null ?>" autocomplete="off">
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
												<?= $value->name; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="workers">Workers: </label>
									<select class="form-select select2 " name="workers" id="workers">
										<option value="">Select Worker:</option>
									</select>
								</div>
>>>>>>> 92c975cdf4f5256b62fd9b7efd38dddc7a55f875

								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Remark: </label>
									<input type="text" name="remarks" id="" class="form-control" placeholder="Enter Remark" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['remarks'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Quantity: </label>
									<input type="text" name="given_qty" id="" class="form-control" placeholder="Enter Quantity" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_qty'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Given Weight: </label>
									<input type="text" name="given_weight" id="" class="form-control" placeholder="Enter Weight" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['given_weight'] : '' ?>">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Labour: </label>
									<input type="text" name="labour" id="" class="form-control" placeholder="Enter Labour" autocomplete="off" value="<?= (isset($process_data)) ? $process_data['labour'] : '' ?>">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Row Material Weight: </label>
									<input type="text" name="" id="" class="form-control" placeholder="Enter Weight" autocomplete="off" value="">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">Final Weight: </label>
									<input type="text" name="" id="" class="form-control" placeholder="Enter Weight" autocomplete="off" value="">
								</div>
								<div class="col-md-4 col-sm-3">
									<label class="form-label" for="">&nbsp </label>
									<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-usb" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary ms-auto"><?= (isset($process_data)) ? 'Update' : 'Submit' ?>
							</button>
						</div>

						<div class="modal modal-blur fade modal-lg" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Add Row Material</h5>
										<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
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
												if (empty($given_row_meterial)) {
													$given_row_meterial[] = [
														'row_material_id' => 0,
														'rmWeight'        => '',
														'rmTouch'         => 0,
														'rmQuantity'      => '',
														'id'              => 0
													];
												}
												foreach ($given_row_meterial as $row) { ?>
													<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
													<tr class="mainRow">
														<td>
															<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? "0"; ?>" />
															<select class="form-select select2 row_material" required name="row_material[]">
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
															<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? null ?>" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" name="rmWeight[]" value="<?= $row['weight'] ?? null ?>" class="form-control weight" placeholder="Enter Weight" autocomplete="off">
														</td>
														<td class="text-muted">
															<input type="number" name="rmQuantity[]" value="<?= $row['quantity'] ?? null ?>" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
														</td>
														<td>
															<button type="button" class="btn btn-danger deleteRow">X</button>
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
											<td><?= $key + 1; ?></td>
											<td><a href="<?= base_url('manufacturing/process/manage/') . $id . '/' . $result->id; ?>" class="btn btn-warning">Edit</a></td>
											<td><?= $result->creation_date; ?></td>
											<td><?= $result->process_name; ?></td>
											<td><?= $result->customer_name; ?></td>
											<td><?= $result->given_qty; ?></td>
											<td><?= $result->given_weight; ?></td>
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
		var mainRow = $('.mainRow')[0].outerHTML;
		$('.process').change(function() {
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
					success: function(data) {
						$('#workers').html(data);
					}
				});
			} else {
				$('#workers').html('<option value="">Select Workers</option>');
			}
		}).trigger('change');

		$("button[data-target='#exampleModal']").click(function(event) {
			event.preventDefault();

			var garnu_received_id = $('#garnu_received_id').val();
			var given_id = $('#given_id').val();

			if (garnu_received_id != "" && given_id != "") {}
			$("#modal-report").modal('show');
		});

		$('.addButton').click(function() {
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

		$(document).on('click', '.deleteRow', function() {
			if ($('.deleteRow').length > 1) {
				$(this).parents('tr').remove();
				console.log($(this).parents('tr'));
			}
		});

		$(document).on('click', '.save', function() {
			var count = 0;
			$('.row_material').each(function() {
				var row_material = $(this).val();
				if (row_material == "") {
					count += 1;
					SweetAlert('warning', 'Please Fill your form.');
				}
			});
			$('.touch').each(function() {
				var touch = $(this).val();
				if (touch == "") {
					count += 1;
					SweetAlert('warning', 'Please Fill your form.');
				}
			});
			(count == 0) ? $("#modal-report").modal('hide'): null;
		});

		$(document).on('input', '.touch', function() {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
		});

		$('#modal-report').on('shown.bs.modal', function(e) {
			var modal = this;
			$('.row_material').each(function() {
				$(this).select2({
					width: '200',
					dropdownParent: $(modal)
				});
			});
		});

	});
</script>
