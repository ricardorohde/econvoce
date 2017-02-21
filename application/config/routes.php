<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'site/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['configjs'] = 'site/tools/configjs';
$route['admin/configjs'] = 'admin/tools/configjs';


$route['admin'] = 'admin/dashboard';
$route['admin/(login|logout)'] = 'admin/acesso/$1';

$route['admin/vendas/empreendimento/(:num)/(:num)'] = 'admin/vendas/index/0/0/$2/$1';
$route['admin/vendas/empreendimento/(:num)'] = 'admin/vendas/index/0/0/1/$1';
$route['admin/vendas/(:num)/(:num)/empreendimento/(:num)/(:num)'] = 'admin/vendas/index/$1/$2/$4/$3';
$route['admin/vendas/(:num)/(:num)/empreendimento/(:num)'] = 'admin/vendas/index/$1/$2/1/$3';


$route['admin/vendas/(:num)/(:num)/(:num)'] = 'admin/vendas/index/$1/$2/$3';
$route['admin/vendas/(:num)/(:num)'] = 'admin/vendas/index/$1/$2/1';
$route['admin/vendas/(:num)'] = 'admin/vendas/index/0/0/$1';

$route['admin/usuarios/importar'] = 'admin/usuarios/importar';
$route['admin/usuarios/cadastrar'] = 'admin/usuarios/editar';
$route['admin/usuarios/(:num)/editar'] = 'admin/usuarios/editar/$1';
$route['admin/usuarios/incompletos/(:num)'] = 'admin/usuarios/index/$1/0/1';
$route['admin/usuarios/incompletos'] = 'admin/usuarios/index/1/0/1';
$route['admin/usuarios'] = 'admin/usuarios';
$route['admin/usuarios/(:num)'] = 'admin/usuarios/index/$1';
$route['admin/usuarios/(:any)'] = 'admin/usuarios/index/1/$1';
$route['admin/usuarios/(:any)/(:num)'] = 'admin/usuarios/index/$2/$1';

$route['admin/empreendimentos/importar'] = 'admin/empreendimentos/importar';
$route['admin/empreendimentos/cadastrar'] = 'admin/empreendimentos/editar';
$route['admin/empreendimentos/(:num)/excluir'] = 'admin/empreendimentos/excluir/$1';
$route['admin/empreendimentos/(:num)/editar'] = 'admin/empreendimentos/editar/$1';
$route['admin/empreendimentos/(:num)'] = 'admin/empreendimentos/index/0/$1';
$route['admin/empreendimentos/(:any)'] = 'admin/empreendimentos/index/$1';
$route['admin/empreendimentos/(:any)/(:num)'] = 'admin/empreendimentos/index/$1/$2';
