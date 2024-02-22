<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        <?= "Login" ?>
    </title>
    <!-- CSS files -->
    <link href="<?= base_url("assets") ?>/dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url("assets") ?>/dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>


<body class=" d-flex flex-column">
    <script src="<?= base_url("assets") ?>/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
            </div>
            <?php
            $this->load->view('flash_message');
            ?>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                    <?php echo form_open('login'); ?>
                    <div class="mb-2">
                        <label class="form-label">Mobile No</label>
                        <?php echo form_input(['class' => 'form-control ', 'placeholder' => 'Enter mobile ', 'id' => 'mobile', 'name' => 'mobile', 'value' => set_value("mobile")]); ?>
                    </div>
                    <div>
                        <span class="text-danger fw-bold"> <?php echo form_error('mobile'); ?> </span>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                        </label>
                        <div class="input-group input-group-flat">
                            <?php echo form_password(['class' => 'form-control', 'placeholder' => 'Enter password', 'id' => 'password', 'name' => 'password', 'value' => set_value("password")]); ?>
                        </div>
                    </div>
                    <div>
                        <span class="text-danger fw-bold "> <?php echo form_error('password'); ?></span>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabler Core -->
    <?php $this->load->view("layout/script"); ?>
</body>

</html>