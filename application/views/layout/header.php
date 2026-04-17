<div class="mb-3 sticky-top">
	<script src="<?= base_url("assets") ?>/dist/js/demo.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<header class="navbar navbar-expand-md navbar-light d-print-none">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse ms-3" id="navbar-menu">
			<div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
				<ul class="navbar-nav">
					<li class="nav-item <?= IsActive("dashboard"); ?>">
						<a class="nav-link" href="<?= base_url("dashboard") ?>">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-home fs-2"></i>
							</span>
							<span class="nav-link-title">
								Dashboard
							</span>
						</a>
					</li>
					<li class="nav-item dropdown <?= IsActive("master"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-anchor"></i>
							</span>
							<span class="nav-link-title">
								Master
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("master/process"); ?>" href="<?= base_url("master/process") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-spinner" aria-hidden="true"></i>
										</span>
										Process
									</a>
									<a class="dropdown-item <?= uri("master/account_type"); ?>" href="<?= base_url("master/account_type") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-receipt" aria-hidden="true"></i>
										</span>
										Account type
									</a>
									<a class="dropdown-item <?= uri("master/city"); ?>" href="<?= base_url("master/city") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-city" aria-hidden="true"></i>
										</span>
										City
									</a>
									<a class="dropdown-item <?= uri("master/category"); ?>" href="<?= base_url("master/category") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-list" aria-hidden="true"></i>
										</span>
										Category
									</a>
									<a class="dropdown-item <?= uri("master/item"); ?>" href="<?= base_url("master/item") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-layer-group" aria-hidden="true"></i>
										</span>
										Item
									</a>
									<a class="dropdown-item <?= uri("master/sub_item"); ?>" href="<?= base_url("master/sub_item") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-diagram-next" aria-hidden="true"></i>
										</span>
										Sub Item
									</a>
									<a class="dropdown-item <?= uri("master/row_material"); ?>" href="<?= base_url("master/row_material") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-brands fa-dropbox" aria-hidden="true"></i>
										</span>
										Raw Meterial
									</a>
									<a class="dropdown-item <?= uri("master/row_material_type"); ?>" href="<?= base_url("master/row_material_type") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-dumpster-fire" aria-hidden="true"></i>
										</span>
										Raw Meterial type
									</a>
									<a class="dropdown-item <?= uri("master/metal_type"); ?>" href="<?= base_url("master/metal_type") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-brands fa-squarespace" aria-hidden="true"></i>
										</span>
										Metal Type
									</a>
									<a class="dropdown-item <?= uri("master/bank"); ?>" href="<?= base_url("master/bank") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-building-columns" aria-hidden="true"></i>
										</span>
										Bank
									</a>
									<a class="dropdown-item <?= uri("master/stamp"); ?>" href="<?= base_url("master/stamp") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-stamp" aria-hidden="true"></i>
										</span>
										Stamp
									</a>
									<a class="dropdown-item <?= uri("master/unit"); ?>" href="<?= base_url("master/unit") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-bars" aria-hidden="true"></i>
										</span>
										Unit
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("registration"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-address-card"></i>
							</span>
							<span class="nav-link-title">
								Registration
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("registration/customer"); ?>" href="<?= base_url("registration/customer") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-address-card" aria-hidden="true"></i>
										</span>
										Customer
									</a>
									<a class="dropdown-item <?= uri("registration/user"); ?>" href="<?= base_url("registration/user") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-user-plus" aria-hidden="true"></i>
										</span>
										User
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("manufacturing"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-industry"></i>
							</span>
							<span class="nav-link-title">
								Manufacturing
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<!--<a class="dropdown-item" href="<?= base_url("manufacturing/main_garnu") ?>">-->
									<!--	<span class="nav-link-icon d-md-none d-lg-inline-block">-->
									<!--		<i class="fa-solid fa-filter-circle-dollar"></i>-->
									<!--	</span>-->
									<!--	Main Garnu-->
									<!--</a>-->
									<a class="dropdown-item <?= uri("manufacturing/pre_garnu"); ?>" href="<?= base_url("manufacturing/pre_garnu") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-filter"></i>
										</span>
										Garnu
									</a>
									<a class="dropdown-item <?= uri("manufacturing/garnu"); ?>" href="<?= base_url("manufacturing/garnu") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-hammer"></i>
										</span>
										Casting
									</a>
									<a class="dropdown-item <?= uri("manufacturing/receive_garnu"); ?>" href="<?= base_url("manufacturing/receive_garnu") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-sharp fa-solid fa-arrow-rotate-left"></i>
										</span>
										Received Garnu
									</a>
									<a class="dropdown-item <?= uri("manufacturing/product"); ?>" href="<?= base_url("manufacturing/product") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-truck-fast"></i>
										</span>
										Main Dhal Report
									</a>
									<a class="dropdown-item <?= uri("manufacturing/product/main_given_report"); ?>" href="<?= base_url("manufacturing/product/main_given_report") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-line-chart" aria-hidden="true"></i>
										</span>
										Main Given Report
									</a>
									<a class="dropdown-item <?= uri("manufacturing/receive_garnu/lot_creation"); ?>" href="<?= base_url("manufacturing/receive_garnu/lot_creation") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-boxes-packing"></i>
										</span>
										Ready For Lot
									</a>
									<a class="dropdown-item <?= uri("manufacturing/lot"); ?>" href="<?= base_url("manufacturing/lot") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-random" aria-hidden="true"></i>
										</span>
										Lot Creation
									</a>
									<a class="dropdown-item <?= uri("manufacturing/ready_for_sale"); ?>" href="<?= base_url("manufacturing/ready_for_sale") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-diagram-next" aria-hidden="true"></i>
										</span>
										Ready For Sale
									</a>
									<a class="dropdown-item <?= uri("manufacturing/given_testing"); ?>" href="<?= base_url("manufacturing/given_testing") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-filter"></i>
										</span>
										Given Testing
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("purchase"); ?> <?= IsActive("purchase_return"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa fa-solid fa-money-check-dollar"></i>
							</span>
							<span class="nav-link-title">
								Purchase
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("purchase"); ?>" href="<?= base_url("purchase") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-user-plus"></i>
										</span>
										Purchase Item
									</a>
									<a class="dropdown-item <?= uri("purchase_return"); ?>" href="<?= base_url("purchase_return") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bill-transfer"></i>
										</span>
										Purchase Item Return
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("sales"); ?> <?= IsActive("sales_return"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-file-invoice-dollar"></i>
							</span>
							<span class="nav-link-title">
								Sales
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("sales"); ?>" href="<?= base_url("sales") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bills"></i>
										</span>
										Sales Report
									</a>
										<a class="dropdown-item <?= uri("sales_return"); ?>" href="<?= base_url("sales_return") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bill-transfer"></i>
										</span>
										Sales Return Report
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("payment"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-payment" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-file-invoice-dollar"></i>
							</span>
							<span class="nav-link-title">
								Payment
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("payment/jama_report"); ?>" href="<?= base_url("payment/jama_report") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bills"></i>
										</span>
										Payment Report
									</a>
									<a class="dropdown-item <?= uri("payment/transfer_entry"); ?>" href="<?= base_url("payment/transfer_entry") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bill-transfer"></i>
										</span>
										Trasnfer Entry
									</a>
								</div>
							</div>
						</div>
					</li>

					<!--<li class="nav-item <?= IsActive("payment"); ?>">-->
					<!--	<a class="nav-link" href="<?= base_url("payment/jama_report") ?>">-->
					<!--		<span class="nav-link-icon d-md-none d-lg-inline-block">-->
					<!--			<i class="fa-solid fa-file-invoice-dollar"></i>-->
					<!--		</span>-->
					<!--		<span class="nav-link-title">-->
					<!--			Payment Report-->
					<!--		</span>-->
					<!--	</a>-->
					<!--</li>-->

					<li class="nav-item dropdown <?= IsActive("report"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-credit-card"></i>
							</span>
							<span class="nav-link-title">
								Report
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("report/lot"); ?>" href="<?= base_url("report/lot") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-boxes-packing"></i>
										</span>
										Lot Reprot
									</a>
									<a class="dropdown-item <?= uri("report/Row_material_stock"); ?>" href="<?= base_url("report/Row_material_stock") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-user-plus"></i>
										</span>
										Row Material Stock
									</a>
									<a class="dropdown-item <?= uri("report/metal_type_stock"); ?>" href="<?= base_url("report/metal_type_stock") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-brands fa-squarespace" aria-hidden="true"></i>
										</span>
										Metal type Stock
									</a>
									<a class="dropdown-item <?= uri("report/row_material_closing"); ?>" href="<?= base_url("report/row_material_closing") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-brands fa-dropbox" aria-hidden="true"></i>
										</span>
										Row Material Closing
									</a>
									<a class="dropdown-item <?= uri("report/account_ledger"); ?>" href="<?= base_url("report/account_ledger") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-receipt" aria-hidden="true"></i>
										</span>
										Account Ledger
									</a>
									<a class="dropdown-item <?= uri("report/daybook"); ?>" href="<?= base_url("report/daybook") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-repeat" aria-hidden="true"></i>
										</span>
										Daybook
									</a>
									<a class="dropdown-item <?= uri("report/account_ledger/balanceSheetReport"); ?>" href="<?= base_url("report/account_ledger/balanceSheetReport") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-book" aria-hidden="true"></i>
										</span>
										Balance Sheet
									</a>
									<a class="dropdown-item <?= uri("report/silverBhavReport"); ?>" href="<?= base_url("report/silverBhavReport") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-money-bill-transfer"></i>
										</span>
										Silver Bhav Report
									</a>
									<a class="dropdown-item <?= uri("report/stockReport"); ?>" href="<?= base_url("report/stockReport") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-line-chart" aria-hidden="true"></i>
										</span>
										Stock Report
									</a>
									<a class="dropdown-item <?= uri("report/lot_wise_rm"); ?>" href="<?= base_url("report/lot_wise_rm") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-random" aria-hidden="true"></i>
										</span>
										Lot Wise Row Material
									</a>
									<a class="dropdown-item <?= uri("report/profit_loss_report"); ?>" href="<?= base_url("report/profit_loss_report") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa fa-random" aria-hidden="true"></i>
										</span>
										Profit And Loss report
									</a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown <?= IsActive("setting"); ?>">
						<a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="fa-solid fa-gear"></i>
							</span>
							<span class="nav-link-title">
								Setting
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow" data-bs-popper="static">
							<div class="dropdown-menu-columns">
								<div class="dropdown-menu-column">
									<a class="dropdown-item <?= uri("setting/sequence"); ?>" href="<?= base_url("setting/sequence") ?>">
										<span class="nav-link-icon d-md-none d-lg-inline-block">
											<i class="fa-solid fa-boxes-packing"></i>
										</span>
										Sequence
									</a>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="navbar-nav flex-row order-md-last me-5 ">
			<div class="nav-item dropdown">
				<a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
					<span class="avatar avatar-sm" style="background-image: url(<?= base_url("assets") ?>/man.png)">
						<span class="badge bg-success"></span>
					</span>

					<div class="d-none d-xl-block ps-2">
						<div>
							<?= ucfirst($this->session->userdata('admin_login')['name'] ?? "") ?>
						</div>
						<div class="mt-1 small text-muted"><?= ucfirst(strtolower($this->session->userdata('admin_login')['user_type'] ?? "")) ?></div>
					</div>
				</a>

				<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
					<a href="?theme=dark" id="dark_mode" class="dropdown-item hide-theme-dark" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Enable dark mode">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
							</path>
						</svg>
						Dark Mode
					</a>
					<a href="?theme=light" id="dark_mode" class="dropdown-item hide-theme-light" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Enable light mode">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
							<path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
							</path>
						</svg>
						Light Mode
					</a>
					<a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
						<i class="fs-2 ti ti-key dropdown-item-icon "></i>
						Change Password
					</a>
					<a href="<?= base_url('login/logout') ?>" class="dropdown-item logout-confirm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Logout">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
							</path>
							<path d="M7 12h14l-3 -3m0 6l3 -3"></path>
						</svg>
						Logout
					</a>
				</div>
			</div>
		</div>
	</header>
</div>

<script>
	$(document).on('click', '.logout-confirm', function(e) {
		e.preventDefault();
		const logoutUrl = $(this).attr('href');

		Swal.fire({
			title: 'Logout?',
			text: 'Are you sure you want to logout?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, logout',
			cancelButtonText: 'Cancel',
			confirmButtonColor: '#206bc4',
			cancelButtonColor: '#d63939',
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = logoutUrl;
			}
		});
	});
</script>

<div class="modal modal-blur fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Change PassWord</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="#" id="changePassword">
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="mb-2">
								<label class="form-label" for=""> Old Password</label>
								<div class="input-group">
									<input type="password" name="old_password" class="form-control" placeholder="Your Old password" autocomplete="off">
									<span class="input-group-text">
										<a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" data-bs-original-title="Show password"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
											<i class="fa-regular fa-eye"></i>
										</a>
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-2">
								<label class="form-label" for="">New Password</label>
								<div class="input-group">
									<input type="password" name="new_password" class="form-control" placeholder="Your New password" autocomplete="off">
									<span class="input-group-text">
										<a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" data-bs-original-title="Show password">
											<i class="fa-regular fa-eye"></i>
										</a>
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-2">
								<label class="form-label" for="">Confirm Password</label>
								<div class="input-group">
									<input type="password" name="confirm_password" class="form-control" placeholder="Your Confirm password" autocomplete="off">
									<span class="input-group-text">
										<a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" data-bs-original-title="Show password">
											<i class="fa-regular fa-eye"></i>
										</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div>
						<a href="#" class="btn btn-danger" data-bs-dismiss="modal">
							Cancel
						</a>
						<button type="submit" class="input-icon btn btn-primary btn-primary submit-btn">Save Changes
							<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
