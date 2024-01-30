<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Received </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body modal-body_2html">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary">Receipt</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-11">
                    <h1 class="card-title"><b> Garnu Report </b></h1>
                </div>
                <div class="col-sm-1 ms-5 ps-5">
                    <a class="btn btn-action bg-primary text-white"
                        href="<?= base_url('manufacturing/garnu/index/add') ?>">
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
                                <input type="date" id="fromdate" name="fromdddate" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <label>To date:</label> <br>
                                <input type="date" id="todate" name="todate" class="form-control">
                            </div>

                        </div>
                        <div class="mt-3">
                            <table id="garnu" class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Serial No </th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Garnu Weight</th>
                                        <th scope="col">Touch</th>
                                        <th scope="col">Silver</th>
                                        <th scope="col">Copper</th>
                                        <th scope="col">Creation Date</th>
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
    $(document).ready(function () {

        var table = $('#garnu').DataTable({
            "iDisplayLength": 5,
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
                'url': "<?= base_url(); ?>manufacturing/garnu/getlist",
                'data': function (data) {
                    data.todate = $('#todate').val();
                    data.fromdate = $('#fromdate').val();
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
                data: 'garnu_weight'
            },
            {
                data: 'touch'
            },
            {
                data: 'silver'
            },
            {
                data: 'copper'
            },
            {
                data: 'creation_date'
            },
            {
                data: 'recieved'
            },
            {
                data: 'created_at'
            },

            ],

        });
        $('#todate').on('change', function () {
            table.clear()
            table.draw()
        });
        $('#fromdate').on('change', function () {
            table.clear()
            table.draw()
        });


        $(document).on('click', '.Received', function () {
            $("#ReceivedModel").modal('show');
            var id = $(this).data('receiveid');
            $('.modal-body_2html').html("");
            $.ajax({
                url: "<?= base_url() . 'manufacturing/garnu/receive'; ?>",
                type: 'post',
                data: {
                    id: id,
                },
                success: function (res) {
                    $('.modal-body_2html').html(res);
                }
            });
        });
    });
</script>