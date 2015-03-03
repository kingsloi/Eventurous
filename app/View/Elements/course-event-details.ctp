<a class="list-group-item event-details <?php echo $eventHTMLClasses; ?>" data-course="<?php echo $courseID;?>" data-event="<?php echo $eventID;?>" href="<?php echo $eventBookURL; ?>">
	<h4 class="list-group-item-heading event-<?php echo $eventID;?>">
		<span class="event-name"><?php echo $event['details']['name'];?></span>
		<?php if($eventFull){?>
			<span class="label-<?php echo $eventUnavailableClass; ?>">Full</span>
		<?php } ?>
		<?php if($closedForBookings){?>
			<span class="label-<?php echo $eventUnavailableClass; ?>">Closed</span>
		<?php } ?>
	</h4>
	<div class="list-group-item-text clearfix">
		<?php if($hideDetails == false):?>
			<p class="start col-1-2">
				<span class="heading">Start:</span>
				<?php echo $this->App->formatDatesPretty($event['details']['event_start'], true, $event['details']['all_day_event']);?>
			</p>
			<p class="finish col-1-2">
				<span class="heading">Finish:</span>
				<?php echo $this->App->formatDatesPretty($event['details']['event_finish'], true, $event['details']['all_day_event']);?>
			</p>
			<p class="location col-1-2">
				<span class="heading">Location:</span>
				<?php echo $event['details']['location'];?>
			</p>
		<?php endif; ?>
		<p class="limit col-1-2 <?php if($eventFull){ echo $eventUnavailableClass;}?>">
			<?php if($availableEventBookings !== false){?>
				<span class="heading ">Limit:</span>
				<?php echo $event['bookingCounters']['currentBookings'];?> / 
				<?php echo $event['bookingCounters']['limit'];?>
			<?php }?>
		</p>
		<?php if($event['details']['desc']){?>
		<p class="description clear-left">
			<span class="heading">Description:</span>
			<?php echo $event['details']['desc'];?>
		</p>
		<?php } ?>
	</div>
</a>