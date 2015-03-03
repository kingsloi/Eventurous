<?php
App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model');

class HistoryLog extends AppModel {

	public $useTable = 'history_log';

	public function logAction($type, $typeID, $typeAction, $data, $madeBy = null)
	{
		//set empty array to store log details in
		$newLogEventData 								= array();

		//assign necessary variables from function call to log details
		$newLogEventData['HistoryLog']['type'] 			= $type;
		$newLogEventData['HistoryLog']['type_id'] 		= $typeID;
		$newLogEventData['HistoryLog']['type_action'] 	= $typeAction;

		//json_encode the entire $this->data ($data) data
		$newLogEventData['HistoryLog']['data'] 			= json_encode($data);

		//future proofing:
		//if revision is not made by person who is logged in
		//then feed it as the last parameter of function call
		//else if left blank, it uses currently logged in user ID	
		if(empty($madeBy)){
			$loggedInUser								= CakeSession::read("Auth.User.Profile.id");
			$newLogEventData['HistoryLog']['made_by'] 	= $loggedInUser;
		}else{
			$newLogEventData['HistoryLog']['made_by'] 	= $madeBy;
		}
	

		//make a new historyLog record
		$newLogEvent = new HistoryLog();
		$newLogEvent->create();

		//if there's a problem saving
		//log error to historyLog.log file in /app/tmp/logs/historyLog.log
		if(!$newLogEvent->save($newLogEventData))
		{
			CakeLog::write('historyLog', 'Error saving history log. object= '.json_encode($newLogEventData));
		}
	}



	public function findHistory($recordType, $recordID){
		return $this->find('all', array('order'=>'HistoryLog.id DESC','conditions'=>array('HistoryLog.type'=>$recordType, 'HistoryLog.type_id'=>$recordID)));
	}



}
