<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Silver — Sign In</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<style>
		*,
		*::before,
		*::after {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		html,
		body {
			height: 100%;
			font-family: 'Nunito', sans-serif;
		}

		body {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2rem;
			position: relative;
			overflow: hidden;
			background: #fff;
		}

		.error-message {
			margin-bottom: 1rem;
			padding: 12px 14px;
			border-radius: 14px;
			background: rgba(255, 77, 77, 0.10);
			border: 1px solid rgba(255, 77, 77, 0.25);
			color: #d62828;
			font-size: 14px;
			font-weight: 700;
			text-align: center;
		}

		/* ── Big colorful blobs ── */
		.blob {
			position: fixed;
			border-radius: 50%;
			filter: blur(80px);
			pointer-events: none;
			z-index: 0;
		}

		.b1 {
			width: 520px;
			height: 520px;
			background: #ff6fb7;
			top: -160px;
			left: -160px;
			opacity: 0.55;
			animation: drift1 10s ease-in-out infinite;
		}

		.b2 {
			width: 480px;
			height: 480px;
			background: #ffe066;
			top: -100px;
			right: -120px;
			opacity: 0.5;
			animation: drift2 12s ease-in-out infinite;
		}

		.b3 {
			width: 440px;
			height: 440px;
			background: #43e8d8;
			bottom: -140px;
			left: -100px;
			opacity: 0.5;
			animation: drift3 14s ease-in-out infinite;
		}

		.b4 {
			width: 400px;
			height: 400px;
			background: #a78bfa;
			bottom: -120px;
			right: -100px;
			opacity: 0.5;
			animation: drift1 11s ease-in-out infinite reverse;
		}

		.b5 {
			width: 260px;
			height: 260px;
			background: #ff9a3c;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			opacity: 0.35;
			animation: drift2 9s ease-in-out infinite;
		}

		@keyframes drift1 {

			0%,
			100% {
				transform: translate(0, 0) scale(1)
			}

			50% {
				transform: translate(30px, 20px) scale(1.06)
			}
		}

		@keyframes drift2 {

			0%,
			100% {
				transform: translate(0, 0) scale(1)
			}

			50% {
				transform: translate(-25px, 30px) scale(1.05)
			}
		}

		@keyframes drift3 {

			0%,
			100% {
				transform: translate(0, 0) scale(1)
			}

			50% {
				transform: translate(20px, -25px) scale(1.07)
			}
		}

		/* ── Card ── */
		.card {
			position: relative;
			z-index: 1;
			width: 100%;
			max-width: 420px;
			background: rgba(255, 255, 255, 0.72);
			backdrop-filter: blur(32px);
			-webkit-backdrop-filter: blur(32px);
			border-radius: 28px;
			border: 1.5px solid rgba(255, 255, 255, 0.9);
			box-shadow:
				0 8px 32px rgba(0, 0, 0, 0.1),
				0 2px 8px rgba(0, 0, 0, 0.06),
				inset 0 1px 0 rgba(255, 255, 255, 0.95);
			padding: 2.8rem 2.4rem 2.4rem;
			animation: popIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
		}

		@keyframes popIn {
			from {
				opacity: 0;
				transform: scale(0.92) translateY(30px);
			}

			to {
				opacity: 1;
				transform: scale(1) translateY(0);
			}
		}

		/* ── Logo ── */
		.logo-row {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 2rem;
		}

		.logo-badge {
			width: 44px;
			height: 44px;
			border-radius: 14px;
			background: linear-gradient(135deg, #ff6fb7, #a78bfa);
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 14px rgba(167, 139, 250, 0.45);
		}

		.logo-badge i {
			color: white;
			font-size: 20px;
		}

		.logo-name {
			font-size: 24px;
			font-weight: 900;
			background: linear-gradient(135deg, #ff6fb7, #a78bfa, #43b8ff);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			letter-spacing: -0.5px;
		}

		/* ── Heading ── */
		.heading {
			font-size: 28px;
			font-weight: 900;
			color: #1a1a2e;
			line-height: 1.2;
			margin-bottom: 6px;
		}

		.subheading {
			font-size: 14px;
			color: #888;
			font-weight: 400;
			margin-bottom: 2rem;
		}

		/* ── Fields ── */
		.field {
			margin-bottom: 1rem;
		}

		.field-label {
			display: block;
			font-size: 12px;
			font-weight: 700;
			letter-spacing: 0.8px;
			text-transform: uppercase;
			color: #555;
			margin-bottom: 7px;
		}

		.input-wrap {
			position: relative;
		}

		.field-input {
			width: 100%;
			background: rgba(255, 255, 255, 0.85);
			border: 2px solid rgba(0, 0, 0, 0.07);
			border-radius: 14px;
			padding: 13px 46px 13px 16px;
			font-size: 15px;
			font-family: 'Nunito', sans-serif;
			font-weight: 600;
			color: #1a1a2e;
			outline: none;
			transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
		}

		.field-input::placeholder {
			color: #bbb;
			font-weight: 400;
		}

		.field-input:focus {
			border-color: #a78bfa;
			background: #fff;
			box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.18);
		}

		.field-icon {
			position: absolute;
			right: 14px;
			top: 50%;
			transform: translateY(-50%);
			color: #ccc;
			pointer-events: none;
			transition: color 0.2s;
		}

		.field-icon i {
			font-size: 16px;
		}

		.field-input:focus~.field-icon {
			color: #a78bfa;
		}

		/* ── Button ── */
		.btn-signin {
			width: 100%;
			margin-top: 1.6rem;
			padding: 15px;
			border: none;
			border-radius: 14px;
			background: linear-gradient(135deg, #ff6fb7 0%, #a78bfa 50%, #43b8ff 100%);
			background-size: 200% 200%;
			color: #fff;
			font-family: 'Nunito', sans-serif;
			font-size: 15px;
			font-weight: 800;
			letter-spacing: 0.5px;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			box-shadow: 0 6px 24px rgba(167, 139, 250, 0.45);
			transition: transform 0.15s, box-shadow 0.2s, background-position 0.5s;
			animation: gradShift 4s ease infinite;
		}

		@keyframes gradShift {
			0% {
				background-position: 0% 50%;
			}

			50% {
				background-position: 100% 50%;
			}

			100% {
				background-position: 0% 50%;
			}
		}

		.btn-signin:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 32px rgba(167, 139, 250, 0.55);
		}

		.btn-signin:active {
			transform: scale(0.98);
		}

		.btn-arrow {
			transition: transform 0.2s;
		}

		.btn-signin:hover .btn-arrow {
			transform: translateX(4px);
		}

		/* ── Decorative chips ── */
		.chips {
			display: flex;
			gap: 8px;
			flex-wrap: wrap;
			margin-top: 1.6rem;
			justify-content: center;
		}

		.chip {
			padding: 5px 14px;
			border-radius: 100px;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 0.3px;
			border: 1.5px solid;
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		.chip i {
			font-size: 12px;
		}

		.chip-pink {
			color: #ff6fb7;
			border-color: rgba(255, 111, 183, 0.3);
			background: rgba(255, 111, 183, 0.08);
		}

		.chip-purp {
			color: #a78bfa;
			border-color: rgba(167, 139, 250, 0.3);
			background: rgba(167, 139, 250, 0.08);
		}

		.chip-cyan {
			color: #0ea5e9;
			border-color: rgba(14, 165, 233, 0.3);
			background: rgba(14, 165, 233, 0.08);
		}

		/* ── Floating shapes (decorative) ── */
		.shape {
			position: fixed;
			border-radius: 50%;
			z-index: 0;
			pointer-events: none;
		}

		.s1 {
			width: 18px;
			height: 18px;
			background: #ff6fb7;
			top: 18%;
			left: 12%;
			opacity: 0.7;
			animation: float1 5s ease-in-out infinite;
		}

		.s2 {
			width: 12px;
			height: 12px;
			background: #ffe066;
			top: 30%;
			right: 10%;
			opacity: 0.8;
			animation: float2 6s ease-in-out infinite;
		}

		.s3 {
			width: 22px;
			height: 22px;
			background: #43e8d8;
			bottom: 22%;
			left: 8%;
			opacity: 0.6;
			animation: float1 7s ease-in-out infinite reverse;
		}

		.s4 {
			width: 14px;
			height: 14px;
			background: #a78bfa;
			bottom: 30%;
			right: 9%;
			opacity: 0.7;
			animation: float2 5.5s ease-in-out infinite;
		}

		.s5 {
			width: 10px;
			height: 10px;
			background: #ff9a3c;
			top: 60%;
			left: 6%;
			opacity: 0.65;
			animation: float1 8s ease-in-out infinite;
		}

		@keyframes float1 {

			0%,
			100% {
				transform: translateY(0)
			}

			50% {
				transform: translateY(-14px)
			}
		}

		@keyframes float2 {

			0%,
			100% {
				transform: translateY(0)
			}

			50% {
				transform: translateY(12px)
			}
		}
	</style>
</head>

<body>

	<!-- Blobs -->
	<div class="blob b1"></div>
	<div class="blob b2"></div>
	<div class="blob b3"></div>
	<div class="blob b4"></div>
	<div class="blob b5"></div>

	<!-- Floating dots -->
	<div class="shape s1"></div>
	<div class="shape s2"></div>
	<div class="shape s3"></div>
	<div class="shape s4"></div>
	<div class="shape s5"></div>

	<!-- Card -->
	<div class="card">

		<div class="logo-row">
			<div class="logo-badge">
				<i class="fa-solid fa-star"></i>
			</div>
			<span class="logo-name">Silver</span>
		</div>

		<h2 class="heading">Welcome back! </h2>
		<p class="subheading">Sign in to start your session</p>

		<?php echo validation_errors('<div class="error-message">', '</div>'); ?>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="error-message">
				<?php echo $this->session->flashdata('error'); ?>
			</div>
		<?php endif; ?>

		<form action="<?php echo base_url('login'); ?>" method="post">

			<div class="field">
				<label class="field-label" for="mobile">Mobile Number</label>
				<div class="input-wrap">
					<input class="field-input"
						type="text"
						id="mobile"
						name="mobile"
						value="<?php echo set_value('mobile'); ?>"
						placeholder="Enter your mobile number"
						autocomplete="off" />
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
						placeholder="Enter your password" />
					<span class="field-icon">
						<i class="fa-solid fa-lock"></i>
					</span>
				</div>
			</div>

			<button type="submit" class="btn-signin">
				Sign In
				<i class="fa-solid fa-arrow-right btn-arrow"></i>
			</button>

		</form>

		<div class="chips">
			<span class="chip chip-pink"><i class="fa-solid fa-lock"></i> Secure Login</span>
			<span class="chip chip-purp"><i class="fa-solid fa-bolt"></i> Fast Access</span>
			<span class="chip chip-cyan"><i class="fa-solid fa-circle-check"></i> Trusted</span>
		</div>

	</div>

</body>

</html>