<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
	public function index() {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Dashboard',
        'page' => array(
          'one' => 'dashboard'
        )
      )
    ));

		$this->template->view('admin/master', 'admin/dashboard', $data);
	}
}
