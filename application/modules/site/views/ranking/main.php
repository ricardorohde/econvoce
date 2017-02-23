<div class="container">
  <h1>Ranking</h1>

  <form action="<?php echo base_url('ranking'); ?>" method="get">
    <input type="text" name="q" placeholder="Buscar por ranking" size="30" value="<?php echo $this->input->get('q'); ?>" />
    <?php
    if(isset($search) && $search){
      ?>
      <a href="<?php echo base_url('ranking'); ?>">Limpar</a>
      <?php
    }
    ?>
  </form>


  <?php
  if(isset($ranking['results']) && !empty($ranking['results'])){
    ?>
    <div class="row">
      <?php
      $position_count = 1 + ($page > 1 ? ($per_page * ($page-1)) : 0);
      foreach ($ranking['results'] as $rank_position) {
        ?>
        <div class="col-sm-12">
          <?php echo $position_count; ?>
          <?php print_l($rank_position); ?>
        </div>
        <?php
        $position_count++;
      }
      ?>
    </div>
    <?php

    if(isset($ranking['pagination']) && !empty($ranking['pagination'])){
      echo $ranking['pagination'];
    }
  }
  ?>

</div>
