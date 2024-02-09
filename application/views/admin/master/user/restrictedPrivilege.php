<?php $permission = explode(',', $update['permission'] ?? ''); ?>
<div class="card-body">
    <div class="accordion" id="masters_privilege">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-1" aria-expanded="false">
                    Masters
                </button>
            </h2>
            <div id="collapse-1" class="accordion-collapse collapse" data-bs-parent="#masters_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['process_add']; ?>" <?php if (isset($update) && in_array(privilege['process_add'], $permission)) {
                                             echo "checked";
                                         } else {
                                         } ?>>
                                    <span class="form-check-label">Process Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['process_view']; ?>" <?php if (isset($update) && in_array(privilege['process_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Process View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['process_edit']; ?>" <?php if (isset($update) && in_array(privilege['process_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Process Edit</span>
                                </label><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['city_add']; ?>" <?php if (isset($update) && in_array(privilege['city_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">City Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['city_view']; ?>" <?php if (isset($update) && in_array(privilege['city_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">City View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['city_edit']; ?>" <?php if (isset($update) && in_array(privilege['city_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">City Edit</span>
                                </label><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['account_type_add']; ?>" <?php if (isset($update) && in_array(privilege['account_type_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Account Type Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['account_type_view']; ?>" <?php if (isset($update) && in_array(privilege['account_type_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Account Type View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['account_type_edit']; ?>" <?php if (isset($update) && in_array(privilege['account_type_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Account Type Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['category_add']; ?>" <?php if (isset($update) && in_array(privilege['category_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Category Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['category_view']; ?>" <?php if (isset($update) && in_array(privilege['category_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Category View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['category_edit']; ?>" <?php if (isset($update) && in_array(privilege['category_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Category Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['item_add']; ?>" <?php if (isset($update) && in_array(privilege['item_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Item Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['item_view']; ?>" <?php if (isset($update) && in_array(privilege['item_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Item View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['item_edit']; ?>" <?php if (isset($update) && in_array(privilege['item_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Item Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_type_add']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_meterial_type_add'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Meterial Type Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_type_view']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_meterial_type_view'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Meterial Type View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_type_edit']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_meterial_type_edit'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Meterial Type Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_add']; ?>" <?php if (isset($update) && in_array(privilege['row_meterial_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Meterial Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_view']; ?>" <?php if (isset($update) && in_array(privilege['row_meterial_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Meterial View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_meterial_edit']; ?>" <?php if (isset($update) && in_array(privilege['row_meterial_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Meterial Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['metal_type_add']; ?>" <?php if (isset($update) && in_array(privilege['metal_type_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Metal Type Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['metal_type_view']; ?>" <?php if (isset($update) && in_array(privilege['metal_type_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Metal Type View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['metal_type_edit']; ?>" <?php if (isset($update) && in_array(privilege['metal_type_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Metal Type Edit</span>
                                </label><br>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['bank_add']; ?>" <?php if (isset($update) && in_array(privilege['bank_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Bank Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['bank_view']; ?>" <?php if (isset($update) && in_array(privilege['bank_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Bank View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['bank_edit']; ?>" <?php if (isset($update) && in_array(privilege['bank_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Bank Edit</span>
                                </label><br>
                            </div>
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion mt-3" id="Manufacturing_privilege ">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-2" aria-expanded="false">
                    Manufacturing
                </button>
            </h2>
            <div id="collapse-2" class="accordion-collapse collapse" data-bs-parent="#Manufacturing_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['garnu_add']; ?>" <?php if (isset($update) && in_array(privilege['garnu_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Garnu Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['garnu_view']; ?>" <?php if (isset($update) && in_array(privilege['garnu_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Garnu View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['garnu_edit']; ?>" <?php if (isset($update) && in_array(privilege['garnu_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Garnu Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>