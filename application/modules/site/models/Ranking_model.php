<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_ranking($request = array(), $row = false){
    $return = array();

    $this->load->library('subquery');

    // SELECT
    $this->db->select('*');

    // WHERE
    $where = array();
    if(isset($request['params']['where']) && !empty($request['params']['where'])){
      $where = $request['params']['where'];
    }

    // FROM
    $sub = $this->subquery->start_subquery('from');
    $sub->select('s.*, @rank:=@rank + 1 as rank', FALSE)->order_by('pontuacao_total DESC');

    $sub2 = $this->subquery->start_subquery('from')
      ->select('usuarios.nome, usuarios.apelido, perfis.slug as perfil_slug, usuario, sum(pontuacao) as pontuacao_total')
      ->join("vendas", "t.venda = vendas.id", "inner")
      ->join("usuarios", "t.usuario = usuarios.id", "inner")
      ->join("perfis", "usuarios.perfil = perfis.id", "inner")
      ->where($where)
      ->from('vendas_usuarios t')->group_by('usuario');

    $this->subquery->end_subquery('s');

    $sub3 = $this->subquery->start_subquery('from');
    $sub3->select('@rank := 0', FALSE);
    $this->subquery->end_subquery('init');

    $this->subquery->end_subquery('r');

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

    // GET ROWS COUNT
    $return['total_rows'] = $this->registros_model->obter_registros_count($this->db->_compile_select());
    $return['current_page'] = (isset($request['params']['pagination']['page']) ? $request['params']['pagination']['page'] : 1);

    // PAGINATION
    if(isset($request['params']['pagination']) && !empty($request['params']['pagination'])){
      if(isset($request['params']['pagination']['limit']) && !empty($request['params']['pagination']['limit'])){
        $limit = (isset($request['params']['pagination']['limit']) && !empty($request['params']['pagination']['limit']) ? $request['params']['pagination']['limit'] : 12);

        if(isset($request['params']['pagination']['page']) && !empty($request['params']['pagination']['page'])){
          $page = max(0, ($request['params']['pagination']['page'] - 1) * $limit);

          $return['pagination'] = $this->site->create_pagination($return['current_page'], $limit, $return['total_rows'], rtrim((isset($request['params']['base_url']) ? base_url($request['params']['base_url']) : current_url()), "/" . $request['params']['pagination']['page']), (isset($request['params']['url_suffix']) ? $request['params']['url_suffix'] : null));
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

    $query = $this->db->get();

    if($query->num_rows()){
      if($row){
        $return = $query->row_array();
        $return_ids = array($return['usuario']);
      }else{
        $return['results'] = array();

        $return_ids = array();
        $return_count = 0;
        foreach($query->result_array() as $result){
          $return['results'][$return_count] = $result;
          $return_ids[$return_count] = $result['usuario'];
          $return_count++;
        }
      }

      $return = $this->obter_ranking_vendas($request, $return_ids, $return);

      return $return;
    }else{
      return false;
    }
  }

  public function obter_ranking_vendas($request = array(), $return_ids, $return) {
    $params = array('where' => $request['params']['where']);
    $params['where_in'] = array(
      'vendas_usuarios.usuario' => $return_ids
    );

    $vendas = $this->registros_model->obter_registros(
      'vendas_usuarios',
      $params,
      false,
      'vendas_usuarios.usuario as usuario_id, vendas_usuarios.pontuacao, estagios.slug as estagio_slug',
      array(
        array('vendas', 'vendas_usuarios.venda = vendas.id', 'inner'),
        array('estagios', 'vendas.estagio = estagios.id', 'inner')
      ),
      array(
        'vendas_usuarios.usuario' => 'ASC'
      )
    );

    if(!empty($vendas)){
      if($return){
        foreach ($vendas as $venda) {
          if(isset($return['results'])){
            $venda_key = array_search ($venda['usuario_id'], $return_ids);
            
            if(!isset($return['results'][$venda_key]['vendas'][$venda['estagio_slug']])){
              $return['results'][$venda_key]['vendas'][$venda['estagio_slug']] = 0;
            }
            $return['results'][$venda_key]['vendas'][$venda['estagio_slug']] = ($return['results'][$venda_key]['vendas'][$venda['estagio_slug']] + $venda['pontuacao']);
          }else{
            if(!isset($return['vendas'][$venda['estagio_slug']])){
              $return['vendas'][$venda['estagio_slug']] = 0;
            }
            $return['vendas'][$venda['estagio_slug']] = ($return['vendas'][$venda['estagio_slug']] + $venda['pontuacao']);
          }
        }
        return $return;
      }

      return $vendas;
    }else{
      if($return) return $return;
    }

    return false;
  }

  public function obter_vendas_periodos($request = array()) {
    // SELECT
    $this->db->select('MONTH(vendas.data_contrato) AS mes, YEAR(vendas.data_contrato) AS ano');

    // FROM
    $this->db->from('vendas');

    // ORDER
    $this->db->order_by('YEAR(vendas.data_contrato) DESC, MONTH(vendas.data_contrato) DESC');

    // WHERE
    if(isset($request['where']) && !empty($request['where'])){
      $this->db->where($request['where']);
    }

    //GROUPBY
    $this->db->group_by(array('MONTH(vendas.data_contrato)','YEAR(vendas.data_contrato)'));

    // LIMIT
    $this->db->limit(3);

    // Order
    //$this->db->order_by('YEAR(vendas.data_contrato) ASC');

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return array_reverse($query->result_array());
    }

    return false;
  }
}
