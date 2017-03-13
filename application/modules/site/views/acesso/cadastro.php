<div class="sidebar">
  <div class="areautil">
    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/img/logo-econvoce--acesso.png'); ?>" alt="Econ Você"></a>

    <h1 class="form__title">Cadastre-se para participar do econvocê</h1>

    <div class="form__description">já tem uma conta? <a href="<?php echo base_url('login'); ?>" class="color-green">Faça login aqui</a>.</div>


    <form action="<?php echo base_url('cadastro'); ?>" method="post" id="login-form" class="text-left">

      <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

      <div class="form-group">
        <label for="perfil" class="sr-only">Cargo</label>

        <div class="select-box <?php echo $this->input->post('perfil') ? 'active' : ''; ?>">
          <select name="perfil" id="perfil">
            <option value="0">Cargo</option>
            <option <?php echo $this->input->post('perfil') == 1 ? 'selected="true"' : ''; ?> value="1">Estagiário</option>
            <option <?php echo $this->input->post('perfil') == 2 ? 'selected="true"' : ''; ?> value="2">Corretor</option>
            <option <?php echo $this->input->post('perfil') == 3 ? 'selected="true"' : ''; ?> value="3">Coordenador</option>
            <option <?php echo $this->input->post('perfil') == 4 ? 'selected="true"' : ''; ?> value="4">Gerente</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="nome" class="sr-only">Nome e sobrenome</label>
        <input type="text" class="input-text input-12" id="nome" name="nome" placeholder="Nome e sobrenome" value="<?php echo $this->input->post('nome'); ?>">
      </div>

      <div class="form-group">
        <label for="apelido" class="sr-only">Apelido</label>
        <input type="text" class="input-text input-12 apelido-mask" id="apelido" name="apelido" placeholder="Apelido" value="<?php echo $this->input->post('apelido'); ?>">
      </div>

      <div class="form-group">
        <label for="email" class="sr-only">Seu e-mail</label>
        <input type="email" class="input-text input-12" id="email" name="email" placeholder="Seu e-mail" value="<?php echo $this->input->post('email'); ?>">
      </div>

      <div class="form-group">
        <label for="senha" class="sr-only">Senha</label>
        <input type="password" class="input-text input-12" id="senha" name="senha" placeholder="Senha" value="<?php echo $this->input->post('senha'); ?>">
      </div>

      <div class="form-group">
        <label for="repetir_senha" class="sr-only">Repetir senha</label>
        <input type="password" class="input-text input-12" id="repetir_senha" name="repetir_senha" placeholder="Repetir senha" value="<?php echo $this->input->post('repetir_senha'); ?>">
      </div>

      <div class="form-group">
        <label for="cpf" class="sr-only">CPF</label>
        <input type="text" class="input-text input-12 cpf-mask" id="cpf" name="cpf" placeholder="CPF" value="<?php echo $this->input->post('cpf'); ?>">
      </div>

      <div class="form-group">
        <label for="telefone" class="sr-only">Telefone</label>
        <input type="text" class="input-text input-12 phone-mask" id="telefone" name="telefone" placeholder="Telefone" value="<?php echo $this->input->post('telefone'); ?>">
      </div>

      <div class="form-group creci-input-text <?php echo $this->input->post('perfil') == 1 ? 'hidden' : ''; ?>">
        <label for="creci" class="sr-only">CRECI</label>
        <input type="text" class="input-text input-12" id="creci" name="creci" placeholder="CRECI" value="<?php echo $this->input->post('creci'); ?>">
      </div>

      <div class="esqueci-senha">
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" <?php echo isset($usuario['regulamento']) && $usuario['regulamento'] == 1 ? 'checked' : ''; ?> name="regulamento" value="1">
              Li e aceito os termos do <a href="javascript: void(0);" class="btn-abrir-regulamento color-green">Regulamento</a>?
            </label>
          </div>
        </div>
      </div>


      <button type="submit" class="btn btn-block btn-blue-dark btn-cadastrar">
        Clique para cadastrar
      </button>

    </form>
  </div>
</div>