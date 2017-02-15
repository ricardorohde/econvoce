<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// Base Controller
class Default_Controller extends MX_Controller {
  function __construct() {
    parent::__construct();
    header('Content-Type: text/html; charset=utf-8');
    $this->output->enable_profiler(FALSE);
  }
}

// Site Controller
class Site_Controller extends Default_Controller {
  function __construct() {
    parent::__construct();

    $this->load->add_package_path(APPPATH . 'modules/site/');

    //$this->load->model(array('logs_model'));
    //$this->form_validation->set_error_delimiters('<small class="help-block">', '</small>');
  }
}

// Admin Controller
class Admin_Controller extends Default_Controller {
  public $header;

  function __construct() {
    parent::__construct();
    $this->load->add_package_path(APPPATH . 'modules/admin/');
    $this->load->library(array('admin'));
    $this->load->model(array('notificacoes_model'));

    $header = array('header' => array('notificacoes' => array()));

    $notificacoes_duplicadas = $this->notificacoes_model->obter_notificacoes_duplicadas();

    if($notificacoes_duplicadas){
      $header['header']['notificacoes'][] = array(
        'label' => ($notificacoes_duplicadas == 1 ? 'Existe '. $notificacoes_duplicadas .' venda duplicada' : 'Existem '. $notificacoes_duplicadas .' vendas duplicadas'),
        'url' => 'admin/vendas/duplicadas'
      );
    }

    $this->header = $header;
  }
}
