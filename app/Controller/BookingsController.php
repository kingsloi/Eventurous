<?php
App::uses('AppController', 'Controller');

class BookingsController extends AppController {

	public $components 	= array('Paginator','Export.Export');


/*------------------------------------------------------------------------------------------------------------
 * checkScoresAgainstThreshold(int, 'type', int)
 * 
 * Use function to check a number(s) against other numbers i.e.
 * checkScoresAgainstThreshold(array(33,33), add, 60) will return false
 * because 66 is greater than threshold
-----------------------------------------------------------------------*/

	public function checkScoresAgainstThreshold($scores, $type, $threshold){
		switch($type){

			case 'add':
				$total = array_sum($scores);
			break;
			case 'average':
				$sum = array_sum($scores);
				$total = $sum / count($scores);
		}
		if($threshold){

			if($total < $threshold){

				return false;
			}else{

				return true;
			}
		}
	}

/*------------------------------------------------------------------------------------------------------------
* 	setBookingProgress(int)
*
*	set the booking progress bar (33%, 66% etc.)
-----------------------------------------------------------------------*/	
	public function setBookingProgress($percentage){
		$this->set('progressPercentage', $percentage);
	}



/*------------------------------------------------------------------------------------------------------------
*	setCourseAndEvent(int int)
*
*	Set Revelent Event + Course Cooking for use in Adding a booking
-----------------------------------------------------------------------*/
	public function setCourseAndEvent($courseID, $eventID){
		$this->render(false);

		$this->loadModel('BookingCourse');
		$this->loadModel('Event');

		$courseDetails 	= $this->BookingCourse->findById($courseID);
		$eventDetails 	= $this->Event->findById($eventID);  
		$courseName 	= $courseDetails['BookingCourse']['name'];
		$courseType		= $courseDetails['BookingCourse']['course_type_id'];
		$eventName 		= $eventDetails['Event']['name'];

		$this->Session->write("eventID", $eventID);
		$this->Session->write("eventName", $eventName);
		$this->Session->write("courseID", $courseID);
		$this->Session->write("courseName", $courseName);
		$this->Session->write("courseType", $courseType);


		//Check course type, if nomination based (i.e. mdp), redirect to nominate-booking page to find hrms etc
		//if Self Booking (i.e. Jeff's workshops), use currently logged in user details

		/*
		* 1= Nomination based
		* 2= Self Booking
		* 3= Automatic Approval
		* ... Add as necessary
		*/

		switch($courseType):
			case 1:
				
				return $this->redirect('/nominate-booking');
			break;
			case 2:
			case 3:

				return $this->redirect(array('action' => 'addSelfBooking'));
			break;

		endswitch;

	}

/*------------------------------------------------------------------------------------------------------------
*	review()	
*
*	Action to allow users to review their OWN bookings
*	either acceptBooking() or rejectBooking() them
-----------------------------------------------------------------------*/
	public function review(){

		//get currently logged in profile ID
		$userProfileID	= $this->Auth->user('Profile.id');
		
		//get bookings for said user
		$bookings 		= $this->Booking->getAllInformationForSpecificBookingUser(
			array(
				'Booking.profile_id' => $userProfileID,
				'Booking.booking_status_id'=>'2',
				'Event.event_finish >' => date('Y-m-d 23:59:59')
			), 
			'all', 
			true
		);
		
        if ($this->request->is('requested')) {

            return $bookings;
        } else {

            $this->set('bookings', $bookings);
        }
	}


/*------------------------------------------------------------------------------------------------------------
*	acceptBooking(int)
*
*	Accept a booking invitation
-----------------------------------------------------------------------*/
	public function acceptBooking($bookingID) {

		//check ownship of booking i.e. person tries to hack form
		$this->checkOwnersnip($bookingID);

		//set variables
		$this->render(false);
		$this->request->onlyAllow('post', 'delete');
		$this->Booking->id = $bookingID;

		//check if booking exists
		if (!$this->Booking->exists()) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//attempt to save booking with status_id == 8 - (invite accepted)
		if($this->Booking->saveField('booking_status_id','8',array('counterCache'=>true))){

			$this->Session->setFlash('Your place has been confirmed.', 'alert-success');
			return $this->redirect($this->referer());
		}else{

			//fail
			$this->Session->setFlash('The booking could not be saved. Please try again.', 'alert-error');
			CakeLog::write('app-errors', 'acceptBooking() error ID='.$bookingID);
		}

	}	

/*------------------------------------------------------------------------------------------------------------
*	rejectBooking(int)
*
*	Reject a booking invitation
-----------------------------------------------------------------------*/
	public function rejectBooking($bookingID) {

		//check ownship of booking - check for naughty people changing booking iD!
		$this->checkOwnersnip($bookingID);

		$this->render(false);
		$this->request->onlyAllow('post', 'delete');
		$this->Booking->id = $bookingID;
		
		//check booking exists
		if (!$this->Booking->exists()) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//attempt to save booking status == 3 (status_id == Delegate Declined)
		if($this->Booking->saveField('booking_status_id','20',array('counterCache'=>true))){

			$this->Session->setFlash('You have rejected your booking.', 'alert-error');
			return $this->redirect($this->referer());
		}else{

			$this->Session->setFlash('The booking could not be saved. Please try again.', 'alert-error');
			CakeLog::write('app-errors', 'rejectBooking() error ID='.$bookingID);
		}

	}	


/*------------------------------------------------------------------------------------------------------------
*	getBookingReasonsByCourseID(int, 'list/all')
*
*	Function will return the revelenet reasons for the course
*	i.e. succession and promotions, or Self Booked
-----------------------------------------------------------------------*/
	public function getBookingReasonsByCourseID($courseID, $type){

		$this->loadModel('BookingReason');

		//get booking reaons
		$bookingReasons = $this->BookingReason->getReasonsByCourseId($courseID, $type);

		return $bookingReasons;		
	}


/*------------------------------------------------------------------------------------------------------------
*	doesBookingCourseHavePossibleQuestions(int)
*	
*	Function will check to see if course has any reasons which have questions attached to them
-----------------------------------------------------------------------*/	
	public function doesBookingCourseHavePossibleQuestions($bookingReasons){

		$this->loadModel('Question');

		//does booking course have questions?
		$doesCourseHasQuestions = $this->Question->doesCourseHavePossibleQuestions($bookingReasons);
		
		return $doesCourseHasQuestions;
	}

/*------------------------------------------------------------------------------------------------------------
*	getBookingReasonQuestions(int)
*	
*	Function will return questions related to a reason
-----------------------------------------------------------------------*/
	public function getBookingReasonQuestions($reasonID){
		$this->loadModel('Question');
		
		//get booking questions
		$bookingQuestions = $this->Question->getBookingReasonQuestions($reasonID);	
		return $bookingQuestions;		
	}


/*------------------------------------------------------------------------------------------------------------
*	noEventOrCourseSelected()
*
*	If no course or event sessions, show message and redirect to course list
-----------------------------------------------------------------------*/
	public function noEventOrCourseSelected(){


		$this->Session->setFlash('Please select a course and event.', 'alert-warning');
		return $this->redirect('/categories');
	}


/*------------------------------------------------------------------------------------------------------------
*	processBookingSituation(array, int)
*	
*	PROCESS EXISTING EVENT BOOKING, BASED ON STATUS ID
-----------------------------------------------------------------------*/
	public function processBookingSituation($profileEventBooking, $courseType){
		/*
		* IDs relate to booking_status_id
			1 = Unconfirmed
			2 = Approved
			3 = Cancelled
			4 = Date Changed
			5 = Rejected
			6 = Incomplete
			7 = Complete
		*/
		$bookingStatusID 			= $profileEventBooking['booking_status_id'];
		$bookingID 					= $profileEventBooking['id'];
		$bookingReasonID 			= $profileEventBooking['booking_reason_id'];

		switch($courseType):
			case 1:
				
				$rediectFail 			= '/nominate-booking';
			break;
			case 2:
			case 3:

				$rediectFail 			= '/categories';
			break;
		endswitch;



		//page to redirect to caputre additional info
		$rediectMoreInfo 			= '/add-additional-info';


		//--------------
		//- SET SPECIFIC MESSAGE/REDIRECT URL BASED ON THE STATUS OF THE EXISTING BOOKING
		//--------------
		switch ($bookingStatusID){

			case '1':
				$selfBookingMsg 		= 'You\'re already booked on that event but your booking has yet to be approved.';
				$nominationBookingMsg	= 'Employee is already booked on that event but their booking has yet to be approved';	
				$redirect = $rediectFail;				
			break;
			case '2':
				$selfBookingMsg 		= 'An Invite has been sent. Please check your email and confirm your place.';
				$nominationBookingMsg	= 'Employee has had an invite sent, but has yet to confirm their place.';
				$redirect 				= $rediectFail;	
			break;
			case '3':
				$selfBookingMsg 		= 'You have cancelled your booking for that event.';
				$nominationBookingMsg	= 'Employee was booked onto that event, but have since cancelled.';
				$redirect 				= $rediectFail;					
			break;
			case '4':
				$selfBookingMsg 		= 'You requested that the date be changed for that booking.';
				$nominationBookingMsg	= 'Employee was booked onto event, but has since had their booking cancelled and changed to another date';
				$redirect 				= $rediectFail;			
			break;
			case '5':
				$selfBookingMsg 		= 'Sorry, but your booking has been reviewed and rejected.';
				$nominationBookingMsg 	= 'Sorry, but the employee has had their booking reviewed and rejected';
				$redirect 				= $rediectFail;					
			break;
			case '6':
				$selfBookingMsg 		= 'You have an incomplete booking for that event. Please add the necessary booking information';
				$nominationBookingMsg	= 'Employee has an incomplete booking for that event. Please add the necessary booking information';
				$this->Session->write('bookingID', $bookingID);
				$this->Session->write('reasonID', $bookingReasonID);
				$redirect 				= $rediectMoreInfo;					
			break;
			case '7':
			case '13':
				$selfBookingMsg 		= 'You have already attended and completed that event.';
				$nominationBookingMsg	= 'Employee has already attended and completed that event.';
				$redirect 				= $rediectFail;	
			break;
			case '8':
				$selfBookingMsg 		= 'You have confirmed your booking and a due to start the course.';
				$nominationBookingMsg	= 'Employee has confirmed the booking and is due to start.';
				$redirect 				= $rediectFail;	
			break;
			case '9':
				$selfBookingMsg 		= 'You are currently enrolled on the event.';
				$nominationBookingMsg	= 'Employee is already enrolled on to the event';
				$redirect 				= $rediectFail;	
			break;
			case '10':
			case '11':
			case '14':
				$selfBookingMsg 		= 'You are no longer on the event and your booking has been cancelled.';
				$nominationBookingMsg	= 'Employee is no longer on the event and their booking has been cancelled.';
				$redirect 				= $rediectFail;	
			break;
			case '12':
				$selfBookingMsg 		= 'Your booking has been cancelled for this event but has been rebooked on to the next available event.';
				$nominationBookingMsg	= 'Employee is no longer on the event but has been rebooked on to the next available event.';
				$redirect 				= $rediectFail;	
			break;
			case '15':
				$selfBookingMsg 		= 'Your ARD has refused your booking. Please contact them for more information';
				$nominationBookingMsg	= 'Employee has had their booking refused by their ARD. Please contact them for more information.';
				$redirect 				= $rediectFail;	
			break;
			case '16':
				$selfBookingMsg 		= 'Your nomination has already been received. Futher correspondence will follow by email.';
				$nominationBookingMsg	= 'Employee\'s nomination has already been received. Futher correspondence will follow by email.';
				$redirect 				= $rediectFail;	
			break;
			case '17':
				$selfBookingMsg 		= 'Your booking has been cancelled by your HRA. Please contact them for more information.';
				$nominationBookingMsg	= 'Employee\'s nomination has been cancelled by their HRA. Please contact them for more information.';
				$redirect 				= $rediectFail;	
			break;
			case '18':
				$selfBookingMsg 		= 'Your booking has been cancelled by your ARD. Please contact them for more information.';
				$nominationBookingMsg	= 'Employee\'s nomination has been cancelled by their ARD. Please contact them for more information.';
				$redirect 				= $rediectFail;	
			break;
			case '18':
				$selfBookingMsg 		= 'Your booking has been cancelled by your ARD. Please contact them for more information.';
				$nominationBookingMsg	= 'Employee\'s nomination has been cancelled by their ARD. Please contact them for more information.';
				$redirect 				= $rediectFail;	
			break;
			case '20':
				$selfBookingMsg 		= 'You declined your invitation. Please contact the course leader.';
				$nominationBookingMsg	= 'Employee declined their invitation. Please contact them for more information.';
				$redirect 				= $rediectFail;	
			break;
		}

	//--------------
	//- SET MESSAGE TEXT, AND REDIRECT AS NECESSARY
	//--------------		
	$warningMessage = ($courseType == 1 ? $nominationBookingMsg : $selfBookingMsg);
	$this->Session->setFlash($warningMessage, 'alert-warning');	
	return $this->redirect($redirect);	
	}



/*------------------------------------------------------------------------------------------------------------
*	processCourseBookingSituation(array, int, int, int, int)
*
*	Process user bookings for an entire course to see if they've attended previously
*	if they have attended previously, check whether course allows multiple event attendance (or just a one time thing)
*	Also check to see if attempting to book on SAME event, if so, process that seperately	
-----------------------------------------------------------------------*/
	public function processCourseBookingSituation($userCourseBookings, $courseType, $courseID, $eventID, $userProfileID){

		//$userCourseBookings 	= array of bookings for said course + whether course allows multiple bookings
		//$courseType 			= type of course i.e. self booking or nomination
		//$courseID & eventID & userProfileID = self explanirotry

		//store whether course allows multple bookings
		$allowMultipleCourseBookings = $userCourseBookings['allowMultipleBookings'];

		//if there are course bookings
		if(!empty($userCourseBookings['courseEventsBookings'])){

			//loop through each course booking
			foreach($userCourseBookings['courseEventsBookings'] as $id => $courseBooking){

				//if event that's being requested has ALREADY been attended by the user/profileID
				if($courseBooking['Booking']['event_id'] == $eventID){

					//process that booking's situation! (i.e. passed/failed/incomplete?)
					$this->processBookingSituation($courseBooking['Booking'], $courseType);
				}
			}

			//check to see whether course 
			//allows multiple event bookings
			if($allowMultipleCourseBookings == false){

				//if course donesn't allow
				//multiple bookings for multple events
				$notAllowedMultipleBookingsText = "Sorry, you've already attended this course and multiple bookings are not permitted. Please contact the course leader for more information.";
				$this->Session->setFlash($notAllowedMultipleBookingsText, 'alert-warning');
				
				//redirect to referer page	
				return $this->redirect($this->referer()); 
			}
		}
	}




/*------------------------------------------------------------------------------------------------------------
*	ADD FUNCTIONS
-----------------------------------------------------------------------*/

