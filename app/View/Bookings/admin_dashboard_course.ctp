<?php 
	$eventCount 	= 0;
	$tabHeadings 	= "";
	$tabContent 	= "";
	$courseName		= $courseDetails['BookingCourse']['name'];
	$courseID		= $courseDetails['BookingCourse']['id'];
	

	if(!empty($allEvents)){

		//set table heading
		$tabHeadings 	.= "
		<li class='tab-heading'>
			Events
		</li>";

		//loop through each event associated to the course
		//grabbing AND retreiving each individual booking tab 		
		foreach($allEvents as $id => $event):

			//get specific
			$eventID 		= $event['eventID'];
			$eventName 		= $event['name'];

			//generate 'active' tab class
			$activeClass 	= ($eventCount == 0 ? 'active' : '');

			//generate specific TAB HEADING
			$tabHeadings 	.= "
			<li class='$activeClass'>
				<a href='#event$eventID' data-toggle='tab'>$eventName</a>
			</li>";
			

			//generate + built specific TAB BODY
			$tabContent 	.= 	"<div class='tab-pane $activeClass' id='event$eventID'>";
			$tabContent 	.= 	$this->element('admin-event-dashboard', array('eventInfo'=>$bookingEventsInfo[$eventID])
			);
			$tabContent 	.= 	"</div><!-- /event$eventID-->";

			//increment tab count by 1
			$eventCount++;
			
		endforeach;

		//build export Course events TAB HEADING
		$tabHeadings 	.= "
		<li class='tab-heading large-top-margin'>
			Export Course Bookings
		</li>
		<li class='download-course-bookings'>";
			$tabHeadings 	.= $this->Form->postLink(__('<span class="glyphicon glyphicon-cloud-download spaced"></span><span>Export Course Bookings</span>'), array(
	        	'controller' 	=> 'bookings',
				'action' 		=> 'admin_downloadBookingsByCourseID', $courseID), array(
				'escape'		=> false,
				'class' 		=> ''), false

			);
		$tabHeadings 	.= "</li>";
	}
?>

<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'admin-dashboards')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1><?php echo $courseName; ?> Dashboard</h1>
	</div>
	<p>Below is an overview of the course, which is split into seperate events, with information of how many people have requested to be booked on the event, which is then seperated by bookings by each status. Mass edit, and export bookings by course and events using the buttons provided.</p>
	<div id="page-content">
		<div class="tabbed-content tabbable tabs-left ">
		<?php if(!empty($tabHeadings)){?>

			<div class="mobile-headings col-xs-12 visible-xs">&nbsp;</div>
			<ul class="nav nav-tabs tab-heading col-sm-3 col-md-2 hidden-xs" id="admin-course-tabs">
				
				<?php echo $tabHeadings;?>
			</ul>
			
			<div class="tab-content tab-body col-sm-9 col-md-10">
				
				<?php echo $tabContent; ?>
			</div><!-- /.tab-content-->
		</div><!-- /.tabbed-content-->
		
		<?php } else {?>
			
			<div class="col-sm-12">
			<?php echo $this->element(
				'no-results-found', array(
					'text'=>"There are no events for this course. <a href='/admin/events/add/$courseID'>Add an event</a>."
					)
				);?>
			</div>

		<?php } ?>
	</div><!-- /#page-content .col-sm-9 -->
</div><!-- /#page-container .row-fluid -->