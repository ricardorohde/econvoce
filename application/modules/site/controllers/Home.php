<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Site_Controller {
	public function index() {
    $data = array(
      'section' => array(
        'body_class' => 'page-home'
      )
    );

		$this->template->view('site/master', 'site/home', $data);
	}
}
