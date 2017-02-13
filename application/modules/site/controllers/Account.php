<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Site_Controller {
	public function login() {
    $data = array(
      'section' => array(
        'body_class' => 'page-login',
        'hide_footer' => true
      )
    );

		$this->template->view('site/master', 'site/account/login', $data);
	}
}
