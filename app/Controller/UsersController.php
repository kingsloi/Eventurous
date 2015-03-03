<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $helpers = array('Session');
	public $components = array(
		'Paginator',
		'Session',
    	'Auth'
	);


    //----------------
    //  Before Filter
    //----------------
	public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('login','password_reset');
    }

    //----------------
    //  Admin Login - is the exact same as a normal login!
    //----------------
    public function admin_login() {

    	return $this->redirect('/login');
    }

    //----------------
    //  Global login function
    //----------------
	public function login() {

		//don't allow user to visit login page after loggin in!
		if($this->Session->read("hasConfirmed") == true){

			//return to categories page
			return $this->redirect('/categories');
		}

		//set the title for the view
		$this->set('title_for_layout', 'Login');

		//if POST
		if ($this->request->is('post')) {

			//try to authenticate with username/passwoed
			if ($this->Auth->login()) {

				//if successful, but haven't confirmed, set necessary variable
				$this->Session->write("hasConfirmed", false);

				//check if user is_active (boolean)
				if ($this->Auth->user('is_active') == 1) {

					//if user is active, redirect to confirmation page
					return $this->redirect(array('action' => 'confirmation'));
				}else{

					//else if user isn't active i.e. is_active = 0
					//set message, redirect to logout
					$this->Session->setFlash('<b>Error:</b> Unfortunately your account is not active. Please contact <b>'.Configure::read('APP_ADMIN_EMAIL').'</b>', 'alert-error');
					return $this->redirect('/logout');
				}
			}else{

				//if POST isn't successful i.e. user enters wrong details
				$this->Session->setFlash('<b>Error:</b> Incorrect HRMS# or password.', 'alert-error');
			}
		}
	}


    //----------------
    //  Confirmation view
    //----------------
	public function confirmation() {

		//set title
		$this->set('title_for_layout', 'Confim your details');

		//set IDs
		$userID 			= $this->Auth->user('id');
		$loggedInUser 		= $this->Session->read('Auth');

		//build name and also initials
		$firstName 			= $loggedInUser['User']['Profile']['first_name'];
		$lastName 			= $loggedInUser['User']['Profile']['surname'];
		$loggedInUserName 	= $firstName .' '. $lastName;
		$loggedInUserNameInitials = $firstName[0].''.$lastName[0];

		//build sessions
		$this->Session->write("loggedInUserName", $loggedInUserName);
		$this->Session->write("loggedInUserNameInitials", $loggedInUserNameInitials);

		//build conditions
		$conditions = array(
		    'conditions' => array('User.id' => $userID),
		    'fields'=>array(
		    	'User.id',
		    	'User.username',
		    	'User.role',
		    	'Profile.*'
		    ),
		    'contain' => array(
            	'Profile' => array(
                	'Store' => array(
                		'Region'
                	)
            ))
		);

		//if POST
		if($this->request->is('post')){

			//if user presses 'confirm' button
			if($this->request->data['route'] == 'confirm'){

				//set a hasConfirmed Flag
				$this->Session->write("hasConfirmed", true);

				//retrn to /catagories pages
				return $this->redirect('/categories');

			}else if($this->request->data['route'] == 'logout'){

				//return to /logout page if press 'logout' button
				return $this->redirect('logout');
			}
		}

		//go and get profile
		//send to profile
		$results 	= $this->User->find('first', $conditions);
		$this->set('userprofile', $results);
	}



    //----------------
    //  Global login function
    //----------------
	public function logout() {
		//delete * sessions
		$this->Session->delete('User');
		$this->Session->delete('Auth');
		$this->Session->delete('hasConfirmed');
		$this->Session->delete('loggedInUserName');
		$this->Session->delete('loggedInUserNameInitials');
	    $this->redirect($this->Auth->logout());
	}


    //----------------
    //  Passowrd reset
    //----------------
	public function password_reset($hash = null) {

		//set layout for view
		$this->set('title_for_layout', 'Password Reset');

		//load PasswordReset model (to access Database table)
		$this->LoadModel('PasswordReset');

		//if fed a hash i.e. /password-reset/c91e9b0cdc62b07adf69b3ea04123969088ffa18
		if($hash){

			//attempt to find PasswordReset value
			//with fed hash
			$foundHash = $this->PasswordReset->find(
				'first', array(
					'conditions' => array(
						'PasswordReset.hash' => $hash
					)
				)
			);

			//if a hash HAS been found
			if($foundHash){

				//get username
				//created
				//and whether or not password reset has been used before (active or not)
				$hashCreation 		= $foundHash['PasswordReset']['created'];
				$requestUsername 	= $foundHash['PasswordReset']['username'];
				$isHashActive		= $foundHash['PasswordReset']['is_active'];

				//if PasswordReset has been used (is_active => 0)
				if($isHashActive == false){

					//show password reset has already been used, redirect to same page
					$this->Session->setFlash('This password reset has already been used.','alert-error');
					return $this->redirect('/password-reset');
				}else{

					//check HASH validitiy
					//hash's expire after 24 hours so check whether hash has expired or not
					if (time() >= strtotime($hashCreation) + 86400) {

						//if hash has expired, set expired message, and redirect o same page
						$this->Session->setFlash('Password reset hash has expired. Please request a new password.','alert-error');
						return $this->redirect('/password-reset');
					}else{

						//if password is still is active
						//i.e. not been activated before
						$this->set('username', $requestUsername);
						$this->set('newPasswordForm', true);
					}
				}

			}else{

				//if INVALID hash i.e. hash doesn't exist
				//set failure message and redirect
				$this->Session->setFlash('Naughty, naughty. That\'s and invalid hash!','alert-error');
				return $this->redirect('/password-reset');
			}

		}

	    //----------------
	    //  IF POST
	    //  whether from Password Request (request password link), or Password reset (enter new password)
	    //----------------
		if ($this->request->is('post')) {

			//get form type - whether resetPassword -OR- newPassword
			$formType = $this->request->data['User']['formType'];

			//if reset password (i.e. there is a valid hash present)
			if($formType == 'resetPassword'){

				//security check.. just in case
				(!isset($this->request->data['User']['newPassword'])||!isset($this->request->data['User']['newPassword'])||!isset($this->request->data['User']['username']) ? die('don\'t even think about it.') : '');

				//set checks + set form type to view
				$this->set('newPasswordForm', true);

				//set errors to false
				$errors = false;

				//get new password + password repeat values
				$formUsername 				= $this->request->data['User']['username'];
				$newPassword 				= $this->request->data['User']['newPassword'];
				$newPasswordRepeat 			= $this->request->data['User']['newPasswordRepeat'];

				//if newPassword MATCHES newPasswordRepeat (i.e. passwords match)
				if($newPassword == $newPasswordRepeat){

					if(strlen($newPassword) > 3 && strlen($newPassword) < 21){

						//find password record by hash
						$userToReset 				= $this->PasswordReset->findByHash($hash);

						//getPassword Reset
						$this->PasswordReset->id 	= $userToReset['PasswordReset']['id'];

						//get usename
						$userNameToReset 			= $userToReset['PasswordReset']['username'];


						//check username enter matches password request hrms (pretty much 2step authentication)
						if($formUsername == $userNameToReset){

							//get User Profile + encrypt new password
							$userDetails 				= $this->User->findByUsername($userNameToReset);
							$userID 					= $userDetails['User']['id'];
							$this->User->id 			= $userID;
							//$encryptedPassword 			= AuthComponent::password($newPassword);

							//attempt to updated password reset field so that it can no longer be used
							//if fails to say, set errors > true


							//attempt to save new, ecnrypted user password
							if(!$this->User->savefield('password', $newPassword, false)){

								$errors = true;
							}else{

								if(!$this->PasswordReset->savefield('is_active', false)){

									$errors = true;
								};

							}

							//if there are no errors i.e. password reset + password update were successful
							if($errors == false){

								//set success message + redirect to home
								$this->Session->setFlash('Password reset successfully.','alert-success');
								$this->redirect('/');
							}else{

								///if there was a problem saving either the User password or deactivating password reset
								$this->Session->setFlash('There was a problem updating your password, please contact the system administrator ASAP.','alert-error');
								//$this->redirect('/');
							}
						}else{

							//username entered doesn't match one from request
							$this->Session->setFlash('HRMS# does not match HRMS of password request. Please try again.','alert-error');
						}


					}else{
						$this->Session->setFlash('Please enter a password between 4-20 characters.','alert-error');
					}

				}else{

					//if passwords do not match
					//set error message to rectify
					$this->Session->setFlash('Passwords do not match. Please try again.','alert-error');
				}


			}else{

				//---------------
				// PASSWORD REQUEST
				//---------------

				//get username/hrms from form input
				$username = $this->request->data['User']['username'];

				//try and find username
				//also attached 'Profile'
				//get email and phonenumber fields only!
				$userProfile = $this->User->find(
					'first', array(
						'conditions' => array(
							'User.username' => $username
						),
						'contain' => array(
							'Profile'
						),
						'fields' => array(
							'User.username',
							'Profile.email',
							'Profile.phonenumber'
						)
					)
				);

				//if username has been found!
				if(!empty($userProfile)){

					//get the username of the user
					$userName = $userProfile['User']['username'];

					//check if user has an email on their account
					//use this as 1st priority to save ourselves the extra workload of resetting 1,000,000 passwords!!
					if(!empty($userProfile['Profile']['email'])){

						//get user email/number
						$userEmail 	= $userProfile['Profile']['email'];
						$userNumber = $userProfile['Profile']['phonenumber'];

						//build a password reset URL (like: c91e9b0cdc62b07adf69b3ea04123969088ffa18)
						$uniqueHash = Security::hash(String::uuid(),'sha1',true);
						$resetURL 	= Router::url( ($this->here), true ).'/'.$uniqueHash;

						//set a password link message
						$message 	=  '<p>Someone has requested a password reset for this account.</p>';
						$message 	.= '<p>If this was a mistake, just ignore this email.</p>';
						$message 	.= '<p>However, if this password reset request was intentional, please click the following URL:</p>';
						$btnAction 	=	$resetURL;

						//if email address is found, send reset email TO user email
						$emailSent = $this->sendPasswordReset('user', array(
							'username' 	=> $userName,
							'to'		=> $userEmail,
							'message'	=> $message,
							'btnAction'	=> $btnAction
						));

						//check if email has been sent!
						if($emailSent == true){

							//create a new PasswordReset record with hash value + username
							//to check valididity
							$this->PasswordReset->create();

							//build PasswordReset array
							$PasswordResetData = array(
								'PasswordReset' => array(
									'username'	=> $userName,
									'hash'		=> $uniqueHash,
									'is_active'	=> 1
								)
							);

							//save PasswordReset
							$this->PasswordReset->save($PasswordResetData);

							//set a success message
							$this->Session->setFlash('A password reset link has been sent to the email on file. Please check your spam folder','alert-success');

						}else{

							//if there was a problem sending the email, set a flash message
							$this->Session->setFlash('There was a problem sending the password reset email. Please try again.','alert-error');
						}

					}else if(empty($userProfile['Profile']['email']) && !empty($userProfile['Profile']['phonenumber'])){

						//if no email, but has a phone number
						$userNumber = $userProfile['Profile']['phonenumber'];
						$message 	=  "<p>Someone has requested a password reset for $userName's account.<p>";
						$message 	.= '<p>Please contact the user on: <br/>';
						$message 	.= $userNumber.'</p>';

						//if phone number is not empty
						//send an email to admin instead
						$emailSent = $this->sendPasswordReset('admin', array(
								'username' 		=> $userName,
								'to'			=> Configure::read('APP_ADMIN_EMAIL'),
								'message'		=> $message
						));

						//check if email has been sent
						if($emailSent == true){

							//if successful, show success
							$this->Session->setFlash('A password request has been emailed to the system administrator with the contact number on file.','alert-success');
						}else{

							//else, show fail!
							$this->Session->setFlash('There was a problem sending the password reset email. Please try again.','alert-error');
						}

					}else{

						//if  profile DOESN'T have
						//email address -OR- phone number
						//then print system admin contact details
						$this->set('requestMoreInfo', true);
					}

				}else{

					//if username has not been found!
					$this->Session->setFlash('HRMS# not found. Please try again.','alert-error');
				}
			}
		}
	}

	//---------------
	// Send Password Reset
	//---------------
	function sendPasswordReset($sendEmailTo, $emailDetails){

		//TODO
		$Email 			= new CakeEmail('outlook');

		//get to/hrms/message
		$emailTo 		= $emailDetails['to'];
		$emailUsername 	= $emailDetails['username'];
		$emailMessage 	= $emailDetails['message'];

		//check to see if email is being set to user or system admin
		$emailSubject 	= (($sendEmailTo == 'user') ? 'Booking System Password Reset' : $emailUsername . ' has requested a password reset');

		//set Email variables
		$Email->to($emailTo);
		$Email->emailFormat('html');
		$Email->template('password-reset', 'main');
		$Email->subject($emailSubject);
		$Email->viewVars($emailDetails);

		//try to send email
		try {
		    if ( $Email->send() ) {

		        //return true if successful
		        return true;
		    } else {

		    	//return false if failed
		        return false;
		    }
		} catch ( Exception $e ) {

			//attempt to catch errors, brah
		    die('error sending email');
		}

	}

	//----------------
    //  AJAX Search
    //----------------
	public function search() {

		//load Profile Model for calling in
		//find ARD in Profile model
		$this->loadModel('Profile');

		//this action is only available by AJAX
		if (!$this->request->is('ajax')) {

			//die if not ajax
			die('Hey, no! naughty!!');
		}


		//becase AJAX, no layout is required
		$this->layout 		= 'ajax';
		$this->autoLayout 	= false;
		$this->autoRender 	= false;

		//set response as false (i.e. failed),
		//overwrite when successful
		$response 			= array('success' => false);
		$unique 			= false;
		$resultsTotal		= 0;

		if(!isset($this->data['Search']['criteria'])){

			//if no search query
			$response['data'] = 'Please select a search criteria';
			$response['code'] = -1;
			return json_encode($response);
		}


		//Get which option user selected i.e. HRMS #, First Name or Surname
		$searchCriteria = $this->data['Search']['criteria'];

		//Get search query
		$searchQuery 	= $this->data['Search']['search'];

		//Which option the user selected
		switch ($searchCriteria) {
		    case 'hrms':

		        $criteria 				= 'User.username';
		        $findValue 				= 'first';
		        $notFoundErrorMessage 	= 'HRMS Not Found';
		        $searchType 			= 'hrms';
		        $unique 				= true;
		        break;

		    case 'firstname':

		    	$searchQuery 			= strtoupper($searchQuery);
		        $criteria 				= 'UPPER (`Profile.first_name`) LIKE';
		        $findValue 				= 'all';
		        $notFoundErrorMessage 	= 'First name Not Found';
		        $searchType 			= 'firstname';
		        break;
		    case 'surname':

				$searchQuery 			= strtoupper($searchQuery);
		        $criteria 				= 'UPPER (`Profile.surname`) LIKE';
		        $findValue 				= 'all';
		        $notFoundErrorMessage 	= 'Surname Not Found';
		        $searchType 			= 'surname';
		        break;
		}


		//check to see if query isn't empty
		if (!empty($searchQuery)){

			//bind dat model fam
			$this->User->bindModel(array(
			    'belongsTo' => array(
			   		'Profile' => array(
			            'foreignKey' => false,
			            'conditions' => array('Profile.user_id = User.id')
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

			//set conditions based on option and query data
			$conditions = array(
				'conditions' 	=> array(
					$criteria 			=> $searchQuery.'%', //specific seach criteria/query hrms/fname/sname etc
					'User.is_active' 	=> 1 //only show active users
				),
				'fields' 		=> array(
					'User.username',
					'Profile.id',
					'Profile.user_id',
					'Profile.first_name',
					'Profile.surname',
					'Profile.job_title_id',
					'Profile.store_id',
					'Store.*',
					'Region.*',
					'JobTitle.*'
				),
				'contain' 		=> array(
					'Profile',
					'Store',
					'Region',
					'JobTitle'
					)
				);

			//attempt to find users
			$users = $this->User->find($findValue, $conditions);


			//If users are found
			if($users){

				//if search can only bring back 1 result i.e. HRMS#
				if ($unique){

					//results = 1
					$resultsTotal 	= 1;
				}else{

					//results = count of users
					$resultsTotal 	= count($users);
				}

				//set blank text incase not found
				$usersRegionARD = "";

				//if $users is greater than 1
				if($resultsTotal > 1){

					//loop through each user and get their ARD
					foreach ($users as $id => $user) {

						//get ARD and add it to the array
						//$usersRegionARD 				= $this->Profile->getRegionARD($users[$id]['Profile']['user_id']);
						//$users[$id]['Profile']['ard'] 	= $usersRegionARD;
					}

					//if $user = 1
				}else{

					//if returned 1 user is unique i.e. hrms#
					if($unique){

						//add it to array
						//$usersRegionARD 			= $this->Profile->getRegionARD($users['Profile']['user_id']);
						//$users['Profile']['ard'] 	= $usersRegionARD;

					}else{

						//if returned user isn't unique but only 1 user
						//TODO!!
						//could move to JS
						//$usersRegionARD 				= $this->Profile->getRegionARD($users[0]['Profile']['user_id']);
						//$users[0]['Profile']['ard'] 	= $usersRegionARD;
					}
				}

				//success
				$response['searchType'] 	= $searchType;
				$response['totalUsers'] 	= $resultsTotal;
				$response['success'] 		= true;
				$response['data'] 			= $users;
			}else{

				//failure
				$response['searchType'] 	= $searchType;
				$response['totalUsers'] 	= $resultsTotal;
				$response['data'] 			= $notFoundErrorMessage;
				$response['code'] 			= 0;
			}
		} else {

			//if no search query
			$response['data'] = 'Please enter a search query';
			$response['code'] = -1;
		}

		//return this badboi
		$this->header('Content-Type: application/json');
		return json_encode($response);
		//return
	}
}