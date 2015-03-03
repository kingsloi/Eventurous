<?php if(!$stores): 
	$noResults = true;
  else:
	$noResults = false;
  endif;
?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-stores')); ?>
<section class="page-content clearfix">
  <?php echo $this->Session->flash(); ?>
  
  <div class="page-header">
	<h1>All Stores</h1>
  </div>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada felis aliquam ornare fringilla. Vivamus at arcu scelerisque, pellentesque nisi vehicula, dictum quam. Aenean ultricies tellus a massa venenatis volutpat. Sed viverra nibh tempor lacus blandit venenatis.</p>
  <div class="booking-results clearfix table-responsive ">
	<table class="table table-bordered stores-list-table table-condensed <?php echo ($noResults == true) ? 'no-results' : ''; ?>">
	  <thead>
		<?php if($noResults == false){?>
		  <tr>
			<th><?php echo $this->Paginator->sort('Store.name','Stores/Departments'); ?></th>
			<th><?php echo $this->Paginator->sort('Region.name','Region'); ?></th>
			<th><?php echo $this->Paginator->sort('Store.profile_count','Employees'); ?></th>
		  </tr>
		<?php }?>
	  </thead>
	  <tbody>
		<?php foreach ($stores as $store): ?>
		<tr>
		  <td>
			<?php echo $this->Html->link($store['Store']['name'], array('controller' => 'reports', 'action' => 'usersInStore', $store['Store']['id'])); ?>
		  </td>
		  <td>
			<?php echo $this->Html->link($store['Region']['name'], array('controller' => 'reports', 'action' => 'usersInRegion', $store['Region']['id'])); ?>
		  </td>
		  <td>
			<?php echo $store['Store']['profile_count'];?>
		  </td>
		</tr>
		<?php endforeach; ?>
		<?php if(!$stores):?>       
		  <tr><td colspan="9"><?php echo $this->element('no-results-found', array('text'=>'There are no bookings for this event/course. Please try again later.'));?></td></tr>
		<?php endif; ?>
	  </tbody>
	</table>

	<?php echo $this->element('layout-pagination'); ?>
  </div>
</section>