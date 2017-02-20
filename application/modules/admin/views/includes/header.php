<nav class="navbar navbar-transparent navbar-absolute">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse">
      <span class="sr-only">Menu</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"><?php echo isset($section['title']) ? $section['title'] : ''; ?></a>
    </div>

    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <?php
        if(isset($header['notificacoes']) && !empty($header['notificacoes'])){
          ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="material-icons">notifications</i>
              <span class="notification"><?php echo count($header['notificacoes']); ?></span>
              <p class="hidden-lg hidden-md">Notificações</p>
            </a>
            <ul class="dropdown-menu">
              <?php
              foreach ($header['notificacoes'] as $notificacao) {
                ?>
                <li><a href="<?php echo isset($notificacao['url']) ? strpos($notificacao['url'], '//') === false ? base_url($notificacao['url']) : $notificacao['url'] : '#'; ?>"><?php echo $notificacao['label']; ?></a></li>
                <?php
              }
              ?>
            </ul>
          </li>
          <?php
        }
        ?>

        <li>
          <a href="<?php echo base_url('admin/logout'); ?>" class="dropdown-toggle">
            <i class="material-icons">close</i>
            Sair
          </a>
        </li>
      </ul>

      <?php
      if(isset($section['search_form_action'])){
        ?>
        <form action="<?php echo isset($section['search_form_action']) ? base_url($section['search_form_action']) : '';?>" class="navbar-form navbar-right" role="search">
          <div class="form-group  is-empty">
            <input type="text" class="form-control" name="q" placeholder="Buscar">
            <span class="material-input"></span>
          </div>
          <button type="submit" class="btn btn-white btn-round btn-just-icon">
            <i class="material-icons">search</i><div class="ripple-container"></div>
          </button>
        </form>
        <?php
      }
      ?>

    </div>
  </div>
</nav>
