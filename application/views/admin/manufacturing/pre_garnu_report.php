<style>
	.net_weight {
		background-color: #ebebeb;
		color: black;
	}

	#garnu,
	#garnu th,
	#garnu td {
		font-weight: bold;
		/* Make text bold */
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>

				<form id="garnu_receive">
					<div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-md-3">
										<p class="modal-title">Garnu Receive </p>
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
									<input type="hidden" class="garnu_id" name="garnu_id" />
									<table class="table card-table table-vcenter text-center text-nowrap ">
										<thead class="thead-light">
											<th>Metal Type</th>
											<th>Lot</th>
											<th scope="col">Weight(Kg)</th>
											<th scope="col">Touch (%)</th>
											<th scope="col">Net Weight</th>
											<th scope="col"></th>
										</thead>

										<tbody class="paste append-here">
											<input type="hidden" class="ids" name="ids[]" />
											<tr class="sectiontocopy">
												<input type="hidden" class="sdid" name="sdid[]" />
												<td>
													<select class="form-select select2 metal_type_id" name="metal_type_id[]">
														<option value="">Select Metal</option>
														<?php
														$item = $this->db->get('item')->result();
														foreach ($item as $value) { ?>
															<option value="<?= $value->id; ?>">
																<?= $value->name; ?>
															</option>
														<?php } ?>
													</select>
												</td>
												<td>
													<select class="form-select select2 lotWiseRm" name="lot[]" data-selected-id="">
													</select>
												</td>

												<td>
													<input class="form-control weight" step="any" type="number" name="weight[]" placeholder="Enter Weight" value="0" required>
												</td>
												<td>
													<input class="form-control touch" type="number" name="touch[]" step="any" placeholder="Enter touch(%)" value="0" required>
												</td>
												<td>
													<input class="form-control net_weight" type="number" step="any" value="0" readonly name="net_weight[]">
												</td>
												<td>
													<button type="button" class="btn btn-danger del">X</button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td>
													<h3>Total :</h3>
												</td>
												<td></td>
												<td>
													<div class="d-flex">
														<h4><span class='text-end ms-3 total-weight'>0</span></h4>
													</div>
												</td>
												<td>
													<div class="d-flex">
														<h4><span class='text-end ms-3 total-touch'>0</span></h4>
													</div>
												</td>
												<td>
													<div class="d-flex">
														<h4><span class='text-end ms-3 total-net_weight'>0</span></h4>
													</div>
												</td>
												<td></td>
											</tr>
											<tr>
												<td colspan="1" id="jama_baki"></td>
												<td colspan="1">
													<label class="form-check">
														<input class="form-check-input" id="is_kasar" name="is_kasar" type="checkbox">
														<h4 class="form-check-label is_kasar">Is Kasar</h4>
													</label>
												</td>
												<td colspan="1">
													<div class="parent-div-party" style="display:none">
														<?php $this->db->select('*');
														$this->db->from('customer');
														$party =  $this->db->get()->result_array();  ?>
														<select name="transfer_account" class="form-select col-md-2 col-sm-3" id="select-account">
															<option value="">Select Worker</option>
															<?php
															if (!empty($party)) {
																foreach ($party as $row) { ?>
																	<option value="<?= $row['id']; ?>"> <?= $row['name']; ?> </option>
															<?php }
															} ?>
														</select>
													</div>
												</td>
												<td></td>
												<td></td>
												<td></td>
												<input type="hidden" name="jama_baki" value="" class="jama_baki">
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-success btn-success" id="add">
										<span class="mx-1">Add Row</span><i class="fa-solid fa-plus"></i>
									</button>
									<div>
										<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
										<button type="submit" class="input-icon btn btn-primary btn-primary submitBtn" id="submitBtn">Save Changes
											<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
										</button>
										<!-- <button type="submit" class="btn btn-primary btn-primary submitBtn" >Save Changes</button> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>

				<div class="col-sm-11">
					<h1 class="card-title"><b> Garnu Report </b></h1>
				</div>
				<div class="col-sm-1 ms-5 ps-5">
					<a class="btn btn-action bg-primary text-white" href="<?= base_url('manufacturing/pre_garnu/index/add') ?>">
						<i class="far fa-plus card-title" aria-hidden="true">
						</i>
					</a>
				</div>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" value="<?= date('Y-m-01'); ?>" id="fromdate" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
							<div class="col-sm-2">
								<label>Received:</label> <br>
								<select class="form-select select2" id="received">
									<option value="">All</option>
									<option value="YES">YES</option>
									<option value="NO">NO</option>
								</select>
							</div>
						</div>
						<div class="mt-3">
							<table id="garnu" class="table table-vcenter card-table" style="width:100% !important;">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Action</th>
										<th scope="col">Name</th>
										<th scope="col">Weight</th>
										<!--<th scope="col">Weight</th>-->
										<th scope="col">Touch</th>
										<th scope="col">Fine</th>
										<th scope="col">Last Process</th>
										<th scope="col">Worker Name</th>
										<th scope="col">Received</th>
										<th scope="col">Created At</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td class="text-center text-primary" colspan="3">
											<h3 class="blinking-text">Totals ==></h3>
										</td>
										<td></td>
										<td></td>
										<td> -- </td>
										<td> -- </td>
										<td> -- </td>
										<td> -- </td>
										<td> -- </td>
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
<script class="javascript">
	var main_row = '';
	$(document).ready(function() {
		main_row = $(".sectiontocopy")[0].outerHTML;
		var garnuTouch = "";
		var garnuWeight = "";
		var garnuName = "";
		var trRef = null;

		$('#ReceivedModel').on('shown.bs.modal', function(e) {
			var modal = this;
			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $(modal)
				});
			});
			$('.lotWiseRm').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $(modal)
				});
			});

			var LastRm = $('.metal_type_id').last();
			if (LastRm.val() == '' || LastRm.val() == 0 || LastRm.val() == null) {
				return LastRm.select2('open');
			}
		});

		var table = $('#garnu').DataTable({
			"iDisplayLength": 10,
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000, 5000],
				[5, 10, 25, 50, 100, 500, 1000, 5000]
			],
			'processing': true,
			'serverSide': true,
			'destroy': true,
			'serverMethod': 'post',
			'searching': true,
			"ajax": {
				'showLoader': true,
				'url': "<?= base_url(); ?>manufacturing/pre_garnu/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.received = $('#received').val();
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'action'
				},
				{
					data: 'name'
				},
				{
					data: 'garnu_weight'
				},
				// {
				// 	data: 'weight'
				// },
				{
					data: 'touch'
				},
				{
					data: 'fine'
				},
				{
					data: 'process_name'
				},
				{
					data: 'worker_name'
				},
				{
					data: 'recieved'
				},
				{
					data: 'created_at'
				},
			],
			footerCallback: function(row, data, start, end, display) {
				handelFooterTotal(
					this.api(),
					[3, 4, 5]
				);
			},
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
			"rowCallback": function(row, data) {
				if (data.is_recieved == 'YES') {
					$(row).css('color', 'green');
				} else if (data.is_recieved == 'NO') {
					$(row).css('color', 'red');
				}
			}
		});
		$('#todate').on('change', function() {
			table.clear()
			table.draw()
		});
		$('#fromdate,#received').on('change', function() {
			table.clear()
			table.draw()
		});

		$(document).on('input', '.touch,.weight', function() {
			RmcalculateMain();
		});

		function loadLotOptions(metal_type_id = null, ref, selected_id = null) {
			var lotWiseRmEl = ref.parents('tr').find(".lotWiseRm");
			lotWiseRmEl.html("");
			if (!metal_type_id) {
				lotWiseRmEl.html('<option value="">Select</option>').trigger('change');
				return;
			}

			$.ajax({
				type: "POST",
				showloader: true,
				dataType: "json",
				url: `${BaseUrl}manufacturing/main_garnu/getStockTouch`,
				data: {
					metal_type_id,
					lot_wise_rm_id: selected_id,
				},
				success: function(response) {
					if (response.success) {
						var lotOptions = getLotOptions(response.data, selected_id);
						if (lotWiseRmEl.data('select2')) {
							lotWiseRmEl.select2('destroy');
						}
						lotWiseRmEl.html(lotOptions);
						lotWiseRmEl.attr('data-selected-id', selected_id || '').data('selected-id', selected_id || '');
						lotWiseRmEl.select2({
							width: '100%',
							dropdownParent: $('#ReceivedModel')
						});
						if (selected_id == null || selected_id === '') {
							lotWiseRmEl.select2("open");
						}
					} else {
						lotWiseRmEl.html('<option value="">Select</option>');
						lotWiseRmEl.select2({
							width: '100%',
							dropdownParent: $('#ReceivedModel')
						});
						SweetAlert('warning', response.message);
					}
				},
			});
		}

		function getLotOptions(response, selected_id = null) {
			var options = `<option value="">Select</option>`;
			$.each(response, function(key, value) {
				if (!value || value.id === undefined || value.id === null) {
					return;
				}
				var id = value.id ?? '';
				var touch = value.touch ?? 0;
				var remWeight = value.rem_weight ?? 0;
				var remQuantity = value.rem_quantity ?? 0;
				var code = value.code || "";
				var option = id + " - " + code + " Weight: " + remWeight + " Touch: " + touch + " Quantity: " + remQuantity;
				var selected = selected_id != null && String(selected_id) === String(id) ? "selected" : "";
				options += `<option value="${id}" ${selected} data-touch="${touch}" data-weight="${remWeight}" data-quantity="${remQuantity}">${option}</option>`;
			});
			return options;
		}

		function RmcalculateMain() {
			var Totaltouch = 0;
			var Totalweight = 0;
			var Totalnet_weight = 0;

			$('.weight').each(function() {
				Totalweight += parseFloat($(this).val() || 0);
			});
			$('.touch').each(function() {
				Totaltouch += parseFloat($(this).val() || 0);
			});
			$('.net_weight').each(function() {
				Totalnet_weight += parseFloat($(this).val() || 0);
			});

			$('.total-touch').text("");
			$('.total-weight').text("");
			$('.total-net_weight').text("");

			$('.total-touch').text(formatNumber(Totaltouch));
			$('.total-weight').text(formatNumber(Totalweight));
			$('.total-net_weight').text(formatNumber(Totalnet_weight));


			var Total = $(".garnu_weight").text() - Totalweight;
			let formattedTotal = formatNumber(Total);
			var jamaBaki = "";
			if (formattedTotal > 0) {
				jamaBaki = `<h4 class='text-danger'>ધટાડો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			} else if (formattedTotal == 0) {
				jamaBaki = `<h4 class='text-success'>સરભર :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			} else {
				jamaBaki = `<h4 class='text-success'>વધારો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
			}
			$(".jama_baki").val(formattedTotal);
			$("#jama_baki").html("");
			$("#jama_baki").html(jamaBaki);
		}

		function receiveGarnu(id = null) {
			return $.ajax({
				showLoader: true,
				url: '<?= base_url("manufacturing/pre_garnu/checkReceive"); ?>',
				type: 'POST',
				dataType: 'json',
				data: {
					id
				},
				success: function(response) {
					if (response.success) {
						$('.garnu_id').val("");
						$('.garnu_id').val(id);

						garnuTouch = response.garnuData.touch;
						garnuName = response.garnuData.name;
						garnuWeight = response.garnuData.garnu_weight;
						garnukasar = response.garnuData.is_kasar;
						garnuaccount = response.garnuData.transfer_account;

						if(garnukasar == 'YES'){
							isKasar($(":checked"));
						}else{
							isKasar($(":not(:checked)"));
						}
						$('#select-account')
									.val(garnuaccount)
									.trigger('change')
									.select2({
										width: '100%',
										dropdownParent: $('#ReceivedModel')
									});
						$('.garnu_weight').text("");
						$('.garnu_name').text("");
						$('.garnu_weight').text(garnuWeight);
						$('.garnu_name').text(garnuName);
						if (response.data != "") {
							$(response.data).each(function(index, value) {
								var net_weight = (value.net_weight != 0) ? value.net_weight : value.touch * value.weight / 100;
								var metal_type_id = (value.metal_type_id) ? value.metal_type_id : "0";
								var touch = (value.touch) ? value.touch : garnuTouch;
								var lot = (value.lot) ? value.lot : "";

								var $lastRow;
								if (index == 0) {
									$lastRow = $('.append-here tr').last();
									$lastRow.parent().find('.ids').val(value.id);
								} else {
									$(".append-here").append(main_row);
									$lastRow = $('.append-here tr').last();
									$lastRow.parent().append('<input type="hidden" class="ids" name="ids[]" value="' + value.id + '" />');
								}

								$lastRow.find('.sdid').val(value.id);
								$lastRow.find('.touch').val(touch).trigger('change');
								$lastRow.find('.weight').val(value.weight).trigger('change');
								$lastRow.find('.net_weight').val(net_weight).trigger('change');
								$lastRow.find('.metal_type_id')
									.val(metal_type_id)
									.select2({
										width: '100%',
										dropdownParent: $('#ReceivedModel')
									})
									.trigger('change.select2');
								$lastRow.find('.lotWiseRm').attr('data-selected-id', lot).data('selected-id', lot);
								loadLotOptions(metal_type_id, $lastRow.find('.metal_type_id'), lot);

								var LastRm = $lastRow.find('.metal_type_id');
								if (LastRm.val() == " " || LastRm.val() == '0' || LastRm.val() == null) {
									return LastRm.select2('open');
								}
							});
						} else {
							$('.touch').last().val(garnuTouch);
						}
					}
				},
				error: function() {
					alert("An error occurred.");
				}
			});
		}

		function scrollEvent(target, pixel = 500) {
			var animated = target.animate({
				scrollTop: target.prop('scrollHeight')
			}, pixel);
		}

		$(document).on('click', '.receive-btn', function() {
			trRef = $(this);
			var id = $(this).data('receiveid');
			$(".sectiontocopy").not(':first').remove();
			$(".ids").not(':first').remove();
			$(".ids").val(0);
			$('.garnu_id').val("");
			$('.garnu_id').val(id);
			$('.append-here tr').first().find('.sdid,.weight,.net_weight').val(0);
			$('.append-here tr').first().find('.metal_type_id').val('');
			$('.append-here tr').first().find('.lotWiseRm').html('<option value="">Select</option>').attr('data-selected-id', '').data('selected-id', '');
			$('.append-here tr').first().find('.metal_type_id').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});
			$('.append-here tr').first().find('.lotWiseRm').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});

			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $('#ReceivedModel')
				});
			});
			$('.lotWiseRm').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $('#ReceivedModel')
				});
			});

			receiveGarnu(id).done(function() {
				$("#ReceivedModel").modal('show');
				RmcalculateMain();
			});
		});

		$("#add").click(function() {
			var metal = $('.metal_type_id ').last();
			if (metal.val() == '') {
				return metal.select2('open');
			}
			var lotWiseRm = $('.lotWiseRm').last();
			// if (lotWiseRm.val() == '') {
			// 	return lotWiseRm.select2('open');
			// }

			$(".append-here").append(main_row);
			$('.append-here tr').last().find('.sdid,.weight,.net_weight').val(0);
			$('.append-here tr').last().find('.touch').val(garnuTouch);
			$('.append-here tr').last().find('.metal_type_id').val('');
			$('.append-here tr').last().find('.lotWiseRm').html('<option value="">Select</option>').attr('data-selected-id', '').data('selected-id', '');
			$('.append-here tr').last().find('.metal_type_id').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});
			$('.append-here tr').last().find('.lotWiseRm').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});

			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $('#ReceivedModel')
				});
			});
			$('.metal_type_id ').last().select2('open');

			var modalBody = $('#ReceivedModel .modal-body');
			scrollEvent(modalBody, 550);
			RmcalculateMain();
		});

		$(document).on('change', '.metal_type_id', function() {
			var ref = $(this);
			loadLotOptions(ref.val(), ref);
		});

		$(document).on('change', '.lotWiseRm', function() {
			var ref = $(this);
			var selectedOption = ref.find('option:selected');
			var touch = parseFloat(selectedOption.data('touch') || garnuTouch || 0);
			var weight = parseFloat(selectedOption.data('weight') || 0);
			var row = ref.parents('tr');
			row.find('.touch').val(touch).trigger('input');
			row.find('.weight').val(weight).trigger('input');
			RmcalculateMain();
		});

		$(document).on('click', '.del', function() {
			var metal_type_id = $(".metal_type_id").length;
			if (metal_type_id > 1) {
				$(this).parent().parent().remove();
			}
			RmcalculateMain();
		});

		$(document).on('input', '.touch', function() {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
			var weight = touch.parent().siblings().find('.weight').val();
			var net_weight = weight * touch.val();
			touch.parent().siblings().find('.net_weight').val(formatNumber(net_weight / 100));
			RmcalculateMain();
		});

		$(document).on('input', '.weight', function() {
			var weight = $(this);
			var touch = weight.parent().siblings().find('.touch').val();
			var net_weight = touch * weight.val();
			weight.parent().siblings().find('.net_weight').val(formatNumber(net_weight / 100));
			RmcalculateMain();
		});

		$(document).on('focus', '.touch,.weight,.quantity,.Pcs,.receivedWeight,.touch2, .weight2, .quantity2', function() {
			handleInputFocusAndBlur(this, 'focus');
		}).on('blur', '.touch,.weight,.quantity,.Pcs,.receivedWeight,.touch2, .weight2, .quantity2', function() {
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

		$('#garnu_receive').on('submit', function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			var self = $(this);
			$.ajax({
				url: '<?php echo base_url('manufacturing/pre_garnu/receive'); ?>',
				type: 'POST',
				data: formData,
				beforeSend: (data) => {
					self.find('.submitBtn span').show();
					// ShowBlockUi('#ReceivedModel');
				},
				success: function(response) {
					var response = JSON.parse(response);
					if (response.success === true) {
						var tr = trRef.parents('tr');
						tr.css('color', 'green');
						tr.find('.d-flex a').removeClass("d-none");
						tr.find('.badge').removeClass("bg-danger");
						tr.find('.badge').addClass('bg-indigo');
						tr.find('.badge').text('Yes');
						$('#ReceivedModel').modal('hide');
						SweetAlert('success', response.message);
					} else {
						$('#ReceivedModel').modal('hide');
						SweetAlert('error', response.message);
					}
				},
				error: function() {
					SweetAlert('error', "Error submitting form");
				},
				complete: () => {
					self.find('.submitBtn span').hide();
				}
			});
		});

		$(document).on('click', '.is_receive', function() {
			var self = $(this);
			var id = $(this).data('garnu_id');
			alert_if("Do you want to update the Receive Garnu?", function() {
				$.ajax({
					url: "<?php echo base_url('manufacturing/pre_garnu/updateStatus'); ?>",
					type: 'POST',
					showLoader: true,
					data: {
						id
					},
					success: function(response) {
						var response = JSON.parse(response);
						console.log(response);
						if (response.success === true) {
							var tr = self.parents('tr');
							tr.css('color', 'green');
							tr.find('.d-flex a').removeClass("d-none");
							tr.find('.is_receive ').addClass("d-none");
							tr.find('.badge').removeClass("bg-danger");
							tr.find('.badge').addClass('bg-indigo');
							tr.find('.badge').text('Yes');
							SweetAlert('success', response.message);
							ShowBlockUi('#garnu');
						} else {
							SweetAlert('error', response.message);
							ShowBlockUi('#garnu');
						}
					},
					error: function() {
						SweetAlert('error', 'There was a problem processing your request.');
					},
				});
			});
		});

		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});

		$(document).on("click", "#is_kasar", function() {
			isKasar($(this));
		});

		function isKasar(ref) {
			if (ref.is(":checked")) {
				$(".is_kasar").css("color", "green");
				$("#is_kasar").prop("checked", true);
			} else {
				$(".is_kasar").css("color", "red");
				$("#is_kasar").prop("checked", false);
			}
			$(".parent-div-party").toggle(ref.is(":checked"));
			$('#select-account').select2({
				width: '100%',
				dropdownParent: $('#ReceivedModel')
			});
		}
	});
</script>
