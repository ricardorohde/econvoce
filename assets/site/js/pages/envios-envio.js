var envios_envio = app['envios_envio'] = {};

$(function(){

  envios_envio.init = function(){
    $.ajax({
      url: app.base_url('vendas/'+ $('#envio_guid').val() +'/enviar'),
      dataType: 'JSON'
    }).done(function(request) {
      $('.quantidade.enviados').html(request.enviados);
      $('.quantidade.erros').html(request.erros);
      $('.envio-status.enviando').hide();
      $('.envio-status.enviado').show();
    });
  };

  envios_envio.init();
});