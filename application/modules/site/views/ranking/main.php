<div class="container">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <form action="<?php echo base_url($search_action); ?>" method="get">
        <div class="search-box">
          <div class="icon left"><i class="fa fa-search" aria-hidden="true"></i></div>
          <input type="text" class="search__input input-12" name="q" placeholder="Buscar por vendedores" value="<?php echo $this->input->get('q'); ?>" />
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
  <img src="<?php echo base_url('assets/site/img/home__icone--ranking.png'); ?>" alt="">
</div>


<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="text-center">
        <h1 class="page__title">Ranking Vendedores</h1>


        <div class="meses__table">
          <div class="meses__row">
            <?php
            if($periodos){
              foreach ($periodos as $key => $periodo) {
                if($key == 0 && isset($mes) && $mes == $periodo['mes'] && isset($ano) && $ano == $periodo['ano']){
                  ?>
                  <div class="meses__left disabled">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                  </div>
                  
                  <div class="meses__middle"><?php echo $this->site->mes($periodo['mes']); ?> de <?php echo $periodo['ano']; ?></div>

                  <?php
                  if(isset($periodos[1])){
                    $proximo = $periodos[1];
                    ?>
                    <a href="<?php echo base_url('ranking/' . $proximo['mes'] . '/' . $proximo['ano'] . ($this->input->get('q') ? '?q=' . $this->input->get('q') : '')); ?>" class="meses__right" title="<?php echo $this->site->mes($proximo['mes']); ?>/<?php echo $proximo['ano']; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <?php
                  }
                  ?>

                  <?php
                }else if($key == 1 && isset($mes) && $mes == $periodo['mes'] && isset($ano) && $ano == $periodo['ano']){
                  ?>
                  <?php
                  if(isset($periodos[0])){
                    $anterior = $periodos[0];
                    ?>
                    <a href="<?php echo base_url('ranking/' . $anterior['mes'] . '/' . $anterior['ano'] . ($this->input->get('q') ? '?q=' . $this->input->get('q') : '')); ?>" class="meses__left" title="<?php echo $this->site->mes($anterior['mes']); ?>/<?php echo $anterior['ano']; ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <?php
                  }
                  ?>

                  <div class="meses__middle"><?php echo $this->site->mes($periodo['mes']); ?> de <?php echo $periodo['ano']; ?></div>

                  <?php
                  if(isset($periodos[2])){
                    $proximo = $periodos[2];
                    ?>
                    <a href="<?php echo base_url('ranking/' . $proximo['mes'] . '/' . $proximo['ano'] . ($this->input->get('q') ? '?q=' . $this->input->get('q') : '')); ?>" class="meses__right" title="<?php echo $this->site->mes($proximo['mes']); ?>/<?php echo $proximo['ano']; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <?php
                  }
                  ?>

                  <?php
                }else if($key == 2 && isset($mes) && $mes == $periodo['mes'] && isset($ano) && $ano == $periodo['ano']){
                  ?>

                  <?php
                  if(isset($periodos[1])){
                    $anterior = $periodos[1];
                    ?>
                    <a href="<?php echo base_url('ranking/' . $anterior['mes'] . '/' . $anterior['ano'] . ($this->input->get('q') ? '?q=' . $this->input->get('q') : '')); ?>" class="meses__left" title="<?php echo $this->site->mes($anterior['mes']); ?>/<?php echo $anterior['ano']; ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <?php
                  }
                  ?>

                  <div class="meses__middle"><?php echo $this->site->mes($periodo['mes']); ?> de <?php echo $periodo['ano']; ?></div>

                  <div class="meses__right disabled">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                  </div>
                  <?php
                }
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php
  if(isset($ranking['results']) && !empty($ranking['results'])){
    ?>
    <div class="table">
      
      <div class="thead">
        <div class="tr">
          <div class="th"></div>
          <div class="th hidden-sm hidden-xs"></div>
          <div class="th"></div>

          <div class="th points text-center"><span class="hidden-xs">Pronto para morar</span><span class="hidden-sm hidden-md hidden-lg">P</span></div>
          <div class="th points text-center"><span class="hidden-xs">Remanescente</span><span class="hidden-sm hidden-md hidden-lg">R</span></div>
          <div class="th points text-center"><span class="hidden-xs">Lançamento</span><span class="hidden-sm hidden-md hidden-lg">L</span></div>

          <div class="th total text-center"><span class="hidden-xs">Total</span><span class="hidden-sm hidden-md hidden-lg">T</span></div>
        </div>
      </div>

      <div class="tbody">
        <?php
        foreach ($ranking['results'] as $position) {
          $nome = $position['nome'] === $position['apelido'] ? $position['apelido'] : $position['nome'];
          ?>
          <div class="tr">
            <div class="td rank"><?php echo $position['rank']; ?>º</div>
            
            <div class="td hidden-sm hidden-xs letters">
              <div class="table-initial-letters small <?php echo $position['perfil_slug']; ?>"><?php echo $this->site->letras_iniciais($nome); ?></div>
            </div>
            
            <div class="td name semi-bold"><?php echo $nome; ?></div>
            
            <div class="td points semi-bold text-center"><?php echo isset($position['vendas']['lancamento']) ? number_format($position['vendas']['lancamento'], 0, '.', '.') : '-'; ?></div>
            <div class="td points semi-bold text-center"><?php echo isset($position['vendas']['remanescente']) ? number_format($position['vendas']['remanescente'], 0, '.', '.') : '-'; ?></div>
            <div class="td points semi-bold text-center"><?php echo isset($position['vendas']['pronto']) ? number_format($position['vendas']['pronto'], 0, '.', '.') : '-'; ?></div>
            
            <div class="td total text-center color-green semi-bold"><?php echo number_format($position['pontuacao_total'], 0, '.', '.'); ?></div>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    
    <?php
    if(isset($ranking['pagination']) && !empty($ranking['pagination'])){
      echo $ranking['pagination'];
    }
    ?>
    <?php
  }else{
    ?>
    <div class="alert alert-danger">Não existem registros neste período.</div>
    <?php
  }
  ?>

</div>
