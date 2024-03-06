<div class="page-body">
	<div class="container-xl">
		<div class="row row-cards">
			<div class="col-md-12">
				<div class="card">
					<div class="card-status-top bg-primary"></div>
					<div class="card-header">
						<h3 class="card-title"><?= isset($row_data) ? 'Edit ' . $page_title : 'Add <b>' . $page_title . '</b>'; ?></h3>
					</div>
					<div class="card-body">
						<!--<div class="row row-cards">-->
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<input type="hidden" value="<?php if (!empty($baki_code)) {
																	echo $baki_code;
																} else {
																	echo '0';
																} ?>" name="baki_code" id="baki_code">
									<label class="form-label">Date <span class="text-danger">*</span></label>
									<input type="date" name="date" class="form-control" id="date" value="<?= date('Y-m-d'); ?>" />
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Party<span class="text-danger">*</span></label>
									<input type="hidden" id="check_party_id" value="<?php if (!empty($party_id)) {
																						echo $party_id;
																					} else {
																						echo '0';
																					} ?>">
									<select name="party_id" id="party_id" class="form-select select2">
										<option value="">Select</option>
										<?php foreach ($party as $c) { ?>
											<option value="<?= $c['id']; ?>" <?php if (!empty($party_id) && $c['id'] == $party_id) {
																					echo 'selected';
																				} ?>><?= $c['name']; ?> </option>
										<?php } ?>
									</select>
								</div>
							</div>

						</div>


						<div class="row" style="margin-top:1%;">
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Type<span class="text-danger">*</span></label>
									<select name="type" id="type" class="form-select select2">
										<option data-mode="" value="">select</option>
										<option value="fine" data-mode="Chorsa,Silli">Fine</option>
										<option value="cash" data-mode="">Cash</option>
										<option value="bank" data-mode="">Bank</option>
										<option value="ratecutfine" data-mode="Cash Chorsha,RTGS Chorsa,Cash Silli,RTGS Silli">Rate Cut - Fine</option>
										<option value="ratecutrs" data-mode="Cash Chorsha,RTGS Chorsa,Cash Silli,RTGS Silli">Rate Cut - Rs</option>
										<option value="roopu" data-mode="">Roopu</option>
									</select>
								</div>
							</div>

							<div class="col-md-2 allinone dfine dratecutfine dratecutrs">
								<div class="form-group">
									<label class="form-label">Mode<span class="text-danger">*</span></label>
									<select name="mode" id="mode" class="form-select select2">
									</select>
								</div>
							</div>
							<div class="col-md-1 allinone dratecutrs">
								<div class="form-group">
									<label class="form-label">Amount<span class="text-danger">*</span></label>
									<input type="text" name="amount2" class="form-control" id="amount2" value="" placeholder="amount" />
								</div>
							</div>
							<div class="col-md-1 allinone dfine dratecutfine droopu">
								<div class="form-group">
									<label class="form-label">Gross <span class="text-danger">*</span></label>
									<input type="text" name="gross" class="form-control" id="gross" value="" placeholder="gross" />
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
									<input type="text" name="fine" class="form-control" id="fine" value="" placeholder="fine" />
								</div>
							</div>
							<div class="col-md-1 allinone dratecutfine dratecutrs">
								<div class="form-group">
									<label class="form-label">Rate<span class="text-danger">*</span></label>
									<input type="text" name="rate" class="form-control" id="rate" value="" placeholder="rate" />
								</div>
							</div>
							<div class="col-md-1 allinone dcash dbank dratecutfine">
								<div class="form-group">
									<label class="form-label">Amount<span class="text-danger">*</span></label>
									<input type="text" name="amount" class="form-control" id="amount" value="" placeholder="amount" />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">Remark<span class="text-danger">*</span></label>
									<input type="text" name="remark" class="form-control" id="remark" value="" placeholder="remark" />
								</div>
							</div>
							<div class="col-md-2  allinone dfine">
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
						</div>


						<br />
						<div class="card-footer">
							<button type="button" id="baki_data" class="btn btn-primary">Submit</button>
							<input type="hidden" value="" id="baki_id">
							<button type="button" id="baki_data_ajax_update" class="btn btn-primary">Update</button>
						</div>
					</div>
					<div class="card-footer">
						<table class="table" id="table-baki">
							<thead>
								<tr>
									<th>Sl No</th>
									<th>Action</th>
									<th>Party Name</th>
									<th>Date</th>
									<th>Type</th>
									<th>mode</th>
									<th>Gross</th>
									<th>Purity</th>
									<th>W/B</th>
									<th>Fine</th>
									<th>Metal Type</th>
									<th>Rate</th>
									<th>Amount</th>
									<th>Remark</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
							<tfoot>

							</tfoot>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<script class="javascript">
	$(document).ready(function() {
		// $("#party_id").select2();
		$('#baki_data_ajax_update').hide();
		var example_table_billing = $('#table-baki').DataTable({
			'paging': false,
			"searching": false,
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			"ajax": {
				'url': "<?php echo base_url(); ?>payment/baki/report",
				'data': function(data) {
					data.baki_code = $('#baki_code').val();
				}
			},
			"columns": [{
					data: 'sno'
				},
				{
					data: 'action'
				},
				{
					data: 'party'
				},
				{
					data: 'date'
				},
				{
					data: 'type'
				},
				{
					data: 'mode'
				},
				{
					data: 'gross'
				},
				{
					data: 'purity'
				},
				{
					data: 'wb'
				},
				{
					data: 'fine'
				},
				{
					data: 'metal_type'
				},
				{
					data: 'rate'
				},
				{
					data: 'amount'
				},
				{
					data: 'remark'
				},
			],
		});
		$(".allinone").hide();
		// 		$("#type").select2();


		$("#type").change(function() {
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

		});

		$(document).on("click", "#baki_data", function() {
			var date = $("#date").val();
			var party_id = $("#party_id").val();
			var type = $("#type").val();
			if (date == "" || party_id == "" || type == "") {
				alert('Field Are Required');
			} else {

				if (type == "ratecutrs") {
					$("#amount").val($("#amount2").val());
				}

				var data = {
					'date': date,
					'party_id': party_id,
					'type': type,
					'mode': $("#mode").val(),
					'gross': $("#gross").val(),
					'purity': $("#purity").val(),
					'wb': $("#wb").val(),
					'fine': $("#fine").val(),
					'rate': $("#rate").val(),
					'amount': $("#amount").val(),
					'remark': $("#remark").val(),
					'code': $('#baki_code').val(),
					'check_party_id': $('#check_party_id').val(),
					'metal_type_id': $('#metal_type_id').val(),
				}

				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>payment/baki/baki_data",
					data: data,
					success: function(res) {
						try {
							res = JSON.parse(res);
							$('#baki_code').val(res.baki_code);
							example_table_billing.clear();
							example_table_billing.draw();
							if (res.status) {
								// swal({
								// 	title: 'Saved Successfully',
								// 	text: res.message,
								// 	icon: 'success',
								// });
								$('#check_party_id').val(res.party_id);
								$('#gross').val('');
								$('#purity').val(100);
								$('#wb').val('');
								$('#fine').val('');
								$('#rate').val('');
								$("#amount2").val('')
								$('#amount').val('');
								$('#rate').val('');
								$('#remark').val('');
							} else {
								swal({
									icon: 'error',
									title: 'Error 500',
									text: res.message
								})
							}
						} catch (error) {
							console.log(error);
							swal({
								icon: 'error',
								title: 'Something went wrong',
								text: error.message
							})
						}
					},
					error: function(error) {
						console.log(error);
						swal({
							icon: 'error',
							title: 'Something went wrong. Please try again.',
							text: error.message
						});
					}
				});
			}
		});

		$(document).on("click", "#baki_data_ajax_update", function() {
			var date = $("#date").val();
			var party_id = $("#party_id").val();
			var type = $("#type").val();
			if (date == "" || party_id == "" || type == "") {
				alert('Field Are Required');
			} else {

				if (type == "ratecutrs") {
					$("#amount").val($("#amount2").val());
				}

				var data = {
					'date': date,
					'party_id': party_id,
					'type': type,
					'mode': $("#mode").val(),
					'gross': $("#gross").val(),
					'purity': $("#purity").val(),
					'wb': $("#wb").val(),
					'fine': $("#fine").val(),
					'rate': $("#rate").val(),
					'amount': $("#amount").val(),
					'remark': $("#remark").val(),
					'code': $('#baki_code').val(),
					'baki_id': $('#baki_id').val(),
					'metal_type_id': $('#metal_type_id').val(),
				}

				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>payment/baki/baki_data_ajax_update",
					data: data,
					success: function(res) {
						try {
							res = JSON.parse(res);
							$('#baki_code').val(res.baki_code);
							example_table_billing.clear();
							example_table_billing.draw();
							if (res.status) {
								swal({
									title: 'Saved Successfully',
									text: res.message,
									icon: 'success',
								});
								$('#party_id').val('');
								$('#type').val('');
								$('#gross').val('');
								$('#purity').val(100);
								$('#wb').val('');
								$('#fine').val('');
								$('#rate').val('');
								$("#amount2").val('')
								$('#amount').val('');
								$('#rate').val('');
								$('#remark').val('');
								$('#baki_data').show();
								$('#baki_data_ajax_update').hide();
							} else {
								swal({
									icon: 'error',
									title: 'Error 500',
									text: res.message
								})
							}
						} catch (error) {
							console.log(error);
							swal({
								icon: 'error',
								title: 'Something went wrong',
								text: error.message
							})
						}
					},
					error: function(error) {
						console.log(error);
						swal({
							icon: 'error',
							title: 'Something went wrong. Please try again.',
							text: error.message
						});
					}
				});
			}
		});

		$(document).on("click", ".baki_edit_row", function() {
			var baki_id = $(this).data('id');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>payment/baki/baki_edit_row",
				data: {
					baki_id: baki_id
				},
				success: function(res) {
					try {
						res = JSON.parse(res);
						if (res.status) {

							if (res.data.type == "ratecutrs") {
								$('#amount2').val(res.data.amount);
							}

							$('#date').val(res.data.date);
							$("#party_id").val(res.data.customer_id).trigger('change');

							//  $('#party_id').val();
							$('#type').val(res.data.type).trigger('change');
							$('#gross').val(res.data.gross);
							$('#purity').val(res.data.purity);
							$('#wb').val(res.data.wb);
							$('#fine').val(res.data.fine);
							$('#rate').val(res.data.rate);
							$('#amount').val(res.data.amount);
							$('#rate').val(res.data.rate);
							$('#remark').val(res.data.remark);
							$('#baki_id').val(res.data.id);
							$('#mode').html('<option value="' + res.data.mode + '" selected>' + res.data.mode + '</option>');
							$("#metal_type_id").val(res.data.metal_type_id).trigger('change');
							$('#baki_data').hide();
							$('#baki_data_ajax_update').show();

						} else {
							swal({
								icon: 'error',
								title: 'Error 500',
								text: res.message
							})
						}
					} catch (error) {
						console.log(error);
						swal({
							icon: 'error',
							title: 'Something went wrong',
							text: error.message
						})
					}
				},
				error: function(error) {
					console.log(error);
					swal({
						icon: 'error',
						title: 'Something went wrong. Please try again.',
						text: error.message
					});
				}
			});

		});

		$(document).on("click", ".delete_row", function() {
			var baki_id = $(this).data('id');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>payment/baki/delete_row",
				data: {
					baki_id: baki_id
				},
				success: function(res) {
					res = JSON.parse(res);
					if (res.status) {
						example_table_billing.clear();
						example_table_billing.draw();
					} else {
						swal({
							icon: 'error',
							title: 'Error 500',
							text: res.message
						})
					}
				},
				error: function(error) {
					console.log(error);
					swal({
						icon: 'error',
						title: 'Something went wrong. Please try again.',
						text: error.message
					});
				}
			});

		});

	});
</script>
