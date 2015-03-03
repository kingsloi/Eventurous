<section class="password-reset page-content small-centered">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Password Reset</h1>
	</div>


	<?php

	if(!isset($requestMoreInfo)){ ?>
		<?php echo $this->Form->create('User', array('url' => $this->here,'class' => 'password-reset-form form-horizontal', 'role'=>'form'));?>
		

		<?php if(!isset($newPasswordForm)){ ?>


			<p>To request a password reset, enter your HRMS# and press the <strong>Request Password Reset</strong> button, and further correspondence will follow by email/phone.</p>
			<div class="form-group">
				<?php echo $this->Form->input('formType', array('type'=>'hidden','value'=>'requestPassword'));?>
				<?php echo $this->Form->input('username', array('div'=>'col-lg-12', 'label' => false,'class'=>'form-control input-lg','placeholder'=>'HRMS#', 'autofocus'=>'autofocus'));?> 
			</div>
			<button type="submit" class="btn btn-primary btn-lg btn-block">Request Password Reset</button>        


		<?php }else{ ?>

			<p>To reset your password, enter your HRMS#, enter a new password, enter your new password again, and then press <strong>Reset Password</strong>.</p>
			<div class="form-group">
				<?php echo $this->Form->input('formType', array('type'=>'hidden','value'=>'resetPassword'));?>
				<?php echo $this->Form->input('username', array('div'=>'col-lg-12', 'label' => 'HRMS#','class'=>'form-control input-lg','placeholder'=>'hrms#', 'autofocus'=>'autofocus','type'=>'text'));?> 
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('newPassword', array('div'=>'col-lg-12', 'label' => 'New Password','class'=>'form-control input-lg','placeholder'=>'new password','type'=>'password'));?> 
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('newPasswordRepeat', array('div'=>'col-lg-12', 'label' => 'Repeat New Password','class'=>'form-control input-lg','placeholder'=>'repeated new password','type'=>'password'));?> 
			</div>
			<button type="submit" class="btn btn-primary btn-lg btn-block">Reset Password</button>        

		<?php } ?>



		<?php echo $this->Form->end(); ?>

	<?php }else{ ?>

		<p>Sorry, there are no contact details stored on that account. Please contact the system administrator.</p>
		<div class="form-group">
			<div class="col-lg-12"><?php echo Configure::read('APP_ADMIN_NAME'); ?></div>
			<div class="col-lg-12"><?php echo Configure::read('APP_ADMIN_PHONE');?></div>
			<div class="col-lg-12">
				<a href="mailto:<?php echo Configure::read('APP_ADMIN_EMAIL');?>"><?php echo Configure::read('APP_ADMIN_EMAIL');?></a>
			</div>
		</div>
	<?php } ?>
	<div class="clearfix">
		<a href="/" class="btn btn-primary pull-left">&larr; Return Home</a>
	</div>
</section>