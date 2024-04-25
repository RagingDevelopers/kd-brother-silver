<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
	<title><?= $title ?></title>
	<style>
		.div_tag_print {
			text-align: center;
		}

		table,
		thead,
		tbody,
		tr,
		th,
		td,
		tfoot {
			padding: 0;
			font-size: 7.4px;
			margin: 0;
			/*font-weight: 900;*/
		}

		th {
			text-align: left;
		}

		th p {
			font-size: 0.5px;
			font-weight: 900;
			margin: 0;
			padding: 0;
		}

		.qr_code {
			height: 50px;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: "Arial", serif;
		}
	</style>
</head>

<body>

	<?php
	$tagString = "LOT". '/' . $LC['barcode']. '/' .$LC['gross_weight'] . '/' . $LC['less_weight'] . '/' . $LC['net_weight'] . '/' . '/1';
	// pre($tagString);exit;
	?>
	<div class="div_tag_print" style="page-break-before: always;">
		<table style="margin-top:3px;">
			<thead>
			</thead>
			<tbody>
				<tr>
					<th rowspan="5">
						<!--<img class="qr_code" style="margin-left:5px;" src="https://chart.googleapis.com/chart?cht=qr&chs=177x177&chld=L|0&cht=qr&chl=<?= $tagString ?>&choe=UTF-8" /><br>-->
						<img class="qr_code" style="margin-left:5px;" src="<?=generate_qr_code($tagString)?>" /><br>
					</th>
				</tr>
				<tr>
					<th>G: <?= $LC['gross_weight'] ?></th>
				</tr>
				<tr>
					<th>l: <?= $LC['less_weight']; ?></th>
				</tr>
				<tr>
					<th>N: <?= $LC['net_weight']; ?>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>
