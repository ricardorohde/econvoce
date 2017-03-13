<?php
header("Expires: Sat, 24 Jul 2020 05:00:00 GMT");
header('Content-type: application/javascript');
?>
var app = app || {};

var navbar_open = null;
var navbar_open_timeout = 0;

(function (window, document, $, undefined) {
	'use strict';

	app = {
		body: $("body"),

		base_url: function($uri){
			var $base_url = '<?php echo base_url(); ?>';

			if(typeof $uri != 'undefined'){
				$base_url += $uri;
			}

			return $base_url;
		},

		remove_acentos: function(str) {
		  var diacritics = [
		    {char: 'A', base: /[\300-\306]/g},
		    {char: 'a', base: /[\340-\346]/g},
		    {char: 'E', base: /[\310-\313]/g},
		    {char: 'e', base: /[\350-\353]/g},
		    {char: 'I', base: /[\314-\317]/g},
		    {char: 'i', base: /[\354-\357]/g},
		    {char: 'O', base: /[\322-\330]/g},
		    {char: 'o', base: /[\362-\370]/g},
		    {char: 'U', base: /[\331-\334]/g},
		    {char: 'u', base: /[\371-\374]/g},
		    {char: 'N', base: /[\321]/g},
		    {char: 'n', base: /[\361]/g},
		    {char: 'C', base: /[\307]/g},
		    {char: 'c', base: /[\347]/g}
		  ]

		  diacritics.forEach(function(letter){
		    str = str.replace(letter.base, letter.char);
		  });

		  return str;
		},

		init: function(){
			$('.dropdown').on('click', function(){
				var self = $(this).next('.navbar-hover-box');

				if(navbar_open){
					if(navbar_open[0].className !== self[0].className){
						clearTimeout(navbar_open_timeout);
						navbar_open.hide();
					}
				}
				

				self.toggle(0, function(){
					navbar_open = self.is(":visible") ? self : null;
				});
			});

			$('.navbar-hover-box').on('mouseout', function(){
				var self = $(this);
				navbar_open_timeout = setTimeout(function(){
					self.hide();
				}, 1500);
			});

			$('.navbar-hover-box').on('mouseover', function(){
				clearTimeout(navbar_open_timeout);
			});

			$('.btn-fechar-regulamento').on('click', function(){
				$('.regulamento-fullscreen').scrollTop(0).fadeOut('fast');
			});

			$('.btn-abrir-regulamento').on('click', function(){
				$('.regulamento-fullscreen').fadeIn('fast');
			});
		}
	};

	app.init();

}(this, document, jQuery));


