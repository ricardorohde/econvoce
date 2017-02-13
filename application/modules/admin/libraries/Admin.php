<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin {
  function __construct(){
    $this->ci =& get_instance();
  }

  public function user_logged($condition = TRUE, $redirect = NULL, $section = 'usuario_logado'){
    $login_check = $this->ci->session->userdata($section);
    $is_logged = $login_check ? TRUE : FALSE;

    if($is_logged == $condition){
      if($redirect){
        if($redirect === TRUE){
          $redirect = 'minha-conta/login';
        }
        $this->ci->session->set_flashdata('redirect', base_url($this->ci->uri->uri_string()));
        redirect(base_url($redirect), 'location');
      }
      return TRUE;
    }
    return FALSE;
  }

  public function userinfo($slug, $section = 'usuario_logado'){
    if($this->user_logged(TRUE, NULL, $section)){
      $usuario = $this->ci->session->userdata($section);
      if(isset($usuario[$slug])){
        return $usuario[$slug];
      }
    }
    return false;
  }

  public function round_points($value, $nearest) {
    $log = false;
    
    $value_round = round($value);
    if($log) echo 'De ' . $value . ' para ' . round($value) . '<br>';

    $value_divide = $value_round / $nearest;
    if($log) echo 'Divide: ' . $value_round . ' / ' . $nearest . ' = ' . $value_divide . '<br>';

    $value_divide_round = round($value_divide);
    if($log) echo 'Divide round: round(' . $value_divide . ') = ' . $value_divide_round . '<br>';

    $value_multiplica = $value_divide_round * $nearest;
    if($log) echo 'Multiplica: ' . $value_divide_round . ' * ' . $nearest . ' = ' . $value_multiplica . '<br>';

    return $value_multiplica;
  }

}
