<style>
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

				<div class="col-sm-11">
					<h1 class="card-title"><b> Casting Report </b></h1>
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
										<th scope="col">Created At</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="4"></td>
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
	$(document).ready(function() {
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
				'url': "<?= base_url(); ?>manufacturing/garnu/getlist",
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
					data: 'created_at'
				},
			],
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

		$(document).on('click', '.is_receive', function() {
			var self = $(this);
			var id = $(this).data('garnu_id');
			alert_if("Do you want to update the Receive Garnu?", function() {
				$.ajax({
					url: "<?php echo base_url('manufacturing/garnu/updateStatus'); ?>",
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
	});
</script>
