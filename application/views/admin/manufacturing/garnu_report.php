<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>

                <div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Received </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="garnu_receive">
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
                                            <td class="d-flex border border-0 align-content-start flex-wrap">
                                                <button type="button" class="btn btn-outline-warning"
                                                    id="add">AddRow</button>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="d-flex border border-0 justify-content-end  flex-wrap">
                                                <button type="button" class="btn btn-outline-primary submitBtn"
                                                    id="submitBtn">Submit</button>
                                            </td>
                                        </tfoot>
                                    </table>
                                </form>
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
                                        <th scope="col">Process</th>
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
$(document).ready(function() {
    main_row = $(".sectiontocopy")[0].outerHTML;

    $('#ReceivedModel').on('shown.bs.modal', function(e) {
        var modal = this;
        $('.metal_type_id').each(function() {
            $(this).select2({
                width: '100',
                dropdownParent: $(modal)
            });
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
            'data': function(data) {
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
                data: 'process'
            },
            {
                data: 'recieved'
            },
            {
                data: 'created_at'
            },
        ],
        "rowCallback": function(row, data) {
            if (data.recieved == 'YES') {
                $(row).css('color', 'green');
            } else if (data.recieved == 'NO') {
                $(row).css('color', 'red');
            }
        }
    });
    $('#todate').on('change', function() {
        table.clear()
        table.draw()
    });
    $('#fromdate').on('change', function() {
        table.clear()
        table.draw()
    });

	function reciveGarnu(id = null) {
    var postData = { id: id };
    return $.ajax({
        url: '<?= base_url('manufacturing/garnu/checkReceive'); ?>', // Make sure this is in a PHP file
        type: 'POST',
        dataType: 'json',
        data: postData,
        success: function(response) {
            if (response.success) {
                $(response.data).each(function(index, value) {
                    $(".append-here").append(main_row);
                    var $lastRow = $('.append-here tr').last();
                    $lastRow.find('.sdid').val(value.sdid);
                    $lastRow.find('.touch, .weight, .metal_type_id').val('');
                    $lastRow.find('.metal_type_id').val(value.metal_type_id).trigger('change');
                    $lastRow.find('.metal_type_id').select2({
                        width: '100',
                        dropdownParent: $('#ReceivedModel')
                    });
                });
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert("An error occurred.");
        }
    });
}


    $(document).on('click', '.receive-btn', function() {
		var id = $(this).data('receiveid');
		reciveGarnu(id).done(function(){
			$("#ReceivedModel").modal('show');
		});
    });

    $("#add").click(function() {
        $(".append-here").append(main_row);
        $('.append-here tr').last().find('.sdid').val(0);
        $('.append-here tr').last().find('.touch, .weight,.metal_type_id').val('');
        $('.append-here tr').last().find('.metal_type_id').select2({
            width: '100',
            dropdownParent: $('#ReceivedModel')
        });

        $('.metal_type_id').each(function() {
            $(this).select2({
                width: '100',
                dropdownParent: $('#ReceivedModel')
            });
        });
    });

    $(document).on('click', '.del', function() {
        var metal_type_id = $(".metal_type_id").length;
        if (metal_type_id > 1) {
            $(this).parent().parent().remove();
        }
    });

    // $(document).on('click', ".submitBtn", function() {

    //     var dataArray = [];
    //     $(".append-here tr").each(function() {
    //         var row = $(this);
    //         var metal_type_id = row.find(".metal_type_id").val();
    //         var touch = row.find(".touch").val();
    //         var weight = row.find(".weight").val();

    //         var rowData = {
    //             metal_type_id: metal_type_id,
    //             touch: touch,
    //             weight: weight,
    //         };
    //         dataArray.push(rowData);
    //     });
    //     $.ajax({
    //         url: "<?php echo base_url('manufacturing/garnu/receive'); ?>",
    //         type: "POST",
    //         data: {
    //             data: JSON.stringify(dataArray)
    //         },
    //         dataType: "json",
    //         success: function(response) {
    //             if (response.success) {
    //                 alert("Receive  successfully: " + response.message);
    //                 // location.reload();
    //             } else {
    //                 alert("Failed to add data: " + response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             alert("Error adding data: " + error);
    //         }
    //     });
    // });

});
</script>
