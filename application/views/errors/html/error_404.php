<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>KD Brothers — Page Not Found</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
			background: #f5f0e8;
		}

		.panel-video {
			position: relative;
			flex: 1.1;
			overflow: hidden;
			display: none;
		}

		@media (min-width: 900px) {
			.panel-video {
				display: block;
			}
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
			background: linear-gradient(135deg,
					rgba(0, 0, 0, 0.55) 0%,
					rgba(10, 10, 10, 0.30) 50%,
					rgba(0, 0, 0, 0.65) 100%);
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
			color: rgba(255, 255, 255, 0.68);
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
			border: 1.5px solid rgba(201, 168, 76, 0.45);
			color: rgba(255, 255, 255, 0.80);
			background: rgba(201, 168, 76, 0.10);
			display: flex;
			align-items: center;
			gap: 6px;
		}

		.video-tag i {
			font-size: 11px;
			color: #c9a84c;
		}

		.panel-error {
			flex: 0 0 460px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2.5rem 2rem;
			background: #faf7f2;
			position: relative;
			overflow: hidden;
			box-shadow: -6px 0 40px rgba(0, 0, 0, 0.10);
		}

		@media (max-width: 900px) {
			.panel-error {
				flex: 1;
				min-height: 100vh;
			}
		}

		.panel-error::before {
			content: '';
			position: absolute;
			width: 500px;
			height: 500px;
			border-radius: 50%;
			border: 1px solid rgba(201, 168, 76, 0.14);
			top: -120px;
			right: -160px;
			pointer-events: none;
		}

		.panel-error::after {
			content: '';
			position: absolute;
			width: 360px;
			height: 360px;
			border-radius: 50%;
			border: 1px solid rgba(201, 168, 76, 0.10);
			bottom: -80px;
			left: -80px;
			pointer-events: none;
		}

		.error-card {
			position: relative;
			z-index: 2;
			width: 100%;
			max-width: 380px;
		}

		.logo-row {
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.logo-badge {
			width: 46px;
			height: 46px;
			border-radius: 14px;
			background: linear-gradient(135deg, #c9a84c, #f0d080);
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 24px rgba(201, 168, 76, 0.30);
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

		.mobile-brand {
			display: none;
			font-size: 11px;
			letter-spacing: 2px;
			text-transform: uppercase;
			color: rgba(201, 168, 76, 0.80);
			font-weight: 700;
			margin-bottom: 2rem;
		}

		@media (max-width: 900px) {
			.mobile-brand {
				display: block;
			}
		}

		.error-code {
			font-family: 'Cormorant Garamond', serif;
			font-size: 7rem;
			font-weight: 700;
			line-height: 0.9;
			letter-spacing: 4px;
			color: #1a1209;
			margin-bottom: 1rem;
		}

		.error-code span {
			color: #c9a84c;
		}

		.heading {
			font-size: 28px;
			font-weight: 800;
			color: #1a1209;
			line-height: 1.2;
			margin-bottom: 8px;
		}

		.subheading {
			font-size: 14px;
			color: rgba(30, 20, 5, 0.52);
			font-weight: 500;
			line-height: 1.7;
			margin-bottom: 2rem;
		}

		.divider {
			width: 40px;
			height: 2px;
			background: linear-gradient(90deg, #c9a84c, #f0d080);
			border-radius: 2px;
			margin-bottom: 2rem;
		}

		.btn-row {
			display: flex;
			gap: 12px;
			flex-wrap: wrap;
		}

		.btn-primary,
		.btn-secondary {
			text-decoration: none;
			border-radius: 12px;
			padding: 14px 18px;
			font-size: 13.5px;
			font-weight: 800;
			letter-spacing: 1px;
			text-transform: uppercase;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			transition: 0.2s ease;
		}

		.btn-primary {
			flex: 1;
			background: linear-gradient(135deg, #c9a84c, #e8c96a, #c9a84c);
			color: #0f0f0f;
			box-shadow: 0 10px 28px rgba(201, 168, 76, 0.22);
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 14px 36px rgba(201, 168, 76, 0.32);
		}

		.btn-secondary {
			width: 52px;
			color: #1a1209;
			background: #fff;
			border: 1.5px solid rgba(180, 150, 80, 0.18);
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
		}

		.btn-secondary:hover {
			color: #c9a84c;
			transform: translateY(-2px);
		}

		.footer-note {
			margin-top: 2rem;
			text-align: center;
			font-size: 11.5px;
			color: rgba(30, 20, 5, 0.32);
			letter-spacing: 0.3px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
		}

		.footer-note i {
			font-size: 11px;
			color: rgba(201, 168, 76, 0.60);
		}
	</style>
</head>

<body>

	<div class="panel-video">
		<video autoplay muted loop playsinline>
			<source src="/assets/video3.mp4" type="video/mp4">
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

	<div class="panel-error">
		<div class="error-card">

			<p class="mobile-brand">
				<i class="fa-solid fa-gem"></i> &nbsp;KD Brothers
			</p>

			<div class="logo-row">
				<div class="logo-badge">
					<i class="fa-solid fa-gem"></i>
				</div>
				<span class="logo-name">KD Brothers</span>
			</div>

			<div class="error-code">4<span>0</span>4</div>

			<h1 class="heading">Page not found</h1>

			<p class="subheading">
				The page you are looking for may have been moved, removed,
				or is temporarily unavailable.
			</p>

			<div class="divider"></div>

			<div class="btn-row">
				<a href="/" class="btn-primary">
					Go Home
					<i class="fa-solid fa-arrow-right"></i>
				</a>
			</div>

			<p class="footer-note">
				<i class="fa-solid fa-lock"></i>
				Secured &amp; encrypted connection
			</p>

		</div>
	</div>

</body>

</html>