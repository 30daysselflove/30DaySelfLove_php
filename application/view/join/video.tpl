<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />

		<!-- METADATA -->
		<meta name="description" content="{if $video->description}{$video->description}{else}Video of my personal #30DaysSelfLove Journey{/if}" />
		<meta name="keywords" content="#30DaysSelfLove, App, Mobile, Android, iOS" />
		<meta name="author" content="{if $user->realName}{$user->realName}{else}{$user->username}{/if}" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<!-- PAGE TITLE -->
		<title>{if $video->title}{$video->title}{else}#30DaysSelfLove - Video{/if}</title>

		<!-- FAVICON -->
        <link rel="apple-touch-icon" sizes="57x57" href="/join/images/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/join/images/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/join/images/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/join/images/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/join/images/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/join/images/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/join/images/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/join/images/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/join/images/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="/join/images/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/join/images/favicons/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/join/images/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/join/images/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/join/images/favicons/manifest.json">
        <link rel="shortcut icon" href="/join/images/favicons/favicon.ico">
        <meta name="msapplication-TileColor" content="#a23abd">
        <meta name="msapplication-TileImage" content="/join/images/favicons/mstile-144x144.png">
        <meta name="msapplication-config" content="/join/images/favicons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">

        <meta property="og:video" content="{$video->mediaURL}" />
        <meta property="og:video:height" content="640" />
        <meta property="og:video:width" content="385" />
        <meta property="og:video:type" content="video/mp4" />
        <meta property="og:image" content="http://{$video->thumbImageURL}" >

		<!--
		=================================
		STYLESHEETS
		=================================
		-->

		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="/join/css/bootstrap.min.css" />

		<!-- WEB FONTS -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />

		<!-- ICON FONTS -->
		<link rel="stylesheet" href="/join/css/font-awesome.min.css" />
		<link rel="stylesheet" href="/join/css/simple-line-icons.min.css" />

		<!-- OTHER STYLES -->
		<link rel="stylesheet" href="/join/css/animate.min.css" />
		<link rel="stylesheet" href="/join/css/owl.carousel.min.css" />
		<link rel="stylesheet" href="/join/css/nivo-lightbox.min.css" />
		<link rel="stylesheet" href="/join/css/nivo-lightbox/default.min.css" />

		<!-- MAIN STYLES -->
		<link rel="stylesheet" href="/join/css/style.css" />

		<!-- COLORS -->
		<link id="color-css" rel="stylesheet" href="/join/css/colors/green.css" />
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/turquoise.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/blue.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/purple.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/pink.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/red.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/orange.css" /> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/yellow.css" /> -->

		<!-- JQUERY -->
		<script src="/join/js/jquery-1.11.1.min.js"></script>

	</head>

	<body class="with-preloader">
    <iframe style="display:none" height="0" width="0" id="appLoader" src="{$redirect}"></iframe>
		
		<!--
		=================================
		PRELOADER
		=================================
		-->
		<div id="preloader" class="preloader">
			<div class="preloader-inner">
				<span class="preloader-logo">
					<img src="/join/images/logos/preloader-logo.png" alt="#30DaysSelfLove" />
					<strong>Loading</strong>
				</span>
			</div>
		</div>

		<div id="document" class="document">


			<!--
			=================================
			HERO SECTION
			=================================
			-->
			<section id="home" class="hero-section hero-layout-2 section section-inverse-color parallax-background" data-stellar-background-ratio="0.4">

				<!-- BACKGROUND OVERLAY -->
				<div class="black-background-overlay"></div>

				<div class="container">

					<div class="hero-content">

						<!-- HERO TEXT -->
						<div class="hero-text">
							
							<!-- LOGO -->
							<div class="hero-logo wow fadeIn" data-wow-duration="1s">
								<img src="/join/images/logos/hero-logo.png" alt="#30DaysSelfLove" />
							</div>

							<!-- TAGLINE -->
							<h1 class="hero-title wow fadeInUp" data-wow-duration="1s" style="margin-bottom: 5px;">{$video->title}</h1>
							<h3 class="wow fadeInUp" data-wow-duration="2s" style="margin-bottom: 20px">{$video->description}</h3>


                                    <div class="fadeIn" data-wow-duration="1s" style="margin-bottom: 40px">
                                        <video controls style="background-color: #000000;width:90%;max-width: 500px;max-height: 281px;">
                                            <source src="//{$video->mediaURL}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>

                                    </div>
							<!-- DOWNLOAD BUTTONS -->
							<p class="download-buttons wow fadeInUp clearfix" data-wow-duration="1s" data-wow-delay="0.5s">
								<!-- APP STORE DOWNLOAD -->
								<a href="https://itunes.apple.com/us/app/30-days-self-love/id952893123?ls=1&mt=8" class="btn btn-app-download btn-ios">
									<i class="fa fa-apple"></i>
									<strong>Download App</strong> <span>from App Store</span>
								</a>
							</p>

						</div>
					</div>

				</div>
			</section>

			<!--
			=================================
			FOOTER SECTION
			=================================
			-->
			<footer class="footer-section section">

				<!-- CONTACT SECTION TOGGLE -->
				<a href="#contact" class="contact-toggle" data-toggle="collapse"><i class="icon-envelope"></i></a>

				<div class="container">



					<!-- SOCIAL MEDIA LINKS -->
					<ul class="social-media-links wow fadeIn" data-wow-duration="1s">
						<li><a href="http://facebook.com"><i class="fa fa-facebook"></i><span class="sr-only">Facebook</span></a>
						<li><a href="http://twitter.com"><i class="fa fa-twitter"></i><span class="sr-only">Twitter</span></a>
						<li><a href="http://instagram.com"><i class="fa fa-instagram"></i><span class="sr-only">Instagram</span></a>
						<li><a href="http://pinterest.com"><i class="fa fa-pinterest"></i><span class="sr-only">Pinterest</span></a>
						<li><a href="#"><i class="fa fa-envelope"></i><span class="sr-only">Email</span></a>
					</ul>

					<!-- COPYRIGHT -->
					<div class="copyright">Copyright &copy; #30DaysSelfLove, All rights reserved</div>
				</div>
			</footer>

		</div>

		<!--
		=================================
		JAVASCRIPTS
		=================================
		-->
		<script src="/join/js/bootstrap.min.js"></script>
		<script src="/join/js/retina.min.js"></script>
		<script src="/join/js/smoothscroll.min.js"></script>
		<script src="/join/js/wow.min.js"></script>
		<script src="/join/js/jquery.nav.min.js"></script>
		<script src="/join/js/nivo-lightbox.min.js"></script>
		<script src="/join/js/jquery.stellar.min.js"></script>
		<script src="/join/js/owl.carousel.min.js"></script>
		<script src="/join/js/script.js"></script>

	</body>

</html>