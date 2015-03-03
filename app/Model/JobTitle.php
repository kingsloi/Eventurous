<?php
App::uses('AppModel', 'Model');
/**
 * JobTitle Model
 *
 * @property Profile $Profile
 */
class JobTitle extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

	public $validate = array(
		'title' => array(
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


	
	public $hasMany = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'job_title_id',
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


