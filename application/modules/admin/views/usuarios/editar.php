<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2><?php echo isset($action) && $action == 'editar' ? 'Editar usuário' : 'Cadastrar usuário'; ?></h2>

        <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

        <form action="<?php echo base_url(isset($form_action) ? $form_action : 'admin/usuarios/cadastrar'); ?>" method="POST">
            <div class="form-group">
                <label class="sr-only" for="">Nome completo</label>
                <input type="text" name="nome" class="form-control" placeholder="Nome completo" value="<?php echo isset($usuario['nome']) ? $usuario['nome'] : '';?>">
            </div>

            <div class="form-group">
                <label class="sr-only" for="">Apelido</label>
                <input type="text" name="apelido" class="form-control" placeholder="Apelido" value="<?php echo isset($usuario['apelido']) ? $usuario['apelido'] : '';?>">
            </div>

            <div class="form-group">
                <label class="sr-only" for="">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="E-mail" value="<?php echo isset($usuario['email']) ? $usuario['email'] : '';?>">
            </div>

            <div class="form-group">
                <label class="sr-only" for="">Telefone</label>
                <input type="text" name="telefone" class="form-control" placeholder="Telefone" value="<?php echo isset($usuario['telefone']) ? $usuario['telefone'] : '';?>">
            </div>

            <div class="form-group">
                <label class="sr-only" for="">CRECI</label>
                <input type="text" name="creci" class="form-control" placeholder="CRECI" value="<?php echo isset($usuario['creci']) ? $usuario['creci'] : '';?>">
            </div>

            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                    <label for="">Perfil</label>
                    <select name="perfil" class="form-control">
                      <?php
                      if(isset($perfis) && !empty($perfis)){
                        foreach ($perfis as $perfil) {
                          ?>
                          <option <?php echo isset($usuario['perfil']) && $usuario['perfil'] === $perfil['id'] ? 'selected="true"' : ''; ?> value="<?php echo $perfil['id']; ?>"><?php echo $perfil['nome']; ?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control">
                      <?php
                      foreach($this->config->item('usuarios_status') as $key => $value){
                        ?>
                        <option <?php echo isset($usuario['status']) && $usuario['status'] == $key ? 'selected="true"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php

                      }
                      ?>
                    </select>
                </div>
              </div>
            </div>

        
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

      </div>
    </div>
  </div>
</div>