	/*------------------------------------------------------------------------------------------------------------
	*	SELF BOOKING()
	*
	*	use for courses where user books themselves rather than nominated
	-----------------------------------------------------------------------*/
	public function addSelfBooking() {

		//SET COURSE ID, NAME, EVENT ID, NAME
		$eventID 		= $this->Session->read("eventID");
		$courseID 		= $this->Session->read("courseID");
		$courseType 	= $this->Session->read("courseType");
		$this->set('courseName', $this->Session->read("courseName"));
		$this->set('eventName', $this->Session->read("eventName"));


		//if no event OR course ID
		//redirect to course list
		if(!$eventID || !$courseID){

			//no course or event selected
			$this->noEventOrCourseSelected(); 
		}

		//get revelent booking reasons
		$bookingReasons = $this->getBookingReasonsByCourseID($courseID, $type = 'list');

		//if Course has more than 1 reason (future proofing incase Self Booking workshops
		//have more than one reason i.e. self booking, or suggestive, etc.)
		if(count($bookingReasons) !== 1){

			//if there's a need, add an element where they can choose 
			//the relevent booking reason from a dropdown box
			die('woops. Sorry, you can\'t do that (yet)');
		}else{

			//get first ID
			$bookingReasonID = key($bookingReasons);
		}

		
		//Get logged in Users Profile		
		$userProfileID	= $this->Auth->user('Profile.id');


		//ok, now we're going to check to see if employee has already completed/attended an event for a particular course
		//we're also to check to see if that course allows multiple attendances. here goes. 
		$userCourseBookings = $this->Booking->checkIfEmployeeHasAttendedACourseEvent($userProfileID, $courseID);

		//if employee has been on courses
		if(!empty($userCourseBookings)){

			//process course situation
			$this->processCourseBookingSituation($userCourseBookings, $courseType, $courseID, $eventID, $userProfileID);
		}


		switch($courseType):
			case 2:
				
				$defaultBookingStatus 	= 1;
				$pageTitle				= 'Self Booking';	
			break;
			case 3:

				$defaultBookingStatus 	= 9;
				$pageTitle				= 'Automatic Approval';	
			break;
		endswitch;





		//Create a new booking - Build Booking array
		$userID 					= $this->Booking->Profile->getProfileIDFromUserID($this->Auth->user('id'));
		$this->Booking->create();
		$bookingInformation = array(
			'Booking' => array(
				'booking_reason_id' => $bookingReasonID,//booking reason (e.g. Self Booked)
				'booking_status_id' => $defaultBookingStatus,				//
				'profile_id'		=> $userProfileID,	//User Profile ID
				'event_id'			=> $eventID,		//Event they want to book on to
				'booked_by'			=> $userID 			//Booked By User ID
			)
		);


		//attempt to save booking
		if ($this->Booking->save($bookingInformation)) {
			
			//if creating a new booking is successful
			//check to see if booking reason has questions
			$bookingQuestions 	= $this->getBookingReasonQuestions($bookingReasonID);

			//get the booking ID of inserted Booking
			$bookingID 			= $this->Booking->id;

			//if has reason has questions
			//redirect to additional booking form to capture responses
			//pass BookingID and reasonID to view
			if(!empty($bookingQuestions)){

				$this->Session->write('bookingID', $bookingID);
				$this->Session->write('reasonID', $bookingReasonID);

				//if has questions
				//updated booking to reflect thats its incomplete
				$incompleteBookingStatus = array(
					'Booking' => array(
						'booking_status_id' => '6' //incomplete
					)
				);
				
				//attempt to save booking to incomplete status
				if($this->Booking->save($incompleteBookingStatus)){

					//if saved, redirect to add-additional-info
					return $this->redirect('/add-additional-info');
				}else{

					//
					$this->Session->setFlash('The booking could not be saved. Please try again.', 'alert-error');
					CakeLog::write('app-errors', 'addSelfBooking($incompleteBookingStatus) error ID='.$bookingID);
				}

			}else{

				//if no more information is requested, booking is complete
				$this->completeBooking();				
			}
		}else{

			//if a problem with saving...
			$this->Session->setFlash('The booking could not be saved. Please try again.', 'alert-error');
			CakeLog::write('app-errors', 'addSelfBooking()');
		}

		//set for layout
		$this->set('title_for_layout', $pageTitle);
	}


