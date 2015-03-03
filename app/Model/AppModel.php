<?php

App::uses('Model', 'Model');

class AppModel extends Model {
    //global stuff!
	public $recursive           = -1; // disable recursive
    public $actsAs              = array('Containable');


public function saveAssociated($data = null, $options = array()) {

    foreach ($data as $alias => $modelData) {

        if (!empty($this->hasAndBelongsToMany[$alias])) {

            $habtm = array();

            $Model = ClassRegistry::init($this->hasAndBelongsToMany[$alias]['className']);

            foreach ($modelData as $modelDatum) {

                if (empty($modelDatum['id'])) {

                    $Model->create();

                }

                $Model->save($modelDatum);

                $habtm[] = empty($modelDatum['id']) ? $Model->getInsertID() : $modelDatum['id'];                    

            }

            $data[$alias] = array($alias => $habtm);

        }

    }

    return parent::saveAssociated($data, $options);

}


    //----------------
    //  Gets inserted (created) IDs from DB
    //----------------
    var $newlyInsertedIDs = array();
    public function afterSave($created) {
        if($created) {
            
            $this->newlyInsertedIDs[] = $this->getInsertID();
        }
        return true;
    }


    //----------------
    //  Process changed data - only log CHANGED data and not everything
    //----------------
    public function processChangesToLog($type, $beforeUpdate, $updatedData){

        //set blank array
    	$newData = array();

        //obviously no need to log created/modified fields (because we already store that)
		unset($beforeUpdate[$type]['created']);
		unset($beforeUpdate[$type]['modified']);

        //loop through each field
    	foreach($beforeUpdate[$type] as $oldDataKey => $oldDataValue):

            //if old data exists in new data 
            //i.e. we're attempting to replace
    		if(isset($updatedData[$type][$oldDataKey])){                

                //store the value of the changed field
    			$newDataValue		= $updatedData[$type][$oldDataKey];

                //compare the new value against the old value
                //if it's changed, then log it brah
    			if($newDataValue !== $oldDataValue){

                    $newData[$type][$oldDataKey] = $newDataValue;
    			}
    		}
    	endforeach;

        //return the log!
		return $newData;
    }


    //----------------
    //  SendEmail global function!
    //----------------
    public function sendEmail($emailMessageObject, $templateToUse, $emailType){

        //set default email flag to false, 
        //only overwrite if we're good to send
        $sendEmailFlag          = false;

        //if deliverEmailTo isnt set
        if(!isset($emailMessageObject['deliverEmailTo'])){
            
            //write to log
            //return 
            CakeLog::write('emailFailed', 'No "TO:" email address. email= '.json_encode($emailMessageObject));
            return;
        }else{

            //else if it is deliverEmailTo is set
            $deliverEmailTo     = $emailMessageObject['deliverEmailTo'];

            //double check that email, brah
            //don't wanna be sending emails to fake address, and have them bounce back, ya feel?!
            if(!empty($deliverEmailTo) && filter_var($deliverEmailTo, FILTER_VALIDATE_EMAIL)){
                
                //override default false email flag to true if we're good to go
                $sendEmailFlag  = true;

                //set the necessary email type
                //and whatever email-type logic i.e. admin-edit, admin-delete, user-add, user-edit etc.
                switch($emailType):
                    case "booking-admin-edit":
                        $firstPersonSubject         = "Updates to your ".$emailMessageObject['BookingCourseDetails']['name']." ".$emailMessageObject['courseEvent']['eventName']. " booking.";
                        $thirdPersonSubject         = "There have been changes to ". $emailMessageObject['bookedFor']['fullname'] ."'s ". $emailMessageObject['BookingCourseDetails']['name'] ." booking";
                        break;
                    case "booking-user-add":
                        $emailSubject               = 'Your booking to '. $emailMessageObject['BookingCourseDetails']['name'].' '.$emailMessageObject['courseEvent']['eventName']. ' has been requested.';
                        break;
                endswitch;


                //decide who to send the email to
                //whether to send to person who booked
                //or the person who the booking is for
                if($emailMessageObject['emailOnBehalfOf'] == true){

                    $emailSubject       = $thirdPersonSubject;
                }else{

                     $emailSubject      = $firstPersonSubject;
                }

                //set the necessary template data to be used in the email template
                $data           = $emailMessageObject;

                //set the detault email options
                $options        = array(
                    'subject'   =>  $emailSubject,
                    'layout'    =>  'main',
                    'template'  =>  $templateToUse,
                    'from_name' =>  'Kingsley Raspe', 
                    'from_email'=>  'p4uelearning@outlook.com',
                    'config'    =>  'outlook'
                );
            }
        
        }

        //if we're good to go.. 
        //send the email to ze queue!
        if($sendEmailFlag){
            ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($deliverEmailTo, $data, $options);
        }
    }

}
