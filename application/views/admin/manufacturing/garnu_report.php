<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>

				<div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Received </h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="garnu_receive">
									<input type="hidden" class="garnu_id" name="garnu_id" />
									<table class="table card-table table-vcenter text-center text-nowrap ">
										<thead class="thead-light">
											<th>Metal Type</th>
											<th scope="col">Weight(Gm)</th>
											<th scope="col">Touch (%)</th>
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
														$metal_type = $this->db->get('metal_type')->result();
														foreach ($metal_type as $value) { ?>
															<option value="<?= $value->id; ?>">
																<?= $value->name; ?>
															</option>
														<?php } ?>
													</select>
												</td>

												<td>
													<input class="form-control weight" type="number" name="weight[]" placeholder="Enter Weight" value="" required>
												</td>
												<td>
													<input class="form-control touch" type="number" name="touch[]" placeholder="Enter touch(%)" value="" required>
												</td>
												<td>
													<button type="button" class="btn btn-danger del">X</button>
												</td>
											</tr>
										</tbody>

										<tfoot>
											<td class="d-flex border border-0 align-content-start flex-wrap">
												<button type="button" class="btn btn-outline-warning" id="add">AddRow</button>
											</td>
											<td>
											</td>
											<td class="d-flex border border-0 justify-content-end  flex-wrap">
												<button type="submit" class="btn btn-outline-primary submitBtn" id="submitBtn">Submit</button>
											</td>
										</tfoot>
									</table>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-11">
					<h1 class="card-title"><b> Garnu Report </b></h1>
				</div>
				<div class="col-sm-1 ms-5 ps-5">
					<a class="btn btn-action bg-primary text-white" href="<?= base_url('manufacturing/garnu/index/add') ?>">
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
								<input type="date" id="fromdate" name="fromdddate" class="form-control">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control">
							</div>
						</div>
						<div class="mt-3">
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Action</th>
										<th scope="col">Name</th>
										<th scope="col">Garnu Weight</th>
										<th scope="col">Touch</th>
										<th scope="col">Silver</th>
										<th scope="col">Copper</th>
										<th scope="col">Process</th>
										<th scope="col">Received</th>
										<th scope="col">Created At</th>
									</tr>
								</thead>
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

		$('#ReceivedModel').on('shown.bs.modal', function(e) {
			var modal = this;
			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $(modal)
				});
			});
		});

		var table = $('#garnu').DataTable({
			"iDisplayLength": 5,
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
				'url': "<?= base_url(); ?>manufacturing/garnu/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
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
				{
					data: 'touch'
				},
				{
					data: 'silver'
				},
				{
					data: 'copper'
				},
				{
					data: 'process'
				},
				{
					data: 'recieved'
				},
				{
					data: 'created_at'
				},
			],
			"rowCallback": function(row, data) {
				if (data.recieved == 'YES') {
					$(row).css('color', 'green');
				} else if (data.recieved == 'NO') {
					$(row).css('color', 'red');
				}
			}
		});
		$('#todate').on('change', function() {
			table.clear()
			table.draw()
		});
		$('#fromdate').on('change', function() {
			table.clear()
			table.draw()
		});

		function receiveGarnu(id = null){
			return $.ajax({
				showLoader: true,
				url: '<?= base_url("manufacturing/garnu/checkReceive"); ?>',
				type: 'POST',
				dataType: 'json',
				data: {id},
				success: function(response) {
					if (response.success) {
						$('.garnu_id').val("");
						$('.garnu_id').val(id);
						$(response.data).each(function(index, value) {
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
							$lastRow.find('.touch').val(value.touch).trigger('change');
							$lastRow.find('.weight').val(value.weight).trigger('change');
							$lastRow.find('.metal_type_id')
								.val(value.metal_type_id)
								.trigger('change')
								.select2({
									width: '100%',
									dropdownParent: $('#ReceivedModel')
								});
						});
					} else {
						SweetAlert('error', response.message);
					}
				},
				error: function() {
					alert("An error occurred.");
				}
			});
		}

		$(document).on('click', '.receive-btn', function() {
			var id = $(this).data('receiveid');
			$(".sectiontocopy").not(':first').remove();
			$(".ids").not(':first').remove();
			$(".ids").val(0);
			$('.garnu_id').val("");
			$('.garnu_id').val(id);
			$('.append-here tr').first().find('.sdid').val(0);
			$('.append-here tr').first().find('.touch, .weight,.metal_type_id').val('');
			$('.append-here tr').first().find('.metal_type_id').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});

			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $('#ReceivedModel')
				});
			});

			receiveGarnu(id).done(function() {
				$("#ReceivedModel").modal('show');
			});
		});

		$("#add").click(function() {
			$(".append-here").append(main_row);
			$('.append-here tr').last().find('.sdid').val(0);
			$('.append-here tr').last().find('.touch, .weight,.metal_type_id').val('');
			$('.append-here tr').last().find('.metal_type_id').select2({
				width: '100',
				dropdownParent: $('#ReceivedModel')
			});

			$('.metal_type_id').each(function() {
				$(this).select2({
					width: '100',
					dropdownParent: $('#ReceivedModel')
				});
			});
		});

		$(document).on('click', '.del', function() {
			var metal_type_id = $(".metal_type_id").length;
			if (metal_type_id > 1) {
				$(this).parent().parent().remove();
			}
		});

		$(document).on('input', '.touch', function() {
			var touch = $(this);
			if (touch.val() > 100) {
				SweetAlert('warning', 'Touch should be less than equal to 100'), touch.val("");
			}
		});

		$('#garnu_receive').on('submit', function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: '<?php echo base_url('manufacturing/garnu/receive'); ?>',
				type: 'POST',
				data: formData,
				success: function(response) {
					var response = JSON.parse(response);
					if (response.success === true) {
						$('#ReceivedModel').modal('hide');
						SweetAlert('success', response.message);
					} else {
						$('#ReceivedModel').modal('hide');
						SweetAlert('error', response.message);
					}
				},
				error: function() {
					SweetAlert('error', "Error submitting form");
				}
			});
		});
	});
</script>
