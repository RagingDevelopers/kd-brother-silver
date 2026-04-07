<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<h1 class="card-title"><b> Customer </b></h1>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="col-md-12 mb-5 ">
					<div class="row ms-1">
						<form class="row" action="<?= (isset($update)) ? base_url("registration/customer/index/update/{$update['id']}") : base_url('registration/customer/index/store') ?>" method="post">
							<div class="row">
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Name: </label>
									<input class="form-control required " type="text" name="name" placeholder="Enter User  Name" value="<?= $update['name'] ?? null ?>" id="name" required>
								</div>
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Mobile Number: </label>
									<input class="form-control" type="number" name="mobile" placeholder="Enter Mobile Number" value="<?= $update['mobile'] ?? null ?>" id="mobile">
								</div>
								<div class="col-sm-3">
									<label class="form-label" for="prd"> City: </label>
									<select class="form-select select2 " name="city_id" id="city_id">
										<option>Select City</option>
										<?php
										$city = $this->db->get('city')->result();
										foreach ($city as $value) {
										?>
											<option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['city_id']) {
																					echo 'selected';
																				} ?>><?= $value->name; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-sm-3">
									<label class="form-label" for="prd"> Account Type: </label>
									<select class="form-select select2" name="account_type_id" id="account_type_id" required>
										<option disabled>Select Account Type</option>
										<?php
										$account_type = $this->db->get('account_type')->result();
										foreach ($account_type as $value) {
										?>
											<option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['account_type_id']) {
																					echo 'selected';
																				} ?>><?= $value->name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Opening Amount: </label>
									<input class="form-control" type="number" name="opening_amount" placeholder="Enter Opening Amount" value="<?= $update['opening_amount'] ?? 0 ?>" id="opening_amount" required>
								</div>
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Opening Amount Type: </label>
									<select class="form-select select2" id="opening_amount_type" required name="opening_amount_type">
										<option selected value="">Select Opening Amount Type</option>
										<option value="BAKI" <?php if (!empty($update) && $update['opening_amount_type'] == 'BAKI') {
																	echo 'selected';
																} ?>>Baki</option>
										<option value="JAMA" <?php if (!empty($update) && $update['opening_amount_type'] == 'JAMA') {
																	echo 'selected';
																} ?>>Jama</option>
									</select>
								</div>

								<div class="col-sm-3">
									<label class="form-label" for="prd"> Opening Fine: </label>
									<input class="form-control" type="number" name="opening_fine" step="any" placeholder="Enter Opening Fine" value="<?= $update['opening_fine'] ?? 0 ?>" id="opening_fine" required>
								</div>
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Opening Fine Type: </label>
									<select class="form-select select2" id="opening_fine_type" required name="opening_fine_type">
										<option selected value="">Select Opening Fine Type</option>
										<option value="BAKI" <?php if (!empty($update) && $update['opening_fine_type'] == 'BAKI') {
																	echo 'selected';
																} ?>>Baki</option>
										<option value="JAMA" <?php if (!empty($update) && $update['opening_fine_type'] == 'JAMA') {
																	echo 'selected';
																} ?>>Jama</option>
									</select>
								</div>
								<div class="col-sm-3 pt-3" id="processDropdown" style="display: none;">
									<label class="form-label" for="process_id"> Process: </label>
									<select class="form-select" name="process_id[]" multiple id="process_id">
										<?php
										$process = $this->db->get('process')->result();
										foreach ($process as $value) {
										?>
											<option value="<?= $value->id; ?>" <?php if (isset($update) && in_array($value->id, explode(',',$update['process_id']))) {
																					echo 'selected';
																				} ?>><?= $value->name; ?>
											</option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-3 pt-3">
									<label class="form-check">
										<input class="form-check-input" name="addItem" value="true" id="addItem" <?php if (isset($items) && !empty($items)) {
																														echo "checked";
																													} ?> type="checkbox">
										<span class="form-check-label">Add Items</span>
									</label>
								</div>
							</div>

							<div class="card mt-5" id="isChecked">
								<div class="card-header bg-light">
								</div>
								<div class="row">
									<table class="table table-borderless">
										<thead class="thead-light">
											<th scope="col">Item</th>
											<th scope="col">Touch</th>
											<th scope="col">Wastage</th>
											<th scope="col">Label</th>
											<th scope="col">Rate</th>
											<th scope="col">Subtotal</th>
											<th scope="col"></th>
										</thead>

										<tbody class="paste append-here">
											<?php
											if (empty($items)) {
												$items[] = [
													'item_id' => 0,
													'extra_touch'        => 0,
													'wastage'         => 0,
													'label'      => "",
													'rate'      => 0,
													'sub_total'      => 0,
													'id'              => 0
												];
											}
											$j = 1;
											foreach ($items as $row) { ?>
												<tr class="sectiontocopy">
													<input type="hidden" class="sdid" name="sdid[]" value="<?= $row['id'] ?? 0; ?>" />
													<td>
														<select class="form-select item_id" name="item_id[]">
															<option value="">Select Item</option>
															<?php
															$item = $this->db->get('item')->result();
															foreach ($item as $value) { ?>
																<option value="<?= $value->id; ?>" <?php if (isset($row) && !empty($row) && $value->id == $row['item_id']) {
																										echo 'selected';
																									} ?>><?= $value->name; ?></option>
															<?php } ?>
														</select>
													</td>
													<td>
														<input class="form-control extra_touch" step="any" type="number" name="extra_touch[]" placeholder="Enter Extra touch  Name" value="<?= $row['extra_touch'] ?? null ?>" id="extra_touch" required>
													</td>
													<td>
														<input class="form-control wastage" type="number" name="wastage[]" placeholder="Enter Wastage Amount" value="<?= $row['wastage'] ?? null ?>" id="wastage" required>
													</td>
													<td>
														<select class="form-select label" name="label[]">
															<option value="">Select Label</option>
															<option value="NET" <?php if (!empty($row) && $row['label'] == 'NET') {
																					echo 'selected';
																				} ?>>NET</option>
															<option value="PCS" <?php if (!empty($row) && $row['label'] == 'PCS') {
																					echo 'selected';
																				} ?>>PCS</option>
															<option value="FIXED" <?php if (!empty($row) && $row['label'] == 'FIXED') {
																						echo 'selected';
																					} ?>>FIXED</option>
															<option value="GROSS" <?php if (!empty($row) && $row['label'] == 'GROSS') {
																						echo 'selected';
																					} ?>>GROSS</option>
														</select>
													</td>
													<td>
														<input class="form-control rate" type="number" name="rate[]" placeholder="Enter Rate Amount" value="<?= $row['rate'] ?? null ?>" id="rate" required>
													</td>
													<td>
														<input class="form-control sub_total" type="number" name="sub_total[]" placeholder="Subtotal" id="sub_total" value="<?= $row['sub_total'] ?? null ?>" required readonly>
													</td>
													<td>
														<button type="button" class="btn btn-danger del">X</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td>
													<button type="button" class="btn btn-success" id="add">Add
														Row</button>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>

							<div class="row">
								<div class="col-md-5 md-ms-4">
									<label class="form-label" for="prd"> &nbsp </label>
									<input class="btn btn-primary " type="submit" value="<?= isset($update) ? "Update" : "Submit" ?>">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script class="javascript">
	// var main_row = '';
	$(document).ready(function() {
	    $('.select2').select2();
		var main_row = $(".sectiontocopy")[0]?.outerHTML;
		$(document).on('click', '#add', function() {
			var item_id = $('.item_id').last();
			if (item_id.val() == '' || item_id.val() == 0 || item_id.val() == null) {
				return item_id.select2('open');
			}

			$(".append-here").append(main_row);
			var row = $(".append-here").children("tr:last");
			row.find('.sdid,.extra_touch, .wastage,.rate,.sub_total').val(0);
			row.find('.item_id,.label').val('');
			row.find('.item_id').select2({
				placeholder: "-- Select --",
				// allowClear: true,
			});
			row.find('.label').select2({
				placeholder: "-- Select --",
				// allowClear: true,
			});
			row.find('.item_id').select2('open');
		});

		$(document).on('click', '.del', function() {
			alert_if("Confirm delete this", () => {
				var item = $(".item_id").length;
				if (item > 1) {
					$(this).parent().parent().remove();
				}
			});
		});

		if ($('#addItem').is(':checked')) {
			$('#isChecked').show();
		} else {
			$('#isChecked').hide();
		}

		$(document).on("click", "#addItem", function() {
			if (this.checked) {
				$('#isChecked').show();
				$('.item_id').select2();
				$('.label').select2();
			} else {
				$('#isChecked').hide();
			}
		});

		$(document).on("change", ".item_id", function() {
			var currentItem = $(this);
			var selectedItem = currentItem.val();
			$(".item_id").not(currentItem).each(function() {
				if ($(this).val() === selectedItem) {
					SweetAlert("error", "This Item is already Selected, please try again");
					currentItem.val(null).trigger('change').select2('open');
					return false;
				}
			});
		});

		$(document).on('keyup', '.extra_touch,.wastage', function() {
			var $row = $(this).closest('tr');
			var extra_touch = parseFloat($row.find('.extra_touch').val()) || 0;
			var wastage = parseFloat($row.find('.wastage').val()) || 0;

			var sum = extra_touch + wastage;
			$row.find('.sub_total').val(sum);
		});

		function processType() {
			if ($('#account_type_id').val() == 2) {
				$('#process_id').select2();
				$("#processDropdown").show();
			} else {
				$("#processDropdown").hide();
			}
		}
		processType();
		$("#account_type_id").change(function() {
			processType();
		});

		$(document)
		.on(
			"focus",
			".extra_touch,.wastage,.rate,.sub_total",
			function () {
				handleInputFocusAndBlur(this, "focus");
			}
		)
		.on(
			"blur",
			".extra_touch,.wastage,.rate,.sub_total",
			function () {
				handleInputFocusAndBlur(this, "blur");
			}
		);

		function handleInputFocusAndBlur(element, eventType) {
			var $element = $(element);
			if (
				(eventType === "focus" && $element.val() == "0") ||
				$element.val() == "0.00"
			) {
				$element.val("");
			} else if (eventType === "blur" && $element.val() == "") {
				$element.val("0");
			}
		}

	});
</script>
