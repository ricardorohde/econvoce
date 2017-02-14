<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendas_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_vendas($request = array(), $row = false){
    $return = array();
    $where = array();

    // ID
    if(isset($request['params']['venda_id'])){
      $this->db->where('vendas.id', $request['params']['venda_id']);
    }

    // Slug
    if(isset($request['params']['empreendimento_id']) && !empty($request['params']['empreendimento_id'])){
      $this->db->where('vendas.empreendimento', $request['params']['empreendimento_id']);
    }

    // SELECT
    $this->db->select((isset($request['select']) ? $request['select'] : "
      vendas.id as venda_id,
      vendas.unidade,
      vendas.torre,
      DATE_FORMAT(vendas.data_contrato, '%d/%m/%Y') as data_contrato,
      vendas.vgv_liquido,
      vendas.parente,
      vendas.status,

      empreendimentos.nome as empreendimento_nome,

      estagios.nome as estagio_nome,
      estagios.sigla as estagio_sigla,
    "));

    // FROM
    $this->db->from('vendas');

    // JOINS
    $this->db->join("empreendimentos", "vendas.empreendimento = empreendimentos.id", "inner"); // Empreendimentos
    $this->db->join("estagios", "vendas.estagio = estagios.id", "inner"); // Estágios

    // WHERE
    if(isset($request['params']['where']) && !empty($request['params']['where'])){
      $this->db->where($request['params']['where']);
    }

    // WHERE IN
    if(isset($request['params']['where_in']) && !empty($request['params']['where_in'])){
      foreach ($request['params']['where_in'] as $key => $value) {
        $this->db->where_in($key, $value);
      }
    }

    // ORDER BY
    if(isset($request['params']['orderby']) && !empty($request['params']['orderby'])){
      switch ($request['params']['orderby']) {
        case 'data_contrato':
          $this->db->order_by('vendas.data_contrato ASC');
        break;
      }
    }

    if(isset($request['params']['duplicate']) && $request['params']['duplicate']){
      $this->db->join("vendas vendas_duplicadas", "vendas_duplicadas.parente = vendas.id", "inner"); // Duplicadas
    }

    //$this->db->group_by('imoveis.id');

    // GET ROWS COUNT
    $return['total_rows'] = $this->registros_model->obter_registros_count($this->db->_compile_select());
    $return['current_page'] = (isset($request['params']['pagination']['page']) ? $request['params']['pagination']['page'] : 1);


    // PAGINATION
    if(isset($request['params']['pagination']) && !empty($request['params']['pagination'])){
      if(isset($request['params']['pagination']['limit']) && !empty($request['params']['pagination']['limit'])){
        $limit = (isset($request['params']['pagination']['limit']) && !empty($request['params']['pagination']['limit']) ? $request['params']['pagination']['limit'] : 12);

        if(isset($request['params']['pagination']['page']) && !empty($request['params']['pagination']['page'])){
          $page = max(0, ($request['params']['pagination']['page'] - 1) * $limit);

          $return['pagination'] = $this->admin->create_pagination($return['current_page'], $limit, $return['total_rows'], rtrim((isset($request['params']['base_url']) ? base_url($request['params']['base_url']) : current_url()), "/" . $request['params']['pagination']['page']), (isset($request['params']['url_suffix']) ? $request['params']['url_suffix'] : null));
        }
      }
    }

    if(isset($limit) && !empty($limit)){
      if(isset($page) && !empty($page)){
        $this->db->limit($limit, $page);
      }else{
        $this->db->limit($limit);
      }
    }

    $sql = $this->db->_compile_select();
    $return['sql'] = $sql;

    $query = $this->db->get();

    if($query->num_rows()){
      if($row){
        $return = $query->row_array();
        $return_ids = array($return['venda_id']);
      }else{
        $return['results'] = array();

        $return_ids = array();
        $return_count = 0;
        foreach($query->result_array() as $result){
          $return['results'][$return_count] = $result;
          $return_ids[$return_count] = $result['venda_id'];
          $return_count++;
        }
      }

      if(isset($request['params']['duplicate']) && $request['params']['duplicate']){
        $return = $this->obter_vendas_duplicadas($return_ids, $return);
      }

      $return = $this->obter_vendas_usuarios($return_ids, $return);

      return $return;
    }else{
      return false;
    }
  }

  public function obter_vendas_usuarios($return_ids, $return) {

    $usuarios = $this->registros_model->obter_registros('vendas_usuarios', array(
      'where_in' => array(
        'vendas_usuarios.venda' => $return_ids
      )
    ), false, 'vendas_usuarios.venda as venda_id, vendas_usuarios.pontuacao, usuarios.nome as usuario_nome, usuarios.apelido as usuario_apelido, perfis.nome as perfil_nome, perfis.slug as perfil_slug, perfis.sigla as perfil_sigla', array(
      array('usuarios', 'vendas_usuarios.usuario = usuarios.id', 'inner'),
      array('perfis', 'vendas_usuarios.perfil = perfis.id', 'inner')
    ));


    if(!empty($usuarios)){
      if($return){
        foreach ($usuarios as $usuario) {
          if(isset($return['results'])){
            $usuario_key = array_search ($usuario['venda_id'], $return_ids);
            $return['results'][$usuario_key]['usuarios'][] = $usuario;
          }else{
            $return['usuarios'][] = $usuario;
          }
        }
        return $return;
      }

      return $usuarios;
    }else{
      if($return) return $return;
    }

    return false;
  }


  public function obter_vendas_duplicadas($return_ids, $return) {
    $vendas = $this->obter_vendas(array(
      'params' => array(
        'where_in' => array(
          'vendas.parente' => $return_ids
        )
      )
    ));

    if(isset($vendas['results']) && !empty($vendas['results'])){
      if($return){
        foreach ($vendas['results'] as $venda) {

          if(isset($return['results'])){
            $venda_key = array_search ($venda['parente'], $return_ids);
            $return['results'][$venda_key]['duplicados'][] = $venda;
          }else{
            $return['duplicados'][] = $venda;
          }
        }
        return $return;
      }

      return $vendas['results'];
    }else{
      if($return) return $return;
    }

    return false;

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
        $estagio = $this->registros_model->obter_registros('estagios', array('where' => array('estagios.apelido' => $excel_linha['estagio'])), TRUE);

        if(!$estagio){
          $estagio = $this->registros_model->obter_registros('estagios', array('where' => array('estagios.id' => 1)), TRUE);
        }

        $estagios_array[$estagio['id']] = $excel_linha['estagio'];
        $estagios[$estagio['id']] = $estagio;
      }

      $venda['estagio'] = $estagio['id'];

      // EMPREENDIMENTO
      if(in_array($excel_linha['empreendimento'], $empreendimentos)){
        $empreendimento = array_search($excel_linha['empreendimento'], $empreendimentos);
      }else{
        if($empreendimento = $this->registros_model->obter_registros('empreendimentos', array('where' => array('empreendimentos.apelido' => $excel_linha['empreendimento'])), 'id')) {
        }else{
          $empreendimento = $this->empreendimentos_model->adicionar_empreendimento(array('nome' => $excel_linha['empreendimento'], 'apelido' => $excel_linha['empreendimento'], 'estagio' => $estagio['id'], 'status' => 2), 'id');
        }
        $empreendimentos[$empreendimento] = $excel_linha['empreendimento'];
      }
      $venda['empreendimento'] = $empreendimento;

      //VENDA
      if($venda_check = $this->registros_model->obter_registros('vendas', array('where' => $venda), TRUE)) {
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
            if($usuario = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $corretor_apelido)), 'id')) {
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
            if($usuario = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $gerente_apelido)), 'id')) {
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

      //COORDENADORES
      if(isset($excel_linha['coordenador']) && (isset($excel_linha['coordenador'][0]) && !empty($excel_linha['coordenador'][0]))){
        $coordenadores_cleared = $excel_linha['coordenador'];

        if($coordenadores_cleared && isset($excel_linha['corretor']) && (isset($excel_linha['corretor'][0]) && !empty($excel_linha['corretor'][0]))){
          $coordenadores_cleared = $this->admin->clear_users($coordenadores_cleared, $excel_linha['corretor']);
        }

        if($coordenadores_cleared && isset($excel_linha['gerente']) && (isset($excel_linha['gerente'][0]) && !empty($excel_linha['gerente'][0]))){
          $coordenadores_cleared = $this->admin->clear_users($coordenadores_cleared, $excel_linha['gerente']);
        }

        if(!empty($coordenadores_cleared)){
          $coordenador_log = false;
          $coordenadores_count = count($excel_linha['coordenador']);
          $usuario_insert = array();

          foreach ($coordenadores_cleared as $coordenador_apelido) {

            if(in_array($coordenador_apelido, $usuarios)){
              $usuario = array_search($coordenador_apelido, $usuarios);
            }else{
              if($usuario = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $coordenador_apelido)), 'id')) {
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
      }

      $excel_count++;
    }

    return array(
      'vendas_inseridas' => $vendas_inseridas,
      'vendas_existentes' => $vendas_existentes,
    );
  }

  public function obter_vendas_periodos() {
    // SELECT
    $this->db->select('MONTH(vendas.data_contrato) AS mes, YEAR(vendas.data_contrato) AS ano');

    // FROM
    $this->db->from('vendas');

    // ORDER
    $this->db->order_by('MONTH(vendas.data_contrato) DESC, YEAR(vendas.data_contrato) DESC');

    //GROUPBY
    $this->db->group_by(array('MONTH(vendas.data_contrato)','YEAR(vendas.data_contrato)'));

    // LIMIT
    $this->db->limit(3);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return array_reverse($query->result_array());
    }

    return false;
  }

  // public function get_vendas($params = array(), $select = '*', $join = array(), $row = false){
  //
  //   $this->db->select($select);

  //
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


  // }

  // public function get_pontuacao($params = array(), $select = '', $join = array()){
  //   return $this->get_pontuacoes($params, $select, $join, true);
  // }

  // public function calcula_pontuacoes($request){
  //   print_l($request);

  // }
}
