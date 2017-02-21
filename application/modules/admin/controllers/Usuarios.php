<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('usuarios_model'));
  }

  public function index($page = 1, $perfil = 0, $incompletos = 0) {
    $this->admin->user_logged();

    $where = array();
    $like = array();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Usuários',
        'page' => array(
          'one' => 'usuarios'
        ),
        'search_form_action' => ($incompletos ? 'admin/usuarios/incompletos' : ($perfil ? 'admin/usuarios/' . $perfil : 'admin/usuarios'))
      ),
      'perfil_slug' => $perfil,
      'perfis' => $this->registros_model->obter_registros('perfis', array('where' => array('perfis.slug !=' => 'nao-informado')))
    ));

    if($this->input->get('q')){
      $like['usuarios.nome'] = $this->input->get('q');
      $like['usuarios.apelido'] = $this->input->get('q');
      $like['perfis.nome'] = $this->input->get('q');
      $data['filter'] = true;
    }

    if($perfil){
      $where['perfis.slug'] = $perfil;
    }

    if($incompletos){
      $where['usuarios.status'] = 2;
      $data['filter'] = true;
    }

    $data['usuarios'] = $this->usuarios_model->obter_usuarios(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('registros_limite'),
          'page' => $page
        ),
        'orderby' => 'nome',
        'where' => $where,
        'like' => $like
      )
    ));

    $this->template->view('admin/master', 'admin/usuarios/lista', $data);
  }

  public function importar() {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Importar planilha - Usuários',
        'page' => array(
          'one' => 'usuarios',
          'two' => 'importar',
        )
      )
    ));

    if($this->input->post('flag')){
      if(isset($_FILES['arquivo']) && $_FILES['arquivo']['size']){

        $vendas_existentes = 0;
        $vendas_inseridas = 0;
        $vendas_identicas = 0;

        $errors_upload = array();

        $importacoes = array();

        $file_name = $_FILES['arquivo']['name'];
        $file_size = $_FILES['arquivo']['size'];
        $file_tmp = $_FILES['arquivo']['tmp_name'];
        $file_type = $_FILES['arquivo']['type'];
        $file_ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

        $file_path = FCPATH . '/assets/uploads/' . $file_name;

        $extensions= array("xlsx");

        if(in_array($file_ext, $extensions)=== false){
          $errors_upload[] = "extension not allowed, please choose a XLSX file.";
        }

        if($file_size > 2097152){
          $errors_upload[] = 'File size must be excately 2 MB';
        }

        if(empty($errors_upload)){
          move_uploaded_file($file_tmp, $file_path);

          sleep(2);

          $this->load->library('excel');

          try {
            $file_type = PHPExcel_IOFactory::identify($file_path);
            $objReader = PHPExcel_IOFactory::createReader($file_type);
            $objPHPExcel = $objReader->load($file_path);
            $sheets = array();

            $colunas = array(
              'nome' => 'Nome Completo',
              'apelido' => 'Apelido',
              'telefone' => 'Telefone',
              'email' => 'E-mail',
              'creci' => 'CRECI'
            );

            $colunas_dados = array();

            $importacoes = array();

            $perfis = $this->registros_model->obter_registros('perfis');
            $perfis_labels = array('corretor' => 'Corretores', 'gerente' => 'Gerentes', 'coordenador' => 'Coordenadores');
            $usuarios = array();

            $usuario_count = 0;
            foreach ($objPHPExcel->getAllSheets() as $sheet) {
              $linhas = $sheet->toArray();
              $perfil = in_multiarray(array_search($sheet->getTitle(), $perfis_labels), 'slug', $perfis, true);

              if(isset($linhas[0][0]) && !empty($linhas[0][0])){

                foreach ($linhas as $linha_count => $linha) {

                  if(!$linha_count){
                    foreach($linha as $linha_coluna_count => $linha_coluna){
                      if(in_array($linha_coluna, $colunas)){
                        $colunas_dados[array_search($linha_coluna, $colunas)] = $linha_coluna_count;
                      }
                    }
                  }else{

                    if(isset($colunas_dados) && !empty($colunas_dados)){
                      foreach ($colunas_dados as $linha_key => $linha_value) {
                        $linha_processo = false;
                        $usuario_status = 1;
                        
                        if(isset($linha[$linha_value]) && trim($linha[$linha_value])){
                          if($linha_key == 'email'){
                            $email = $linha[$linha_value];
                            $email_processo = true;

                            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                              $usuario_status = 2;

                              if($email === '#N/A'){
                                $email_processo = false;
                              }
                            }
                            
                            if($email_processo){
                              $usuarios[$usuario_count][$linha_key] = $email;
                            }
                          }elseif($linha_key === 'nome'){

                            $nome = $linha[$linha_value];

                            $nome = ucwords(strtolower($nome));
                            foreach (array('De','Da','Do','Dos','E') as $nome_key => $nome_value) {
                              $nome = str_replace(' '. $nome_value .' ', ' '. strtolower($nome_value) .' ', $nome);  
                            }

                            $usuarios[$usuario_count][$linha_key] = $nome;
                          }else{
                            $usuarios[$usuario_count][$linha_key] = $linha[$linha_value];
                          }

                          $linha_processo = true;
                        }

                        if($linha_processo){
                          $usuarios[$usuario_count]['status'] = $usuario_status;
                          $usuarios[$usuario_count]['perfil'] = $perfil['id'];
                        }
                      }
                    }
                  }

                  $usuario_count++;
                }
              }
            }

            if(!empty($usuarios)){
              $importacao = $this->usuarios_model->atualizar_usuarios($usuarios);
              foreach ($importacao as $key => $value) {
                $importacoes[$key] = (isset($importacoes[$key]) ? $importacoes[$key] + $value : $value);
              }
            }else{
              $importacoes['nenhum_usuario'] = true;
            }

            $data['importacoes'] = $importacoes;

          } catch (Exception $e) {
            die($e->getMessage());
          }

          $data['upload'] = true;
        }else{
          $data['upload_erros'] = $errors_upload;
        }
      }
    }

    $this->template->view('admin/master', 'admin/usuarios/importar', $data);
  } //importar

  public function editar($usuario_id = null) {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => ($usuario_id ? 'Editar usuário' : 'Cadastrar usuário'),
        'page' => array(
          'one' => 'usuarios',
          'two' => 'editar'
        )
      ),
      'action' => ($usuario_id ? 'editar' : 'cadastrar'),
      'form_action' => ($usuario_id ? 'admin/usuarios/' . $usuario_id . '/editar' : 'admin/usuarios/cadastrar'),
      'perfis' => $this->registros_model->obter_registros('perfis')
    ));

    if($usuario_id){
      $data['usuario'] = $this->usuarios_model->obter_usuarios(array(
        'params' => array(
          'usuario_id' => $usuario_id
        )
      ), true);
    }

    if($this->input->post()){
      $data['usuario'] = $this->input->post();

      $this->form_validation->set_rules(array(
        array(
          'field' => 'nome',
          'label' => 'Nome completo',
          'rules' => 'required',
          'errors' => array(
            'required' => 'Você precisa preencher o nome completo do usuário.'
          )
        ),
        array(
          'field' => 'apelido',
          'label' => 'Apelido do usuário',
          'rules' => 'required',
          'errors' => array(
            'required' => 'Você precisa preencher o apelido do usuário.'
          )
        ),
        array(
          'field' => 'email',
          'label' => 'E-mail do usuário',
          'rules' => 'required|valid_email',
          'errors' => array(
            'required' => 'Você precisa preencher o e-mail do usuário.',
            'valid_email' => 'O e-mail informado está inválido.'
          )
        ),
        array(
          'field' => 'perfil',
          'label' => 'Perfil do usuário',
          'rules' => 'required|greater_than[1]',
          'errors' => array(
            'required' => 'Você precisa preencher o perfil do usuário.',
            'greater_than' => 'Você precisa preencher o perfil do usuário.'
          )
        )
      ));

      if($this->form_validation->run() == TRUE){
        if($usuario_id){
          if($usuario =  $this->usuarios_model->atualizar_usuario(array_merge($data['usuario'], array('status' => 1, 'update' => 0)), array('usuarios.id' => $usuario_id), TRUE)) {
            $this->admin->alerta_redirect('success', 'Usuário atualizado com sucesso.', 'admin/usuarios/'. $usuario['id'] .'/editar');
          }
        }else{
          if($usuario = $this->usuarios_model->adicionar_usuario(array_merge($data['usuario'], array('status' => 1, 'update' => 0)), true)) {
            $this->admin->alerta_redirect('success', 'Usuário cadastrado com sucesso.', 'admin/usuarios/'. $usuario['id'] .'/editar');
          }
        }
      }else{
        $data = array_merge($data, $this->admin->form_error($this->form_validation->error_array()));
      }
    }

    $this->template->view('admin/master', 'admin/usuarios/editar', $data);
  } //editar
}
