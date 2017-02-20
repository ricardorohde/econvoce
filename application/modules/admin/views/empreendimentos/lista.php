<div class="content">
  <div class="container-fluid">
    <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

    <div class="row">
      <div class="col-md-12">
        <a href="<?php echo base_url('admin/empreendimentos/importar'); ?>" class="btn btn-warning">IMPORTAR PLANILHA</a>
        <a href="<?php echo base_url('admin/empreendimentos/cadastrar'); ?>" class="btn btn-warning">CADASTRAR EMPREENDIMENTO</a>

        <?php
        if(isset($filter) && $filter){
          ?>
          <a href="<?php echo base_url('admin/empreendimentos'); ?>" class="btn btn-danger">Remover filtro de busca</a>
          <?php
        }
        ?>

        <?php
        if(isset($empreendimentos['results']) && !empty($empreendimentos['results'])){
          ?>
          <div class="card">
            <div class="card-header" data-background-color="gray">
              <div class="row">
                <div class="col-xs-12 col-sm-6">
                  <a class="btn btn-sm"><?php echo isset($empreendimentos['total_rows']) ? ($empreendimentos['total_rows'] == 1 ? 'Foi encontrado 1 registro' : 'Foram encontrados ' . $empreendimentos['total_rows'] . ' registros') : '<strong>Nenhum registro encontrado</strong>'; ?></a>
                </div>

                <?php
                if(isset($estagios) && !empty($estagios)){
                  ?>
                  <div class="col-xs-12 col-sm-6">
                    <ul class="nav nav-sm nav-pills">
                      <li role="presentation" class="<?php echo $estagio_slug === 0 ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/empreendimentos'); ?>">Todos</a></li>
                      <?php
                      foreach ($estagios as $estagio) {
                        ?>
                        <li role="presentation" class="<?php echo (isset($estagio_slug) && $estagio_slug === $estagio['slug']) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/empreendimentos/' . $estagio['slug']); ?>"><?php echo $estagio['nome']; ?></a></li>
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
                  <th width="50%">Nome do empreendimento</th>
                  <th width="40%">Estágio</th>
                  <th width="10%" class="text-center">Prioridade</th>
                  <th></th>
                </thead>
                <tbody>
                <?php

                  foreach ($empreendimentos['results'] as $empreendimento) {
                    ?>
                    <tr>
                      <td><?php echo !empty($empreendimento['nome']) ? $empreendimento['nome'] : $empreendimento['apelido']; ?></td>
                      <td><?php echo $empreendimento['estagio_nome']; ?></td>
                      <td class="text-center"><?php echo $empreendimento['prioridade']; ?></td>
                      <td nowrap="true">
                        <a class="btn btn-info btn-xs" href="<?php echo base_url('admin/vendas/empreendimento/' . $empreendimento['empreendimento_id']); ?>">Vendas</a>
                        <a class="btn btn-warning btn-xs" href="<?php echo base_url('admin/empreendimentos/' . $empreendimento['empreendimento_id'] . '/editar'); ?>">Editar</a>
                        <a onclick="return confirm('Se você excluir este empreendimento, todas as vendas e pontuações relacionadas a ele também serão excluídas. Deseja continuar?');" class="btn btn-danger btn-xs" href="<?php echo base_url('admin/empreendimentos/' . $empreendimento['empreendimento_id'] . '/excluir'); ?>">Excluir</a>
                      </td>
                    </tr>
                    <?php
                  }

                ?>
                </tbody>
              </table>
            </div>
            <?php echo isset($empreendimentos['pagination']) ? $empreendimentos['pagination'] : ''; ?>
          </div>
          <?php
        }else{
          ?>
          <p>&nbsp;</p>
          <div class="alert alert-danger">Nenhum empreendimento encontrado</div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
