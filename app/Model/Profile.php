<?php
App::uses('AppModel', 'Model');
App::import('component', 'CakeSession');        
class Profile extends AppModel {

	public $displayField = 'fullname';

	public $virtualFields = array(
	    'fullname' => 'CONCAT(Profile.first_name, " ", Profile.surname)'
	);

	public $validate = array(
		'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'surename' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
	    'phonenumber' => array(
	    	'valid_rule' => array(
		    	'allowEmpty'	=> true,
		    	//http://stackoverflow.com/questions/8099177/validating-uk-phone-numbers-in-php
				'rule' => array('phone', '/^\(?(?:(?:0(?:0|11)\)?[\s-]?\(?|\+)44\)?[\s-]?\(?(?:0\)?[\s-]?\(?)?|0)(?:\d{5}\)?[\s-]?\d{4,5}|\d{4}\)?[\s-]?(?:\d{5}|\d{3}[\s-]?\d{3})|\d{3}\)?[\s-]?\d{3}[\s-]?\d{3,4}|\d{2}\)?[\s-]?\d{4}[\s-]?\d{4}|8(?:00[\s-]?11[\s-]?11|45[\s-]?46[\s-]?4\d))(?:(?:[\s-]?(?:x|ext\.?\s?|\#)\d+)?)$/', 'uk'),
		        'message'    => 'Please enter a valid 07* or +44 phone number'
		    ),
			'unique_rule' => array(
				'rule' 		=> 'isUnique',
				'on' 		=> 'update',
				'message' 	=> 'This phone number is assigned to another user.',
				'last' 		=> true
	    	)
		),
		'email' => array(
	    	'valid_rule' => array(
		    	'allowEmpty'	=> true,
				'rule' 			=> 'email',
		        'message'    	=> 'Please enter a valid email address'
		    ),
			'unique_rule' => array(
				'rule' 		=> 'isUnique',
				'on' 		=> 'update',
				'message' 	=> 'This email address is assigned to another user.',
				'last' 		=> true
	    	)
		)
	);


	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Store' => array(
			'className' => 'Store',
			'foreignKey' => 'store_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		),
		'JobTitle' => array(
			'className' => 'JobTitle',
			'foreignKey' => 'job_title_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Note' => array(
			'className' => 'Note',
			'foreignKey' => 'note_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Booking' => array(
			'className' => 'Booking',
			'foreignKey' => 'profile_id',
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




	public function getRegionARD($id){
		
		if(!$id){
			$profileID = $this->Auth->user('Profile.id');
		}else{
			$profileID = $id;
		}


		$this->bindModel(array(
		    'belongsTo' => array(
		        'Store' => array(
		            'foreignKey' => false,
		            'conditions' => array('Store.id = Profile.store_id')
		        ),
		        'JobTitle' => array(
		            'foreignKey' => false,
		            'conditions' => array('JobTitle.id = Profile.job_title_id')
		        )		               	
		    )
		));



		$results = $this->find('first', 
			array(
				'conditions' => array(
					'Profile.id' => $profileID
				),
				'contain'=>array(
					'Store'
				)
			)
		);



		//If Profile found
		if($results){
			
			//Get the searched for users REGION ID
			$regionID = $results['Store']['region_id'];

			//Attempt to search for an ARD for that particular region

			$ARDconditions = array(
				'conditions' 	=> array(
					//'Profile.job_title_id' 			=> '1',
					'JobTitle.title'	=> 'Field|Associate Regional Director',
					'Store.region_id'	=> $regionID
				),

				'contain' 		=> array(
					'Store',
					'JobTitle'
				)
			);


			$results = $this->find('first', $ARDconditions);

    		//
    		if($results){

    			$ardFullName = $results['Profile']['first_name']." ". $results['Profile']['surname'];
    			return $ardFullName;
    		}else{

    			return '';
    		}
		}else{
			echo "error";
		}
	}






	public function getProfilesList(){
		return $this->find('list');
	}


    public function findNameFromUserID($userID = null){
        $criteria = array(
            'conditions' => array('Profile.user_id' => $userID));
        $returnedUser = $this->find('first', $criteria);
        return $returnedUser['Profile']['first_name']." ".$returnedUser['Profile']['surname'];
    }

    public function getAllUsersFullNames(){
        $returnedUsers = $this->find('all');
        $allUsersFullNames = array();
        foreach($returnedUsers as $returnedUser):
            $allUsersFullNames[$returnedUser['Profile']['id']] = $returnedUser['Profile']['fullname'];
        endforeach;
        
        return $allUsersFullNames;
    }

 	public function getProfileIDFromUserID($userID = null){
 		if(empty($userID)){
 			$loggedInUserID = CakeSession::read('Auth.User.id');
			$userID 		= $loggedInUserID;
			$userProfile 	= $this->findByUserId($userID);
			$userProfileID 	= $userProfile['Profile']['id'];	
 		}else{
 			$userProfile 	= $this->findByUserId($userID);
 			if(!empty($userProfile)){
 				
 				$userProfileID 	= $userProfile['Profile']['id'];
 			}else{
 				
 				throw new NotFoundException(__('Invalid profile'));
 			}
			
 		}

		return $userProfileID;
	}


//COULD IMPROVE TODO
	public function getProfileInformation($userProfileID){

		$this->bindModel(array(
		    'belongsTo' => array(
		        'Region' => array(
		            'foreignKey' => false,
		            'conditions' => array('Region.id = Store.region_id')
		        )
		    )
		));


		//Get all information related to user (profile_id)
		$profileOptions = array(
			'contain'=>array(
				'User',
				'JobTitle',
				'Store',
				'Region',
			),
			'fields'		=> array(
				'User.id',
				'User.username',
				'Profile.id',
				'Profile.first_name',
				'Profile.surname',
				'Profile.phonenumber',
				'Profile.email',
				'Profile.modified',
				'JobTitle.title',
				'Region.id',
				'Region.name',
				'Store.id',
				'Store.name',

			),
			'conditions' 	=> array(
				'Profile.id' => $userProfileID
			)
		);
		$userProfile = $this->find('first', $profileOptions);
		return $userProfile;
		
	}







}
