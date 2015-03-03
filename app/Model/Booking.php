<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');


class Booking extends AppModel {

	public $displayField 	= 'id';
	public $name 			= 'Booking';
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'event_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'booking_reason_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter a valid reason for request',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'booking_status_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'booked_by' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $belongsTo = array(
		'Profile' => array( //User
			'className' => 'Profile', //User
			'foreignKey' => 'profile_id', //user_id
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true

		),
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		),
		'BookingReason' => array(
			'className' => 'BookingReason',
			'foreignKey' => 'booking_reason_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BookingStatus' => array(
			'className' => 'BookingStatus',
			'foreignKey' => 'booking_status_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/*
//------------------------------------------------------------------------------------------------------------
// MODEL FUNCTIONS
//------------------------------------
*/

	public function afterSave($created){
		

		//if new booking
		//do magic
		if($created){
			$this->processAddBooking($this->data);
		}
		
	}

	//----------------
	//	BEFORE BOOKING SAVE - DO MAGIC
	//----------------
	public function beforeSave($options = array()){

		//IF NEW BOOKING
		if (empty($this->id)){
		
		}else{//IF EXISTING BOOKING


			//if beforeSave is called on an admin_ action
			if(isset($this->data['Booking']['bookingEditType']) && $this->data['Booking']['bookingEditType'] == 'admin'){

				//Process Booking
				$bookingsObject = $this->processUpdatedBooking($this->data);

				//check to see if bookingsObject is empty 
				//i.e. if there were changes to the booking
				if($bookingsObject['changeType'] !== 'none'){

					//if we're good to go, send zè email!
					$this->sendEmail($bookingsObject, 'admin-booking-update', 'booking-admin-edit');
					
					//if booking has had its event changed
					//create a NEW booking for the NEW event, changing the OLD booking 
					//for the OLD event to status '12'
					if($bookingsObject['changeType'] == 'event'){
						$this->data['Booking']['booking_status_id'] 	= '12'; //Moved to other Phase/Event
						$this->data['Booking']['event_id'] 				= $bookingsObject['change']['old']['eventID'];
						$this->data['Booking']['related']				= $bookingsObject['change']['new']['booking_id'];
					}

					//finally, process the changes of the new and the old record
					//getting them ready to log
					$this->processUpatedChanges($this->data);
				}
			}else{
				
				//
				$this->processUpatedChanges($this->data);
			}
					 
		}
	}


	

	



	//----------------
	//	PROCESS THE CHANGES BETWEEN THE NEW AND THE OLD RECORD
	//	@DESC - Used to process the changes of a record update
	//----------------
	public function processUpatedChanges($newBookingData){

		//get current booking data
		$oldBookingData			= $this->Find('first', array(
			'conditions' 		=> array(
				'Booking.id' 	=> $newBookingData['Booking']['id']
				)
			)
		);

		//--------------
		//	Compare the changes between the current booking data and the new booking data
		//--------------
		$dataToLog = $this->processChangesToLog('Booking',$oldBookingData, $newBookingData);

		//if no differences between the new and old stuff, then don't log
		//if there are differences, log each UPDATED field
		if(!empty($dataToLog)){
			
			//set necessary HistoryLog variables
			$type 		= 'Booking';
			$typeID 	= $newBookingData['Booking']['id'];
			$actionType	= 'Update';

			//load HistoryLog model
			$HistoryLog = ClassRegistry::init('HistoryLog');

			//log changes
			$HistoryLog->logAction($type, $typeID, $actionType, $dataToLog, $madeBy = null);			
		}

	}





	//----------------
	//	PROCESS NEW BOOKING
	//	@DESC - Used to process if a booking is a new booking
	//----------------
	public function processAddBooking($bookingData = null){

		//unset uncessary arrays
		unset($bookingData['User']);
		unset($bookingData['Profile']);
		unset($bookingData['Store']);
		unset($bookingData['Region']);
		unset($bookingData['Booking']['created']);
		unset($bookingData['Booking']['modified']);
		unset($bookingData['Booking']['ard']);
		unset($bookingData['Booking']['bookingEditType']);

		//set necessary HistoryLog variables
		$type 		= 'Booking';
		$typeID 	= $bookingData['Booking']['id'];
		$actionType	= 'Add';

		//load HistoryLog model
		$HistoryLog = ClassRegistry::init('HistoryLog');

		//log changes
		$HistoryLog->logAction($type, $typeID, $actionType, $bookingData, $madeBy = null);	

	}


	//----------------
	//	PROCESS UPDATED BOOKING
	//	@DESC - Used to process if a booking has been updated
	//----------------
	public function processUpdatedBooking($bookingData = null){
		
		//Get Updated Booking Object
		$bookingID 					= $bookingData['Booking']['id'];
		
		//get relevent booking object
		$preUpdatedBooking			= $this->Find('first', array(
				'conditions' 		=> array(
					'Booking.id' 	=> $bookingID
				)
			)
		);

		//set variables
		$emailMessageObject = array();

		//Get profile id of booking
		$bookingProfileID 						= $preUpdatedBooking['Booking']['profile_id'];
		$bookingBookedByID 						= $preUpdatedBooking['Booking']['booked_by'];
		$bookingStatusID 						= $preUpdatedBooking['Booking']['booking_status_id'];
		$bookingEventID 						= $preUpdatedBooking['Booking']['event_id'];

		//set flags for no change - overwrite when/if necessary
		$noEventChange = $noStatusChange = true;

		//Get Profile object of person who's booked
		$bookingProfileFullObject 				= $this->Profile->findById($bookingProfileID);
		if(!empty($bookingProfileFullObject)){
			
			$bookingProfileObject['fullname']	= $bookingProfileFullObject['Profile']['fullname'];
			$bookingProfileObject['email']		= $bookingProfileFullObject['Profile']['email'];
			$bookingProfileObject['phonenumber']= $bookingProfileFullObject['Profile']['phonenumber'];
			$bookingProfileStoreObject			= $this->Profile->Store->findById($bookingProfileFullObject['Profile']['store_id']);
			$bookingProfileObject['store']		= $bookingProfileStoreObject['Store']['name'];
			
			$emailMessageObject['bookedFor']	= $bookingProfileObject;
			$emailMessageObject['emailOnBehalfOf']	= false;				
		}


		//--------------
		//	Who to send email to
		//--------------
		//if profile ID == booked_by
		//i.e. if person booking themself
		//send email to person being booked	

		/*
			TODO:- 
				1)As/If needed, maybe check whether booking_reason_id is Self Booking, then email that person
				  OR if not self booking i.e. promotion/sucession then email nominator
		*/	

		if($bookingProfileID == $bookingBookedByID){
			
			$emailMessageObject['deliverEmailTo']	 = $bookingProfileObject['email'];

		}elseif($bookingProfileID !== $bookingBookedByID){
			
			//if profile ID !== booked_by
			//i.e. if person is being nominated by another person
			//send email to person who BOOKED
			$bookingByProfileFullObject 				= $this->Profile->findById($bookingBookedByID);
			if(!empty($bookingByProfileFullObject)){
				
				$bookingByProfileObject['fullname']		= $bookingByProfileFullObject['Profile']['fullname'];
				$bookingByProfileObject['email']		= $bookingByProfileFullObject['Profile']['email'];
				$bookingByProfileObject['phonenumber']	= $bookingByProfileFullObject['Profile']['phonenumber'];
				
				$emailMessageObject['bookedBy']			= $bookingByProfileObject;
				$emailMessageObject['deliverEmailTo']	= $bookingByProfileObject['email'];	
				$emailMessageObject['emailOnBehalfOf']	= true;		
			}

		}

		//--------------
		//	compare booking event
		//--------------

		if(isset($bookingData['Booking']['event_id'])){
			//get old booking event
			$oldEventID				= $preUpdatedBooking['Booking']['event_id'];
			//get new booking event
			$newEventID 			= $bookingData['Booking']['event_id'];

			if($oldEventID !== $newEventID){
				$noEventChange = false;
				//send old event and new event to function to do necessary processing
				$emailMessageObject['eventChange'] 					= $this->processEventChange($oldEventID, $newEventID, $preUpdatedBooking);
				$emailMessageObject['changeType']					= 'event';
				$emailMessageObject['change']['old']				= $emailMessageObject['eventChange']['oldEvent'];
				$emailMessageObject['change']['new']				= $emailMessageObject['eventChange']['newEvent'];
				$emailMessageObject['BookingCourseDetails']			= $emailMessageObject['eventChange']['BookingCourseDetails'];
				$emailMessageObject['courseEvent']['eventName']		= $emailMessageObject['eventChange']['newEvent']['eventName'];
				$emailMessageObject['courseEvent']['eventLocation']	= $emailMessageObject['eventChange']['newEvent']['eventLocation'];
				$emailMessageObject['courseEvent']['eventStart']	= $emailMessageObject['eventChange']['newEvent']['eventStart'];
				$emailMessageObject['courseEvent']['eventFinish']	= $emailMessageObject['eventChange']['newEvent']['eventFinish'];
				$emailMessageObject['text'] 						= $emailMessageObject['eventChange']['text'];
				$emailMessageObject['additionalText']				= $emailMessageObject['eventChange']['additionalText'];
				unset($emailMessageObject['eventChange']);
			}			
		}

		//--------------
		//	Compare booking status
		//--------------
		if(isset($bookingData['Booking']['booking_status_id'])){
			//get old and new booking status
			$oldStatusID		= $preUpdatedBooking['Booking']['booking_status_id'];
			$newStatusID 		= $bookingData['Booking']['booking_status_id'];

			//If old booking status is different from new booking status id
			if($oldStatusID !== $newStatusID){
				$noStatusChange = false;
				//send old status and new status to function to do necessary processing
				$emailMessageObject['statusChange'] 				= $this->processBookingStatusChange($oldStatusID, $newStatusID, $preUpdatedBooking);				
				$emailMessageObject['changeType']					= 'status';
				$emailMessageObject['change']['oldID'] 				= $emailMessageObject['statusChange']['oldID'];
				$emailMessageObject['change']['old'] 				= $emailMessageObject['statusChange']['oldStatus'];
				$emailMessageObject['change']['newID'] 				= $emailMessageObject['statusChange']['newID'];
				$emailMessageObject['change']['new'] 				= $emailMessageObject['statusChange']['newStatus'];
				$emailMessageObject['BookingCourseDetails']			= $emailMessageObject['statusChange']['BookingCourseDetails'];
				$emailMessageObject['courseEvent']['eventName']		= $emailMessageObject['statusChange']['event'];
				$emailMessageObject['courseEvent']['eventLocation']	= $emailMessageObject['statusChange']['eventLocation'];
				$emailMessageObject['courseEvent']['eventStart']	= $emailMessageObject['statusChange']['eventStart'];
				$emailMessageObject['courseEvent']['eventFinish']	= $emailMessageObject['statusChange']['eventFinish'];
				$emailMessageObject['text'] 						= $emailMessageObject['statusChange']['text'];
				$emailMessageObject['additionalText']				= $emailMessageObject['statusChange']['additionalText'];

				unset($emailMessageObject['statusChange']);
			}
		}

		if($noEventChange == true && $noStatusChange == true){
			$emailMessageObject['changeType']		= 'none';
		}

		
		//if course hides details from user i.e. MDP Level 1
		//unset/set necessary variables so they aren't passed to the email
		if(isset($emailMessageObject['BookingCourseDetails']) && $emailMessageObject['BookingCourseDetails']['hide_details_from_user'] == true){

			if($emailMessageObject['changeType'] == 'status'){
				
				//don't show location/dates/times
				unset($emailMessageObject['courseEvent']['eventLocation']);
				unset($emailMessageObject['courseEvent']['eventStart']);
				unset($emailMessageObject['courseEvent']['eventFinish']);
				
				//we're *assuming* that the user doesn't want to know if they've failed w/e
				//so, we're making our own "nice" status on the fly to make it nicer
				$emailMessageObject['text']				= "There have been changes to the following delegates ".$emailMessageObject['courseEvent']['eventName']." booking.";	
				$emailMessageObject['additionalText']	= "The status of the booking has been changed to: ";
				$emailMessageObject['additionalText'] 	.= $this->niceifyBookingStatus($emailMessageObject['change']['newID']);
			
			}elseif($emailMessageObject['changeType'] == 'event'){
				
				//don't show location/dates/times
				unset($emailMessageObject['change']['old']['eventLocation']);
				unset($emailMessageObject['change']['old']['eventStart']);
				unset($emailMessageObject['change']['old']['eventFinish']);
				unset($emailMessageObject['change']['new']['eventLocation']);
				unset($emailMessageObject['change']['new']['eventStart']);
				unset($emailMessageObject['change']['new']['eventFinish']);
			
				$emailMessageObject['text']				= "The delegates ".$emailMessageObject['change']['old']['eventName']." booking has been cancelled and moved to another event. A new booking has been made for the delegate to attend " . $emailMessageObject['change']['new']['eventName']. " instead.";	
				$emailMessageObject['additionalText']	= "Additional information/actions may be required by the delegate. Please advise the delegate to login to their account and complete any other additional actions which may be required for the new booking inorder to ensure their place on the course/event.";

			}

		}

		return $emailMessageObject;

		
	}

	//----------------
	//	PROCESS BOOKING EVENT CHANGE
	//	@DESC - Used to process the booking if the event has been changed i.e. PHASE 1 -> PHASE 2
	//----------------
	public function processEventChange($oldEventID, $newEventID, $preUpdatedBooking){

		//get booking event ID
		$preUpdatedBookingEventID				= $preUpdatedBooking['Booking']['event_id'];

		//Get old event information
		$oldBookingEvent						= $this->Event->find(
														'first', array(
															'conditions'=>array(
																'Event.id'=>$oldEventID
															)
														)
													);

		//get course information
		$BookingCourseObj 		= ClassRegistry::init('BookingCourse');
		$bookingCourse			= $BookingCourseObj->findById($oldBookingEvent['Event']['booking_course_id']);

		$message['oldEvent']['booking_id']		= $preUpdatedBooking['Booking']['id'];
		$message['oldEvent']['eventID']			= $oldBookingEvent['Event']['id'];
		$message['oldEvent']['eventName']		= $oldBookingEvent['Event']['name'];
		$message['oldEvent']['eventLocation']	= $oldBookingEvent['Event']['location'];
		$message['oldEvent']['eventStart']		= $oldBookingEvent['Event']['event_start'];
		$message['oldEvent']['eventFinish']		= $oldBookingEvent['Event']['event_finish'];


		//Get old event information
		$newBookingEvent						= $this->Event->find(
														'first', array(
															'conditions'=>array(
																'Event.id'=>$newEventID
															)
														)
													);
		$message['newEvent']['eventID']			= $newBookingEvent['Event']['id'];
		$message['newEvent']['eventName']		= $newBookingEvent['Event']['name'];
		$message['newEvent']['eventLocation']	= $newBookingEvent['Event']['location'];
		$message['newEvent']['eventStart']		= $newBookingEvent['Event']['event_start'];
		$message['newEvent']['eventFinish']		= $newBookingEvent['Event']['event_finish'];

		$message['text'] 			= "Your booking to ".$message['oldEvent']['eventName']." has been cancelled and moved to ".$newBookingEvent['Event']['name'].".";
		$message['additionalText'] 	= "Please login to your account to confirm your attendance to ".$newBookingEvent['Event']['name'].".";

		//------------
		// When changing event, create a new booking for new event, marking old event booking as 'moved'
		//------------
		//copy old booking to new booking
		$postUpdatedBooking								= $preUpdatedBooking;

		//because it's a new booking, remove booking ID, created, and modified fields 
		unset($postUpdatedBooking['Booking']['id']);
		unset($postUpdatedBooking['Booking']['created']);
		unset($postUpdatedBooking['Booking']['modified']);

		//replace old event ID with new event ID
		$postUpdatedBooking['Booking']['event_id'] 		= $newEventID;

		//update new booking status ID with a revelent ID *change*
		$postUpdatedBooking['Booking']['booking_status_id'] = '2';	

		//attempt to save new booking
		$newBooking = new Booking();
		$newBooking->create();
		$newBooking->save($postUpdatedBooking);
		

		$newBookingID = $newBooking->id;
		$message['newEvent']['booking_id'] 				= $newBookingID;
		$message['BookingCourseDetails']				= $bookingCourse['BookingCourse'];

		return $message;
	}

	//----------------
	//	PROCESS BOOKING STATUS CHANGE
	//	@DESC - Used to process the booking if the status has been changed i.e. invite sent -> invite accepted
	//----------------
	public function processBookingStatusChange($oldStatusID, $newStatusID, $preUpdatedBooking){

		//get booking event ID
		$preUpdatedBookingEventID	= $preUpdatedBooking['Booking']['event_id'];

		//GET BOOKING EVENT INFORMATION 
		$bookingEvent				= $this->Event->find(
											'first', array(
												'conditions'=>array(
													'Event.id'=>$preUpdatedBookingEventID
												)
											)
										);


		//get course information
		$BookingCourseObj 		= ClassRegistry::init('BookingCourse');
		$bookingCourse			= $BookingCourseObj->findById($bookingEvent['Event']['booking_course_id']);

		//GET LIST OF NAMES OF BOOKING STATUSES
		$bookingStatusesList 		= $this->BookingStatus->find('list');

		//GET OLD STATUS AND NEW STATUS NAME
		$oldBookingStatusName 		= $bookingStatusesList[$oldStatusID];
		$newBookingStatusName		= $bookingStatusesList[$newStatusID];

		$eventName					= $bookingEvent['Event']['name'];
		/*
			STATUSES:
			1.	Unconfirmed
			2.	Invite Sent
			3.	Delegate Cancelled
			4.	Date Changed
			5.	Rejected
			6.	Incomplete Application
			7.	Completed
			8.	Invite Accepted
			9.	Currently On Programme
			10.	No Show
			11.	Left The Programme
			12.	Moved To Next Phase
			13.	Passed
			14.	Failed
			15. ARD Refused
		*/
		$message['text'] 					= "Your booking to [[eventName]] has been changed from [[oldBookingStatusName]] to [[newBookingStatusName]]";
		switch($newStatusID){
			case '1':
				$message['additionalText'] 	= "Your booking for $eventName has been submitted. Please note that all bookings are subject to review. Correspondence by email will follow once reviewed.";
				break;
			case '2':
				$message['additionalText'] 	= "Your booking for [[eventName]] has been accepted.";
				$message['btnAction']		= "Please confirm your attendance.";
				break;
			case '3':
				$message['additionalText'] 	= "You have cancelled your booking to [[eventName]].";
				break; 
			case '4':
				$message['additionalText'] 	= "You booking for [[eventName]] has been cancelled and moved to another event. Futher correspondence will follow.";
				$message['btnAction']		= "Please login to your account to review the bookings.";
				break; 
			case '5':
				$message['additionalText'] 	= "Unfortunately your booking for [[eventName]] has been rejected. Please contact the course leader for more information.";
				$message['btnAction']		= "Login to your account for more information.";
				break; 
			case '6':
				$message['additionalText'] 	= "Your booking is incomplete. Please login and complete your booking for [[eventName]], making sure it contains the necessary information.";
				$message['btnAction']		= "Please login to your account to add the necessary information.";
				break; 
			case '7':
				$message['additionalText'] 	= "You have successfully completed the [[eventName]].";
				break; 
			case '8':
				$message['additionalText'] 	= "You have accepted the invitation to attend [[eventName]].";
				break; 
			case '9':
				$message['additionalText'] 	= "Your booking for [[eventName]] has been confirmed by the course leader and you are now registered to attend [[eventName]].";
				break; 
			case '10':
				$message['additionalText'] 	= "You failed to show for [[eventName]], this has been noted and passed to the course leader.";
				break; 
			case '11':
				$message['additionalText'] 	= "You have left [[eventName]]. Your booking has been cancelled.";
				break; 
			case '12':
				$message['additionalText'] 	= "Your booking for [[eventName]] has been cancelled and has been moved to another event. Correspondence will follow.";
				$message['btnAction']		= "Please login to your account to review the bookings.";				
				break; 
			case '13':
				$message['additionalText'] 	= "Congratulations. You have passed [[eventName]].";
				break; 
			case '14':
				$message['additionalText'] 	= "Unfortunately you have failed [[eventName]]. Please contact the course leader for more information.";
				break;
			case '15':
				$message['additionalText'] 	= "Unfortunately your booking to [[eventName]] has been refused by your ARD. Please contact your ARD for more information.";
				break;
			case '16':
				$message['additionalText'] 	= "Your booking to [[eventName]] has been received. Further correspondence will follow by email.";
				break;
			case '17':
				$message['additionalText'] 	= "Unfortunately your booking to [[eventName]] has been cancelled by your HRA. Please contact your HRA for more information.";
				break; 
			case '18':
				$message['additionalText'] 	= "Unfortunately your booking to [[eventName]] has been cancelled by your ARD. Please contact your ARD for more information.";
				break;   
		}

		//replace placeholder text in array format
		$txtToChange 	= array(
			'[[eventName]]',
			'[[oldBookingStatusName]]',
			'[[newBookingStatusName]]'
		);
		$txtToReplace 	= array(
			$eventName, 
			$oldBookingStatusName, 
			$newBookingStatusName
		); 

		$message['additionalText'] 	= str_replace($txtToChange, $txtToReplace, $message['additionalText']);
		$message['text'] 			= str_replace($txtToChange, $txtToReplace, $message['text']);

		//assign necessary stuff
		$message['BookingCourseDetails']	= $bookingCourse['BookingCourse'];
		$message['event']					= $eventName;
		$message['oldID']					= $oldStatusID;
		$message['oldStatus']				= $oldBookingStatusName;
		$message['newID']					= $newStatusID;
		$message['newStatus']				= $newBookingStatusName;

		$message['eventLocation']			= $bookingEvent['Event']['location'];
		$message['eventStart']				= $bookingEvent['Event']['event_start'];
		$message['eventFinish']				= $bookingEvent['Event']['event_finish'];

		return $message;		
	}










	//----------------
	//	Get ALL Bookings based for a particular event and status (optional)
	//	Only showing latest versions of bookings (again, optional) (i.e. not related)
	//----------------
	public function getAllEventBookings($eventID = null, $statusID = null, $showOnlyLatestsVersionBookings = null){
		$this->bindModel(array(
		    'belongsTo' => array(
		        'BookingCourse' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingCourse.id = Event.booking_course_id')
		        ),
		        'Event' => array(
		            'foreignKey' => false,
		            'conditions' => array('Event.id = Booking.event_id')
		        ),
		        'Profile' => array(
		            'foreignKey' => false,
		            'conditions' => array('Profile.id = Booking.profile_id')
		        ),
		        'BookingReason' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingReason.id = Booking.booking_reason_id')
		        )
		    )
		));

		$conditions = array('Booking.booking_status_id <> 6');

		//if statusID is fed to function
		//filter by statusID
		if(!empty($statusID)){
			$conditions = array('Booking.booking_status_id' => $statusID);
		}

		//if eventID is fed to function
		//filter by eventID
		if(!empty($eventID)){
			$conditions = array_merge($conditions, array('Booking.event_id =' => $eventID));
		}

		//if showOnlyLatestsVersionBookings is true
		//only show bookings that booking.related = null (i.e. doesn't have a related booking)
		if($showOnlyLatestsVersionBookings == true){
			$conditions = array_merge($conditions, array('Booking.related =' =>null));
		}

		//get dem bookings
		$allUnconfirmedBookings = $this->find('all', array(
			'contain'=> array('BookingCourse', 'Event','Profile', 'BookingReason'),
			'conditions' => $conditions,
			'order'=>'Booking.created ASC'
		));
		return $allUnconfirmedBookings;		
	}

