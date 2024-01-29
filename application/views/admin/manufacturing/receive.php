<div class="row">
    <table class="table card-table table-vcenter text-center text-nowrap ">
        <thead class="thead-light">
            <th>Metal Type</th>
            <th scope="col">Weight(Gm)</th>
            <th scope="col">Touch (%)</th>
            <th scope="col"></th>
        </thead>

        <tbody class="paste append-here">
            <tr class="main-row">
                <td>
                    <select class="form-select select2 metal_type_id" name="metal_type_id[]">
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
                                                        placeholder="Enter Weight" value=""
                                                        required>
                                                </td>                                <td>
                                                    <input class="form-control touch" type="number" name="touch[]"
                                                        placeholder="Enter touch(%)"
                                                        value="" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-btn">X</button>
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