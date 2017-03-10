$(document).ready(function(){
	$('.cpf-mask').mask('000.000.000-00', {reverse: true});

	var SPMaskBehavior = function (val) {
	  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	spOptions = {
	  onKeyPress: function(val, e, field, options) {
	      field.mask(SPMaskBehavior.apply({}, arguments), options);
	    }
	};

	$('.phone-mask').mask(SPMaskBehavior, spOptions);

	$('.apelido-mask').on('keyup', function () {
		$(this).val(function (_, val) {
			return app.remove_acentos(val.toUpperCase().replace(' ', ''));
		});
	});

	$('#perfil').change(function(){
		var perfil = $(this);
		var creci_input_text = $('.creci-input-text').show();


		if(perfil.val() == 0){
			perfil.closest('.select-box').removeClass('active');
		}else{
			perfil.closest('.select-box').addClass('active');

			if(perfil.val() == 1){
				creci_input_text.hide();
			}

			
			// console.log(perfil.val());
		}

		
		// 

		// if($this.val() > 0){
		// 	
		// }


		//console.log($this.val());
		// if($this.val()){

		// }

		// $this.addClass('active')
	});

	
});