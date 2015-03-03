<?php

ini_set("memory_limit","64M");
//https://github.com/josegonzalez/cakephp-upload

App::uses('AppController', 'Controller');

class UploadsController extends AppController {
    
    public $components = array('Paginator');


/*------------------------------------------------------------------------------------------------------------
 *  admin_index page:
 *
 *  this is the index page that handles the file upload &!!! initiates the import process
-----------------------------------------------------------------------*/

    public function admin_index() {

        //get username array
        $this->LoadModel('Profile');
        //$allUsersFullNames      = $this->Profile->getAllUsersFullNames();

        //if POST
        if ($this->request->is('post')) {            

            //if no file selected..
            if(empty($this->request->data['Upload']['file'])){
                
                $this->Session->setFlash('No File Selected.', 'alert-error');
                return;
            }

            //get filename of attempted uploaded file
            $fileName = $this->request->data['Upload']['file']['name'];

            //check to see if name already exists i.e. has already been uploaded
            $fileExistCheck = $this->Upload->find('all', array(
                'conditions' => array(
                    'Upload.file' => $fileName."aaa"
                )
            ));

            //if no record exists in db i.e. empty
            if(empty($fileExistCheck)){

                //create new Upload record
                $this->Upload->create();

                //attempt to save
                if ($this->Upload->save($this->request->data)) {
                   
                   //if successful
                    $type       = 'userImport';
                    $typeID     = $this->Upload->id;
                    $actionType = 'upload';
                    $dataToLog  = $this->request->data['Upload']['file']['name']. " uploaded";

                    //load HistoryLog model
                    $this->loadModel('HistoryLog');

                    //log changes
                    $this->HistoryLog->logAction($type, $typeID, $actionType, $dataToLog, $madeBy = null);

                    //set flash
                    $this->Session->setFlash('File successfully uploaded!', 'alert-success');
                } else {
                    
                    //if unsucessful
                    $this->Session->setFlash('There was a problem uploading the file. Please try again.', 'alert-error');
                }
            }else{

                //if find returns result
                //file already exists
                $this->Session->setFlash('File already exists!', 'alert-error');
            }

        }

        //Get all uploads with type text/csv
        $allUserCsvUploads = $this->Upload->find('all', array(
            'conditions' => array(
                //'Upload.type' => 'text/csv' 
            ),
            'order' => 'Upload.created DESC'
        ));

        //load HistoryLog model,
        //find all history logs with userImport and action = import
        //only get created and type_id fields
        $historyLogModel        = $this->loadModel('HistoryLog');


        //get all historyLogEntries
        // in array([type_id]=>[made_by])
        $allHistoryLogEntries      = $this->HistoryLog->find('list', array(
                'conditions' => array(
                    'HistoryLog.type'           => 'userImport',
                    'HistoryLog.type_action'    => 'import'
                ),
                'order'     => array('HistoryLog.created DESC'),
                'fields'    => array('HistoryLog.type_id','HistoryLog.made_by')
            )
        );

        //in array([type_id]=>[created])
        //TODO - could be imporved
        $historyLogEntries      = $this->HistoryLog->find('list', array(
                'conditions' => array(
                    'HistoryLog.type'           => 'userImport',
                    'HistoryLog.type_action'    => 'import'
                ),
                'order'     => array('HistoryLog.created DESC'),
                'fields'    => array('HistoryLog.type_id','HistoryLog.created')
            )
        );
        
        //loop through each csvUpload
        //also loop through each historyLogEntry and see if the uploadID = the type_id of the HistoryLog
        foreach($allUserCsvUploads as $id => $userCsvUpload){

            //get the csvUploadID
            $csvUploadID = $userCsvUpload['Upload']['id'];

            //convert username ids
            //in historyLog
            if(isset($allHistoryLogEntries[$csvUploadID])){
                
                $uploaderProfile = $this->Profile->findById($allHistoryLogEntries[$csvUploadID]);
                $allUserCsvUploads[$id]['Upload']['importedBy'] = $uploaderProfile['Profile']['fullname'];
            }

            //try and see if csvUploadID is present in historyLogEntries
            if (isset($historyLogEntries[$csvUploadID])) {

                //if it has been set, set the hasBeenImported value to the historyLog creation date 
                $allUserCsvUploads[$id]['Upload']['hasBeenImported'] = $historyLogEntries[$csvUploadID];
            }else{

                //if it's not found in the log
                //but if it's also older than the last upload (i.e. 2nd (or greater) uploaded file)
                if($id > 0){

                    //then set it to oldData
                    $allUserCsvUploads[$id]['Upload']['hasBeenImported'] = 'oldImportData';
                }
            }
            
        }

        //setUserCsvUploads
        $this->set('allUserCsvUploads', $allUserCsvUploads);

    }
    
/*------------------------------------------------------------------------------------------------------------
 *  admin_processUserImport($fileName, $fileID)
 *
 *  this method is called form a form which posts the filename & ID of the uploaded file inthe DB
-----------------------------------------------------------------------*/    
    public function admin_processUserImport($fileName, $fileID) {

        //no need for a view
        $this->layout = $this->autoLayout = $this->autoRender = false;

        //if post (post only..)
        if ($this->request->is('post') && !empty($fileName)) { 

            //PROCESS THIS SUCKAAA!!!!!!!! 
            $results = $this->Upload->processUserImport($fileName);

            if(!empty($results)){

                    //if successful
                    $type       = 'userImport';
                    $typeID     = $fileID;
                    $actionType = 'import';
                    $dataToLog  = $fileName. " uploaded";

                    //load HistoryLog model
                    $this->loadModel('HistoryLog');

                    //log changes
                    //$this->HistoryLog->logAction($type, $typeID, $actionType, $dataToLog, $madeBy = null);
            }

            //show success
            $this->Session->setFlash('No File Selected.', 'alert-with-messages', array('messages' => $results['messages'],'errors' => $results['errors']));
            $this->redirect(array('action' => 'admin_index'));
        }

    }

