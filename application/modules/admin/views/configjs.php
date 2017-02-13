<?php
header("Expires: Sat, 24 Jul 2020 05:00:00 GMT");
header('Content-type: application/javascript');
?>
var app = app || {};

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
		}
	};

}(this, document, jQuery));
