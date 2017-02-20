<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Empreendimentos_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_empreendimentos($request = array(), $row = false){
    $return = array();
    $where = array();

    // ID
    if(isset($request['params']['empreendimento_id'])){
      $this->db->where('empreendimentos.id', $request['params']['empreendimento_id']);
    }

    // SELECT
    $this->db->select((isset($request['select']) ? $request['select'] : "
      empreendimentos.id as empreendimento_id,
      empreendimentos.nome as nome,
      empreendimentos.apelido as apelido,
      empreendimentos.prioridade,
      empreendimentos.estagio,

      
      estagios.nome as estagio_nome,
      estagios.sigla as estagio_sigla,
    "));

    // FROM
    $this->db->from('empreendimentos');

    // JOINS
    $this->db->join("estagios", "empreendimentos.estagio = estagios.id", "inner"); // Estágios

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
        case 'nome':
          $this->db->order_by('empreendimentos.nome ASC');
        break;
      }
    }

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
        $return_ids = array($return['empreendimento_id']);
      }else{
        $return['results'] = array();

        $return_ids = array();
        $return_count = 0;
        foreach($query->result_array() as $result){
          $return['results'][$return_count] = $result;
          $return_ids[$return_count] = $result['empreendimento_id'];
          $return_count++;
        }
      }

      //$return = $this->obter_vendas_usuarios($return_ids, $return);

      return $return;
    }else{
      return false;
    }
  }

  public function adicionar_empreendimento($params = array(), $row = TRUE) {
    $empreendimento = $params;

    $this->db->insert('empreendimentos', $empreendimento);
    $empreendimento_id = $this->db->insert_id();
    if($empreendimento_id){
      return $this->registros_model->obter_registros('empreendimentos', array('where' => array('empreendimentos.id' => $empreendimento_id)), $row, 'empreendimentos.*, prioridades.percentual as prioridade', array(array('prioridades', 'empreendimentos.prioridade = prioridades.id', 'inner')));
    }
    return false;
  }

  public function atualizar_empreendimento($params = array(), $where = array(), $row = TRUE) {
    $empreendimento = $params;

    $this->db->update('empreendimentos', $empreendimento, $where);

    return $this->registros_model->obter_registros('empreendimentos', array('where' => $empreendimento), $row, 'empreendimentos.*, prioridades.percentual as prioridade', array(array('prioridades', 'empreendimentos.prioridade = prioridades.id', 'inner')));
  }

  public function atualizar_empreendimentos($empreendimentos = array(), $insert = true) {
    $this->db->update('empreendimentos', array('update' => 1), array('update' => 0));

    $empreendimentos_adicionados = 0;
    $empreendimentos_atualizados = 0;

    if($empreendimentos){
      foreach ($empreendimentos as $item => $empreendimento) {
        if($empreendimento_check = $this->obter_empreendimentos(array('params' => array('where' => array('empreendimentos.apelido' => $empreendimento['apelido']))), TRUE)) {
          $empreendimentos_atualizados++;
          $this->atualizar_empreendimento(array_merge($empreendimento, array('update' => 0)), array('empreendimentos.id' => $empreendimento_check['empreendimento_id']), TRUE);
        }else{
          $empreendimentos_adicionados++;
          $this->adicionar_empreendimento(array_merge($empreendimento, array('update' => 0)), TRUE);
        }
      }
    }

    return array(
      'empreendimentos_adicionados' => $empreendimentos_adicionados,
      'empreendimentos_atualizados' => $empreendimentos_atualizados
    );
  }

  public function excluir_empreendimento($empreendimento_id) {
    return $this->db->delete('empreendimentos', array('id' => $empreendimento_id));
  }
}
