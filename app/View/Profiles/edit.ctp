<?php echo $this->element('layout-header', array('currentPage'=>'my-profile')); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>

	<div class="page-header">
		<h1>Edit Your Profile</h1>
	</div>
	<p>A user report from HRMS is imported into the system on a weekly basis. If the details below aren't current, please ensure that they are correct in HRMS. However, contact details are not imported. To ensure that the booking process is as smooth as possible, and to be informed on changes to your bookings, add an email address and phone number to your account.</p>
	<div class="col-md-4">
		<?php echo $this->Form->create('User', array('class'=>'form-horizontal','inputDefaults' => array('label' => false), 'role' => 'form')); ?>
		<h2>Update Password</h2>
		<p>To update your password, enter your new password, repeat it, and then click <strong>Update Password</strong>.</p>
			<div class="form-group">
				<?php echo $this->Form->input('formType', array('type'=>'hidden','value'=>'updatePassword'));?>
				<?php echo $this->Form->input('password', array('div'=>'col-lg-12', 'label' => 'New Password','class'=>'form-control','placeholder'=>'new password','type'=>'password'));?> 
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('passwordRepeat', array('div'=>'col-lg-12', 'label' => 'Repeat New Password','class'=>'form-control','placeholder'=>'repeated new password','type'=>'password'));?> 
			</div>
			<?php echo $this->Form->submit('Update Password', array('class' => 'btn btn-md btn-primary', 'div'=>'pull-right actions')); ?>
			<?php echo $this->Form->end(); ?>   
	</div>
	<div class="col-md-8">
		<h2>User Details</h2>
		<p>Please ensure the details below are correct - update them if necessary.</p>
			<?php echo $this->Form->create('Profile', array('class'=>'form-horizontal','inputDefaults' => array('label' => false), 'role' => 'form')); ?>
				<?php echo $this->Form->input('formType', array('type'=>'hidden','value'=>'addContactDetails'));?>
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 control-label">Store</label>
						<div class="col-lg-10"><p class="form-control-static"><?php echo $store['Store']['name'];?></p></div>
					</div><!-- .form-group -->

					<div class="form-group">
						<label class="col-lg-2 control-label">Job Title</label>
						<div class="col-lg-10"><p class="form-control-static"><?php echo $jobTitle['JobTitle']['title'];?></p></div>
					</div><!-- .form-group -->

					<div class="form-group">
						<label class="col-lg-2 control-label">First Name</label>
						<div class="col-lg-10"><p class="form-control-static"><?php echo $profile['Profile']['first_name'];?></p></div>
					</div><!-- .form-group -->

					<div class="form-group">
						<label class="col-lg-2 control-label">Surname</label>
						<div class="col-lg-10"><p class="form-control-static"><?php echo $profile['Profile']['surname'];?></p></div>
					</div><!-- .form-group -->

					<div class="form-group">
						<label class="col-lg-2 control-label">Email</label>
						<?php echo $this->Form->input('email', array('required'=>false,'class' => 'form-control', 'div'=>'col-lg-10')); ?>
					</div><!-- .form-group -->
					<div class="form-group">
						<label class="col-lg-2 control-label">Phone Number</label>
						<?php echo $this->Form->input('phonenumber', array('required'=>false,'class' => 'form-control', 'div'=>'col-lg-10')); ?>
					</div><!-- .form-group -->
				</fieldset>
			<?php echo $this->Form->submit('Save Profile', array('class' => 'btn btn-lg btn-primary', 'div'=>'pull-right actions')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
</div>
