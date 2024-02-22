<style>
#example_table_billing_filter {
    float: right;
    padding-right: 15px;
}

#example_table_billing_length {
    padding: 10px 15px;
}

.dt-buttons {
    padding: 0px 0px 5px 15px;
}

#example_table_billing_info {
    float: left;
    padding: 15px;
}

#example_table_billing_paginate {
    float: right;
    padding: 10px 15px;
}

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
                        <a class="btn btn-action bg-primary text-white m-1 p-3"
                            href="<?= base_url(); ?>sales/create">
                            <i class="far fa-plus card-title"></i>
                        </a>
                    </div>
                    <div class="mt-3 p-2">
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="from">From Date</label>
                                    <input type="date" name="from" class=" form-control from" id="from">
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="to">To Date</label>
                                    <input type="date" name="to" class=" form-control to" id="to">
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="customer_id">Party</label>
                                    <select class="form-control customer_id select2" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    <?php foreach ($party as $c) { ?>
                                    <option value="<?= $c['id']; ?>">
                                        <?= $c['name']; ?>
                                    </option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <b>
                            <table id="example_table_billing"
                                class="table card-table table-vcenter text-center text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Date</th>
                                        <th>Party</th>                                       
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>                                      
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

<script>
$(document).ready(function() {
    $('#example_table_billing').DataTable();
});
</script>