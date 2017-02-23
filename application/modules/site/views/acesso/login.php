
<form action="<?php echo base_url('site/login'); ?>" method="post" id="login-form" class="text-left">

  <input type="text" name="redirect" value="<?php echo ($this->session->tempdata('redirect') ? $this->session->tempdata('redirect') : ($this->input->post('redirect') ? $this->input->post('redirect') : base_url('login'))); ?>" />

  <?php $this->load->view('site/includes/alerts', $this->_ci_cached_vars); ?>

  <div class="form-group">
    <label for="login" class="sr-only">Login</label>
    <input type="text" class="form-control" id="login" name="login" placeholder="Login" value="<?php echo $this->input->post('login'); ?>">
  </div>

  <div class="form-group">
    <label for="senha" class="sr-only">Senha</label>
    <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
  </div>

  <button type="submit" class="btn btn-block btn-primary">
    Enviar
  </button>

</form>