	//----------------
	// check to see if employee has already attended a course event
	// i.e. if allow_multiple_bookings = true, then ignore, else if it's false (i.e. only 1 course attendace)
	// then redirect them saying they can't attend event because they've already completed course
	//----------------
	public function checkIfEmployeeHasAttendedACourseEvent($profileID, $courseID){
		
		//get course information
		$bookingCourse = $this->Event->BookingCourse->find(
			'first', array(
				'conditions' => array(
					'BookingCourse.id' => $courseID
				)
			)
		);

		//if course doesn't allow multiple bookings
		//get ALL events for that course in 'list' format
		$bookingCourseEvents = $this->Event->getEventsByCourseID($courseID, 'list');

		//if no events, set blank array - just in case
		$courseEventsBookings = array();
		
		//if course has events - just in case
		if(!empty($bookingCourseEvents)){

			//set blank array
			$eventIDsArray 	= array();

			//build makeshift array of eventIDs
			//note to self.. can't get just keys in (12,13,14) but rather (1 => 12,2 => 13,3 => 14)
			foreach($bookingCourseEvents as $id => $event){
				
				//add ids to array
				array_push($eventIDsArray, $id);
			}

			//attempt to find bookings in that course
			$courseEventsBookings = $this->find('all', array(
			    'conditions' => array(
			    	'Booking.profile_id' => $profileID, 
			        'Booking.event_id' 	 => $eventIDsArray
			    )
			));

		}

		//check to see if course allows for multiple bookings
		//if course doesn't allow multiple attendances
		if($bookingCourse['BookingCourse']['allow_multiple_bookings'] == true){

			//allow multiple bookings
			$allowMultipleBookings = true;
		}else{
			
			//dont allow multiple bookings
			$allowMultipleBookings = false;
		}

		//return an array of whether or not to allow_multple_bookings
		//and another array of matched bookings for events in that course
		return array(
			'allowMultipleBookings' => $allowMultipleBookings, 
			'courseEventsBookings'=> $courseEventsBookings
		);
	}


