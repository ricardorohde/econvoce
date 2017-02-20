<div class="container" style="padding-top:100px;">
  <div class="col-sm-4 col-sm-offset-4">
    <form action="<?php echo base_url('admin/login'); ?>" method="post" id="login-form" class="text-left">
      <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>
      <div class="main-login-form">
        <h5>Acesso restrito</h5>
        <div class="login-group">

          <div class="form-group">
            <label for="login" class="sr-only">Login</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Login" value="<?php echo $this->input->post('login'); ?>">
          </div>

          <div class="form-group">
            <label for="senha" class="sr-only">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
          </div>

        </div><br />

        <button type="submit" class="btn btn-block btn-primary">
          Enviar
        </button>
      </div>
    </form>
  </div>
</div>
