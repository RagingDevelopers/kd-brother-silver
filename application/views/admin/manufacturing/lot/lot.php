<style>
    .readonly {
        background-color: #d2e4e0;
    }
    .codeCopy {
		cursor: pointer;
	}
</style>
<div class="row">
    <div class="card">
        <div class="col-md-12">
            <b>
            <div class="card-header">
                <h3 class="card-title pt-2">Barcode</h3>&nbsp;&nbsp;&nbsp;
                <input type="text" value="<?= (!empty($barcode)) ? $barcode : ''; ?>" id="barcode" class="form-control" placeholder="Right-click to paste (use Ctrl+V)" style="display:inline-block; width:250px">
                <input type="hidden" id="csrf_token_name" />
                <input type="hidden" id="csrf_hash" />
                <input type="hidden" id="second_polish_id" />
                <input type="hidden" id="purchase_items_id" />
                <input type="hidden" id="submit_type" value="insert" data-id="0" />
                <input type="hidden" id="source_from" value="" data-id="0" />
                <div id="output-of-barcode"></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <?php include_once 'part_lot_headertable.php'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <?php include_once 'part_lot_1.php'; ?>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            </b>
        </div>
    </div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="lotCreation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lot Creation Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="LotCreation">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="w-100">
                                <tr>
                                    <td class="pt-3">
                                        <label>Gross.Wt:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" step="any" readonly class="form-control grossWeight readonly" value="0" name="grossWeight" placeholder="Gross Weight">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Less.Wt:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" step="any" class="form-control lessWeight" value="0" name="lessWeight" placeholder="Less Weight">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-3">
                                        <label>Net.Wt:*</label>
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" step="any" class="form-control netWeight" value="0" name="netWeight" placeholder="Net Weight">
                                        <input type="hidden" step="any" class="form-control barcode"  name="barcode">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end mt-2">
                        <div>
                            <button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="input-icon btn btn-primary btn-primary submit-btn">Save Changes
    							<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
    						</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row mt-2">
    <div class="card">
        <div class="col-md-12">
            <div class="card-header">
                <h3 class="card-title " style="display:block;">
                    Tag
                    <button class="btn btn-outline-secondary button__select-deselect_all">Select All</button>
                    <button class="btn btn-primary button_old__print-selected-tags"><i class="fa fa-print"></i></button>
                    <button class="btn btn-primary button_old__print-selected-barcode"><i class="fa fa-print"></i> &nbsp; Barcode</button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <?= form_open(site_url('manufacturing/lot/printCustomTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomoldTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_old_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_old_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomoldBarcode'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_old_custom_barcode',
                                    'target' => '_blank'
                                ], [
                                    'barcode' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomeventTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_event_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_old_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomflaTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_fla_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_fla_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomukTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_uk_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_uk_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                                <?= form_open(site_url('manufacturing/lot/printCustomanexTags'), [
                                    'method' => 'POST',
                                    'id' => 'form-print_anex_custom_tags',
                                    'target' => '_blank'
                                ], [
                                    'checked_anex_tags' => ''
                                ]) ?>
                                <?= form_close() ?>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <?php include 'part_lot_2.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php $time = time(); ?>
<script src="<?= site_url('assets/dist/js/lot/lot.js?v=' . $time); ?>"></script>
<script src="<?= site_url('assets/dist/js/lot/edit_lot.js?v=' . $time); ?>"></script>
<script>
    $(document).ready(function() {

        $(".select2").select2({
            placeholder: "-- Select --",
            allowClear: true,
        });
        
        $(document).on("click", ".codeCopy", function() {
			var textToCopy = $(this).text();
			var $temp = $("<textarea>");
			$("body").append($temp);
			$temp.val(textToCopy).select();
			document.execCommand("copy");
			$temp.remove();
			Swal.fire({
				icon: 'success',
				title: 'Copied!',
				text: `Copy Code: ${textToCopy}`,
				showConfirmButton: false,
				timer: 1500
			});
		});


        $('#barcode').on('contextmenu', function(e) {
            e.preventDefault();
            navigator.clipboard.readText().then((text) => {
                $('#barcode').val(text);
                if (text != "") {
                    SweetAlert("success", `${text}  Past Successfully!!`);
                } else {
                    SweetAlert("error", `Text Not Found`);
                }
            }).catch((err) => {
                SweetAlert("error", `Failed to read clipboard contents: ${err}`);
            });
        });


        if ($('#barcode').val().trim()) {
            $('#barcode').trigger('blur');
        }

        $(document).keydown(function(zEvent) {
            if (zEvent.which == "113") {
                zEvent.preventDefault();
                $('#submit-btn').trigger('click');
            }
        });

        $(document).on("click", ".lotCreationBarcode", function() {
            if($('#barcode').val() == ""){
                SweetAlert("error", "Please Enter Barcode..");
                return false;
            }
            $.ajax({
        		url: `${BaseUrl}manufacturing/lot/lotCreationBarcode`,
        		type: "POST",
        		data : {
                   barcode: $('#barcode').val(),
                },
        		success: function (response) {
        			var response = JSON.parse(response);
        			if (response.success == true) {
        				$("#lotCreation").modal("show");
                        $('.grossWeight').val(response.data.gross_weight);
                        $('.lessWeight').val(response.data.less_weight);
                        $('.netWeight').val(response.data.net_weight);
                        $('.barcode').val($('#barcode').val());
        			} else {
                        $('.barcode').val($('#barcode').val());
                        var lessWeight = $('.l-weight').text();
                        if(lessWeight != "" ){
                            $('.lessWeight').val(lessWeight);
                            $('.grossWeight').val(lessWeight);
                        }
                        $('.netWeight').val("0");
                        
                        var modal = $("#lotCreation");
                        modal.modal("show");
        			}
        		},
        		error: function () {
        			SweetAlert("error", "Error submitting form");
        		},
        	});
        });

        $(document).on("input", ".netWeight,.lessWeight", function() {
            var lessWeight = $('.lessWeight').val();
            var netWeight = $('.netWeight').val();
            $('.grossWeight').val(parseFloat(lessWeight || 0) + parseFloat(netWeight || 0));
        });
        
        $(document)
    	.on(
    		"focus",
    		".netWeight",
    		function () {
    			handleInputFocusAndBlur(this, "focus");
    		}
    	)
    	.on(
    		"blur",
    		".netWeight",
    		function () {
    			handleInputFocusAndBlur(this, "blur");
    		}
    	);
    	function handleInputFocusAndBlur(element, eventType) {
        	var $element = $(element);
        	if (eventType === "focus" && $element.val() == "0") {
        		$element.val("");
        	} else if (eventType === "blur" && $element.val() == "") {
        		$element.val("0");
        	}
        }
        
        $(document).on("submit", "#LotCreation", function (e) {
        	e.preventDefault();
        	
        	if($('.grossWeight').val() <= 0){
        	    SweetAlert("error", "Gross Weight should be Greater than equal to 00");
        	    return;
        	}
        	if($('.lessWeight').val() <= 0){
        	    SweetAlert("error", "Less Weight should be Greater than equal to 00");
        	    return;
        	}
        	if($('.netWeight').val() <= 0){
        	    SweetAlert("error", "Net Weight should be Greater than equal to 00");
        	    return;
        	}
        	alert_if("Are you confirm add this Data", () => {
            	var formData = $(this).serialize();
            	var self = $(this);
            	$.ajax({
            		url: `${BaseUrl}manufacturing/lot/lotCreationAdd`,
            		type: "POST",
            		data: formData,
            		beforeSend: (data) => {
            			self.find(".submit-btn span").show();
            			self.find(".submit-btn").attr("disabled", true);
            		},
            		success: function (response) {
            			var response = JSON.parse(response);
            			if (response.success == true) {
            				$("#lotCreation").modal("hide");
            				SweetAlert("success", response.message);
            			} else {
            				$("#lotCreation").modal("hide");
            				SweetAlert("error", response.message);
            			}
            		},
            		error: function () {
            			SweetAlert("error", "Error submitting form");
            		},
            		complete: () => {
            			self.find(".submit-btn span").hide();
            			self.find(".submit-btn").attr("disabled", false);
            		},
            	});
        	});
        });


        $(document).on('click', '#submit-btn', function() {

            var customer_id = $('select[name="m_customer"]').val();
            var item_id = $('select[name="m_item"]').val();
            var sub_item = $('select[name="sub_item"]').val();
            var group_id = $('select[name="m_group"]').val();
            var stamp = $('select[name="stamp"]').val();
            var gross_weight = $('#input-gross-weight').val();
            var touch = $('.touch').val();
            var l_weight = $('#input-less-weight').val() || 0;
            var amt = $('#input-amt').val();
            var tag = $('#barcode').val();
            var pcs = $('#input-pcs').val();

            var net_weight = $('#input-net-weight').val();
            var csrf_token_name = $('#csrf_token_name').val();
            var csrf_hash = $('#csrf_hash').val();
            // var index = parseInt($('.done-pcs').html()) + 1; // CHANGE
            var index = $('#select-design-code').val(); // CHANGE
            var submit_type = $('#submit_type').val();
            var update_id = $('#submit_type').data('id');
            var sourceFrom = $('#source_from').val();
            var other_amt = $('#input-other-amt').val();
            var term = $('#input-term').val();
            var order_no = $('#input-order-no').val();
            var client_logo_id = $('#select-logo').val();
            var pendingPcs = $('.pending-pcs').text() || 0;

            var data = {
                tag: tag,
                pcs: pcs,
                submit_type: submit_type,
                update_id: update_id,
                customer_id: customer_id,
                item_id: item_id,
                sub_item: sub_item,
                group_id: group_id,
                stamp: stamp,
                touch: touch,
                gross_weight: gross_weight,
                l_weight: l_weight,
                amt: amt,
                net_weight: net_weight,
                index: index,
                source_from: sourceFrom,
                other_amt: other_amt,
                term: term,
                order_no: order_no,
                client_logo_id: client_logo_id,
                ad_data: []
            }

            // 			console.log(data);

            net_weight = parseF(net_weight);
            gross_weight = parseF(gross_weight);

			if($("#barcode").val() != "" && $("#barcode").val() != null){
				if(pendingPcs > 0){
					if (net_weight > 0 && gross_weight > 0) {
						// $(document).Toasts('create', {
						// 	class: 'bg-warning',
						// 	title: 'Please Wait...',
						// 	subtitle: '',
						// 	body: '<div class="text-center to-close">' + '<i class="fas fa-sync fa-2x fa-spin"></i></div>'
						// });
						$('#data_ad_table').children('tbody').children('tr').each(function(i, v) {
							var ad_id = $(v).children('.td-calc').children('input.data_ad_id').val();
							var actual_weight = $(v).children('.td-actual-weight').children('.input-actual-weight').val();
							var master_weight = $(v).children('.td-master-weight').children('.input-master-weight').val();
							var weight = $(v).children('.td-weight').children('.input-weight').val();
							var pcs = $(v).children('.td-pcs').children('.input-pcs').val();
							var rate = $(v).children('.td-rate').children('.input-sal-rate').val();
							var amt = $(v).children('.td-amt').children('.input-sal-amt').val();
							var per = $(v).children('.td-per').children('.input-sal-per').val();
							var wr = $(v).children('.td-wr').children('.input-sal-wr').val();
							var ad_rs = $(v).children('td.td-ad-rate').children('.input-ad-rate').val();
							var final_rate = $(v).children('td.td-final-rate').children('.input-final-rate').val();
							var calc = $(v).children('.td-calc').children('.form-group').children('.select-amt-calc-on').val();
		
							var obj = {
								ad_id: ad_id,
								actual_weight: actual_weight,
								weight: weight,
								master_weight: master_weight,
								pcs: pcs,
								rate: rate,
								amt: amt,
								per: per,
								wr: wr,
								calc: calc,
								ad_rs: ad_rs,
								final_rate: final_rate
							};
		
							data.ad_data.push(obj);
						});
						// console.log(data);
						$.ajax({
							url: "<?= site_url('manufacturing/lot/saveLotCreation') ?>",
							type: "POST",
							data: data,
							success: function(data) {
								if (data == 'success') {
									SweetAlert("success", 'Save Successfully');
								} else {
									SweetAlert("error", 'Save Failed');
								}
							},
							complete: function(data) {
								$('.to-close').parent().prev().children('button').trigger('click');
								$('.close').trigger('click');
								$('#barcode').trigger('blur');
								$('#submit_type').val('insert');
								$('#submit_type').data('id', '0');
								// 		location.href = "<?= site_url('admin/lot/view/') ?>" + $('#barcode').val().trim();
								$('#input-gross-weight').val('');
								$('#input-net-weight').val('');
								$('#input-pcs').val(1);
								$('#input-less-weight').val('');
								$('#input-amt').val('');
								// $("#isLWeight").trigger('click');
								if ($("#isLWeight").is(":checked")) {
									$("#isLWeight").trigger('click');
								}
		
							}
						})
					} else {
						SweetAlert("error", 'Net Weight And Gross Weight Should be more than 0');
					}
				}else{
					SweetAlert("error", `Your Pending Pcs Is ${pendingPcs}`);
				}
			}else{
				SweetAlert("error", `Please Enter Barcode`);
			}
        });

        $(document).on('click', '.report-delete-row-btn', function() {
			var id = $(this).attr('data-id');
            alert_if("Do you want to Delete This Item ?", function() {
                $.ajax({
                    url: "<?= site_url('manufacturing/lot/delete/'); ?>" + id,
                    success: function(data) {
                        if (data == 'success') {
                            SweetAlert("success", 'Deleted Successfully!');
                        } else {
                            SweetAlert("error", 'Deleted Failed!');
                        }
                    },
                    complete: function(data) {
                        $('.to-close').parent().prev().children('button').trigger('click');
                        $('.close').trigger('click');
                        $('#submit_type').val('insert');
                        $('#submit_type').data('id', '0');
                        $('#barcode').trigger('blur');
                    }
                });
            });
        });

        $(document).on('click', '.report-edit-row-btn', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= site_url('manufacturing/lot/edit/'); ?>" + id,
                success: function(data) {
                    data = jQuery.parseJSON(data);
                    if (data.status) {
                        SweetAlert("success", 'You can edit this lot now!');
                        setDataEveryWhere(data);
                        calculateGrossWeight();
						// var selected_id = $('.item').attr('data-sub_item');
						// setSubItem($('.item').val(),$('.item'),selected_id);
                        $('#submit_type').val('update');
                        $('#submit_type').data('id', data.data.id);

						setTimeout(() => {
							var selected_id = $('.item').attr('data-sub_item');
							setSubItem($('.item').val(),$('.item'),selected_id);
						}, 100);
                    } else {
                        SweetAlert("error", 'Failed to fetch data!');
                    }
                },
                complete: function(data) {
					var selected_id = $('.item').attr('data-sub_item');
					setSubItem($('.item').val(),$('.item'),selected_id);
                    $('.to-close').parent().prev().children('button').trigger('click');
                    $('.close').trigger('click');
                    if ($('#submit_type').val() == "insert" || $('#submit_type').val() != "update") {
                        $('#barcode').trigger('blur');
                    }
                }
            });
        });

        $(document).on('click', '.report-print-row-btn', function() {
            var tagNo = $(this).parent().parent().children('td.report-td-tag-no').html();
            location.href = "<?= site_url('admin/lot/printTags/') ?>" + tagNo;
        });

    });
</script>