	//----------------
	// Has employee attended course before?
	//----------------
	public function hasEmployeeAttendedACourseEvent($profileID, $courseID){
		
		//get course information
		$bookingCourse = $this->Event->BookingCourse->find(
			'first', array(
				'conditions' => array(
					'BookingCourse.id' => $courseID
				)
			)
		);

		//if course doesn't allow multiple bookings
		//get ALL events for that course in 'list' format
		$bookingCourseEvents = $this->Event->getEventsByCourseID($courseID, 'list');

		//if no events, set blank array - just in case
		$courseEventsBookings = "";
		
		//if course has events - just in case
		if(!empty($bookingCourseEvents)){

			//set blank array
			$eventIDsArray 	= array();

			//build makeshift array of eventIDs
			//note to self.. can't get just keys in (12,13,14) but rather (1 => 12,2 => 13,3 => 14)
			foreach($bookingCourseEvents as $id => $event){
				
				//add ids to array
				array_push($eventIDsArray, $id);
			}

			//attempt to find bookings in that course
			$courseEventsBookings = $this->find('count', array(
			    'conditions' => array(
			    	'Booking.profile_id' 		=> $profileID, 
			        'Booking.event_id' 	 		=> $eventIDsArray,
			        'Booking.booking_status_id' => array(7,13,14)
			    )
			));

		}

		//return bookings count
		return $courseEventsBookings;
	}





