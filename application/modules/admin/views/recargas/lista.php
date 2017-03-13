<div class="content">
  <div class="container-fluid">
    <?php $this->load->view('admin/includes/alerts', $this->_ci_cached_vars); ?>

    <div class="row">
      <div class="col-md-12">
        <a href="<?php echo base_url('admin/recargas/importar'); ?>" class="btn btn-warning">IMPORTAR PLANILHA DE CARTÕES</a>

        <?php
        if(isset($filter) && $filter){
          ?>
          <a href="<?php echo base_url('admin/recargas'); ?>" class="btn btn-danger">Remover filtro de busca</a>
          <?php
        }
        ?>

        <?php
        if(isset($empreendimento) && !empty($empreendimento)){
          ?>
          <h3><?php echo $empreendimento['apelido']; ?></h3>
          <?php
        }
        ?>

        <?php
        if(isset($vendas['results']) && !empty($vendas['results'])){
          ?>
          <div class="card">
            <?php
            if($periodos){
              ?>
              <div class="card-header" data-background-color="gray">
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <a class="btn btn-sm"><?php echo isset($vendas['total_rows']) ? ($vendas['total_rows'] == 1 ? 'Foi encontrado 1 registro' : 'Foram encontrados ' . $vendas['total_rows'] . ' registros') : '<strong>Nenhum registro encontrado</strong>'; ?></a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                    <ul class="nav nav-sm nav-pills">
                      <li role="presentation" class="<?php echo (!isset($mes) || (isset($mes) && $mes == 0)) && (!isset($ano) || (isset($ano) && $ano == 0)) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/vendas' . (isset($empreendimento) && !empty($empreendimento) ? '/empreendimento/' . $empreendimento['empreendimento_id'] : '')); ?>">Todos</a></li>
                      <?php
                      foreach ($periodos as $periodo) {
                        ?>
                        <li role="presentation" class="<?php echo (isset($mes) && $mes == $periodo['mes'] && isset($ano) && $ano == $periodo['ano']) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/vendas/' . $periodo['mes'] . '/' . $periodo['ano'] . (isset($empreendimento) && !empty($empreendimento) ? '/empreendimento/' . $empreendimento['empreendimento_id'] : '')); ?>"><?php echo $this->admin->mes($periodo['mes']); ?>/<?php echo $periodo['ano']; ?></a></li>
                        <?php
                      }
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>

            <div class="card-content table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>Empreendimento</th>
                  <th>Estágio</th>
                  <th>Unidade/Torre</th>
                  <th>Data</th>
                  <th>VGV (L)</th>
                </thead>
                <tbody>
                  <?php
                    foreach ($vendas['results'] as $venda) {
                      ?>
                      <tr class="<?php echo isset($venda['parente']) && $venda['parente'] != 0 ? 'warning' : ''; ?>">
                        <td><a href="<?php echo base_url('admin/vendas?q=' . $venda['empreendimento_apelido'])?>" class="text-muted"><u><?php echo $venda['empreendimento_apelido']; ?></u></a></td>
                        <td><a href="<?php echo base_url('admin/vendas?q=' . $venda['estagio_nome'])?>" class="text-muted"><u><?php echo $venda['estagio_nome']; ?></u></a></td>
                        <td><?php echo $venda['unidade'] . (isset($venda['torre']) && $venda['torre'] != '-' ? '/' . $venda['torre'] : ''); ?></td>
                        <td><?php echo $venda['data_contrato']; ?></td>
                        <td class="text-primary"><?php echo number_format($venda['vgv_liquido'], 0, ',', '.'); ?></td>
                      </tr>
                      <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>

            <?php echo isset($vendas['pagination']) ? $vendas['pagination'] : ''; ?>
          </div>
          <?php
        }else{
          ?>
          <p>&nbsp;</p>
          <div class="alert alert-danger">Nenhuma venda encontrada.</div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
