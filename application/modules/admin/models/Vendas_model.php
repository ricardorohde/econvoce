<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendas_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function adicionar_pontuacoes($excel_linhas) {
    $this->load->model(array('empreendimentos_model','usuarios_model'));

    $vendas_existentes = 0;
    $vendas_inseridas = 0;

    $empreendimentos = array();
    $estagios = array();
    $estagios_array = array();
    $usuarios = array();
    $excel_count = 0;

    $reais_x_pontos = (int) $this->config->item('reais_x_pontos');

    $perfis = array();
    $obter_perfis = $this->registros_model->obter_registros('perfis');
    foreach($obter_perfis as $perfil){
      $perfis[$perfil['slug']] = array(
        'id' => $perfil['id'],
        'sigla' => $perfil['sigla'],
        'percentual' => $perfil['percentual']
      );
    }

    foreach($excel_linhas as $excel_linha){
      $venda = array(
        'unidade' => $excel_linha['unidade'],
        'torre' => $excel_linha['torre']
      );

      // ESTAGIO
      if(in_array($excel_linha['estagio'], $estagios_array)){
        $estagio = $estagios[array_search($excel_linha['estagio'], $estagios_array)];
      }else{
        $estagio = $this->registros_model->obter_registros('estagios', array('estagios.apelido' => $excel_linha['estagio']), TRUE);

        if(!$estagio){
          $estagio = $this->registros_model->obter_registros('estagios', array('estagios.id' => 1), TRUE);
        }

        $estagios_array[$estagio['id']] = $excel_linha['estagio'];
        $estagios[$estagio['id']] = $estagio;
      }

      $venda['estagio'] = $estagio['id'];

      // EMPREENDIMENTO
      if(in_array($excel_linha['empreendimento'], $empreendimentos)){
        $empreendimento = array_search($excel_linha['empreendimento'], $empreendimentos);
      }else{
        if($empreendimento = $this->registros_model->obter_registros('empreendimentos', array('empreendimentos.apelido' => $excel_linha['empreendimento']), 'id')) {
        }else{
          $empreendimento = $this->empreendimentos_model->adicionar_empreendimento(array('nome' => $excel_linha['empreendimento'], 'apelido' => $excel_linha['empreendimento'], 'estagio' => $estagio['id'], 'status' => 2), 'id');
        }
        $empreendimentos[$empreendimento] = $excel_linha['empreendimento'];
      }
      $venda['empreendimento'] = $empreendimento;

      //VENDA
      if($venda_check = $this->registros_model->obter_registros('vendas', $venda, TRUE)) {
        $this->db->update('vendas', array('status' => 1), array('id' => $venda_check['id']));

        $venda['parente'] = isset($venda_check['parente']) && $venda_check['parente'] ? $venda_check['parente'] : $venda_check['id'];
        $venda['status'] = 1;

        $vendas_existentes++;
      }
      
      $vendas_inseridas++;
      $venda['data_contrato'] = $excel_linha['data_contrato'];
      $venda['vgv_liquido'] = $excel_linha['vgv_liquido'];
      
      $this->db->insert('vendas', $venda);

      $venda_id = $this->db->insert_id();

      //CORRETORES
      if(isset($excel_linha['corretor']) && (isset($excel_linha['corretor'][0]) && !empty($excel_linha['corretor'][0]))){
        $corretor_log = false;
        $corretores_count = count($excel_linha['corretor']);
        $usuario_insert = array();

        foreach ($excel_linha['corretor'] as $corretor_apelido) {

          if(in_array($corretor_apelido, $usuarios)){
            $usuario = array_search($corretor_apelido, $usuarios);
          }else{
            if($usuario = $this->registros_model->obter_registros('usuarios', array('usuarios.apelido' => $corretor_apelido), 'id')) {
            }else{
              $usuario = $this->usuarios_model->adicionar_usuario(array('nome' => $corretor_apelido, 'apelido' => $corretor_apelido, 'perfil' => 1, 'status' => 2), 'id');
            }
          }

          $pontuacao_perfil = ($perfis['corretor']['percentual'] / 100) * $venda['vgv_liquido'];
          if($corretor_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['corretor']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

          $pontuacao_estagio = ($estagio['percentual'] / 100) * $pontuacao_perfil;
          if($corretor_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $estagio['percentual'] . '% = ' . $pontuacao_estagio . '<br>';

          $pontuacao_divisao = $pontuacao_estagio / $corretores_count;
          if($corretor_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $corretores_count . ' = ' . $pontuacao_divisao . '<br>';

          $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
          if($corretor_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
          if($corretor_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $this->admin->round_points($pontuacao_reais_vs_pontos, 50) . '<br>';
          if($corretor_log) echo '<hr>';

          $usuario_insert[] = array(
            'venda' => $venda_id,
            'usuario' => $usuario,
            'perfil' => $perfis['corretor']['id'],
            'pontuacao' => $this->admin->round_points($pontuacao_reais_vs_pontos, 50)
          );


        }

        $this->db->insert_batch('vendas_usuarios', $usuario_insert);
      }

      //GERENTES
      if(isset($excel_linha['gerente']) && (isset($excel_linha['gerente'][0]) && !empty($excel_linha['gerente'][0]))){
        $gerente_log = false;
        $gerentes_count = count($excel_linha['gerente']);
        $usuario_insert = array();

        foreach ($excel_linha['gerente'] as $gerente_apelido) {

          if(in_array($gerente_apelido, $usuarios)){
            $usuario = array_search($gerente_apelido, $usuarios);
          }else{
            if($usuario = $this->registros_model->obter_registros('usuarios', array('usuarios.apelido' => $gerente_apelido), 'id')) {
            }else{
              $usuario = $this->usuarios_model->adicionar_usuario(array('nome' => $gerente_apelido, 'apelido' => $gerente_apelido, 'perfil' => 1, 'status' => 2), 'id');
            }
          }

          $pontuacao_perfil = ($perfis['gerente']['percentual'] / 100) * $venda['vgv_liquido'];
          if($gerente_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['gerente']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

          $pontuacao_estagio = ($estagio['percentual'] / 100) * $pontuacao_perfil;
          if($gerente_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $estagio['percentual'] . '% = ' . $pontuacao_estagio . '<br>';

          $pontuacao_divisao = $pontuacao_estagio / $gerentes_count;
          if($gerente_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $gerentes_count . ' = ' . $pontuacao_divisao . '<br>';

          $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
          if($gerente_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
          if($gerente_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $this->admin->round_points($pontuacao_reais_vs_pontos, 50) . '<br>';
          if($gerente_log) echo '<hr>';

          $usuario_insert[] = array(
            'venda' => $venda_id,
            'usuario' => $usuario,
            'perfil' => $perfis['gerente']['id'],
            'pontuacao' => $this->admin->round_points($pontuacao_reais_vs_pontos, 50)
          );


        }

        $this->db->insert_batch('vendas_usuarios', $usuario_insert);
      }

      echo 'Coordenador:';
      print_l($excel_linha['coordenador']);
      echo 'Gerente:';
      print_l($excel_linha['gerente']);
      echo 'Ambos:';
      print_l(array_intersect($excel_linha['coordenador'], $excel_linha['gerente']));
      echo '<hr>';

// if(isset($excel_linha['coordenador'])){

//   $coordenadores_linha = $excel_linha['coordenador'];



//   echo 'Antes<br>';
//   print_l($excel_linha['coordenador']);

//   foreach ($excel_linha['coordenador'] as $coordenador_apelido) {
//     if(!empty($coordenador_apelido)){
      
//       // SE FOR CORRETOR
//       if(in_array($coordenador_apelido, $excel_linha['corretor'])){
//         // NÃO RECEBE COMO COORDENADOR
//         $corretor_key = array_search($coordenador_apelido, $excel_linha['corretor']);
//         echo '>>' . $corretor_key . '<<<br>';
//         echo $excel_linha['corretor'][$corretor_key] . '<br>';
//         unset($excel_linha['corretor'][$corretor_key]);
//         echo 'Excluiu (corretor): ' . $corretor_key . '->' . $coordenador_apelido . '<br>';
//       }

//       // SE FOR GERENTE
//       if(in_array($coordenador_apelido, $excel_linha['gerente'])){
//         // NÃO RECEBE COMO COORDENADOR
//         $gerente_key = array_search($coordenador_apelido, $excel_linha['gerente']);
//         echo '>>' . $gerente_key . '<<<br>';
//         echo $excel_linha['gerente'][$gerente_key] . '<br>';
//         unset($excel_linha['gerente'][$gerente_key]);
//         echo 'Excluiu (gerente): ' . $gerente_key . '->' . $coordenador_apelido . '<br>';
//       }
//     }
//   }

//   echo 'Depois<br>';
//   print_l($excel_linha['coordenador']);
//   echo '<hr>';
// }


      //COORDENADORES
      if(isset($excel_linha['coordenador']) && (isset($excel_linha['coordenador'][0]) && !empty($excel_linha['coordenador'][0]))){
        $coordenador_log = false;
        $coordenadores_count = count($excel_linha['coordenador']);
        $usuario_insert = array();

        foreach ($excel_linha['coordenador'] as $coordenador_apelido) {

          if(in_array($coordenador_apelido, $usuarios)){
            $usuario = array_search($coordenador_apelido, $usuarios);
          }else{
            if($usuario = $this->registros_model->obter_registros('usuarios', array('usuarios.apelido' => $coordenador_apelido), 'id')) {
            }else{
              $usuario = $this->usuarios_model->adicionar_usuario(array('nome' => $coordenador_apelido, 'apelido' => $coordenador_apelido, 'perfil' => 1, 'status' => 2), 'id');
            }
          }

          $pontuacao_perfil = ($perfis['coordenador']['percentual'] / 100) * $venda['vgv_liquido'];
          if($coordenador_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['coordenador']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

          $pontuacao_estagio = ($estagio['percentual'] / 100) * $pontuacao_perfil;
          if($coordenador_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $estagio['percentual'] . '% = ' . $pontuacao_estagio . '<br>';

          $pontuacao_divisao = $pontuacao_estagio / $coordenadores_count;
          if($coordenador_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $coordenadores_count . ' = ' . $pontuacao_divisao . '<br>';

          $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
          if($coordenador_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
          if($coordenador_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $this->admin->round_points($pontuacao_reais_vs_pontos, 50) . '<br>';
          if($coordenador_log) echo '<hr>';

          $usuario_insert[] = array(
            'venda' => $venda_id,
            'usuario' => $usuario,
            'perfil' => $perfis['coordenador']['id'],
            'pontuacao' => $this->admin->round_points($pontuacao_reais_vs_pontos, 50)
          );


        }

        $this->db->insert_batch('vendas_usuarios', $usuario_insert);
      }

      //print_l($excel_linha);



      $excel_count++;
    }

    return array(
      'vendas_inseridas' => $vendas_inseridas,
      'vendas_existentes' => $vendas_existentes,
    );
  }

  // public function get_vendas($params = array(), $select = '*', $join = array(), $row = false){
  //   // SELECT
  //   $this->db->select($select);

  //   // FROM
  //   $this->db->from('vendas');

  //   // JOIN
  //   if($join){

  //     foreach($join as $join_item){
  //       $this->db->join($join_item[0], $join_item[1], $join_item[2]);
  //     }
  //   }

  //   // WHERE
  //   if($params){
  //     foreach($params as $key => $value){
  //       $this->db->where($key, $value);
  //     }
  //   }

  //   $query = $this->db->get();

  //   if ($query->num_rows() > 0) {
  //     if($row){
  //       return $query->row_array();
  //     }
  //     return $query->result_array();
  //   }
  //   return false;
  // }

  // public function get_pontuacao($params = array(), $select = '', $join = array()){
  //   return $this->get_pontuacoes($params, $select, $join, true);
  // }

  // public function calcula_pontuacoes($request){
  //   print_l($request);

  // }
}