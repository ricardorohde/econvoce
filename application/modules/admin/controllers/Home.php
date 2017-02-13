<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {
	public function index() {
    $data = array(
      'section' => array(
      )
    );

		$this->template->view('site/master', 'site/home', $data);
	}
}