	/*------------------------------------------------------------------------------------------------------------
	*	NOMINATE BOOKING()
	*
	*	for courses where people are nominated
	-----------------------------------------------------------------------*/
	public function nominateBooking() {

		//SET COURSE ID, NAME, EVENT ID, NAME
		$eventID 	= $this->Session->read("eventID");
		$courseID 	= $this->Session->read("courseID");
		$courseType = $this->Session->read("courseType");
		$this->set('courseName', $this->Session->read("courseName"));
		$this->set('eventName', $this->Session->read("eventName"));
		
		
		//if no event OR course ID, redirect to course list
		if(!$eventID || !$courseID){ 

			$this->noEventOrCourseSelected(); 
		}

		//if courseType is not nomination, redirect to courses page
		if($courseType !== '1'){

			return $this->redirect('/categories');
		}

		//load relevant booking reasons based on courseID (mdp level 1, mdp level 2, etc)
		$bookingReasons = $this->getBookingReasonsByCourseID($courseID, 'list');
		$this->set(compact('bookingReasons'));

		//check to see if booking course(its reasons) has questions
		$doesCourseHasQuestions = $this->doesBookingCourseHavePossibleQuestions($bookingReasons);

		//if course has possible questions i.e. if a reason assoaciated 
		//to a course has questions attached to it, set booking progress accordingly
		if($doesCourseHasQuestions){

			$this->setBookingProgress('33');
		}else{

			$this->setBookingProgress('50');
		}

		//if POST && Profile ID is NOT EMPTY
		if ($this->request->is('post') && !empty($this->request->data['Profile']['id'])) {

			//Set Revelent post data based on selected user and course and event cookies
			$userID 												= $this->Booking->Profile->getProfileIDFromUserID($this->Auth->user('id'));
			$profileID 												= $this->request->data['Profile']['id'];
			$this->request->data['Booking']['booking_status_id'] 	= '1'; //1 = unconfirmed
			$this->request->data['Booking']['profile_id'] 			= $this->request->data['Profile']['id'];
			$this->request->data['Booking']['event_id'] 			= $eventID;
			$this->request->data['Booking']['booked_by']			= $userID;		
			

			
			//Check to see if reason chosen has assoaciated questions
			$bookingQuestions = array();
			if(isset($this->request->data['Booking']['booking_reason_id'])){

				$reasonID = $this->request->data['Booking']['booking_reason_id'];
				
				//check whether booking type has questions against it
				$bookingQuestions = $this->getBookingReasonQuestions($reasonID);
			}else{

				//if no reason, leave as empty
				$this->request->data['Booking']['booking_reason_id'] = null;
			}


			//if has questions
			//change booking status to reflect thats its incomplete
			if(!empty($bookingQuestions)){

				$this->request->data['Booking']['booking_status_id'] 	= '6'; //6 = incomplete
			}

			//ok, now we're going to check to see if employee has already completed/attended an event for a particular course
			//we're also to check to see if that course allows multiple attendances. here goes. 
			$userCourseBookings = $this->Booking->checkIfEmployeeHasAttendedACourseEvent($profileID, $courseID);

			if(!empty($userCourseBookings)){
				$this->processCourseBookingSituation($userCourseBookings, $courseType, $courseID, $eventID, $profileID);
			}

			//CREATE A NEW BOOKING
			$this->Booking->create();
			$this->request->data['Booking']['bookingEditType'] = 'nominationAdd';
			
			if ($this->Booking->save($this->request->data)) {

				//get last inserted ID from booking (to allow us to add to 
				//the specific booking)
				$bookingID = $this->Booking->id;

				if(!empty($bookingQuestions)){

					/*
					* If more information is requested then redirect to additional info page
					*/

					//set revelent booking and reason id's to pass to next action
					$this->Session->write('bookingID', $bookingID);
					$this->Session->write('reasonID', $reasonID);

					return $this->redirect('/add-additional-info');
					
				}else{

					//if no more information is requested
					$this->Session->delete('bookingID');
					$this->Session->delete('reasonID');
					$this->completeBooking();
				}
			}else{

				//if error saving, preserve POST data
				$this->Session->setFlash('The booking could not be saved. Please try again.', 'alert-error');
				CakeLog::write('app-errors', 'nominateBooking()');
				$this->data = $this->request->data;
			}

		}// end if post
		$this->set('title_for_layout', 'Nominate an employee');
}


/*------------------------------------------------------------------------------------------------------------
*	additionalBookingInfo()
*
*	Addition Booking Information (promotion and succession details) are added(updated) here
-----------------------------------------------------------------------*/
	public function additionalBookingInfo() {

		$bookingID 	= $this->Session->read("bookingID");
		$reasonID  	= $this->Session->read("reasonID");

		//if bookingID or reasonID are empty then redirect to nominate-booking page
		if(!$bookingID || !$reasonID){ 


			return $this->redirect('/nominate-booking');
		}

		//check to see if booking exsits
		if (!$this->Booking->exists($bookingID)) {


			throw new NotFoundException(__('Invalid booking'));
		}

		//set body class
		$this->set('bodyClass','confirm-on-exit');

		//set progress
		$this->setBookingProgress('66');

		//get current information for user who's being nominated
		$this->set('selectedUser', $this->Booking->getAllInformationForSpecificBookingUser(array('Booking.ID' => $bookingID),'first',true)); 

		//load booking reasons (i.e. promotion/succession)
		$this->loadModel('BookingReasons');

		//append booking reasons to a list
		$bookingReasons = $this->BookingReasons->findById($reasonID);	

		//set revelent Booking reason name i.e. promotion / succession etc.
		if(isset($bookingReasons)){

			$bookingTypeName = $bookingReasons['BookingReasons']['name'];
		}
		else{


			$bookingTypeName = '';
		}

		$this->set('bookingTypeName', $bookingTypeName);

		//Load booking reason questions
		$this->loadModel('Question');
		$bookingQuestions = $this->Question->getBookingReasonQuestions($reasonID);

		//if no booking reason questions
		//i.e. if only Self Booking / no questions assisgned to reason
		if(empty($bookingQuestions)){

			//if there are no questions to be asked
			//but have been redirected here, then
			//update the booking, save it, and redirect to completeBooking()
			$confirmBookingInfo = array(
				'Booking' => array(
					'id' => $bookingID,			//booking reason (e.g. Self Booked)
					'booking_status_id' => '1',	//unconfirmed
					'bookingEditType' 	=> 'nominationAdd-Additonal'
				)
			);

			//update the necessary booking to show that it's now completed
			if ($this->Booking->save($confirmBookingInfo)) {

				//complete booking as necessary
				$this->completeBooking();

			}else{

				//if can't save, set message and redirect
				$this->Session->setFlash('The booking could not be saved. Please, try again.', 'alert-error');
				CakeLog::write('app-errors', 'additionalBookingInfo() -line 785-');
				return $this->redirect("/categories");
			}
		}

		//set booking questions
		$this->set('questions', $bookingQuestions); 

		//----------------
		//	on POST from additional booking info
		//----------------
		if ($this->request->is('post') || $this->request->is('put')){

			//if additional info submit button cancel = true
			//i.e. the user has clicked the cancel booking button (and not the continue button)
			//delete the incomplete booking
			if($this->request->data['submitType'] == 'cancel'){

				//Tell Cake which ID to mess with
				$this->Booking->id = $bookingID;

				//attempt to delete dat ID ya get me
				if ($this->Booking->delete()) {

					$this->Session->setFlash('The booking nomination has been withdrawn','error');
				}else{

					$this->Session->setFlash('The booking nomination could not be deleted. Please, try again.','error');
					CakeLog::write('app-errors', 'additionalBookingInfo() -line 812-');
				}

				//return to /categories page
				return $this->redirect("/categories");
			}


			//set the booking ID to manipulate
			$this->request->data['Booking']['id'] = $bookingID;

			//if the ARD approval == Y
			if($this->request->data['Booking']['Approval'] == 'Y'){
				
				$superiorApproval = true;
			}else{
				
				$superiorApproval = false;
			}

			$addionalInfoArray 	= array();
			$questionAnswers 	= array();

			if(isset($this->request->data['Questions'])){

				//loop through each POST'd question
				foreach ($this->request->data['Questions'] as $questionAnswer){

					//add question + answer to array
					$questionAnswers[] = $questionAnswer;
				}
			}

			
			//----------------
			//	QUESTION VALIDATION
			//----------------
			//set variables
			$hasScores 			= false;
			$scores 			= array();
			$questionErrors 	= array();
			$questionError 		= array();

			//Check asnwer against individual question (mustequal, etc.)
			foreach ($questionAnswers as $key => $answer){

				//decode revelent question attributes
				$questionAttributes = json_decode($bookingQuestions[$key]['Question']['attributes'], true);
				
				//if question has validation
				if(!empty($questionAttributes['validation'])){

					//assuming validation is only MUST EQUAL - add necessary logic as and when
					$questionAnswerMustEqual = $questionAttributes['validation'][0]['mustequal'];

					if($questionAnswerMustEqual !== $answer){

						//Set error flag if answer doesn't meet relevent criteria
						$questionError = array(
							'question'	=> $bookingQuestions[$key]['Question']['id'], 
							'type' 		=> 'must equal', 
							'value'		=> $questionAnswerMustEqual);

						$questionErrors[] = $questionError;
					}
				} 
			}

			//----------------
			//	JSON revelent additional info fields
			//----------------			
			
			//if no errors
			if(!$questionErrors){

				$index = 0;

				//if POST data has questions
				if(isset($this->request->data['Questions'])){

					//look through each question, seperate question# and answer
					foreach ($this->request->data['Questions'] as $key => $question):

						//prepare new array to store question key and answer
						$addionalInfoArray[$bookingQuestions[$index]['Question']['id']] = $question;
						
						//increase index for new array (NOT Question KEY!)
						$index++;
					endforeach;		
				}


				//Json endcode additional fields, and redirect to thanks page
				$bookingAdditionalInfo = json_encode($addionalInfoArray);

				//assigned JSON'd data to correct column
				$this->request->data['Booking']['booking_criteria'] 		= $bookingAdditionalInfo;
				
				//set updated 'uncomfirmed' status
				$this->request->data['Booking']['booking_status_id'] 		= '1';
				
				//if booking approval = false (i.e. No)
				if($superiorApproval == false){

					//set ARD Refused status
					$this->request->data['Booking']['booking_status_id'] 	= '15';
				}
				
				//----------------
				//	Attempt to Save fields
				//----------------
				if ($this->Booking->save($this->request->data)) {

					//delete Booking and Reason sessions
					$this->Session->delete('bookingID');
					$this->Session->delete('reasonID');
					
					//booking complete, redirect
					$this->completeBooking();

				}else{

					$this->Session->setFlash('The booking could not be saved. Please, try again.', 'alert-error');
					CakeLog::write('app-errors', 'additionalBookingInfo() booking ID ='.$bookingID);
					
					//preserve POST data
					$this->data = $this->request->data;
				}
			}else{

				//return with errors
				$this->Session->setFlash('Your nomination did not meet the minimum booking requirements. Please reconsider your nomination.', 'alert-error');
				
				//$this->set('errors', $questionErrors);
				$this->set('bodyClass','confirm-on-exit a');
			}
		}

		$this->set('title_for_layout', 'Add Additional Booking Information');
}

/*------------------------------------------------------------------------------------------------------------
*	complete() & CompleteBooking()
*
*	Set 'Complete' view once booking has been completed
-----------------------------------------------------------------------*/
	public function complete() {

		$this->setBookingProgress('100');
		$this->set('title_for_layout', 'Booking Complete');
	}
	public function completeBooking() {

		$this->set('title_for_layout', 'Booking Complete');
		$this->Session->setFlash('Your booking has been submitted. Please note that all bookings are subject to review. Correspondence by email will follow once reviewed. ', 'alert-success');
		return $this->redirect('/booking-complete');
	}		

/*------------------------------------------------------------------------------------------------------------
*	checkOwnersnip(int)
*
*	check ownship of booking to stop people editing other peoples bookings!
-----------------------------------------------------------------------*/
	function checkOwnersnip($bookingID){

		//Get the currently logged in Users Profile ID
		$loggedInProfileID = $this->Auth->User('Profile.id');

		//set conditions to check if that profile id can edit the booking
		$searchCriteria = array(
			'conditions'=>array(
				'Booking.id'		 => $bookingID, 
				'Booking.profile_id' => $loggedInProfileID
			)
		);

		//attempt to find
		$isOwner = $this->Booking->find('count', $searchCriteria);

		//if not owner, redirect with a naughty status
		if($isOwner == 0){

			$this->Session->setFlash('Naughty, naughty! No, you can\'t do that. ', 'alert-error');
			CakeLog::write('app-auth', "checkOwnersnip() - profileID=$loggedInProfileID tried to access bookingID=$bookingID");
			return $this->redirect($this->referer());			
		}
	}


/*------------------------------------------------------------------------------------------------------------
*	view(int)
*
*	view Specific Booking
-----------------------------------------------------------------------*/
	public function view($bookingID = null) {
		
		if (!$this->Booking->exists($bookingID)) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//check that ID actually exists
		$this->checkOwnersnip($bookingID);

		//get all the information related to the booking (statuses, profiles, course, etcs etc.)
		$booking = $this->Booking->getAllInformationForSpecificBooking($bookingID);
		
		//get all revision history
		$this->getAllRecordHistory($booking['Booking']['id'], 'user');

		//only allow the user to cancel 
		//certain statuses
		//i.e. 'currently on programme', 'invite sent', 
		$cancelableStatusIDs				= array(1,2,8,9);
		$currentBookingStatusID 			= $booking['BookingStatus']['id'];
		$booking['BookingStatus']['name'] 	= $this->niceifyBookingStatus($booking['BookingStatus']['id']);

		//search for current status ID against only cancelable status ids
		if (!in_array($currentBookingStatusID, $cancelableStatusIDs)) {
			
			//if it's not found, disable the cancel button
			$this->set('bodyClass','disable-buttons');
		}


		$this->checkIfHasRelated($booking['Booking']);
		//$this->getAllRecordHistory($booking['Booking']['id']);


		//get the name of the person who booked the booking
		$this->Booking->Profile->id = $booking['Booking']['booked_by'];

		$booking['Booking']['booked_by'] = $this->Booking->Profile->field('first_name'). ' '.$this->Booking->Profile->field('surname');
		

		//set variables for use in view
		$this->set('booking',$booking);
	
	}

/*------------------------------------------------------------------------------------------------------------
*	edit(int)
*
*	Edit Specific Booking
-----------------------------------------------------------------------*/

