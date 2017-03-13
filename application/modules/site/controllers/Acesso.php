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
          'rules' => 'required|valid_cpf|is_unique[usuarios.cpf]'
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
        
        if($usuario = $this->acesso_model->adicionar_usuario($data['usuario'], null, true)) {
          $this->session->set_tempdata('usuario_cadastrado', $usuario, 60);
          redirect(base_url('cadastro/sucesso'), 'location');
        }
      }else{
        $data = array_merge($data, $this->site->form_error($this->form_validation->error_array()));
      }
    }

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
        $data = array_merge($data, $this->site->alerta_redirect('success', 'Você receberá um e-mail para redefinir sua senha.', false, 'visible'));
      }else{
        $data = array_merge($data, $this->site->alerta_redirect('danger', 'Esse e-mail não foi encontrado no sistema.', false, 'visible'));
      }
    }

    $this->template->view('site/master', 'site/acesso/esqueci-minha-senha', $data);
  }

  public function redefinir_senha($usuario_guid) {

    $redefinicao = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.guid' => $usuario_guid)), true);

    if(!$redefinicao){
      $this->site->alerta_redirect('danger', 'Essa redefinição de senha não foi encontrada. Solicite uma nova redefinição.', 'esqueci-minha-senha');
    }

    $data = array(
      'section' => array(
        'hierarchy' => array('esqueci-minha-senha','redefinir-senha'),
        'hide_all' => true,
        'body_class' => 'page-acesso redefinir-senha'
      ),
      'form_action' => 'cadastro/redefinir-senha/' . $usuario_guid
    );

    if($this->input->post()){
      $data['usuario'] = $this->input->post();

      $rules = array(
        array(
          'field' => 'senha',
          'label' => 'Senha',
          'rules' => 'required|matches[repetir_senha]'
        ),
        array(
          'field' => 'repetir_senha',
          'label' => 'Repetir senha',
          'rules' => 'required'
        )
      );

      $this->form_validation->set_rules($rules);

      if($this->form_validation->run() == TRUE){
        if($usuario = $this->acesso_model->redefinir_senha($usuario_guid, $data['usuario']['senha'])) {
          redirect(base_url(), 'location');
        }
      }else{
        $data = array_merge($data, $this->site->form_error($this->form_validation->error_array()));
      }
    }

    $this->template->view('site/master', 'site/acesso/redefinir-senha', $data);
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


  public function minha_conta() {
    $this->site->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('envios'),
        'body_class' => 'page-minha-conta'
      ),
      'assets' => array(
        'scripts' => array(
          array('assets/site/js/jquery.mask.min.js', true),
          array('assets/site/js/pages/acesso-cadastro.js', true)
        )
      )
    ));

    $data['perfis'] = $this->registros_model->obter_registros('perfis', array('where' => array('perfis.id>' => 1)));
    $data['usuario'] = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.id' => $this->site->userinfo('id'))), true);

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
          'rules' => 'required|unique[usuarios.apelido.'. $this->site->userinfo('id') .']',
          'errors' => array(
            'unique' => 'Este apelido já está sendo utilizado. Tente outro.'
          )
        ),
        array(
          'field' => 'cpf',
          'label' => 'CPF',
          'rules' => 'required|valid_cpf|unique[usuarios.cpf.'. $this->site->userinfo('id') .']'
        ),
        array(
          'field' => 'email',
          'label' => 'E-mail',
          'rules' => 'required|valid_email|unique[usuarios.email.'. $this->site->userinfo('id') .']'
        ),
        array(
          'field' => 'telefone',
          'label' => 'Telefone',
          'rules' => 'required'
        )
      );

      if(!empty($data['usuario']['senha']) || !empty($data['usuario']['repetir_senha'])){
        $rules[] = array(
          'field' => 'senha',
          'label' => 'Senha',
          'rules' => 'required|matches[repetir_senha]'
        );

        $rules[] = array(
          'field' => 'repetir_senha',
          'label' => 'Repetir senha',
          'rules' => 'required'
        );
      }

      if(!isset($data['usuario']['estagiario'])){
        $rules[] = array(
          'field' => 'creci',
          'label' => 'CRECI',
          'rules' => 'required|unique[usuarios.creci.'. $this->site->userinfo('id') .']',
          'errors' => array(
            'is_unique' => 'Já existe um usuário cadastrado com o mesmo Creci.'
          )
        );
      }

      $this->form_validation->set_rules($rules);

      if($this->form_validation->run() == TRUE){

        $data['usuario']['estagiario'] = isset($data['usuario']['estagiario']) ? 1 : 0;

        if($usuario = $this->acesso_model->adicionar_usuario($data['usuario'], $this->site->userinfo('id'), true)) {
          $this->site->alerta_redirect('success', 'Seu cadastro foi atualizado com sucesso.', 'minha-conta', 'visible');
        }
      }else{
        $data = array_merge($data, $this->site->form_error($this->form_validation->error_array()));
      }
    }

    $this->template->view('site/master', 'site/acesso/minha-conta', $data);
  }

  public function logout() {
    $this->session->unset_userdata('site_logado');
    $this->session->unset_userdata('notificacao_ranking');
    redirect(base_url(), 'location');

  }
}
