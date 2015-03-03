<?php
App::uses('AppModel', 'Model');

class Event extends AppModel {

	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'event_datetime' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $belongsTo = array(
		'BookingCourse' => array(
			'className' => 'BookingCourse',
			'foreignKey' => 'booking_course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Booking' => array(
			'className' => 'Booking',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/*
//------------------------------------------------------------------------------------------------------------
// MODEL FUNCTIONS
//------------------------------------
*/
	//----------------
	//	GET COURSE EVENTS
	//----------------
	public function getEventsByCourseID($courseID, $findType = 'all'){

		$allAvailableCourseEvents = $this->find(
			$findType, array(
				'conditions' => array(
					'booking_course_id' => $courseID
				)
			)
		);

		return $allAvailableCourseEvents;
	}

	//----------------
	//	GET (AND COUNT) COURSE EVENTS
	//----------------
	public function getAndCountEventsByCourseID($courseID, $limit = false, $cutOffPeriod = false){
		$cutOffDate 	= date('Y-m-d', strtotime($cutOffPeriod));
		
		$courseEvents 	= $this->find(
			'all', array(
				'conditions'=> array(
					'Event.booking_course_id' 	=> $courseID,
					'Event.event_finish >'	=> $cutOffDate
				),
				'order'=>array('Event.event_start ASC')
			)
		);

		if(!empty($courseEvents)){
			$courseEventsBookings = array();

			foreach($courseEvents as $eventID => $courseEvent){
				$eventBookings = $this->Booking->find(
					'count', array(
						'conditions'=>array(
							'Booking.event_id'=>$courseEvent['Event']['id'],
							//'Booking.booking_status_id'=>1,
						),
						'limit'=>$limit
					)
				);
				$eventBookings = array(
					'name'			=>$courseEvent['Event']['name'],
					'courseID'		=>$courseEvent['Event']['booking_course_id'],
					'eventID'		=>$courseEvent['Event']['id'],
					'eventLimit'	=>$courseEvent['Event']['limit'],
					'allDayEvent'	=>$courseEvent['Event']['all_day_event'],
					'location'		=>$courseEvent['Event']['location'],
					'event_start'	=>$courseEvent['Event']['event_start'],
					'event_finish'	=>$courseEvent['Event']['event_finish'],
					'bookingsTotal'	=>$eventBookings
				);
				
				$courseEventsBookings[] = $eventBookings;
			}
			return $courseEventsBookings;
		}
	}

//EOF
}