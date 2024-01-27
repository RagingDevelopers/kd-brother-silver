<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Garnu </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("manufacturing/garnu/index/update/{$update['id']}") : base_url('manufacturing/garnu/index/store') ?>"
                            method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter Garnu Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Garnu Weight(Gm): </label>
                                    <input class="form-control gweight" type="number" name="garnu_weight"
                                        placeholder="Enter Garnu Weight(Gm)"
                                        value="<?= $update['garnu_weight'] ?? null ?>" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd">Tunch(%): </label>
                                    <input class="form-control gtunch" type="number" name="tunch" placeholder="Enter Tunch (%)"
                                        value="<?= $update['tunch'] ?? null ?>"  required >
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Silver: </label>
                                    <input class="form-control gsilver" type="number" name="silver" placeholder="Silver(Gm)"
                                        value="<?= $update['silver'] ?? null ?>"  required readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Copper: </label>
                                    <input class="form-control gcopper" type="number" name="copper" placeholder="Copper(Gm)"
                                        value="<?= $update['copper'] ?? null ?>" required readonly>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-header bg-light">
                                </div>
                                <div class="row">
                                    <table class="table table-borderless">
                                        <thead class="thead-light">
                                            <th scope="col">Metal Type</th>
                                            <th scope="col">Weight(Gm)</th>
                                            <th scope="col">Tunch(%)</th>
                                            <th scope="col">Silver(Gm)</th>
                                            <th scope="col">Copper(Gm)</th>
                                            <th scope="col"></th>
                                        </thead>

                                        <tbody class="paste append-here">
                                            <?php
                                            if (!empty($update)) {
                                                $j = 1;
                                                foreach ($items as $row) { ?>
                                                    <tr class="sectiontocopy">
                                                        <input type="hidden" class="sdid" name="sdid[]"
                                                            value="<?= $row['id'] ?? null; ?>" />
                                                        <td>
                                                            <select class="form-select select2 metal_type_id"
                                                                name="metal_type_id[]">
                                                                <option>Select Metal</option>
                                                                <?php
                                                                $metal_type = $this->db->get('metal_type')->result();
                                                                foreach ($metal_type as $value) {
                                                                    ?>
                                                                    <option value="<?= $value->id; ?>" <?php if (isset($row) && $value->id == $row['metal_type_id']) {
                                                                          echo 'selected';
                                                                      } ?>><?= $value->name; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control weight" type="number"
                                                                name="weight[]" placeholder="Enter Weight"
                                                                value="<?= $row['weight'] ?? null ?>" required>
                                                        </td>
                                                        <td>
                                                            <input class="form-control tunch" type="number" name="tunch[]"
                                                                placeholder="Enter tunch(%)"
                                                                value="<?= $row['tunch'] ?? null ?>" required>
                                                        </td>
                                                        <td>
                                                        <input class="form-control silver" type="number" name="silver[]"
                                                                placeholder="Silver(Gm)"
                                                                value="<?= $row['silver'] ?? null ?>"  required readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control copper" type="number" name="copper[]"
                                                                placeholder="Copper(Gm)"
                                                                value="<?= $row['copper'] ?? null ?>"  required readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger del">X</button>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else { ?>

                                                <tr class="sectiontocopy">
                                                    <input type="hidden" class="sdid" name="sdid[]" value="0">
                                                    <td>
                                                        <select class="form-select select2 metal_type_id"
                                                            name="metal_type_id[]">
                                                            <option>Select Item</option>
                                                            <?php
                                                            $metal_type = $this->db->get('metal_type')->result();
                                                            foreach ($metal_type as $value) {
                                                                ?>
                                                                <option value="<?= $value->id; ?>">
                                                                    <?= $value->name; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input class="form-control weight" type="number" name="weight[]"
                                                            placeholder="Enter weight(Gm)" value="" required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control tunch" type="number" name="tunch[]"
                                                            placeholder="Enter tunch(%)" value="" required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control silver" type="number" name="silver[]"
                                                            placeholder="Silver(Gm)" value="" required readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control copper" type="number" name="copper[]"
                                                            placeholder="Copper(Gm)" value="" required readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger del">X</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td> 
                                                <td></td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Used Weight: </label>
                                                            <input class="form-control total_used_weight" type="number"
                                                                name="weight[]" placeholder="Weight(Gm)"
                                                                value="" required readonly>
                                                        </td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Used Silver: </label>
                                                            <input class="form-control total_used_silver" type="number" name="silver[]"
                                                                placeholder="Silver(Gm)"
                                                                value="" required readonly>
                                                        </td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Used Copper: </label>

                                                        <input class="form-control total_used_copper" type="number" name="copper[]"
                                                                placeholder="copper(Gm)"
                                                                value=""  required readonly>
                                                        </td>
                                            </tr>
                                            <tr>
                                                <td></td> 
                                                <td></td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Un-Used Weight: </label>
                                                            <input class="form-control total_unused_weight" type="number"
                                                                name="weight[]" placeholder="Weight(Gm)"
                                                                value="" required readonly>
                                                        </td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Un-Used Silver: </label>
                                                            <input class="form-control total_unused_silver" type="number" name="silver"
                                                                placeholder="Silver(Gm)"
                                                                value="" required readonly>
                                                        </td>
                                                        <td>
                                                        <label class="form-label" for="prd">Total Un-Used Copper: </label>

                                                        <input class="form-control total_unused_copper" type="number" name="silver"
                                                                placeholder="Copper(Gm)"
                                                                value=""  required readonly>
                                                        </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-success" id="add">Add
                                                        Row</button>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script class="javascript">
    var main_row = '';
        $(document).ready(function () {
            main_row = $(".sectiontocopy")[0].outerHTML;
            $('.metal_type_id').each(function () {
                $(this).select2({
                    width: '100'
                });
            });
            
            $("#add").click(function () {
                $(".append-here").append(main_row);
                $('.append-here tr').last().find('.sdid').val(0);
                $('.append-here tr').last().find('.weight, .tunch,.silver,.copper').val('');
                $('.append-here tr').last().find('.metal_type_id').select2();
            });

            $(document).on('click', '.del', function () {
                var metal_type = $(".metal_type_id").length;
                if (metal_type > 1) {
                    $(this).parent().parent().remove();
                }
            });

            $(document).on('keyup', '.tunch,.weight,.gtunch,.gweight', function () {
                var $row = $(this).parent().parent(); 

                var tunch = parseFloat($row.find('.tunch').val()) || 0;
                var weight = parseFloat($row.find('.weight').val()) || 0;
                var gtunch = parseFloat($row.find('.gtunch').val()) || 0;
                var gweight = parseFloat($row.find('.gweight').val()) || 0;

                var gsilver = (gweight * gtunch) / 100;
                $row.find('.gsilver').val(gsilver);
                var gcopper = gweight - gsilver;
                $row.find('.gcopper').val(gcopper);

                var silver = (weight * tunch)/100;
                $row.find('.silver').val(silver);
                var copper=weight-silver;
                $row.find('.copper').val(copper);

                var totalUsedWeight = 0;
                var totalUsedSilver = 0;
                var totalUsedCopper = 0;

                    $('.append-here tr').each(function () {
                        var row = $(this);
                        var rowWeight = parseFloat(row.find('.weight').val()) || 0;
                        var rowSilver = parseFloat(row.find('.silver').val()) || 0;
                        var rowCopper = parseFloat(row.find('.copper').val()) || 0;

                        //var rowGWeight = parseFloat(row.find('.gweight').val()) || 0;
                        // var rowGSilver = parseFloat(row.find('.gsilver').val()) || 0;
                        // var rowGCopper = parseFloat(row.find('.gcopper').val()) || 0;

                        totalUsedWeight += rowWeight;
                        totalUsedSilver += rowSilver;
                        totalUsedCopper += rowCopper;
                    });
                    
                var totalUnusedWeight =gweight-totalUsedWeight;
                var totalUnusedSilver=gsilver-totalUsedSilver;
                var totalUnusedCopper=gcopper-totalUsedCopper;

                if (gweight === 0) {
                    totalUnusedWeight = 0;
                    totalUnusedSilver = 0;
                    totalUnusedCopper = 0;
                }

                $('.total_used_weight').val(totalUsedWeight);
                $('.total_used_silver').val(totalUsedSilver);
                $('.total_used_copper').val(totalUsedCopper);

                $('.total_unused_weight').val(totalUnusedWeight);
                $('.total_unused_silver').val(totalUnusedSilver);
                $('.total_unused_copper').val(totalUnusedCopper);
            });
        });
</script>
