<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('empreendimentos_model'));
  }

  public function index($estagio = 0, $page = 1) {
    $data = array(
      'section' => array(
        'hierarchy' => array('empreendimentos'),
      ),
      'search_action' => 'empreendimentos' . ($estagio ? '/' . $estagio : ''),
      'estagios' => $this->registros_model->obter_registros('estagios', array('where' => array('estagios.slug !=' => 'nao-informado')))
    );

    $where = array();

    if($estagio){
      $where['estagios.slug'] = $estagio;
    }

    $like = array();

    if($this->input->get('q')){
      $like['empreendimentos.apelido'] = $this->input->get('q');
      $data['search'] = true;
    }

    $data['empreendimentos'] = $this->empreendimentos_model->obter_empreendimentos(array(
      'params' => array(
        'pagination' => array(
          'limit' => 12,
          'page' => $page
        ),
        'orderby' => 'nome',
        'where' => $where,
        'like' => $like
      )
    ));

    $this->template->view('site/master', 'site/empreendimentos/main', $data);
  }
}
