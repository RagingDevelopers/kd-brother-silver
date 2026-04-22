<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>KD Brothers — Sign In</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		html, body {
			height: 100%;
			font-family: 'Nunito', sans-serif;
		}

		body {
			min-height: 100vh;
			display: flex;
			background: #f5f0e8;
		}

		/* ── LEFT PANEL (Video) ── */
		.panel-video {
			position: relative;
			flex: 1.1;
			overflow: hidden;
			display: none;
		}

		@media (min-width: 900px) {
			.panel-video { display: block; }
		}

		.panel-video video {
			position: absolute;
			inset: 0;
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.panel-video-overlay {
			position: absolute;
			inset: 0;
			background: linear-gradient(
				135deg,
				rgba(0,0,0,0.55) 0%,
				rgba(10,10,10,0.30) 50%,
				rgba(0,0,0,0.65) 100%
			);
		}

		.panel-video-content {
			position: absolute;
			inset: 0;
			display: flex;
			flex-direction: column;
			justify-content: flex-end;
			padding: 3rem;
		}

		.video-brand {
			font-family: 'Cormorant Garamond', serif;
			font-size: 3.6rem;
			font-weight: 700;
			color: #fff;
			line-height: 1;
			letter-spacing: 2px;
			text-transform: uppercase;
		}

		.video-brand span {
			display: block;
			width: 48px;
			height: 3px;
			background: linear-gradient(90deg, #c9a84c, #f0d080, #c9a84c);
			border-radius: 2px;
			margin-bottom: 1rem;
		}

		.video-tagline {
			margin-top: 0.8rem;
			font-size: 1rem;
			color: rgba(255,255,255,0.68);
			letter-spacing: 1.5px;
			text-transform: uppercase;
			font-weight: 500;
		}

		.video-tags {
			display: flex;
			gap: 10px;
			margin-top: 2rem;
			flex-wrap: wrap;
		}

		.video-tag {
			padding: 6px 16px;
			border-radius: 100px;
			font-size: 11.5px;
			font-weight: 700;
			letter-spacing: 0.8px;
			text-transform: uppercase;
			border: 1.5px solid rgba(201,168,76,0.45);
			color: rgba(255,255,255,0.80);
			background: rgba(201,168,76,0.10);
			display: flex;
			align-items: center;
			gap: 6px;
		}

		.video-tag i { font-size: 11px; color: #c9a84c; }

		/* ── RIGHT PANEL (Form) ── */
		.panel-form {
			flex: 0 0 460px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2.5rem 2rem;
			background: #faf7f2;
			position: relative;
			overflow: hidden;
			box-shadow: -6px 0 40px rgba(0,0,0,0.10);
		}

		@media (max-width: 900px) {
			.panel-form {
				flex: 1;
				min-height: 100vh;
			}
		}

		/* subtle shimmer rings */
		.panel-form::before {
			content: '';
			position: absolute;
			width: 500px;
			height: 500px;
			border-radius: 50%;
			border: 1px solid rgba(201,168,76,0.14);
			top: -120px;
			right: -160px;
			pointer-events: none;
		}

		.panel-form::after {
			content: '';
			position: absolute;
			width: 360px;
			height: 360px;
			border-radius: 50%;
			border: 1px solid rgba(201,168,76,0.10);
			bottom: -80px;
			left: -80px;
			pointer-events: none;
		}

		/* ── Form Card ── */
		.form-card {
			position: relative;
			z-index: 2;
			width: 100%;
			max-width: 380px;
		}

		/* Logo */
		.logo-row {
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 2.6rem;
		}

		.logo-badge {
			width: 46px;
			height: 46px;
			border-radius: 14px;
			background: linear-gradient(135deg, #c9a84c, #f0d080);
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 24px rgba(201,168,76,0.30);
		}

		.logo-badge i {
			color: #0f0f0f;
			font-size: 20px;
		}

		.logo-name {
			font-family: 'Cormorant Garamond', serif;
			font-size: 26px;
			font-weight: 700;
			color: #1a1209;
			letter-spacing: 3px;
			text-transform: uppercase;
		}

		/* Heading */
		.heading {
			font-size: 26px;
			font-weight: 800;
			color: #1a1209;
			line-height: 1.2;
			margin-bottom: 6px;
		}

		.subheading {
			font-size: 13.5px;
			color: rgba(30,20,5,0.48);
			font-weight: 500;
			margin-bottom: 2.4rem;
			letter-spacing: 0.2px;
		}

		/* Divider */
		.divider {
			width: 40px;
			height: 2px;
			background: linear-gradient(90deg, #c9a84c, #f0d080);
			border-radius: 2px;
			margin-bottom: 2rem;
		}

		/* Error */
		.error-message {
			margin-bottom: 1.2rem;
			padding: 12px 16px;
			border-radius: 12px;
			background: rgba(220,53,69,0.07);
			border: 1px solid rgba(220,53,69,0.22);
			color: #c0392b;
			font-size: 13.5px;
			font-weight: 600;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.error-message::before {
			content: '\f071';
			font-family: 'Font Awesome 6 Free';
			font-weight: 900;
			font-size: 13px;
			flex-shrink: 0;
		}

		/* Fields */
		.field {
			margin-bottom: 1.2rem;
		}

		.field-label {
			display: block;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 1.2px;
			text-transform: uppercase;
			color: rgba(30,20,5,0.52);
			margin-bottom: 8px;
		}

		.input-wrap {
			position: relative;
		}

		.field-input {
			width: 100%;
			background: #fff;
			border: 1.5px solid rgba(180,150,80,0.18);
			border-radius: 12px;
			padding: 13px 48px 13px 16px;
			font-size: 14.5px;
			font-family: 'Nunito', sans-serif;
			font-weight: 600;
			color: #1a1209;
			outline: none;
			transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
			box-shadow: 0 2px 8px rgba(0,0,0,0.05);
		}

		.field-input::placeholder {
			color: rgba(30,20,5,0.28);
			font-weight: 400;
		}

		.field-input:focus {
			border-color: rgba(201,168,76,0.70);
			background: #fffef9;
			box-shadow: 0 0 0 4px rgba(201,168,76,0.12), 0 2px 8px rgba(0,0,0,0.05);
		}

		.field-icon {
			position: absolute;
			right: 14px;
			top: 50%;
			transform: translateY(-50%);
			color: rgba(30,20,5,0.28);
			transition: color 0.2s;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.field-icon i { font-size: 15px; }

		.field-input:focus ~ .field-icon { color: rgba(201,168,76,0.80); }

		/* Show/hide password toggle */
		.toggle-pwd {
			position: absolute;
			right: 14px;
			top: 50%;
			transform: translateY(-50%);
			background: none;
			border: none;
			cursor: pointer;
			color: rgba(30,20,5,0.30);
			padding: 4px;
			display: flex;
			align-items: center;
			transition: color 0.2s;
			line-height: 1;
		}

		.toggle-pwd:hover { color: #c9a84c; }
		.toggle-pwd i { font-size: 15px; pointer-events: none; }

		/* Sign In Button */
		.btn-signin {
			width: 100%;
			margin-top: 1.8rem;
			padding: 14px;
			border: none;
			border-radius: 12px;
			background: linear-gradient(135deg, #c9a84c, #e8c96a, #c9a84c);
			background-size: 200% auto;
			color: #0f0f0f;
			font-family: 'Nunito', sans-serif;
			font-size: 14px;
			font-weight: 800;
			letter-spacing: 1px;
			text-transform: uppercase;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			box-shadow: 0 10px 28px rgba(201,168,76,0.22);
			transition: background-position 0.4s ease, transform 0.15s ease, box-shadow 0.2s ease;
		}

		.btn-signin:hover {
			background-position: right center;
			transform: translateY(-2px);
			box-shadow: 0 14px 36px rgba(201,168,76,0.32);
		}

		.btn-signin:active { transform: scale(0.98); }

		.btn-arrow { transition: transform 0.2s; }
		.btn-signin:hover .btn-arrow { transform: translateX(5px); }

		/* Footer note */
		.footer-note {
			margin-top: 2rem;
			text-align: center;
			font-size: 11.5px;
			color: rgba(30,20,5,0.32);
			letter-spacing: 0.3px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
		}

		.footer-note i { font-size: 11px; color: rgba(201,168,76,0.60); }

		/* Mobile branding (only visible when video panel is hidden) */
		.mobile-brand {
			display: none;
			font-size: 11px;
			letter-spacing: 2px;
			text-transform: uppercase;
			color: rgba(201,168,76,0.80);
			font-weight: 700;
			margin-bottom: 2rem;
		}

		@media (max-width: 900px) {
			.mobile-brand { display: block; }
		}
	</style>
</head>

<body>

	<!-- LEFT: Video Panel -->
	<div class="panel-video">
		<video autoplay muted loop playsinline>
			<source src="<?= base_url('assets/video3.mp4') ?>" type="video/mp4">
		</video>
		<div class="panel-video-overlay"></div>
		<div class="panel-video-content">
			<div class="video-brand">
				<span></span>
				KD Brothers
			</div>
			<p class="video-tagline">Where elegance meets craftsmanship</p>
			<div class="video-tags">
				<span class="video-tag"><i class="fa-solid fa-gem"></i> Premium Jewellery</span>
				<span class="video-tag"><i class="fa-solid fa-shield-halved"></i> Certified Purity</span>
				<span class="video-tag"><i class="fa-solid fa-truck-fast"></i> Trusted Since Day One</span>
			</div>
		</div>
	</div>

	<!-- RIGHT: Form Panel -->
	<div class="panel-form">
		<div class="form-card">

			<p class="mobile-brand"><i class="fa-solid fa-gem"></i> &nbsp;KD Brothers</p>

			<div class="logo-row">
				<div class="logo-badge">
					<i class="fa-solid fa-gem"></i>
				</div>
				<span class="logo-name">KD Brothers</span>
			</div>

			<h2 class="heading">Welcome back</h2>
			<p class="subheading">Sign in to your account to continue</p>
			<div class="divider"></div>

			<?php echo validation_errors('<div class="error-message">', '</div>'); ?>

			<?php if ($this->session->flashdata('error')): ?>
				<div class="error-message">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php endif; ?>

			<form action="<?php echo base_url('login'); ?>" method="post" autocomplete="off">

				<div class="field">
					<label class="field-label" for="mobile">Mobile Number</label>
					<div class="input-wrap">
						<input class="field-input"
							type="text"
							id="mobile"
							name="mobile"
							value="<?php echo set_value('mobile'); ?>"
							placeholder="Enter your mobile number"
							autocomplete="off"
							required />
						<span class="field-icon">
							<i class="fa-solid fa-mobile-screen-button"></i>
						</span>
					</div>
				</div>

				<div class="field">
					<label class="field-label" for="password">Password</label>
					<div class="input-wrap">
						<input class="field-input"
							type="password"
							id="password"
							name="password"
							placeholder="Enter your password"
							required />
						<button type="button" class="toggle-pwd" id="togglePwd" aria-label="Toggle password visibility">
							<i class="fa-regular fa-eye" id="togglePwdIcon"></i>
						</button>
					</div>
				</div>

				<button type="submit" class="btn-signin">
					Sign In
					<i class="fa-solid fa-arrow-right btn-arrow"></i>
				</button>

			</form>

			<p class="footer-note">
				<i class="fa-solid fa-lock"></i>
				Secured &amp; encrypted connection
			</p>

		</div>
	</div>

	<script>
		const toggleBtn = document.getElementById('togglePwd');
		const pwdInput  = document.getElementById('password');
		const pwdIcon   = document.getElementById('togglePwdIcon');

		toggleBtn.addEventListener('click', function () {
			const isPassword = pwdInput.type === 'password';
			pwdInput.type    = isPassword ? 'text' : 'password';
			pwdIcon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
		});
	</script>

</body>

</html>
