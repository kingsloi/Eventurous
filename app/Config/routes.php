<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/login', array('controller' => 'users', 'action'=>'login'));
	Router::connect('/logout', array('controller' => 'users', 'action'=>'logout'));
	Router::connect('/confirmation', array('controller' => 'users', 'action'=>'confirmation'));
	Router::connect('/password-reset', array('controller' => 'users', 'action'=>'password_reset'));
	Router::connect('/password-reset/:hash', array('controller' => 'users', 'action'=>'password_reset'), array('pass' => array('hash')));



	Router::connect('/categories', array('controller' => 'coursecategories', 'action'=>'show_categories'));
	Router::connect('/category/:categoryID/courses', array('controller' => 'bookingcourses', 'action'=>'listCategoryCourses'), array('pass' => array('categoryID')));
	
	//Router::connect('/courses', array('controller' => 'bookingcourses', 'action'=>'listCourses'));
	
	Router::connect('/book-on/course/:courseid/event/:eventid', array('controller' => 'bookings','action' => 'setCourseAndEvent'), array('pass' => array('courseid', 'eventid'))); 
	Router::connect('/nominate-booking', array('controller' => 'bookings', 'action'=>'nominateBooking'));
	Router::connect('/booking-complete', array('controller' => 'bookings', 'action'=>'complete'));
	Router::connect('/add-additional-info', array('controller' => 'bookings','action' => 'additionalBookingInfo')); 

	/*
	*	REPORTING
	*/
	#Router::connect('/reports', array('controller' => 'bookings', 'action'=>'ListAllReports'));
	#Router::connect('/reports/bookings/all', array('controller' => 'bookings', 'action'=>'viewAllBookings'));
	#Router::connect('/reports/bookings/by/region/:id', array('controller' => 'bookings', 'action'=>'viewAllBookingsByRegion'), array('pass' => array('region_id')));
	#Router::connect('/reports/bookings/by/store/:id', array('controller' => 'bookings', 'action'=>'viewAllBookingsByStore'), array('pass' => array('store_id')));
	#Router::connect('/reports/bookings/by/user/:id', array('controller' => 'bookings', 'action'=>'viewAllBookings'));
	
	//profile 
	Router::connect('/profile/details', array('controller' => 'profiles', 'action'=>'view'));
	Router::connect('/profile/edit', array('controller' => 'profiles', 'action'=>'edit'));
		//User Bookings
		Router::connect('/bookings/previous', array('controller' => 'bookings', 'action'=>'previous_bookings'));
		Router::connect('/bookings/future', array('controller' => 'bookings', 'action'=>'future_bookings'));
		Router::connect('/bookings/thismonth', array('controller' => 'bookings', 'action'=>'this_month_bookings'));
		Router::connect('/bookings/cancelled', array('controller' => 'bookings', 'action'=>'cancelled_bookings'));
		Router::connect('/bookings/outforreview', array('controller' => 'bookings', 'action'=>'out_for_review_bookings'));


	Router::connect('/admin/dashboards', array('controller' => 'bookings', 'action'=>'dashboards','prefix'=>'admin','admin'=>true));
	Router::connect('/admin/dashboards/category/:categoryID/courses', array('controller' => 'bookingcourses', 'action'=>'listCategoryCourses','prefix'=>'admin','admin'=>true), array('pass' => array('categoryID')));
	Router::connect('/admin/dashboard/course/:courseid', array(
		'controller'=>'bookings', 
		'action'=>'dashboard_course',
		'prefix' => 'admin', 
		'admin' => true
	), array('pass' => array('courseid')));

	Router::connect('/admin/bookings/mass-edit/event/:eventID/status/:statusID', array('controller' => 'bookings', 'action'=>'massBookingsStatusEdit','prefix'=>'admin','admin'=>true), array('pass' => array('eventID', 'statusID')));

	Router::connect('/admin/bookings/mass-edit/event/:eventID', array('controller' => 'bookings', 'action'=>'massBookingsStatusEdit','prefix'=>'admin','admin'=>true), array('pass' => array('eventID')));


	Router::connect('/admin/manage/users', array('controller' => 'uploads', 'action'=>'index','prefix'=>'admin','admin'=>true));

	Router::connect('/admin/manage/:controller', array(
		'action'=>'manage',
		'prefix' => 'admin', 
		'admin' => true
	));

	Router::connect('/admin/manage/bookingcourses/category/:categoryID', array('controller' => 'bookingcourses', 'action'=>'manage', 'prefix'=>'admin', 'admin'=>true), array('pass' => array('categoryID')));

	Router::connect('/admin/manage/events/category/:categoryID', array('controller' => 'events', 'action'=>'manage', 'prefix'=>'admin', 'admin'=>true), array('pass' => array('categoryID')));
	Router::connect('/admin/manage/events/category/:categoryID/course/:courseID', array('controller' => 'events', 'action'=>'manage', 'prefix'=>'admin', 'admin'=>true), array('pass' => array('categoryID','courseID')));




	/*
	*	EXPORT BOOKINGS TO CSV
	*/
	Router::connect('/admin/export/bookings', array(
    		'controller'	=>'bookings',
    		'action' 		=> 'downloadBookings', 
    		'prefix' 		=> 'admin', 
    		'admin' 		=> true
	)); 

	/*
	*	GENERIC /admin/ * contoller / * action / * - method 
	*/
	Router::connect('/admin/:controller/:action/*', array(
	    			'action' => null, 'prefix' => 'admin', 'admin' => true
	)); 
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/frequently-asked-questions', array('controller' => 'pages', 'action' => 'faqs'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
