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
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-status-top bg-blue"></div>
			<div class="card-header justify-content-between">
				<!--<div class="col-sm-11">-->
				<h1 class="card-title"><b>Lot Wise Row Material Report </b></h1>
				<span class="btn btn-action bg-primary text-white m-1 p-3 showmerger" data-bs-toggle="tooltip" data-bs-placement="top">
					<i class="far fa-plus card-title"></i>
				</span>
				<!--</div>-->
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" id="fromdate" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
							<div class="col-sm-2">
								<label>Type :</label> <br>
								<select class="form-select select2" id="type">
									<option value="">Select Type</option>
									<option value="RECEIVE">Receive</option>
									<option value="GIVEN">Given</option>
									<option value="PURCHASE">Purchase</option>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Row Material :</label> <br>
								<select class="form-select select2" id="rm">
									<option value="">Select Row Material</option>
									<?php
									if (!empty($rm)) {
										foreach ($rm as $row) { ?>
											<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Status :</label> <br>
								<select class="form-select select2" id="status">
									<option value="">Select</option>
									<option value="YES">Complated</option>
									<option value="NO">Not Complated</option>
								</select>
							</div>
						</div>
						<div class="mt-3">
							<cite>
								<b>
									<table id="garnu" class="table table-vcenter card-table">
										<button class="btn btn-primary button_old__print"><i class="fa fa-print"></i></button>
										<thead>
											<tr>
												<th scope="col">SN<input type="checkbox" class="form-check-input checkall" style="margin-left: 5px !important;"></th>
												<th scope="col">Code</th>
												<th scope="col">Type</th>
												<th scope="col">Row Material</th>
												<th scope="col">Touch</th>
												<th scope="col">Weight</th>
												<th scope="col">Quantity</th>
												<th scope="col">Given Weight</th>
												<th scope="col">Given Quantity</th>
												<th scope="col">Receive Weight</th>
												<th scope="col">Receive Quantity</th>
												<th scope="col">Used Weight</th>
												<th scope="col">Used Quantity</th>
												<th scope="col">Is Complated</th>
												<th scope="col">Created At</th>
											</tr>
										</thead>
									</table>
								</b>
							</cite>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Used Row Material</p>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<b>
					<table class="table card-table table-vcenter text-center text-nowrap w-100" id="rowMaterial">
						<thead class="thead-light">
							<th scope="col">Sr.No</th>
							<th scope="col">Last Process</th>
							<th scope="col">Type</th>
							<th scope="col">Row Material</th>
							<th scope="col">Touch (%)</th>
							<th scope="col">Weight(Gm)</th>
							<th scope="col">Quantity</th>
						</thead>
					</table>
				</b>
			</div>
			<div class="modal-footer justify-content-end">
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-blur fade modal-xl" data-bs-backdrop="static" data-bs-keyboard="false" id="MergerModel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Merger Row Material</p>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-2 justify-content-center">
					<div class="col-sm-2">
						<label>Enter Code</label>
						<input type="text" id="codeName" name="code" class="form-control">
					</div>
					<div class="col-sm-2">
						<label>Row Material</label>
						<select class="form-select select2" id="rmId">
							<option value="">Select Row Material</option>
							<?php
							if (!empty($rm)) {
								foreach ($rm as $row) { ?>
									<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
							<?php }
							} ?>
						</select>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-vcenter card-table table-striped">
						<thead class="thead-light">
							<th>Code</th>
							<th>Touch</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th>Given Weight</th>
							<th>Given Quantity</th>
							<th>Receive Weight</th>
							<th>Receive Quantity</th>
							<th>Rem Weight</th>
							<th>Rem Quantity</th>
							<th>Fine</th>
							<th></th>
						</thead>
						<tbody id="MergerBody">
							<tr class="metalRow">
								<input type="hidden" name="fromdddate" class="form-control id" readonly="readonly">
								<td>
									<select class="form-control select2 rmCode">
										<option value="">Select Group</option>
									</select>
								</td>
								<td><input type="text" name="fromdddate" class="form-control touch p-1" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control weight p-1" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control quantity" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control given_weight" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control given_quantity" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control receive_weight" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control receive_quantity" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control rem_weight" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control rem_quantity" readonly="readonly"></td>
								<td><input type="text" name="fromdddate" class="form-control fine" readonly="readonly"></td>
								<td><button type="button" class="btn btn-danger metalDeleteRow">X</button></td>
							</tr>
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
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-given_weight'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-given_quantity'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-receive_weight'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-receive_quantity'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-rem_weight'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-rem_quantity'>0</span></h4>
									</div>
								</td>
								<td>
									<div class="d-flex">
										<h4><span class='text-end ms-3 metal-total-fine'>0</span></h4>
									</div>
								</td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success mergerAddButton">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<h3 class="text-center">Average Touch : <span class="text-end ms-3 average-touch">0</span></h3>
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-primary saveMetalData">Save Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script class="javascript">
	var main_row = '';
	$(document).ready(function() {
		var metalRow = $(".metalRow")[0]?.outerHTML;

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
				'url': "<?= base_url(); ?>report/Lot_wise_rm/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.row_material = $('#rm').val();
					data.type = $('#type').val();
					data.isComplated = $('#status').val();
				}
			},
			"columns": [{
					data: 'id',
					orderable: false
				},
				{
					data: 'code'
				},
				{
					data: 'type'
				},
				{
					data: 'rm'
				},
				{
					data: 'touch'
				},
				{
					data: 'weight'
				},
				{
					data: 'quantity'
				},
				{
					data: 'given_weight'
				},
				{
					data: 'given_quantity'
				},
				{
					data: 'receive_weight'
				},
				{
					data: 'receive_quantity'
				},
				{
					data: 'rem_weight'
				},
				{
					data: 'rem_quantity'
				},
				{
					data: 'is_complated'
				},
				{
					data: 'created_at'
				},
			],
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
		});
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#rm,#type,#status').on('change', function() {
			table.clear();
			table.draw();
		});

		$('#rm,#type,#status').select2();

		var rmTable = null;
		$(document).on('click', '.showUsed', function() {
			var id = $(this).data('id');
			if (rmTable !== null) {
				rmTable.destroy();
				rmTable = null;
			}

			UsedLotRm(id).done(function(response) {
				if (!response.success || response.data === "") {
					SweetAlert('warning', 'Data Not Found !!');
					return;
				}
				rmTable = initializeDataTable(response);
				$("#ReceivedModel").modal('show');
			}).fail(function(jqXHR, textStatus, errorThrown) {
				SweetAlert('error', 'Error fetching data: ' + errorThrown);
			});
		});

		function initializeDataTable(response) {
			var dataToLoad = response.data.map(function(item, index) {
				return [
					index + 1, // SR
					item.Process_name, // Process
					item.type, // Type
					item.row_material, // Rm
					item.touch, // Touch
					item.weight, // Weight
					item.quantity // Quantity
				];
			});

			return $("#rowMaterial").DataTable({
				data: dataToLoad,
				columns: [{
						title: "SR"
					},
					{
						title: "Process"
					},
					{
						title: "Type"
					},
					{
						title: "RM"
					},
					{
						title: "Touch"
					},
					{
						title: "Weight"
					},
					{
						title: "Quantity"
					}
				],
				destroy: true,
				retrieve: true
			});
		}

		function UsedLotRm(id) {
			return $.ajax({
				url: '<?= base_url("report/lot_wise_rm/UsedRowMaterial"); ?>',
				type: 'POST',
				dataType: 'json',
				data: {
					id: id
				},
			});
		}

		$(document).on('click', '.showmerger,.mergerAddButton', function() {
			$.ajax({
				showLoader: true,
				url: "<?= base_url('report/lot_wise_rm/getrmCode'); ?>",
				timeout: 8000,
				type: "POST",
				processData: true,
				success: function(response) {
					const lastTr = $("#MergerBody tr").last();
					lastTr.find('.rmCode').html(response);
				}
			});
			$("#MergerModel").modal('show');
			$("#MergerModel").find('.select2').select2({
				dropdownParent: $('#MergerModel')
			});
		});

		$(document).on('change', '.rmCode', function() {
			var refrence = $(this).parents("tr");
			var id = refrence.find(".rmCode option:selected").val();

			$.ajax({
				showLoader: true,
				url: "<?= base_url('report/lot_wise_rm/getCodeDetail'); ?>",
				timeout: 8000,
				type: "POST",
				data: {
					id: id
				},
				processData: true,
				success: function(response) {
					response = JSON.parse(response);
					refrence.find(".id").val(response.id);
					refrence.find(".touch").val(response.rem_weight);
					refrence.find(".weight").val(response.weight);
					refrence.find(".quantity").val(response.quantity);
					refrence.find(".given_weight").val(response.given_weight);
					refrence.find(".given_quantity").val(response.given_quantity);
					refrence.find(".receive_weight").val(response.receive_weight);
					refrence.find(".receive_quantity").val(response.receive_quantity);
					refrence.find(".rem_weight").val(response.rem_weight);
					refrence.find(".rem_quantity").val(response.rem_quantity);
					refrence.find(".fine").val((response.rem_weight * response.rem_weight) / 100);
					Metalcalculate();
				}
			});
		});

		$(document).on("click", ".mergerAddButton", function() {
			var LastRm = $(".metal_type").last();
			if (LastRm.val() == "") {
				return LastRm.select2("open");
			}
			$("#MergerBody").append(metalRow);
			const lastTr = $("#MergerBody tr").last();
			lastTr.find(".metalWeight,.metalQuantity").val(0);
			lastTr.find(".rmCode").select2({
				width: "250",
				dropdownParent: $("#MergerModel"),
			});
			// 	lastTr.find(".rmCode").last().select2("open");
			var modalBody = $("#MergerModel .modal-body");
			scrollEvent(modalBody, 550);
		});

		$(document).on("click", ".metalDeleteRow", function() {
			if ($(".metalDeleteRow").length > 1) {
				$(this).parents("tr").remove();
			}
			Metalcalculate();
		});
	});

	$(document).on("click", ".saveMetalData", function() {
		var touch = $('.metal-total-touch').text();
		var weight = $('.metal-total-weight').text();
		var qty = $('.metal-total-qty').text();
		var givenWeight = $('.metal-total-given_weight').text();
		var givenQuantity = $('.metal-total-given_quantity').text();
		var receiveWeight = $('.metal-total-receive_weight').text();
		var receiveQuantity = $('.metal-total-receive_quantity').text();
		var remWeight = $('.metal-total-rem_weight').text();
		var remQuantity = $('.metal-total-rem_quantity').text();
		var averageTouch = $('.average-touch').text();
		var code = $('#codeName').val();
		var rmId = $('#rmId').val();
		if (!code && !rmId) {
			alert('Code And Row Material Field Is Requeid!');
			return false;
		}
		var ids = [];
		$('#MergerBody tr').each(function() {
			if (!$(this).find('.id').val()) {
				alert('Code select field required');
				return false;
				exit;
			} else {
				ids.push($(this).find('.id').val());
			}
		});
		if (ids.length <= 1) {
			alert('Add More record');
			return false;
		}
		$.ajax({
			showLoader: true,
			url: "<?= base_url('report/lot_wise_rm/addMergerData'); ?>",
			timeout: 8000,
			type: "POST",
			data: {
				id: ids,
				touch: touch,
				weight: weight,
				qty: qty,
				givenWeight: givenWeight,
				givenQuantity: givenQuantity,
				receiveWeight: receiveWeight,
				receiveQuantity: receiveQuantity,
				remWeight: remWeight,
				remQuantity: remQuantity,
				code: code,
				rmId: rmId,
				averageTouch: averageTouch,
			},
			processData: true,
			success: function(response) {
				response = JSON.parse(response);
				if (response.success) {
					SweetAlert('success', response.message);
					location.reload();
				} else {
					SweetAlert('error', response.message);
				}
			}
		});
	});

	$('.saveMetalData').on('click', function() {
		let values = [];
		let hasDuplicates = false;

		$('#MergerBody tr').each(function() {
			let value = $(this).find('.id').val();
			if (values.includes(value)) {
				hasDuplicates = true;
				return false; // break out of the loop
			}
			values.push(value);
		});

		if (hasDuplicates) {
			alert('Duplicate values found!');
			return false;
		}
	});

	function Metalcalculate() {
		var metalTotalweight = 0;
		var metalTotaltouch = 0;
		var metalTotalqty = 0;
		var metalTotalGivenWeight = 0;
		var metalTotalGivenQuantity = 0;
		var metalTotalReceiveWeight = 0;
		var metalTotalReceiveQuantity = 0;
		var metalTotalRemWeight = 0;
		var metalTotalRemQuantity = 0;
		var metalTotalFine = 0;

		$(".weight").each(function() {
			metalTotalweight += parseFloat($(this).val() || 0);
		});
		$(".touch").each(function() {
			metalTotaltouch += parseFloat($(this).val() || 0);
		});
		$(".quantity").each(function() {
			metalTotalqty += parseFloat($(this).val() || 0);
		});
		$(".given_weight").each(function() {
			metalTotalGivenWeight += parseFloat($(this).val() || 0);
		});
		$(".given_quantity").each(function() {
			metalTotalGivenQuantity += parseFloat($(this).val() || 0);
		});
		$(".receive_weight").each(function() {
			metalTotalReceiveWeight += parseFloat($(this).val() || 0);
		});
		$(".receive_quantity").each(function() {
			metalTotalReceiveQuantity += parseFloat($(this).val() || 0);
		});
		$(".rem_weight").each(function() {
			metalTotalRemWeight += parseFloat($(this).val() || 0);
		});
		$(".rem_quantity").each(function() {
			metalTotalRemQuantity += parseFloat($(this).val() || 0);
		});
		$(".fine").each(function() {
			metalTotalFine += parseFloat($(this).val() || 0);
		});

		$(".metal-total-touch").text("");
		$(".metal-total-weight").text("");
		$(".metal-total-net_weight").text("");
		$(".metal-total-given_weight").text("");
		$(".metal-total-given_quantity").text("");
		$(".metal-total-receive_weight").text("");
		$(".metal-total-receive_quantity").text("");
		$(".metal-total-rem_weight").text("");
		$(".metal-total-rem_quantity").text("");
		$(".metal-total-fine").text("");

		$(".metal-total-weight").text(formatNumber(metalTotalweight));
		$(".metal-total-touch").text(formatNumber(metalTotaltouch));
		$(".metal-total-qty").text(formatNumber(metalTotalqty));
		$(".metal-total-given_weight").text(formatNumber(metalTotalGivenWeight));
		$(".metal-total-given_quantity").text(formatNumber(metalTotalGivenQuantity));
		$(".metal-total-receive_weight").text(formatNumber(metalTotalReceiveWeight));
		$(".metal-total-receive_quantity").text(formatNumber(metalTotalReceiveQuantity));
		$(".metal-total-rem_weight").text(formatNumber(metalTotalRemWeight));
		$(".metal-total-rem_quantity").text(formatNumber(metalTotalRemQuantity));
		$(".metal-total-fine").text(formatNumber(metalTotalFine));

		$(".average-touch").text(((formatNumber(metalTotalFine) * 100) / formatNumber(metalTotaltouch)).toFixed(2));
	}

	function scrollEvent(target, pixel = 500) {
		var animated = target.animate({
				scrollTop: target.prop("scrollHeight"),
			},
			pixel
		);
	}


	$(document).ready(function() {
		$('.checkall').click(function() {
			$('.rowId').prop('checked', this.checked);
		})

		$('.button_old__print').click(function() {
			var ids = [];
			$('.rowId').each(function() {
				if ($(this).is(':checked')) {
					ids.push($(this).data('rowid'));
				}
			})
			if (ids.length == 0) {
				alert('Please select atleast one record');
				return false;
			}
			console.log(ids);
			
			var form = $('<form>', {
				action: "<?= base_url('report/lot_wise_rm/printCustomerTags'); ?>",
				method: 'POST',
				target: '_blank'
			});

			$('<input>').attr({
				type: 'hidden',
				name: 'ids[]',
				value: ids.join(',')
			}).appendTo(form);

			form.appendTo('body').submit().remove();
		})
	});
</script>
