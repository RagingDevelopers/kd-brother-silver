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

	.select2 {
		width: 160px !important;
	}

	.inputBox {
		width: 100px !important;
	}

	.readonly {
		background-color: #ebebeb;
		color: black;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<form action="<?= (isset($update)) ? base_url("sales/update/{$update['id']}") : base_url('sales/store') ?>" method="post" class="main-form" novalidate>
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
									<input type="date" name="date" class="form-control" id="date" value="<?php echo date('Y-m-d'); ?>" />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Party<span class="text-danger">*</span></label>
									<select name="party_id" class="form-select select2" id="party_id">
										<option value="">Select Customer</option>
										<?php foreach ($party as $c) { ?>
											<option value="<?= $c['id']; ?>">
												<?= $c['name']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="card mt-5">
								<div class="row">
									<div class="table-responsive">
										<table class="table card-table table-vcenter text-center text-nowrap">
											<thead>
												<tr>
													<th>Item</th>
													<th>Stamp</th>
													<th>Unit</th>
													<th>Remarks</th>
													<th>Gross Weight</th>
													<th>Less Weight</th>
													<th>Net Weight</th>
													<th>Touch</th>
													<th>Wastage</th>
													<th>Fine</th>
													<th>Piece</th>
													<th>Labour</th>
													<th>Rate</th>
													<th>Sub Total</th>
													<th></th>
												</tr>
											</thead>

											<tbody class="paste append-here">
												<?php
												if (empty($items)) {
													$items[] = [
														'item'               => 0,
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
														'rate'        		 => 0,
														'sub_total'   		 => 0,
														'id'          		 => 0
													];
												}
												foreach ($items as $row) { ?>
													<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
													<tr class="main-row">
														<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? null; ?>" />
														<td>
															<select class="form-select select2 item" required name="item[]">
																<option value="">Select Item</option>
																<?php foreach ($item as $i) { ?>
																	<option value="<?= $i['id']; ?>" <?php if (isset($row) && $i['id'] == $row['item']) {
																											echo 'selected';
																										} ?>> <?= $i['name']; ?>
																	</option>
																<?php } ?>
															</select>
														</td>
														<td><select name="stamp[]" id="" class="form-control select2 stamp">
																<option value="">Select Stamp</option>
																<?php foreach ($stamp as $s) { ?>
																	<option value="<?= $s['id']; ?>" <?php if (isset($row) && $s['id'] == $row['stamp']) {
																											echo 'selected';
																										} ?>> <?= $s['name']; ?> </option>
																<?php } ?>
															</select></td>
														<td><select name="unit[]" id="" class="form-control select2 unit">
																<option value="">Select Unit</option>
																<?php foreach ($unit as $u) { ?>
																	<option value="<?= $u['id']; ?>" <?php if (isset($row) && $u['id'] == $row['unit']) {
																											echo 'selected';
																										} ?>><?= $u['name']; ?></option>
																<?php } ?>
															</select></td>
														<td><input type="text" class="form-control remark inputBox" name="remark[]" placeholder="Remark" value="<?= $row['remark'] ?? null ?>"></td>
														<td>
															<input type="text" class="form-control gross_weight inputBox" name="gross_weight[]" placeholder="Gross Weight" value="<?= $row['gross_weight'] ?? null ?>">
														</td>
														<td>
															<input type="hidden" name="raw-material-data[]" value="<?= $row['raw_material_string'] ?? null; ?>" class="form-control rmdata" placeholder="Enter Weight" autocomplete="off">
															<div class="d-flex gap-2">
																<input type="text" class="form-control inputBox less_weight readonly" name="less_weight[]" readonly placeholder="Less Weight" value="<?= $row['less_weight'] ?? null ?>">
																<button type="button" class="bg-danger btn btn-action text-danger-fg me-2 Receivedmaterial" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Received">
																	<i class="fa-solid fa-hashtag"></i>
																</button>
															</div>
														</td>
														<td><input type="text" class="form-control inputBox net_weight readonly" name="net_weight[]" readonly placeholder="Net Weight" value="<?= $row['net_weight'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox touchData" name="touch[]" placeholder="Touch" value="<?= $row['touch'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox wastage" name="wastage[]" placeholder="Wastage" value="<?= $row['wastage'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox fine readonly" name="fine[]" readonly placeholder="Fine" value="<?= $row['fine'] ?? null ?>"></td>
														<td><input type="text" class="form-control inputBox piece" name="piece[]" placeholder="Piece" value="<?= $row['piece'] ?? null ?>"></td>
														<td><select name="labour[]" id="" class="form-control select2 labour">
																<option value="">Select Labour</option>
																<option value="net">Net</option>
																<option value="pcs">Pcs</option>
																<option value="fixed">Fixed</option>
																<option value="gross">Gross</option>
															</select></td>
														<td><input type="text" class="form-control rate inputBox" name="rate[]" placeholder="Rate" value="<?= $row['rate'] ?? null ?>"></td>
														<td><input type="text" class="form-control sub_total inputBox" name="sub_total[]" placeholder="Sub Total" value="<?= $row['sub_total'] ?? null ?>"></td>
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
													<td></td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 TotalRate'>0</span></h4>
														</div>
													</td>
													<td>
														<div class="d-flex">
															<h4><span class='text-end ms-3 Sub_total'>0</span></h4>
														</div>
													</td>
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
																					} ?>><?= $rm['name']; ?>
												</option>
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

<script class="javascript">
	// var main_row = '';
	var mainFunction = (function() {
		let main = {
			isInit: false,
			baseUrl: "<?= base_url() ?>",
			datatable: null,
			mainRow: null,
			mainRmRow: null,
			init: function() {
				try {
					if (!this.isInit) {
						this.isInit = true;
						mainFunction = null;
						console.log("main Function Enabled")
						if (this.isInit) {
							main.ready.call();
						}
					}
				} catch (error) {
					console.log(`mainFunction in isInit error : ${error}`);
				}
			},
			select2: (el, config = {}) => {
				Object.assign(config, {
					placeholder: "-- Select --",
					allowClear: true,
					width: '500px',
				});
				if (!$(el).data('select2')) {
					$(el).select2(config);
				}
				return $(el)
			},
			lastTr: $('.append-here tr').last(),
			ready: function() {
				main.mainRow = $(".main-row")[0].outerHTML;
				main.mainRmRow = $('.mainRow')[0]?.outerHTML;
				$(document).ready(function() {
					$('.item').each(function() {
						main.select2(this)
					});
					$('.stamp').each(function() {
						main.select2(this)
					});
					$('.unit').each(function() {
						main.select2(this)
					});
					$('.labour').each(function() {
						main.select2(this)
					});
					$(this).on('click', "#add", function() {
						if ($('.item').last().val() == '') {
							return $('.item').last().select2('open');
						}
						if ($('.stamp').last().val() == '') {
							return $('.stamp').last().select2('open');
						}
						if ($('.unit').last().val() == '') {
							return $('.unit').last().select2('open');
						}
						if ($('.labour').last().val() == '') {
							return $('.labour').last().select2('open');
						}
						$(".append-here").append(main.mainRow);
						const lastTr = $('.append-here tr').last();
						lastTr.find('.remark').val("");
						lastTr.find('.rowid,.raw_material_string,.gross_weight,.less_weight,.net_weight,.touchData,.wastage,.piece,.labour,.rate,.sub_total').val(0);
						main.select2(lastTr.find('.item')).select2('open');
						main.select2(lastTr.find('.stamp'));
						main.select2(lastTr.find('.unit'));
						main.select2(lastTr.find('.labour'));

						var modalBody = $('table .append-here');
						scrollEventRight(modalBody, 1000);
					});
					$(this).on('click', '.remove-btn', function(e) {
						var $this = this;
						var metal_type = $(".item").length;
						if (metal_type > 1) {
							alert_if("Confirm delete this", () => {
								$(this).parent().parent().remove(),
									main.calculation($this);
							})
						}
					});
					$('.main-form').submit(function(e) {
						e.preventDefault();
						main.validateSubmit(this)
					});

					function scrollEvent(target, pixel = 500) {
						var animated = target.animate({
							scrollTop: target.prop('scrollHeight')
						}, pixel);
					}

					function scrollEventRight(target, duration = 1000) {
						var $target = $(target);
						var scrollPosition = $target.prop('scrollWidth') - $target.innerWidth();
						$target.animate({
							scrollLeft: scrollPosition
						}, duration);
					}

					$(this).on('click', '.Receivedmaterial', function() {
						rmBtn = $(this);
						var modal = $("#received-report");
						var givenContainer = rmBtn.parents('tr');
						var mainSection = modal.find(".mainRow");
						modal.find("tbody").html("");
						var string = givenContainer.find(".rmdata").val();
						var data = string?.split("|");
						mainSectionLength = data?.length ?? 0;
						if (mainSectionLength > 0) {
							for (var i = 0; i < mainSectionLength; i++) {
								modal.find("tbody").append(main.mainRmRow);
								var row = modal.find(".mainRow").eq(i),
									splitByHash = data[i]?.split(","),
									row_material = splitByHash[0] ?? 0,
									quantity = splitByHash[1] ?? 0;
								rmrate = splitByHash[2] ?? 0,
									rmsub_total = splitByHash[3] ?? 0;
								received_detail_id = splitByHash[4] ?? 0;
								row.find(".row_material").val(row_material).trigger("change");;
								(row.find(".quantity").val(quantity));
								(row.find(".rmrate").val(rmrate));
								(row.find(".rmsub_total").val(rmsub_total));
								(row.find(".received_detail_id").val(received_detail_id));
								row.find('.row_material').select2({
									width: '200',
									dropdownParent: $('#received-report')
								});
								main.RmcalculateMain(rmBtn);
								// main.select2(row.find(".row_material"));
							}
						} else {
							modal.find("tbody").append(main.mainRmRow);
						}
						modal.modal("show");
					});

					$('#received-report').on('shown.bs.modal', function(e) {
						var lastrm = $('.row_material').last().val();
						if (lastrm == "") {
							$('.row_material').last().select2('open');
						}
						var lastquantity = $('.quantity').last().val();
						if (lastquantity == 0) {
							$('.quantity').last().focus();
						}
						var lastrmrate = $('.rmrate').last().val();
						if (lastrmrate == 0) {
							$('.rmrate').last().focus();
						}
					});

					function handleInputFocusAndBlur(element, eventType) {
						var $element = $(element);
						if (eventType === 'focus' && $element.val() == '0') {
							$element.val('');
						} else if (eventType === 'blur' && $element.val() == '') {
							$element.val('0');
						}
					}

					$(document).on('focus', '.gross_weight, .touchData, .wastage, .quantity, .rmrate', function() {
						main.handleInputFocusAndBlur(this, 'focus');
					}).on('blur', '.gross_weight, .touchData, .wastage, .quantity, .rmrate', function() {
						main.handleInputFocusAndBlur(this, 'blur');
					});


					$('.addButton2').click(function() {
						var LastRm = $('.row_material').last();
						if (LastRm.val() == '') {
							return LastRm.select2('open');
						}
						$('#JBody').append(main.mainRmRow);
						const lastTr = $('#JBody tr').last();
						// lastTr.find('.rowid').val(0);
						lastTr.find('.quantity,.rmrate,.rmsub_total').val(0);
						lastTr.find('.row_material').select2({
							width: '200',
							dropdownParent: $('#received-report')
						});
						lastTr.find('.row_material').last().select2('open');
						var modalBody = $('#received-report .modal-body');
						scrollEvent(modalBody, 550);
					});

					$(this).on('click', '.deleteRow2', function() {
						if ($('.deleteRow2').length > 1) {
							$(this).parents('tr').remove();
						}
					});

					$(this).on('click', '.saveRmData', function() {
						var count = 0;
						$('.row_material').each(function() {
							var row_material = $(this).val();
							if (row_material == 0 || row_material == "") {
								count += 1;
								$(this).select2('open');
								SweetAlert('warning', 'Please fill all rows.');
							}
						});
						$('.rmrate').each(function() {
							var rmrate = $(this).val();
							if (rmrate == 0 || rmrate == "") {
								count += 1;
								$(this).focus();
								SweetAlert('warning', 'Please fill all rows.');
							}
						});
						$('.quantity').each(function() {
							var quantity = $(this).val();
							if (quantity == 0 || quantity == "") {
								count += 1;
								$(this).focus();
								SweetAlert('warning', 'Please fill all rows.');
							}
						});
						(count == 0) ? $("#received-report").modal('hide'): null;

						var modal = $('#received-report');
						var container = rmBtn.parents('tr');
						var mainSection = modal.find(".mainRow");
						var mainSectionLength = modal.find('tbody tr').length;
						let FilterVar = (el) => {
							if (el == "" || el == undefined || el == NaN) {
								return 0;
							}
							return el;
						};
						var string = "";
						var totalRmSub_total = 0;
						for (var i = 0; i < mainSectionLength; i++) {
							var row = mainSection.eq(i);
							var rm = row.find(".row_material option:selected").val();
							var quantity = FilterVar(row.find(".quantity").val());
							var rmrate = FilterVar(row.find(".rmrate").val());
							var rmsub_total = FilterVar(row.find(".rmsub_total").val());
							var received_detail_id = FilterVar(row.find(".received_detail_id").val());
							totalRmSub_total += parseFloat(rmsub_total) || 0;
							string += [rm, quantity, rmrate, rmsub_total, received_detail_id].join(",");
							if (mainSectionLength > i + 1)
								string += "|";
						}
						container.find(".rmdata").val(string);
						container.find(".less_weight").val(totalRmSub_total / 1000);
						// finalCalculation(rmBtn);
						main.calculateMain(rmBtn);
						main.Totalcalculate();
					});

					$(this).on('input', '.touch', function() {
						var touch = $(this);
						if (touch.val() > 100) {
							SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
						}
					});

					$(this).on('input', '.gross_weight,.touchData,.wastage,.piece,.rate,.sub_total', function() {
						main.calculateMain($(this));
						main.Totalcalculate();
					});

					$(this).on('input', '.quantity,.rmrate', function() {
						main.RmcalculateMain($(this));
					});
				});
			},

			calculateMain: function(ref) {
				var container = ref.parents('tr');
				var gross_weight = container.find(".gross_weight").val() || 0;
				var less_weight = container.find(".less_weight").val() || 0;
				container.find(".net_weight").val(parseFloat(gross_weight) - parseFloat(less_weight));

				var net_weight = container.find(".net_weight").val() || 0;
				var touch = container.find(".touchData").val() || 0;
				var wastage = container.find(".wastage").val() || 0;

				var fine = parseFloat(touch) + parseFloat(wastage);
				container.find(".fine").val(fine * parseFloat(net_weight) / 100);
				main.Totalcalculate();
			},

			RmcalculateMain: function(ref) {
				var Totalquantity = 0;
				var Totalrmrate = 0;
				var Totalrmsub_total = 0;

				var container = ref.parents('tr');
				var quantity = container.find(".quantity").val() || 0;
				var rmrate = container.find(".rmrate").val() || 0;
				container.find(".rmsub_total").val(parseFloat(quantity) * parseFloat(rmrate));

				$('.quantity').each(function() {
					Totalquantity += parseFloat($(this).val() || 0);
				});
				$('.rmrate').each(function() {
					Totalrmrate += parseFloat($(this).val() || 0);
				});
				$('.rmsub_total').each(function() {
					Totalrmsub_total += parseFloat($(this).val() || 0);
				});

				$('.total-qty').text(Totalquantity);
				$('.total-rmrate').text(Totalrmrate);
				$('.total-rmsub_total').text(Totalrmsub_total);
			},

			Totalcalculate: function() {
				var gross_weight = 0;
				var less_weight = 0;
				var net_weight = 0;
				var touchData = 0;
				var wastage = 0;
				var Fine = 0;
				var piece = 0;
				var rate = 0;
				var sub_total = 0;

				$('.gross_weight').each(function() {
					gross_weight += parseFloat($(this).val() || 0);
				});
				$('.less_weight').each(function() {
					less_weight += parseFloat($(this).val() || 0);
				});
				$('.net_weight').each(function() {
					net_weight += parseFloat($(this).val() || 0);
				});
				$('.touchData').each(function() {
					touchData += parseFloat($(this).val() || 0);
				});
				$('.wastage').each(function() {
					wastage += parseFloat($(this).val() || 0);
				});
				$('.fine').each(function() {
					Fine += parseFloat($(this).val() || 0);
				});
				$('.piece').each(function() {
					piece += parseFloat($(this).val() || 0);
				});
				$('.rate').each(function() {
					rate += parseFloat($(this).val() || 0);
				});
				$('.sub_total').each(function() {
					sub_total += parseFloat($(this).val() || 0);
				});

				$('.TotalGross_weight').text("");
				$('.TotalGross_weight').text(gross_weight);
				$('.TotalLess_weight').text("");
				$('.TotalLess_weight').text(less_weight);
				$('.TotalNet_weight').text("");
				$('.TotalNet_weight').text(net_weight);
				$('.TotalTouchData').text("");
				$('.TotalTouchData').text(touchData);
				$('.TotalWastage').text("");
				$('.TotalWastage').text(wastage);
				$('.TotalFine').text("");
				$('.TotalFine').text(Fine);
				$('.TotalPiece').text("");
				$('.TotalPiece').text(piece);
				$('.TotalRate').text("");
				$('.TotalRate').text(rate);
				$('.Sub_total').text("");
				$('.Sub_total').text(sub_total);
			},

			handleInputFocusAndBlur: function(element, eventType) {
				var $element = $(element);
				if (eventType === 'focus' && $element.val() == '0') {
					$element.val('');
				} else if (eventType === 'blur' && $element.val() == '') {
					$element.val('0');
				}
			},
			validateSubmit: function(ref) {
				$(ref).off('submit').submit();
			}

		};

		return {
			init: main.init
		}
	})();
	mainFunction.init.call();
</script>