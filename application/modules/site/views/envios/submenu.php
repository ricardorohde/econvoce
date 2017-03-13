<div class="row">
  <div class="col-xs-12">
    <div class="text-center">
      <h1 class="page__title">Ferramenta que ajuda você a vender.</h1>
      
      <div class="table estagios-buttons">
        <div class="tr">
          <div class="td btn-estagios <?php echo isset($section['hierarchy'][0]) && $section['hierarchy'][0] === 'envios' && !isset($section['hierarchy'][1]) ? 'active' : ''; ?>">
            <span>
              <i class="fa fa-circle-thin hidden-xs" aria-hidden="true"></i>
              PREPARO DE E-MAIL
            </span>
          </div>
          <div class="td btn-estagios <?php echo isset($section['hierarchy'][0]) && $section['hierarchy'][0] === 'envios' && isset($section['hierarchy'][1]) && $section['hierarchy'][1] === 'visualizacao' ? 'active' : ''; ?>">
            <span>
              <i class="fa fa-circle-thin hidden-xs" aria-hidden="true"></i>
              VISUALIZAÇÃO
            </span>
          </div>
          <div class="td btn-estagios <?php echo isset($section['hierarchy'][0]) && $section['hierarchy'][0] === 'envios' && isset($section['hierarchy'][1]) && $section['hierarchy'][1] === 'envio' ? 'active' : ''; ?>">
            <span>
              <i class="fa fa-circle-thin hidden-xs" aria-hidden="true"></i>
              ENVIO
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>