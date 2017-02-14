<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendas extends Admin_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model(array('vendas_model'));
  }

  public function index($mes = null, $ano = null, $page = 1) {
    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Vendas',
        'page' => array(
          'one' => 'vendas'
        )
      ),
      'mes' => $mes,
      'ano' => $ano,
      'periodos' => $this->vendas_model->obter_vendas_periodos()
    ));


    $where = array();

    if($mes){
      $where['MONTH(data_contrato)'] = $mes;
    }

    if($ano){
      $where['YEAR(data_contrato)'] = $ano;
    }

    $data['vendas'] = $this->vendas_model->obter_vendas(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('vendas_limite'),
          'page' => $page
        ),
        'orderby' => 'data_contrato',
        'where' => $where
      )
    ));

    // print_l($data['vendas']);

    $this->template->view('admin/master', 'admin/vendas/lista', $data);
  }

  public function duplicadas($page = 1) {
    $data = array_merge($this->header, array(
      'section' => array(
        'title' => 'Vendas duplicadas',
        'page' => array(
          'one' => 'vendas',
          'two' => 'duplicadas'
        )
      )
    ));

    $where = array(
    );

    $data['vendas'] = $this->vendas_model->obter_vendas(array(
      'params' => array(
        'pagination' => array(
          'limit' => $this->config->item('vendas_limite'),
          'page' => $page
        ),
        'duplicate' => true,
        'orderby' => 'vendas.data_contrato',
        'where' => $where
      )
    ));

    $this->template->view('admin/master', 'admin/vendas/duplicadas', $data);
  }

  public function importar() {
    $data = array(
      'section' => array(
        'title' => 'Importar planilha - Vendas',
        'page' => array(
          'one' => 'vendas',
          'two' => 'importar',
        )
      )
    );

    if($this->input->post('flag')){
      if(isset($_FILES['arquivo']) && $_FILES['arquivo']['size']){

        $vendas_existentes = 0;
        $vendas_inseridas = 0;

        $errors_upload = array();

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
              'mes' => 'MÊS',
              'ano' => 'ANO',
              'data_contrato' => 'CONTRATO',
              'empreendimento' => 'EMPREEND',
              'unidade' => 'UNID',
              'torre' => 'TORRE',
              'estagio' => 'ESTÁGIO',
              'vgv_liquido' => 'VGV LÍQ',
              'gerente' => 'GERENTE',
              'corretor' => 'CORRETOR',
              'coordenador' => 'COORD'
            );

            foreach ($objPHPExcel->getAllSheets() as $sheet) {
              $linhas = $sheet->toArray();
              $linhas_dados = array();

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
                    if($field === 'data_contrato'){
                      $contrato = explode('/', $linha[$column]);
                      $linhas_dados[$linha_count][$field] = date("Y-m-d", mktime(0, 0, 0, $contrato[0], $contrato[1], $contrato[2]));
                    }elseif(in_array($field, array('gerente','corretor','coordenador'))){
                      $linhas_dados[$linha_count][$field] = explode('/', $linha[$column]);
                    }elseif($field === 'vgv_liquido'){
                      $linhas_dados[$linha_count][$field] = number_format(filter_var($linha[$column], FILTER_SANITIZE_NUMBER_INT), 2, '.', '');
                    }else{
                      $linhas_dados[$linha_count][$field] = $linha[$column];
                    }
                  }
                }
              }

              $importacao = $this->vendas_model->adicionar_pontuacoes($linhas_dados);

              if(isset($importacao['vendas_inseridas'])){
                $vendas_inseridas += $importacao['vendas_inseridas'];
              }
              if(isset($importacao['vendas_existentes'])){
                $vendas_existentes += $importacao['vendas_existentes'];
              }
            }

            $data['importacoes'] = array();
            if($vendas_inseridas){
              $data['importacoes']['vendas_inseridas'] = $vendas_inseridas;
            }
            if($vendas_existentes){
              $data['importacoes']['vendas_existentes'] = $vendas_existentes;
            }

          } catch (Exception $e) {
            die($e->getMessage());
          }

          // //load the excel library
          // $this->load->library('excel');

          // try {
          //  $file_type = PHPExcel_IOFactory::identify($file_path);
          //  $objReader = PHPExcel_IOFactory::createReader($file_type);
          //  $objPHPExcel = $objReader->load($file_path);
          //  $sheets = array();
          //  foreach ($objPHPExcel->getAllSheets() as $sheet) {
          //    $sheets[$sheet->getTitle()] = $sheet->toArray();
          //  }

          //  $this->db->update('empreendimentos', array('update' => 1));

          //  foreach($sheets as $sheet_name => $sheet_rows){
          //    $empreendimento_tipo = ($sheet_name == 'Pronto para morar' ? 1 : ($sheet_name == 'Remanescente' ? 2 : ($sheet_name == 'Lançamento' ? 3 : 0)));

          //    $count = 0;
          //    foreach($sheet_rows as $empreendimento){
          //      if($count){
          //        $empreendimento_array = array(
          //          'tipo' => $empreendimento_tipo,
          //          'nome' => (isset($empreendimento[0]) ? $empreendimento[0] : ''),
          //          'milhas' => (isset($empreendimento[1]) ? $empreendimento[1] : ''),
          //          'update' => 0
          //        );

          //        $erro_mensagem = array();

          //        if(empty($empreendimento[0])){
          //         $erro_mensagem[] = 'O nome é inválido;';
          //        }

          //        if(empty($empreendimento[1])){
          //         $erro_mensagem[] = 'A quantidade de milhas é inválida;';
          //        }

          //        if(empty($erro_mensagem)){
          //          if($empreendimento_check = $this->empreendimentos_model->get_empreendimento(array('empreendimentos.nome' => $empreendimento[0]))){
          //            $this->db->update('empreendimentos', $empreendimento_array, array('id' => $empreendimento_check['id']));
          //          }else{
          //            $this->db->insert('empreendimentos', $empreendimento_array);
          //          }
          //        }else{
          //          if(!isset($erros[$sheet_name])){
          //            $erros[$sheet_name][] = array('Nome', 'Milhas', 'Erro');
          //          }
          //          $erros[$sheet_name][] = array($empreendimento[0], $empreendimento[1], implode(' / ', $erro_mensagem));
          //        }
          //      }

          //      $count++;
          //    }
          //  }

          //  if(isset($erros) && !empty($erros)){
          //    $data['planilha_erros'] = true;

          //    $objPHPExcel = new PHPExcel();
          //    $sheet_count = 0;
          //    foreach($erros as $erro_name => $erro_rows){
          //      if($sheet_count){
          //        $objPHPExcel->createSheet();
          //      }

          //      $objPHPExcel->setActiveSheetIndex($sheet_count);

          //      $objPHPExcel->getActiveSheet()->fromArray($erro_rows, null, 'A1');

          //      $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

          //      $objPHPExcel->getActiveSheet()->setTitle($erro_name);

          //      $sheet_count++;
          //    }

          //    // Redirect output to a client’s web browser (Excel2007)
          //    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          //    header('Content-Disposition: attachment;filename="empreendimentos-erros-'. date("YmdHis", time()) .'.xlsx"');
          //    header('Cache-Control: max-age=0');
          //    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
          //    $objWriter->save('php://output');
          //    exit;
          //  }
          // } catch (Exception $e) {
          //  die($e->getMessage());
          // }

          // $data['upload'] = true;
        }else{
          $data['upload_erros'] = $errors_upload;
        }
      }
    }

    $this->template->view('admin/master', 'admin/vendas/importar', $data);
  }
}
