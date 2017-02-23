<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_ranking($request = array(), $row = false){
    $return = array();
    $where = array();

    // SELECT
    $this->db->select((isset($request['select']) ? $request['select'] : "
      (SELECT count( * ) FROM vendas_usuarios b WHERE b.pontuacao > vendas_usuarios.pontuacao ) AS rank
      usuarios.id,
      usuarios.nome,
      usuarios.apelido,
      SUM(pontuacao) as pontuacao,
      perfis.nome as perfil

    "));

    // FROM
    $this->db->from('vendas_usuarios');

    // JOINS
    $this->db->join("usuarios", "vendas_usuarios.usuario = usuarios.id", "inner"); // Usuarios
    $this->db->join("perfis", "vendas_usuarios.perfil = perfis.id", "inner"); // Estágios

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
    $this->db->order_by('pontuacao DESC');

    // GROUP BY
    $this->db->group_by('vendas_usuarios.usuario');

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

//      $return = $this->obter_vendas_usuarios($return_ids, $return);

      return $return;
    }else{
      return false;
    }
  }
}
