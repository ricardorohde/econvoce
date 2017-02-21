<div class="content">
  <div class="container-fluid">
    <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

    <div class="row">
      <div class="col-md-12">
        <a href="<?php echo base_url('admin/usuarios/importar'); ?>" class="btn btn-warning">IMPORTAR PLANILHA</a>
        <a href="<?php echo base_url('admin/usuarios/cadastrar'); ?>" class="btn btn-warning">CADASTRAR USUÁRIO</a>

        <?php
        if(isset($filter) && $filter){
          ?>
          <a href="<?php echo base_url('admin/usuarios'); ?>" class="btn btn-danger">Remover filtro de busca</a>
          <?php
        }
        ?>

        <?php
        if(isset($usuarios['results']) && !empty($usuarios['results'])){
          ?>
          <div class="card">
            <div class="card-header" data-background-color="gray">
              <div class="row">
                <div class="col-xs-12 col-sm-6">
                  <a class="btn btn-sm"><?php echo isset($usuarios['total_rows']) ? ($usuarios['total_rows'] == 1 ? 'Foi encontrado 1 registro' : 'Foram encontrados ' . $usuarios['total_rows'] . ' registros') : '<strong>Nenhum registro encontrado</strong>'; ?></a>
                </div>
                <?php
                if(isset($perfis) && !empty($perfis)){
                  ?>
                  <div class="col-xs-12 col-sm-6">
                    <ul class="nav nav-sm nav-pills">
                      <li role="presentation" class="<?php echo $perfil_slug === 0 ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/usuarios'); ?>">Todos</a></li>
                      <?php
                      foreach ($perfis as $perfil) {
                        ?>
                        <li role="presentation" class="<?php echo (isset($perfil_slug) && $perfil_slug === $perfil['slug']) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/usuarios/' . $perfil['slug']); ?>"><?php echo $perfil['nome']; ?></a></li>
                        <?php
                      }
                      ?>
                    </ul>
                  </div>
                  <?php
                }
                ?>
              </div>
            </div>

            <div class="card-content table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th width="50%">Nome</th>
                  <th width="30%">Apelido</th>
                  <th width="20%">Perfil</th>
                  <th></th>
                </thead>
                <tbody>
                  <?php
                    foreach ($usuarios['results'] as $usuario) {
                      ?>
                      <tr class="<?php echo isset($usuario['status']) && $usuario['status'] == 2 ? 'warning' : ''; ?>">
                        <td><a href="<?php echo base_url('admin/usuarios?q=' . $usuario['nome'])?>" class="text-muted"><u><?php echo $usuario['nome']; ?></u></a></td>
                        <td><a href="<?php echo base_url('admin/usuarios?q=' . $usuario['apelido'])?>" class="text-muted"><u><?php echo $usuario['apelido']; ?></u></a></td>
                        <td><a href="<?php echo base_url('admin/usuarios?q=' . $usuario['perfil_nome'])?>" class="text-muted"><u><?php echo $usuario['perfil_nome']; ?></u></a></td>
                        <td nowrap="true">
                          <a class="btn btn-warning btn-xs" href="<?php echo base_url('admin/usuarios/' . $usuario['id'] . '/editar'); ?>">Editar</a>
                          <a onclick="return confirm('Se você excluir este usuário, todas as vendas e pontuações relacionadas a ele também serão excluídas. Deseja continuar?');" class="btn btn-danger btn-xs" href="<?php echo base_url('admin/usuarios/' . $usuario['id'] . '/excluir'); ?>">Excluir</a>
                        </td>
                      </tr>
                      <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>

            <?php echo isset($usuarios['pagination']) ? $usuarios['pagination'] : ''; ?>
          </div>
          <?php
        }else{
          ?>
          <p>&nbsp;</p>
          <div class="alert alert-danger">Nenhum usuário encontrado.</div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
