<div class="content">
  <div class="container-fluid">
    <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

    <div class="row">
      <div class="col-md-12">
        <a href="<?php echo base_url('admin/usuarios/importar'); ?>" class="btn btn-warning">IMPORTAR PLANILHA</a>

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
                  <div class="col-xs-12 col-sm-6">
                </div>
              </div>
            </div>

            <div class="card-content table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>Apelido</th>
                  <th>Perfil</th>
                </thead>
                <tbody>
                  <?php
                    foreach ($usuarios['results'] as $usuario) {
                      ?>
                      <tr>
                        <td><a href="<?php echo base_url('admin/usuarios?q=' . $usuario['apelido'])?>" class="text-muted"><u><?php echo $usuario['apelido']; ?></u></a></td>
                        <td><a href="<?php echo base_url('admin/usuarios?q=' . $usuario['perfil_nome'])?>" class="text-muted"><u><?php echo $usuario['perfil_nome']; ?></u></a></td>
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
