<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');

class User extends AppModel {
	public $displayField = 'username';   

	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A HRMS# is required'
				)
		),
		'password' => array(
			'unique_rule' 	=> array(
				'rule' 		=> array('between', 4, 20),
				'on' 		=> 'update',
				'message' 	=> 'Please enter a password between 4-20 characters',
				//'last' 		=> true,
				'allowEmpty' => false
	    	)
		),
		'role' => array(
			'valid' => array(
				'rule' => array('inList', array('admin', 'user')),
				'message' => 'Please enter a valid role',
				'allowEmpty' => false
				)
			)
		);

	public $hasOne = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			)
		);





	public function beforeSave($options = array()) {

        parent::beforeSave();
        if(isset($this->data['User']['password'])){
        	
        	$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        	return true;
        }
        
        
    }



}