<div class="col-md-6">
	<div class="helper">
		<span class="badge alert-info steps">3</span>
		<span class="helper-text">Read the course description.</span>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $courseInfo['BookingCourse']['name'];?></h3>
		</div>
		<div class="panel-body course-details">
			<p class="heading">Type:</p>
			<p class="info"><?php echo $courseInfo['CourseType']['name'];?></p>
			<p class="heading">Description:</p>
			<div class="info">
				<?php echo Markdown($courseInfo['BookingCourse']['desc']); ?>
			</div>
			<p class="heading">Course Contact:</p>
			<p class="info"><?php echo $courseInfo['BookingCourse']['contact_name'];?></p>
			<p class="heading">Contact Email:</p>
			<p class="info"><a href="mailto:<?php echo $courseInfo['BookingCourse']['contact_email'];?>?subject=Question about <?php echo $courseInfo['BookingCourse']['name'];?>"><?php echo $courseInfo['BookingCourse']['contact_email'];?></a></p>
			<p class="heading">Contact Number:</p>
			<p class="info"><a href="tel:<?php echo $courseInfo['BookingCourse']['contact_number'];?>"><?php echo $courseInfo['BookingCourse']['contact_number'];?></a></p>
		</div>
	</div>
	<div class="helper">
		<span class="badge alert-info steps">4</span>
		<span class="helper-text">Read and accept the course criteria</span>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Course Criteria</h3>
		</div>
		<div class="panel-body">
			<?php 
			if(!empty($courseInfo['BookingCourse']['criteria_text'])){
				echo Markdown($courseInfo['BookingCourse']['criteria_text']);	
			}else{
				echo "N/A";
			}

			?>
		</div>
	</div>
</div><!-- /left column-->	

<div class="col-md-6 event-list">

	<?php 
		if(!empty($courseInfo['BookingCourse']['hasAttended']) && $courseInfo['BookingCourse']['hasAttended'] > 0){
			echo $this->element('warning', array('message'=>"You've already attended this course."));
		}
	?>

	<div class="helper">
		<span class="badge alert-info steps">5</span>
		<span class="helper-text">Select which event you would like to attend</span>
	</div>
	<h3 class="h4 no-t-margin">Available Events:</h3>
	<div class="list-group">
	<?php 
		$courseID 				= $courseInfo['BookingCourse']['id'];
		$hideEventDetails 		= $courseInfo['BookingCourse']['hide_details_from_user'];
		$hasAvailableEvents 	= false;	
		
		//seperate available events
		//from full events i.e. events which can't be booked on
		$fullEventsHTML 		= ""; 
		foreach($courseInfo['Events'] as $id => $event){
			$eventID 				= $event['details']['id'];
			$allDayEvent			= $event['details']['all_day_event'];
			$allowBookings 			= $event['details']['allow_bookings'];
			
			//has events flag, used further down
			$hasEvents 				= true;
			$eventFull 				= false;
			$closedForBookings		= false;
			//HTML class for <a> element (to do JS magic)
			$eventHTMLClasses 	 	= "";
			$eventUnavailableClass 	= "";

			//store available bookings (i.e. false if there is no event limit
			//or 0+ if there is a limit & no slots available)
			$availableEventBookings = $event['bookingCounters']['availableBookings'];

			//the default URL
			$eventBookURL			= "/book-on/course/$courseID/event/$eventID";
			
			//If courseType == self-booking
			//Show JS confirm() to ensure they're happy to book onto the event
			if($courseInfo['CourseType']['id'] == 2){
				$eventHTMLClasses 		= 'self-booking-confirm';
			}

			//if there are no available bookings
			//add necessary classes to code elements, and decide whether to echo element or save for later use
			if($availableEventBookings == 0 && $availableEventBookings !== false){
				$eventHTMLClasses 		= $eventHTMLClasses . ' event-full event-unavailable';
				$eventUnavailableClass 	= 'event-unavailable';
				$eventFull 				= true;
				$printType				= 'add-to-string';

			}else{

				$printType				= 'echo';
				$hasAvailableEvents 	= true;	

				if($allowBookings == false){
					$eventHTMLClasses 		= $eventHTMLClasses . ' event-closed event-unavailable';
					$eventUnavailableClass 	= 'event-unavailable';
					$closedForBookings 		= true;
					$printType				= 'add-to-string';
				}


			}

			//copy returned event HTML
			$eventsHTML =  $this->element(
					'course-event-details', array(
						'event'					=> $event, 
						'eventID'				=> $eventID,
						'courseID'				=> $courseID,
						'eventFull'				=> $eventFull,
						'eventHTMLClasses' 		=> $eventHTMLClasses,
						'eventUnavailableClass'	=> $eventUnavailableClass,
						'availableEventBookings'=> $availableEventBookings,
						'eventBookURL'			=> $eventBookURL,
						'hideDetails'			=> $hideEventDetails,
						'allowBookings'			=> $allowBookings,
						'closedForBookings'		=> $closedForBookings
					)
				);

			//if event ISN'T full
			//echo result
			if($printType == 'echo'){
				
				echo $eventsHTML;
			}else{
				//else, if event is full, store html in another variable for use
				//later
				$fullEventsHTML 		.= $eventsHTML;
			}
		} //foreach 
	?>
		<?php if(empty($courseInfo['Events']) || $hasAvailableEvents == false){ 
			$hasEvents = false;
			?>
			<a class="list-group-item" href="javascript:void(0);">
				No available events. Please try again later.
			</a>
		<?php } ?>
		<?php if($courseInfo['BookingCourse']['criteria_text'] && $hasEvents == true){ ?>
			<div class="criteria-overlay">
				<div class="options">
					<h4>Course Criteria</h4>
					<p>I have read and understood and I&hellip;</p>
					<a href="#" class="btn btn-labeled btn-danger col-xs-5 overlay-decline">
						<div class="btn-label">
							<span class="glyphicon glyphicon-thumbs-down"></span>
							<span class="label-text">Decline</span>
						</div>
					</a>
					<a href="#" class="btn btn-labeled btn-success col-xs-5 pull-right overlay-agree">
						<div class="btn-label">
							<span class="glyphicon glyphicon-thumbs-up"></span>
							<span class="label-text">Accept</span>
						</div>
					</a>
				</div>
			</div><!-- criteria-overlay-->
		<?php } ?>
	</div><!-- /event-list list-group-->
	
	<?php if($fullEventsHTML !== ""){?>
		<h3 class="h4">Unavailable Events:</h3>
		<div class="list-group">
			<?php 
				echo $fullEventsHTML;
			?>
		</div><!-- /event-list list-group-->
	<?php } ?>
</div><!-- /right-column-->