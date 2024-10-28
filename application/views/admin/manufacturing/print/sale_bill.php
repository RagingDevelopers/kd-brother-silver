<style name="new">
.table {
    width: 100%;
    color: #212529;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
    padding: 0.3rem;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
    border-bottom-width: 2px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
    border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
border-color: #7abaff;
}

.table-hover .table-primary:hover {
background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
border-color: #8fd19e;
}

.table-hover .table-success:hover {
background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
border-color: #86cfda;
}

.table-hover .table-info:hover {
background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
border-color: #ed969e;
}

.table-hover .table-danger:hover {
background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
border-color: #fbfcfc;
}

.table-hover .table-light:hover {
background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
border-color: #95999c;
}

.table-hover .table-dark:hover {
background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
color: #fff;
background-color: #343a40;
border-color: #454d55;
}

.table .thead-light th {
color: #495057;
background-color: #e9ecef;
border-color: #dee2e6;
}

.table-dark {
color: #fff;
background-color: #343a40;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
border-color: #454d55;
}

.table-dark.table-bordered {
border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
color: #fff;
background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
.table-responsive-sm {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}
.table-responsive-sm > .table-bordered {
border: 0;
}
}

@media (max-width: 767.98px) {
.table-responsive-md {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}
.table-responsive-md > .table-bordered {
border: 0;
}
}

@media (max-width: 991.98px) {
.table-responsive-lg {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}
.table-responsive-lg > .table-bordered {
border: 0;
}
}

@media (max-width: 1199.98px) {
.table-responsive-xl {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}
.table-responsive-xl > .table-bordered {
border: 0;
}
}

.table-responsive {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
border: 0;
}

.table-responsive {
display: block;
width: 100%;
overflow-x: auto;
-webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
border: 0;
}
  .bill-title {
		background-color: lightblue;
	}

	.right {
		text-align: right;
	}

	/*.bill-table,*/
	/*.bill-table thead tr,*/
	/*.bill-table thead tr td,*/
	/*.bill-table tfoot tr,*/
	/*.bill-table tfoot tr td,*/
	/*.bill-table tbody tr,*/
	/*.bill-table tbody tr td {*/
	/*	border: 1px solid #eee;*/
	/*	border-collapse: collapse;*/
	/*}*/

	.customer-table,
	.customer-table tbody tr,
	.customer-table tbody tr td,
	.customer-table tr,
	.customer-table tr td {
		padding: 0px !important;
		margin: 0px !important;
	}
 
</style>
<style>
	.bill-title {
		background-color: lightblue;
	}

	.right {
		text-align: right;
	}
</style>
<?php //pre($bill_data); 
?>
<button type="button" class="btn btn-info"  id="printbtn">Print</button>
<div class="card" id="printarea">
	<div class="card-body">
		<div class="row mt-3">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="bill-title row" style="padding-left: 0; padding-right: 0; margin-left: 0px; margin-right: 0px;">
					<div class="col-md-4"></div>
					<div class="col-md-4" style="text-align:center;"><strong>Sales Estimate</strong></div>
					<div class="col-md-4" style="text-align:right;"><strong>ORIGINAL</strong></div>
				</div>
				<div class="row pr-2">
					<div class="col-md-6">
						<table class="customer-table" style="padding:0px;">
							<tr>
								<th>Name&nbsp;</th>
								<td>:&nbsp;<?= $customer['customer_name']; ?></td>
							</tr>
							<tr>
								<th>City </th>
								<td>:&nbsp;<?= $customer['city']; ?></td>
							</tr>
							<tr>
								<th>Date </th>
								<td>:&nbsp;<?= date("d-m-Y", strtotime($customer['date'])) ?></td>
							</tr>
							<tr>
								<th>Invoice No </th>
								<td>:&nbsp;<?= $customer['sequence_code'] ?></td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="customer-table" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td rowspan="3" class="right"><?= date("h:i:s A", strtotime($customer['created_at'])) ?></td>
							</tr>
						</table>
					</div>
				</div>
				<!-- <div class="row pl-2 pr-2">
					<div class="col-md-6"><strong>Date: </strong><?= date("d-m-Y", strtotime($customer['created_at'])) ?></div>
					<div class="col-md-6 right"><?= date("h:i:s A", strtotime($customer['created_at'])) ?></div>
				</div> -->
				<div class="row">
					<div class="col-md-12">
						<table width="100%" class="bill-table table">
							<thead>
								<tr>
									<th>No.</th>
									<th>Item Name</th>
									<th>Sub Item Name</th>
									<th class="right">Gr.Wt.</th>
									<th class="right">Le.Wt.</th>
									<th class="right">Nt.Wt.</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$totalGrWt = 0;
								$totalNtWt = 0;
								$totalLeWt = 0;
								$totalDiscount = 0;
								$i = 1;
								?>
								<?php foreach ($bill_data as $v) {
									$totalGrWt += $v['gross_weight'];
									$totalNtWt += $v['net_weight'];
									$totalLeWt += $v['less_weight'];
									$totalDiscount +=0;
									?>
                                    
									<tr>
										<td><?= $i++; ?></td>
										<td><?= $v['item_name'] ?></td>
										<td><?= $v['sub_item_name'] ?></td>
										<td class="right"><?= $v['gross_weight'] ?></td>
										<td class="right"><?= $v['less_weight'] ?></td>
										<td class="right"><?= $v['net_weight'] ?></td>
									</tr>

								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th class="right"></th>
									<th class="right"><?= $totalGrWt ?></th>
									<th class="right"><?= $totalLeWt ?></th>
									<th class="right"><?= $totalNtWt ?></th>
								</tr>
								<?php if($totalDiscount>0){ ?>
								<tr>
									<th></th>
									<th></th>
									<th class="right"></th>
									<th class="right"></th>
									<th class="right">Discount</th>
									<th class="right"><?= $totalDiscount ?></th>
								</tr>
								<tr>
									<th></th>
									<th></th>
									<th class="right"></th>
									<th class="right"></th>
									<th class="right">Final Amount</th>
									<th class="right"><?= $totalAmount-$totalDiscount ?></th>
								</tr>
								<?php } ?>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">Note:- <?= $bill_data[0]['remark'] ?></div>
			<div class="col-md-2"></div>
		</div>
	</div>
</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js'></script>
<script>
   $(document).ready(function(){
        function printData()
        {
            var csslink="<link rel='stylesheet' type='text/css' href='<?php echo site_url('assets/back/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>' media='all' />";
            var csslink2="<link rel='stylesheet' type='text/css' href='<?php echo site_url('assets/back/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>' media='all' />";
            var csslink3="<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css' media='all' />";
            var csslink4="<link rel='stylesheet' type='text/css' href='<?php echo site_url('assets/back/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'); ?>' media='all' />";
           var divToPrint=document.getElementById("printarea");
           newWin= window.open("");
          newWin.document.write(csslink);
          newWin.document.write(csslink2);
           newWin.document.write(csslink3);
            newWin.document.write(csslink4);
          newWin.document.write("<style>"+$("style[name='new']").html()+"</style>");
           newWin.document.write(divToPrint.outerHTML);
          newWin.document.close();
            newWin.onload = function() { // wait until all resources loaded 
                newWin.focus(); // necessary for IE >= 10
                newWin.print();  // change window to mywindow
                newWin.close();// change window to mywindow
            };
        }
    
        $('#printbtn').on('click',function(){
            printData();
        });
   });
   
</script>
