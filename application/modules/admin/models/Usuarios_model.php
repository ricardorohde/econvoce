<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function adicionar_usuario($params = array(), $row = TRUE) {
    $usuario = $params;

    $this->db->insert('usuarios', $usuario);
    $usuario_id = $this->db->insert_id();

    if($usuario_id){
      return $this->registros_model->obter_registros('usuarios', array('usuarios.id' => $usuario_id), $row);
    }

    return false;
  }
}