	public function edit($bookingID = null) {
		
		if (!$this->Booking->exists($bookingID)) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//check that ID actually exists
		$this->checkOwnersnip($bookingID);

		//get all the information related to the booking (statuses, profiles, course, etcs etc.)
		$booking = $this->Booking->getAllInformationForSpecificBooking($bookingID);

		//only allow the user to cancel 
		//certain statuses
		//i.e. 'currently on programme', 'invite sent', 
		$cancelableStatusIDs				= array(1,2,8,9);
		$currentBookingStatusID 			= $booking['BookingStatus']['id'];
		$booking['BookingStatus']['name'] 	= $this->niceifyBookingStatus($booking['BookingStatus']['id']);

		//search for current status ID against only cancelable status ids
		if (!in_array($currentBookingStatusID, $cancelableStatusIDs)) {
			
			//if it's not found, disable the cancel button
			$this->set('bodyClass','disable-buttons');
		}


		//get the name of the person who booked the booking
		$this->Booking->Profile->id = $booking['Booking']['booked_by'];

		$booking['Booking']['booked_by'] = $this->Booking->Profile->field('first_name'). ' '.$this->Booking->Profile->field('surname');

		//-------------
		// IF POST
		//-------------

		if ($this->request->is('post')) {
			/* TODO:- 
			*	1) set up necessary variable to work with on save
			*/
			$this->request->data['Booking']['bookingEditType'] = 'user';

			//if cancel = true
			//if($this->request->data['Booking']['cancel'] == true){
				//set the status to 'delegate cancelled'
				$this->request->data['Booking']['booking_status_id'] = 3;
			//}

			//attempt to save
			if ($this->Booking->save($this->request->data)) {

				//if successful save
				$this->Session->setFlash('Booking updated successfully.', 'alert-success');
				return $this->redirect('/bookings/view/'.$bookingID);

			} else {

				//if not a successful save, show an error
				$this->Session->setFlash('<b>Error</b>: Booking could not be saved. Please try again later.', 'alert-error');
				CakeLog::write('app-errors', "booking-edit() - bookingID=$bookingID");
			}

		}		

		//set variables for use in view
		$this->set('booking',$booking);
	}

/*------------------------------------------------------------------------------------------------------------
*	delete()
*
*	Delete specific booking
-----------------------------------------------------------------------*/
	public function delete($bookingID) {
		die('sorry. this action is disabled.');
		CakeLog::write('app-auth', "booking-delete() - bookingID=$bookingID");
	}





/*------------------------------------------------------------------------------------------------------------
*	ADMIN FUNCTIONS
-----------------------------------------------------------------------*/

