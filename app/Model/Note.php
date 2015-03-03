<?php
App::uses('AppModel', 'Model');
/**
 * Note Model
 *
 * @property Profile $Profile
 */
class Note extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'note';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'note_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
