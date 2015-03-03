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
			<?php echo "<a href='/$controller/$action/$ID'>$actionLink $nameTitle</a>";?>
		</li>
		<li class="viewAllLink">
			<a href="<?php echo $categoryURL; ?>">View All <?php echo $actionPlural;?></a>
		</li>
	</ul><!-- /.list-group -->
</div><!-- /.actions -->
