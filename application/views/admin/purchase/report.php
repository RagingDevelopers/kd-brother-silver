<style>
    .billing_edit_tooltip {
        position: relative;
    }

    .billing_edit_tooltip .billing_edit_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .billing_edit_tooltip:hover .billing_edit_tooltip_text {
        visibility: visible;
    }

    .billing_delete_tooltip {
        position: relative;
    }

    .billing_delete_tooltip .billing_delete_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .billing_delete_tooltip:hover .billing_delete_tooltip_text {
        visibility: visible;
    }

    .bill_print_tooltip {
        position: relative;
    }

    .bill_print_tooltip .bill_print_tooltip_text {
        visibility: hidden;
        width: 120px;
        background-color: #555555;
        color: #fff;
        text-align: center;
        margin-bottom: 5px;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .bill_print_tooltip:hover .bill_print_tooltip_text {
        visibility: visible;
    }
    .totalColor {
        color:black;
    }
</style>
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-status-top bg-primary"></div>
                <div class="card-header justify-content-between">
                    <h3 class="card-title"><b><?= $page_title; ?> </b></h3>
                    <a class="btn btn-action bg-primary text-white m-1 p-3" href="<?= base_url(); ?>purchase/create">
                        <i class="far fa-plus card-title"></i>
                    </a>
                </div>
                <div class="mt-3 p-2">
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row row-cards">
                                <div class="col-md-2 ">
                                    <div class="form-group">
                                        <label for="from" class="form-label">From Date</label>
                                        <input type="date" value="<?=date('Y-m-01');?>" class=" form-control from" id="from">
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <div class="form-group">
                                        <label for="to" class="form-label">To Date</label>
                                        <input type="date" class=" form-control to" id="to">
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <div class="form-group">
                                        <label for="customer_id" class="form-label">Party</label>
                                        <select class="form-control select2" id="customer_id" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($party as $c) { ?>
                                                <option value="<?= $c['id']; ?>">
                                                    <?= $c['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <div class="form-group">
                                        <label for="item_id" class="form-label">Item</label>
                                        <select class="form-control select2" id="item_id">
                                            <option value="">Select Item</option>
                                            <?php foreach ($items as $item) { ?>
                                                <option value="<?= $item['id']; ?>">
                                                    <?= $item['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="group_by" class="form-label">Group By</label>
                                        <select class="form-control select2" id="group_by">
                                            <option value="">Select Group</option>
                                            <option value="item">Item</option>
                                            <option value="bill" selected>Bill</option>
                                            <option value="customer">Customer</option>
                                            <option value="voucher">Voucher</option>
                                            <option value="month">Month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-outline-primary float-end" id="search">Search</button>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-status-top bg-primary"></div>
                <div class="card-header justify-content-between">
                    <h3 class="card-title"><b><?= $page_title; ?> </b></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="report"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
		
        function checkGroupTypes($type = '') {
            const groupByTypes = [
                'item', 'bill', 'customer', 'voucher','month'
            ];

            return groupByTypes.includes($type);
        }
        
         $(document).on("change", "#group_by", function(e) {
    		    let now = new Date();
                let date = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('01').slice(-2);
    		    if($(this).val() == "month"){
    		        $("#from").val("")
    		    }else{
    		        $("#from").val(date)
    		    }
    		});
    		
    	    $(document).on("click", ".date", function(e) {
                let date = $.trim($(this).text());
                let parts = date.split('-');
            
                if (parts.length < 2) {
                    SweetAlert("warning", "Incorrect date format; please use 'Year-MonthName' format.");
                    return; 
                }
                
                
                $("#group_by").val('voucher').trigger('change');
                getMonthDateRange(parts[0], parts[1]);
                $('#search').click();
            });
            
        function getMonthDateRange(year, monthName) {
            var monthNamesToNumbers = {
                'january': 0, 'february': 1, 'march': 2, 'april': 3, 'may': 4, 'june': 5,
                'july': 6, 'august': 7, 'september': 8, 'october': 9, 'november': 10, 'december': 11
            };
        
            var monthIndex = monthNamesToNumbers[monthName.toLowerCase()];
            var firstDay = new Date(year, monthIndex, 1);
            var lastDay = new Date(year, monthIndex + 1, 0);
        
            // Directly set Date objects in Flatpickr
            if (fpFrom && fpTo) {
                fpFrom.setDate(firstDay);
                fpTo.setDate(lastDay);
            } else {
                SweetAlert("warning", "Flatpickr instances not found.");
            }
        }

        
        $(document).on("click", "#search", function(e) {
            e.preventDefault();
            $('#report').html(null);

            var groupBy = $("#group_by option:selected").val();

            if (checkGroupTypes(groupBy) === false) {
				// SweetAlert("warning", "Invalid Group type");
				$("#group_by").select2('open');
                return false;
            }

            data = {
                "from_date": $("#from").val(),
                "to_date": $("#to").val(),
                "customer": $("#customer_id option:selected").val(),
                "item": $("#item_id option:selected").val(),
                "group": groupBy,
				"url": "purchase",
            }
            
            $.ajax({
                showLoader: true,
                url: "<?= base_url('purchase/getReport'); ?>",
                timeout: 8000,
                type: "POST",
                processData: true,
                data: data,
                success: function(response) {
                    $('#report').html(response);
                }
            });
        });

		$('#search').click();
    });
</script>
