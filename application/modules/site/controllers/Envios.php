<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Envios extends Site_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('empreendimentos_model', 'envios_model'));
  }

  public function index($envio_guid = null) {
    $this->site->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('envios'),
        'body_class' => 'page-envios'
      ),
      'assets' => array(
        'scripts' => array(
          array('assets/site/js/mustache.min.js', true),
          array('assets/site/js/pages/envios.js', true)
        )
      ),
      'form_action' => 'vendas' . ($envio_guid ? '/' . $envio_guid : '')
    ));

    $data['empreendimentos'] = $this->empreendimentos_model->obter_empreendimentos(array(
      'params' => array(
        'orderby' => 'nome'
      )
    ));

    if($envio_guid){
      $envio = $this->registros_model->obter_registros('envios', array('where' => array('envios.guid' => $envio_guid, 'envios.status' => 0)), true);

      if(!$envio){
        $this->site->alerta_redirect('danger', 'Não foi encontrado o disparo de e-mails. Começe novamente.', 'vendas');
      }

      $data['envio'] = $envio;
      $data['envio']['emails'] = $this->registros_model->obter_registros('envios_emails', array('where' => array('envios_emails.envio' => $data['envio']['id'])));
    }
    
    if($this->input->post()){
      $error = '';
      $post = $this->input->post();

      $data['envio'] = array(
        'empreendimento' => $post['empreendimento']
      );

      $envio_emails = array();
      if(isset($post['nome']) && !empty($post['nome'])){
        foreach($post['nome'] as $key => $value) {
          if(!empty($post['nome'][$key]) || !empty($post['email'][$key])) {

            $cliente_error = false;

            if(!empty($post['email'][$key])) {
              if(!in_array($post['email'][$key], $envio_emails)){
                $envio_emails[] = $post['email'][$key];
              }else{
                $error .= '&bull; Existem e-mails repetidos na lista.<br>';
                $cliente_error = true;
              }
            }

            if(empty($post['nome'][$key])){
              $error .= '&bull; Preencha corretamente o nome de todos os clientes.<br>';
              $cliente_error = true;
            }

            if(!valid_email($post['email'][$key])){
              $error .= '&bull; Existe um ou mais e-mails não preenchidos ou inválidos.<br>';
              $cliente_error = true;
            }

            $data['envio']['emails'][] = array(
              'nome' => $post['nome'][$key],
              'email' => $post['email'][$key],
              'error' => $cliente_error
            );
          }
        }
      }

      $this->form_validation->set_rules(array(
        array(
          'field' => 'empreendimento',
          'label' => 'Produto',
          'rules' => 'required|is_natural_no_zero',
          'errors' => array(
            'is_natural_no_zero' => 'Você precisa selecionar um produto'
          )
        ),
        array(
          'field' => 'email[]',
          'label' => 'E-mail',
          'rules' => 'valid_email|differs[email[]]',
          'errors' => array(
            'valid_email' => 'Existe um ou mais e-mails inválidos'
          )
        )
      ));

      if($this->form_validation->run() == TRUE){
        if($error){
          $data = array_merge($data, $this->site->alerta_redirect('danger', $error, false, 'visible'));
        }else{
          if($envio_guid = $this->envios_model->criar_envio($data['envio'], $envio_guid)) {
            redirect('vendas/' . $envio_guid . '/visualizacao', 'location');
          }
        }
      }else{
        $data = array_merge($data, $this->site->form_error($this->form_validation->error_array()));
      }
    }

    $this->template->view('site/master', 'site/envios/main', $data);
  }

  public function visualizacao($envio_guid) {
    $this->site->user_logged();

    $envio = $this->registros_model->obter_registros('envios', array('where' => array('envios.guid' => $envio_guid, 'envios.status' => 0)), true);

    if(!$envio){
      $this->site->alerta_redirect('danger', 'Não foi encontrado o disparo de e-mails. Começe novamente.', 'vendas');
    }

    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('envios','visualizacao'),
        'body_class' => 'page-envios'
      ),
      'envio_guid' => $envio_guid,
      'form_action' => 'vendas/' . $envio_guid . '/visualizacao'
    ));

    $data['envio'] = $envio;
    $data['envio']['emails'] = $this->registros_model->obter_registros('envios_emails', array('where' => array('envios_emails.envio' => $data['envio']['id'])));

    if($this->input->post('envio_flag')){
      if($this->envios_model->preparar_envio($envio_guid)) {
        redirect('vendas/' . $envio_guid . '/envio', 'location');
      }
    }

    $this->template->view('site/master', 'site/envios/visualizacao', $data);
  }

  public function visualizacao_email($envio_guid) {
    $this->site->user_logged();


    $data['envio'] = $this->registros_model->obter_registros(
      'envios',
      array('where' => array('envios.guid' => $envio_guid)),
      true,
      '
        envios.*,
        
        estagios.nome as estagio_nome,
        estagios.slug as estagio_slug,
        
        empreendimentos.nome as empreendimento_nome,,
        empreendimentos.endereco as empreendimento_endereco,
        empreendimentos.lazer as empreendimento_lazer,
        empreendimentos.dormitorios as empreendimento_dormitorios,
        empreendimentos.suites as empreendimento_suites,
        empreendimentos.vagas as empreendimento_vagas,
        empreendimentos.area as empreendimento_area,
        empreendimentos.imagem as empreendimento_imagem,
        empreendimentos.url as empreendimento_url
      ',
      array(
        array('empreendimentos', 'envios.empreendimento = empreendimentos.id', 'inner'),
        array('estagios', 'empreendimentos.estagio = estagios.id', 'inner')
      )
    );
    
    $this->load->view('site/vendas-email', $data);
  }

  public function envio($envio_guid) {
    $this->site->user_logged();

    $envio = $this->registros_model->obter_registros('envios', array('where' => array('envios.guid' => $envio_guid, 'envios.status' => 1)), true);

    if(!$envio){
      $this->site->alerta_redirect('danger', 'Não foi encontrado o disparo de e-mails. Começe novamente.', 'vendas');
    }

    $data = array_merge($this->header, array(
      'section' => array(
        'hierarchy' => array('envios','envio'),
        'body_class' => 'page-envios'
      ),
      'assets' => array(
        'scripts' => array(
          array('assets/site/js/pages/envios-envio.js', true)
        )
      ),
      'envio_guid' => $envio_guid,
      'form_action' => 'vendas/' . $envio_guid . '/visualizacao'
    ));

    $this->template->view('site/master', 'site/envios/envio', $data);
  }

  public function envio_processo($envio_guid) {
    $envio = $this->registros_model->obter_registros(
      'envios',
      array('where' => array('envios.guid' => $envio_guid)),
      true,
      '
        envios.*,
        
        estagios.nome as estagio_nome,
        estagios.slug as estagio_slug,
        
        empreendimentos.nome as empreendimento_nome,,
        empreendimentos.endereco as empreendimento_endereco,
        empreendimentos.lazer as empreendimento_lazer,
        empreendimentos.dormitorios as empreendimento_dormitorios,
        empreendimentos.suites as empreendimento_suites,
        empreendimentos.vagas as empreendimento_vagas,
        empreendimentos.area as empreendimento_area,
        empreendimentos.imagem as empreendimento_imagem,
        empreendimentos.url as empreendimento_url
      ',
      array(
        array('empreendimentos', 'envios.empreendimento = empreendimentos.id', 'inner'),
        array('estagios', 'empreendimentos.estagio = estagios.id', 'inner')
      )
    );

    $perfis_cores = $this->config->item('perfis_cores');
    $estagios_cores = $this->config->item('estagios_cores');

    $params = array(
      'usuario_nome' => $this->site->userinfo('nome_sobrenome'),
      'usuario_perfil_nome' => $this->site->userinfo('perfil_nome'),
      'usuario_perfil_cor' => $perfis_cores[$this->site->userinfo('perfil_slug')],
      'usuario_telefone' => $this->site->userinfo('telefone'),
      'usuario_email' => $this->site->userinfo('email'),

      'empreendimento_nome' => $envio['empreendimento_nome'],
      'empreendimento_endereco' => $envio['empreendimento_endereco'],
      'empreendimento_lazer' => $envio['empreendimento_lazer'],
      'empreendimento_dormitorios' => $envio['empreendimento_dormitorios'],
      'empreendimento_suites' => $envio['empreendimento_suites'],
      'empreendimento_vagas' => $envio['empreendimento_vagas'],
      'empreendimento_area' => $envio['empreendimento_area'],
      'empreendimento_imagem' => $envio['empreendimento_imagem'],
      'empreendimento_url' => $envio['empreendimento_url'],

      'estagio_nome' => strtoupper($envio['estagio_nome']),
      'estagio_cor' => $estagios_cores[$envio['estagio_slug']],
    );

    $envio_emails = $this->registros_model->obter_registros('envios_emails', array('where' => array('envios_emails.envio' => $envio['id'])));

    if(!empty($envio_emails)){
      $enviados = 0;
      $erros = 0;

      foreach ($envio_emails as $cliente) {
        $params['cliente_nome'] = $cliente['nome'];
        $envio = $this->site->send_mail('sss', 'ddd', 'vendas', $params);
        if($envio){
          $enviados++;
        }else{
          $erros++;
        }
      }

      $this->envios_model->finalizar_envio($envio_guid);

      echo json_encode(array(
        'enviados' => $enviados,
        'erros' => $erros
      ));
    }
  }
}
