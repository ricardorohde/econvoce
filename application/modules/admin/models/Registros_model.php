<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Registros_model extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function obter_registros_count($sql){
    $query = $this->db->query($sql);
    return $query->num_rows();
  }

  public function obter_registros($table, $params = array(), $row = false, $select = '*', $join = false, $order = false) {
    // SELECT
    $this->db->select($select);

    // FROM
    $this->db->from($table);

    // JOIN
    if($join && is_array($join)){
      foreach($join as $join_item){
        $this->db->join($join_item[0], $join_item[1], $join_item[2]);
      }
    }

    // WHERE
    if(isset($params['where']) && !empty($params['where'])){
      foreach($params['where'] as $key => $value){
        $this->db->where($key, $value);
      }
    }

    // WHERE IN
    if(isset($params['where_in']) && !empty($params['where_in'])){
      foreach ($params['where_in'] as $key => $value) {
        $this->db->where_in($key, $value);
      }
    }

    //ORDER
    if($order){
      $orderby = array();
      foreach($order as $key => $value){
        $this->db->order_by($key, $value);
      }
    }

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      if($row){
        if($row === TRUE){
          return $query->row_array();
        }else{
          $row_field = $query->row_array();
          if(isset($row_field[$row])){
            return $row_field[$row];
          }
          return false;
        }
      }
      return $query->result_array();
    }
    return false;
  }
}
