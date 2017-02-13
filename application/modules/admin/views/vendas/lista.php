<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
      <a href="<?php echo base_url('admin/vendas/importar'); ?>" class="btn btn-warning">IMPORTAR PLANILHA</a>
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
                    <?php
                    foreach ($periodos as $periodo) {
                      ?>
                      <li role="presentation" class="<?php echo (isset($mes) && $mes == $periodo['mes'] && isset($ano) && $ano == $periodo['ano']) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/vendas/' . $periodo['mes'] . '/' . $periodo['ano']); ?>"><?php echo $this->admin->mes($periodo['mes']); ?>/<?php echo $periodo['ano']; ?></a></li>
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
              if(isset($vendas['results']) && !empty($vendas['results'])){
                foreach ($vendas['results'] as $venda) {
                  ?>
                  <tr class="<?php echo isset($venda['parente']) && $venda['parente'] != 0 ? 'warning' : ''; ?>">
                    <td><?php echo $venda['empreendimento_nome']; ?></td>
                    <td><?php echo $venda['estagio_nome']; ?></td>
                    <td><?php echo $venda['unidade'] . (isset($venda['torre']) && $venda['torre'] != '-' ? '/' . $venda['torre'] : ''); ?></td>
                    <td><?php echo $venda['data_contrato']; ?></td>
                    <td class="text-primary"><?php echo number_format($venda['vgv_liquido'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php
                }
              }
              ?>
              </tbody>
            </table>
          </div>

          <?php echo isset($vendas['pagination']) ? $vendas['pagination'] : ''; ?>
        </div>
      </div>
    </div>
  </div>
</div>
