<?php if(!$lists): 
	$noResults = true;
else:
	$noResults = false;
endif;


?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-bookings')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Choose a report booking category</h1>
	</div>
	<p>Choose which category you'd like to see the bookings for. View bookings by&hellip;</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course category below</span>
	</div>
	<div class="booking-results clearfix table-responsive ">
		<table class="table table-bordered bookings-index-table <?php echo ($noResults == true) ? 'no-results' : ''; ?>">

			<thead>
				<?php if($noResults == false){?>
				<tr>
					<th><?php echo $this->Paginator->sort($sortColumn,'Value'); ?></th>
					<th>View Bookings</th>
				</tr>
				<?php }?>
			</thead>
			<tbody>
				<?php foreach ($lists as $listID => $list): ?>
					<tr>
		  				<td><?php echo $list;?></td>
		  				<td>
		  					<a href="/admin/reports/bookings/<?php echo $subLink;?>/<?php echo $listID;?>">
		  						View Bookings &rarr;
		  					</a>
		  				</td>
		  			</tr>
				<?php endforeach; ?>
				<?php if(!$lists):?>       
				  <tr>
				  	<td colspan="2"><?php echo $this->element('no-results-found', array('text'=>'There are no bookings for this event/course. Please try again later.'));?>
				  	</td>
				  </tr>
				<?php endif; ?>
		  		</tbody>
		</table>
		<?php echo $this->element('layout-pagination'); ?>
	</div>
</div><!-- /page-content-->