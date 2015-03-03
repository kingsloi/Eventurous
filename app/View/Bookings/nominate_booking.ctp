<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'add-booking')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header clearfix">
		<h1 class="col-lg-9">Nominate an Employee
			<span class="badge"><?php echo $courseName;?></span>
			<span class="badge"><?php echo $eventName;?></span>
		</h1>
		<?php echo $this->element('booking-progress'); ?>
	</div>
	<p>Using the search field below, select either HRMS, First Name, or Surname to search for the person you would like to nominate for the selected course.</p>
	<div class="col-lg-4">
		<div class="helper">
			<span class="badge alert-info steps">6</span>
			<span class="helper-text">Select the option you'd like to search, and enter in a search query</span>
		</div>
		<h2 class="h3">Employee Search</h2>
		<?php echo $this->Form->create('Search', array('class' => 'form-inline', 'role'=>'form'));?>                
			<div class="search-options">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default">
						<input type="radio" name="data[Search][criteria]" id="SearchCriteriaHrms" value="hrms"> HRMS
					</label>
					<label class="btn btn-default">
						<input type="radio" name="data[Search][criteria]" id="SearchCriteriaFirstname" value="firstname"> First Name
					</label>
					<label class="btn btn-default">
						<input type="radio" name="data[Search][criteria]" id="SearchCriteriaSurname" value="surname"> Surname
					</label>
				</div>

			</div>
			<div class="search-option-fields clearfix">
				<?php echo $this->Form->input('search', array('div'=>false, 'label' => false,'class'=>'form-control','placeholder'=>'e.g. 123456/Paul/Smith','autofocus'=>'autofocus'));?> 
				<button class="btn btn-primary btn" type="submit" id="show-details"><span class="glyphicon glyphicon-search"></span> Search</button>
			</div>
		<?php echo $this->Form->end(); ?>

		<div id="responseError" class="alert alert-danger alert-sm"></div>
		<div id="multiple-results">
			<div class="helper">
				<span class="badge alert-info steps point">6.5</span>
				<span class="helper-text">Choose an employee from the list below</span>
			</div>
			<h3 class="h4">Select an employee below:</h3>
			<div class="table-responsive">
				<table id="results" class="table">
					<thead>
						<tr>
							<th>HRMS#</th>
							<th>Name</th>
							<th>Location</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-lg-8 user-details">
		<div class="helper col-lg-offset-2">
			<span class="badge alert-info steps">7</span>
			<span class="helper-text">Confirm that the details match the person that you'd like to nomicate</span>
		</div>
		<?php echo $this->Form->create('Booking', array('method'=>'post','class' => 'form-horizontal', 'role'=>'form'));?>  
		<?php echo $this->Form->input('Profile.id', array('type'=>'text','div'=>false, 'label' => false,'class'=>'hidden form-control'));?>
			<fieldset class="add-booking-confirmation">
				<div class="form-group">
					<label for="UserUsername" class="col-lg-2 control-label">HRMS</label>
					<div class="col-lg-10">
						<?php echo $this->Form->input('User.username', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
				</div>
				<div class="form-group">
					<label for="ProfileFirstName" class="col-lg-2 control-label">First Name</label>
					<div class="col-lg-4">
						<?php echo $this->Form->input('Profile.first_name', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
					<label for="ProfileSurname" class="col-lg-2 control-label">Surname</label>
					<div class="col-lg-4">
						<?php echo $this->Form->input('Profile.surname', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
				</div>
				<div class="form-group">
					<label for="RegionName" class="col-lg-2 control-label">Region</label>
					<div class="col-lg-4">
						<?php echo $this->Form->input('Region.name', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
					<label for="StoreName" class="col-lg-2 control-label">Store</label>
					<div class="col-lg-4">
						<?php echo $this->Form->input('Store.name', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
				</div>
				<div class="form-group">
					<label for="BookingArd" class="col-lg-2 control-label">ARD</label>
					<div class="col-lg-10">
						<?php echo $this->Form->input('ard', array('disabled'=>true,'div'=>false, 'label' => false,'class'=>'form-control locked'));?>
					</div>
				</div>
			</fieldset>
            <div class="form-group">
                <label for="update-user-information" class="col-lg-2 control-label">Incorrect Information?</label>
                <div class="col-lg-10">
                    <p class="small-text" style="padding-top:15px">
                    	A user report from HRMS is ran and imported in to the system once a week. Please ensure any changes to a nominee's details are made in HRMS.
                    </p>
                </div>
            </div>
            <?php if($bookingReasons):?>
	            <div class="form-group">
	                <label for="BookingBookingReasonId" class="col-lg-2 control-label">Reason for Request</label>
	                <div class="col-lg-10">
						<?php echo $this->Form->input('booking_reason_id', array('empty' => true,'div'=>false, 'label' => false,'class'=>'form-control'));?>
	                </div>
	            </div>
        	<?php endif; ?>
            <div class="form-group">
            	<div class="col-lg-2">
            		&nbsp;
            	</div>
            	<div class="col-lg-10">
            		<button class="btn btn-danger" type="reset">
            			<span class="glyphicon glyphicon-remove"></span> Cancel
            		</button>
            		<button type="submit" class="btn btn-success pull-right" id="submit-nomination">Next 
            			<span class="glyphicon glyphicon-arrow-right"></span>
            		</button>
            	</div>
            </div>
		</form>
	</div>
</div>