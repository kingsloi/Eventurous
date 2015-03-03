<?php
App::uses('AppModel', 'Model');

class BookingReason extends AppModel {

	public $displayField 	= 'name';
	public $name 			= 'BookingReason';
	
	public $hasMany = array(
		'Booking' => array(
			'className' => 'Booking',
			'foreignKey' => 'booking_reason_id',
			'dependent' => false
		),
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'booking_reason_id',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'BookingCourse' => array(
			'className' => 'BookingCourse',
			'joinTable' => 'booking_courses_booking_reasons',
			'foreignKey' => 'booking_reason_id',
			'associationForeignKey' => 'booking_course_id',
			'unique' => 'keepExisting'
		)
	);


	public function getReasonsByCourseId($courseId = null, $type) {
	    if(empty($courseId)) return false;
	    $type 	= (!isset($type) ? 'all' : $type);
	    $bookingCourseReasons = $this->find($type, array(
	        'joins' => array(
	             array('table' => 'booking_courses_booking_reasons',
	                'alias' => 'BookingCourseReasons',
	                'type' => 'INNER',
	                'conditions' => array(
	                    'BookingCourseReasons.booking_course_id' => $courseId,
	                    'BookingCourseReasons.booking_reason_id = BookingReason.id'
	                )
	            )
	        ),
	        'group' => 'BookingReason.id'
	    ));
	    return $bookingCourseReasons;
	}







}
