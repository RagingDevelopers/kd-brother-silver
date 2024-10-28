<style>
	.span-fine-ans,
	.span-alloy {
		background-color: yellow;
	}

	.username,
	.customer,
	.items_group,
	.item,
	.item_cat {
		cursor: pointer;
		color: #0062cc;
	}

	.rose,
	.yellow {
		cursor: pointer;
		background-color: #FF0000;
	}

	.rose:hover,
	.yellow:hover {
		opacity: 30%;
	}

	.warning {
		background-color: #ffd970;
	}

	.success {
		background-color: #8aff70;
	}

	.info {
		background-color: #70ffee;
	}

	.primary {
		background-color: #787fff;
	}

	.danger {
		background-color: #ff7878;
	}
</style>
<div class="content-header">
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
	<div class="col-12 col-sm-12">
		<div class="card card-white">
			<div class="card-header">
				<h3 class="card-title">Balance Sheet Report</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<?php include('ajax_balance_sheet_report.php') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="file" id="image" style="display:none" />
<input type="hidden" id="img_field" />
<input type="hidden" id="req_status" />
<script src="<?= site_url('assets/back/plugins/select2/js/select2.min.js'); ?>"></script>
<?php $time = time(); ?>
<script>
	var isSearched = false;
	const parseF = function(str) {
		var f = parseFloat(str);
		if (isNaN(f)) {
			f = 0;
		}
		if (typeof str === "undefined") {
			f = 0;
		}
		if (str == null) {
			f = 0;
		}
		return f;
	};

	const parseI = function(str) {
		var f = parseInt(str);
		if (isNaN(f)) {
			f = 0;
		}
		if (typeof str === "undefined") {
			f = 0;
		}
		if (str == null) {
			f = 0;
		}
		return f;
	};
	$(document).ready(function() {

		$(document).on('click', '.transfer_entry', function() {
			var phase = $(this).data('phase');
			var karigar = $('#karigar').val();
			if (!karigar) {
				alert("Please Select Karigar");
				return;
			}
			var items_group = $('#items_group').val();
			if (!items_group) {
				alert("Please Select Items Group");
				return;
			}
			var item = $('#item').val();
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();

			var form = $(document.createElement('form'));
			$(form).attr("action", "<?= site_url('admin/karigar_transfer_entry/add') ?>");
			$(form).attr("method", "POST");
			$(form).css("display", "none");

			var input_csrf = $("<input>")
				.attr("type", "text")
				.attr("name", "<?= $this->security->get_csrf_token_name(); ?>")
				.val("<?= $this->security->get_csrf_hash(); ?>");
			$(form).append($(input_csrf));

			var input_karigar = $("<input>")
				.attr("type", "text")
				.attr("name", "karigar")
				.val(karigar);
			$(form).append($(input_karigar));

			var input_items_group = $("<input>")
				.attr("type", "text")
				.attr("name", "items_group")
				.val(items_group);
			$(form).append($(input_items_group));

			var input_item = $("<input>")
				.attr("type", "text")
				.attr("name", "item")
				.val(item);
			$(form).append($(input_item));

			var input_from_date = $("<input>")
				.attr("type", "text")
				.attr("name", "from_date")
				.val(from_date);
			$(form).append($(input_from_date));

			var input_to_date = $("<input>")
				.attr("type", "text")
				.attr("name", "to_date")
				.val(to_date);
			$(form).append($(input_to_date));

			var input_phase = $("<input>")
				.attr("type", "text")
				.attr("name", "phase")
				.val(phase);
			$(form).append($(input_phase));

			$(document.body).append(form);

			$(form).submit();
		});

		jQuery(function() {

			$('.select-customer').select2({
				width: 200,
				theme: 'bootstrap4'
			});

			$(".select-item").select2({
				// minimumInputLength: 2,
				width: 200,
				theme: 'bootstrap4',
				ajax: {
					url: location.protocol + '//' + location.hostname + "/admin/common/getItem",
					type: "post",
					dataType: 'json',
					delay: 300,
					data: function(params) {
						return {
							searchTerm: params.term
						};
					},
					processResults: function(response) {

						return {
							results: response
						};
					},
					cache: true
				}
			});
		});
		jQuery(function() {
			$(".select-karigar").select2({
				// minimumInputLength: 2,
				width: 200,
				theme: 'bootstrap4',
				ajax: {
					url: location.protocol + '//' + location.hostname + "/admin/common/getKarigar",
					type: "post",
					dataType: 'json',
					delay: 300,
					data: function(params) {
						return {
							searchTerm: params.term
						};
					},
					processResults: function(response) {

						return {
							results: response
						};
					},
					cache: true
				}
			});
		});

		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		});

		$(document).on('click', '#search_btn', function() {
			var date = $('#date').val();
			var items_group = $('#items_group').val();
			location.href = '<?= site_url('admin/report/daybook_report/') ?>' + date + '/' + items_group;
		});
	});
</script>
