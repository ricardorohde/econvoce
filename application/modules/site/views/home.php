<div class="container">
  <div class="row">
    <div class="col-xs-12 text-center">
      <?php
      $nome = explode(' ', $this->site->userinfo('nome'));
      ?>
      <h1 class="page-home__title">Bem Vindo <span class="<?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->userinfo('perfil_nome'); ?></span> <?php echo $nome[0] . ' ' . $nome[(count($nome) - 1)]; ?></h1>

      <div class="page-home__description">Pronto para mergulhar? Selecione um produto para começar.</div>

      <ul class="home__icones">
        <li>
          <a href="<?php echo base_url('produtos'); ?>" class="home__icones--item produtos">
            <h2 class="title">Produtos</h2>
            <div class="description">Confira a categoria dos produtos.</div>
          </a>
        </li>

        <li>
          <a href="<?php echo base_url('ranking'); ?>" class="home__icones--item ranking">
            <h2 class="title">Ranking</h2>
            <div class="description">Confira a pontuação deste Mês.</div>
          </a>
        </li>

        <li>
          <a href="<?php echo base_url('vendas'); ?>" class="home__icones--item vendas">
            <h2 class="title">Vendas</h2>
            <div class="description">Ferramenta que ajuda você a vender.</div>
          </a>
        </li>
      </ul>

    </div>
  </div>
</div>