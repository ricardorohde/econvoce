<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2><?php echo isset($action) && $action == 'editar' ? 'Editar empreendimento' : 'Cadastrar empreendimento'; ?></h2>

        <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

        <form action="<?php echo base_url(isset($form_action) ? $form_action : 'admin/empreendimentos/cadastrar'); ?>" method="POST">
            <div class="form-group">
                <label class="sr-only" for="">Nome do empreendimento</label>
                <input type="text" name="apelido" class="form-control" placeholder="Nome do empreendimento" value="<?php echo isset($empreendimento['apelido']) ? $empreendimento['apelido'] : '';?>">
            </div>

            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                    <label for="">Estágio</label>
                    <select name="estagio" class="form-control">
                      <?php
                      if(isset($estagios) && !empty($estagios)){
                        foreach ($estagios as $estagio) {
                          ?>
                          <option <?php echo isset($empreendimento['estagio']) && $empreendimento['estagio'] === $estagio['id'] ? 'selected="true"' : ''; ?> value="<?php echo $estagio['id']; ?>"><?php echo $estagio['nome']; ?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Prioridade</label>
                    <select name="prioridade" class="form-control">
                      <?php
                      if(isset($prioridades) && !empty($prioridades)){
                        foreach ($prioridades as $prioridade) {
                          ?>
                          <option <?php echo isset($empreendimento['prioridade']) && $empreendimento['prioridade'] === $prioridade['id'] ? 'selected="true"' : ''; ?> value="<?php echo $prioridade['id']; ?>"><?php echo $prioridade['id']; ?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                </div>
              </div>
            </div>


            
            <!--div class="form-group">
                <label class="sr-only" for="">Textarea</label>
                <textarea class="form-control" placeholder="Textarea" rows="1"></textarea>
            </div-->

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

      </div>
    </div>
  </div>
</div>
