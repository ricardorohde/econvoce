<?php defined('BASEPATH') OR exit('No direct script access allowed');
$data = $this->_ci_cached_vars;
$show_header = isset($section['hide_header']) && $section['hide_header'] ? false : true;
$show_footer = isset($section['hide_footer']) && $section['hide_footer'] ? false : true;
$hide_all = isset($section['hide_all']) && $section['hide_all'] ? true : false;
?><!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Econ Você</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />

  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600,700,800" rel="stylesheet">
  <link href="<?php echo base_url('assets/site/css/main.css?v=' . time()); ?>" rel="stylesheet" />
</head>

<body class="<?php echo isset($section["body_class"]) ? (is_array($section["body_class"]) ? implode(" ", $section["body_class"]) : $section["body_class"]) : ''; ?>">

  <?php
  if($hide_all == false){
    ?>
    <div class="website">
      <div class="page-wrap">
    <?php
    if($show_header){
      $this->load->view('site/includes/common/header.php', $data);
    }
  }

      echo $content;

  if($hide_all == false){
    ?> 
      </div>

      <?php
      if($show_footer){
        $this->load->view('site/includes/common/footer.php', $data);
      }
      ?>
    </div>
    <?php
  }
  ?>

  <?php
  $this->load->view('site/regulamento.php', $data);
  ?>

  <script src="<?php echo base_url('assets/site/js/LAB.min.js'); ?>"></script>
  <script>
    $LAB
      .script("<?php echo base_url('assets/site/js/jquery-3.1.0.min.js'); ?>").wait()
      .script("<?php echo base_url('assets/site/js/pace.min.js'); ?>").wait()
      .script("<?php echo base_url('configjs?v=' . $this->config->item('site_versao')); ?>").wait()
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
