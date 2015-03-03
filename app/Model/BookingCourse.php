<?php
App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class BookingCourse extends AppModel {

	public $displayField 	= 'name';
	public $name 			= 'BookingCourse';

	public $belongsTo = array(
		'CourseType' => array(
			'className' 	=> 'CourseType',
			'foreignKey' 	=> 'course_type_id',
		),
		'CourseCategory' 	=> array(
			'className' 	=> 'CourseCategory',
			'foreignKey' 	=> 'course_category_id',
		)
	);

	public $hasMany = array(
		'Event' => array(
			'className' 	=> 'Event',
			'foreignKey' 	=> 'booking_course_id',
			'dependent' 	=> false,
		)
	);

	public $hasAndBelongsToMany = array(
		'BookingReason' => array(
			'className' 	=> 'BookingReason',
			'joinTable' 	=> 'booking_courses_booking_reasons',
			'foreignKey' 	=> 'booking_course_id',
			'associationForeignKey' => 'booking_reason_id',
			'unique' 		=> 'keepExisting',
			'conditions' 	=> '',
			'fields' 		=> '',
			'order' 		=> '',
			'limit' 		=> '',
			'offset' 		=> '',
			'finderQuery' 	=> '',
		)
	);

	public function getCoursesList(){
		return $this->find('list');
	}

	public function getAllCategoryCoursesAndEvents($categoryID){

		//get profileID
		$userProfileID 	= CakeSession::read("Auth.User.Profile.id");

		//Get courses in the selected category
		//also fetch coursetype and coursecategory
		$categoryCourses = $this->find('all',
			array(
				'contain'=>array(
					'CourseType',
					'CourseCategory'
				),
				'conditions'=>array(
					'BookingCourse.course_category_id'	=> $categoryID
				)
			)
		);



		//Loop through each course
		//Getting all the events for that course
		foreach ($categoryCourses as $categoryID => $categoryCourse){


			//assign the courseID
			$bookingCourseID = $categoryCourse['BookingCourse']['id'];

			//Get the events of the coruse
			$courseConditions = array(
				'conditions'=>array(
					'Event.booking_course_id' 	=> $bookingCourseID,
					//'Event.allow_bookings'		=> 1,
					'Event.closed'				=> 0,
					'Event.event_finish >' 		=> date('Y-m-d H:i:s')
				)
			);
			$categeryCourseEvents = $this->Event->find('all', $courseConditions);

			$allCourseEvents = array();

			//loop through each event of said course
			//find all the bookings for that event			
			foreach ($categeryCourseEvents as $eventID => $courseEvents){

				//assign the event ID
				$eventID 	= $courseEvents['Event']['id'];

				//get event LIMIT
				$eventLimit = $courseEvents['Event']['limit'];
				
				//Get the bookings of the event
				$eventConditions = array(
					'conditions'	=>array(
						'Booking.event_id'	=> $eventID,
						
						//only count INVITE SENT / INVITE ACCEPTED / CURRENTLY ON / NOMINATION RECEIVED
						//everything is fair game. you get me. fam.
						'Booking.booking_status_id' => array(8,9,16) 
					)
				);

				//count ALL event bookings
				$eventBookingCount 	= $this->Event->Booking->find('count', $eventConditions);
				
				//get all AVAILABLE bookings (limit - current booked)
				//if there's no limit 
				//set false, ya feeeeeeels
				if($eventLimit > 0){
					if($eventBookingCount < $eventLimit){
						$availableEventBookings = $eventLimit - $eventBookingCount;
					}else{
						$availableEventBookings = 0;
					}
					
				}else{
					$availableEventBookings = false;
				}
				
				//store count figures in a seperate array for ease I guess
				$eventBookingCounters = array(
					'limit' 			=> $eventLimit,
					'currentBookings' 	=> $eventBookingCount,
					'availableBookings' => $availableEventBookings
				);

				//build final array (details + count figures)
				$eventDetails 		= array(
					'details'			=> $courseEvents['Event'], 
					'bookingCounters'	=> $eventBookingCounters
				);

				//push eventDetails array to all events array for a particular course
				//i.e. Phase 1 + Phase 2 + Phase 3, etc.
				array_push($allCourseEvents, $eventDetails);
				
			}

			//add course events to categoryCourse array and return 
			//total/full array of all courses+events in said category
			$categoryCourses[$categoryID]['Events'] = $allCourseEvents;
			$categoryCourses[$categoryID]['BookingCourse']['hasAttended'] = $this->Event->Booking->hasEmployeeAttendedACourseEvent($userProfileID,$bookingCourseID);
		}
		return $categoryCourses;	
	}


	//get course name for a given event id
	public function getEventCourseName($eventID){
		$this->bindModel(array(
		    'belongsTo' => array(
		        'Event'	=> array(
		            'foreignKey' => false,
		            'conditions' => array(
		            	'Event.id = BookingCourse.id'
		            )	
		        )
		    )
		));
		$courseNames = $this->Find(
			'list', array(
				'contain'	=> array(
					'Event'
				),
				'conditions'	=> array(
					'Event.id' 	=> $eventID
				)
			)
		);
		return $courseNames[$eventID];
	}

//EOF
}