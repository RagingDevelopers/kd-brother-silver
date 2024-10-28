<style>
	.span-fine-ans,
	.span-alloy {
		background-color: yellow;
	}

	.item {
		cursor: pointer;
	}
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
	</div><!-- /.container-fluid -->
	<?php
	if ($this->session->flashdata('flash_message') != "") {
		$message = $this->session->flashdata('flash_message'); ?>

		<div class="alert alert-<?= $message['class']; ?> alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-circle"></i> Message:</h4>
			<?php echo $message['message']; ?>
		</div>
	<?php
		$this->session->set_flashdata('flash_message', "");
	}
	?>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Tag</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<label>From Date</label>
						<input type="date" class="form-control from" id="from_date" value="<?= date('Y-m-01') ?>" />
					</div>
					<div class="col-md-2">
						<label>To Date</label>
						<input type="date" class="form-control to" id="to_date" />
					</div>
					<div class="col-md-2">
						<label>Item:</label>
						<select class="form-control select2" id="item">
							<option value="0">Select Item</option>
							<?php foreach ($item as $v) : ?>
								<option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2">
						<label>Tag No.</label>
						<input type="text" class="form-control" id="tag_no" value="" />
					</div>
					 <div class="col-md-2">
						<label>Is Sold:</label>
						<select class="form-control select2" id="is_sold">
							<option value=" ">Select</option>
							<option value="0">No</option>
							<option value="2">Yes</option>
							<option value="1">Ready For Sale</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Group By:</label>
						<select class="form-control select2" id="group_by">
							<?php foreach ($group_by as $key => $v) : ?>
								<option value="<?= $key ?>"><?= $v ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2">
						<label>Gr. Wt</label>
						<input type="text" class="form-control" id="gross_weight" value="" />
					</div>
					<div class="col-md-2">
						<label>Nt Wt.</label>
						<input type="text" class="form-control" id="net_weight" value="" />
					</div>
					<div class="col-md-3 mt-4 gap-2 d-flex">
						<button class="btn btn-primary" id="search_btn" type="button">Search</button>
						<!-- <button class="btn btn-light" id="cancel_btn" type="button">Cancel</button>
						<button class="btn btn-warning" id="download_btn" type="button">Download</button> -->
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive" id="set_table_here">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="file" id="image" style="display:none" />
<input type="hidden" id="img_field" />
<input type="hidden" id="req_status" />
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});

		var datatable = $('#example1').DataTable({});

		$(document).on('click', '#search_btn', function() {
			var itemId = $('#item').val();
			var fromDate = $('#from_date').val();
			var toDate = $('#to_date').val();
			var isSold = $('#is_sold').val();
			// var userId = $('#user').val();
			var groupBy = $('#group_by').val();
			var tagNo = $('#tag_no').val();
			var grossWeight = $('#gross_weight').val();
			var netWeight = $('#net_weight').val();

			var data = {
				itemId: itemId,
				fromDate: fromDate,
				toDate: toDate,
				// userId: userId,
				groupBy: groupBy,
				isSold: isSold,
				tagNo: tagNo,
				grossWeight: grossWeight,
				netWeight: netWeight
			}


			$.ajax({
			    showLoader: true,
				url: "<?= site_url('report/lot/ajax_report') ?>",
				type: "POST",
				data: data,
				success: function(data) {
					$('#set_table_here').html(data);
				},
				complete: function(data) {

				}
			});
		});
	});
</script>
<?php $time = time(); ?>
<script>
	$(document).ready(function() {

		$(document).on('click', '.item', function() {
			var id = $(this).data('item_id');
			$('#item').val(id).trigger('change');
			$('#group_by').val('tag').trigger('change');
			$('#search_btn').trigger('click');
		});

		var dc_id = 0;
		var imageObj;

		$(document).on('click', '.rose', function() {
			if ($('#req_status').val() != 'TRUE') {
				$('#img_field').val('rose');
				var img = $(this);
				imageObj = img;
				var dcId = $(this).data('dc_id');
				dc_id = dcId;
				$('#image').trigger('click');
			}
		});

		$(document).on('change', '#image', function() {
			var file_data;

			var img2 = $('#image').val();
			if (img2 != "") {
				var file_data = $('#image').prop('files')[0];
			}

			var data = new FormData();
			data.append("dc_id", dc_id);
			if (img2 != "") {
				data.append("image", file_data);
			}

			var img = $('#img_field').val();

			data.append('image_field', img);

			console.log(data.get('image'));

			$.ajax({
				beforeSend: function(xhr) {
					$('#req_status').val('TRUE');
				},
				showLoader: true,
				url: "<?= site_url('admin/design_code/uploadImage'); ?>",
				method: "POST",
				data: data,
				dataType: 'text', // what to expect back from the PHP script, if anything
				contentType: false,
				processData: false,
				success: function(data) {
					imageObj.prop("src", "<?= base_url("uploads/design_code/") ?>" + data);
				},
				complete: function(data) {
					$('#req_status').val('FALSE');
				}
			});
		});

		$(document).on('click', '.yellow', function() {
			// img = 'yellow';
			// $('#img').data('img','rose');
			if ($('#req_status').val() != 'TRUE') {
				$('#img_field').val('yellow');
				var img = $(this);
				imageObj = img;
				var dcId = $(this).data('dc_id');
				dc_id = dcId;
				$('#image').trigger('click');
			}
		});
	});
</script>
