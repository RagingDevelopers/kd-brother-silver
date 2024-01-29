<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <form
                action="<?= (isset($update)) ? base_url("manufacturing/garnu/index/update/{$update['id']}") : base_url('manufacturing/garnu/index/store') ?>"
                method="post" class="main-form" novalidate>
                <div class="card-header">
                    <div class="card-status-top bg-blue"></div>
                    <h1 class="card-title"><b> Garnu </b></h1>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="col-md-12 mb-5 ">
                        <div class="row ms-1">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter Garnu Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label"> Garnu Weight(Gm): </label>
                                    <input class="form-control mweight" type="number" name="garnu_weight"
                                        placeholder="Enter Garnu Weight(Gm)"
                                        value="<?= $update['garnu_weight'] ?? null ?>" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Touch(%): </label>
                                    <input class="form-control mtouch" type="number" name="touch"
                                        placeholder="Enter Touch (%)" value="<?= $update['touch'] ?? null ?>" required>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label"> Silver: </label>
                                    <input class="form-control msilver" type="number" name="silver"
                                        placeholder="Silver(Gm)" value="<?= $update['silver'] ?? null ?>" required
                                        readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label"> Copper: </label>
                                    <input class="form-control mcopper" type="number" name="copper"
                                        placeholder="Copper(Gm)" value="<?= $update['copper'] ?? null ?>" required
                                        readonly>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="row">
                                    <table class="table ">
                                        <thead class="thead-light">
                                            <th>Metal Type</th>
                                            <th scope="col">Weight(Gm)</th>
                                            <th scope="col">Touch (%)</th>
                                            <th scope="col">Silver(Gm)</th>
                                            <th scope="col">Copper(Gm)</th>
                                            <th scope="col"></th>
                                        </thead>

                                        <tbody class="paste append-here">
                                            <?php
                                            if (empty($update)) {
                                                $items[] = [
                                                    'metal_type_id' => '',
                                                    'weight'        => '',
                                                    'touch'         => '',
                                                    'silver'        => '',
                                                    'copper'        => '',
                                                    'id'            => 0
                                                ];
                                            }
                                            foreach ($items as $row) { ?>
                                                <tr class="main-row">
                                                    <input type="hidden" class="rowid" name="rowid[]"
                                                        value="<?= $row['id'] ?? null; ?>" />
                                                    <td>
                                                        <select class="form-select select2 metal_type_id"
                                                            name="metal_type_id[]">
                                                            <option value="">Select Metal</option>
                                                            <?php foreach ($metal_type as $value) { ?>
                                                                <option value="<?= $value->id; ?>" <?php if (isset($row) && $value->id == $row['metal_type_id']) {
                                                                      echo 'selected';
                                                                  } ?>><?= $value->name; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control weight" type="number" name="weight[]"
                                                            placeholder="Enter Weight" value="<?= $row['weight'] ?? null ?>"
                                                            required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control touch" type="number" name="touch[]"
                                                            placeholder="Enter touch(%)"
                                                            value="<?= $row['touch'] ?? null ?>" required>
                                                    </td>
                                                    <td>
                                                        <input class="form-control silver" type="number" name="silver[]"
                                                            placeholder="Silver(Gm)" value="<?= $row['silver'] ?? null ?>"
                                                            required readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control copper" type="number" name="copper[]"
                                                            placeholder="Copper(Gm)" value="<?= $row['copper'] ?? null ?>"
                                                            required readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger remove-btn">X</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="border border-none">
                                                <td colspan="1"
                                                    class="d-flex border border-0 align-content-start flex-wrap">
                                                    <button type="button" class="btn btn-outline-warning" id="add">Add
                                                        Row</button>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <label class="form-label" for="prd">Total Used Weight: </label>
                                                        <input class="form-control total_used_weight" type="number"
                                                            name="weight[]" placeholder="Weight(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label class="form-label" for="prd">Total Un-Used Weight:
                                                        </label>
                                                        <input class="form-control total_unused_weight" type="number"
                                                            name="weight[]" placeholder="Weight(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <div class="">
                                                        <label class="form-label" for="prd">Total Used Silver: </label>
                                                        <input class="form-control total_used_silver" type="number"
                                                            name="silver[]" placeholder="Silver(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label class="form-label" for="prd">Total Un-Used Silver:
                                                        </label>
                                                        <input class="form-control total_unused_silver" type="number"
                                                            name="silver" placeholder="Silver(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <label class="form-label" for="prd">Total Used Copper: </label>
                                                        <input class="form-control total_used_copper" type="number"
                                                            name="copper[]" placeholder="copper(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                    <div class='mt-2'>
                                                        <label class="form-label" for="prd">Total Un-Used Copper:
                                                        </label>
                                                        <input class="form-control total_unused_copper" type="number"
                                                            name="silver" placeholder="Copper(Gm)" value="" required
                                                            readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
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

<script class="javascript">
    // var main_row = '';
    var mainFunction = (function () {
        let main = {
            isInit: false,
            baseUrl: "<?= base_url() ?>",
            datatable: null,
            mainRow: null,
            init: function () {
                try {
                    if (!this.isInit) {
                        this.isInit = true;
                        mainFunction = null;
                        console.log("main Function Enabled")
                        if (this.isInit) {
                            main.ready.call();
                        }
                    }
                } catch (error) {
                    console.log(`mainFunction in isInit error : ${error}`);
                }
            },
            select2: (el, config = {}) => {
                Object.assign(config, {
                    placeholder: "-- Select --",
                    allowClear: true,
                });
                if (!$(el).data('select2')) {
                    $(el).select2(config);
                }
                return $(el)
            },
            lastTr: $('.append-here tr').last(),
            calculateMain: function (ref) {
                const row = $(ref).parent().parent();
                const weight = parseF(row.find('.mweight').val());
                const touch = parseF(row.find('.mtouch').val());
                const silver = parseF(row.find('.msilver').val());
                const copper = parseF(row.find('.mcopper').val());

                if (touch > 100) {
                    SweetAlert('warning', 'Touch should be less than 100');
                    row.find('.mtouch').val(100)
                    return;
                }
                const fSilver = (weight * touch) / 100;
                const fCopper = weight - fSilver;
                row.find('.msilver').val(fSilver);
                row.find('.mcopper').val(fCopper);

            },
            ready: function () {
                main.mainRow = $(".main-row")[0].outerHTML;
                $(document).ready(function () {
                    $('.metal_type_id').each(function () {
                        main.select2(this)
                    });
                    $(this).on('click', "#add", function () {
                        var metal = $('.metal_type_id').last();
                        console.log(metal)
                        if (metal.val() == '') {
                            return metal.select2('open');
                        }
                        $(".append-here").append(main.mainRow);
                        const lastTr = $('.append-here tr').last();
                        lastTr.find('.rowid').val(0);
                        lastTr.find('.weight, .touch,.silver,.copper').val('');
                        main.select2(lastTr.find('.metal_type_id')).select2('open');
                    });
                    $(this).on('click', '.remove-btn', function (e) {
                        var metal_type = $(".metal_type_id").length;
                        if (metal_type > 1) {
                            $(this).parent().parent().remove();
                        }
                    });
                    $('.main-form').submit(function (e) {
                        e.preventDefault();
                        main.validateSubmit(this)
                    });

                    $(this).on('keyup', '.mweight,.mtouch,.msilver,.mcopper', function () {
                        main.calculateMain(this)
                        main.calculation($('.append-here tr').eq(0).find('.touch'));
                    })
                    $(this).on('keyup', '.touch,.weight', function () {
                        main.calculation(this);
                    });
                });
            },
            calculation: function (ref) {
                var valid = true;
                const mainWeight = parseF($('.mweight').val()),
                    mainTouch = parseF($('.mtouch').val()),
                    mainSilver = parseF($('.msilver').val()),
                    mainCopper = parseF($('.mcopper').val()),
                    row = $(ref).parents('tr'),
                    weight = parseF(row.find('.weight').val()),
                    touch = parseF(row.find('.touch').val()),
                    silver = parseF(row.find('.silver').val()),
                    copper = parseF(row.find('.copper').val());

                if (mainWeight == 0)
                    return SweetAlert('warning', 'Weight should not be empty'), $(ref).val(''), $('.mweight').focus();

                if (touch > 100) {
                    return SweetAlert('warning', 'Touch should be less than equal to 100'), $(ref).val(0.00);
                }

                const fSilver = ((weight * touch) / 100);
                const fCopper = weight - fSilver;
                row.find('.silver').val(fSilver), row.find('.copper').val(fCopper);

                var totalUsedWeight = 0,
                    totalUsedSilver = 0,
                    totalUsedCopper = 0

                $('.append-here tr').each(function () {
                    var row = $(this),
                        rowWeight = parseF(row.find('.weight').val()),
                        rowSilver = parseF(row.find('.silver').val()),
                        rowCopper = parseF(row.find('.copper').val());

                    totalUsedWeight += rowWeight;
                    totalUsedSilver += rowSilver;
                    totalUsedCopper += rowCopper;
                });
                if (totalUsedWeight > mainWeight) {
                    SweetAlert('warning', 'Total Used Weight should not be equal to Main Weight');
                    row.find('.weight').val(0);
                    return;
                } else if (totalUsedSilver > mainSilver) {

                    SweetAlert('warning', 'Total Used Silver should not be greater than Main Silver'), valid = false
                    row.find('.silver').val(0);
                    return;
                }
                else if (totalUsedCopper > mainCopper) {
                    SweetAlert('warning', 'Total Used Copper should not be greater than Main Copper'), valid = false
                    row.find('.copper').val(0);
                    return;
                }

                $('.total_used_weight').val(totalUsedWeight)
                $('.total_used_silver').val(totalUsedSilver)
                $('.total_used_copper').val(totalUsedCopper)

                $('.total_unused_weight').val(mainWeight - totalUsedWeight)
                $('.total_unused_silver').val(mainSilver - totalUsedSilver)
                $('.total_unused_copper').val(mainCopper - totalUsedCopper)
            },
            validateSubmit: function (ref) {
                var preventEnter = true;
                const metal_type = $('.metal_type_id').length;
                const form = $(ref);
                const url = form.attr('action');
                const method = form.attr('method');

                var mainSilver = parseF($('.msilver').val()),
                    mainCopper = parseF($('.mcopper').val()),
                    mainWeight = parseF($('.mweight').val());

                if (mainWeight < 1) {
                    return SweetAlert('warning', 'Garnu Weight must be greater then: 1')
                }

                if (metal_type < 1)
                    return SweetAlert('warning', 'Metal Type should not be empty'), $('.metal_type_id').focus();

                $('.append-here tr').each(function () {
                    var row = $(this),
                        weight = (row.find('.weight')),
                        silver = (row.find('.silver')),
                        copper = (row.find('.copper'));

                    if (weight.val() == '') {
                        return SweetAlert('warning', 'Weight should not be empty'), weight.focus();
                    }
                    if (silver.val() == '') {
                        return SweetAlert('warning', 'Silver should not be empty'), silver.focus();
                    }
                    if (copper.val() == '') {
                        return SweetAlert('warning', 'Copper should not be empty'), copper.focus();
                    }
                    if (weight.val() < 1) {
                        return SweetAlert('warning', 'Weight must be greater then: 1')
                    }
                });


                form.find('input,select,textarea').each(function () {
                    if ($(this).val() == '') {
                        $(this).addClass('is-invalid');
                        preventEnter = false;
                    } else {
                        preventEnter = true
                        $(this).removeClass('is-invalid');
                    }
                });
                if (preventEnter)
                    form.unbind('form').submit();
            }
        };
        return {
            init: main.init
        }
    })();
    mainFunction.init.call();
</script>