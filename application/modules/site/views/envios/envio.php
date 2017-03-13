<div class="page-icon">
  <img src="<?php echo base_url('assets/site/img/home__icone--vendas.png'); ?>" alt="">
</div>


<div class="container page-bottom">
  
  <?php $this->load->view('submenu.php', $this->_ci_cached_vars); ?>

  <form action="<?php echo base_url($form_action); ?>" method="post" id="login-form" class="text-left">
    <input type="hidden" name="envio_guid" id="envio_guid" value="<?php echo $envio_guid; ?>" />

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

        <?php $this->load->view('site/includes/common/alerts', $this->_ci_cached_vars); ?>


        <div class="envio-status enviando">
          <div class="icon">
            <img src="<?php echo base_url('assets/site/img/loading.gif'); ?>" alt="">
          </div>
          <h1>Aguarde, realizando disparo de e-mails.</h1>
        </div>

        <div class="envio-status enviado">
          <div class="icon">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
          </div>
          <h1>Envio finalizado</h1>

          <div class="table centering">
            <div class="tr">
              <div class="td disparo__item">
                <span class="quantidade enviados">0</span>
                E-mails enviados
              </div>

              <div class="td disparo__item">
                <span class="quantidade erros">0</span>
                Erros do envio
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
</div>