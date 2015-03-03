<?php if(!$bookings):	
		$noResults = true;
	else:
		$noResults = false;
	endif;
?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-bookings')); ?>
<section class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header">
		<h1>All <?php echo (isset($pageTitle) ? $pageTitle : ''); ?> Bookings 			
			<?php if(isset($subPageTitle)){echo "<span class='badge'>$subPageTitle</span>";}?>
		</h1>
	</div>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada felis aliquam ornare fringilla. Vivamus at arcu scelerisque, pellentesque nisi vehicula, dictum quam. Aenean ultricies tellus a massa venenatis volutpat. Sed viverra nibh tempor lacus blandit venenatis.</p>
	<div class="booking-results clearfix table-responsive ">
		<table class="table table-bordered booskings-list-table table-condensed <?php echo ($noResults == true) ? 'no-results' : ''; ?>">
			<thead>
				<?php if($noResults == false){?>
					<tr>
						<th><?php echo $this->Paginator->sort('User.username','HRMS'); ?></th>
						<th><?php echo $this->Paginator->sort('Profile.fullname','Name'); ?></th>
						<th><?php echo $this->Paginator->sort('Region.name','Region'); ?></th>
						<th><?php echo $this->Paginator->sort('Store.name','Store/Dept'); ?></th>
						<th><?php echo $this->Paginator->sort('BookingCourse.name','Course'); ?></th>
						<th><?php echo $this->Paginator->sort('Event.name','Event'); ?></th>
						<th><?php echo $this->Paginator->sort('BookingStatus.name','Status'); ?></th>
						<th>Booked By</th>
					</tr>
					<?php }?>

			</thead>
			<tbody>
				<?php foreach ($bookings as $booking): ?>
				<tr class="clickable" data-url="/admin/bookings/view/<?php echo $booking['Booking']['id']; ?>/">
					<td>
						<?php echo $this->Html->link($booking['User']['username'], array('controller' => 'regions', 'action' => 'view', $booking['Region']['id'])); ?>
					</td>
					<td>
						<?php echo $booking['Profile']['first_name']; ?> <?php echo $booking['Profile']['surname']; ?>
					</td>
					<td>
						<?php echo $this->Html->link($booking['Region']['name'], array('controller' => 'regions', 'action' => 'view', $booking['Region']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($booking['Store']['name'], array('controller' => 'stores', 'action' => 'view', $booking['Store']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($booking['BookingCourse']['name'], array('controller' => 'booking_course', 'action' => 'view', $booking['BookingCourse']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($booking['Event']['name'], array('controller' => 'events', 'action' => 'view', $booking['Event']['id'])); ?>
					</td>
					<td>
						<?php echo $this->App->formatStatus($booking['BookingStatus']['id'], $booking['BookingStatus']['name'], false); ?>
					</td>
					<td>
						<?php echo $booking['Booking']['booked_by']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php if(!$bookings):?>				
					<tr><td colspan="9"><?php echo $this->element('no-results-found', array('text'=>'There are no bookings for this event/course. Please try again later.'));?></td></tr>
				<?php endif; ?>
			</tbody>
		</table>

		<?php echo $this->element('layout-pagination'); ?>
	</div>
</section>