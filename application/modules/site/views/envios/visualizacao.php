<div class="page-icon">
  <img src="<?php echo base_url('assets/site/img/home__icone--vendas.png'); ?>" alt="">
</div>


<div class="container page-bottom">
  
  <?php $this->load->view('submenu.php', $this->_ci_cached_vars); ?>

  <form action="<?php echo base_url($form_action); ?>" method="post" id="login-form" class="text-left">
    <input type="hidden" name="envio_flag" value="1" />
    
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

        <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>

        <div class="form-group-item">
          <label class="area__title">Confirmação de e-mails</label>

          <div class="emails__list row">
            <?php
            if(isset($envio['emails']) && !empty($envio['emails'])){
              foreach ($envio['emails'] as $cliente) {
                ?>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                  <div class="cliente-email__item">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <?php echo $cliente['email']; ?>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>
        </div>

        <hr>

        <div class="area__title">Visualização do email selecionado</div>
        <div class="area__description">Como o cliente irá visualizar o e-mail</div>


        <div class="email-preview text-center">
          <script>
            function resizeIframe(obj) {
              obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
            }
          </script>
          <iframe width="600" src="<?php echo base_url('vendas/'. $envio_guid .'/email')?>" frameborder="0" onload="resizeIframe(this);"></iframe>
        </div>

      </div>
    </div>

    <hr>

    <div class="row">
      <div class="col-xs-6">
        <a href="<?php echo base_url('vendas/' . $envio_guid); ?>" class="btn btn-grey-transparent"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Voltar</a>
      </div>

      <div class="col-xs-6 text-right">
        <button type="submit" class="btn btn-green">Enviar <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </form>
</div>