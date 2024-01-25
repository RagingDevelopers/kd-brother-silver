<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Customer </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("registration/customer/index/update/{$update['id']}") : base_url('registration/customer/index/store') ?>"
                            method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter User  Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Mobile Number: </label>
                                    <input class="form-control" type="number" name="mobile"
                                        placeholder="Enter Mobile Number"
                                        value="<?= $update['mobile'] ?? null ?>" id="mobile" required>
                                </div>  
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> City: </label>
                                    <select class="form-select select2 " name="city_id" id="city_id">
                                        <option>Select City</option>
                                            <?php
                                            $city = $this->db->get('city')->result();
                                            foreach ($city as $value) {
                                            ?>
                                                <option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['city_id']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $value->name; ?></option>
                                            <?php } ?>
                                     </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Account Type: </label>
                                    <select class="form-select select2 " name="account_type_id" id="account_type_id">
                                        <option>Select Account Type</option>
                                            <?php
                                            $account_type = $this->db->get('account_type')->result();
                                            foreach ($account_type as $value) {
                                            ?>
                                                <option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['account_type_id']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $value->name; ?></option>
                                            <?php } ?>
                                     </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                    <div class="col-sm-3">
                                            <label class="form-label" for="prd"> Opening Amount: </label>
                                            <input class="form-control" type="number" name="opening_amount"
                                                placeholder="Enter Opening Amount"
                                                value="<?= $update['opening_amount'] ?? null ?>" id="opening_amount" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="prd"> Opening Amount Type: </label>
                                        <select class="form-select select2" id="opening_amount_type" name="opening_amount_type" >
                                    <option selected value="">Select Opening Amount Type</option>
                                    <option value="JAMA" <?php if (!empty($update) && $update['opening_amount_type'] == 'JAMA') {
                                                                        echo 'selected';
                                                                    } ?>>Jama</option>                      
                                    <option value="BAKI" <?php if (!empty($update) && $update['opening_amount_type'] == 'BAKI') {
                                                                echo 'selected';
                                                            } ?>>Baki</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <label class="form-label" for="prd"> Opening Fine: </label>
                                        <input class="form-control" type="number" name="opening_fine"
                                            placeholder="Enter Opening Fine"
                                            value="<?= $update['opening_fine'] ?? null ?>" id="opening_fine" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="prd"> Opening Fine Type: </label>
                                        <select class="form-select select2" id="opening_fine_type" name="opening_fine_type" >
                                    <option selected value="">Select Opening Fine Type</option>
                                    <option value="JAMA" <?php if (!empty($update) && $update['opening_fine_type'] == 'JAMA') {
                                                                        echo 'selected';
                                                                    } ?>>Jama</option>                      
                                    <option value="BAKI" <?php if (!empty($update) && $update['opening_fine_type'] == 'BAKI') {
                                                                echo 'selected';
                                                            } ?>>Baki</option>
                                        </select>
                                    </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-header bg-light">
                                </div>
                                <div class="row">
                                     <table class="table table-borderless">
                                            <thead class="thead-light">
                                                <th scope="col">Item</th>
                                                <th scope="col">Touch</th>
                                                <th scope="col">Extra Touch</th>
                                                <th scope="col">Wastage</th>
                                                <th scope="col">Label</th>
                                                <th scope="col">Rate</th>
                                                <th scope="col">Subtotal</th>
                                                <th scope="col"></th>
                                            </thead>  
                                          
                                            <tbody  class="paste append-here">     
                                            <?php
                                         if (!empty($update)) {
                                                $j = 1;
                                                 foreach($items as $row){ ?>                                             
                                                <tr class="sectiontocopy">                                               
                                                    <input type="hidden" class="sdid"  name="sdid[]" value="<?= $row['id'] ?? null;  ?>"/>
                                                    <td>
                                                        <select class="form-select select2 item_id" name="item_id[]" id="item_id">
                                                            <option>Select Item</option>
                                                                <?php
                                                                $item = $this->db->get('item')->result();
                                                                foreach ($item as $value) {
                                                                ?>
                                                                    <option value="<?= $value->id; ?>" <?php if (isset($row) && $value->id == $row['item_id']) {
                                                                                                            echo 'selected';
                                                                                                        } ?>><?= $value->name; ?></option>
                                                                <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control touch" type="number" name="touch[]" placeholder="Enter touch Amount"
                                                        value="<?= $row['touch'] ?? null ?>" id="touch" required>
                                                    </td>
                                                        <td>
                                                            <input class="form-control extra_touch" type="number" name="extra_touch[]" placeholder="Enter Extra touch  Name"
                                                            value="<?= $row['extra_touch'] ?? null ?>" id="extra_touch" required>
                                                        </td>
                                                        <td>
                                                            <input class="form-control wastage" type="number" name="wastage[]" placeholder="Enter Wastage Amount"
                                                            value="<?= $row['wastage'] ?? null ?>" id="wastage" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-select select2 label" id="label" name="label[]" >
                                                                <option selected value="">Select Label</option>
                                                                <option value="NET" <?php if (!empty($row) && $row['label'] == 'NET') {
                                                                                                    echo 'selected';
                                                                                                } ?>>NET</option>                      
                                                                <option value="PCS" <?php if (!empty($row) && $row['label'] == 'PCS') {
                                                                                            echo 'selected';
                                                                                        } ?>>PCS</option>
                                                                <option value="FIXED" <?php if (!empty($row) && $row['label'] == 'FIXED') {
                                                                                            echo 'selected';
                                                                                        } ?>>FIXED</option>
                                                                <option value="GROSS" <?php if (!empty($row) && $row['label'] == 'GROSS') {
                                                                                            echo 'selected';
                                                                                        } ?>>GROSS</option>
                                                            </select>
                                                        </td>
                                                    <td>
                                                        <input class="form-control rate" type="number" name="rate[]" placeholder="Enter Rate Amount"
                                                            value="<?= $row['rate'] ?? null ?>" id="rate" required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control sub_total" type="number" name="sub_total" placeholder="Subtotal"
                                                        id="sub_total" value="<?= $row['sub_total'] ?? null ?>" required readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger del">X</button>
                                                    </td>
                                                </tr>
                                                <?php }}else{?>

                                                <tr class="sectiontocopy">
                                                    <input type="hidden" class="sdid" name="sdid[]" value="0">
                                                    <td>
                                                        <select class="form-select select2 item_id" name="item_id[]" id="item_id">
                                                            <option>Select Item</option>
                                                                <?php
                                                                $item = $this->db->get('item')->result();
                                                                foreach ($item as $value) {
                                                                ?>
                                                                    <option value="<?= $value->id; ?>"><?= $value->name; ?></option>
                                                                <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control touch" type="number" name="touch[]" placeholder="Enter touch Amount"
                                                        value="" id="touch" required>
                                                    </td>
                                                        <td>
                                                            <input class="form-control extra_touch" type="number" name="extra_touch[]" placeholder="Enter Extra touch  Name"
                                                            value="" id="extra_touch" required>
                                                        </td>
                                                        <td>
                                                            <input class="form-control wastage" type="number" name="wastage[]" placeholder="Enter Wastage Amount"
                                                            value="" id="wastage" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-select select2 label" id="label" name="label[]" >
                                                                <option selected value="">Select Label</option>
                                                                <option value="NET" >NET</option>                      
                                                                <option value="PCS" >PCS</option>
                                                                <option value="FIXED" >FIXED</option>
                                                                <option value="GROSS" >GROSS</option>
                                                            </select>
                                                        </td>
                                                    <td>
                                                        <input class="form-control rate" type="number" name="rate[]" placeholder="Enter Rate Amount"
                                                            value="" id="rate" required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control sub_total" type="number" name="sub_total" placeholder="Subtotal"
                                                        id="sub_total" value="" required readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger del">X</button>
                                                    </td>
                                                </tr>
                                                    <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>      
                                                        <button type="button" class="btn btn-primary" id="add">+</button>
                                                    </td>                                    
                                                </tr>   
                                            </tfoot>
                                      </table>
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-md-5 md-ms-4">
                                        <label class="form-label" for="prd"> &nbsp </label>
                                        <input class="btn btn-primary " type="submit"
                                            value="<?= isset($update) ? "Update" : "Submit" ?>">
                                    </div>
                                </div>
                            </div>
                        </form>
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
            $("#add").click(function() {               
                $(".append-here").append(main_row);
                $('.append-here tr').last().find('.sdid').val(0);
            });

            $(document).on('click', '.del', function() {
                var item = $(".item_id").length;
                if (item > 1) {
                    $(this).parent().parent().remove();
                }
            });

            $(document).on('keyup', '.touch,.extra_touch,.wastage,.rate', function() {
                var $row = $(this).closest('tr'); 

                    var touch = parseFloat($row.find('.touch').val()) || 0;
                    var extra_touch = parseFloat($row.find('.extra_touch').val()) || 0;
                    var wastage = parseFloat($row.find('.wastage').val()) || 0;
                    var rate = parseFloat($row.find('.rate').val()) || 0;

                    var sum = touch + extra_touch + wastage + rate;
                    $row.find('.sub_total').val(sum);
            });
            
    });
    </script>
