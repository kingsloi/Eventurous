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
<b>Warning</b>: This <?php echo ucwords($recordType)?> has been cancelled moved to a newer <?php echo ucwords($recordType)?>. <a href='<?php echo $link;?>'>See the updated <?php echo ucwords($recordType)?></a>.