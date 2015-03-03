<?php
App::uses('AppModel', 'Model');
/**
 * CourseCategory Model
 *
 * @property BookingCourse $BookingCourse
 */
class CourseCategory extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'course_category';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
		'desc' => array(

		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Order must be a number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'BookingCourse' => array(
			'className' 	=> 'BookingCourse',
			'foreignKey' 	=> 'course_category_id',
			//'dependent' 	=> ture,
			'conditions' 	=> '',
			'fields' 		=> '',
			'order' 		=> '',
			'limit' 		=> '',
			'offset' 		=> '',
			'exclusive' 	=> '',
			'finderQuery' 	=> '',
			'counterQuery' 	=> ''
		)
	);

}
