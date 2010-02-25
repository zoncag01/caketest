<?php 
class UserController extends AppController 
{
	var $name = "Users";
	var $helpers = array('Html');
	
	function index() {
		// make sure we're logged in
		$this->checkSession();
		
		$this->Users->recursive = 0;
		$this->set('users', $this->User->findAll());
	}
	
	function edit($id = null) {
		
		// make sure we're logged in
		$this->checkSession();
		
		if(empty($this->data)) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for User');
				$this->redirect('/users/index');
			}
			$this->data = $this->User->read(null, $id);
		} else {
			$this->cleanUpFields();
			if($this->User->save($this->data)) {
				$this->Session->setFlash('The User has been saved');
				$this->redirect('/users/index');
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}
	
	function delete($id = null) {
		
		// make sure we're logged in
		$this->checkSession();
		
		if(!$id) {
			$this->Session->setFlash('Invalid id for User');
			$this->redirect('/users/index');
		}
		if($this->User->del($id)) {
			$this->Session->setFlash('The User has been deleted.');
			$this->redirect('/users/index');
		}
	}
	
	function add() 
	{
		// make sure we're logged in
		$this->checkSession();
		
			$this->set('username_error', 'Email must be valid.'); 
			if (!empty($this->data)) 
			{ 
				if ($this->User->validates($this->data)) 
				{ 
					if ($this->User->findByEmail($this->data['User']['email'])) 
					{ 
						$this->User->invalidate('email'); 
						$this->set('username_error', 'Email already exists.'); 
					} else { 
						$this->data['User']['password'] = md5($this->data['User']['password']); 
						if ($this->User->save($this->data)) 
						{
							$this->Session->setFlash('The User has been created.');
							$this->redirect('/users/index'); 
						} else { 
							$this->flash('There was a problem saving this information', '/users/add'); 
						} 
					} 
				} else { 
					$this->validateErrors($this->User); 
				} 
			}
	} 
	

	function login() 
	{ 
		$this->set('error', false); 
		if ($this->data) 
		{ 
			$results = $this->User->findByEmail($this->data['User']['email']); 
			if ($results && $results['User']['password'] == md5($this->data['User']['password'])) 
			{ 
				$this->Session->write('user', $this->data['User']['email']); 
				$this->Session->write('last_login', $results['User']['last_login']); 
				$results['User']['last_login'] = date("Y-m-d H:i:s"); 
				$this->User->save($results); 
				$this->redirect('/'); 
			} else { 
				$this->Session->setFlash('Wrong username / password. Please try again.');
				$this->redirect('/users/login'); 
			} 
		} 
	}

	function logout() 
	{ 
		$this->Session->delete('user'); 
		$this->redirect('/users/login'); 
		$this->Session->setFlash('Ta for now!');
	}
} 
?>