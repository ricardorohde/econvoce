<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Site_Controller {
	public function index() {
		$template = 'site/home';

	    $data = array(
	      'section' => array(
	        'hierarchy' => array('home'),
	        'body_class' => 'page-home'
	      )
	    );

		if($this->site->user_logged(false, false)){
			$data['section']['hide_header'] = true;
			$data['section']['body_class'] = 'page-prehome';
			$template = 'site/prehome';
		}

		$this->template->view('site/master', $template, $data);
	}
}
