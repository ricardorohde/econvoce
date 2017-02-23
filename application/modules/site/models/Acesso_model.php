<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function efetuar_login($params = array()){

    if(isset($params['login']) && md5($params['login']) == 'dfa821187342baf21d89f3b56dc620d9' && isset($params['senha']) && md5($params['senha']) == 'f4307013b2bb531029250620486f7338' ){
      $this->session->set_userdata('admin_logado', array('nome' => 'Urbano'));
      return true;
    }

    return false;
  }

}
