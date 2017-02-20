<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('usuarios_model'));
  }

  public function index($page = 1) {
    $this->admin->user_logged();

    $where = array();
    $like = array();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Usuários',
        'page' => array(
          'one' => 'usuarios'
        ),
        'search_form_action' => 'admin/usuarios'
      )
    ));

    if($this->input->get('q')){
      $like['usuarios.apelido'] = $this->input->get('q');
      $like['perfis.nome'] = $this->input->get('q');
      $data['filter'] = true;
    }

    $data['usuarios'] = $this->usuarios_model->obter_usuarios(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('registros_limite'),
          'page' => $page
        ),
        'where' => $where,
        'like' => $like
      )
    ));

    $this->template->view('admin/master', 'admin/usuarios/lista', $data);
  }
}
