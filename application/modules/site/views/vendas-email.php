<?php
$perfis_cores = $this->config->item('perfis_cores');
$estagios_cores = $this->config->item('estagios_cores');

$params = array(
  'usuario_nome' => $this->site->userinfo('nome_sobrenome'),
  'usuario_perfil_nome' => $this->site->userinfo('perfil_nome'),
  'usuario_perfil_cor' => $perfis_cores[$this->site->userinfo('perfil_slug')],
  'usuario_telefone' => $this->site->userinfo('telefone'),
  'usuario_email' => $this->site->userinfo('email'),

  'cliente_nome' => 'Cliente',

  'empreendimento_nome' => $envio['empreendimento_nome'],
  'empreendimento_endereco' => $envio['empreendimento_endereco'],
  'empreendimento_lazer' => $envio['empreendimento_lazer'],
  'empreendimento_dormitorios' => $envio['empreendimento_dormitorios'],
  'empreendimento_suites' => $envio['empreendimento_suites'],
  'empreendimento_vagas' => $envio['empreendimento_vagas'],
  'empreendimento_area' => $envio['empreendimento_area'],
  'empreendimento_imagem' => $envio['empreendimento_imagem'],
  'empreendimento_url' => $envio['empreendimento_url'],

  'estagio_nome' => strtoupper($envio['estagio_nome']),
  'estagio_cor' => $estagios_cores[$envio['estagio_slug']],
);

echo $this->site->obter_email_template('vendas', $params);

?>