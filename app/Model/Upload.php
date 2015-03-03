<?php


class Upload extends AppModel {

    public $actsAs = array(
        'Upload.Upload' => array(
            'file' => array(
            	'pathMethod' => 'flat',
            	'thumbnails' => false
            )

        )
    );

    //upload validation!
    //1 module per rule
    //i.e. array('model'=>rule('notempty'=>array('rule='notempty')),'notblank=>...etc.')
	public $validate = array(
		'file' => array(
		   'isValidExtension' => array(
                'rule' => array('isValidExtension', array('csv')),
                'message' => 'File does not have a .csv extension'
                ),
		    'isFileUploadOrHasExistingValue' => array(
                'rule' => array('isFileUploadOrHasExistingValue'),
                'message' => 'File was missing from submission'
                )
		    )
	);

	function processUserImport($filename) {

		//SET PHP VARIABLES
		//INCRASE EXECUTION TIME TO 300S and memory limit (temporary) to 128MB
		ini_set('max_execution_time', 300);
		ini_set("memory_limit","128M");

		//get admin name + email from settings
		$adminName 	= Configure::read('APP_ADMIN_NAME');
		$adminEmail = Configure::read('APP_ADMIN_EMAIL');

		// Load ParseCSV library (not exactly using cake conventions...but still YOLO, right?)
		// http://code.google.com/p/parsecsv-for-php/
		require_once('../lib/parsecsv.lib.php');
        $csv = new parseCSV();

        // set the filename to read CSV from
        // file path is /webroot/files/upload/file/$filename
        $filename = WWW_ROOT  . 'files' . DS . 'upload' . DS . 'file' . DS . $filename;

		//pottentially used to check CSV validididididi-p-diddy-ity?!?!?!? TODO
		//$csv->limit = 3;

		//auto detect deliminater/parse .csv file
		//data is available by $csv->data;
		//head is available by $csv->titles;
		$csv->auto($filename);

		//set blank return array
		$return = array(
		    'messages' 	=> array(),
		    'errors' 	=> array()
		);

		//set blank arrays / counters
		$reactiveUsersArray = $csvDiffereceFinal = $updatedUsersProfiles = $allDBUsersDetailsFormat = $allActiveDBUsersList = $allDBUsersList = $newProfilesFinalArray = $dbUsersInUpload = $newUsersFinalArray = $allCsvUsers = $employeeIDsArray = $regionsArray = $storesArray = $jobTitlesArray = $regionsFinalArray = $storesFinalArray = $jobTitlesFinalArray = array();
		$newUserCount = $regionCount = $jobTitlesCount = $employeeCounter = 1;

		//load necessary models!
		$userModel	 	= ClassRegistry::init('User');
		$profileModel  	= ClassRegistry::init('Profile');
		$regionModel 	= ClassRegistry::init('Region');
		$storeModel 	= ClassRegistry::init('Store');
		$jobTitlesModel = ClassRegistry::init('JobTitle');

		//get ALL Stores
		$allStores 		= $storeModel->find('list');

		//get ALL users and their assoaciated profile but limit the fields
		//[User] =>, [Profile]
		$allDBUsersDetails = $userModel->find(
			'all', array(
				'contain'	=> array('Profile'),
				'fields'	=> array(
					'User.id',
					'User.username',
					'User.is_active',
					'Profile.id',
					'Profile.user_id',
					'Profile.store_id',
					'Profile.job_title_id',
					'Profile.first_name',
					'Profile.surname',
					'Profile.original_hire_date'
				)
			)
		);


		$allNewJobTitles = $allJobTitles = $jobTitlesModel->find(
			'list'
		);


		//-------------------------------------------------
		//loop through each user in DB
		//-------------------------------------------------
		foreach($allDBUsersDetails as $id => $userProfile){

			//if($userProfil)
			//set variables
			$userDBUserName = $userProfile['User']['username'];
			$userDBUserID	= $userProfile['User']['id'];

			//build another array of users, in format: [$username] => $DBID
			$allDBUsersList[$userDBUserName] = $userDBUserID;

			if($userProfile['User']['is_active'] == true){

				$allActiveDBUsersList[$userDBUserName] = $userDBUserID;
			}

			//build a temp array to push
			$tmpProfileArray = array(
				'id'					=> $userProfile['Profile']['id'],
				'employeeID'			=> $userDBUserName,
				'first_name' 			=> $userProfile['Profile']['first_name'],
				'surname' 				=> $userProfile['Profile']['surname'],
				'job_title_id'			=> $userProfile['Profile']['job_title_id'],
				'store_id'				=> $userProfile['Profile']['store_id'],
				'original_hire_date' 	=> $userProfile['Profile']['original_hire_date'],
				'is_active'				=> $userProfile['User']['is_active']
			);


			//set new array of DB users w/ details
			//unset array after processing

			$allDBUsersDetailsFormat[$userDBUserName] = $tmpProfileArray;
			unset($allDBUsersDetails[$id]);
		}

		//-------------------------------------------------
		//loop through each csv row! (excluding header row)
		//-------------------------------------------------
		foreach($csv->data as $id => $data){

			//check to see if Employee Number / User ID / HRMS Number is less than 2
			//if it is less than 2, it's obviously blank so ignore
			if(strlen($data['Employee Number']) < 2){

				//skip current iteration
				continue;
			}

			//temp arrays which are used once.
			$tmpStoreArray = array();

	/********************************************
		 EMPLOYEE logic/details
	********************************************/

			//trim whitespace either side
			$employeeID 		= trim($data['Employee Number']);

			//start ID from 1 - no mo 0-based indexes!
			$employeeIDsArray[$employeeCounter] = $employeeID;

			//start trim white space from start and end, and any where there's more than 1 space
			//split names into first and last
			$employeeName 		= trim(preg_replace('/\s+/',' ', $data['Employee Name']));
			$employeeSplitnames	= explode(' ', $employeeName);
			$employeeFname 		= $employeeSplitnames[0];
			$getLastNameIndex	= (count($employeeSplitnames) - 1);
			$employeeSname   	= $employeeSplitnames[$getLastNameIndex];

			//format hire date to MYSQL DATETIME i.e. 2014-01-07 00:00:00
			$originalHireDate	= date("Y-m-d H:i:s", strtotime($data['Original Date Of Hire']." 00:00:00"));

	/********************************************
		 REGION logic/details
	********************************************/

			//trim whitespace either side of Region
			$regionValue			= trim($data['Region']);

			//split region value by space by only first space
			//works for "Region 1" and "Head Office"
			$regionDetails			= explode(' ', $regionValue, 2);

			//build array of regions - future proof incase more regions are added
			if(!in_array($regionValue, $regionsArray)) {

			    //build a 1-based array of regions to import
				$regionsArray[$regionCount] = $regionValue;

				//build temp array with correct Cake format
				$tmpRegionArray = array(
					'Region' => array(
						'id' 	=> $regionCount,
						'name' 	=> $regionValue
					)
				);

				//push to final regions array
				array_push($regionsFinalArray, $tmpRegionArray);

				//becuase 1-based array, increment it only on NEW value found in regionsArray
				$regionCount++;
			}

			//Set the regionIDKey to the 1-based array key of the array
			if( ($regionIDKey 	= array_search($regionValue, $regionsArray)) !== NULL);

	/********************************************
		 STORE/ORGANIZATION logic/details
	********************************************/

			//trim whitespace either side of Store/Department name
			$storeName			= trim($data['Organization Name']);

			//explode store name, limit by 2, i.e. limit name so it's 909=>'entire store name/department name'
			$storeDetails 		= explode(' ', $storeName, 2);
			$storeID			= $storeDetails[0];
			$storeName			= trim($storeDetails[1]);

			//ok, loop check to see if storeID is in storesArray
			//if it's not in array, add it.
			if(!array_key_exists($storeID, $storesArray)){

				//set/reset duplicateStoreCount as 1
				$duplicateStoreCount 	= 1;

				//trim white space around name (not inbetween just either side)
				//i.e. array([909]=>[Learning and Development])
				$storesArray[$storeID] 		= $storeName;

				$tmpStoreArray = array(
					'Store' => array(
						'id' => $storeID,
						'name' => $storeName,
						'region_id' => $regionIDKey
					)
				);

				//push to final stores array
				array_push($storesFinalArray, $tmpStoreArray);

			}else{

				//because multple departments/stores share the same ID
				//check whether the names are the same
				//i.e. 909 => Learning and Development AND 909 => Retail Learning and Development
				//because those names aren't the same
				if(!in_array($storeName, $storesArray)){

					//because more than 2 departments share the same ID
					//append A -or- B -or- C -or- D, etc, to each unique department
					switch($duplicateStoreCount){
						case (1):
							$duplicateStoreID = 'A';
						break;
						case (2):
							$duplicateStoreID = 'B';
						break;
						case (3):
							$duplicateStoreID = 'C';
						break;
						case (4):
							$duplicateStoreID = 'D';
						break;
						case (5):
							$duplicateStoreID = 'E';
						break;
						case (6):
							$duplicateStoreID = 'F';
						break;
						case (7):
							$duplicateStoreID = "G";
						break;
						case (8):
							$duplicateStoreID = 'H';
						break;
						case (9):
							$duplicateStoreID = "I";
						break;
						case (10):
							$duplicateStoreID = 'J';
						break;
						case (11):
							$duplicateStoreID = 'K';
						break;
						case (12):
							$duplicateStoreID = 'L';
						break;
						case (13):
							$duplicateStoreID = "M";
						break;
						case (14):
							$duplicateStoreID = 'N';
						break;
						case (15):
							$duplicateStoreID = "O";
						break;
						case (16):
							$duplicateStoreID = 'P';
						break;
					}

					//increment store counter by 1 for each unique department with a shared ID
					$duplicateStoreCount++;

					//ok, append the duplicateStoreID count (a,b,c, etc.) to the store ID
					$storeID 				= $storeID . $duplicateStoreID;

					//add new $storeID => Storename to array
					$storesArray[$storeID] 	= $storeName;

					//build array in Cake format
					$tmpStoreArray = array(
						'Store'=>array(
							'id'=>$storeID,
							'name'=>$storeName,
							'region_id'=>$regionIDKey
						)
					);

					//add new temp store information to main store information
					array_push($storesFinalArray, $tmpStoreArray);
				}
			}


	/********************************************
		 JOB TITLE logic/details
	********************************************/
			$jobTitle			= trim($data['Job Title']);

			/*
				HERE
			*/
			if(in_array($jobTitle, $allJobTitles)) {

				$jobTitleIDKey = array_search($jobTitle, $allJobTitles);
			}else{

				if(!in_array($jobTitle, $allNewJobTitles)){

					if(empty($allJobTitles)){
						//echo $jobTitle;
						array_push($allNewJobTitles, $jobTitle);
					}

				}

				$jobTitleIDKey = array_search($jobTitle, $allNewJobTitles);
			}


			//build another array of all CSV users with the necessary database fields
			$allCsvUsers[$employeeID] = array(
				'employeeID'			=> $employeeID,
				'first_name'			=> $employeeFname,
				'surname'				=> $employeeSname,
				'job_title_id' 			=> $jobTitleIDKey,
				'store_id'				=> array_search($storeName, $storesArray),
				'original_hire_date' 	=> $originalHireDate,
				'is_active'				=> true
			);

			//increment counter [1], [2], etc.
			$employeeCounter++;

			//unset data iteration
			unset($data);

		//EO-ForEach ($csv->data)
		}

		//unset csv data variable
		unset($csv->data);

		//-----------
		// check to see if user is current in DB
		// if in DB, compare current record with CSV record - update if they don't match
		// if in DB but not in CSV, we're going to deactive those users i.e. not delete them.
		// if not in DB, insert it into DB
		//-----------

		//flip array so it's array([Username]=>[userIDKey])
		$employeeIDsArray = array_flip($employeeIDsArray);

		//loop through each user in CSV
		foreach($employeeIDsArray as $userName => $userIDKey){

			//cache user csv row
			$csvUserDetails = $allCsvUsers[$userName];

			//unset csv row
			unset($allCsvUsers[$userName]);

	/********************************************
		 DEFINE APPLICATION ADMINS BY HRMS Number
	********************************************/

			$applicationAdmins = explode(',', Configure::read('APP_ADMINS'));

			//get user role
			//if is an admin, role = admin, else role = user
			$userRole = (in_array($userName, $applicationAdmins) ? 'admin' : 'user');

	/********************************************
		 UPDATE ROW
	********************************************/

			//if CSV User is in DB i.e. an already exisitng user i.e. UPDATE
			//compare their current profile, with their new details in the CSV
			//to see if there's any updates
			if(isset($allDBUsersList[$userName])){


				//build array of users who are found in DB
				//this is used when comparing the different between the users in the DB and then users in the CSV
				//to check to see users who are no longer in the business
				$dbUsersInUpload[$userName] = $userIDKey;

				//because there's no DB field in CSV, check (just in case)
				if(isset($allDBUsersDetailsFormat[$userName]['id'])){

					//cache it brah
					$profileID 	= $allDBUsersDetailsFormat[$userName]['id'];

					//temporay remove from main DB array
					//otherwise it will compare it and because there's no id in the csv file, it'll always be a difference
					unset($allDBUsersDetailsFormat[$userName]['id']);
				}

				//compare the difference between the CSV row and the the DB row
				$csvDifference = array_diff($csvUserDetails, $allDBUsersDetailsFormat[$userName]);

				if(!empty($csvDifference)){
					// pr($allDBUsersDetailsFormat[$userName]);
					// echo '------';
					// pr($csvUserDetails);
				}

				//if there IS a difference between the two.. i.e. the user has changed
				//store, name, job title, etc.
				if(!empty($csvDifference)){

					//just a check to see we don't get any dodgy errors about $profileID not being set
					if(!empty($profileID)){

						//re-add the temporary removed id from the main DB array
				 		$csvDifference['id'] 			= $profileID;

				 		//if user is not active(is_active => 0) in DB
				 		//but appears in CSV (thereoretically is_active => 1)
				 		if(isset($csvDifference['is_active'])){

				 			//unset is_active from array (otherwise it'll merge it into [Profile]=>)
				 			//when it's a [User]=> attribute
				 			unset($csvDifference['is_active']);

				 			//create necessary [User] array for extra
				 			$reactiveUserArray['User']['id'] 		= $allDBUsersList[$userName];
				 			$reactiveUserArray['User']['is_active'] = true;

				 			//push reactived user to final reactivation array
				 			array_push($reactiveUsersArray, $reactiveUserArray);

				 		}

				 		//build another array so it's in correct Cake conventions i.e. [Model] => [fields]
				 		$csvDiffereceFinal['Profile'] 	= $csvDifference;
					}

					//add changed row to main array for edit/update later on
					array_push($updatedUsersProfiles, $csvDiffereceFinal);
				}

			}else{

	/********************************************
		 INSERT ROW
	********************************************/
				//else if they aren't in the DB
				//i.e. they're a NEW/INSERT record

				//Build their default password which is in format: firstname.surname - all LOWERCASE i.e. kingsley.raspe
				$userFNameSName = strtolower($csvUserDetails['first_name'].".".$csvUserDetails['surname']);

				//user CakeAuth to generate their hashed password
				//MOVED TO USER MODEL -> BEFORESAVE();
				$userPassword 	= $userFNameSName;

				//build a new [User] array with their username (HRMS),
				//their hashed password, and their current system role (Admin/User)
				$newUserArrayTmp = array(
					'User' => array(
						'username' 				=> $userName,
						'password' 				=> $userPassword,
						'role'					=> $userRole,
						'is_active'				=> true
					)
				);

				//build a new [Profile] array with their necessary info the CSV
				$newProfileArrayTmp = array(
					'Profile' => array(
						'user_id'				=> $userIDKey,
						'store_id'				=> $csvUserDetails['store_id'],
						'job_title_id'			=> $csvUserDetails['job_title_id'],
						'first_name'			=> $csvUserDetails['first_name'],
						'surname'				=> $csvUserDetails['surname'],
						'original_hire_date' 	=> $csvUserDetails['original_hire_date']
					)
				);

				//Push newUserArray to Final Users' array, same with Profile!
				array_push($newUsersFinalArray, $newUserArrayTmp);
				array_push($newProfilesFinalArray, $newProfileArrayTmp);
			}
		}

	/********************************************
		USERS TO DEACTIVATE FROM DB (not in CSV anymore)
	********************************************/

		//compare array keys (which is Users' username i.e. HRMS number)
		//between ALL current DB Users, and Users who were found in csvUpload
		$deactiveUsers = array_diff_key($allActiveDBUsersList, $dbUsersInUpload);



		//blank arrays, brah!
 		$deactiveUserArray = $deactivedUserIDArray = $deactiveBookingsArray  = array();

 		//loop through each deactived user
		foreach($deactiveUsers as $username => $deactivedUserID):

			//push deactived user ID to final deactived user ID array
			array_push($deactivedUserIDArray, $deactivedUserID);

			//build User array using dem cake conventions, ya get me fam
			$tmpDeactiveUserArray = array(
				'User' => array(
					'id'		=> $deactivedUserID,
					'is_active' => 0
				)
			);

			//push temp array to final deactivated user array
			array_push($deactiveUserArray, $tmpDeactiveUserArray);
		endforeach;

		//get ALL bookings for ALL deactived users (i.e. users who are in DB but not CSV)
		//but where their booking isn't ALREADY deactivated
		$deactiveUserBookings 	= 	$profileModel->Booking->find(
										'list', array(
											'conditions' => array(
												'Booking.profile_id' 			=> $deactivedUserIDArray,
												'NOT' => array(
  													'Booking.booking_status_id' => 19
													)
											)
										)
									);

		//loop through each deactived user booking
		//that isn't already reactivated
		foreach($deactiveUserBookings as $id => $userBookingID):

			//build temp array
			$tmpDeactiveBookingArray 	= array(
				'Booking' => array(
					'id' 				=> $userBookingID,
					'booking_status_id' => 19 //19 = left the business
				)
			);

			//push temp deactived booking to final array
			array_push($deactiveBookingsArray, $tmpDeactiveBookingArray);

		endforeach;






		$newJobTitles = array_diff($allNewJobTitles, $allJobTitles);

		foreach($newJobTitles as $newJobTitleID => $newJobTitle){

			$tmpJobTitleArray = array(
				'JobTitle' => array(
					'id' 	=> $newJobTitleID,
					'title' => $newJobTitle
				)
			);
			array_push($jobTitlesFinalArray, $tmpJobTitleArray);
		}

		//pr($jobTitlesFinalArray);


		//Generate counts!
		$deactivedUsersCount 	= count($deactiveUsers);
		$deactivedBookingsCount = count($deactiveBookingsArray);
		$reactiveUserCount		= count($reactiveUsersArray);
		$newUsersCount			= count($newUsersFinalArray);
		$updatedProfileCount	= count($updatedUsersProfiles);
		$storeCount 			= count($storesFinalArray);
		$regionCount			= count($regionsFinalArray);
		$jobTitleCount			= count($jobTitlesFinalArray);





	/********************************************
		SAVE DAT DATA
	********************************************/


		//regions
		//just like stores above
		if(!empty($regionsFinalArray)){
			$regionModel->query('TRUNCATE regions;');
			if($regionModel->SaveAll($regionsFinalArray)){
				$return['messages'][] 	= 'Regions successfully updated ('.$regionCount.' total regions) ';
			}else{
				$return['errors'][] 	= "Regions import was unsucessful. Please contact $adminName <$adminEmail> ASAP.";
			}
		}



		//stores
		//Application always removes stores (truncates the tables)
		//because stores and regions move around (or get added)
		//this way, application is self-learning(not learning, but it doesn't need any store/region management from user)
		if(!empty($storesFinalArray)){
			$storeModel->query('TRUNCATE stores;');
			if($storeModel->SaveAll($storesFinalArray)){
				$return['messages'][] 	= 'Stores successfully updated ('.$storeCount.' total stores) ';
			}else{
				$return['errors'][] 	= "Stores import was unsucessful. Please contact $adminName <$adminEmail> ASAP.";
			}
		}





		//reactivaded users
		//i.e. users who were deactived (weren't in CSV once upon a time)
		//but have since came back
		if(!empty($reactiveUsersArray)){
			if($userModel->SaveAll($reactiveUsersArray)){
				$return['messages'][] 	= $reactiveUserCount.' users reactivated';
			}else{
				$return['errors'][] 	= "Failed to reactive users. Please contact $adminName <$adminEmail> ASAP.";
			}
		}

		//deactivate a users
		//users who don't appear in CSV
		if(!empty($deactiveUserArray)){
			if($userModel->SaveAll($deactiveUserArray)){
				$return['messages'][] 	= $deactivedUsersCount.' users deactivated';
			}else{
				$return['errors'][] 	= "Users NOT deactivated. Please contact $adminName <$adminEmail> ASAP.";
			}
		}

		//deactivate a deactivated users bookings
		//change status id to 19
		if(!empty($deactiveBookingsArray)){
			if($profileModel->Booking->SaveAll($deactiveBookingsArray)){
				$return['messages'][] 	= $deactivedBookingsCount.' bookings deactivated';
			}else{
				$return['errors'][] 	= "Bookings NOT deactivated. Please contact $adminName <$adminEmail> ASAP.";
			}
		}


		//new users
		//users who are in CSV but not in DB
		//username/password/role/is_active
		if(!empty($newUsersFinalArray)){
			if($userModel->SaveAll($newUsersFinalArray)){
				$return['messages'][] 	= 'New users added sucessfully ('.$newUsersCount.' new users) ';
			}else{
				$return['errors'][] 	= "New users not added. Please contact $adminName <$adminEmail> ASAP.";
			}
		}


		//new user profiles
		//each profile is associated with 1 user
		//firstname/surname/jobtitle/store
		if(!empty($newProfilesFinalArray)){
			if($profileModel->SaveAll($newProfilesFinalArray)){
				$return['messages'][] 	= 'New user profiles added sucessfully.';
			}else{
				$return['errors'][] 	= "New user profiles import was unsucessful. Please contact $adminName <$adminEmail> ASAP.";
			}
		}


		//users whose details don't match the CSV
		//this could be a change in name/job title/ store, etc
		if(!empty($updatedUsersProfiles)){
			if($profileModel->SaveAll($updatedUsersProfiles)){
				$return['messages'][] 	= 'Existing user details updated successfully ('.$updatedProfileCount.' updated users) ';
			}else{
				$return['errors'][] 	= "Existing user details update was unsucessful. Please contact $adminName <$adminEmail> ASAP.";
			}
		}

		// jobtitles
		// just like stores above
		if(!empty($jobTitlesFinalArray)){
			//$jobTitlesModel->query('TRUNCATE job_titles;');
			if($jobTitlesModel->SaveAll($jobTitlesFinalArray)){
				$return['messages'][] 	= 'Job Titles successfully updated ('.$jobTitleCount.' total job titles) ';
			}else{
				$return['errors'][] 	= "Job Titles import was unsucessful. Please contact $adminName <$adminEmail>ASAP.";
			}
		}

		//return $return object of messages and any problems
		return $return;

	//EO-process
	}

//EOF
}
?>