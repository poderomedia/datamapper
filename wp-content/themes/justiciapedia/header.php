<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<title><?php echo w_title(); ?></title>

		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/stylesheets/application.css" type="text/css" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/stylesheets/extend.css" type="text/css" charset="utf-8" />
		
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/javascripts/jquery-1.10.2.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/javascripts/bootstrap.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/javascripts/application.js"></script>

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	</head>
	
	<body>
		<div class="bar-top">
			<div class="row">
				<div class="col16 clearfix">
					<ul class="clearfix">
						<li><a href="http://chequeado.com/">Ir a Chequeado.com</a></li>
					</ul>
				</div>
			</div>
		</div>

		<header>
			<div class="row">
				<div class="col16 clearfix">

					<a href="#" class="btn-collapse collapsed" data-toggle="collapse" data-target="#nav-main">
						<i></i> Menu
					</a>
 
					<nav id="nav-main" class="clearfix collapse navbar-collapse">
						<a href="<?php bloginfo('url'); ?>/judges/" class="nav-item">Jueces</a>
						<a href="<?php bloginfo('url'); ?>/prosecutors/" class="nav-item">Fiscales</a>
						<a href="<?php bloginfo('url'); ?>/attorneys/" class="nav-item">Abogados</a>
						<a href="<?php bloginfo('url'); ?>/others/" class="nav-item">Más</a>
						<a href="https://www.donaronline.org/chequeado-com/donantes-fieles" target="_blank" class="btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/tmp/ico-check-color.png" alt="" /> Doná acá</a>
					</nav>
						
					<h2 class="logo">
						<a href="<?php bloginfo('url'); ?>">Justiciapedia</a>
					</h2>

				</div>
			</div>
		</header>