    public function admin_addFakeBookings(){
        //filltext.com/?rows=1000&profile_id={randomNumberRange|1to6400}&booking_reason_id={randomNumberRange|1to14}&booked_by_id={randomNumberRange|1to6400}&booking_status_id={randomNumberRange|1to18}&event_id={randomNumberRange|1to14}
        $jsonData   = file_get_contents('http://filltext.com/?rows=1000&profile_id={randomNumberRange|1to6050}&booking_reason_id=1&booked_by={randomNumberRange|1to6505}&booking_status_id={randomNumberRange|1to18}&event_id={randomNumberRange|1to13}');
        $jsonArray  = json_decode($jsonData, true);


        function fakebookingsgen($jsonData) {
            return $jsonData = array('Booking' => $jsonData);
        }

        $bookings = array_map("fakebookingsgen", $jsonArray); 
        $this->loadModel('Booking');
        if($this->Booking->SaveAll($bookings)){

        }else{
            pr($this->Booking->invalidFields());
        }
    }

function admin_database_mysql_dump($tables = '*') {

    $return = '';

    $modelName = $this->modelClass;

    $dataSource = $this->{$modelName}->getDataSource();
    $databaseName = $dataSource->getSchemaName();


    // Do a short header
    $return .= '-- Database: `' . $databaseName . '`' . "\n";
    $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";


    if ($tables == '*') {
        $tables = array();
        $result = $this->{$modelName}->query('SHOW TABLES');
        foreach($result as $resultKey => $resultValue){
            $tables[] = current($resultValue['TABLE_NAMES']);
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    // Run through all the tables
    foreach ($tables as $table) {
        $tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);

        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
        $createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
        $createTableEntry = current(current($createTableResult));
        $return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";

        // Output the table data
        foreach($tableData as $tableDataIndex => $tableDataDetails) {

            $return .= 'INSERT INTO ' . $table . ' VALUES(';

            foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

                if(is_null($dataValue)){
                    $escapedDataValue = 'NULL';
                }
                else {
                    // Convert the encoding
                    $escapedDataValue = mb_convert_encoding( $dataValue, "UTF-8", "ISO-8859-1" );

                    // Escape any apostrophes using the datasource of the model.
                    $escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
                }

                $tableDataDetails[$table][$dataKey] = $escapedDataValue;
            }
            $return .= implode(',', $tableDataDetails[$table]);

            $return .= ");\n";
        }

        $return .= "\n\n\n";
    }

    // Set the default file name
    $fileName = $databaseName . '-backup-' . date('Y-m-d_H-i-s') . '.sql';

    // Serve the file as a download
    $this->autoRender = false;
    $this->response->type('Content-Type: text/x-sql');
    $this->response->download($fileName);
    $this->response->body($return);
}

//EOF
}