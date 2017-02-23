<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('ranking_model'));
  }

  public function index($page = 1) {
    $per_page = 12;

    $data = array(
      'section' => array(
        'hierarchy' => array('ranking'),
      ),
      'per_page' => $per_page,
      'page' => $page
    );

    $where = array();

    $like = array();

    if($this->input->get('q')){
      $like['usuarios.nome'] = $this->input->get('q');
      $like['usuarios.apelido'] = $this->input->get('q');
      $data['search'] = true;
    }

    $data['ranking'] = $this->ranking_model->obter_ranking(array(
      'params' => array(
        'pagination' => array(
          'limit' => $per_page,
          'page' => $page
        ),
        'orderby' => 'nome',
        'where' => $where,
        'like' => $like
      )
    ));

    $this->template->view('site/master', 'site/ranking/main', $data);
  }
}
