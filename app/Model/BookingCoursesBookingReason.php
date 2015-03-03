<?php
App::uses('AppModel', 'Model');
/**
 * BookingCoursesBookingReason Model
 *
 * @property BookingCourse $BookingCourse
 * @property BookingReason $BookingReason
 */
class BookingCoursesBookingReason extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'BookingCourse' => array(
			'className' => 'BookingCourse',
			'foreignKey' => 'booking_course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BookingReason' => array(
			'className' => 'BookingReason',
			'foreignKey' => 'booking_reason_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
