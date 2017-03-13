<div class="sidebar">
  <div class="areautil">
    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/img/logo-econvoce--acesso.png'); ?>" alt="Econ Você"></a>

    <h1 class="form__title">Esqueci minha senha</h1>

    <div class="form__description">Preencha seu e-mail para alterar sua senha.</div>


    <form action="<?php echo base_url('esqueci-minha-senha'); ?>" method="post" class="text-left">

      <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

      <div class="form-group">
        <label for="email" class="sr-only">E-mail</label>
        <input type="email" required class="input-text input-12" id="email" name="email" placeholder="Seu e-mail" value="<?php echo $this->input->post('email'); ?>">
      </div>

      <button type="submit" class="btn btn-block btn-blue-dark btn-entrar">
        Clique para entrar
      </button>

      <hr>

      <a href="<?php echo base_url('login'); ?>" class="btn btn-block btn-green">Faça seu Login</a>

    </form>
  </div>
</div>