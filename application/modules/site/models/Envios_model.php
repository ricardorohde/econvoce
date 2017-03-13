<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Envios_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function criar_envio($params = array(), $guid = null){
    $acao = $guid ? 'edit' : 'add';
    $guid = $guid ? $guid : uniqid();

    $envio = array(
      'guid' => $guid,
      'usuario' => $this->site->userinfo('id'),
      'empreendimento' => $params['empreendimento']
    );

    if($acao == 'add'){
      $this->db->set('data_criado', 'NOW()', FALSE);
      $this->db->insert('envios', $envio);
      $envio_id = $this->db->insert_id();
    }else{
      $this->db->update('envios', $envio, array('guid' => $guid));

      if($get_envio = $this->registros_model->obter_registros('envios', array('where' => array('envios.guid' => $guid)), true)){
        $envio_id = $get_envio['id'];
      }

      $this->db->update('envios_emails', array('update' => 1), array('envio' => $envio_id));
    }

    $emails = array();
    if(isset($params['emails']) && !empty($params['emails'])){
      foreach($params['emails'] as $cliente){
        if($email_check = $this->db->get_where('envios_emails', array('envio' => $envio_id, 'email' => $cliente['email']))->row_array()){
          $this->db->update('envios_emails', array(
            'nome' => $cliente['nome'],
            'email' => $cliente['email'],
            'update' => 0
          ), array('id' => $email_check['id']));
        }else{
          $this->db->insert('envios_emails', array(
            'envio' => $envio_id,
            'nome' => $cliente['nome'],
            'email' => $cliente['email'],
            'update' => 0
          ));
        }
      }
    }

    $this->db->delete('envios_emails', array('envio' => $envio_id, 'update' => 1));

    return $guid;
  }

  public function preparar_envio($envio_guid) {
    $this->db->update('envios', array('status' => 1), array('guid' => $envio_guid));
    return $envio_guid;
  }

  public function finalizar_envio($envio_guid) {
    $this->db->set('data_enviado', 'NOW()', FALSE);
    $this->db->update('envios', array('status' => 2), array('guid' => $envio_guid));
    return $envio_guid;
  }
}
