<?php

	$ID 			= $item['id'];
	$controller		= $item['controller']; 
	$action 		= $item['action'];
	$actionLink 	= ucwords($action); 
	$name 			= $item['name'];
	$nameTitle		= ucwords($name);
	$fullAction		= $item['fullAction'];
	$actionPlural	= $fullAction."s";
	$categoryID		= $item['categoryID'];
	$categoryURL	= $item['categoryURL'] .$categoryID. "/";
?>
<div class="actions">
	<ul class="nav nav-pills nav-stacked">
		<li class="editLink">
			<?php echo "<a href='/admin/$controller/$action/$ID'>$actionLink $nameTitle</a>";?>
		</li>
		<li class="deleteLink">
			<?php echo $this->Form->postLink(__('Delete '.$nameTitle), array('controller'=>$controller,'action' => 'delete', $ID), array('class' => ''), __('Are you sure you want to delete %s? This action cannot be undone.', $name.'#'.$ID)); ?>
		</li>
		<li class="viewAllLink">
			<a href="<?php echo $categoryURL; ?>">View All <?php echo $actionPlural;?></a>
		</li>
	</ul><!-- /.list-group -->
</div><!-- /.actions -->
