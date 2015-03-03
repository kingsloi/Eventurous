<?php
App::uses('AppController', 'Controller');

class BookingCoursesController extends AppController {
	
	public $components = array('Paginator');

/*------------------------------------------------------------------------------------------------------------
 * listCategoryCourses(int)
 * 
 * used on /categories page, allows user to see courses in a category
-----------------------------------------------------------------------*/
	public function listCategoryCourses($categoryID) {

		//set the necessary categoryID session
		$this->Session->write('categoryID', $categoryID);

		//if they land on the category page,
		//delete eventID/courseID to prevent problems
		$this->Session->delete('eventID');
		$this->Session->delete('courseID');

		//find all COURSES in that category
		$courses = $this->BookingCourse->getAllCategoryCoursesAndEvents($categoryID);

		//set those courses for the view!
		$this->set('courses', $courses);

		//get the coursename
		if(!empty($courses)){

			$title_for_layout = $courses[0]['CourseCategory']['name'];
		}else{

			$title_for_layout = 'Courses';
		}

		//set page <title>
		$this->set('title_for_layout', $title_for_layout);
	}


/*------------------------------------------------------------------------------------------------------------
 * admin_listCategoryCourses(int)
 * 
 * used on admin dashboard categories page
-----------------------------------------------------------------------*/
	public function admin_listCategoryCourses($categoryID) {

		//Get category name
		$courseCategory		= 	$this->BookingCourse->CourseCategory->find('first', array('conditions'=>array('CourseCategory.id'=>$categoryID),'fields'=>array('id','name')));
		
		//find all courses in category
		$courses 			= 	$this->BookingCourse->find('all',
									array(
										'fields'=>array(
											'id','name'
										),
										'conditions'=>array(
											'course_category_id'=>$categoryID
										)
									)
								);
		//set view variables
		$categoryName		= 	$courseCategory['CourseCategory']['name'];
		$title_for_layout 	= 	$categoryName. ' courses';

		$this->set('courses', $courses);
		$this->set('categoryName', $categoryName);
		$this->set('categoryID', $categoryID);
		$this->set('title_for_layout', $title_for_layout);
	}


/*------------------------------------------------------------------------------------------------------------
 * admin_add(int)
 * 
 * used on adding course. if category is set, this is automatically selected in view (in <select>)
-----------------------------------------------------------------------*/

	public function admin_add($categoryID = null) {

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->BookingCourse->create();

			if ($this->BookingCourse->save($this->request->data)) {
				
					$newCourseID = $this->BookingCourse->id;

					/*TODO:
						
						1) Need to figure out best way to set up functionalty for nomination booking reasons (i.e. MDP Level l Success/Promotion)
						2) Ability to add other reasons
					*/
					$bookingReason = array(
						'BookingCoursesBookingReason' => array(
							'booking_course_id' => $newCourseID,
							'booking_reason_id' => '3' //3 == 'None' - user isn't allow to add booking reasons
						)
					);

					//create a new HasManyAndBelongsToMany field for new CourseReason
					$this->BookingCourse->BookingCoursesBookingReason->create();
					$this->BookingCourse->BookingCoursesBookingReason->save($bookingReason);

				$this->Session->setFlash('Course added sucessfully. Add some events :)', 'alert-success');
				$this->redirect(array('action' => 'admin_manage'));
			} else {

				$this->Session->setFlash(__('The course category could not be saved. Please, try again.'), 'alert-error');
			}
		} else {

			$courseTypes 		= $this->BookingCourse->CourseType->find('list');
			$courseCategories 	= $this->BookingCourse->CourseCategory->find('list');
			$bookingReasons 	= $this->BookingCourse->BookingReason->find('list');

			/*
				TODO
				1) Fix booking reasons/course types
			*/

			//unset nomination reasons
			unset($bookingReasons[1]); //mdp level 1 promotion
			unset($bookingReasons[2]); //mdp level 2 succession

			//unset nomiation type
			unset($courseTypes[1]);		//nomination

			$this->set('bookingReasons', $bookingReasons);
			$this->set('courseTypes', $courseTypes);
			$this->set('courseCategories', $courseCategories);

			if(isset($categoryID)){$this->set('categoryID',$categoryID);}
		}
	}

/*------------------------------------------------------------------------------------------------------------
 * admin_edit(int)
 * 
 * used on editing course.
-----------------------------------------------------------------------*/
	public function admin_edit($id) {
		
		if (!$this->BookingCourse->exists($id)) {
			
			throw new NotFoundException(__('Invalid course.'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			if ($this->BookingCourse->saveAssociated($this->request->data)) {

				$this->Session->setFlash(__('Course sucessfully updated!'), 'alert-success');
				$this->redirect(array('action' => 'admin_manage'));
			} else {

				$this->Session->setFlash(__('Couse could not be saved. Please try again.'), 'alert-error');
			}
		} else {

			$options = array('contain'=>'BookingReason','conditions' => array('BookingCourse.' . $this->BookingCourse->primaryKey => $id));
			$this->request->data 	= $this->BookingCourse->find('first', $options);
			
			$courseTypes 			= $this->BookingCourse->CourseType->find('list');
			$courseCategories 		= $this->BookingCourse->CourseCategory->find('list');
			$this->set('courseTypes',$courseTypes);
			$this->set('courseCategories',$courseCategories);


		}
	}


/*------------------------------------------------------------------------------------------------------------
 * admin_delete(int)
 * 
 * used on deleting category.
-----------------------------------------------------------------------*/
	public function admin_delete($id) {

		if (!$this->request->is('post')) {

			throw new MethodNotAllowedException();
		}

		$this->BookingCourse->id = $id;

		if (!$this->BookingCourse->exists()) {

			throw new NotFoundException(__('Invalid course category'));
		}
		if ($this->BookingCourse->delete($id)) {

			$this->Session->setFlash(__('Course deleted'), 'alert-success');
			$this->redirect(array('action' => 'admin_manage'));
		}

		$this->Session->setFlash(__('Course was not deleted'), 'alert-error');
		$this->redirect(array('action' => 'admin_manage'));
	}


/*------------------------------------------------------------------------------------------------------------
 * admin_manage(int)
 * 
 * used on managing category.
-----------------------------------------------------------------------*/
	public function admin_manage($categoryID = null) {
		
		//if a categoryID is fed to function
		//display courses within that category
		if(isset($categoryID)){

			//get ALL courses from categoryID
			$courses = $this->BookingCourse->find('all', array(
				'order'			=> array('BookingCourse.order ASC'),
				'conditions'	=> array('BookingCourse.course_category_id' => $categoryID)
				)
			);	

			$categoryName = $this->BookingCourse->CourseCategory->field(
			    'name', array(
			    	'id' => $categoryID)
			);
			
			//set courses variables to view
			$this->set('categoryName', $categoryName);
			$this->set('courses', $courses);
			$this->set('categoryID', $categoryID);	

		}else{

			//if no categoryID is fed to function
			//display ALL categories
			$categories = $this->BookingCourse->CourseCategory->find('all', array(
				'order'	=> array('CourseCategory.order ASC')
				)
			);	

			//set Categories variables to view
			$this->set('categories', $categories);
		}

	}

}