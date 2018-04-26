<?php /* Smarty version Smarty-3.1.11, created on 2015-02-11 01:16:39
         compiled from "../application/view/join/user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:90016435254d18682adde11-14849238%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '68b93fe65015bf5378a36dac196b777944e40ac3' => 
    array (
      0 => '../application/view/join/user.tpl',
      1 => 1423646184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '90016435254d18682adde11-14849238',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_54d18682af2eb0_09430504',
  'variables' => 
  array (
    'user' => 0,
    'redirect' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54d18682af2eb0_09430504')) {function content_54d18682af2eb0_09430504($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />

    <!-- METADATA -->
    <meta name="description" content="Join me in your own #30DaysSelfLove Journey!" />
    <meta name="keywords" content="#30DaysSelfLove, App, Mobile, Android, iOS" />
    <meta name="author" content="<?php if ($_smarty_tpl->tpl_vars['user']->value->realName){?><?php echo $_smarty_tpl->tpl_vars['user']->value->realName;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['user']->value->username;?>
<?php }?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- PAGE TITLE -->
    <title>#30DaysSelfLove - Join me!</title>

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

    <meta property="og:image" content="//join.30daysselflove.com/join/images/favicons/android-chrome-192x192.png" >


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
    <iframe style="display:none" height="0" width="0" id="appLoader" src="<?php echo $_smarty_tpl->tpl_vars['redirect']->value;?>
"></iframe>

    <script>
        $(function()
        {
            //$("#appLoader");
        });
    </script>
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
								<img src="/join/images/logos/hero-logo.png" alt="#30DaysSelfLove Logo" />
							</div>

							<!-- TAGLINE -->
							<h1 class="hero-title wow fadeInUp" data-wow-duration="1s">Join me on my personal journey by starting your own!</h1>

							<!-- DOWNLOAD BUTTONS -->
							<p class="download-buttons wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
								<!-- APP STORE DOWNLOAD -->
								<a href="https://itunes.apple.com/us/app/30-days-self-love/id952893123?ls=1&mt=8" class="btn btn-app-download btn-ios">
									<i class="fa fa-apple"></i>
									<strong>Download App</strong> <span>from App Store</span>
								</a>

								<!-- WINDOWS PHONE STORE DOWNLOAD -->
								<!--<a href="#please-edit-this-link" class="btn btn-app-download btn-windows-phone">
									<i class="fa fa-windows"></i>
									<strong>Download App</strong> <span>from Windows Store</span>
								</a>-->
							</p>

							<!-- WATCH THE VIDEO -->
							<a href="#video" class="hero-watch-video-link anchor-link wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s"><i class="fa fa-youtube-play"></i>Watch The Video</a>

						</div>
					</div>

				</div>
			</section>

			<!--
			=================================
			VIDEO SECTION
			=================================
			-->
			<section id="video" class="video-section section section-inverse-color parallax-background" data-stellar-background-ratio="0.4">

				<!-- BACKGROUND OVERLAY -->
				<div class="black-background-overlay"></div>

				<div class="container">

					<div class="row">
						<div class="col-md-10 col-md-offset-1">

							<div class="video-embed wow fadeIn" data-wow-duration="1s">
								<!-- VIDEO EMBED FROM YOUTUBE: please change the URL -->
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/XHRyTzOVKIo" frameborder="0" allowfullscreen></iframe>
							</div>
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

					<!-- DOWNLOAD BUTTONS -->
					<p class="download-buttons wow fadeIn" data-wow-duration="1s">
						<!-- APP STORE DOWNLOAD -->
						<a href="https://itunes.apple.com/us/app/30-days-self-love/id952893123?ls=1&mt=8" class="btn btn-app-download btn-ios">
							<i class="fa fa-apple"></i>
							<strong>Download App</strong> <span>from App Store</span>
						</a>

						<!-- WINDOWS PHONE STORE DOWNLOAD -->
						<!--<a href="#please-edit-this-link" class="btn btn-app-download btn-windows-phone">
							<i class="fa fa-windows"></i>
							<strong>Download App</strong> <span>from Windows Store</span>
						</a>-->
					</p>

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

</html><?php }} ?>