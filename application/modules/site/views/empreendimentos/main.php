<div class="container">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <form action="<?php echo base_url($search_action); ?>" method="get">
        <div class="search-box">
          <div class="icon left"><i class="fa fa-search" aria-hidden="true"></i></div>
          <input type="text" class="search__input input-12" name="q" placeholder="Buscar por empreendimentos" value="<?php echo $this->input->get('q'); ?>" />
          <?php
          if(isset($search) && $search){
            ?>
            <a class="icon right" href="<?php echo base_url($search_action); ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            <?php
          }
          ?>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="page-icon">
  <img src="<?php echo base_url('assets/site/img/home__icone--produtos.png'); ?>" alt="">
</div>


<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="text-center">
        <h1 class="page__title">Empreendimentos</h1>
        
        <div class="table estagios-buttons">
          <div class="tr">
            <?php
            if(isset($estagios) && !empty($estagios)){
              foreach ($estagios as $key => $estagio) {
                ?>
                <div class="td btn-estagios <?php echo isset($estagio_slug) && $estagio_slug == $estagio['slug'] ? 'active' : ''; ?>">
                  <a href="<?php echo base_url('produtos/' . $estagio['slug']); ?>"><?php echo $estagio['nome']; ?></a>
                </div>
                <?php
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <?php
    if(isset($empreendimentos['results']) && !empty($empreendimentos['results'])){
      $reais_x_pontos = (int) $this->config->item('reais_x_pontos');
      ?>
      <?php
      foreach ($empreendimentos['results'] as $empreendimento) {
        $pontuacao_perfil = ($this->site->userinfo('perfil_percentual') / 100) * $empreendimento['vgv_liquido'];
        $pontuacao_prioridade = ($empreendimento['prioridade_percentual'] / 100) * $empreendimento['estagio_percentual'];
        $pontuacao_estagio = ($pontuacao_prioridade / 100) * $pontuacao_perfil;
        $pontuacao_reais_vs_pontos = $pontuacao_estagio * $reais_x_pontos;
        $pontuacao_final = $this->site->round_points($pontuacao_reais_vs_pontos, 50);
        ?>
        <div class="col-sm-6">
          <div class="table empreendimento__item">
            <div class="tr">
              <div class="td"><?php echo $empreendimento['apelido']; ?></div>
              <div class="td text-right">
                <?php echo $pontuacao_final; ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>

      <div class="clearfix"></div>

      <?php
      if(isset($empreendimentos['pagination']) && !empty($empreendimentos['pagination'])){
        echo $empreendimentos['pagination'];
      }
    }else{
      ?>
      <div class="col-xs-12">
        <div class="alert alert-danger text-center">
          Nenhum produto encontrado com essas características.
          <?php
          if(isset($search) && $search){
            ?>
            <a class="alert-link" href="<?php echo base_url($search_action); ?>">Limpar busca</a>.
            <?php
          }
          ?>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>