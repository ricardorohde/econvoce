<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('acesso_model'));
  }

  public function login() {
    $data = array(
      'section' => array(
        'hide_all' => true
      )
    );

    if($this->input->post()){
      if($login = $this->acesso_model->efetuar_login($this->input->post())){
        $this->admin->alerta_redirect('success', 'Seja bem vindo(a).', 'admin', 'visible');
      }else{
        $data = array_merge($data, $this->admin->alerta_redirect('danger', 'Login e/ou senha inválidos', false, 'visible'));
      }
    }

    $this->template->view('admin/master', 'admin/login', $data);
  }

  public function logout() {
    session_destroy();
    redirect(base_url('admin/login'), 'location');

  }
}
