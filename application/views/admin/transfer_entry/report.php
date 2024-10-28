<style>
	#cutomerJamaReport_filter {
		float: right;
		padding-right: 15px;
	}

	#cutomerJamaReport_length {
		padding: 10px 15px;
	}

	.dt-buttons {
		padding: 0px 0px 5px 15px;
	}

	#cutomerJamaReport_info {
		float: left;
		padding: 15px;
	}

	#cutomerJamaReport_paginate {
		float: right;
		padding: 10px 15px;
	}
</style>
<style>
    .billing_edit_tooltip {
        position: relative;
    }

    .billing_edit_tooltip .billing_edit_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .billing_edit_tooltip:hover .billing_edit_tooltip_text {
        visibility: visible;
    }

    .billing_delete_tooltip {
        position: relative;
    }

    .billing_delete_tooltip .billing_delete_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .billing_delete_tooltip:hover .billing_delete_tooltip_text {
        visibility: visible;
    }

    .bill_print_tooltip {
        position: relative;
    }

    .bill_print_tooltip .bill_print_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .bill_print_tooltip:hover .bill_print_tooltip_text {
        visibility: visible;
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
						<a class="btn btn-action bg-primary text-white m-1 p-3" href="<?= base_url(); ?>payment/transfer_entry/add">
							<i class="far fa-plus card-title"></i>
						</a>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row row-cards">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">From Date</label>
										<input type="date" value="<?=date('Y-m-01');?>" class="form-control" placeholder="YYY-MM-DD" id="from">
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
							<table class="table card-table table-vcenter text-center text-nowrap datatable" id="report">
								<thead>
									<tr>
									    <th>Sr. No.</th>
                                        <th>Action</th>
                                        <th>Payment Type</th>
                                        <th>Customer</th>
                                        <th>Transfer Customer</th>
                                        <th>Total Amount</th>
                                        <th>Gold</th>
                                        <th>Narration</th>
                                        <th>Date</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
								<tfoot>
								    <tr>
								        <td class="text-center text-primary" colspan="5"><h3 class="blinking-text">Totals ==></h3></td>
								        <td></td>
								        <td></td>
								        <td> -- </td>
								        <td> -- </td>
								        <td> -- </td>
								        <td> -- </td>
								    </tr>
								</tfoot>
							</table>
						</b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script class="javascript">
	$(document).ready(function() {

		$("#from").flatpickr();
		$("#to").flatpickr();
		// $("#group_by").select2();

		var datatable = $('#report').DataTable({
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
			    'showLoader': true,
                'url': '<?= site_url('payment/transfer_entry/ajax_getList') ?>',
				'data': function(data) {
					var from = $("#from").val();
					var to = $("#to").val();
					var group_by = $("#group_by").find("option:selected").val();
					data.from = from;
					data.to = to;
					
    				data.customer_id = ($('.customer_id')?.val() ?? null);
                    data.payment_type = ($('.payment_type')?.val() ?? null);
				}
			},
			'columns': [{
                data: 'srno',
                "bSortable": false
            },
            {
                data: 'id',
                "bSortable": false
            },
            {
                data: 'payment_type'
            },
            {
                data: 'customer'
            },
            {
                data: 'transfer_customer'
            },
            {
                data: 'total_amount'
            },
            {
                data: 'gold'
            },
            {
                data: 'narration'
            },
            {
                data: 'date'
            },
            {
                data: 'created_by'
            },
            {
                data: 'created_at'
            }
        ],
			"dom": 'lBfrtip',
			"buttons": [
				'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
			],
			footerCallback: function(row, data, start, end, display) {
                handelFooterTotal(
                    this.api(),
                    [5,6]
                );
            },

		});
    		
    	$(this).on('change', '.from,.to,.customer_id,.payment_type', function() {
            datatable.clear().draw();
        });


		$("#from,#to,#group_by").change(function() {
			datatable.clear();
			datatable.draw();
		});
	});
</script>