	/*------------------------------------------------------------------------------------------------------------
	*	massEditForm() 
	*
	*	massedit form
	-----------------------------------------------------------------------*/
	public function admin_massEditForm(){

		//only post
		$this->request->onlyAllow('post');

		if ($this->request->is('post') || $this->request->is('put')) {

			//get IDs
			$eventID 	= $this->request->data['bookings']['eventID'];
			$statusID 	= $this->request->data['bookings']['bookingStatus'];


			//redirect to correct mass-edit page
			if(!empty($statusID) && $statusID !== 'all'){

				return $this->redirect("/admin/bookings/mass-edit/event/$eventID/status/$statusID");
			}else{

				return $this->redirect("/admin/bookings/mass-edit/event/$eventID");
			}
		}
	}

	/*------------------------------------------------------------------------------------------------------------
	*	massBookingsStatusEdit(int, int)	
	*
	*	Mass Approve Bookings either 1 or many bookings at 1 time
	-----------------------------------------------------------------------*/
	public function admin_massBookingsStatusEdit($eventID, $statusID = null) {
		
		//check if Event exists, error if not
		if($eventID){
			if (!$this->Booking->Event->exists($eventID)) {
				
				throw new NotFoundException(__('Invalid Event'));
			}
		}else{
			
			throw new NotFoundException(__('No Event Selected'));
		}

		//get event + course details
		$selectedEvent 		= $this->Booking->Event->find(
			'first', array(
				'fields' => array(
					'Event.name',
					'BookingCourse.id',
					'BookingCourse.name'
				),
				'contain' => array(
					'BookingCourse
					'
				),
				'conditions' => array(
					'Event.id' => $eventID
				)
			)
		);

		//get course ID, send stuff to the view
		$bookingCourseID 	= $selectedEvent['BookingCourse']['id'];
		$this->set('selectedEvent', $selectedEvent);

		//get booking statuses and pass to view
		$bookingStatuses = $this->Booking->BookingStatus->find(
			'list', array(
				'fields'=>array(
					'BookingStatus.id',
					'BookingStatus.action'
					)
			)
		);
		$this->set(compact('bookingStatuses'));

		//-------------
		// IF POST
		//-------------
		if ($this->request->is('post') || $this->request->is('put')) {
			
			//attempt to save ALL of fields *could be improved*
			if ($this->Booking->saveAll($this->request->data['Booking'])) {

				//if successful, present user with message
				$this->Session->setFlash('All bookings updated successfully.', 'alert-success');
							
				//remove POST request data to prevent 
				//deleted dropdown 'selected' being applied 
				//to other fields when deleted 
				$this->request->data['Booking'] = null;

				return $this->redirect("/admin/dashboard/course/$bookingCourseID");

			}else{
				
				//if failed to save, present user error message
				$this->Session->setFlash('The booking could not be saved. Please, try again.', 'alert-error');
				CakeLog::write('app-errors', "booking-admin_massBookingsStatusEdit()");
			}
		}
		
		if(!isset($statusID)){ 

			$statusID = null;
		}
		$allUnconfirmedBookings = $this->Booking->getAllEventBookings($eventID, $statusID, true);

		//-------------
		// ASSIGN BOOKED_BY USER ID TO THE BOOKERS' NAME INSTEAD (i.e. 1 -> KINGSLEY RASPE)
		//-------------
		$allUsersFullNames = $this->Booking->Profile->getAllUsersFullNames();
		
		foreach($allUnconfirmedBookings as $id => $booking){

			if(array_key_exists($booking['Booking']['booked_by'], $allUsersFullNames)){

				$allUnconfirmedBookings[$id]['Booking']['booked_by'] = $allUsersFullNames[$booking['Booking']['booked_by']];
			}
		}

		//send data to view for processing
		$this->set('allUnconfirmedBookings',$allUnconfirmedBookings);
	}

