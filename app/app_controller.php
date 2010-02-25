<?php
class AppController extends Controller {
	var $users = array('User');
	var $components = array('Session');
	var $helpers = array('Html', 'Form');
	
	function checkSession(){
		global $user_id;
		$username = $this->Session->read('user');
		if (!$username){
			$this->redirect('/users/login');
			exit;
		} else {
			$results = $this->User->findByEmail($username); 
			$user_id = $results['User']['id'];
			$this->set('user', $results['User']['email']);
		}
	}
}
?>