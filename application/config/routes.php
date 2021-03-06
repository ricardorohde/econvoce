<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'site/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;


// SITE

$route['configjs'] = 'site/tools/configjs';

$route['cadastro/sucesso'] = 'site/acesso/cadastro_sucesso';
$route['cadastro/confirmar/(:any)'] = 'site/acesso/cadastro_confirmar/$1';
$route['(login|logout|cadastro|esqueci-minha-senha)'] = 'site/acesso/$1';
$route['cadastro/redefinir-senha/(:any)'] = 'site/acesso/redefinir_senha/$1';

$route['produtos/(:num)'] = 'site/empreendimentos/index/0/$1';
$route['produtos/(:any)/(:num)'] = 'site/empreendimentos/index/$1/$2';
$route['produtos/(:any)'] = 'site/empreendimentos/index/$1';
$route['produtos'] = 'site/empreendimentos';

$route['ranking/(:num)/(:num)/(:num)'] = 'site/ranking/index/$3/$1/$2';
$route['ranking/(:num)/(:num)'] = 'site/ranking/index/1/$1/$2';
$route['ranking/(:num)'] = 'site/ranking/index/$1';
$route['ranking'] = 'site/ranking';

$route['vendas/(:any)'] = 'site/envios/index/$1';
$route['vendas'] = 'site/envios';
$route['vendas/(:any)/visualizacao'] = 'site/envios/visualizacao/$1';
$route['vendas/(:any)/email'] = 'site/envios/visualizacao_email/$1';
$route['vendas/(:any)/envio'] = 'site/envios/envio/$1';
$route['vendas/(:any)/enviar'] = 'site/envios/envio_processo/$1';

$route['minha-conta'] = 'site/acesso/minha_conta';


// ADMIN

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

$route['admin/recargas/(:num)/(:num)/(:num)'] = 'admin/recargas/index/$1/$2/$3';
$route['admin/recargas/(:num)/(:num)'] = 'admin/recargas/index/$1/$2/1';
$route['admin/recargas/(:num)'] = 'admin/recargas/index/0/0/$1';
