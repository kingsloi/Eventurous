<?php if(!$regions): 
	$noResults = true;
  else:
	$noResults = false;
  endif;
?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-regions')); ?>
<section class="page-content clearfix">
  <?php echo $this->Session->flash(); ?>
  
  <div class="page-header">
	<h1>All Regions</h1>
  </div>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam malesuada felis aliquam ornare fringilla. Vivamus at arcu scelerisque, pellentesque nisi vehicula, dictum quam. Aenean ultricies tellus a massa venenatis volutpat. Sed viverra nibh tempor lacus blandit venenatis.</p>
  <div class="booking-results clearfix table-responsive ">
	<table class="table table-bordered regions-list-table table-condensed <?php echo ($noResults == true) ? 'no-results' : ''; ?>">
	  <thead>
		<?php if($noResults == false){?>
		  <tr>
			<th><?php echo $this->Paginator->sort('Region.name','Region'); ?></th>
			<th><?php echo $this->Paginator->sort('Region.store_count','Stores/Departments'); ?></th>
		  </tr>
		<?php }?>
	  </thead>
	  <tbody>
		<?php foreach ($regions as $region): ?>
		<tr>
		  <td>
			<?php echo $this->Html->link($region['Region']['name'], array('controller' => 'reports', 'action' => 'stores', $region['Region']['id'])); ?>
		  </td>
		  <td>
			<?php echo $region['Region']['store_count'];?>
		  </td>
		</tr>
		<?php endforeach; ?>
		<?php if(!$regions):?>       
		  <tr><td colspan="9"><?php echo $this->element('no-results-found', array('text'=>'There are no bookings for this event/course. Please try again later.'));?></td></tr>
		<?php endif; ?>
	  </tbody>
	</table>

	<?php echo $this->element('layout-pagination'); ?>
  </div>
</section>