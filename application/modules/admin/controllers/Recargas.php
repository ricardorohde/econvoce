<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recargas extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('vendas_model'));
  }

  public function index($mes = 0, $ano = 0, $page = 1, $empreendimento_id = 0) {
    $this->admin->user_logged();

    $where = array();
    $like = array();

    if($mes){
      $where['MONTH(data_contrato)'] = $mes;
    }

    if($ano){
      $where['YEAR(data_contrato)'] = $ano;
    }

    if($empreendimento_id){
      $this->load->model('empreendimentos_model');
      $empreendimento = $this->empreendimentos_model->obter_empreendimentos(array('params' => array('empreendimento_id' => $empreendimento_id)), true);
      if($empreendimento){
        $data['empreendimento'] = $empreendimento;
        $where['empreendimentos.id'] = $empreendimento_id;
      }
    }

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Recargas',
        'page' => array(
          'one' => 'Recargas'
        ),
        'search_form_action' => ($mes && $ano ? 'admin/vendas/' . $mes . '/' . $ano : 'admin/vendas')
      ),
      'mes' => $mes,
      'ano' => $ano,
      'periodos' => $this->vendas_model->obter_vendas_periodos($empreendimento_id ? array('where' => array('vendas.empreendimento' => $empreendimento_id)) : null)
    ));

    if($this->input->get('q')){
      $like['empreendimentos.apelido'] = $this->input->get('q');
      $like['estagios.nome'] = $this->input->get('q');
      $like['vendas.unidade'] = $this->input->get('q');
      $data['filter'] = true;
    }

    $data['vendas'] = $this->vendas_model->obter_vendas(array(
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

    // print_l($data['vendas']);

    $this->template->view('admin/master', 'admin/recargas/lista', $data);
  }

  public function importar() {
    $this->admin->user_logged();

    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Importar planilha - Recargas',
        'page' => array(
          'one' => 'recargas',
          'two' => 'importar',
        )
      )
    ));

    if($this->input->post('flag')){
      if(isset($_FILES['arquivo']) && $_FILES['arquivo']['size']){

        // $vendas_existentes = 0;
        // $vendas_inseridas = 0;
        // $vendas_identicas = 0;

        $errors_upload = array();

        // $importacoes = array();

        $file_name = $_FILES['arquivo']['name'];
        $file_size = $_FILES['arquivo']['size'];
        $file_tmp = $_FILES['arquivo']['tmp_name'];
        $file_type = $_FILES['arquivo']['type'];
        $file_ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

        $file_path = FCPATH . '/assets/uploads/recargas/';
        $file = $file_path . $file_name;


        $extensions= array("xlsx");

        if(in_array($file_ext, $extensions)=== false){
          $errors_upload[] = "extension not allowed, please choose a XLSX file.";
        }

        if($file_size > 2097152){
          $errors_upload[] = 'File size must be excately 2 MB';
        }

        if(empty($errors_upload)){
          move_uploaded_file($file_tmp, $file);

          sleep(2);

          $this->load->library('excel');

          try {
            $file_type = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($file_type);
            $objPHPExcel = $objReader->load($file);

            $colunas = array(
              'cartao_id' => 'id',
              'cartao_numero' => 'Cartao',
              'status' => 'Status'
            );

            foreach ($objPHPExcel->getAllSheets() as $sheet) {
              $linhas = $sheet->toArray();

              foreach ($linhas as $linha_count => $linha) {
                if(!$linha_count){
                  $colunas_valores = array();
                  foreach($linha as $linha_coluna_count => $linha_coluna){
                    if(in_array($linha_coluna, $colunas)){
                      $colunas[array_search($linha_coluna, $colunas)] = $linha_coluna_count;
                    }
                  }
                }else{
                  foreach($colunas as $field => $column){
                    // if($field === 'data_contrato'){
                    //   $contrato = explode('/', $linha[$column]);
                    //   $linhas_dados[$linha_count][$field] = date("Y-m-d", mktime(0, 0, 0, $contrato[0], $contrato[1], $contrato[2]));
                    // }elseif(in_array($field, array('gerente','corretor','coordenador'))){
                    //   $linhas_dados[$linha_count][$field] = explode('/', $linha[$column]);
                    // }elseif($field === 'vgv_liquido'){
                    //   $linhas_dados[$linha_count][$field] = number_format(filter_var($linha[$column], FILTER_SANITIZE_NUMBER_INT), 2, '.', '');
                    // }else{
                      $linhas_dados[$linha_count][$field] = $linha[$column];
                    //}
                  }

                }
              }
            }

            print_l($linhas_dados);


          } catch (Exception $e) {
            die($e->getMessage());
          }

          $data['upload'] = true;
        }else{
          $data['upload_erros'] = $errors_upload;
        }
      }
    }

    $this->template->view('admin/master', 'admin/recargas/importar', $data);
  } //importar
}
