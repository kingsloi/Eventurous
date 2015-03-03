<?php
App::uses('AppController', 'Controller');


/*------------------------------------------------------------------------------------------------------------
 *	TODO:
 *
 *  REWORK THIS ENTIRE CONTROLLER!!!!!!
-----------------------------------------------------------------------*/
class ReportsController extends AppController {
	public $components 	= array('Paginator','Export.Export');
	public $uses = null;
	public $name = 'Reports';


	public function admin_index() {
		
		
	}
	public function admin_stores($regionID = null) {

		$this->loadModel('Store');
		$this->Store->bindModel(array(
			'belongsTo' => array(
				'Region' => array(
					'foreignKey' => false,
					'conditions' => array(
						'Region.id = Store.region_id'
					)
				)
			)
		));


		$conditions = array();
		if(!empty($regionID)){

			$conditions = array(
				'Store.region_id'	=> $regionID
			);
		}
		$this->Paginator->settings = array(
			'limit'=>100,
			'conditions'=> $conditions,
			'contain'=>array(
				'Region'
			)
		);




		$stores = $this->Paginator->paginate('Store');
		$this->set('stores', $stores);
	}


	public function admin_regions() {

		$this->loadModel('Region');
		$this->Paginator->settings = array(
			'limit'=>50,
			'conditions'=>array(
			)
		);
		$regions = $this->Paginator->paginate('Region');


		$this->set('regions', $regions);
	}


	public function admin_usersInRegion($regionID){
		
		$this->admin_users($regionID, null);
		$this->render('admin_users');
	}

	public function admin_usersInStore($storeID){
		
		$this->admin_users(null, $storeID);
		$this->render('admin_users');
	}


	public function admin_users($regionID = null, $storeID = null) {

		$this->loadModel('Profile');
		$this->Profile->bindModel(array(
			'belongsTo' => array(
				'User' => array(
					'foreignKey' => false,
					'conditions' => array('User.id = Profile.user_id')
				),
				'Store' => array(
					'foreignKey' => false,
					'conditions' => array('Store.id = Profile.store_id')
				),
				'Region' => array(
					'foreignKey' => false,
					'conditions' => array('Region.id = Store.region_id')
				),
				'JobTitle' => array(
					'foreignKey' => false,
					'conditions' => array('JobTitle.id = Profile.job_title_id')
				)		               	
			)
		));


		$conditions = array();
		if(!empty($regionID)){

			$conditions = array(
				'Store.region_id'	=> $regionID
			);
		}

		if(!empty($storeID)){

			$conditions = array(
				'Store.id'	=> $storeID
			);
		}

		
		$this->Paginator->settings = array(
			'conditions'=> $conditions,
			'limit'=>50,
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
				'Profile.booking_count',
				'JobTitle.title',
				'Region.id',
				'Region.name',
				'Store.id',
				'Store.name',

			)
		);

		$profiles = $this->Paginator->paginate('Profile');
		$this->set('profiles', $profiles);
	}
