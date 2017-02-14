<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function adicionar_empreendimento($params = array(), $row = TRUE) {
    $empreendimento = $params;

    $this->db->insert('empreendimentos', $empreendimento);
    $empreendimento_id = $this->db->insert_id();
    if($empreendimento_id){
      return $this->registros_model->obter_registros('empreendimentos', array('where' => array('empreendimentos.id' => $empreendimento_id)), $row);
    }
    return false;
  }
}