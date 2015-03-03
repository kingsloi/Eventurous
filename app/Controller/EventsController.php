<?php
App::uses('AppController', 'Controller');

class EventsController extends AppController {

public $components = array('Paginator');

/*------------------------------------------------------------------------------------------------------------
 *  Admin Manage(int, int)
 *
 *	/admin/manage/events
-----------------------------------------------------------------------*/
	public function admin_manage($categoryID = null, $courseID = null) {

		//if a categoryID is fed to function
		//display courses within that category
		if(isset($categoryID)){

			//get ALL courses from categoryID
			$courses = $this->Event->BookingCourse->find('all', array(
				'order'			=> array('BookingCourse.order ASC'),
				'conditions'	=> array('BookingCourse.course_category_id' => $categoryID)
				)
			);	

			$categoryName = $this->Event->BookingCourse->CourseCategory->field(
			    'name', array(
			    	'id' => $categoryID)
			);

			//set category ID
			$this->set('categoryName', $categoryName);
			$this->set('categoryID', $categoryID);				

			//if course ID is set
			//i.e. they're in a course!
			if(isset($courseID)){
				
				//get all events in that course
				$events = $this->Event->find('all', array(
					'order'			=> array('Event.event_start ASC'),
					'conditions'	=> array('Event.booking_course_id' => $courseID)
					)
				);	

				$courseName = $this->Event->BookingCourse->field(
			    	'name', array(
			    		'id' => $courseID)
				);

				//set courses variables to view
				$this->set('courseName', $courseName);
				$this->set('events', $events);
				$this->set('courseID', $courseID);
			}else{
				
				//set courses variables to view
				$this->set('courses', $courses);
			}

		}else{

			//if no categoryID is fed to function
			//display ALL categories
			$categories = $this->Event->BookingCourse->CourseCategory->find('all', array(
				'order'			=> array('CourseCategory.order ASC')
				)
			);	

			//set Categories variables to view
			$this->set('categories', $categories);
		}
	}


/*------------------------------------------------------------------------------------------------------------
 *  Admin Add(int)
 *
 *	/admin/events/add/1
-----------------------------------------------------------------------*/
	
	public function admin_add($courseID = null) {

		//if POSTd data
		if ($this->request->is('post')) {

			//create a new booking
			$this->Event->create();

			//attempt to save POSTd data
			if ($this->Event->save($this->request->data)) {

				//if successful, show success message, redirect to admin_manage view
				$this->Session->setFlash('The event has been saved.','alert-success');
				return $this->redirect(array('action' => 'admin_manage'));
			} else {

				//if failure to save, show failure message
				$this->Session->setFlash('The event could not be saved. Please, try again.','alert-danger');
			}
		}

		//post stuff to view
		$bookingCourses = $this->Event->BookingCourse->find('list');
		$this->set('courseID', $courseID);
		$this->set(compact('bookingCourses'));
	}


/*------------------------------------------------------------------------------------------------------------
 *  Admin edit(int)
 *
 *	/admin/events/edit/1
-----------------------------------------------------------------------*/
	
	public function admin_edit($id = null) {
		
		//check to see if event exists!
		if (!$this->Event->exists($id)) {

			//throw new error that event doesn't exist
			throw new NotFoundException(__('Invalid event'));
		}


		//if POST/Put
		if ($this->request->is('post') || $this->request->is('put')) {

			//Attempt to update/update event
			if ($this->Event->save($this->request->data)) {

				//if successful, show success, redirect to admin_manage
				$this->Session->setFlash('The event has been saved', 'alert-success');
				return $this->redirect(array('action' => 'admin_manage'));
			} else {

				//if failure show failure, redirect to same function
				$this->Session->setFlash('The event could not be saved. Please, try again.', 'alert-error');
			}
		} else {

			//if not post, set requet data to field in DB
			$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
			$this->request->data = $this->Event->find('first', $options);
		}

		//send variables to view
		$bookingCourses = $this->Event->BookingCourse->find('list');
		$this->set(compact('bookingCourses'));
	}



/*------------------------------------------------------------------------------------------------------------
 *  Admin Delete(int)
 *
 *	/admin/events/delete/1
-----------------------------------------------------------------------*/
	
	public function admin_delete($id = null) {

		//Pass $id into Cake for editing
		$this->Event->id = $id;

		//check to see if event exists!
		if (!$this->Event->exists()) {

			//if not exists, throw error
			throw new NotFoundException('Invalid event');
		}

		//only allow posts/delete
		$this->request->onlyAllow('post', 'delete');

		//attempt to delete!
		if ($this->Event->delete()) {

			//if successful, show error message
			$this->Session->setFlash('The event has been deleted.', 'alert-success');
		} else {

			//if failure show failure message
			$this->Session->setFlash('The event could not be deleted. Please, try again.','alert-error');
		}

		//redirect to admin_manage
		return $this->redirect(array('action' => 'admin_manage'));
	}


}