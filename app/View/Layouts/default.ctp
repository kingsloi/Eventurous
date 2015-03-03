<?php
	$cakeDescription = __d('mdp', 'Booking 4u - Phones 4u Event Booking');
	$bodyClass = (isset($bodyClass) ? $bodyClass : '');
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
   <head>
	   	<?php echo $this->Html->charset(); ?>
	   	<base href="http://eventours.dev/">
	    <meta http-equiv="X-UA-Compatible" 	content="IE=edge">
	    <meta name="viewport" 				content="width=device-width, initial-scale=1.0">
	    <meta name="description" 			content="">
	    <meta name="author" 				content="">
 		<title><?php echo $title_for_layout; ?> &rsaquo; <?php echo $cakeDescription ?></title>
		<?php echo $this->element('layout-stylesheets');?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="/js/ie/html5shiv.min.js"></script>
			<script src="/js/ie/respond.min.js"></script>
			<?php echo $this->Html->css('ie.css');?>
		<![endif]-->

	</head>
	<body class="<?php echo $bodyClass;?>">
		<section class="container-full <?php echo (AuthComponent::user('role')=="admin" ? 'admin': ''); ?>">
			<?php echo $this->fetch('content'); ?>
		</section><!-- /container-full -->

		<footer id="main-footer " style="margin-left:180px">
			<div style="container">
				<small>
					<?php echo $this->element('sql_dump'); ?>
				</small>
			</div>
		</footer><!-- #.main-footer -->
	<?php echo $this->element('layout-javascripts');?>
  </body>
</html>