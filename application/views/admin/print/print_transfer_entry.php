<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= "Payment" ?></title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/fontawesome-free/css/all.min.css') ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?= site_url('assets/ionicon/css/ionicons.min.css') ?>">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/jqvmap/jqvmap.min.css') ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= site_url('assets/back/dist/css/adminlte.min.css') ?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/daterangepicker/daterangepicker.css') ?>">
	<!-- summernote -->
	<link rel="stylesheet" href="<?= site_url('assets/back/plugins/summernote/summernote-bs4.min.css') ?>">
	<style>
		body {
			margin: 20px;
		}

		.table {
			width: 100%;
		}

		.table,
		.table thead tr,
		.table thead tr th,
		.table thead tr td,
		.table tbody tr th,
		.table tbody tr td {
			border: 2px solid #777;
			border-collapse: collapse;
			padding: 5px;
		}
	</style>
</head>

<body>
	<?php //pre($data)
	?>
	<?php extract($data); ?>
	<div class="row">
		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2" style="padding-top: 10px;padding-bottom: 10px;">Transfer Entry</th>
					</tr>
					<tr>
						<th colspan="2">
							<p align="center"><?= ucfirst($payment_type) ?></p>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Customer
						</th>
						<th>
							<?= $customer_name ?>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Transfer To
						</th>
						<th>
							<?= $transfer_customer_name ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Date</th>
						<th><?= $date ?></th>
					</tr>
					<tr>
						<th> Silver </th>
						<th><?= $gold ?></th>
					</tr>
					<tr>
						<th>Total Amount</th>
						<th><?= $total_amount ?></th>
					</tr>
					<tr>
						<th>Narration</th>
						<th><?= $narration ?></th>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2" style="padding-top: 10px;padding-bottom: 10px;">Transfer Entry</th>
					</tr>
					<tr>
						<th colspan="2">
							<p align="center"><?= ucfirst($payment_type) ?></p>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Customer
						</th>
						<th>
							<?= $customer_name ?>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Transfer To
						</th>
						<th>
							<?= $transfer_customer_name ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Date</th>
						<th><?= $date ?></th>
					</tr>
					<tr>
						<th>Gold</th>
						<th><?= $gold ?></th>
					</tr>
					<tr>
						<th>Total Amount</th>
						<th><?= $total_amount ?></th>
					</tr>
					<tr>
						<th>Narration</th>
						<th><?= $narration ?></th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
		window.print();
	</script>
	<script src="<?= site_url('assets/back/plugins/jquery/jquery.min.js'); ?>"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= site_url('assets/back/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?= site_url('assets/back/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- bs-custom-file-input -->
	<script src="<?= site_url('assets/back/plugins/bs-custom-file-input/bs-custom-file-input.min.js'); ?>"></script>
	<!-- DataTables -->
	<script src="<?= site_url('assets/back/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?= site_url('assets/back/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
	<script src="<?= site_url('assets/back/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?= site_url('assets/back/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>

	<!-- ChartJS -->
	<script src="<?= site_url('assets/back/plugins/chart.js/Chart.min.js'); ?>"></script>
	<!-- Sparkline -->
	<script src="<?= site_url('assets/back/plugins/sparklines/sparkline.js'); ?>"></script>
	<!-- JQVMap -->
	<!-- <script src="<?= site_url('assets/back/plugins/jqvmap/jquery.vmap.min.js'); ?>"></script>
		<script src="<?= site_url('assets/back/plugins/jqvmap/maps/jquery.vmap.usa.js'); ?>"></script> 
	-->
	<!-- jQuery Knob Chart -->
	<script src="<?= site_url('assets/back/plugins/jquery-knob/jquery.knob.min.js'); ?>"></script>
	<!-- daterangepicker -->
	<script src="<?= site_url('assets/back/plugins/moment/moment.min.js'); ?>"></script>
	<script src="<?= site_url('assets/back/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?= site_url('assets/back/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
	<!-- Summernote -->
	<script src="<?= site_url('assets/back/plugins/summernote/summernote-bs4.min.js'); ?>"></script>
	<!-- overlayScrollbars -->
	<script src="<?= site_url('assets/back/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?= site_url('assets/back/dist/js/adminlte.js'); ?>"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?= site_url('assets/back/dist/js/demo.js'); ?>"></script>
</body>

</html>