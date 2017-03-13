<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos extends Site_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('empreendimentos_model'));
  }

  public function index($estagio = 0, $page = 1) {
    $this->site->user_logged();
    
    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('produtos'),
        'body_class' => 'page-empreendimentos'
      ),
      'search_action' => 'produtos' . ($estagio ? '/' . $estagio : ''),
      'estagios' => $this->registros_model->obter_registros('estagios', array('where' => array('estagios.slug !=' => 'nao-informado')))
    ));

    $where = array();

    if($estagio){
      $where['estagios.slug'] = $estagio;
      $data['estagio_slug'] = $estagio;
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
