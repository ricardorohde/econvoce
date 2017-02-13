<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends Site_Controller {
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}

  function configjs() {
    $this->load->view('site/configjs');
  }

}
