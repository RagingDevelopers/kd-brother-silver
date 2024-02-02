<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="col-sm-12">
                    <form action="<?= base_url('manufacturing/process/add'); ?>" method="post" class="" novalidate>
                        <div class="card-header">
                            <div class="card-status-top bg-blue"></div>
                            <h1 class="card-title"><b> Garnu </b></h1>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="row mt-1">                    
                                <input type="hidden" name="garnu_id" id="" class="form-control"
                                    value="<?= $data['id']; ?>">
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Garnu Name: </label>
                                    <input type="text" name="name" id="" class="form-control"
                                        placeholder="Enter Garnu Name" value="<?= $data['name']; ?>" autocomplete="off">
                                    <?php  echo form_error('name');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Received Quantity: </label>
                                    <input type="text" name="rc_qty" id="" class="form-control"
                                        placeholder="Enter Quantity" autocomplete="off">
                                    <?php  echo form_error('rc_qty');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Garnu Weight: </label>
                                    <input type="text" name="weight" id="" class="form-control"
                                        placeholder="Enter Weight" value="<?= $data['garnu_weight'];?>"
                                        autocomplete="off">
                                    <?php  echo form_error('weight');  ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="process">Process: </label>
                                    <select class="form-select select2 process" name="process">
                                        <option value=''>Select Process</option>
                                        <?php
                                        foreach ($process as $value) {
                                        ?>
                                        <option value="<?= $value->id; ?>"><?= $value->name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php  echo form_error('process');  ?>
                                </div>

                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="workers">Workers: </label>
                                    <select class="form-select select2 " name="workers" id="workers">
                                        <option value="">Select Worker:</option>
                                    </select>
                                    <?php  echo form_error('workers');  ?>
                                </div>

                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Remark: </label>
                                    <input type="text" name="remarks" id="" class="form-control"
                                        placeholder="Enter Remark" autocomplete="off">
                                    <?php  echo form_error('remarks');  ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Given Quantity: </label>
                                    <input type="text" name="given_qty" id="" class="form-control"
                                        placeholder="Enter Quantity" autocomplete="off">
                                    <?php  echo form_error('given_qty');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Given Weight: </label>
                                    <input type="text" name="given_weight" id="" class="form-control"
                                        placeholder="Enter Weight" autocomplete="off">
                                    <?php  echo form_error('given_weight');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Labour: </label>
                                    <input type="text" name="labour" id="" class="form-control"
                                        placeholder="Enter Labour" autocomplete="off">
                                    <?php  echo form_error('labour');  ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Received Quantity: </label>
                                    <input type="text" name="receive_qty" id="" class="form-control"
                                        placeholder="Enter Quantity" autocomplete="off">
                                    <?php  echo form_error('receive_qty');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Received Weight: </label>
                                    <input type="text" name="receive_weight" id="" class="form-control"
                                        placeholder="Enter Weight" autocomplete="off">
                                    <?php  echo form_error('receive_weight');  ?>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label class="form-label" for="">Total: </label>
                                    <input type="text" name="total" id="" class="form-control" autocomplete="off">
                                    <?php  echo form_error('total');  ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary ms-auto">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-header">
                        <div class="card-status-top bg-blue"></div>
                        <h1 class="card-title"><b> Process Report </b></h1>
                    </div>

                    <div class="card-body border-bottom py-3">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Given Date</th>
                                        <th scope="col">Process Name</th>
                                        <th scope="col">Worker Name</th>
                                        <th scope="col">Given Quantity</th>
                                        <th scope="col">Given Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php foreach ($table as $result) { ?>
                                    <tr>
                                        <td>1</td>
                                        <td><a href="" class="btn btn-warning">Edit</a></td>
                                        <td><?= $result->creation_date; ?></td>
                                        <td>@mdo</td>
                                        <td>sdf</td>
                                        <td><?= $result->given_qty; ?></td>
                                        <td><?= $result->given_weight; ?></td>
                                    </tr> 
                                          <?php } ?>                   
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.process').change(function() {
        var process_id = $(this).val();
        if (process_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>manufacturing/process/getWorkers",
                method: "POST",
                data: {
                    process_id
                },
                success: function(data) {
                    $('#workers').html(data);
                }
            });
        } else {
            $('#workers').html('<option value="">Select Workers</option>');
        }
    });
});
</script>