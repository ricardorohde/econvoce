<div class="container">
  <h1>Empreendimentos</h1>

  <form action="<?php echo base_url($search_action); ?>" method="get">
    <input type="text" name="q" placeholder="Buscar por empreendimento" size="30" value="<?php echo $this->input->get('q'); ?>" />
    <?php
    if(isset($search) && $search){
      ?>
      <a href="<?php echo base_url($search_action); ?>">Limpar</a>
      <?php
    }
    ?>
  </form>

  <?php
  if(isset($estagios) && !empty($estagios)){
    foreach ($estagios as $key => $estagio) {
      ?>
      <a href="<?php echo base_url('empreendimentos/' . $estagio['slug']); ?>" class="btn btn-primary btn-xs"><?php echo $estagio['nome']; ?></a>
      <?php
    }
  }
  ?>

  <hr>

  <?php
  if(isset($empreendimentos['results']) && !empty($empreendimentos['results'])){
    ?>
    <div class="row">
      <?php
      foreach ($empreendimentos['results'] as $empreendimento) {
        ?>
        <div class="col-sm-6">
          <?php print_l($empreendimento['apelido']); ?>
        </div>
        <?php
      }
      ?>
    </div>
    <?php

    if(isset($empreendimentos['pagination']) && !empty($empreendimentos['pagination'])){
      echo $empreendimentos['pagination'];
    }
  }
  ?>

</div>
