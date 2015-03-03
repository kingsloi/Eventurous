<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $helpers = array('Markdown.Markdown','Minify.Minify');
    
/*------------------------------------------------------------------------------------------------------------
 *  components(int)
-----------------------------------------------------------------------*/
    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array(
                'controller'    => 'user', 
                'action'        => 'login'
                ),
            'logoutRedirect'    => array(
                'controller'    => 'user', 
                'action'        => 'login'),
            'authorize'         => array(
                'Controller')
            )
        );


/*------------------------------------------------------------------------------------------------------------
 *  afterFilter(int)
-----------------------------------------------------------------------*/
    public function afterFilter(){

        //Check to see if use has confirmed they say who they say they are 
        //i.e. clicked 'confirm' on /confirmation page
        if($this->Session->read('hasConfirmed') !== NULL){
            
            if($this->Session->read('hasConfirmed') == false){

                //only pages that don't require user to be confirmed
                $allUnconfirmedURLs  = array(
                    '/profile/edit',
                    '/logout',
                    '/login',
                    '/',
                    '/confirmation',
                    '/password-reset'
                );

                //get current URL i.e. /logout, /categories /admin/dasboard/course/1 etc
                $currentURL          = $this->here;

                //check to see if current page is not in array of allowed unconfirmable pages
                if(array_search($currentURL, $allUnconfirmedURLs) !== false){

                }else{

                    return $this->redirect('/confirmation');
                }
            }

        }
    }

/*------------------------------------------------------------------------------------------------------------
 *  _fetchSettings(int)
 *
 *  Used to apply settings in Settings DB table
-----------------------------------------------------------------------*/
    function _fetchSettings(){

        //loading model on the fly
        $this->loadModel('Setting');

        //fetching All settings
        $settings_array = $this->Setting->find('all');

        //loop through each setting
        foreach($settings_array as $key=>$value){

            //write setting Configure::write('APP_NAME',EVENTUROUS)
            Configure::write($value['Setting']['key'], $value['Setting']['pair']);
        }
    }


/*------------------------------------------------------------------------------------------------------------
 *  beforeFilter()
-----------------------------------------------------------------------*/
    public function beforeFilter() {

        //get Application settings!
        $this->_fetchSettings();

        //set BODY class to whatever is fed in controller to view
        //i.e. $this->set('bodyClass','confirm-on-exit');
        if(isset($bodyClass)){

            //set calss
            $this->set('bodyClass', $bodyClass);
        }

        //define role
        if($this->Auth->user('role') == 'admin') {

            $this->Auth->allow('*');
            $this->set('isAdmin', true);
        }elseif($this->Auth->user('role') == 'user'){

            $this->Auth->allow('index','view','add'); 
            $this->set('isAdmin', false);          
        }
        else{

            //$this->Auth->allow();
        }
    }


/*------------------------------------------------------------------------------------------------------------
 *  niceifyBookingStatus(bookingstatusid)
 *
 *  used for niceifying a booking status from rejected -> refused etc 
-----------------------------------------------------------------------*/
    public function niceifyBookingStatus($bookingStatusID){

        //Get various different ID for grouped status (i.e. delegate cancelled, admin cancelled)
        switch($bookingStatusID){
            case 1:

                return 'Awaiting Review from Course Leader';
                break;
            case 2:

                return 'Requires Review from Delegate';
                break;
            case 6:

                return 'Incomplete Application. Please add information.';
                break;
            case 7:
            case 13:

                return 'Complete/Passed';
                break;
            case 8:
            case 16:

                return 'Delegate set to attend course/event';
                break;
            case 9:

                return 'Deletegate currently attending course/event';
                break;
            case 14:

                return 'Please contact course leader';
                break;
            case 3:
            case 10:
            case 11:

                return 'Delegate Cancelled';
                break;
            case 5:
            case 15:
            case 17:
            case 18:

                return 'Course Leader Cancelled/Refused';
                break;
            case 4:
            case 12:

                return 'Date Changed/Booking Moved';
                break;
        }
    }



/*------------------------------------------------------------------------------------------------------------
 *  deactivated
 *
 *  on 31/01/14
 *  

    public function generateBreadCrumbs($includeHome, $crumbs){
        $breadcrumbs = array();
        if($includeHome === true){

            $homeBreadcrumb = 'home';
            array_unshift($crumbs, $homeBreadcrumb);
        }
        $this->set('breadcrumbs', $crumbs);
    }
-----------------------------------------------------------------------*/ 
    public function isAuthorized($user = null) {
        // Any registered user can access public functions
        if (empty($this->request->params['admin'])) {
            return true;
        }

        // Only admins can access admin functions
        if (isset($this->request->params['admin'])) {
            return (bool)($user['role'] === 'admin');
        }

        // Default deny
        return false;
    }

}
