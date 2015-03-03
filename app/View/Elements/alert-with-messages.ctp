<?php if(!empty($messages)):?>
	<div class="alert alert-success">
		<p>Success!</p>
		<ul>
			<?php foreach($messages as $message): ?>
				<li><?php echo $message;?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif;?>


<?php if(!empty($errors)):?>
	<div class="alert alert-danger">
		<p>Uh oh....</p>
		<ul>
			<?php foreach($errors as $error): ?>
				<li><?php echo $error;?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif;?>