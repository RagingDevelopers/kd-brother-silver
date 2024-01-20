<div class="card-body">
    <div class="accordion" id="masters_privilege">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false">
                    Masters
                </button>
            </h2>
            <div id="collapse-1" class="accordion-collapse collapse" data-bs-parent="#masters_privilege">
                <div class="accordion-body pt-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['process_add']; ?>" <?php if (isset($singleuser) && in_array(privilege['process_add'], $exploded_privilege)) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } else {
                                                                                                                                                                        } ?>>
                                    <span class="form-check-label">Process Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['process_view']; ?>" <?php if (isset($singleuser) && in_array(privilege['process_view'], $exploded_privilege)) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } else {
                                                                                                                                                                        } ?>>
                                    <span class="form-check-label">Process View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['process_edit']; ?>" <?php if (isset($singleuser) && in_array(privilege['process_edit'], $exploded_privilege)) {
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
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['city_add']; ?>" <?php if (isset($singleuser) && in_array(privilege['city_add'], $exploded_privilege)) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } else {
                                                                                                                                                                    } ?>>
                                    <span class="form-check-label">City Add</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['city_view']; ?>" <?php if (isset($singleuser) && in_array(privilege['city_view'], $exploded_privilege)) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } else {
                                                                                                                                                                        } ?>>
                                    <span class="form-check-label">City View</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privilege" class="select_privilege" value="<?= privilege['city_edit']; ?>" <?php if (isset($singleuser) && in_array(privilege['city_edit'], $exploded_privilege)) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } else {
                                                                                                                                                                        } ?>>
                                    <span class="form-check-label">City Edit</span>
                                </label><br>
                            </div>
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div><br>
</div>