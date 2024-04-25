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

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Silver Bhav Report</h3>
                </div>
                <!-- /.card-header -->
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
                            <label>Group By</label>
                            <select class="form-control" id="group_by">
                                <option value="">All</option>
                                <option value="MONTH">Month</option>
                                <option value="DAY">Day</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button class="btn btn-primary" id="search_btn" type="button">Search</button>
                            <!--<button class="btn btn-light" id="cancel_btn" type="button">Cancel</button>-->
                            <!--<button class="btn btn-warning" id="download_btn" type="button">Download</button>-->
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
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</section>
<input type="file" id="image" style="display:none" />
<input type="hidden" id="img_field" />
<input type="hidden" id="req_status" />
<script>
    $(document).ready(function() {
        $(document).on('click', '#search_btn', function() {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            var groupBy = $('#group_by').val();
            var data = {
                from_date: fromDate,
                to_date: toDate,
                group_by: groupBy
            }

            $.ajax({
                showLoader: true,
                url: "<?= base_url('report/silverBhavReport/ajax_silverBhavReport') ?>",
                type: "POST",
                data: data,
                success: function(data) {
                    $('#set_table_here').html(data);
                },
                complete: function(data) {
                    $('#report_table').DataTable({
                        'dom': 'lBfrtip',
                        "buttons": [{
                                'extend': 'print',
                                footer: true
                            },
                            {
                                'extend': 'csv',
                                footer: true
                            },
                            {
                                'extend': 'pdf',
                                footer: true
                            },
                            "copy"
                        ],
                    });
                }
            });
        });
    });
</script>
<?php $time = time(); ?>
<script>
    $(document).ready(function() {
        // var img;
        var dc_id = 0;
        var imageObj;
    });
</script>