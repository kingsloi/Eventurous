<?php

	//set variables
	$courseName     	= $data['BookingCourseDetails']['name'];
	$oldEventName 	    = $data['change']['old']['eventName'];
	$NewEventName 	    = $data['change']['new']['eventName'];

	//only set location/start/finish if set
	//else don't show
	$oldEventLocation	= (isset($data['change']['old']['eventLocation']) ? $data['change']['old']['eventLocation'] : '');
	$oldEventStart		= (isset($data['change']['old']['eventStart']) ? $this->App->formatDatesPretty($data['change']['old']['eventStart']) : '');
	$oldEventFinish		= (isset($data['change']['old']['eventStart']) ? $this->App->formatDatesPretty($data['change']['old']['eventStart']) : '');

	$newEventLocation	= (isset($data['change']['new']['eventLocation']) ? $data['change']['new']['eventLocation'] : '');
	$newEventStart		= (isset($data['change']['new']['eventStart']) ? $this->App->formatDatesPretty($data['change']['new']['eventStart']) : '');
	$newEventFinish		= (isset($data['change']['new']['eventStart']) ? $this->App->formatDatesPretty($data['change']['new']['eventStart']) : '');	

?>

<table class="five columns centered-text" style="background-color: #F2DEDE; border-color: #EED3D7; color:#B94A48;">
	<tr>
		<td class="panel">
			<h6 class="details-block-heading">Old Booking Details</h6>
			<table>
				<tr>
					<td>
						<ul class="details-block">
							<li class="details-block-heading">Course:</li>
							<li class="details-block-data">
								<?php echo $courseName; ?>
							</li>
							<li class="details-block-heading">Event:</li>
							<li class="details-block-data">
								<?php echo $oldEventName; ?>
							</li>
							<?php if(!empty($oldEventLocation)){?>
								<li class="details-block-heading">Location:</li>
								<li class="details-block-data">
									<?php echo $oldEventLocation; ?>
							</li>
							<?php }?>
							<?php if(!empty($oldEventStart)){?>
							<li class="details-block-heading">Start:</li>
							<li class="details-block-data">
								<?php echo $oldEventStart; ?>
							</li>
							<?php }?>
							<?php if(!empty($oldEventFinish)){?>
							<li class="details-block-heading">Finish:</li>
							<li class="details-block-data">
								<?php echo $oldEventFinish; ?>
							</li>
							<?php }?>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table class="five columns centered-text" style="background-color: #DFF0D8; border-color: #D6E9C6; color: #468847;">
	<tr>
		<td class="panel" style="border-top-width:0px;">
			<h6 class="details-block-heading">New Booking Details</h6>
			<table>
				<tr>
					<td>
						<ul class="details-block">
							<li class="details-block-heading">Course:</li>
							<li class="details-block-data">
								<?php echo $courseName; ?>
							</li>
							<li class="details-block-heading">Event:</li>
							<li class="details-block-data">
								<?php echo $NewEventName; ?>
							</li>
							<?php if(!empty($newEventLocation)){?>
								<li class="details-block-heading">Location:</li>
								<li class="details-block-data">
									<?php echo $newEventLocation; ?>
							</li>
							<?php }?>
							<?php if(!empty($newEventStart)){?>
							<li class="details-block-heading">Start:</li>
							<li class="details-block-data">
								<?php echo $newEventStart; ?>
							</li>
							<?php }?>
							<?php if(!empty($newEventFinish)){?>
							<li class="details-block-heading">Finish:</li>
							<li class="details-block-data">
								<?php echo $newEventFinish; ?>
							</li>
							<?php }?>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>