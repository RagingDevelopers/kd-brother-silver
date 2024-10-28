<style>
	.net_weight {
		background-color: #ebebeb;
		color: black;
	}

	.codeCopy {
		cursor: pointer;
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
				<div class="col-sm-11">
					<h1 class="card-title"><b>Ready For Lot Report </b></h1>
				</div>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" id="fromdate" value="<?= date('Y-m-01'); ?>" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
							<div class="col-sm-2">
								<label for="item_id">Items :</label>
								<select class="form-select select2 item_id" id="item_id" name="item_id">
									<option value=''>Select Item</option>
									<?php
									foreach ($item as $val) {
									?>
										<option value="<?= $val['id'] ?? 0 ?>" <?php if (isset($row) && ($val['id'] == $row['item_id'])) {
																					echo 'selected';
																				} ?>>
											<?= $val['name']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="mt-3">
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Barcode</th>
										<th scope="col">Item Name</th>
										<th scope="col">Pcs</th>
										<th scope="col">Complated Pcs</th>
										<th scope="col">Weight</th>
										<th scope="col">Labour Type</th>
										<th scope="col">labour</th>
										<th scope="col">Total labour</th>
										<th scope="col">Row Material Weight</th>
										<th scope="col">Total Weight</th>
										<th scope="col">Touch</th>
										<th scope="col">Fine</th>
										<th scope="col">Remark</th>
										<th scope="col">Created At</th>
									</tr>
								</thead>
								<tfoot>
								    <tr>
								        <td class="text-center text-primary" colspan="3"><h3 class="blinking-text">Totals ==></h3></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td> -- </td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
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
		$(".select2").select2({
			placeholder: "-- Select --",
			allowClear: true,
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
				'url': "<?= base_url(); ?>manufacturing/receive_garnu/lot_creation_report",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.item_id = $('#item_id').val();
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'code'
				},
				{
					data: 'item_name'
				},
				{
					data: 'pcs'
				},
				{
					data: 'complated_pcs'
				},
				{
					data: 'weight'
				},
				{
					data: 'labour_type'
				},
				{
					data: 'labour'
				},
				{
					data: 'total_labour'
				},
				{
					data: 'rm_weight'
				},
				{
					data: 'total_weight'
				},
				{
					data: 'touch'
				},
				{
					data: 'fine'
				},
				{
					data: 'remark'
				},
				{
					data: 'created_at'
				},
			],
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
			"columnDefs": [{
					"className": "text-success",
					"targets": [4]
				},
			],
			footerCallback: function(row, data, start, end, display) {
                handelFooterTotal(
                    this.api(),
                    [3,4,5,7,8,9,10,11,12]
                );
            },
		});
		$('#todate').on('change', function() {
			table.clear()
			table.draw()
		});
		$('#fromdate,#item_id').on('change', function() {
			table.clear()
			table.draw()
		});
	});
</script>
