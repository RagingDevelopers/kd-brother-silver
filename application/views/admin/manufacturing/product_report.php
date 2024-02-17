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
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<div class="col-sm-11">
					<h1 class="card-title"><b>Product Report </b></h1>
				</div>
				<!-- <div class="col-sm-1 ms-5 ps-5">
					<a class="btn btn-action bg-primary text-white" href="<?= base_url('manufacturing/garnu/index/add') ?>">
						<i class="far fa-plus card-title" aria-hidden="true">
						</i>
					</a>
				</div> -->
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
							<div class="col-sm-2">
								<label>Process :</label> <br>
								<select class="form-select select2" id="process">
									<option value="">Select Process</option>
									<?php
									if(!empty($process)){
										foreach($process as $row){ ?>
											<option value="<?=$row['id'];?>"><?=$row['name'];?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Worker :</label> <br>
								<select class="form-select select2" id="worker">
									<option value="">Select Worker</option>
									<?php
									if(!empty($worker)){
										foreach($worker as $row){ ?>
											<option value="<?=$row['id'];?>"><?=$row['name'];?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Product :</label> <br>
								<select class="form-select select2" id="product">
									<option value="">Select Product</option>
									<?php
									if(!empty($product)){
										foreach($product as $row){ ?>
											<option value="<?=$row['id'];?>"><?=$row['name'];?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Status :</label> <br>
								<select class="form-select select2" id="status">
									<option value="">All</option>
									<option value="YES">YES</option>
									<option value="NO">NO</option>
								</select>
							</div>
						</div>
						<div class="mt-3">
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">SN</th>
										<th scope="col">Name</th>
										<th scope="col">Touch</th>
										<th scope="col">Weight</th>
										<th scope="col">Silver</th>
										<th scope="col">Copper</th>
										<!-- <th scope="col">Used Weight</th>
										<th scope="col">Used Silver</th>
										<th scope="col">Used Copper</th> -->
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
				'url': "<?= base_url(); ?>manufacturing/product/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.received = $('#status').val();
					data.process_id = $('#process').val();
					data.worker_id = $('#worker').val();
					data.product_id = $('#product').val();
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'garnu_name'
				},
				{
					data: 'garnu_touch'
				},
				{
					data: 'garnu_weight'
				},
				{
					data: 'garnu_silver'
				},
				{
					data: 'garnu_copper'
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
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
			// "rowCallback": function(row, data) {
			// 	if (data.is_recieved == 'YES') {
			// 		$(row).css('color', 'green');
			// 	} else if (data.is_recieved == 'NO') {
			// 		$(row).css('color', 'red');
			// 	}
			// }
		});
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#process,#worker,#product,#status').on('change', function() {
			table.clear();
			table.draw();
		});

		$('#process,#worker,#product,#status').select2();
	});
</script>