/*
//------------------------------------------------------------------------------------------------------------
// View All Bookings
//------------------------------------
*/

	public function admin_bookingsIndex($bookingsCategory = null){

		if(!empty($bookingsCategory)){
			


			switch($bookingsCategory):

				case 'course':

					$modelToFind 	= 'BookingCourse';
					$sortColumn		= 'BookingCourse.name';
					$subLink 		= 'course';
				break;
				case 'event':

					$modelToFind 	= 'Event';
					$sortColumn		= 'Event.name';
					$subLink 		= 'event';
				break;
				case 'region':

					$modelToFind 	= 'Region';
					$sortColumn		= 'Region.name';
					$subLink 		= 'region';
				break;
				case 'store':

					$modelToFind 	= 'Store';
					$sortColumn		= 'Store.name';
					$subLink 		= 'store';
				break;
				case 'user':

					$modelToFind 	= 'Profile';
					$sortColumn		= 'Profile.surname';
					$subLink 		= 'user';
				break;
				case 'booked_by':

					$modelToFind 	= 'Profile';
					$sortColumn		= 'Profile.surname';
					$subLink 		= 'booked_by';
					//TODO:
					//could only show users who have actually booked another user
				break;
				case 'job-title':

					$modelToFind 	= 'JobTitle';
					$sortColumn		= 'JobTitle.title';
					$subLink 		= 'job-title';
				break;
			endswitch;


			$this->Paginator->settings = array(
				'list', array(
					'limit'	=> 1000
				)
			);


			$this->loadModel($modelToFind);
			$lists = $this->Paginator->paginate($modelToFind);


			if(!empty($lists)){

				$this->set('lists', $lists);
				$this->set('sortColumn', $sortColumn);
				$this->set('subLink', $subLink);
				$this->render('admin_bookings_by_category');
			}
		}

	}


	public function admin_bookings($viewByType = null, $viewByID = null, $subViewByType = null, $subViewByID = null) {
		
		$this->loadModel('Booking');
		$this->loadModel('Profile');
		$this->Booking->recursive = -1;
		

		if($viewByType == null || $viewByID == null){

			$this->redirect(array('action'=>'bookingsIndex'));
		}else{

			$conditions 	= array();
			$pageTitle 		= $subPageTitle = '';
			if(isset($viewByType)){


				if(!isset($viewByID)){ 

					$this->Session->setFlash("Please choose a filter to filter by", 'alert-warning');
					$this->redirect(array('action'=>'bookingsIndex'));
				}


				switch($viewByType){

					case 'course':

						$courseDetails 	= $this->Booking->Event->BookingCourse->findById($viewByID);
						$pageTitle		= $courseDetails['BookingCourse']['name'];
						$conditions 	= array('Event.booking_course_id' => $viewByID);
						break;
					case 'event':

						$eventDetails 	= $this->Booking->Event->findById($viewByID);
						$pageTitle		= $eventDetails['Event']['name'];
						$conditions 	= array('Event.id' => $viewByID);
						break;
					case 'store':

						$storeDetails 	= $this->Booking->Profile->Store->findById($viewByID);
						$pageTitle		= $storeDetails['Store']['name'];
						$conditions 	= array('Profile.store_id' => $viewByID);
						break;
					case 'job-title':

						$jobTitleDetails = $this->Booking->Profile->JobTitle->findById($viewByID);
						$pageTitle		 = $jobTitleDetails['JobTitle']['title'];
						$conditions 	 = array('Profile.job_title_id' => $viewByID);
						break;
					case 'booked_by':

						$conditions 	= array(
							'Booking.booked_by' => $viewByID
						);
						break;
					case 'region':

						$this->Session->setFlash("Unfortunately, filtering by region is not yet implemented. This feature will be coming Soon.", 'alert-warning');
						$this->redirect(array('action'=>'admin_bookings'));
						$conditions 	= array('Store.region_id' => $viewByID);
						break;
				}
			}


			if(isset($subViewByType)){

				if(!isset($subViewByID)){ 

					$this->Session->setFlash("Please choose a sub filter to filter by", 'alert-warning');
					$this->redirect(array('action'=>'admin_bookings'));
				}
					switch($subViewByType){

						case 'status':
							$courseDetails 	= $this->Booking->BookingStatus->findById($subViewByID);
							$subPageTitle	= $courseDetails['BookingStatus']['name'];
							$conditions['Booking.booking_status_id'] = $subViewByID;
							break;
				}
			}

			$this->Booking->bindModel(array(
				'belongsTo' => array(
					'Profile' => array(
						'foreignKey' => false,
						'conditions' => array('Profile.id = Booking.profile_id')
					),
					'User' => array(
						'foreignKey' => false,
						'conditions' => array('User.id = Profile.user_id')
					),
					'Store' => array(
						'foreignKey' => false,
						'conditions' => array('Store.id = Profile.store_id')
					),
					'Region' => array(
						'foreignKey' => false,
						'conditions' => array('Region.id = Store.region_id')
					),
					'BookingCourse' => array(
						'foreignKey' => false,
						'conditions' => array('BookingCourse.id = Event.booking_course_id')
					),
					'JobTitle' => array(
						'foreignKey' => false,
						'conditions' => array('JobTitle.id = Profile.job_title_id')
					)		               	
				)
			));


		$this->Paginator->settings = array(
			'limit'	=> 100,
			'contain' => array(
				'BookingReason',
				'BookingStatus',
				'Event',
				'Profile',
				'User',
				'Store',
				'Region',
				'BookingCourse',
				'JobTitle'
			),
			'conditions' => $conditions,
			'fields' => array(
				'User.username',
				'Profile.first_name', 'Profile.surname',
				'JobTitle.id','JobTitle.title',
				'Store.id','Store.name','Store.region_id',
				'Region.id','Region.name',
				'Booking.id','Booking.created','Booking.modified','Booking.booked_by',
				'BookingCourse.id','BookingCourse.name',
				'BookingReason.id','BookingReason.name',
				'BookingStatus.id','BookingStatus.name',
				'Event.name'
			)
		);

			$bookings 			= $this->Paginator->paginate('Booking');
			$allUsersFullNames 	= $this->Profile->getAllUsersFullNames();
			$updatedBookings 	= array();


			foreach($bookings as $booking){
				if(array_key_exists($booking['Booking']['booked_by'], $allUsersFullNames)){
					
					$booking['Booking']['booked_by'] = $allUsersFullNames[$booking['Booking']['booked_by']];
				}
				$updatedBookings[] = $booking;
			}




			$this->set('pageTitle', $pageTitle);
			$this->set('subPageTitle', $subPageTitle);
			$this->set('bookings', $updatedBookings);
			$this->render('bookings');
		}

	}
}
