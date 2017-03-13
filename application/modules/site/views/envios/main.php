<div class="page-icon">
  <img src="<?php echo base_url('assets/site/img/home__icone--vendas.png'); ?>" alt="">
</div>


<div class="container page-bottom">

  <?php $this->load->view('submenu.php', $this->_ci_cached_vars); ?>

  <form action="<?php echo base_url($form_action); ?>" method="post" id="login-form" class="text-left">
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

        <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

        <div class="form-group-item">
          <label for="empreendimento" class="label">Selecione o produto que você gostaria de enviar para os seus clientes.</label>

          <div class="select-box <?php echo isset($envio['empreendimento']) ? 'active' : ''; ?>">
            <select name="empreendimento" id="empreendimento">
              <option value="0">Selecione o produto</option>
              <?php
              if(isset($empreendimentos['results']) && !empty($empreendimentos['results'])){
                foreach($empreendimentos['results'] as $empreendimento){
                  ?>
                  <option <?php echo isset($envio['empreendimento']) && $envio['empreendimento'] == $empreendimento['empreendimento_id'] ? 'selected="true"' : ''; ?> value="<?php echo $empreendimento['empreendimento_id']; ?>"><?php echo $empreendimento['nome']; ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
        </div>

        <br>

        <label for="perfil" class="label">Cadastre as informações do seu cliente <span class="color-green">nome</span> e <span class="color-green">e-mail</span>. Até <span class="maximo_clientes"></span> clientes.</label>

        <div class="table">
          <div class="tbody cliente-itens">
          <?php
          if(isset($envio['emails']) && !empty($envio['emails'])){
            foreach ($envio['emails'] as $cliente) {
              ?>
              <div class="tr cliente-item <?php echo isset($cliente['error']) && $cliente['error'] ? 'has-error' : ''; ?>">

                <div class="td nome">
                  <div class="form-group">
                    <input type="text" class="input-text input-nome input-12" name="nome[]" placeholder="Nome do cliente" value="<?php echo $cliente['nome']; ?>">
                  </div>
                </div>

                <div class="td email">
                  <div class="form-group">
                    <input type="email" class="input-text input-email input-12" name="email[]" placeholder="E-mail" value="<?php echo $cliente['email']; ?>">
                  </div>
                </div>

                <div class="td">
                  <a href="javascript: void(0);" data-adicionar-cliente class="btn btn-adicionar-cliente btn-round btn-transparent"><i class="fa fa-plus" aria-hidden="true"></i></a>
                  <a href="javascript: void(0);" data-remover-cliente class="btn btn-remover-cliente btn-round btn-red"><i class="fa fa-minus" aria-hidden="true"></i></a>
                </div>

              </div>
              <?php
            }
          }
          ?>
          </div>
        </div>

      </div>
    </div>

    <hr>

    <div class="row">
      <div class="col-xs-12 text-right">
        <button type="submit" class="btn btn-green">Avançar <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </form>
</div>


<script id="cliente-item" type="x-tmpl-mustache">
  <div class="tr cliente-item">

    <div class="td nome">
      <div class="form-group">
        <input type="text" class="input-text input-nome input-12" name="nome[]" placeholder="Nome do cliente">
      </div>
    </div>

    <div class="td email">
      <div class="form-group">
        <input type="email" class="input-text input-email input-12" name="email[]" placeholder="E-mail">
      </div>
    </div>

    <div class="td">
      <a href="javascript: void(0);" data-adicionar-cliente class="btn btn-adicionar-cliente btn-round btn-transparent"><i class="fa fa-plus" aria-hidden="true"></i></a>
      <a href="javascript: void(0);" data-remover-cliente class="btn btn-remover-cliente btn-round btn-red"><i class="fa fa-minus" aria-hidden="true"></i></a>
    </div>

  </div>
</script>