<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en" >

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="baseurl" content="<?= base_url(); ?>" />
    <title>
        <?= $page_title ?>
    </title>
    <!-- CSS files -->
    <link href="<?= base_url("assets") ?>/dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/demo.min.css?1684106062" rel="stylesheet" />
	<link rel="stylesheet" href="<?= base_url("assets") ?>/dist/css/spinkit/spinkit.css" />
    <link rel="stylesheet" href="<?= base_url("assets/") ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/") ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/") ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/dist/css/flatpickr.min.css">


    <!-- Select2 Plugins  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />


    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .btn-action {
            width: 30px;
            height: 30px;
            padding: 8px;
            border-radius: 60%;
        }

        .btn {
            transition: transform 0.6s ease, box-shadow 0.3s ease;
        }

        .btn:hover {
            /* transform: translateY(-1px); */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        #example_table_length {
            margin-bottom: 5px;
            color: #626976;
        }

        #example_table_filter {
            color: #626976;
        }

        #example_table_info {
            padding: 0;
            color: #626976;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            display: block;
            width: 100% !important;
            height: 2.25rem;
            padding: 0.375rem 0.75rem;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5 !important;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 0 transparent;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .select2-container--default .select2-selection--multiple {
            width: 100% !important;
            font-size: .875rem;
            font-weight: 400;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 0 transparent;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #90b5e2;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgb(32 107 196 / 25%);
        }

        .form-control:focus {
            color: inherit;
            background-color: #fff;
            border-color: #90b5e2;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgb(32 107 196 / 25%);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            outline: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            line-height: 1.7 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            top: 0px;
            right: 0px;
            width: 25px;
        }

        .btn-group>.btn {
            padding: 7px;
            font-size: 12px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }


        .container,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl {
            max-width: 100% !important;
        }
    </style>
    <script src="https://kit.fontawesome.com/62ef9efeca.js" crossorigin="anonymous"></script>
</head>

<body>
    <script src="<?= base_url("assets") ?>/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
        <?php $this->load->view("layout/header") ?>
        <div class="page-wrapper">
            <div class="d-print-none">
                <?php $this->load->view('flash_message'); ?>
            </div>
            <div class="mx-5">
                <div class="page-body">
                    <?php $this->load->view($page_name) ?>
                </div>
            </div>
            <?php $this->load->view("layout/footer") ?>
        </div>
    </div>
    <?php $this->load->view("layout/script"); ?>
</body>

</html>
