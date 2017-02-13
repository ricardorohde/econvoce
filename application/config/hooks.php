<?php defined('BASEPATH') OR exit('No direct script access allowed');

$hook['pre_system'] = array(
	array(
		'function' => 'print_l',
		'filename' => 'functions.php',
		'filepath' => 'hooks'
	)
);
