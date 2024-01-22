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
                                <<option value="JAMA" <?php if (!empty($update) && $update['opening_amount_type'] == 'JAMA') {
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