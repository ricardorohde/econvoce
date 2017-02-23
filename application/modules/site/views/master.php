<?php defined('BASEPATH') OR exit('No direct script access allowed');
$data = $this->_ci_cached_vars;
$show_sidebar = isset($section['hide_sidebar']) && $section['hide_sidebar'] ? false : true;
$show_header = isset($section['hide_header']) && $section['hide_header'] ? false : true;
$show_footer = isset($section['hide_footer']) && $section['hide_footer'] ? false : true;
$hide_all = isset($section['hide_all']) && $section['hide_all'] ? true : false;
?><!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Econ Você</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />

  <!--  CSS for Demo Purpose, don't include it in your project     -->
  <link href="<?php echo base_url('assets/site/css/styles.css?v=' . time()); ?>" rel="stylesheet" />

  <!--     Fonts and icons     -->
  <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>
</head>

<body class="<?php echo isset($section["body_class"]) ? (is_array($section["body_class"]) ? implode(" ", $section["body_class"]) : $section["body_class"]) : ''; ?>">

  <?php
  if($show_header && $hide_all == false){
    $this->load->view('site/includes/common/header.php', $data);
  }

  echo $content;

  if($show_footer && $hide_all == false){
    $this->load->view('site/includes/common/footer.php', $data);
  }
  ?>

  <script src="<?php echo base_url('assets/site/js/LAB.min.js'); ?>"></script>
  <script>
    $LAB
      .script("<?php echo base_url('assets/site/js/jquery-3.1.0.min.js'); ?>").wait()
      .script("<?php echo base_url('assets/site/js/bootstrap.min.js'); ?>").wait()
      .script("<?php echo base_url('site/configjs?v=' . $this->config->item('site_versao')); ?>").wait()
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