	/*------------------------------------------------------------------------------------------------------------
	*	dashboards()	
	*
	*	dashboard index view page
	-----------------------------------------------------------------------*/
	public function admin_dashboards(){

		$this->loadModel('CourseCategory');
		$courseCategories = $this->CourseCategory->find('all');
		$this->set('categories', $courseCategories);
	}

	/*------------------------------------------------------------------------------------------------------------
	*	dashboard_course(int)	
	*
	*	dashboard index view page
	-----------------------------------------------------------------------*/
	public function admin_dashboard_course($courseID){

		$bookingEventsInfo 	= array();

		//get ALL events
		$allCourseEvents	= $this->Booking->Event->getAndCountEventsByCourseID($courseID, false, '-12 months');
		
		//get all statuses
		$bookingStatuses 	= $this->Booking->BookingStatus->find('list', array(
    		'order' => array('order' => 'ASC')
		));

		//get ALL course details
		$courseDetails 	= $this->Booking->Event->BookingCourse->find(
			'first', array(
				'conditions'=>array(
					'BookingCourse.id' => $courseID
				), 
				'fields' => array(
					'id',
					'name',
					'hide_details_from_user',
					'allow_multiple_bookings'
				)
			)
		);
		$this->set('courseDetails', $courseDetails);

		//-------------
		// if course has events
		//-------------
		if(!empty($allCourseEvents)){

			//loop through each event, building individual dashboard 
			foreach($allCourseEvents as $id => $event){

				$eventID 													= $event['eventID'];
				$bookingsByStatus = array();
				$bookingEventsInfo[$eventID]['Bookings']['eventID'] 		= $eventID;
				$bookingEventsInfo[$eventID]['Bookings']['eventName'] 		= $event['name'];
				$bookingEventsInfo[$eventID]['Bookings']['eventLimit'] 		= $event['eventLimit'];
				$bookingEventsInfo[$eventID]['Bookings']['allDayEvent'] 	= $event['allDayEvent'];
				$bookingEventsInfo[$eventID]['Bookings']['statusIDs']		= $bookingStatuses;
				$bookingEventsInfo[$eventID]['Bookings']['eventLocation'] 	= $event['location'];
				$bookingEventsInfo[$eventID]['Bookings']['eventStart'] 		= $event['event_start'];
				$bookingEventsInfo[$eventID]['Bookings']['eventFinish'] 	= $event['event_finish'];
				

				//Calculate bookings by status
				//1) find all bookings for each course event
				$allEventBookings = $this->Booking->find('all', array(
					'conditions' => array(
						'Booking.event_id' => $eventID
					),
					'fields' => array(
						'Booking.id',
						'Booking.booking_status_id'
					)
				));	

				//loop through each course event
				foreach ($allEventBookings as $bookingID => $eventBooking){

					//add each bookings' status to array 
					array_push($bookingsByStatus, $eventBooking['Booking']['booking_status_id']);	
				}

				//store booking status count for processing/calculate bookings by status
				$bookingsStatusCount = array_count_values($bookingsByStatus);

				//loop through each status
				//caluclate number of bookings with a particular statusID
				foreach ($bookingStatuses as $statusID => $statusName){

					//set/reset tempery count variable
					$temporaryStatusVariableCount = '';

					//if statusID exists in bookingsStatusCount array
					if(array_key_exists($statusID, $bookingsStatusCount)){

						//if it's found
						//add bookingStatus count
						$temporaryStatusVariableCount = $bookingsStatusCount[$statusID];
					}else{

						//if it's not found
						//booking status count = 0 i.e. there's no bookings with that status
						$temporaryStatusVariableCount = 0;
					}

					//set up array that has status name + status count
					$bookingEventsInfo[$eventID]['Bookings']['byStatus'][$statusID] = array(
						'name'	=> $bookingStatuses[$statusID],
						'total'	=> $temporaryStatusVariableCount
					);
				}
				$bookingEventsInfo[$eventID]['Bookings']['total'] 			= count($allEventBookings);
			}
		}

		//set view variables
		$this->set('bookingStatuses', 	$bookingStatuses);
		$this->set('allEvents', 		$allCourseEvents);
		$this->set('bookingEventsInfo', $bookingEventsInfo);

		//Export Bookings Course List
		$coursesList = $this->Booking->Event->BookingCourse->getCoursesList();
		$this->set('courses', $coursesList);
	}


	/*------------------------------------------------------------------------------------------------------------
	*	downloadBookingsByCourseID(int)	
	*
	*	download course bookings
	-----------------------------------------------------------------------*/
	public function admin_downloadBookingsByCourseID($courseID) {

		$this->request->onlyAllow('post');
		if ($this->request->is('post')) {

			$this->admin_downloadBookings($courseID, null);
		}
	}


	/*------------------------------------------------------------------------------------------------------------
	*	downloadBookingsByCourseID(int)	
	*
	*	download event bookings
	-----------------------------------------------------------------------*/
	public function admin_downloadBookingsByEventID() {

		$this->request->onlyAllow('post');
		if ($this->request->is('post')) {

			$this->admin_downloadBookings(null, $this->request->data['bookings']['event_id']);
		}
	}

	/*------------------------------------------------------------------------------------------------------------
	*	downloadBookings(int, int)	
	*
	*	download bookings bookings - by either course or event
	*	@output - csv file
	-----------------------------------------------------------------------*/
	function admin_downloadBookings($courseID = null, $eventID = null){
		$this->layout 		= false;
		$this->autoLayout 	= false;
		$this->autoRender 	= false;
		
		$formattedData = $this->Booking->downloadBookings($courseID, $eventID);

		if(!empty($formattedData)){

			if(!empty($courseID)){

				//if report ran on course - name = course-bookings-date
				$fileName = $formattedData[0]['Course'];
			}else{

				//if report ran on event - name = course-event-bookings-date
				$fileName = $formattedData[0]['Course'];
				$fileName .= ' '.$formattedData[0]['Event'];
			}

			//slugify name (i.e. remove spaces - replace with -)
			$fileName = Inflector::slug($fileName,'-');
			$downloadDateTime = date('d-m-y:H-i');
			
			$this->Export->exportCsv($formattedData, "$fileName-bookings--$downloadDateTime.csv");
		}else{

			//if no bookings
			$this->Session->setFlash('There are no bookings to export that for course/event.', 'alert-error');
			return $this->redirect($this->referer());
		}
		
	}

