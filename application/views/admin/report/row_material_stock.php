<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="col-sm-11">
                    <h1 class="card-title"><b> Row Material Stock Report </b></h1>
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
                            <div class="col-sm-2">
                                <label>Row Material</label><br>
                                <select class="form-select select2" id="row_material">
                                    <option value=''>Select Row Material</option>
                                    <?php
                                    foreach ($row_material as $r) {
                                    ?>
                                        <option value="<?= $r->id ?>">
                                            <?= $r->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Garnu Name</label><br>
                                <select class="form-select select2" id="garnu">
                                    <option value=''>Select Garnu</option>
                                    <?php
                                    foreach ($garnu as $g) {
                                    ?>
                                        <option value="<?= $g->id ?>">
                                            <?= $g->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Process Name</label><br>
                                <select class="form-select select2" id="process">
                                    <option value=''>Select Process</option>
                                    <?php
                                    foreach ($process as $p) {
                                    ?>
                                        <option value="<?= $p->id ?>">
                                            <?= $p->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Type</label><br>
                                <select class="form-select select2" id="type">
                                    <option value=''>Select Type</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                            </div>
                        </div><br>
                        <div class="mt-3">
                            <table id="stock" class="table table-vcenter card-table">
                                <thead>
                                    <tr class="border border-dark">
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th colspan="3">Credit</th>
                                        <th colspan="3">Debit</th>
                                        <th scope="col"></th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Serial No </th>
                                        <th scope="col">Row Material Name</th>
                                        <th scope="col">Garnu Name</th>
                                        <th scope="col">Process Name</th>
                                        <th scope="col">Touch</th>
                                        <th scope="col">Weight</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Touch</th>
                                        <th scope="col">Weight</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Date</th>
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
    $(document).ready(function() {
        var table = $('#stock').DataTable({
            "iDisplayLength": 10,
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, 5000],
                [10, 25, 50, 100, 500, 1000, 5000]
            ],
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'searching': true,
            "ajax": {
                'url': "<?= base_url(); ?>report/Row_material_stock/fetchData",
                'data': function(data) {
                    data.todate = $('#todate').val();
                    data.fromdate = $('#fromdate').val();
                    data.row_material_id = $('#row_material').val();
                    data.garnu_id = $('#garnu').val();
                    data.process_id = $('#process').val();
                    data.types = $('#type').val();
                }
            },
            "columns": [{
                    data: 'id'
                },
                {
                    data: 'row_material'
                },
                {
                    data: 'garnu'
                },
                {
                    data: 'process'
                },
                {
                    data: 'ctouch'
                },
                {
                    data: 'cweight'
                },
                {
                    data: 'cquantity'
                },
                {
                    data: 'dtouch'
                },
                {
                    data: 'dweight'
                },
                {
                    data: 'dquantity'
                },
                {
                    data: 'date'
                },
            ],
            // drawCallback: function(settings) {
            //     $('[data-bs-toggle="tooltip"]').tooltip();
            // },
            "rowCallback": function(row, data) {
                if (data.type == "Credit") {
                    $(row).css({
                        "font-weight": "bold",
                        'color': 'green'
                    });
                } else if (data.type == "Debit") {
                    $(row).css({
                        "font-weight": "bold",
                        'color': 'red'
                    });
                }
            }
        });
        $('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#row_material,#garnu,#process,#type').on('change', function() {
			table.clear();
			table.draw();
		});
    });
</script>