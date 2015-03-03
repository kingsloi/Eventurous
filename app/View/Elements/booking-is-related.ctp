<?php
	$recordType 	= $relatedRecord['relatedRecordType'];
	$recordID		= $relatedRecord['ID'];
	if($relatedRecord['admin'] == true){ $adminLink = '/admin';}else{ $adminLink = '';}
	switch($recordType):
		case 'booking':
			$link 	= "$adminLink/bookings/view/$recordID";
		break;
		case 'event':
			$link 	= "$adminLink/event/view/$recordID";
		break;
	endswitch;
?>
This <?php echo ucwords($recordType)?> is a newer version of an existing <?php echo ucwords($recordType)?>. <a href='<?php echo $link;?>'>See the original</a>.