	/*------------------------------------------------------------------------------------------------------------
	*	checkIfHasRelated($array)
	*
	*	check if booking has a related booking
	*	or is related to another boooking
	-----------------------------------------------------------------------*/
	public function checkIfHasRelated($recordObject){

		//if has related
		//disabl editing/has relate
		if(!empty($recordObject['related'])){

			$this->set('bodyClass','disable-buttons');
			$this->set('hasRelated', true);
		}

		//attempt to find a booking where booking.related = the passed ID
		$isARelatedBooking = $this->Booking->find(
			'first', array(
				'conditions'=>array(
					'Booking.related' => $recordObject['id']
				),
				'fields' => array(
					'Booking.id'
				)
			)
		);

		//if HAS related
		if(!empty($isARelatedBooking)){

			//set found related record to view
			$this->set('isRelated', $isARelatedBooking);
		}


	}


/*------------------------------------------------------------------------------------------------------------
*	getAllRecordHistory(int, booking)
*
*	check if has booking revision history
-----------------------------------------------------------------------*/

	public function getAllRecordHistory($bookingID, $revisionType){

		//load historyLog model
		//retrieve all histories for specific type(Booking) and ID(1)
		$this->loadModel('HistoryLog');
	   
		//paginated approach to getting all history
	    $this->paginate = array(
	        'conditions' => array(
	        	'HistoryLog.type' => 'Booking', 
	        	'HistoryLog.type_id'=>$bookingID
	        ),
	        'limit' => 10,
	        'order' => 'HistoryLog.id DESC'
	    );

	    $recordHistory = $this->paginate('HistoryLog');

		//Get all(list) user names, statuses, reasons and events
		//
		$allUsersFullNames 		= $this->Booking->Profile->getAllUsersFullNames();

		$allBookingStatuses 	= $this->Booking->BookingStatus->find('list');
		$allBookingReasons		= $this->Booking->BookingReason->find('list');
		$allBookingEvents		= $this->Booking->Event->find('list');

		//loop through each record history
		//and perform necessary:
		// 1) made by (id -> name);
		// 2) what fields were changed (i.e. booking_status_id => 1 -> Unconfirmed)
		foreach($recordHistory as $recordID => $record){

			//decode DATA field to expose the db fields that have been changed
			$dataFields = json_decode($recordHistory[$recordID]['HistoryLog']['data'], true);
			$recordHistory[$recordID]['HistoryLog']['data'] 	= $dataFields;

			//change made_by field to a persons Full Name
			if(array_key_exists($record['HistoryLog']['made_by'], $allUsersFullNames)){

				$recordHistory[$recordID]['HistoryLog']['made_by'] = $allUsersFullNames[$record['HistoryLog']['made_by']];
			}


			//---------------
			// Format "event_id" field to show name instead
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['event_id'])){

				if(array_key_exists($recordHistory[$recordID]['HistoryLog']['data']['Booking']['event_id'], $allBookingEvents)){

					$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Event'] = $allBookingEvents[$recordHistory[$recordID]['HistoryLog']['data']['Booking']['event_id']];
				}
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['event_id']);				
			}


			//---------------
			// Format "booked_by" field to show name instead
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booked_by'])){

				if(array_key_exists($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booked_by'], $allUsersFullNames)){

					$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Booked By'] = $allUsersFullNames[$recordHistory[$recordID]['HistoryLog']['data']['Booking']['booked_by']];
				}
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booked_by']);
				
			}


			//---------------
			// Format Related Booking ID
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['related'])){

				$recordHistory[$recordID]['HistoryLog']['data']['Booking']['New Booking'] = $recordHistory[$recordID]['HistoryLog']['data']['Booking']['related'];
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['related']);
			}


			//---------------
			// Format "booking_status_id" field to show name instead
			//---------------

			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_status_id'])){

				if($revisionType == 'user'){

					$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Status'] = $this->niceifyBookingStatus($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_status_id']);
				}else{

					if(array_key_exists($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_status_id'], $allBookingStatuses)){

						$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Status'] = $allBookingStatuses[$recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_status_id']];
					}	
				}
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_status_id']);				
			}


			//---------------
			// Format "booking_reason_id" field to show name instead
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_reason_id'])){

				if(array_key_exists($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_reason_id'], $allBookingReasons)){

					$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Reason'] = $allBookingReasons[$recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_reason_id']];
				}
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_reason_id']);				
			}


			//---------------
			// Format "booking_notes"
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_notes'])){

				$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Booking Notes'] = $recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_notes'];
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_notes']);
			}


			//---------------
			// Format "booking_criteria"
			//---------------
			if(isset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_criteria'])){

				$recordHistory[$recordID]['HistoryLog']['data']['Booking']['Questions'] = "Added questions";
				unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['booking_criteria']);
			}
			//unset unwanted array fields
			unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['profile_id']);
			unset($recordHistory[$recordID]['HistoryLog']['data']['Booking']['id']);
		}

		//if recordHistory not empty
		//send to view
		if(!empty($recordHistory)){

			$this->set(compact('recordHistory'));
		}
	}



/*------------------------------------------------------------------------------------------------------------
*	admin_view(int)
*
*	view specific booking by id
-----------------------------------------------------------------------*/

	public function admin_view($id) {

		//check that booking ID exists
		if (!$this->Booking->exists($id)) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//get all necessary information (store, region, status, reason, notes, etc.)
		$booking = $this->Booking->getAllInformationForSpecificBooking($id);
		
		//check if has related and get all record revisions
		$this->checkIfHasRelated($booking['Booking']);
		$this->getAllRecordHistory($booking['Booking']['id'], 'admin');
		
		//changed booked_by from ID to name 	
		$this->Booking->Profile->id = $booking['Booking']['booked_by'];

		$booking['Booking']['booked_by'] = $this->Booking->Profile->field('first_name'). ' '.$this->Booking->Profile->field('surname');

		//-----------------
		//-	Booking Questions
		//	
		//	find all questions, json_decode booking_criteria, try to find question key from criteria in question array
		// 	if found, i.e. question has been answered, add to questions array
		//-----------------
		if(!empty($booking['Booking']['booking_criteria'])){
			$this->loadModel('Questions');
			$bookingQuestions 		= array();
			$questions 				= $this->Questions->find('all');

			$bookingCriteriaJson = json_decode($booking['Booking']['booking_criteria'], true);

			foreach($questions as $id => $question){

				$questionID = $question['Questions']['id'];
				if(array_key_exists($questionID, $bookingCriteriaJson)){

					array_push($bookingQuestions, array('question'=>$question['Questions']['question'], 'answer'=>$bookingCriteriaJson[$questionID]));
				}
			}
			$booking['Booking']['booking_criteria'] = $bookingQuestions;
		}

		$this->set('booking', $booking);
	}


