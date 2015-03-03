<?php
App::uses('AppModel', 'Model');

class CourseType extends AppModel {

	public $useTable = 'course_type';

	public $displayField = 'name';

	public $hasMany = array(
		'BookingCourse' => array(
			'className' => 'BookingCourse',
			'foreignKey' => 'course_type_id',
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

}
