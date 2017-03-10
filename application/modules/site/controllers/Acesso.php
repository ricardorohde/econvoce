<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso extends Site_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('acesso_model'));
  }

  public function login() {
    $data = array(
      'section' => array(
        'hierarchy' => array('login'),
        'hide_all' => true,
        'body_class' => 'page-acesso login'
      )
    );

    if($this->input->post()){
      if($login = $this->acesso_model->efetuar_login($this->input->post())){
        if(isset($login['result']) && $login['result']){
          redirect($this->site->get_redirect(), 'location');
        }else{
          $data = array_merge($data, $this->site->alerta_redirect('danger', (isset($login['message']) ? $login['message'] : 'Ocorreu um erro inesperado.'), false, 'visible'));
        }
      }
    }

    $this->template->view('site/master', 'site/acesso/login', $data);
  }

  public function cadastro() {
    $perfis = array(1, 2, 2, 4, 3);

    $data = array(
      'section' => array(
        'hierarchy' => array('cadastro'),
        'hide_all' => true,
        'body_class' => 'page-acesso cadastro'
      ),
      'assets' => array(
        'scripts' => array(
          array('assets/site/js/jquery.mask.min.js', true),
          array('assets/site/js/pages/acesso-cadastro.js', true)
        )
      )
    );

    if($this->input->post()){
      $data['usuario'] = $this->input->post();

      $rules = array(
        array(
          'field' => 'perfil',
          'label' => 'Cargo',
          'rules' => 'required|is_natural_no_zero',
          'errors' => array(
            'is_natural_no_zero' => 'O campo Cargo é obrigatório'
          )
        ),
        array(
          'field' => 'nome',
          'label' => 'Nome',
          'rules' => 'required'
        ),
        array(
          'field' => 'apelido',
          'label' => 'Apelido',
          'rules' => 'required|is_unique[usuarios.apelido]',
          'errors' => array(
            'is_unique' => 'Este apelido já está sendo utilizado. Tente outro.'
          )
        ),
        array(
          'field' => 'cpf',
          'label' => 'CPF',
          'rules' => 'required|valid_cpf'
        ),
        array(
          'field' => 'email',
          'label' => 'E-mail',
          'rules' => 'required|valid_email|is_unique[usuarios.email]'
        ),
        array(
          'field' => 'senha',
          'label' => 'Senha',
          'rules' => 'required|matches[repetir_senha]'
        ),
        array(
          'field' => 'repetir_senha',
          'label' => 'Repetir senha',
          'rules' => 'required'
        ),
        array(
          'field' => 'telefone',
          'label' => 'Telefone',
          'rules' => 'required'
        )
      );

      if(isset($data['usuario']['perfil']) && $data['usuario']['perfil'] != 1){
        $rules[] = array(
          'field' => 'creci',
          'label' => 'CRECI',
          'rules' => 'required|is_unique[usuarios.creci]',
          'errors' => array(
            'is_unique' => 'Já existe um usuário cadastrado com o mesmo Creci.'
          )
        );
      }

      $this->form_validation->set_rules($rules);

      if($this->form_validation->run() == TRUE){
        $data['usuario']['estagiario'] = ($data['usuario']['perfil'] == 1 ? 1 : 0);
        $data['usuario']['perfil'] = $perfis[$data['usuario']['perfil']];
        
        if($usuario = $this->acesso_model->adicionar_usuario($data['usuario'], true)) {
          $this->session->set_tempdata('usuario_cadastrado', $usuario, 60);
          redirect(base_url('cadastro/sucesso'), 'location');
        }
      }else{
        $data = array_merge($data, $this->site->form_error($this->form_validation->error_array()));
      }
    }

    $this->site->send_mail('lco', 'Confirmação de Cadastro', 'cadastro', array('nome' => 'Luciano', 'guid' => '32432423'));

    $this->template->view('site/master', 'site/acesso/cadastro', $data);
  }

  public function cadastro_sucesso() {
    //$this->site->user_logged(false, '/', 'usuario_cadastrado');

    $data = array(
      'section' => array(
        'hierarchy' => array('cadastro','sucesso'),
        'hide_all' => true,
        'body_class' => 'page-acesso cadastro'
      )
    );

    $this->template->view('site/master', 'site/acesso/cadastro-sucesso', $data);
  }

  public function esqueci_minha_senha() {
    $data = array(
      'section' => array(
        'hierarchy' => array('esqueci-minha-senha'),
        'hide_all' => true,
        'body_class' => 'page-acesso esqueci-minha-senha'
      )
    );

    if($this->input->post()){
      if($usuario = $this->acesso_model->esqueci_senha($this->input->post())){
        print_l($usuario);
        // if(isset($login['result']) && $login['result']){
        //   redirect($this->site->get_redirect(), 'location');
        // }else{
        //   $data = array_merge($data, $this->site->alerta_redirect('danger', (isset($login['message']) ? $login['message'] : 'Ocorreu um erro inesperado.'), false, 'visible'));
        // }
      }
    }

    $this->template->view('site/master', 'site/acesso/esqueci-minha-senha', $data);
  }

  public function cadastro_confirmar($guid) {
    if($usuario = $this->acesso_model->confirmar_cadastro($guid)){
      if($usuario['result']){
        $this->site->alerta_redirect('success', 'Seu cadastro foi validado com sucesso.', '/', 'visible');
      }else{
        $this->site->alerta_redirect('danger', (isset($usuario['message']) ? $usuario['message'] : 'Ocorreu um erro inesperado.'), 'login', 'visible');
      }
    }
  }

  public function logout() {
    session_destroy();
    redirect(base_url(), 'location');

  }
}