/*------------------------------------------------------------------------------------------------------------
*	user_bookings($int, future/previous/thismonth/cancelled/outforreview)
*
*	get user bookings for a particular profileID and type
-----------------------------------------------------------------------*/
	function user_bookings($profileID = null, $viewType){

		//if profileID (i.e. most likely an admin?)
		if(!empty($profileID)){

			$userProfile 	= $this->Booking->Profile->getProfileInformation($profileID);
			$pageTitle		= $userProfile['Profile']['first_name'].'\'s Profile';
			$userProfileID	= $userProfile['Profile']['id'];
		}else{

			$userProfileID 	= $this->Auth->user('Profile.id');
			$pageTitle		= 'Your Profile';
		}

		//------------
		//-	VIEW TYPE i.e. PREVIOUS/CANCELLED/etc
		//------------
		switch($viewType){
			case 'future':
				$criteria = array(
								'Booking.profile_id' 		=> $userProfileID,
								'Event.event_finish >'		=> date('Y-m-d 23:59:59'),
								'Booking.booking_status_id'	=> array(8,9)
								//'NOT' => array( "Booking.booking_status_id" => array(1, 2, 3, 4) )
							);
			break;
			case 'thismonth':
				$criteria = array(
								'Booking.profile_id' 		=> $userProfileID,
								'Event.event_finish <'		=> date('Y-t-m 23:59:59'),
								'Booking.booking_status_id'	=> array(8,9)
							);
			break;
			case 'previous':
				$criteria = array(
								'Booking.profile_id' 		=> $userProfileID,
								'OR' => array (
									'Event.event_finish <'		=> date('Y-m-d 23:59:59'),
									'Booking.booking_status_id'	=> array(7,13,14)
								)
							);
			break;
			case 'cancelled':
				$criteria = array(
								'Booking.profile_id' 		=> $userProfileID,
								'Event.event_finish >'		=> date('Y-m-d H:m:s'),
								'Booking.booking_status_id'	=> array(3,4,5,10,11,12,15,17,18,20)
							);
			break;
			case 'outforreview':
				$criteria = array(
								'Booking.profile_id' 		=> $userProfileID,
								'Event.event_finish >'		=> date('Y-m-d H:m:s'),
								'Booking.booking_status_id'	=> array(1,16)
							);
			break;
		}


		//get all bookings with set criteria!
		$userBookings 	= $this->Booking->Profile->Booking->getAllInformationForSpecificBookingUser(
							$criteria,
							'all',
							true
						);

		//Loop through each booking and change status name as necessary
		foreach($userBookings as $id => $booking){

			$userBookings[$id]['BookingStatus']['name'] = $this->niceifyBookingStatus($booking['BookingStatus']['id']);
		}


		return $userBookings;

	}


/*------------------------------------------------------------------------------------------------------------
*	!USER_BOOKINGS VIEW!
*
*	* actions for
		* Cancelled
		* Previous
		* This month
		* Future
		* Review
-----------------------------------------------------------------------*/

	public function cancelled_bookings($profileID = null){
		
		$pageTitle 						= 'Cancelled/Date Changed/Moved Bookings';
		$pageDesc 						= '<p>Cancelled/Date Changed/Moved bookings are shown below. It\'s not possible for you to re-active a cancelled booking. If you would like to re-activate your booking, please contact the course leader.</p>';

		//Get All bookings
		$bookings 						= $this->user_bookings($profileID, 'cancelled');

		$this->set('bookings', $bookings);
		$this->set(compact('pageTitle','pageDesc'));
		$this->render('user_bookings');
	}


	public function previous_bookings($profileID = null){
		
		
		$pageTitle 	= 'Previous Bookings';
		$pageDesc 	= '<p>All previously attended passed/completed bookings are shown below.</p>';

		$bookings = $this->user_bookings($profileID, 'previous');
		$this->set(compact('bookings', 'pageTitle','pageDesc'));
		$this->render('user_bookings');
	}

	public function future_bookings($profileID = null){
		
		$pageTitle 	= 'Future Bookings';		
		$pageDesc 	= '<p>All future course/events are shown below.</p>';


		$bookings = $this->user_bookings($profileID, 'future');
		$this->set(compact('bookings', 'pageTitle','pageDesc'));
		$this->render('user_bookings');
	}

	public function this_month_bookings($profileID = null){
		
		$pageTitle 	= 'Bookings this Month';
		$pageDesc 	= '<p>All bookings for courses/events that you\'re currently set to attend and take place this month are shown below.</p>';

		$bookings = $this->user_bookings($profileID, 'thismonth');
		$this->set(compact('bookings', 'pageTitle','pageDesc'));
		$this->render('user_bookings');
	}

	public function out_for_review_bookings($profileID = null){
		
		$pageTitle 	= 'Bookings That are out for review';
		$pageDesc 	= '<p>All bookings for courses/events that our currently out for review by the course leader are shown below.</p>';

		$bookings = $this->user_bookings($profileID, 'outforreview');
		$this->set(compact('bookings', 'pageTitle','pageDesc'));
		$this->render('user_bookings');
	}


/*------------------------------------------------------------------------------------------------------------
*	admin_edit($id)
*
*	edit Specific Booking
-----------------------------------------------------------------------*/
	public function admin_edit($bookingID) {

		//check if booking exiss, throw error if not
		if (!$this->Booking->exists($bookingID)) {

			throw new NotFoundException(__('Invalid booking'));
		}

		//get all booking information
		$booking = $this->Booking->getAllInformationForSpecificBooking($bookingID);

		//check if booking is related to another i.e. old booking
		$this->checkIfHasRelated($booking['Booking']);

		//get all usernames
		//assign booked by 

		$this->Booking->Profile->id = $booking['Booking']['booked_by'];

		$booking['Booking']['booked_by'] = $this->Booking->Profile->field('first_name'). ' '.$this->Booking->Profile->field('surname');

		if(!empty($booking['Booking']['booking_criteria'])){

			$this->loadModel('Questions');
			$bookingQuestions = array();
			$questions = $this->Questions->find('all');

			$bookingCriteriaJson = json_decode($booking['Booking']['booking_criteria'], true);

			foreach($questions as $id => $question){

				$questionID = $question['Questions']['id'];
				if(array_key_exists($questionID, $bookingCriteriaJson)){

					array_push($bookingQuestions, array('question'=>$question['Questions']['question'], 'answer'=>$bookingCriteriaJson[$questionID]));
				}
			}
			$booking['Booking']['booking_criteria'] = $bookingQuestions;
		}

		if ($this->request->is('post')) {

			$this->request->data['Booking']['bookingEditType'] = 'admin';
			if ($this->Booking->save($this->request->data)) {

				$this->Session->setFlash('Booking updated successfully.', 'alert-success');
				return $this->redirect('/admin/bookings/view/'.$bookingID);
			} else {

				$this->Session->setFlash('<b>Error</b>: Profile could not be saved. Please try again later.', 'alert-error');
			}
		}
		
		$bookingStatuses 	= $this->Booking->BookingStatus->find('list', array('fields'=>array('BookingStatus.id','BookingStatus.action')));
		$events 			= $this->Booking->Event->find('list', array('conditions'=>array('Event.booking_course_id'=>$booking['BookingCourse']['id'])));
		$this->set('booking',$booking);
		$this->set(compact('bookingStatuses', 'events'));
	}




	public function admin_delete($id = null) {

		//hey, don't even think about being naughty
		//we're gonna check that u aint tryna mess a brotha up so we checkin dat
		//only post n delete calls can b made
		//4 real, peace up
		$this->request->onlyAllow('post', 'delete');
		$this->Booking->id = $id;


		//check to see if the booking exists
		if (!$this->Booking->exists()) {

			throw new NotFoundException(__('Invalid booking'));
		}


		//get the event_id of the booking about to be deleted
		//for potential redirect to booking view for that event
		$eventID = $this->Booking->field('event_id');
		
		//attempt to delete booking
		if ($this->Booking->delete()) {

			$this->Session->setFlash("Booking #$id has been deleted.", 'alert-warning');
		} else {

			$this->Session->setFlash('The booking could not be deleted. Please, try again.', 'alert-warning');
		}

		//get the referer
		//check where the delete call was made
		//redirect as necessary
		$referer 					= $this->referer();
		$individualBookingAction   	= 'bookings/view';
		$pos 						= strpos($referer, $individualBookingAction);

		if ($pos === false) {

			//if deleted from another page except an idivual booking
			$returnUrl = $this->referer();
		}
		else {

			//if deleted from an individual booking
			$returnUrl = "/admin/reports/bookings/event/$eventID";
		}

		//redirect as necessary
		return $this->redirect($returnUrl);
	}}
