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
                                        class="select_privilege" value="<?= privilege['row_material_type_add']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_material_type_add'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Material Type Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_type_view']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_material_type_view'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Material Type View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_type_edit']; ?>"
                                        <?php if (isset($update) && in_array(privilege['row_material_type_edit'], $permission)) {
                                            echo "checked";
                                        } else {
                                        } ?>>
                                    <span class="form-check-label">Row Material Type Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_add']; ?>" <?php if (isset($update) && in_array(privilege['row_material_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Material Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_view']; ?>" <?php if (isset($update) && in_array(privilege['row_material_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Material View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_edit']; ?>" <?php if (isset($update) && in_array(privilege['row_material_edit'], $permission)) {
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

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['stamp_add']; ?>" <?php if (isset($update) && in_array(privilege['stamp_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Stamp Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['stamp_view']; ?>" <?php if (isset($update) && in_array(privilege['stamp_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Stamp View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['stamp_edit']; ?>" <?php if (isset($update) && in_array(privilege['stamp_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Stamp Edit</span>
                                </label><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['unit_add']; ?>" <?php if (isset($update) && in_array(privilege['unit_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Unit Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['unit_view']; ?>" <?php if (isset($update) && in_array(privilege['unit_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Unit View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['unit_edit']; ?>" <?php if (isset($update) && in_array(privilege['unit_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Unit Edit</span>
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
                                        class="select_privilege" value="<?= privilege['main_garnu_add']; ?>" <?php if (isset($update) && in_array(privilege['main_garnu_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Main Garnu Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['main_garnu_view']; ?>" <?php if (isset($update) && in_array(privilege['main_garnu_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Main Garnu View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['main_garnu_edit']; ?>" <?php if (isset($update) && in_array(privilege['main_garnu_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Main Garnu Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
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
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['given_garnu']; ?>" <?php if (isset($update) && in_array(privilege['given_garnu'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Given Garnu</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['given_garnu_view']; ?>" <?php if (isset($update) && in_array(privilege['given_garnu_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Given Garnu View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['given_garnu_edit']; ?>" <?php if (isset($update) && in_array(privilege['given_garnu_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Given Garnu Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['receive_garnu']; ?>" <?php if (isset($update) && in_array(privilege['receive_garnu'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Receive Garnu</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['receive_garnu_view']; ?>" <?php if (isset($update) && in_array(privilege['receive_garnu_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Receive Garnu View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['receive_garnu_edit']; ?>" <?php if (isset($update) && in_array(privilege['receive_garnu_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Receive Garnu Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['main_report']; ?>" <?php if (isset($update) && in_array(privilege['main_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Main Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
								<label class="form-check">
									<input class="form-check-input" type="checkbox" name="privilege"
										class="select_privilege" value="<?= privilege['lot_creation_add']; ?>" <?php if (isset($update) && in_array(privilege['lot_creation_add'], $permission)) {
											  echo "checked";
										  } else {
										  } ?>>
									<span class="form-check-label">Lot Creation Add</span>
								</label>
								<label class="form-check">
									<input class="form-check-input" type="checkbox" name="privilege"
										class="select_privilege" value="<?= privilege['lot_creation_edit']; ?>" <?php if (isset($update) && in_array(privilege['lot_creation_edit'], $permission)) {
											  echo "checked";
										  } else {
										  } ?>>
									<span class="form-check-label">Lot Creation Edit</span>
								</label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['lot_creation_report']; ?>" <?php if (isset($update) && in_array(privilege['lot_creation_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Lot Creation Report</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['lot_creation_delete']; ?>" <?php if (isset($update) && in_array(privilege['lot_creation_delete'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Lot Creation Delete</span>
                                </label><br>
                            </div>
                        </div>                                           
                    </div><br>
                </div>
            </div>
        </div>
    </div>
	
    <div class="accordion mt-3" id="purchase">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-3" aria-expanded="false">
                    Sale / Purchase
                </button>
            </h2>
            <div id="collapse-3" class="accordion-collapse collapse" data-bs-parent="#purchase">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_add']; ?>" <?php if (isset($update) && in_array(privilege['purchase_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_view']; ?>" <?php if (isset($update) && in_array(privilege['purchase_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_edit']; ?>" <?php if (isset($update) && in_array(privilege['purchase_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Edit</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_delete']; ?>" <?php if (isset($update) && in_array(privilege['purchase_delete'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Delete</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_return_add']; ?>" <?php if (isset($update) && in_array(privilege['purchase_return_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Return Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_return_view']; ?>" <?php if (isset($update) && in_array(privilege['purchase_return_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Return View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_return_edit']; ?>" <?php if (isset($update) && in_array(privilege['purchase_return_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Return Edit</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['purchase_return_delete']; ?>" <?php if (isset($update) && in_array(privilege['purchase_return_delete'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Purchase Return Delete</span>
                                </label><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_add']; ?>" <?php if (isset($update) && in_array(privilege['sale_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_view']; ?>" <?php if (isset($update) && in_array(privilege['sale_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_edit']; ?>" <?php if (isset($update) && in_array(privilege['sale_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Edit</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_delete']; ?>" <?php if (isset($update) && in_array(privilege['sale_delete'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Delete</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_return_add']; ?>" <?php if (isset($update) && in_array(privilege['sale_return_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Return Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_return_view']; ?>" <?php if (isset($update) && in_array(privilege['sale_return_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Return View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_return_edit']; ?>" <?php if (isset($update) && in_array(privilege['sale_return_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Return Edit</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['sale_return_delete']; ?>" <?php if (isset($update) && in_array(privilege['sale_return_delete'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Sale Return Delete</span>
                                </label><br>
                            </div>
                        </div>  
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion mt-3" id="Payment_privilege ">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-4" aria-expanded="false">
                    Payment
                </button>
            </h2>
            <div id="collapse-4" class="accordion-collapse collapse" data-bs-parent="#Payment_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['jama_add']; ?>" <?php if (isset($update) && in_array(privilege['jama_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Jama Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['jama_view']; ?>" <?php if (isset($update) && in_array(privilege['jama_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Jama View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['jama_edit']; ?>" <?php if (isset($update) && in_array(privilege['jama_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Jama Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['baki_add']; ?>" <?php if (isset($update) && in_array(privilege['baki_add'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Baki Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['baki_view']; ?>" <?php if (isset($update) && in_array(privilege['baki_view'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Baki View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['baki_edit']; ?>" <?php if (isset($update) && in_array(privilege['baki_edit'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Baki Edit</span>
                                </label><br>
                            </div>
                        </div>                                           
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion mt-3" id="Report_privilege ">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-5" aria-expanded="false">
                    Report
                </button>
            </h2>
            <div id="collapse-5" class="accordion-collapse collapse" data-bs-parent="#Report_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_stock']; ?>" <?php if (isset($update) && in_array(privilege['row_material_stock'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Material Stock</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['metal_type_stock']; ?>" <?php if (isset($update) && in_array(privilege['metal_type_stock'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Metal Type Stock</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['row_material_closing_stock']; ?>" <?php if (isset($update) && in_array(privilege['row_material_closing_stock'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Row Material Closing Stock</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['account_ledger']; ?>" <?php if (isset($update) && in_array(privilege['account_ledger'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Account Ledger </span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['daybook_report']; ?>" <?php if (isset($update) && in_array(privilege['daybook_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">DayBook Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['balance_sheet_report']; ?>" <?php if (isset($update) && in_array(privilege['balance_sheet_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Balance Sheet Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['lot_report']; ?>" <?php if (isset($update) && in_array(privilege['lot_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Lot Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['silver_bhav_report']; ?>" <?php if (isset($update) && in_array(privilege['silver_bhav_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Silver Bhav Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['stock_report']; ?>" <?php if (isset($update) && in_array(privilege['stock_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Stock Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege"
                                        class="select_privilege" value="<?= privilege['lot_wise_rm_report']; ?>" <?php if (isset($update) && in_array(privilege['lot_wise_rm_report'], $permission)) {
                                              echo "checked";
                                          } else {
                                          } ?>>
                                    <span class="form-check-label">Lot Wise Row Material Report</span>
                                </label><br>
                            </div>
                        </div>                                           
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion mt-3" id="Setting_privilege ">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-6">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-6" aria-expanded="false">
                    Setting
                </button>
            </h2>
            <div id="collapse-6" class="accordion-collapse collapse" data-bs-parent="#Setting_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="privilege"
                                    class="select_privilege" value="<?= privilege['sequence_add']; ?>" <?php if (isset($update) && in_array(privilege['sequence_add'], $permission)) {
                                          echo "checked";
                                      } else {
                                      } ?>>
                                <span class="form-check-label">Sequence Add</span>
                            </label>
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="privilege"
                                    class="select_privilege" value="<?= privilege['sequence_view']; ?>" <?php if (isset($update) && in_array(privilege['sequence_view'], $permission)) {
                                          echo "checked";
                                      } else {
                                      } ?>>
                                <span class="form-check-label">Sequence View</span>
                            </label>
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="privilege"
                                    class="select_privilege" value="<?= privilege['sequence_edit']; ?>" <?php if (isset($update) && in_array(privilege['sequence_edit'], $permission)) {
                                          echo "checked";
                                      } else {
                                      } ?>>
                                <span class="form-check-label">Sequence Edit</span>
                            </label>
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="privilege"
                                    class="select_privilege" value="<?= privilege['sequence_delete']; ?>" <?php if (isset($update) && in_array(privilege['sequence_delete'], $permission)) {
                                          echo "checked";
                                      } else {
                                      } ?>>
                                <span class="form-check-label">Sequence Delete</span>
                            </label><br>
                        </div>       
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
