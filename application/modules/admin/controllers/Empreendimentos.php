<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('empreendimentos_model'));
  }

  public function index($estagio = null, $page = 1) {
    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Empreendimentos',
        'page' => array(
          'one' => 'empreendimentos'
        )
      )
    ));

    $where = array();

    if($estagio){
      $where['estagios.slug'] = $estagio;
    }

    $data['empreendimentos'] = $this->empreendimentos_model->obter_empreendimentos(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('registros_limite'),
          'page' => $page
        ),
        'orderby' => 'data_contrato',
        'where' => $where
      )
    ));

    $this->template->view('admin/master', 'admin/empreendimentos/lista', $data);
  }

}
