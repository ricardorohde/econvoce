<div class="sidebar">
  <div class="areautil">
    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/img/logo-econvoce--acesso.png'); ?>" alt="Econ Você"></a>

    <h1 class="form__title">Redefinir sua senha</h1>

    <div class="form__description">Preencha os campos abaixo para redefiinr sua senha.</div>


    <form action="<?php echo base_url($form_action); ?>" method="post" class="text-left" autocomplete="off">

      <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

      <div class="form-group">
        <label for="email" class="sr-only">Senha</label>
        <input type="password" required class="input-text input-12" id="senha" name="senha" placeholder="Senha" value="<?php echo $this->input->post('senha'); ?>">
      </div>

      <div class="form-group">
        <label for="repetir_senha" class="sr-only">Repetir senha</label>
        <input type="password" class="input-text input-12" id="repetir_senha" name="repetir_senha" placeholder="Repetir senha">
      </div>

      <button type="submit" class="btn btn-block btn-blue-dark btn-entrar">
        Redefinir senha
      </button>

      <hr>

      <a href="<?php echo base_url('cadastro'); ?>" class="btn btn-block btn-green btn-cadastrar">Participe do programa</a>

    </form>
  </div>
</div>