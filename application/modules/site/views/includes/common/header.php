    <!-- Static navbar -->
    <?php
    $header_nome_explode = explode(' ', $this->site->userinfo('nome'));
    $header_nome_final = count($header_nome_explode) == 1 ? $header_nome_explode : $header_nome_explode[0] . ' ' . $header_nome_explode[(count($header_nome_explode) - 1)];
    $header_nome = $this->site->userinfo('nome') === $this->site->userinfo('apelido') ? $this->site->userinfo('apelido') : $header_nome_final;
    ?>
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
                <div class="notification__icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                <div class="notification__content">Confira aqui e veja como será a <a href="#" class="link">pontuação no programa</a>.</div>
              </div>
            </li>

            <li>
              <a href="javascript: void(0);" class="navbar-initial-letters dropdown <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->letras_iniciais($header_nome); ?></a>

              <div class="navbar-hover-box">
                
                <div class="navbar-hover__header">
                  <div class="navbar-hover__header--row">
                    <div class="navbar-hover__header--initial-letters">
                      <span class="navbar-initial-letters small <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->letras_iniciais($header_nome); ?></small>
                    </div>

                    <div class="navbar-hover__header--name">
                      <strong class="name"><?php echo $header_nome; ?></strong>
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


<!-- 

        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-menu">

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="iniciais <?php echo $this->site->userinfo('perfil_slug'); ?>"><?php echo $this->site->letras_iniciais($header_nome); ?></span>
                <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li>


                  
                  <?php echo $header_nome; ?><br>
                  <?php echo $this->site->userinfo('perfil_nome'); ?>
                  
                  <hr>
                  
                  <?php echo $this->site->userinfo('email'); ?><br>
                  <?php echo $this->site->userinfo('telefone'); ?><br>
                  <?php echo !empty($this->site->userinfo('creci')) ? 'Creci: ' . $this->site->userinfo('creci') : ''; ?>

                  <hr>

                  <div class="text-right">
                    <a href="<?php echo base_url('logout'); ?>" class="btn btn-danger btn-xs">Sair</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse 

 -->