	//----------------
	//	CHECK TO SEE IF EMPLOYEE IS BOOKED ON EVENT
	//----------------
	public function checkIfEmployeeIsOnEvent($profileID, $eventID){

		$isBookedOnEvent = $this->find('first', array(
			'conditions' => array(
				'Booking.profile_id' 	=> $profileID, 
				'Booking.event_id'		=> $eventID)
		));

		/*
		* Check to see what the booking situation is
		* i.e. is booked on event but incomplete or
		* is booked on event but unconformed
			
			$bookingSituation = *
				* 0 = not booked on event
				* 1 = booked on event but incomplete booking
				* 2 = booked on event but not incomplete (another status)
		*/
		$booking = 0;
		if(!empty($isBookedOnEvent)){
			$booking = $isBookedOnEvent['Booking'];			
		}
		return $booking;
	}


	//----------------
	//	COUNT BOOKINGS BY STATUS
	//----------------
	public function countBookingsByStatus($bookingStatusID){
		$bookings = $this->find('count', array(
       		'conditions' => array('Booking.booking_status_id' => $bookingStatusID)
    	));	
    	return $bookings;
	}

	//----------------
	//	COUNT BOOKINGS BY STATUS AND COURSE
	//----------------
	public function countBookingsByStatusAndCourse($statusID, $courseID){
		$bookings = $this->find('count', array(
			'contain'=>array('Event'),
       		'conditions' => array(
       			'Booking.booking_status_id' => $statusID,
       			'Event.booking_course_id' => $courseID
       		)
    	));	
    	return $bookings;
	}

