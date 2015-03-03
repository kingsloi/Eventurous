<?php
App::uses('AppController', 'Controller');

class ProfilesController extends AppController {

	public $components = array('Paginator');

/*------------------------------------------------------------------------------------------------------------
 *  getAllUsersFullNames
-----------------------------------------------------------------------*/
	
	public function getAllUsersFullNames() {
		$this->Profile->getAllUsersFullNames();
	}

/*------------------------------------------------------------------------------------------------------------
 *  findNameFromUserID
-----------------------------------------------------------------------*/
	
	public function findNameFromUserID() {
		
		$this->Profile->findNameFromUserID();
	}


/*------------------------------------------------------------------------------------------------------------
 *  view
-----------------------------------------------------------------------*/
	
	public function view() {

		$this->admin_view();
	}


/*------------------------------------------------------------------------------------------------------------
 *  admin_view($profilesID = null)
 *
 *	if profileID is fed to function(admin) then displays that profileID stats,
 *	else displayed logged in user stats
-----------------------------------------------------------------------*/

	public function admin_view($profileID = null){
		
		//	getProfile for either userID or currently logged in user
		if($profileID == null){

			$userProfileID 	= $this->Auth->user('Profile.id');
			$userProfile 	= $this->Profile->getProfileInformation($userProfileID);
			$pageTitle		= 'Your Profile';
			$bookingsToShow = 'month';	

		}else{

			$userProfile 	= $this->Profile->getProfileInformation($profileID);
			$userProfileID	= $userProfile['Profile']['id'];
			$pageTitle		= $userProfile['Profile']['first_name'].'\'s Profile';
			$bookingsToShow = 'all';		
		}

		//if viewing a profile of another user
		//whereas /profile displays bookings that MONTH
		//to increate effiency, /admin/profile/view/n displays ALL a users bookings
		$searchType 			= (($bookingsToShow == 'month') ? 'all' : 'count');
		$basicFind				= (($bookingsToShow == 'month') ? false : true);

		//if admin function
		if($profileID !== null){

			//bind dat model fam
			$this->loadModel('Booking');
			$this->Booking->bindModel(array(
			    'belongsTo' => array(
			   		'Profile' => array(
			            'foreignKey' => false,
			            'conditions' => array('Profile.id = Booking.profile_id')
			        ),
			        'Event' => array(
			            'foreignKey' => false,
			            'conditions' => array('Event.id = Booking.event_id')
			        ),
			        'BookingStatus' => array(
			            'foreignKey' => false,
			            'conditions' => array('BookingStatus.id = Booking.booking_status_id')
			        ),
			        'BookingCourse' => array(
			            'foreignKey' => false,
			            'conditions' => array('BookingCourse.id = Event.booking_course_id')
			        )	               	
			    )
			));

		    $this->Paginator->settings = array(
		        'conditions' => array(
		        	'Booking.profile_id' 		=> $userProfileID,
		        	//'Event.event_finish >'		=> date('Y-m-d 23:59:59', mktime(0, 0, 0, date('m'), 0, date('Y'))),
		        ),
		        'limit' 	=> 10,
		        'contain' 	=> array(
		        	'Profile',
		        	'Event',
		        	'BookingStatus',
		        	'BookingCourse'

		        )
		    );
		   $allUserBookings = $this->Paginator->paginate('Booking');
		   $this->set(compact('allUserBookings'));
		}

		/*
			*	STATS
			*
			*	build booking statistics for profile
		*/
		$userBookingsMonth 		= $this->Profile->Booking->getAllInformationForSpecificBookingUser(
										array(
											'Booking.profile_id' 		=> $userProfileID,
											'Event.event_finish <'		=> date('Y-t-m 23:59:59'),
											'Booking.booking_status_id'	=> array(8,9)
										),
										$searchType,
										true,
										$basicFind
									);

		$userBookingsPast	 	= $this->Profile->Booking->getAllInformationForSpecificBookingUser(
									array(
										'Booking.profile_id' 		=> $userProfileID,
										'OR' => array (
											'Event.event_finish <'		=> date('Y-m-d 23:59:59'),
											'Booking.booking_status_id'	=> array(7,13,14)
										)
									),
									'count',
									true,
									true
								);

		$userBookingsFuture 	= $this->Profile->Booking->getAllInformationForSpecificBookingUser(
									array(
										'Booking.profile_id' 		=> $userProfileID,
										'Event.event_finish >'		=> date('Y-m-d 23:59:59'),
										'Booking.booking_status_id'	=> array(8,9)
									),
									'count',
									true,
									true
								);

		$userBookingsCancelled 	= $this->Profile->Booking->getAllInformationForSpecificBookingUser(
									array(
										'Booking.profile_id' 		=> $userProfileID,
										'Event.event_finish >'		=> date('Y-m-d H:m:s'),
										'Booking.booking_status_id'	=> array(3,4,5,10,11,12,15,17,18)
									),
									'count',
									true,
									true
								);

		$userBookingsOutForReview = $this->Profile->Booking->getAllInformationForSpecificBookingUser(
									array(
										'Booking.profile_id' 		=> $userProfileID,
										'Event.event_finish >'		=> date('Y-m-d H:m:s'),
										'Booking.booking_status_id'	=> array(1,16)
									),
									'count',
									false,
									true
								);

		$userBookingsForReview = $this->Profile->Booking->getAllInformationForSpecificBookingUser(
									array(
										'Booking.profile_id' 		=> $userProfileID,
										'Event.event_finish >'		=> date('Y-m-d H:m:s'),
										'Booking.booking_status_id'	=> array(2)
									),
									'count',
									false,
									true
								);

		//if viewing profile of logged in user
		//nicify statuses
		if($profileID == null){

			//loop through each booking this month
			//niceifying each status
			foreach($userBookingsMonth as $id => $userMonthBooking){

				$userBookingsMonth[$id]['BookingStatus']['name'] = $this->niceifyBookingStatus($userMonthBooking['BookingStatus']['id']);
			}
		}
		
		//send variables to view
		$this->set(compact('userBookingsMonth','userBookingsFuture','allUserBookings','userBookingsPast','userBookingsCancelled','userBookingsOutForReview','userBookingsForReview'));
		$this->set('pageTitle', $pageTitle);
		$this->set('profile', $userProfile);
	}


/*------------------------------------------------------------------------------------------------------------
 *  edit
 *
 *	method used to edit a profile
 *	two possible edits: 1) update password 2) add/update contact information (phone/email)
-----------------------------------------------------------------------*/
	public function edit() {

		//getProfile for current logged in User
		$userProfileID 	= $this->Auth->user('Profile.id');
		$userID 		= $this->Auth->user('id');
		$options 		= array('conditions' => array('Profile.' . $this->Profile->primaryKey => $userProfileID));
		$profile 		= $this->Profile->find('first', $options);

		//if POST/PUT
		if ($this->request->is('post') || $this->request->is('put')) {


			//----------
			//	If adding/Updateing contact details
			//----------
			if(isset($this->request->data['Profile']) && $this->request->data['Profile']['formType'] == 'addContactDetails'){
				
				//set Profile.ID from currently logged in user Profile
				$this->request->data['Profile']['id'] 	= $userProfileID;

				//attempt to save POSTd data
				if ($this->Profile->save($this->request->data)){

					$this->Session->setFlash('Your Profile has been saved.', 'alert-success');
					return $this->redirect('/profile/details');
				}else{

					$this->Session->setFlash('<b>Error</b>: Profile could not be saved. Please try again.', 'alert-error');
				}
			}


			//----------
			//	If adding/Updating Password
			//----------
			if(isset($this->request->data['User']) && $this->request->data['User']['formType'] == 'updatePassword'){

				//get password fields
				$newPassword 				= $this->request->data['User']['password'];
				$newPasswordRepeat 			= $this->request->data['User']['passwordRepeat'];
				
				//if newPassword MATCHES newPasswordRepeat (i.e. passwords match)
				if($newPassword == $newPasswordRepeat){

					$this->request->data['User']['id'] 			= $userID;

					if ($this->Profile->User->save($this->request->data)) {

						$this->Session->setFlash('Password updated', 'alert-success');
						return $this->redirect('/profile/details');
					}

				}else{

					$this->Session->setFlash('<b>Error</b>: Passwords do not match', 'alert-error');	
				}
			}

		}else{

			$this->request->data = $profile;
		}

		//set variables for dropdowns on view
		$store 			= $this->Profile->Store->findById($profile['Profile']['store_id']);
		$jobTitle 		= $this->Profile->JobTitle->findById($profile['Profile']['job_title_id']);
		$this->set(compact('store', 'jobTitle','profile'));
	}
}