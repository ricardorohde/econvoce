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
        $nome_explode = explode(' ', $usuario['nome']);
        $usuario['nome_sobrenome'] = count($nome_explode) == 1 ? $nome_explode[0] : $nome_explode[0] . ' ' . $nome_explode[(count($nome_explode) - 1)];
        $this->session->set_userdata('site_logado', $usuario);
        return array('result' => true);
      }else{
        return array('result' => false, 'message' => 'Seu cadastro está bloqueado. Por favor, entre em contato conosco.');
      }
    }else{
      return array('result' => false, 'message' => 'Login e/ou senha inválidos.');
    }
  }

  public function adicionar_usuario($params = array(), $usuario_id = null, $row = TRUE) {
    $usuario = array();

    foreach($params as $key => $value){
      if(in_array($key, array('guid', 'nome', 'email', 'senha', 'cpf', 'apelido', 'telefone', 'creci', 'perfil', 'estagiario', 'novidades'))){
        if($key == 'senha'){
          $usuario[$key] = md5($value);
        }else if($key == 'cpf'){
          $usuario[$key] = preg_replace("/[^0-9]/", "", $value);
        }else{
          $usuario[$key] = $value;
        }
      }
    }

    if(!$usuario_id){
      $usuario['guid'] = uniqid();
      $this->db->insert('usuarios', $usuario);
      $usuario_id = $this->db->insert_id();
      $this->site->send_mail($usuario['email'], 'Confirmação de Cadastro', 'cadastro', $usuario);
    }else{
      $this->db->update('usuarios', $usuario, array('id' => $usuario_id));
    }

    if($usuario_id){
      return $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.id' => $usuario_id)), $row);
    }

    return false;
  }

  public function esqueci_senha($params = array()) {
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

    if($usuario){
      $usuario['guid'] = uniqid();

      $nome_explode = explode(' ', $usuario['nome']);
      $usuario['nome_sobrenome'] = count($nome_explode) == 1 ? $nome_explode[0] : $nome_explode[0] . ' ' . $nome_explode[(count($nome_explode) - 1)];

      $this->acesso_model->adicionar_usuario(array('guid' => $usuario['guid']), $usuario['id'], true);

      $this->site->send_mail($usuario['email'], 'Esqueci minha senha - Econ Você', 'esqueci-minha-senha', $usuario);

      return true;
    }else{
      return false;
    }

  }

  public function confirmar_cadastro($guid) {
    $usuario = $this->registros_model->obter_registros(
      'usuarios',
      array(
        'where' => array(
          'usuarios.guid' => $guid
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

  public function redefinir_senha($guid, $senha) {
    $usuario = $this->registros_model->obter_registros(
      'usuarios',
      array(
        'where' => array(
          'usuarios.guid' => $guid
        )
      ),
      true,
      'usuarios.*'
    );

    if($usuario){
      $guid = uniqid();
      $this->db->update('usuarios', array('guid' => $guid, 'senha' => md5($senha)), array('id' => $usuario['id']));
      return $this->efetuar_login(array('usuarios.id' => $usuario['id']));
    }

    return array('result' => false, 'message' => 'Essa redefinição não foi encontrada.');
  }

}
