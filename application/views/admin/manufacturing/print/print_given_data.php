<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $page_title ?></title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/fontawesome-free/css/all.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?= base_url("assets") ?>/plugins/summernote/summernote-bs4.min.css">

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
	<div class="row">
		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2" style="text-align:center;padding-top: 10px;padding-bottom: 10px;">
							<img src="https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl=<?= $data['givenData']['garnu_id'] ?? "" . "_" . $data['givenData']['id'] ?? "" ?>&choe=UTF-8" />
							<br>
							<?= $data['givenData']['process_name'] ?? ""; ?>
						</th>
					</tr>
					<tr>
						<th colspan="2" class="text-center">
							Date :- <?= date('d-m-Y', strtotime($data['givenData']['creation_date'] ?? date('Y-m-d'))); ?>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Garnu Name :- <?= $data['givenData']['garnu_name'] ?? ""; ?>
						</th>
						<th>
							Touch <?= $data['givenData']['garnu_touch'] ?? ""; ?>
						</th>
					</tr>
					<tr>
						<th>Karigar</th>
						<th><?= $data['givenData']['worker_name'] ?? "" ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Code</th>
						<th><?= $garnu_id. "-" . $given_id ?></th>
					</tr>
					<tr>
						<th>Item Weight</th>
						<th><?= $data['givenData']['garnu_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Raw Material Weight</th>
						<th><?= $data['givenData']['row_material_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given Total Weight</th>
						<th><?= $data['givenData']['total_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given PCS</th>
						<th><?= $data['givenData']['given_qty'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given Remark</th>
						<th><?= $data['givenData']['remarks'] ?? "" ?></th>
					</tr>
					<?php
					if (!empty($data['givenRowMaterial'])) {
						foreach ($data['givenRowMaterial'] as $rmi => $rm) { ?>
							<tr>
								<th><?= $rm['row_material_name']; ?> (RM)</th>
								<th><?= $rm['quantity'] ?> - <?= $rm['weight'] ?> - <?= $rm['touch'] ?> (PCS - Weight - Touch)</th>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2" style="text-align:center;padding-top: 10px;padding-bottom: 10px;">
							<img src="https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl=<?= $data['givenData']['garnu_id'] ?? "" . "_" . $data['givenData']['id'] ?? "" ?>&choe=UTF-8" />
							<br>
							<?= $data['givenData']['process_name'] ?? ""; ?>
						</th>
					</tr>
					<tr>
						<th colspan="2" class="text-center">
							Date :- <?= date('d-m-Y', strtotime($data['givenData']['creation_date'] ?? date('Y-m-d'))); ?>
						</th>
					</tr>
					<tr style="background-color: #c8a5a3;">
						<th>
							Garnu Name :- <?= $data['givenData']['garnu_name'] ?? ""; ?>
						</th>
						<th>
							Touch <?= $data['givenData']['garnu_touch'] ?? ""; ?>
						</th>
					</tr>
					<tr>
						<th>Karigar</th>
						<th><?= $data['givenData']['worker_name'] ?? "" ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Code</th>
						<th><?= $garnu_id. "-" . $given_id ?></th>
					</tr>
					<tr>
						<th>Item Weight</th>
						<th><?= $data['givenData']['garnu_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Raw Material Weight</th>
						<th><?= $data['givenData']['row_material_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given Total Weight</th>
						<th><?= $data['givenData']['total_weight'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given PCS</th>
						<th><?= $data['givenData']['given_qty'] ?? "" ?></th>
					</tr>
					<tr>
						<th>Given Remark</th>
						<th><?= $data['givenData']['remarks'] ?? "" ?></th>
					</tr>
					<?php
					if (!empty($data['givenRowMaterial'])) {
						foreach ($data['givenRowMaterial'] as $rmi => $rm) { ?>
							<tr>
								<th><?= $rm['row_material_name']; ?> (RM)</th>
								<th><?= $rm['quantity'] ?> - <?= $rm['weight'] ?> - <?= $rm['touch'] ?> (PCS - Weight - Touch)</th>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</div>

	<script>
		window.print();
	</script>
	<script src="<?= base_url("assets") ?>/plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= base_url("assets") ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url("assets") ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- bs-custom-file-input -->
	<script src="<?= base_url("assets") ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url("assets") ?>/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?= base_url("assets") ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?= base_url("assets") ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?= base_url("assets") ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

	<!-- ChartJS -->
	<script src="<?= base_url("assets") ?>/plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?= base_url("assets") ?>/plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<!-- <script src="<?= base_url("assets") ?>/plugins/jqvmap/jquery.vmap.min.js'); ?>"></script>
		<script src="<?= base_url("assets") ?>/plugins/jqvmap/maps/jquery.vmap.usa.js'); ?>"></script> 
	-->
	<!-- jQuery Knob Chart -->
	<script src="<?= base_url("assets") ?>/plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="<?= base_url("assets") ?>/plugins/moment/moment.min.js"></script>
	<script src="<?= base_url("assets") ?>/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?= base_url("assets") ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="<?= base_url("assets") ?>/plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url("assets") ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url("assets") ?>/dist/js/adminlte.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?= base_url("assets") ?>/dist/js/demo.js"></script>
</body>

</html>