	//----------------
	//	COUNT BOOKINGS BY STATUS AND EVENT
	//----------------
	public function countBookingsByStatusAndEvent($statusID, $eventID){
		$bookings = $this->find('count', array(
       		'conditions' => array(
       			'Booking.booking_status_id' => $statusID,
       			'Booking.event_id' => $eventID
       		)
    	));	
    	return $bookings;
	}

	//----------------
	//	COUNT BOOKINGS BY COURSE
	//----------------
	public function totalBookingsByCourse($courseID){
		$allBookings = $this->find('count', array(
			'contain'=>array('Event'),
       		'conditions' => array(
       			'Event.booking_course_id' => $courseID,
       			'Booking.booking_status_id <>' => '6' //don't show incomplete applications
       		)
    	));	
    	return $allBookings;
	}

	//----------------
	//	COUNT BOOKINGS BY EVENT
	//----------------
	public function totalBookingsByEvent($eventID){
		$allBookings = $this->find('count', array(
       		'conditions' => array(
       			'Booking.event_id' => $eventID,
       			'Booking.booking_status_id <>' => '6' //don't show incomplete applications
       		)
    	));	
    	return $allBookings;
	}



	//----------------
	//	GET LATEST BOOKINGS
	//----------------
	public function getLatestBookings($limit = false){
		$bookings = $this->find('all', array(
       		'limit' => $limit,
       		'order' => array('Booking.created'),
       		'contain' => array(
       			'Event' => array(
       				'BookingCourse'
       			),
       			'Profile'
       		)
    	));	
    	return $bookings;
	}

