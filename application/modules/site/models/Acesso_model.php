<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function efetuar_login($params = array()){

    if(isset($params['redirect'])) unset($params['redirect']);

    if(isset($params['senha'])){
      $params['senha'] = md5($params['senha']);
    }

    if($usuario = $this->registros_model->obter_registros(
      'usuarios',
      array(
        'where' => $params
      ),
      true,
      'usuarios.id, usuarios.nome, usuarios.apelido, usuarios.email, usuarios.telefone, usuarios.creci, usuarios.status, perfis.id as perfil, perfis.nome as perfil_nome, perfis.slug as perfil_slug, perfis.percentual as perfil_percentual',
      array(
        array('perfis', 'usuarios.perfil = perfis.id', 'inner')
      )
    )) {
      if($usuario['status'] == 1){
        $this->session->set_userdata('site_logado', $usuario);
        return array('result' => true);
      }else{
        return array('result' => false, 'message' => 'Seu cadastro está bloqueado. Por favor, entre em contato conosco.');
      }
    }else{
      return array('result' => false, 'message' => 'Login e/ou senha inválidos.');
    }
  }

  public function adicionar_usuario($params = array(), $row = TRUE) {
    $usuario = array();

    $usuario['guid'] = uniqid();

    foreach($params as $key => $value){
      if(in_array($key, array('guid', 'nome', 'email', 'senha', 'cpf', 'apelido', 'telefone', 'creci', 'perfil', 'estagiario'))){
        if($key == 'senha'){
          $usuario[$key] = md5($value);
        }else if($key == 'cpf'){
          $usuario[$key] = preg_replace("/[^0-9]/", "", $value);
        }else{
          $usuario[$key] = $value;
        }
      }
    }

    $this->db->insert('usuarios', $usuario);
    $usuario_id = $this->db->insert_id();

    $this->site->send_mail($usuario['email'], 'Confirmação de Cadastro', 'cadastro', $usuario);

    if($usuario_id){
      return $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.id' => $usuario_id)), $row);
    }

    return false;
  }

  public function esqueci_senha($params = array()) {
    print_l($params);

    $usuario = $this->registros_model->obter_registros(
      'usuarios',
      array(
        'where' => array(
          'usuarios.email' => $params['email']
        )
      ),
      true,
      'usuarios.id, usuarios.nome, usuarios.apelido, usuarios.email, usuarios.telefone, usuarios.creci, usuarios.status, perfis.id as perfil, perfis.nome as perfil_nome, perfis.slug as perfil_slug',
      array(
        array('perfis', 'usuarios.perfil = perfis.id', 'inner')
      )
    );

    print_l($usuario);
  }

  public function confirmar_cadastro($guid) {
    $usuario = $this->registros_model->obter_registros(
      'usuarios',
      array(
        'where' => array(
          'usuarios.guid' => $guid,
          'usuarios.status' => 0
        )
      ),
      true,
      'usuarios.*'
    );

    if($usuario){
      $guid = uniqid();
      $this->db->update('usuarios', array('guid' => $guid, 'status' => 1), array('id' => $usuario['id']));
      return $this->efetuar_login(array('usuarios.id' => $usuario['id']));
    }

    return array('result' => false, 'message' => 'Essa confirmação não foi encontrada.');
  }

}
