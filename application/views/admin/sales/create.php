<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-header">
                        <h3 class="card-title">Create <b><?php echo $page_title; ?></b></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input name="date" name="date" class="form-control" id="date"
                                        value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Party<span class="text-danger">*</span></label>
                                    <select name="party_id" class="form-select select2" id="party_id">
                                        <option value="">Select Customer</option>
                                        <?php foreach ($party as $c) { ?>
                                        <option value="<?= $c['id']; ?>">
                                            <?= $c['name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div> <br />
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Item</th>
                                            <th>Stamp</th>
                                            <th>Unit</th>
                                            <th>Remarks</th>
                                            <th>Gross Weight</th>
                                            <th>Less Weight</th>
                                            <th>Net Weight</th>
                                            <th>Touch</th>
                                            <th>Wastage</th>
                                            <th>Piece</th>
                                            <th>Labour</th>
                                            <th>Rate</th>
                                            <th>Sub Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="TBody">
                                        <tr class="mainRow">
                                            <td class="plus">1</td>
                                            <td><select name="" id="" class="form-control select2 item">
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($item as $i) { ?>
                                                    <option value="<?= $i['id']; ?>">
                                                        <?= $i['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select></td>
                                            <td><select name="" id="" class="form-control select2 stamp">
                                                    <option value="">Select Stamp</option>
                                                    <?php foreach ($stamp as $s) { ?>
                                                    <option value="<?= $s['id']; ?>">
                                                        <?= $s['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select></td>
                                            <td><select name="" id="" class="form-control select2 unit">
                                                    <option value="">Select Unit</option>
                                                    <?php foreach ($unit as $u) { ?>
                                                    <option value="<?= $u['id']; ?>">
                                                        <?= $u['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><select name="" id="" class="form-control select2 labour">
                                                    <option value="">Select Labour</option>
                                                    <option value="net">Net</option>
                                                    <option value="pcs">Pcs</option>
                                                    <option value="fixed">Fixed</option>
                                                    <option value="gross">Gross</option>
                                                </select></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger delete-btn">X</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-warning add-row">Add Row</button>
                            </div>
                        </div><br />
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" id="store_billing_data"
                                style="margin-left: 92%;margin-top: 1%;">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $("#party_id").select2();
        $("#date").flatpickr();

        var mainRow = $('.mainRow')[0].outerHTML;
        $('.add-row').click(function() {
            var party_id = $("#party_id").val();
            if (party_id == '') {
                $('#party_id').select2('open');
            } else {
                var item = $('#TBody tr').last().find('.item');
                var stamp = $('#TBody tr').last().find('.stamp');
                var unit = $('#TBody tr').last().find('.unit');
                var labour = $('#TBody tr').last().find('.labour');

                if ((item.val() == '') || (stamp.val() == '') || (unit.val() == '') || (labour.val() == '')) {
                    alert('Please select all field then add new row.');
                    item.select2('open');
                } else {
                    var num = $('.plus').length + 1;
                    $('#TBody').append(mainRow);
                    $('#TBody tr').last().find('.plus').text(num);
                }
            }
        });

        $(document).on("click", '.delete-btn', function() {
            if ($('.delete-btn').length > 1) {
                if (confirm("Are you sure you want to Delete?")) {
                    $(this).parents('tr').remove();
                }
            } else {
                alert("one row is required");
            }
        });

    });
    </script>
    <script src="<?= base_url("assets/admin/") ?>scripts/billing.js"></script>