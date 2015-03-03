<?php
App::uses('AppModel', 'Model');

class Question extends AppModel {

	public $displayField = 'question';

	public $belongsTo = array(
		'BookingReason' => array(
			'className' => 'BookingReason',
			'foreignKey' => 'booking_reason_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


/*
*	GET REVELENT QUESTIONS BASED ON BOOKING REASON
*/

	public function getBookingReasonQuestions($reasonID){
		$bookingQuestions = $this->find(
			'all', array(
				'conditions' => array(
					'Question.booking_reason_id' => $reasonID
				),
				'order' => 'Question.order ASC'
			)
		);
		return $bookingQuestions;	
	}


/*
*	CHECK TO SEE IF COURSE HAS OPTIONS ASSOCIATED TO ITS REASONS
*/
	public function doesCourseHavePossibleQuestions($bookingReasonsArray){
		$questions = $this->find('list', array('fields'=>array('booking_reason_id')));
		$bookingPossibleQuestions = array();
		foreach($questions as $id => $reasonID){
			if(array_key_exists($reasonID, $bookingReasonsArray)){
				array_push($bookingPossibleQuestions, $reasonID);
			}
		}
		if(!empty($bookingPossibleQuestions)){
			return true;
		}else{
			return false;
		}
	}
}
