<?php
	//echo $this->Html->meta('icon');
	// echo $this->Html->css('bootstrap/bootstrap.css');
	// echo $this->Html->css('bootstrap/datepicker.css');
	// echo $this->Html->css('bootstrap/dataTables.bootstrap.css');
	// echo $this->Html->css('fonts.css');
	// echo $this->Html->css('app.css');
	// echo $this->Html->css('app-responsive.css');
	
	echo $this->Minify->css(array(
		'bootstrap/bootstrap', 
		'bootstrap/datepicker',
		'bootstrap/dataTables.bootstrap',
		'fonts',
		'app',
		'app-responsive'
		)
	);
	
?>