<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_usuarios($request = array(), $row = false){
    $return = array();
    $where = array();

    // ID
    if(isset($request['params']['usuario_id'])){
      $this->db->where('usuarios.id', $request['params']['usuario_id']);
    }

    // SELECT
    $this->db->select((isset($request['select']) ? $request['select'] : "
      usuarios.*,
      perfis.nome as perfil_nome
    "));

    // FROM
    $this->db->from('usuarios');

    // JOINS
    $this->db->join("perfis", "usuarios.perfil = perfis.id", "inner"); // Estágios

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
          $this->db->order_by('usuarios.nome ASC');
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
        $return_ids = array($return['id']);
      }else{
        $return['results'] = array();

        $return_ids = array();
        $return_count = 0;
        foreach($query->result_array() as $result){
          $return['results'][$return_count] = $result;
          $return_ids[$return_count] = $result['id'];
          $return_count++;
        }
      }

      //$return = $this->obter_vendas_usuarios($return_ids, $return);

      return $return;
    }else{
      return false;
    }
  }

  public function adicionar_usuario($params = array(), $row = TRUE) {
    $usuario = $params;

    $this->db->insert('usuarios', $usuario);
    $usuario_id = $this->db->insert_id();

    if($usuario_id){
      return $this->registros_model->obter_registros('usuarios', array('where' => array('usuarios.id' => $usuario_id)), $row);
    }

    return false;
  }

  public function atualizar_usuario($params = array(), $where = array(), $row = TRUE) {
    $usuario = $params;

    $this->db->update('usuarios', $usuario, $where);

    return $this->registros_model->obter_registros('usuarios', array('where' => $usuario), $row, 'usuarios.*');
  }

  public function atualizar_usuarios($usuarios = array(), $insert = true) {
    $this->db->update('usuarios', array('update' => 1), array('update' => 0));

    $usuarios_adicionados = 0;
    $usuarios_atualizados = 0;
    $usuarios_repetidos = 0;

    $usuarios_processados = array();

    if($usuarios){
      foreach ($usuarios as $item => $usuario) {
        if(in_array($usuario['apelido'], $usuarios_processados)){
          $usuarios_repetidos++;
        }else{
          $usuarios_processados[] = $usuario['apelido'];

          if($usuario_check = $this->obter_usuarios(array('params' => array('where' => array('usuarios.apelido' => $usuario['apelido']))), TRUE)) {
            $usuarios_atualizados++;
            $this->atualizar_usuario(array_merge($usuario, array('status' => 1, 'update' => 0)), array('usuarios.id' => $usuario_check['id']), TRUE);
          }else{
            $usuarios_adicionados++;
            $this->adicionar_usuario(array_merge($usuario, array('status' => 1, 'update' => 0)), TRUE);
          }
        }
      }
    }

    return array(
      'usuarios_adicionados' => $usuarios_adicionados,
      'usuarios_atualizados' => $usuarios_atualizados,
      'usuarios_repetidos' => $usuarios_repetidos
    );
  }
}
