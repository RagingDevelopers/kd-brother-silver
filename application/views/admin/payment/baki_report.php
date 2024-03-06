<style>
	#customerBakiReport_filter {
		float: right;
		padding-right: 15px;
	}

	#customerBakiReport_length {
		padding: 10px 15px;
	}

	.dt-buttons {
		padding: 0px 0px 5px 15px;
	}

	#customerBakiReport_info {
		float: left;
		padding: 15px;
	}

	#customerBakiReport_paginate {
		float: right;
		padding: 10px 15px;
	}
</style>
<div class="page-body">
	<div class="container-xl">
		<div class="row row-cards">
			<div class="col-12">
				<div class="card">
					<div class="card-status-top bg-primary"></div>
					<div class="card-header justify-content-between">
						<h3 class="card-title"><b><?= $page_title; ?> </b></h3>
						<a class="btn btn-action bg-primary text-white m-1 p-3" href="<?= base_url(); ?>payment/baki">
							<i class="far fa-plus card-title"></i>
						</a>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row row-cards">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">From Date</label>
										<input type="date" class="form-control" placeholder="YYY-MM-DD" id="from">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">End Date</label>
										<input type="date" class="form-control" placeholder="YYY-MM-DD" id="to">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Group By</label>
										<select class="form-control" id="group_by" required>
											<option value="">all</option>
											<option value="fine">Fine</option>
											<option value="cash">Cash</option>
											<option value="bank">Bank</option>
											<option value="ratecutfine">Rate Cut - Fine</option>
											<option value="ratecutrs">Rate Cut - Rs</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<b>
							<table class="table card-table table-vcenter text-center text-nowrap datatable" id="customerBakiReport">
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
						</b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		$("#from").flatpickr();
		$("#to").flatpickr();
		// $("#group_by").select2();

		var customerBakiReport = $('#customerBakiReport').DataTable({
			"iDisplayLength": 25,
			"fixedHeader": true,
			"lengthMenu": [
				[10, 25, 50, 100, 500, 1000, 5000],
				[10, 25, 50, 100, 500, 1000, 5000]
			],
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			"ajax": {
				'url': "<?php echo base_url(); ?>payment/baki_report/report/",
				'data': function(data) {
					var from = $("#from").val();
					var to = $("#to").val();
					var group_by = $("#group_by").find("option:selected").val();
					data.from = from;
					data.to = to;
					data.group_by = group_by;
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
			"dom": 'lBfrtip',
			"buttons": [
				'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
			]

		});

		$("#from,#to,#group_by").change(function() {
			customerBakiReport.clear();
			customerBakiReport.draw();
		});
	});
</script>
