<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <div class="col-sm-11">
                    <h1 class="card-title"><b> Customer Report </b></h1>
                </div>
                <div class="col-sm-1 ms-5 ps-5">
                    <a class="btn btn-action bg-primary text-white"
                        href="<?= base_url('registration/customer/index/add') ?>">
                        <i class="far fa-plus card-title" aria-hidden="true">
                        </i>
                    </a>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="table-responsive">
                    <div class="col-md-12 mb-5 ">
                        <div class="mt-3">
                            <table id="customer" class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Serial No </th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Mobile Number</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Account Type</th>
                                        <th scope="col">Opening Amount</th>
                                        <th scope="col">Opening Amount Type</th>
                                        <th scope="col">Opening Fine</th>
                                        <th scope="col">Opening Fine Type</th>
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
        var table = $('#customer').DataTable({
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
                'url': "<?= base_url(); ?>registration/customer/getlist",
                'data': function (data) {
                    data.todate = $('#todate').val();
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
                data: 'mobile'
            },
            {
                data: 'city_name'
            },
            {
                data: 'account_type_name'
            },
            {
                data: 'opening_amount'
            },
            {
                data: 'opening_amount_type',
                'render': function (data, type, row) {
                    if (data === 'JAMA') {
                        return '<span class="badge bg-primary">' + data + '</span>';
                    } else if (data === 'BAKI') {
                        return '<span class="badge bg-danger">' + data + '</span>';
                    }
                }

            },
            {
                data: 'opening_fine'
            },
            {
                data: 'opening_fine_type',
                'render': function (data, type, row) {
                    if (data === 'JAMA') {
                        return '<span class="badge bg-primary">' + data + '</span>';
                    } else if (data === 'BAKI') {
                        return '<span class="badge bg-danger">' + data + '</span>';
                    }
                }
            },
            {
                data: 'created_at'
            },
            ],
        });
    });
</script>
