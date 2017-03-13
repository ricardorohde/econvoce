<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <?php
        if($this->site->user_logged(true, false)){
          ?>
          <a href="javascript: void(0);" class="btn btn-blue-dark btn-perguntas-frequentes btn-abrir-regulamento">Regulamento</a>
          <?php
        }else{
          ?>
          <a href="mailto:sac@econvoce.com.br" class="btn btn-white color-green btn-perguntas-frequentes">
            FAQ
            <span class="hidden-xs">Econ Você</span>
          </a>
          <?php
        }
        ?>

        <div class="footer__text">
          <span class="copy">Todos os direitos reservados</span>
          <span class="logo-econvoce">econVocê</span>
        </div>
      </div>
    </div>
  </div>
</footer>