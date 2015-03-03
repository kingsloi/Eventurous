<?php if(!$profiles): 
	$noResults = true;
  else:
	$noResults = false;
  endif;
?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-users')); ?>
<section class="page-content clearfix">
  <?php echo $this->Session->flash(); ?>
  
  <div class="page-header">
	<h1>All Users
	</h1>
  </div>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada felis aliquam ornare fringilla. Vivamus at arcu scelerisque, pellentesque nisi vehicula, dictum quam. Aenean ultricies tellus a massa venenatis volutpat. Sed viverra nibh tempor lacus blandit venenatis.</p>
  <div class="booking-results clearfix table-responsive ">
	<table class="table table-bordered profiles-list-table table-condensed <?php echo ($noResults == true) ? 'no-results' : ''; ?>">
	  <thead>
		<?php if($noResults == false){?>
		  <tr>
			<th><?php echo $this->Paginator->sort('User.username','HRMS'); ?></th>
			<th><?php echo $this->Paginator->sort('Profile.first_name','First Name'); ?></th>
			<th><?php echo $this->Paginator->sort('Profile.surname','Surname'); ?></th>
			<th><?php echo $this->Paginator->sort('Region.name','Region'); ?></th>
			<th><?php echo $this->Paginator->sort('Store.name','Store'); ?></th>
			<th><?php echo $this->Paginator->sort('JobTitle.title','Position'); ?></th>
			<th><?php echo $this->Paginator->sort('Profile.booking_count','Bookings'); ?></th>
		  </tr>
		<?php }?>
	  </thead>
	  <tbody>
		<?php foreach ($profiles as $profile): ?>
		<tr>
		  <td>
			<?php echo $this->Html->link($profile['User']['username'], array('controller' => 'profiles', 'action' => 'view', $profile['Profile']['id'])); ?>
		  </td>
		  <td>
			<?php echo $profile['Profile']['first_name']; ?>
		  </td>
		  <td>
			 <?php echo $profile['Profile']['surname']; ?>
		   </td>
		  <td>
			<?php echo $this->Html->link($profile['Region']['name'], array('controller' => 'reports', 'action' => 'stores', $profile['Store']['id'])); ?>
		  </td>
		  <td>
			<?php echo $this->Html->link($profile['Store']['name'], array('controller' => 'reports', 'action' => 'usersInStore', $profile['Store']['id'])); ?>
		  </td>
		  <td>
			<?php echo $profile['JobTitle']['title']; ?>
		  </td>
		  <td>
			<?php echo (($profile['Profile']['booking_count'] == 0) ? '0' : $profile['Profile']['booking_count']); ?>
		  </td>
		</tr>
		<?php endforeach; ?>
		<?php if(!$profiles):?>       
		  <tr><td colspan="9"><?php echo $this->element('no-results-found', array('text'=>'There are no bookings for this event/course. Please try again later.'));?></td></tr>
		<?php endif; ?>
	  </tbody>
	</table>

	<?php echo $this->element('layout-pagination'); ?>
  </div>
</section>