	//----------------
	//	GET LATEST BOOKINGS BY COURSE
	//----------------
	public function getLatestBookingsByCourse($limit = false, $courseID){

		$this->bindModel(array(
		    'belongsTo' => array(
		        'BookingCourse' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingCourse.id = Event.booking_course_id')
		        ),
		        'Event' => array(
		            'foreignKey' => false,
		            'conditions' => array('Event.id = Booking.event_id')
		        )
		    )
		));
		$bookings = $this->find('all', array(
       		'limit' => $limit,
       		'order' => array('Booking.created'),
       		'conditions'=>array('Event.booking_course_id'=>$courseID),
       		'fields'=> array(
       			'Profile.first_name', 'Profile.surname',
       			'BookingCourse.name',
       			'Event.name'

       		),
       		'contain' => array(
       			'Event',
       			'BookingCourse',
       			'Profile'
       		)
    	));	
    	return $bookings;
	}

	//----------------
	//	GET LATEST BOOKINGS BY EVENT
	//----------------
	public function getLatestBookingsByEvent($limit = false, $eventID){

		$this->bindModel(array(
		    'belongsTo' => array(
		        'User' => array(
		            'foreignKey' => false,
		            'conditions' => array('User.id = Profile.user_id')
		        ),
		        'Profile' => array(
		            'foreignKey' => false,
		            'conditions' => array('Profile.id = Booking.profile_id')
		        )
		    )
		));
		$bookings = $this->find('all', array(
       		'limit' => $limit,
       		'order' => array('Booking.created' => 'ASC'),
       		'conditions'=>array('Booking.event_id'=>$eventID),
       		'fields'=> array(
       			'User.username','Profile.first_name', 'Profile.surname',
       		),
       		'contain' => array(
       			'Profile',
       			'User'
       		)
    	));	
    	return $bookings;
	}


	//ADMIN FUCNTION
	public function getAllInformationForSpecificBooking($bookingID){
		$this->bindModel(array(
		    'belongsTo' => array(
		        'BookingCourse' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingCourse.id = Event.booking_course_id')
		        ),
		        'Event' => array(
		            'foreignKey' => false,
		            'conditions' => array('Event.id = Booking.event_id')
		        ),
		        'Profile' => array(
		            'foreignKey' => false,
		            'conditions' => array('Profile.id = Booking.profile_id')
		        ),
		        'BookingReason' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingReason.id = Booking.booking_reason_id')
		        ),
		        'BookingStatus' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingStatus.id = Booking.booking_status_id')
		        ),
		        'JobTitle' => array(
		            'foreignKey' => false,
		            'conditions' => array('JobTitle.id = Profile.job_title_id')
		        ),
		        'Store' => array(
		            'foreignKey' => false,
		            'conditions' => array('Store.id = Profile.store_id')
		        ),
		        'Region' => array(
		            'foreignKey' => false,
		            'conditions' => array('Region.id = Store.region_id')
		        ),
		        'Note' => array(
		            'foreignKey' => false,
		            'conditions' => array('Note.id = Profile.note_id')
		        )
		    )
		));

		$conditions = array('Booking.id' => $bookingID);

		$booking = $this->find('first', array(
			'contain'=> array(
				'BookingCourse', 'Event','Profile', 
				'BookingReason','BookingStatus', 'JobTitle',
				'Store','Region','Note'),
			'conditions' => $conditions
		));
		return $booking;		
	}


