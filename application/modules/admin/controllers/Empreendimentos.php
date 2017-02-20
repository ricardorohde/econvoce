<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('empreendimentos_model'));
  }

  public function index($estagio = 0, $page = 1) {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Empreendimentos',
        'page' => array(
          'one' => 'empreendimentos'
        ),
        'search_form_action' => 'admin/empreendimentos'
      ),
      'estagio_slug' => $estagio,
      'estagios' => $this->registros_model->obter_registros('estagios', array('where' => array('estagios.slug !=' => 'nao-informado')))
    ));

    $where = array();
    $like = array();

    if($estagio){
      $where['estagios.slug'] = $estagio;
    }

    if($this->input->get('q')){
      $like['empreendimentos.apelido'] = $this->input->get('q');
      $data['filter'] = true;
    }

    $data['empreendimentos'] = $this->empreendimentos_model->obter_empreendimentos(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('registros_limite'),
          'page' => $page
        ),
        'orderby' => 'data_contrato',
        'where' => $where,
        'like' => $like
      )
    ));

    $this->template->view('admin/master', 'admin/empreendimentos/lista', $data);
  }

  public function importar() {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Importar planilha - Empreendimentos',
        'page' => array(
          'one' => 'empreendimentos',
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
              'apelido' => 'Nome do empreendimento',
              'prioridade' => 'Prioridade'
            );

            $colunas_dados = array();

            $importacoes = array();

            $estagios = $this->registros_model->obter_registros('estagios');
            $estagios_labels = array('pronto' => 'Pronto para morar', 'remanescente' => 'Remanescente', 'lancamento' => 'Lançamento');
            $empreendimentos = array();

            $empreendimento_count = 0;
            foreach ($objPHPExcel->getAllSheets() as $sheet) {
              $linhas = $sheet->toArray();
              $estagio = in_multiarray(array_search($sheet->getTitle(), $estagios_labels), 'slug', $estagios, true);

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

                        if(isset($linha[$linha_value]) && trim($linha[$linha_value])){
                          $empreendimentos[$empreendimento_count][$linha_key] = $linha[$linha_value];
                          $linha_processo = true;
                        }
                      }
                      if($linha_processo){
                        $empreendimentos[$empreendimento_count]['estagio'] = $estagio['id'];
                      }
                    }
                  }

                  $empreendimento_count++;
                }
              }
            }

            if(!empty($empreendimentos)){
              $importacao = $this->empreendimentos_model->atualizar_empreendimentos($empreendimentos);
              foreach ($importacao as $key => $value) {
                $importacoes[$key] = (isset($importacoes[$key]) ? $importacoes[$key] + $value : $value);
              }
            }else{
              $importacoes['nenhum_empreendimento'] = true;
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

    $this->template->view('admin/master', 'admin/empreendimentos/importar', $data);
  } //importar

  public function editar($empreendimento_id = null) {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => ($empreendimento_id ? 'Editar empreendimento' : 'Cadastrar empreendimento'),
        'page' => array(
          'one' => 'empreendimentos',
          'two' => 'editar'
        )
      ),
      'action' => ($empreendimento_id ? 'editar' : 'cadastrar'),
      'form_action' => ($empreendimento_id ? 'admin/empreendimentos/' . $empreendimento_id . '/editar' : 'admin/empreendimentos/cadastrar'),
      'estagios' => $this->registros_model->obter_registros('estagios'),
      'prioridades' => $this->registros_model->obter_registros('prioridades')
    ));

    if($empreendimento_id){
      $data['empreendimento'] = $this->empreendimentos_model->obter_empreendimentos(array(
        'params' => array(
          'empreendimento_id' => $empreendimento_id
        )
      ), true);
    }

    if($this->input->post()){
      $data['empreendimento'] = $this->input->post();

      $this->form_validation->set_rules(array(
        array(
          'field' => 'apelido',
          'label' => 'Nome do empreendimento',
          'rules' => 'required',
          'errors' => array(
            'required' => 'Você precisa preencher o nome do empreendimento.'
          )
        ),
        array(
          'field' => 'estagio',
          'label' => 'Estágio',
          'rules' => 'required|greater_than[1]',
          'errors' => array(
            'required' => 'Você precisa preencher o estágio do empreendimento.',
            'greater_than' => 'Você precisa preencher o estágio do empreendimento.'
          )
        )
      ));

      if($this->form_validation->run() == TRUE){
        if($empreendimento_id){
          if($empreendimento =  $this->empreendimentos_model->atualizar_empreendimento(array_merge($data['empreendimento'], array('update' => 0)), array('empreendimentos.id' => $empreendimento_id), TRUE)) {
            $this->admin->alerta_redirect('success', 'Empreendimento atualizado com sucesso.', 'admin/empreendimentos/'. $empreendimento['id'] .'/editar');
          }
        }else{
          if($empreendimento = $this->empreendimentos_model->adicionar_empreendimento($data['empreendimento'], true)) {
            $this->admin->alerta_redirect('success', 'Empreendimento cadastrado com sucesso.', 'admin/empreendimentos/'. $empreendimento['id'] .'/editar');
          }
        }
      }else{
        $data = array_merge($data, $this->admin->form_error($this->form_validation->error_array()));
      }
    }

    $this->template->view('admin/master', 'admin/empreendimentos/editar', $data);
  } //editar

  public function excluir($empreendimento_id) {

    $this->empreendimentos_model->excluir_empreendimento($empreendimento_id);

    $this->admin->alerta_redirect('success', 'Empreendimento, vendas e pontuações relacionadas foram excluídos com sucesso.', 'admin/empreendimentos');

  } //excluir

}
