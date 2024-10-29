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
			font-size:7.4px;
			margin: 0;
			/*font-weight: 900;*/
		}

		th {
			text-align: left;
		}

		th p {
			font-size:0.5px;
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
	<?php foreach ($LCS as $LC) { ?>
		<div class="div_tag_print" style="page-break-before: always;">
			<table style="margin-top:3px;">
				<thead>
					<tr>
						<th rowspan="5">
							<?=generate($LC['code'])?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="font-size: large;"><?= $LC['code'] ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php } ?>
</body>

</html>
