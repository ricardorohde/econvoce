var envios = app['envios'] = {};

var maximo_clientes = 8;
var maximo_clientes_atingido = false;

$(function(){
  var template_cliente_item = $('#cliente-item').html();
  var clientes_itens = $('.cliente-itens');
  Mustache.parse(template_cliente_item);

  $('.maximo_clientes').html(maximo_clientes);

  envios.add_cliente = function(){
    if(!maximo_clientes_atingido){

      if($('.cliente-item').length < maximo_clientes){
        if(!$('.cliente-item').length || ($('.cliente-item').length && (clientes_itens.find('.cliente-item').last().find('.input-nome').val().length || clientes_itens.find('.cliente-item').last().find('.input-email').val().length))){
          $('.cliente-itens').append(Mustache.render(template_cliente_item));
        }
      }


      if($('.cliente-item').length == maximo_clientes){
        maximo_clientes_atingido = true;
        clientes_itens.find('.cliente-item').last().find('[data-adicionar-cliente]').hide();
        clientes_itens.find('.cliente-item').last().find('[data-remover-cliente]').css('display', 'inline-block');
      }else{
        if(!clientes_itens.find('.cliente-item').last().find('.input-nome').val().length && !clientes_itens.find('.cliente-item').last().find('.input-email').val().length && clientes_itens.find('.cliente-item').last().find('[data-remover-cliente]').is(':visible')){
          clientes_itens.find('.cliente-item').last().find('[data-remover-cliente]').hide();
          clientes_itens.find('.cliente-item').last().find('[data-adicionar-cliente]').css('display', 'inline-block');
        }
      }
    }
  };

  envios.init = function(){
    envios.add_cliente();

    $('#empreendimento').change(function(){
      var empreendimento = $(this);

      if(empreendimento.val() == 0){
        empreendimento.closest('.select-box').removeClass('active');
      }else{
        empreendimento.closest('.select-box').addClass('active');
      }
    });

    app.body.on('keyup', '.input-nome, .input-email', function(){
      var self = $(this);
      self.closest('.cliente-item').find('[data-adicionar-cliente]').hide();
      self.closest('.cliente-item').find('[data-remover-cliente]').css('display', 'inline-block');

      envios.add_cliente();
    });

    app.body.on('click', '[data-adicionar-cliente]', function(){
      var self = $(this);

      if(!self.closest('.cliente-item').find('.input-nome').val().length && !self.closest('.cliente-item').find('.input-email').val().length){
        self.closest('.cliente-item').addClass('has-error');
      }else{
        envios.add_cliente();
      }

    });

    app.body.on('click', '[data-remover-cliente]', function(){
      maximo_clientes_atingido = false;
      var self = $(this);
      self.closest('.cliente-item').remove();
      envios.add_cliente();
    });
  };

  envios.init();
});