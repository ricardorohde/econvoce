<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'admin/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['configjs'] = 'site/tools/configjs';
$route['admin/configjs'] = 'admin/tools/configjs';

//$route['admin/vendas/importar'] = 'admin/vendas/importar';

