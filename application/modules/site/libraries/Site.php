<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site {
  function __construct(){
    $this->ci =& get_instance();
  }

  public function user_logged($condition = false, $redirect = true, $section = 'site_logado'){
    $login_check = $this->ci->session->userdata($section) || $this->ci->session->tempdata($section);
    $is_logged = $login_check ? true : false;

    if($is_logged == $condition){
      if($redirect){
        if($redirect === true){
          $redirect = 'login';
        }
        $this->ci->session->set_tempdata('redirect', base_url($this->ci->uri->uri_string()), 180);
        redirect(base_url($redirect), 'location');
      }
      return true;
    }
    return false;
  }

  public function userinfo($slug, $section = 'site_logado'){
    if($this->user_logged(TRUE, NULL, $section)){
      $usuario = $this->ci->session->userdata($section);
      if(isset($usuario[$slug])){
        return $usuario[$slug];
      }
    }
    return false;
  }

  public function letras_iniciais($nome = null) {
    $iniciais = '';

    if($nome) {
      $explode = explode(' ', trim($nome));
      if(count($explode) == 0){
        $iniciais = '-';
      }else if(count($explode) == 1){
        $iniciais = substr($explode[0], 0, 1);
      }else{
        $iniciais = substr($explode[0], 0, 1) . substr($explode[count($explode)-1], 0, 1);
      }
    }

    return $iniciais;
  }

  public function mes($mes = null){
    if($mes){
      $meses = $this->ci->config->item('meses');
      return $meses[$mes-1];
    }

    return false;
  }

  public function create_pagination($page = 1, $limit, $total_rows, $base_url, $url_suffix = null){
    $this->ci->load->library('pagination');

    $config = array();
    $config["base_url"] = $base_url; // Set base_url for every links
    if($url_suffix){
      $config['suffix'] = '#filter' . base64_encode($url_suffix);//$url_suffix;
      $config['first_url'] = $config['base_url'] . $config['suffix'];
    }
    $config["cur_page"] = $page;
    $config["total_rows"] = $total_rows; // Set total rows in the result set you are creating pagination for.
    $config["per_page"] = $limit; // Number of items you intend to show per page.
    $config['reuse_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE; // Use pagination number for anchor URL.
    $config['num_links'] = 3; //Set that how many number of pages you want to view.
    $config['full_tag_open'] = '<div class="text-center"><ul class="pagination">';
    $config['full_tag_close'] = '</ul></div><!--pagination-->';
    $config['first_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
    $config['first_tag_open'] = '<li class="first page">';
    $config['first_tag_close'] = '</li>';
    $config['last_link'] = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
    $config['last_tag_open'] = '<li class="last page">';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = '<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>';
    $config['next_tag_open'] = '<li class="next page">';
    $config['next_tag_close'] = '</li>';
    $config['prev_link'] = '<span aria-hidden="true"><i class="fa fa-angle-left"></i></span>';
    $config['prev_tag_open'] = '<li class="prev page">';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a>';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page">';
    $config['num_tag_close'] = '</li>';
    $config['anchor_class'] = 'follow_link';
    $config['attributes'] = array('class' => 'pagination-item');

    // To initialize "$config" array and set to pagination library.
    $this->ci->pagination->initialize($config);

    return $this->ci->pagination->create_links();
  }

  public function get_redirect($local = null) {
    $redirect = base_url($local ? $local : '');
    if($this->ci->input->post("redirect")) {
      $redirect = $this->ci->input->post("redirect");
    }else{
      if($this->ci->session->tempdata('redirect')) {
        $redirect = $this->ci->session->tempdata('redirect');
        $this->ci->session->unset_tempdata('redirect');
      }
    }
    return $redirect;
  }

  public function alerta_redirect($alertaClass = 'danger', $mensagem = 'Ocorreu um erro inesperado.', $redirect = null, $status = 'visible', $extraClass = array()){
    $alerta = array(
      'alerta' => array(
        'type' => $alertaClass,
        'message' => $mensagem,
        'status' => $status,
        'class' => $extraClass
      )
    );

    if($redirect){
      $this->ci->session->set_flashdata('alerta', $alerta);
      $redirect = (strpos($redirect, '//') === false) ? base_url($redirect) : $redirect;
      redirect($redirect, 'location');
      exit;
    }

    return $alerta;
  }

  public function form_error($erros){
    $return = array();
    $erro_item = '';
    if($erros){
      foreach($erros as $key => $value){
        $erro_item .= '&bull; ' . $value . '<br>';
        $return['erros'][$key] = $value;
      }
    }

    $return['alerta'] = array(
      'type' => 'danger',
      'message' => $erro_item,
      'status' => 'visible'
    );

    return $return;
  }

  public function obter_email_template($template, $params = array()) {
    $this->ci->load->helper('file');

    $template_path = "assets/site/emails/" . $template;
    $template = read_file($template_path . '/template.html');

    if($params){
      foreach($params as $key => $value){
        $template = str_replace('{{'. $key .'}}', $value, $template);
      }
    }

    $template = str_replace("{{site_nome}}", $this->ci->config->item('site_nome'), $template);
    $template = str_replace("{{site_email}}", $this->ci->config->item('site_email'), $template);
    $template = str_replace("{{site_sigla}}", $this->ci->config->item('site_sigla'), $template);
    $template = str_replace("{{data_hora}}", date("d/m/Y H:i", time()), $template);
    $template = str_replace("{{template_url}}", base_url($template_path), $template);
    $template = str_replace("{{base_url}}", base_url(), $template);

    return $template;
  }

  public function send_mail($to, $subject, $template, $params = array(), $header = false){
    $template = $this->obter_email_template($template, $params);

    $this->ci->load->library('email');

    $this->ci->email->initialize(array(
      'mailtype' => 'html',
      'smtp_host' => 'smtplw.com.br',
      'smtp_port' => '587',
      'smtp_user' => $this->ci->config->item('site_email_envio'),
      'smtp_pass' => 'Rs2@34ft',
      'protocol' => 'smtp'
    ));

    $this->ci->email->from($this->ci->config->item('site_email_envio'), $this->ci->config->item('site_nome'), $this->ci->config->item('site_email_envio'));
    $this->ci->email->to($to);

    $this->ci->email->subject($subject);
    $this->ci->email->message($template);

    $send = $this->ci->email->send();

    echo $this->ci->email->print_debugger();

    return $send;
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
