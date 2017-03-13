<!-- Static navbar -->
<header class="navbar">
  <div class="container">
    <a href="javascript: void(0);" class="navbar-toggle hidden-sm hidden-md hidden-lg">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </a>

    <a class="navbar-brand" href="<?php echo base_url(); ?>">EconVocê</a>

    <div class="navbar-collapse">
      <ul class="navbar-nav hidden-xs">
        <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'home') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>">Início</a></li>
        <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'produtos') ? 'active' : ''; ?>"><a href="<?php echo base_url('produtos'); ?>">Produtos</a></li>
        <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'ranking') ? 'active' : ''; ?>"><a href="<?php echo base_url('ranking'); ?>">Ranking</a></li>
        <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'vendas') ? 'active' : ''; ?>"><a href="<?php echo base_url('vendas'); ?>">Vendas</a></li>
      </ul>

      <ul class="navbar-right">
        <li>
          <a href="javascript: void(0);" class="active navbar-notification dropdown"><i class="fa fa-bell" aria-hidden="true"></i></a>
          <div class="navbar-hover-box notification">
            <?php
            if(isset($header['notificacoes']) && !empty($header['notificacoes'])){
              ?>
              <div class="notification__icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
              <div class="notification__content"><?php echo $header['notificacoes']['label']; ?></div>
              <?php
            }else{
              ?>
              <div class="notification__icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
              <div class="notification__content">Confira aqui e veja como será a <a href="javascript: void(0);" class="btn-abrir-regulamento link">pontuação no programa</a>.</div>
              <?php
            }
            ?>
          </div>
        </li>

        <li>
          <a href="javascript: void(0);" class="navbar-initial-letters dropdown <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->letras_iniciais($this->site->userinfo('nome_sobrenome')); ?></a>

          <div class="navbar-hover-box">
            
            <div class="navbar-hover__header">
              <div class="navbar-hover__header--row">
                <div class="navbar-hover__header--initial-letters">
                  <span class="navbar-initial-letters small <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->letras_iniciais($this->site->userinfo('nome_sobrenome')); ?></small>
                </div>

                <div class="navbar-hover__header--name">
                  <strong class="name"><?php echo $this->site->userinfo('nome_sobrenome'); ?></strong>
                  <div class="profile <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->userinfo('perfil_nome'); ?></div>
                </div>
              </div>
            </div>

            <div class="navbar-hover__content">
              <div class="navbar-hover__content--row">
                <div class="navbar-hover__content--details">
                  <div class="email"><?php echo $this->site->userinfo('email'); ?></div>
                  <div class="phone"><?php echo $this->site->userinfo('telefone'); ?></div>
                  <div class="creci">Creci: <strong><?php echo $this->site->userinfo('creci'); ?></strong></div>
                </div>
                <div class="navbar-hover__content--buttons">
                  <a href="<?php echo base_url('minha-conta'); ?>">Minha conta</a>
                  <a href="<?php echo base_url('logout'); ?>">Sair</a>
                </div>
              </div>
            </div>

          </div>


        </li>
      </ul>
    </div>
  </div>
</header> 