//USER FUNCTION
	public function getAllInformationForSpecificBookingUser($passedConditions, $findType, $showFields, $basicFindOnly = false){



		$conditions 	= $passedConditions;
		if($showFields == false){
			$fields 	= array('Booking.id');
			$contain 	= array();
		}else{
			$this->bindModel(array(
			    'belongsTo' => array(
			        'BookingCourse' => array(
			            'foreignKey' => false,
			            'conditions' => array('BookingCourse.id = Event.booking_course_id')
			        ),
			        'Event' => array(
			            'foreignKey' => false,
			            'conditions' => array('Event.id = Booking.event_id')
			        ),
			        'BookingReason' => array(
			            'foreignKey' => false,
			            'conditions' => array('BookingReason.id = Booking.booking_reason_id')
			        ),
			        'Profile' => array(
			            'foreignKey' => false,
			            'conditions' => array('Profile.id = Booking.profile_id')
			        ),
			        'BookingStatus' => array(
			            'foreignKey' => false,
			            'conditions' => array('BookingStatus.id = Booking.booking_status_id')
			        ),
			        'JobTitle' => array(
			            'foreignKey' => false,
			            'conditions' => array('JobTitle.id = Profile.job_title_id')
			        ),
			        'Store' => array(
			            'foreignKey' => false,
			            'conditions' => array('Store.id = Profile.store_id')
			        ),
			        'Region' => array(
			            'foreignKey' => false,
			            'conditions' => array('Region.id = Store.region_id')
			        ),
			        'User'=> array(
			            'foreignKey' => false,
			            'conditions' => array('User.id = Profile.user_id')
			        )
			    )
			));

			$fields 	= array(
								'Booking.id',
								'Booking.modified',
								'BookingCourse.name',
								'BookingCourse.hide_details_from_user',
								'BookingStatus.name',
								'BookingReason.name',
								'Event.name',
								'Event.event_start',
								'Event.event_finish',
								'Event.location',
								'User.username',
								'Profile.first_name',
								'Profile.surname',
								'JobTitle.title',
								'Store.name',
								'Region.name'
						);

			$contain 	= array(
				'BookingCourse', 'Event','Profile',
				'BookingReason','BookingStatus', 'JobTitle',
				'Store','Region','User');
		}


		if($basicFindOnly == true){
			$fields 	= array(
							'Booking.id',
							'Event.name',
							'Event.event_start',
							'Event.event_finish',
							'Event.location'
						);
			$contain 	= array(
							'Event'
						);			
		}




		$booking = $this->find($findType, array(
			'contain'		=> $contain,
			'conditions' 	=> $conditions,
			'fields'		=> $fields,
			'order'			=> array('Event.event_start ASC')
		));
		return $booking;		
	}





	//----------------
	//	DOWNLOAD BOOKINGS
	//----------------
	public function downloadBookings($courseID = null, $eventID = null){

		ini_set('max_execution_time', 300);
		ini_set("memory_limit","128M");

	//build booking edit URL
	$appURL 			= Router::url('/', true); //http://mdp.dev/
	$bookingEditSlug 	= 'booking/edit/';
	$bookingEditURL 	= $appURL . $bookingEditSlug; //http://mdp.dev/booking/edit/
	$criteria			= array();
	
	if(!empty($courseID)){
		$criteria = array('Event.booking_course_id =' => $courseID);
	}

	if(!empty($eventID)){
		$criteria = array('Event.id =' => $eventID);
	}

		$this->bindModel(array(
		    'belongsTo' => array(
		        'BookingCourse' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingCourse.id = Event.booking_course_id')
		        ),
		        'Event' => array(
		            'foreignKey' => false,
		            'conditions' => array('Event.id = Booking.event_id')
		        ),
		        'Profile' => array(
		            'foreignKey' => false,
		            'conditions' => array('Profile.id = Booking.profile_id')
		        ),
		        'BookingReason' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingReason.id = Booking.booking_reason_id')
		        ),
		        'BookingStatus' => array(
		            'foreignKey' => false,
		            'conditions' => array('BookingStatus.id = Booking.booking_status_id')
		        ),
		        'JobTitle' => array(
		            'foreignKey' => false,
		            'conditions' => array('JobTitle.id = Profile.job_title_id')
		        ),
		        'Store' => array(
		            'foreignKey' => false,
		            'conditions' => array('Store.id = Profile.store_id')
		        ),
		        'Region' => array(
		            'foreignKey' => false,
		            'conditions' => array('Region.id = Store.region_id')
		        ),
		        'Note' => array(
		            'foreignKey' => false,
		            'conditions' => array('Note.id = Profile.note_id')
		        ),
		        'User' => array(
		            'foreignKey' => false,
		            'conditions' => array('User.id = Profile.user_id')
		        )
		    )
		));



		
	//build revelent conditions, with all the necessary 'contain' tables
	$conditions =array(
		'contain' => array(
			'BookingReason',
			'BookingStatus',
			'Event',
			'BookingCourse',
			'Profile',
			'User',
			'Store',
			'Region'
			),
			'conditions' => $criteria
		);


	//fetch data
	$data = $this->find('all', $conditions);

	//load question model 
	$questions = ClassRegistry::init('Questions');
	//get all questions
	$questions = $questions->find('all');
	//get all Users Firstnames
	$allUsersFullNames = $this->Profile->getAllUsersFullNames();
	

	$formattedData = array();

	//loop through each booking, adding necessary rows and columns in correct format
	foreach ($data as $id => $booking):
		$formattedData[$id]['Booking ID'] 	= $booking['Booking']['id'];
		$formattedData[$id]['HRMS'] 		= $booking['User']['username'];
		$formattedData[$id]['Firstname']	= $booking['Profile']['first_name'];
		$formattedData[$id]['Surname'] 		= $booking['Profile']['surname'];
		$formattedData[$id]['Email'] 		= $booking['Profile']['email'];
		$formattedData[$id]['Phone'] 		= $booking['Profile']['phonenumber'];
		$formattedData[$id]['Surname'] 		= $booking['Profile']['surname'];
		$formattedData[$id]['Region'] 		= $booking['Region']['name'];
		$formattedData[$id]['Store'] 		= $booking['Store']['name'];
		$formattedData[$id]['Course'] 		= $booking['BookingCourse']['name'];
		$formattedData[$id]['Event'] 		= $booking['Event']['name'];
		$formattedData[$id]['Event Start'] 	= $booking['Event']['event_start'];
		$formattedData[$id]['Event Location'] = $booking['Event']['location'];
		$formattedData[$id]['Reason'] 		= $booking['BookingReason']['name'];
		$formattedData[$id]['Status'] 		= $booking['BookingStatus']['name'];

		if(array_key_exists($booking['Booking']['booked_by'], $allUsersFullNames)){
			$formattedData[$id]['Booked By'] = $allUsersFullNames[$booking['Booking']['booked_by']];
		}

		$formattedData[$id]['Booking Notes'] = $booking['Booking']['booking_notes'];
		
		$bookingQuestions = "";
		if(isset($booking['Booking']['booking_criteria'])){
			
			$bookingCriteriaJson = json_decode($booking['Booking']['booking_criteria'], true);
			foreach($questions as $questionID => $question){
				$questionID = $question['Questions']['id'];
				if(array_key_exists($questionID, $bookingCriteriaJson)){
					$formattedData[$id]['Q'][$question['Questions']['question']] = $bookingCriteriaJson[$questionID];
				}
				else{
					$formattedData[$id]['Q'][$question['Questions']['question']] = '-';
				}
			}
		}else{
			//$formattedData[$id]['Booking Criteria'] = '';
		}
		$formattedData[$id]['Booking Moved?'] 	= (empty($booking['Booking']['related']) ? 'No' : 'Yes');
		$formattedData[$id]['Old Booking ID'] 	= (empty($booking['Booking']['related']) ? '-' : $booking['Booking']['related']);
		$formattedData[$id]['Date Created'] 	=  date("d-m-Y", strtotime($booking['Booking']['created']));
		$formattedData[$id]['Time Created'] 	=  date("H:i:s", strtotime($booking['Booking']['created']));
		$formattedData[$id]['Edit Booking'] 	= $bookingEditURL . $booking['Booking']['id'];
	endforeach;
	//pr($formattedData);
	return $formattedData;
	}


/*
	TODO!!!!!!!!!!!!!!!!!!!!! MOVE THIS SOMEWHERE NICER
*/
public function niceifyBookingStatus($bookingStatusID){

        //Get various different ID for grouped status (i.e. delegate cancelled, admin cancelled)
        switch($bookingStatusID){
            case 1:
                return 'Awaiting Review from Course Leader';
                break;
            case 2:
                return 'Requires Review from Delegate';
                break;
            case 6:
                return 'Incomplete Application. Please add information.';
                break;
            case 7:
            case 13:
                return 'Complete/Passed';
                break;
            case 8:
            case 16:
                return 'Delegate set to attend course/event';
                break;
            case 9:
                return 'Deletegate currently attending course/event';
                break;
            case 14:
                return 'Please contact course leader';
                break;
            case 3:
            case 10:
            case 11:
                return 'Delegate Cancelled';
                break;
            case 5:
            case 15:
            case 17:
            case 18:
                return 'Course Leader Cancelled/Refused';
                break;
            case 4:
            case 12:
                return 'Date Changed/Booking Moved';
                break;
        }
    }


//EOF	
}