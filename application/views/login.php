<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>
		<?= "Login" ?>
	</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://dev.avgl.info/assets/back/plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="https://dev.avgl.info/assets/back/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="https://dev.avgl.info/assets/back/dist/css/adminlte.min.css">
	<!-- Custom CSS -->
	<style>
		/* Animated gradient background */
		body {
			background: linear-gradient(270deg, #6a11cb, #ff7e5f, #2575fc, #43cea2, #ffcc33, #ff4e50, #1fddff, #72c6ef);
			background-size: 800% 800%;
			animation: infiniteGradient 20s ease infinite;
			font-family: 'Source Sans Pro', sans-serif;
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			margin: 0;
		}

		/* Keyframes for infinite color shifting animation */
		@keyframes infiniteGradient {
			0% {
				background-position: 0% 50%;
			}

			25% {
				background-position: 50% 0%;
			}

			50% {
				background-position: 100% 50%;
			}

			75% {
				background-position: 50% 100%;
			}

			100% {
				background-position: 0% 50%;
			}
		}


		/* Animation for the card */
		@keyframes slideIn {
			from {
				transform: translateY(50px);
				opacity: 0;
			}

			to {
				transform: translateY(0);
				opacity: 1;
			}
		}


		/* Login box styling */
		.login-box {
			width: 400px;
			margin: 50px auto;
			padding: 20px;
			animation: slideIn 0.8s ease-out;
			background-color: rgba(255, 255, 255, 0.8);
			border-radius: 12px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
			backdrop-filter: blur(10px);
		}

		.card {
			border-radius: 15px;
			background: rgba(255, 255, 255, 0.9);
			/* padding: 2rem; */
			border-radius: 12px;
		}

		.login-logo a {
			font-size: 2.2rem;
			font-weight: bold;
			color: #2575fc;
			text-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
		}

		.login-card-body {
			padding: 2rem;
			text-align: center;
			border-radius: 12px;
		}

		/* Stylish gradient button */
		.btn-primary {
			background: linear-gradient(135deg, #6a11cb, #2575fc);
			border: none;
			border-radius: 50px;
			padding: 12px;
			font-weight: bold;
			letter-spacing: 1px;
			width: 100%;
			color: #fff;
			transition: all 0.9s ease-in-out;
		}

		.btn-primary:hover {
			background: linear-gradient(135deg, #43cea2, #2575fc);
			transform: scale(1.05);
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
		}

		/* Input group styling and animations */
		body .input-group-text {
			background-color: #2575fc !important;
			color: #fff !important;
			border: none !important;
			border-radius: 50px 0 0 50px;
		}

		.form-control {
			border-radius: 9px;
			border: 1px solid #ddd;
			padding: 10px;
			box-shadow: none;
			transition: border-color 0.3s ease, box-shadow 0.3s ease;
		}

		.form-control:focus {
			border-color: #6a11cb;
			box-shadow: 0 0 10px rgba(106, 17, 203, 0.4);
		}

		/* Label for login message */
		.login-box-msg {
			font-size: 1.4rem;
			color: #333;
			font-weight: 600;
			margin-bottom: 1.5rem;
		}

		.alert {
			border-radius: 10px;
			color: #fff;
		}

		.alert h4 {
			font-size: 1.2rem;
		}

		/* Footer styling for links */
		.login-footer {
			margin-top: 1rem;
			font-size: 0.9rem;
			color: #666;
		}

		.login-footer a {
			color: #6a11cb;
		}

		/* Add subtle animation to form elements */
		input[type="email"],
		input[type="password"],
		button[type="submit"] {
			transition: all 0.2s ease-in-out;
		}

		/* Focus effect for form elements */
		input[type="email"]:focus,
		input[type="password"]:focus {
			transform: scale(1.02);
			box-shadow: 0 0 8px rgba(106, 17, 203, 0.4);
		}
	</style>
</head>
<body class="hold-transition login-page">
	<div class="gradient-hover"></div>
	<div class="login-box">
		<div class="login-logo">
			<a href="#"><b>Silver</b></a>
		</div>
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Sign in to start your session</p>
				<?php $this->load->view('flash_message'); ?>
				<form action="<?php echo base_url('login'); ?>" method="post">
					<div class="input-group mb-3">
						<input type="text" name="mobile" class="form-control" placeholder="Enter mobile ">
					</div>
					<div class="input-group mb-3">
						<input type="password" name="password" class="form-control" placeholder="Password">
					</div>

					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-primary btn-block">Sign In</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- jQuery -->
	<script src="https://dev.avgl.info/assets/back/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="https://dev.avgl.info/assets/back/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="https://dev.avgl.info/assets/back/dist/js/adminlte.min.js"></script>
</body>

</html>
