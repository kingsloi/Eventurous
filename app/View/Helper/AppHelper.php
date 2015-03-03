<?php

App::uses('Helper', 'View');

class AppHelper extends Helper {

    public function calculateTense($eventStart, $eventFinish){
        $dateNow = date("Y-m-d H:i:s");
        if($eventStart > $dateNow){
            return "future-event";
        }elseif($eventStart < $dateNow && $eventFinish > $dateNow){
            return "in-progress animated";
        }elseif($eventFinish < $dateNow){
            return "finished";
        }
    }



    public function formatBigNumbers($number){
        if($number > 99){
            return '99+';
        }else{
            return $number;
        }
    }



    // public function niceifyBookingStatus($statusID){
    //     //$this-> $booking['BookingStatus']['id']
    // }


    public function formatStatus($statusID, $statusText, $makeLink = true) {

        /*
        * IDs relate to booking_status_id
            1 = Unconfirmed
            2 = Approved
            3 = Cancelled
            4 = Date Changed
            5 = Rejected
            6 = Incomplete
            7 = Complete
        */
            $colourClass = $this->getStatusColour($statusID);

            $label = "<span class='label label-$colourClass'>$statusText</span>";
        if($makeLink){
            return "<a href='/admin/reports/booking/status/'>$label</a>";
        }else{
            return $label;
        }
    }

    public function getStatusColour($bookingStatusID){
        switch($bookingStatusID){
            case 1:
            case 2:
                return 'warning';
                break;
            case 7:
            case 13:
            case 8:
            case 9:
            case 16:
                return 'success';
                break;
            case 14:
            case 3:
            case 10:
            case 11:
            case 5:
            case 15:
            case 17:
            case 18:
            case 6:
            case 19:
            case 20:
                return 'danger';
                break;
            case 4:
            case 12:
                return 'info';
                break;
        }
        return $class;
    }


    public function getBookingStatusColour($statuses){
        $statusArray    = array();
        foreach ($statuses as $status => $statusQty){
            $class = $this->getStatusColour($status);
            $statusArray[$status] = $class;
        }
        return $statusArray;
    }


    public function getCoursesList(){
        return $this->getCoursesList();
       //$this->set('courses', $coursesList);
    }
	public function formatDatesPretty($dateToFormat, $includeTime = true, $allDay = false){
		
        $timeFormat = '';
		if($includeTime == true){
            
            $timeFormat = 'H:i';
		}
        if($allDay == true){

            $timeFormat = '';
        }

		if(!empty($dateToFormat)){
            
			return date("d-m-Y $timeFormat", strtotime($dateToFormat));
		}
		
	}

    public function formatJobTitle($jobTitleID, $jobTitleText, $makeLink = true) {

        $jobTitleTextArray = (explode('|', $jobTitleText));
    	switch(strtolower($jobTitleTextArray[0])){
    		case 'field':
            case 'sales':
            case 'direct':
    			$colourClass     = '#19D7E2';
    		break;
    		case 'commercial':
            case 'finance':
            case 'ops':
            case 'property':
            case 'retail ops':
            case 'logistics':
            case 'supply chain':
    			$colourClass     = '#5E0BE2';
    		  break;
    		case 'hr':
    			$colourClass     = '#E9BC37';
    		break;
            case 'directorate':
                $colourClass     = '#19A015';
                break;
            case 'it':
            case 'marketing':
                $colourClass     = '#EA3C3C';
                break;
            case 'projects':
            case 'finance':
                $colourClass     = '#1C55D8';
                break;
    		default:
    			$colourClass     = '#737373';
    	}

    	$label = "<span class='label' style='background-color:$colourClass'>$jobTitleText</span>";
    	if($makeLink){

    		return "<a href='/admin/reports/jobtitles/'>$label</a>";
    	}else{
            
    		return $label;
    	}
    }





}
