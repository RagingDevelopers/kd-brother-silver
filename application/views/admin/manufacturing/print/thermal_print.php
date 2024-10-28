<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible">
        <title>Given Receipt</title>

<style>
	body {
		width: 100%;
		margin: 0;
		padding: 0;
		margin-top: 0px;
		margin-bottom: 0px;
		padding-top: 0px;
		padding-bottom: 0px;
		font-weight: bold;
		color: #000;
    	background-color: #fff;
	}

	* {
		font-size: 12px;
		font-family: monospace;
	}

	td,
	th,
	tr,
	table {
		border-top: 1px solid black;
		border-collapse: collapse;
	}

	td.description,
	th.description {
		width: 75px;
		max-width: 75px;
	}

	.centered {
		text-align: center;
		align-content: center;
	}

	h1 {
		width: auto;
		margin: 0;
		padding: 0;
	}

	.ticket {
		width: 200px;
		max-width: 200px;
		height: auto;
		max-height: auto;
		margin-top: 0px;
		margin-bottom: 0px;
		padding-top: 0px;
		padding-bottom: 0px;
	}

	img {
		align-items: center;
		width: 60px;
		margin-top: 5px;
	}

	.mx-5 {
		margin-right: 1rem !important;
		margin-left: 1rem !important;
	}
	.page-body {
		margin-top: 0px;
		margin-bottom: 0px;
	}

	@media print {
		@page {
			size: auto;
			margin: 0;
		}
		.mx-5 {
			margin-right: 1rem !important;
			margin-left: 1rem !important;
		}
		.page-body {
			margin-top: 0px;
			margin-bottom: 0px;
		}

		body, .ticket {
			margin: 0;
			padding: 0;
			box-shadow: none;
		}
		.ticket {
			page-break-inside: avoid;
			page-break-after: auto;
			/* height: 100%;  */
			/* display: block;
			overflow: hidden; */
		}

		.hidden-print,
		.hidden-print * {
			display: none !important;
		}
	}

</style>
    </head>
    <body>
        <div class="ticket">
            <h1 class="centered">KD Brothers</h1>

			<div class="text-center">
				<img class="centered" src="<?=generate_qr_code($data['givenData']['garnu_id'] ?? "" . "-" . $data['givenData']['id'] ?? "",100)?>" />
			</div>
			<br>
			<p class="centered" style="margin-bottom:1px"><?= $data['givenData']['process_name'] ?? ""; ?></p>   
			<p class="centered"	>Date :- <?= date('d-m-Y', strtotime($data['givenData']['creation_date'] ?? date('Y-m-d'))); ?></p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Garnu Name :- <?= $data['givenData']['garnu_name'] ?? ""; ?></th>
                        <th class="description">Touch <?= $data['givenData']['given_touch'] ?? ""; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="quantity">Karigar</td>
                        <td class="description"><?= $data['givenData']['worker_name'] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Code</td>
                        <td class="description"><?= $garnu_id. "-" . $given_id ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Item Weight</td>
                        <td class="description"><?= $data['givenData']['garnu_weight'] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Raw Material Weight</td>
                        <td class="description"><?= $data['givenData']['row_material_weight'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given Total Weight</td>
                        <td class="description"><?= $data['givenData']['total_weight'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given PCS</td>
                        <td class="description"><?= $data['givenData']['given_qty'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given Remark</td>
                        <td class="description"><?= $data['givenData']['remarks'] ?? "" ?></td>
                    </tr>
					<?php
					if (!empty($data['givenRowMaterial'])) {
						foreach ($data['givenRowMaterial'] as $rmi => $rm) { ?>
							<tr>
								<td class="quantity"><?= $rm['row_material_name']; ?></td>
								<td class="description"> PCS - <?= $rm['quantity'] ?> <br> Weight - <?= $rm['weight'] ?> <br> Touch - <?= $rm['touch'] ?> </td>
							</tr>
					<?php }
					} ?>
                </tbody>
            </table>
        </div>

		
		<hr style="margin-top:50px;">

        <div class="ticket">
            <h1 class="centered">KD Brothers</h1>

			<div class="text-center">
				<img class="centered" src="<?=generate_qr_code($data['givenData']['garnu_id'] ?? "" . "-" . $data['givenData']['id'] ?? "",100)?>" />
			</div>
			<br>
			<p class="centered" style="margin-bottom:1px"><?= $data['givenData']['process_name'] ?? ""; ?></p>   
			<p class="centered"	>Date :- <?= date('d-m-Y', strtotime($data['givenData']['creation_date'] ?? date('Y-m-d'))); ?></p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Garnu Name :- <?= $data['givenData']['garnu_name'] ?? ""; ?></th>
                        <th class="description">Touch <?= $data['givenData']['given_touch'] ?? ""; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="quantity">Karigar</td>
                        <td class="description"><?= $data['givenData']['worker_name'] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Code</td>
                        <td class="description"><?= $garnu_id. "-" . $given_id ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Item Weight</td>
                        <td class="description"><?= $data['givenData']['garnu_weight'] ?? "" ?></td>
                    </tr>
                    <tr>
                        <td class="quantity">Raw Material Weight</td>
                        <td class="description"><?= $data['givenData']['row_material_weight'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given Total Weight</td>
                        <td class="description"><?= $data['givenData']['total_weight'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given PCS</td>
                        <td class="description"><?= $data['givenData']['given_qty'] ?? "" ?></td>
                    </tr>
					<tr>
                        <td class="quantity">Given Remark</td>
                        <td class="description"><?= $data['givenData']['remarks'] ?? "" ?></td>
                    </tr>
					<?php
					if (!empty($data['givenRowMaterial'])) {
						foreach ($data['givenRowMaterial'] as $rmi => $rm) { ?>
							<tr>
								<td class="quantity"><?= $rm['row_material_name']; ?></td>
								<td class="description"> PCS - <?= $rm['quantity'] ?> <br> Weight - <?= $rm['weight'] ?> <br> Touch - <?= $rm['touch'] ?> </td>
							</tr>
					<?php }
					} ?>
                </tbody>
            </table>
        </div>

        <button id="btnPrint" class="hidden-print">Print</button>
        <script>
			window.print();
		</script>
    </body>
</html>
