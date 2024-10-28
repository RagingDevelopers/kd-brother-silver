<style>
	.span-fine-ans,
	.span-alloy {
		background-color: yellow;
	}

	.username,
	.city,
	.customer,
	.items_group,
	.item {
		cursor: pointer;
		color: #0062cc;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title mt-2">Sales Report</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<label>From Date</label>
						<input type="date" class="form-control from" id="from_date" value="<?= date('Y-m-d') ?>" />
					</div>
					<div class="col-md-2">
						<label>To Date</label>
						<input type="date" class="form-control to" id="to_date" value="<?= date('Y-m-d') ?>" />
					</div>
					<div class="col-md-2">
						<label>Item:</label>
						<select class="form-control select2 select-item" id="item">
							<option value="">Select Item</option>
							<?php foreach ($item as $v): ?>
								<option value="<?= $v['id'] ?>">
									<?= $v['name'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2 d-none">
						<label>Users:</label>
						<select class="form-control select2" id="user">
							<option value="0">Select User</option>
							<?php foreach ($user as $v): ?>
								<option value="<?= $v['id'] ?>">
									<?= $v['username'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2 mt-4" style="text-align:right">
						<button class="btn btn-primary" id="search_btn" type="button">Search</button>
					    <a href="javascript:history.back()" class="btn btn-warning">Cancel</a>
						<!--<button class="btn btn-warning" id="download_btn" type="button">Down.load</button>-->
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-12">
						<div class="table-responsive" id="set_table_here">

						</div>
					</div>
				</div>
			</div>
			<!-- /.card-body -->
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		var datatable = $('#example1').DataTable({});

		$(document).on('click', '#search_btn', function () {
			var itemId = $('#item').val();
			var fromDate = $('#from_date').val();
			var toDate = $('#to_date').val();
			var userId = $('#user').val();

			var data = {
				itemId,
				fromDate,
				toDate,
				userId,
			}

			$.ajax({
				url: "<?= site_url('report/stockReport/ajax') ?>",
				type: "POST",
				data,
				showLoader: !0,
				success: function (data) {
					$('#set_table_here').html(data);
				},
				complete: function (data) {

				}
			});
		});

		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
	});
</script>
<?php $time = time(); ?>
<!--<script src="<?= base_url('assets/dist/js/sales_report.js?v=' . $time); ?>"></script>-->