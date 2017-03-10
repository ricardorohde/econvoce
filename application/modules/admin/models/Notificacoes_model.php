<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notificacoes_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_vendas_duplicadas(){
    return $this->registros_model->obter_registros_count('SELECT id FROM vendas WHERE vendas.parente != 0');
  }

  public function obter_usuarios_incompletos(){
    return $this->registros_model->obter_registros_count('SELECT id FROM usuarios WHERE usuarios.status != 1');
  }

}
