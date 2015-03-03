<?php
App::uses('AppController', 'Controller');

class CourseCategoriesController extends AppController {

	public $components = array('Paginator');

/*------------------------------------------------------------------------------------------------------------
 *  Show Categories
 *
 *	/categories page
-----------------------------------------------------------------------*/

	public function show_categories(){
		
		//set title for view
		$this->set('title_for_layout', 'Course Categories');
		
		//if on categories page
		//delete category ID & Course ID
		$this->Session->delete('categoryID');
		$this->Session->delete('courseID');
		
		//get ALL categories
		$categories = $this->CourseCategory->find(
			'all', array(
				'conditions'=> array(
					'CourseCategory.is_active' => 1 
				),
				'order'=>array(
					'CourseCategory.order ASC'
				)
			)
		);
		
		//send to the view
		$this->set('categories', $categories);
	}


/*------------------------------------------------------------------------------------------------------------
 *  Admin Manage
 *
 *	/admin/manage/coursecategories page
-----------------------------------------------------------------------*/

	public function admin_manage() {

		$categories = $this->CourseCategory->find(
			'all', array(
				'order'=>array(
					'CourseCategory.order ASC'
				)
			)
		);

		//send to the view
		$this->set('categories', $categories);
	}


/*------------------------------------------------------------------------------------------------------------
 *  admin_add
 *
 *	/admin/coursecategories/add
-----------------------------------------------------------------------*/
	
	public function admin_add() {

		//if POST
		if ($this->request->is('post')) {

			//create a new course
			$this->CourseCategory->create();

			//attempt to save data from form
			if ($this->CourseCategory->save($this->request->data)) {

				//if successful, show success, redirect to admin_manage
				$this->Session->setFlash('Category Saved. Now add some courses :)', 'alert-success');
				$this->redirect(array('action' => 'admin_manage'));
			}else{

				//if failure, show failure
				$this->Session->setFlash('Category not saved! Please try again.', 'alert-error');
			}
		}
	}


/*------------------------------------------------------------------------------------------------------------
 *  admin_edit($id)
 *
 *	/admin/coursecategories/edit
-----------------------------------------------------------------------*/
	
	public function admin_edit($id = null) {
		
		//check to see if Category exists
		if (!$this->CourseCategory->exists($id)) {
			
			//if not, throw new error
			throw new NotFoundException(__('Invalid course category'));
		}

		//if POST/PUT
		if ($this->request->is('post') || $this->request->is('put')) {
			
			//attempt to save
			if ($this->CourseCategory->save($this->request->data)) {
				
				//if success, show success, redirect to admin_manage
				$this->Session->setFlash(__('Category updated.'), 'alert-success');
				$this->redirect(array('action' => 'admin_manage'));
			} else {

				//if error, show failure
				$this->Session->setFlash(__('Category not updated! Please try again.'), 'alert-error');
			}
		} else {

			$options = array('conditions' => array('CourseCategory.' . $this->CourseCategory->primaryKey => $id));
			$this->request->data = $this->CourseCategory->find('first', $options);
		}
	}


/*------------------------------------------------------------------------------------------------------------
 *  admin_delete($id)
 *
 *	/admin/coursecategories/delete
-----------------------------------------------------------------------*/
	
	public function admin_delete($id = null) {

		if (!$this->request->is('post')) {

			throw new MethodNotAllowedException();
		}

		$this->CourseCategory->id = $id;

		if (!$this->CourseCategory->exists()) {

			throw new NotFoundException('Invalid course category');
		}
		if ($this->CourseCategory->delete($id)) {

			$this->Session->setFlash('Course category deleted', 'alert-success');
			$this->redirect(array('action' => 'admin_manage'));
		}

		$this->Session->setFlash('Course category was not deleted', 'alert-error');
		$this->redirect(array('action' => 'admin_manage'));
	}


}