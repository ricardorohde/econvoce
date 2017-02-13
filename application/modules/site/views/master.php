<?php defined('BASEPATH') OR exit('No direct script access allowed');
$data = $this->_ci_cached_vars;
$show_header = isset($section['hide_header']) && $section['hide_header'] ? false : true;
$show_footer = isset($section['hide_footer']) && $section['hide_footer'] ? false : true;
?><!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/site/img/favicon-196x196.png'); ?>" sizes="196x196" />
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/site/img/favicon-96x96.png'); ?>" sizes="96x96" />
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/site/img/favicon-32x32.png'); ?>" sizes="32x32" />
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/site/img/favicon-16x16.png'); ?>" sizes="16x16" />
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/site/img/favicon-128.png'); ?>" sizes="128x128" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Prefirograna</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Open+Sans:700i|Material+Icons" />

  <link href="<?php echo base_url('assets/site/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  <link href="<?php echo base_url('assets/site/css/material-kit.css'); ?>" rel="stylesheet"/>
</head>

<body class="<?php echo isset($section["body_class"]) ? (is_array($section["body_class"]) ? implode(" ", $section["body_class"]) : $section["body_class"]) : ''; ?>">

  <?php
  if($show_header){
    $this->load->view('site/includes/common/header.php', $data);
  }

  echo $content;

  if($show_footer){
    $this->load->view('site/includes/common/footer.php', $data);
  }
  ?>

  <script src="<?php echo base_url('assets/site/js/LAB.min.js'); ?>"></script>
  <script>
    $LAB
      .script("<?php echo base_url('assets/site/js/jquery.min.js'); ?>").wait()
      .script("<?php echo base_url('configjs?v=' . $this->config->item('site_versao')); ?>").wait()
      .script("<?php echo base_url('assets/site/js/pace.min.js'); ?>").wait()
      .script("<?php echo base_url('assets/site/js/bootstrap.min.js'); ?>").wait()
      .script("<?php echo base_url('assets/site/js/material.min.js'); ?>").wait()
      .script("<?php echo base_url('assets/site/js/material-kit.js?v=' . $this->config->item('site_versao')); ?>").wait()
      <?php
      if(isset($assets["scripts"]) && !empty($assets["scripts"])){
        foreach($assets["scripts"] as $index => $script){
          $src = strpos($script[0], '//') === false ? base_url($script[0]) . '?v=' . $this->config->item('site_versao') : $script[0];
          ?>.script("<?php echo $src; ?>")<?php if(isset($script[1]) && $script[1] == true){ ?>.wait(function(){<?php if(isset($script[2])){ ?><?php echo $script[2]; ?><?php } ?>})<?php } ?><?php
        }
      }
      ?>
  </script>

</body>
</html>
