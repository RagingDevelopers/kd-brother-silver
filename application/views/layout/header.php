<div class="mb-3 sticky-top">
    <script src="<?= base_url("assets") ?>/dist/js/demo.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <header class="navbar navbar-expand-md navbar-light d-print-none">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ms-3" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url("dashboard") ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-home fs-2"></i>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown <?=IsActive("master");?>">
                        <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-anchor"></i>
                            </span>
                            <span class="nav-link-title">
                                Master
                            </span>
                        </a>
                        <div class="dropdown-menu" data-bs-popper="static">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url("master/process") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa fa-spinner" aria-hidden="true"></i>
                                        </span>
                                        Process
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url("master/account_type") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                                        </span>
                                        Account type
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url("master/city") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-city" aria-hidden="true"></i>
                                        </span>
                                        City
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url("master/category") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-list" aria-hidden="true"></i>
                                        </span>
                                        Category
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url("master/item") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-layer-group"  aria-hidden="true"></i>
                                        </span>
                                        Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-address-card"></i>
                            </span>
                            <span class="nav-link-title">
                                Registration
                            </span>
                        </a>
                        <div class="dropdown-menu" data-bs-popper="static">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url("registration/user") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                                        </span>
                                        User
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url("registration/customer") ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-address-card" aria-hidden="true"></i>
                                        </span>
                                        Customer
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
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm"
                        style="background-image: url(<?= base_url("assets") ?>/man.png)">
                        <span class="badge bg-success"></span>

                    </span>

                    <div class="d-none d-xl-block ps-2">
                        <div>
                            <?= ucfirst($this->session->userdata('admin_login')['name'] ?? "") ?>
                        </div>
                        <div class="mt-1 small text-muted">Admin</div>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="?theme=dark" id="dark_mode" class="dropdown-item hide-theme-dark" data-bs-toggle="tooltip"
                        data-bs-placement="left" data-bs-original-title="Enable dark mode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                            </path>
                        </svg>
                        Dark Mode
                    </a>
                    <a href="?theme=light" id="dark_mode" class="dropdown-item hide-theme-light"
                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Enable light mode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                            <path
                                d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                            </path>
                        </svg>
                        Light Mode
                    </a>
                    <a href="<?= base_url() ?>" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-original-title="Change Password">
                        <i class="fs-2 ti ti-key dropdown-item-icon "></i>
                        Change Password
                    </a>
                    <a href="<?= base_url('dashboard/logout') ?>" class="dropdown-item" data-bs-toggle="tooltip"
                        data-bs-placement="left" data-bs-original-title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
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
