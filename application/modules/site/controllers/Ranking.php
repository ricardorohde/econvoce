<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends Site_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('ranking_model'));
  }

  public function index($page = 1, $mes = 0, $ano = 0) {
    $this->site->user_logged();

    if($this->session->userdata('notificacao_ranking') == 1){
      $this->load->model(array('acesso_model'));
      $this->acesso_model->adicionar_usuario(array('novidades' => 0), $this->site->userinfo('id'), true);
      $this->session->set_userdata('notificacao_ranking', 0);
    }

    $per_page = 12;

    $periodos = $this->ranking_model->obter_vendas_periodos();
    if(!$mes && !$ano){
      $periodo = end($periodos);
      $ranking_mes = $periodo['mes'];
      $ranking_ano = $periodo['ano'];
    }else{
      $ranking_mes = $mes;
      $ranking_ano = $ano;
    }

    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('ranking'),
        'body_class' => 'page-ranking'
      ),
      'search_action' => 'ranking' . ($mes && $ano ? '/' . $mes .'/'. $ano : ''),
      'mes' => $ranking_mes,
      'ano' => $ranking_ano
    ));

    $like = array();
    if($this->input->get('q')){
      $like['apelido'] = $this->input->get('q');
      $like['nome'] = $this->input->get('q');
      $data['search'] = true;
    }

    $data['periodos'] = $periodos;

    $data['ranking'] = $this->ranking_model->obter_ranking(array(
      'params' => array(
        'pagination' => array(
          'limit' => $per_page,
          'page' => $page
        ),
        'orderby' => 'nome',
        'where' => array(
          'MONTH(data_contrato)' => $ranking_mes,
          'YEAR(data_contrato)' => $ranking_ano
        ),
        'like' => $like
      )
    ));

    $this->template->view('site/master', 'site/ranking/main', $data);
  }
}
