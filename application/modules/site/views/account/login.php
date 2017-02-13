  <div class="page-header header-filter" style="background-image: url('<?php echo base_url('assets/site/img/bg7.jpg'); ?>'); background-size: cover; background-position: top center;">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
          <div class="card card-signup">
            <form class="form" method="" action="">
              <div class="header header-primary text-center">
                <h4 class="card-title">Use suas redes sociais</h4>
                <div class="social-line">
                  <a href="#pablo" class="btn btn-just-icon btn-simple">
                    <i class="fa fa-facebook-square"></i>
                  </a>
                  <a href="#pablo" class="btn btn-just-icon btn-simple">
                    <i class="fa fa-twitter"></i>
                  </a>
                  <a href="#pablo" class="btn btn-just-icon btn-simple">
                    <i class="fa fa-google-plus"></i>
                  </a>
                </div>
              </div>
              <p class="description text-center">ou seu e-mail e sua senha:</p>
              <div class="content">

                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="material-icons">email</i>
                  </span>
                  <input type="text" class="form-control" placeholder="E-mail">
                </div>

                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="material-icons">lock_outline</i>
                  </span>
                  <input type="password" placeholder="Senha" class="form-control" />
                </div>

                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="optionsCheckboxes">
                    Lembrar senha
                  </label>
                </div>
              </div>
              <div class="footer text-center">
                <a href="#pablo" class="btn btn-primary btn-simple btn-wd btn-lg">Entrar</a>
              </div>
            </form>
          </div>
          <div class="text-center">Ainda não criou sua conta? <a href="">Clique aqui</a></div>
        </div>
      </div>
    </div>

    <?php $this->load->view('site/includes/common/footer.php', $this->_ci_cached_vars); ?>
  </div>


