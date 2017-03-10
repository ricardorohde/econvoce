<div class="sidebar">
  <div class="areautil">
    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/img/logo-econvoce--acesso.png'); ?>" alt="Econ Você"></a>

    <h1 class="form__title">Conectar ao painel do usuário</h1>

    <div class="form__description">Todos os campos são obrigatórios.</div>


    <form action="<?php echo base_url('login'); ?>" method="post" id="login-form" class="text-left">

      <input type="hidden" name="redirect" value="<?php echo ($this->session->tempdata('redirect') ? $this->session->tempdata('redirect') : ($this->input->post('redirect') ? $this->input->post('redirect') : base_url())); ?>" />

      <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

      <div class="form-group">
        <label for="email" class="sr-only">E-mail</label>
        <input type="email" required class="input-text input-12" id="email" name="email" placeholder="Seu E-mail" value="<?php echo $this->input->post('email'); ?>">
      </div>

      <div class="form-group">
        <label for="senha" class="sr-only">Senha</label>
        <input type="password" class="input-text input-12" id="senha" name="senha" placeholder="Senha">
      </div>

      <button type="submit" class="btn btn-block btn-blue-dark btn-entrar">
        Clique para entrar
      </button>

      <div class="esqueci-senha">
        Esqueceu sua senha? <a href="<?php echo base_url('esqueci-minha-senha'); ?>" class="color-green">Clique aqui!</a>
      </div>


      <a href="<?php echo base_url('cadastro'); ?>" class="btn btn-block btn-green btn-cadastrar">Participe do programa</a>

    </form>
  </div>
</div>