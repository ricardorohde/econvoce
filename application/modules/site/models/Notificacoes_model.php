<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notificacoes_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_novidades_vendas() {
    $notificacao = $this->registros_model->obter_registros(
      'usuarios',
      array('where' => array('usuarios.id' => $this->site->userinfo('id'), 'usuarios.novidades' => 1)),
      true,
      'count(*) as total'
    );

    if($notificacao) return true;

    return false;
  }
}
