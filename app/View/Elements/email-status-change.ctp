<?php

	//set variables
	$courseName     = $data['BookingCourseDetails']['name'];
	$eventName 	    = $data['courseEvent']['eventName'];
	
	//only set location/start/finish if set
	//else don't show
	$eventLocation	= (isset($data['courseEvent']['eventLocation']) ? $data['courseEvent']['eventLocation'] : '');
	$eventStart		= (isset($data['courseEvent']['eventStart']) ? $this->App->formatDatesPretty($data['courseEvent']['eventStart']) : '');
	$eventFinish	= (isset($data['courseEvent']['eventFinish']) ? $this->App->formatDatesPretty($data['courseEvent']['eventFinish']) : '');

?>
<table class="five columns centered-text">
	<tr>
		<td class="panel">
			<h6 class="details-block-heading">Booking Details</h6>
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
								<?php echo $eventName; ?>
							</li>
							<?php if(!empty($eventLocation)){?>
								<li class="details-block-heading">Location:</li>
								<li class="details-block-data">
									<?php echo $eventLocation; ?>
							</li>
							<?php }?>
							<?php if(!empty($eventStart)){?>
							<li class="details-block-heading">Start:</li>
							<li class="details-block-data">
								<?php echo $eventStart; ?>
							</li>
							<?php }?>
							<?php if(!empty($eventFinish)){?>
							<li class="details-block-heading">Finish:</li>
							<li class="details-block-data">
								<?php echo $eventFinish; ?>
							</li>
							<?php }?>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>