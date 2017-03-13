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
      empreendimentos.apelido as empreendimento_apelido,

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

    // LIKE
    if(isset($request['params']['like']) && !empty($request['params']['like'])){
      $like_count = 0;
      foreach ($request['params']['like'] as $key => $value) {
        if(!$like_count){
          $this->db->like($key, $value);
        }else{
          $this->db->or_like($key, $value);
        }
        $like_count++;
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

    $usuarios = $this->registros_model->obter_registros(
      'vendas_usuarios',
      array(
        'where_in' => array(
          'vendas_usuarios.venda' => $return_ids
        )
      ),
      false,
      'vendas_usuarios.venda as venda_id, vendas_usuarios.pontuacao, usuarios.nome as usuario_nome, usuarios.apelido as usuario_apelido, perfis.nome as perfil_nome, perfis.slug as perfil_slug, perfis.sigla as perfil_sigla',
      array(
        array('usuarios', 'vendas_usuarios.usuario = usuarios.id', 'inner'),
        array('perfis', 'vendas_usuarios.perfil = perfis.id', 'inner')
      ),
      array(
        'perfis.id' => 'ASC',
        'usuarios.nome' => 'DESC'
      )
    );


    if(!empty($usuarios)){
      if($return){
        foreach ($usuarios as $usuario) {
          if(isset($return['results'])){
            $usuario_key = array_search ($usuario['venda_id'], $return_ids);
            $return['results'][$usuario_key]['usuarios'][$usuario['perfil_slug']][] = $usuario;
          }else{
            $return['usuarios'][$usuario['perfil_slug']][] = $usuario;
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
    $vendas_identicas = 0;

    $empreendimentos = array();
    $usuarios = array();
    $perfis = array();
    $get_perfis = $this->registros_model->obter_registros('perfis');
    foreach($get_perfis as $perfil){
      $perfis[$perfil['slug']] = $perfil;
    }
    $estagios = $this->registros_model->obter_registros('estagios');

    $reais_x_pontos = (int) $this->config->item('reais_x_pontos');

    $venda_usuarios = array();

    foreach($excel_linhas as $excel_linha){
      $venda_processo = true;
      $venda = array();

      // Estágio
      $estagio = in_multiarray($excel_linha['estagio'], 'apelido', $estagios, true);
      $venda['estagio'] = $estagio['id'];

      if($estagio){

        // Empreendimento
        if(!$empreendimento = in_multiarray($excel_linha['empreendimento'], 'apelido', $empreendimentos, true)){
          if(!$empreendimento = $this->registros_model->obter_registros('empreendimentos', array('where' => array('empreendimentos.apelido' => $excel_linha['empreendimento'])), true, 'empreendimentos.*, prioridades.percentual as prioridade', array(array('prioridades', 'empreendimentos.prioridade = prioridades.id', 'inner')))) {
            $empreendimento = $this->empreendimentos_model->adicionar_empreendimento(array('nome' => $excel_linha['empreendimento'], 'apelido' => $excel_linha['empreendimento'], 'estagio' => $estagio['id'], 'status' => 2), true);
          }
          $empreendimentos[] = $empreendimento;
        }

        $venda['empreendimento'] = $empreendimento['id'];
        $venda['unidade'] = $excel_linha['unidade'];
        $venda['torre'] = ($excel_linha['torre'] === '-' ? null : $excel_linha['torre']);

        //Checa se já existe a venda
        if($venda_check = $this->registros_model->obter_registros('vendas', array('where' => $venda), TRUE)) {

          // Se a data do contrato for a mesma
          if($venda_check['data_contrato'] == $excel_linha['data_contrato']){
            $vendas_identicas++;
            $venda_processo = false;
          }else{
            $parent = isset($venda_check['parente']) && $venda_check['parente'] ? $venda_check['parente'] : $venda_check['id'];

            $this->db->update('vendas', array('status' => 1), array('id' => $parent));

            $venda['parente'] = $parent;

            $vendas_existentes++;
          }
        }

        if($venda_processo){
          //Data contrato
          $venda['data_contrato'] = $excel_linha['data_contrato'];

          //VGV Líquido
          $venda['vgv_liquido'] = $excel_linha['vgv_liquido'];

          $this->db->set('data_criado', 'NOW()', FALSE);
          $this->db->insert('vendas', $venda);

          $venda_id = $this->db->insert_id();

          if($venda_id){
            $usuarios_log = false;

            //CORRETORES
            if(isset($excel_linha['corretor']) && (isset($excel_linha['corretor'][0]) && !empty($excel_linha['corretor'][0]))){
              $corretores_count = count($excel_linha['corretor']);
              if($usuarios_log) echo '<hr>Corretores<br>';

              foreach ($excel_linha['corretor'] as $corretor_apelido) {
                if(!$corretor = in_multiarray($corretor_apelido, 'apelido', $usuarios, true)){
                  if(!$corretor = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $corretor_apelido)), true)) {
                    $corretor = $this->usuarios_model->adicionar_usuario(array('nome' => $corretor_apelido, 'apelido' => $corretor_apelido, 'perfil' => 1, 'status' => 2), true);
                  }
                  $usuarios[] = $corretor;
                }

                $pontuacao_perfil = ($perfis['corretor']['percentual'] / 100) * $venda['vgv_liquido'];
                if($usuarios_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['corretor']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

                $pontuacao_prioridade = ($empreendimento['prioridade'] / 100) * $estagio['percentual'];
                if($usuarios_log) echo 'Prioridade: ' . $estagio['percentual'] . ' * ' . $empreendimento['prioridade'] . '% = ' . $pontuacao_prioridade . '<br>';

                $pontuacao_estagio = ($pontuacao_prioridade / 100) * $pontuacao_perfil;
                if($usuarios_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $pontuacao_prioridade . '% = ' . $pontuacao_estagio . '<br>';

                $pontuacao_divisao = $pontuacao_estagio / $corretores_count;
                if($usuarios_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $corretores_count . ' = ' . $pontuacao_divisao . '<br>';

                $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
                $pontuacao_final = $this->admin->round_points($pontuacao_reais_vs_pontos, 50);
                if($usuarios_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
                if($usuarios_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $pontuacao_final . '<br>';
                if($usuarios_log) echo '<hr>';

                $venda_usuarios[] = array(
                  'venda' => $venda_id,
                  'usuario' => $corretor['id'],
                  'perfil' => $perfis['corretor']['id'],
                  'pontuacao' => $pontuacao_final
                );
              }
            }

            //GERENTES
            if(isset($excel_linha['gerente']) && (isset($excel_linha['gerente'][0]) && !empty($excel_linha['gerente'][0]))){
              $gerentes_count = count($excel_linha['gerente']);
              if($usuarios_log) echo '<hr>Gerentes<br>';

              foreach ($excel_linha['gerente'] as $gerente_apelido) {
                if(!$gerente = in_multiarray($gerente_apelido, 'apelido', $usuarios, true)){
                  if(!$gerente = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $gerente_apelido)), true)) {
                    $gerente = $this->usuarios_model->adicionar_usuario(array('nome' => $gerente_apelido, 'apelido' => $gerente_apelido, 'perfil' => 1, 'status' => 2), true);
                  }
                  $usuarios[] = $gerente;
                }

                $pontuacao_perfil = ($perfis['gerente']['percentual'] / 100) * $venda['vgv_liquido'];
                if($usuarios_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['gerente']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

                $pontuacao_prioridade = ($empreendimento['prioridade'] / 100) * $estagio['percentual'];
                if($usuarios_log) echo 'Prioridade: ' . $estagio['percentual'] . ' * ' . $empreendimento['prioridade'] . '% = ' . $pontuacao_prioridade . '<br>';

                $pontuacao_estagio = ($pontuacao_prioridade / 100) * $pontuacao_perfil;
                if($usuarios_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $pontuacao_prioridade . '% = ' . $pontuacao_estagio . '<br>';

                $pontuacao_divisao = $pontuacao_estagio / $gerentes_count;
                if($usuarios_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $gerentes_count . ' = ' . $pontuacao_divisao . '<br>';

                $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
                $pontuacao_final = $this->admin->round_points($pontuacao_reais_vs_pontos, 50);
                if($usuarios_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
                if($usuarios_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $pontuacao_final . '<br>';
                if($usuarios_log) echo '<hr>';

                $venda_usuarios[] = array(
                  'venda' => $venda_id,
                  'usuario' => $gerente['id'],
                  'perfil' => $perfis['gerente']['id'],
                  'pontuacao' => $pontuacao_final
                );
              }
            }

            //COORDENADORES
            if(isset($excel_linha['coordenador']) && (isset($excel_linha['coordenador'][0]) && !empty($excel_linha['coordenador'][0]))){
              $coordenadores_cleared = $excel_linha['coordenador'];
              $coordenadores_count = count($excel_linha['gerente']);
              if($usuarios_log) echo '<hr>Coordenadores<br>';

              if($coordenadores_cleared && isset($excel_linha['corretor']) && (isset($excel_linha['corretor'][0]) && !empty($excel_linha['corretor'][0]))){
                $coordenadores_cleared = $this->admin->clear_users($coordenadores_cleared, $excel_linha['corretor']);
              }

              if($coordenadores_cleared && isset($excel_linha['gerente']) && (isset($excel_linha['gerente'][0]) && !empty($excel_linha['gerente'][0]))){
                $coordenadores_cleared = $this->admin->clear_users($coordenadores_cleared, $excel_linha['gerente']);
              }

              if(!empty($coordenadores_cleared)){
                foreach ($coordenadores_cleared as $coordenador_apelido) {
                  if(!$coordenador = in_multiarray($coordenador_apelido, 'apelido', $usuarios, true)){
                    if(!$coordenador = $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.apelido' => $coordenador_apelido)), true)) {
                      $coordenador = $this->usuarios_model->adicionar_usuario(array('nome' => $coordenador_apelido, 'apelido' => $coordenador_apelido, 'perfil' => 1, 'status' => 2), true);
                    }
                    $usuarios[] = $coordenador;
                  }

                  $pontuacao_perfil = ($perfis['coordenador']['percentual'] / 100) * $venda['vgv_liquido'];
                  if($usuarios_log) echo 'Perfil: ' . $venda['vgv_liquido'] . ' * ' . $perfis['coordenador']['percentual'] . '% = ' . $pontuacao_perfil . '<br>';

                  $pontuacao_prioridade = ($empreendimento['prioridade'] / 100) * $estagio['percentual'];
                  if($usuarios_log) echo 'Prioridade: ' . $estagio['percentual'] . ' * ' . $empreendimento['prioridade'] . '% = ' . $pontuacao_prioridade . '<br>';

                  $pontuacao_estagio = ($pontuacao_prioridade / 100) * $pontuacao_perfil;
                  if($usuarios_log) echo 'Estágio: ' . $pontuacao_perfil . ' * ' . $pontuacao_prioridade . '% = ' . $pontuacao_estagio . '<br>';

                  $pontuacao_divisao = $pontuacao_estagio / $coordenadores_count;
                  if($usuarios_log) echo 'Divisão: ' . $pontuacao_estagio . ' / ' . $coordenadores_count . ' = ' . $pontuacao_divisao . '<br>';

                  $pontuacao_reais_vs_pontos = $pontuacao_divisao * $reais_x_pontos;
                  $pontuacao_final = $this->admin->round_points($pontuacao_reais_vs_pontos, 50);
                  if($usuarios_log) echo 'Reais vs Pontos: ' . $pontuacao_divisao . ' * ' . $reais_x_pontos . ' = ' . $pontuacao_reais_vs_pontos . '<br>';
                  if($usuarios_log) echo 'Reais vs Pontos (round): ROUND(' . $pontuacao_reais_vs_pontos . ' / ' . 50 . ') * ' . 50 . ' = ' . $pontuacao_final . '<br>';
                  if($usuarios_log) echo '<hr>';

                  $venda_usuarios[] = array(
                    'venda' => $venda_id,
                    'usuario' => $coordenador['id'],
                    'perfil' => $perfis['coordenador']['id'],
                    'pontuacao' => $pontuacao_final
                  );
                }
              }
            }
          }

          $vendas_inseridas++;
        }
      }
    } //foreach

    if(!empty($venda_usuarios)){
      // Vendas x Usuários
      $this->db->insert_batch('vendas_usuarios', $venda_usuarios);
    }

    if($usuarios){
      $usuarios_novidades = array();
      foreach ($usuarios as $usuario) {
        $usuarios_novidades[] = array(
          'id' => $usuario['id'],
          'novidades' => 1
        );
      }
      $this->db->update_batch('usuarios', $usuarios_novidades, 'id');
    }

    return array(
      'vendas_inseridas' => $vendas_inseridas,
      'vendas_existentes' => $vendas_existentes,
      'vendas_identicas' => $vendas_identicas
    );

  }

  public function obter_vendas_periodos($request = array()) {
    // SELECT
    $this->db->select('MONTH(vendas.data_contrato) AS mes, YEAR(vendas.data_contrato) AS ano');

    // FROM
    $this->db->from('vendas');

    // ORDER
    $this->db->order_by('MONTH(vendas.data_contrato) DESC, YEAR(vendas.data_contrato) DESC');

    // WHERE
    if(isset($request['where']) && !empty($request['where'])){
      $this->db->where($request['where']);
    }

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

  public function atualizar_vgv($empreendimentos = array()) {
    $this->load->model(array('empreendimentos_model'));

    if(!empty($empreendimentos)){
      foreach ($empreendimentos as $empreendimento) {
        if($empreendimento_check = $this->registros_model->obter_registros('empreendimentos', array('where' => array('empreendimentos.apelido' => $empreendimento['nome'])), true)) {
          $this->empreendimentos_model->atualizar_empreendimento(array('vgv_liquido' => $empreendimento['vgv_liquido']), array('empreendimentos.id' => $empreendimento_check['id']), FALSE);
        }
      }
    }

    return true;
  }

}
