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
                            <div class="modal-body">
                                <div class="row">
                                    <table class="table card-table table-vcenter text-center text-nowrap ">
                                        <thead class="thead-light">
                                            <th>Metal Type</th>
                                            <th scope="col">Weight(Gm)</th>
                                            <th scope="col">Touch (%)</th>
                                            <th scope="col"></th>
                                        </thead>

                                        <tbody class="paste append-here">
                                            <tr class="sectiontocopy">
                                                <td>
                                                    <select class="form-select select2 metal_type_id"
                                                        name="metal_type_id[]">
                                                        <option value="">Select Metal</option>
                                                        <?php
                                                        $metal_type = $this->db->get('metal_type')->result();
                                                        foreach ($metal_type as $value) { ?>
                                                            <option value="<?= $value->id; ?>">
                                                                <?= $value->name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input class="form-control weight" type="number" name="weight[]"
                                                        placeholder="Enter Weight" value="" required>
                                                </td>
                                                <td>
                                                    <input class="form-control touch" type="number" name="touch[]"
                                                        placeholder="Enter touch(%)" value="" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger del">X</button>
                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <td colspan="1"
                                                class="d-flex border border-0 align-content-start flex-wrap">
                                                <button type="button" class="btn btn-outline-warning" id="add">Add
                                                    Row</button>
                                            </td>
                                        </tfoot>
                                    </table>
                                </div>
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
    var main_row = '';
    $(document).ready(function () {
        main_row = $(".sectiontocopy")[0].outerHTML;


        $('.metal_type_id').each(function () {
            $(this).select2({
                width: '100',
                dropdownParent: $('#ReceivedModel')
            });
        });

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
                data: 'recieved',
            },
            {
                data: 'created_at'
            },
            ],
            "rowCallback": function (row, data) {
                console.log(row);
                if (data.recieved == 'YES') {
                    $(row).css('color', 'green');
                } else if (data.recieved == 'NO') {
                    $(row).css('color', 'red');
                }
            }

        });
        $('#todate').on('change', function () {
            table.clear()
            table.draw()
        });
        $('#fromdate').on('change', function () {
            table.clear()
            table.draw()
        });


        $(document).on('click', '.garnu_receive', function () {
            $("#ReceivedModel").modal('show');
            var id = $(this).data('receiveid');
        });

        // $('.metal_type_id').select2({
        //     width: '100',
        //     dropdownParent: $('#ReceivedModel')
        // })


        $("#add").click(function () {
            $(".append-here").append(main_row);
            $('.append-here tr').last().find('.sdid').val(0);
            $('.append-here tr').last().find('.touch, .weight,.metal_type_id').val('');
            $('.append-here tr').last().find('.metal_type_id').select2({
                width: '100',
                dropdownParent: $('#ReceivedModel')
            });
            $('.metal_type_id').each(function () {
            $(this).select2({
                width: '100',
                dropdownParent: $('#ReceivedModel')
            });
        });
        });
        $(document).on('click', '.del', function () {
            var metal_type_id = $(".metal_type_id").length;
            if (metal_type_id > 1) {
                $(this).parent().parent().remove();
            }
        });
    });
</script>