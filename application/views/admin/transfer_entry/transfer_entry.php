<?php
$action = '';
$edit = '';
if (isset($row_data)) {
    $action = site_url('payment/transfer_entry/update/' . $row_data['id']);
    $edit = 'Edit';
} else {
    $action = site_url('payment/transfer_entry/insert');
    $edit = 'Add';
}
?>

<style>
    .span-fine-ans,
    .span-alloy {
        background-color: yellow;
    }

    .payment-form-table {
        width: 100%;
    }
</style>
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
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title"><?= $edit ?> Transfer Entry </h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form enctype="multipart/form-data" method="POST" action="<?= $action ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="payment-form-table">
                                <tr>
                                    <td>
                                        <label>Date</label>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control from" name="date" value="<?= date('Y-m-d') ?>" value="<?= (isset($row_data)) ? $row_data['date'] : ''; ?>" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="pt-3">
                                        <label>Account Name:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <select class="customer-select2 select2 form-control" name="customer" id="select-customer" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($customers as $c) : ?>
                                                <option value="<?= $c['id'] ?>" data-fine="<?= $c['opening_fine'] ?? 0 ?>" data-amount="<?= $c['opening_balance'] ?? 0 ?>" <?= (isset($row_data) && $row_data['customer_id'] == $c['id']) ? 'selected' : ''; ?>>
                                                    <?= $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <h4 class="text-blue pt-1 pb-0" id="closing-label"></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Total Amount:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" step="any" class="form-control" value="<?= (isset($row_data)) ? $row_data['total_amount'] : ''; ?>" name="total_amount" placeholder="Total Amount" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Narration:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <textarea class="form-control" placeholder="Narration" name="narration" cols="40"><?= (isset($row_data)) ? $row_data['narration'] : ''; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="payment-form-table">
                                <tr>
                                    <td class="">
                                        <label>Payment Type:*</label>
                                    </td>
                                    <td class="">
                                        <select class="transfer-select2 select2 form-control" name="payment_type">
                                            <option value="" <?= (isset($row_data) && $row_data['payment_type'] == 'payment_type') ? 'selected' : ''; ?>>
                                                Select Payment Type</option>
                                            <option value="credit" <?= (isset($row_data) && $row_data['payment_type'] == 'credit') ? 'selected' : ''; ?>>
                                                Credit</option>
                                            <option value="debit" <?= (isset($row_data) && $row_data['payment_type'] == 'debit') ? 'selected' : ''; ?>>
                                                Debit</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Silver :*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" step="any" value="<?= (isset($row_data)) ? $row_data['gold'] : ''; ?>" class="form-control" name="gold" placeholder="Silver " />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Transfer Account Name: </label>
                                    </td>
                                    <td class="pt-3">
                                        <select class="transfer-select2  select2 form-control" name="transfer_customer" required>
                                            <option value="">Select Transfer Account</option>
                                            <?php foreach ($customers as $c) : ?>
                                                <option value="<?= $c['id'] ?>" <?= (isset($row_data) && $row_data['transfer_customer_id'] == $c['id']) ? 'selected' : ''; ?>>
                                                    <?= $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Created By:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="text" value="<?= (isset($row_data)) ? $row_data['created_by'] : ''; ?>" class="form-control" name="created_by" placeholder="Created By" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <div class="card-footer">
                    <input type="submit" id="save_btn" name="submit_type" class="btn btn-primary" value="Save" />
                    <input type="submit" id="save_and_exit_btn" name="submit_type" class="btn btn-primary" value="Save & Exit" />
                    <input type="submit" id="save_and_print_btn" name="submit_type" class="btn btn-success" value="Save & Print" />
                    <a href="javascript:history.back()" class="btn btn-warning">Cancel</a>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>
    <!-- /.card -->
</div>
<script class="javascript">
    $(document).on('change', '#select-customer', function() {
        var select = $(this);
        var customer_id = select.val();
        $.ajax({
            showLoader: true,
            url: `${BaseUrl}report/account_ledger/customerAmtAndFine_CR_DB/${customer_id}`,
            type: "GET",
            success: function(data) {
                try {
                    var data = JSON.parse(data);
                    var fine = data.fine;
                    var amount = data.amount;

                    if (fine < 0) {
                        fineStr = 'Dr: ' + Math.abs(fine);
                    } else {
                        fineStr = 'Cr: ' + fine;
                    }

                    if (amount < 0) {
                        amountStr = 'Dr: ' + Math.abs(amount);
                    } else {
                        amountStr = 'Cr: ' + amount;
                    }

                    $('#closing-label').html('Gold ' + fineStr + ' &amp; Amt ' + amountStr);
                } catch (e) {
                    console.log(e);
                }
            }
        });
    });

    $(document).ready(function() {


        jQuery(function() {
            <?php if (isset($row_data)) { ?>
                $('#select-customer').val(<?= $row_data['customer_id'] ?>).trigger('change');
            <?php } ?>
        });

    });

    $(window).bind('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $('#save_btn').trigger('click');
                    break;
                case 'e':
                    event.preventDefault();
                    $('#save_and_exit_btn').trigger('click');
                    break;
                case 'p':
                    event.preventDefault();
                    $('#save_and_print_btn').trigger('click');
                    break;
                case 'c':
                    event.preventDefault();
                    history.back();
                    break;
            }
        }
    });
</script>