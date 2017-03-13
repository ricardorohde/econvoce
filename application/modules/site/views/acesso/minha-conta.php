<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="text-center">
        <h1 class="page__title">Minha conta</h1>
      </div>
    </div>
  </div>
</div>

<div class="container page-bottom">
  <form action="<?php echo base_url('minha-conta'); ?>" method="post" id="login-form" class="text-left">
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

        <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

        <div class="row">
          <div class="col-xs-12 col-md-6 col-md-offset-3">

            <div class="form-group">
              <label for="perfil" class="sr-only">Cargo</label>

              <div class="select-box <?php echo isset($usuario['perfil']) ? 'active' : ''; ?>">
                <select name="perfil" id="perfil">
                  <option value="0">Cargo</option>
                  <?php
                  if(isset($perfis) && !empty($perfis)){
                    foreach ($perfis as $perfil) {
                      ?>
                      <option <?php echo $usuario['perfil'] == $perfil['id'] ? 'selected="true"' : ''; ?> value="<?php echo $perfil['id']; ?>"><?php echo $perfil['nome']; ?></option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="nome" class="sr-only">Nome e sobrenome</label>
              <input type="text" class="input-text input-12" id="nome" name="nome" placeholder="Nome e sobrenome" value="<?php echo $usuario['nome']; ?>">
            </div>

            <div class="form-group">
              <label for="apelido" class="sr-only">Apelido</label>
              <input type="text" class="input-text input-12 apelido-mask" id="apelido" name="apelido" placeholder="Apelido" value="<?php echo $usuario['apelido']; ?>">
            </div>

            <div class="form-group">
              <label for="email" class="sr-only">Seu e-mail</label>
              <input type="email" class="input-text input-12" id="email" name="email" placeholder="Seu e-mail" value="<?php echo $usuario['email']; ?>">
            </div>

            <div class="form-group">
              <label for="cpf" class="sr-only">CPF</label>
              <input type="text" class="input-text input-12 cpf-mask" id="cpf" name="cpf" placeholder="CPF" value="<?php echo $usuario['cpf']; ?>">
            </div>

            <div class="form-group">
              <label for="telefone" class="sr-only">Telefone</label>
              <input type="text" class="input-text input-12 phone-mask" id="telefone" name="telefone" placeholder="Telefone" value="<?php echo $usuario['telefone']; ?>">
            </div>

            <div class="form-group creci-input-text <?php echo isset($usuario['estagiario']) && $usuario['estagiario'] == 1 ? 'disabled' : ''; ?>">
              <label for="creci" class="sr-only">CRECI</label>
              <input type="text" class="input-text input-12" id="creci" name="creci" placeholder="CRECI" value="<?php echo $usuario['creci']; ?>">
            </div>

            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" <?php echo isset($usuario['estagiario']) && $usuario['estagiario'] == 1 ? 'checked' : ''; ?> name="estagiario" value="1">
                  Sou estagiário
                </label>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label for="senha" class="sr-only">Senha</label>
                  <input type="password" class="input-text input-12" id="senha" name="senha" placeholder="Senha" value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label for="repetir_senha" class="sr-only">Repetir senha</label>
                  <input type="password" class="input-text input-12" id="repetir_senha" name="repetir_senha" placeholder="Repetir senha" value="">
                </div>
              </div>
            </div>

          </div>
        </div>


      </div>
    </div>

    <hr>

    <div class="row">
      <div class="col-xs-12 text-center">
        <button type="submit" class="btn btn-green">Enviar <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </form>
</div>