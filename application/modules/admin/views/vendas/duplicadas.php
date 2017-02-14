<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="purple">
                <h4 class="title">Vendas duplicadas</h4>
                <p class="category">Aparece como 'duplicada' a venda com as mesmas características (Empreendimento/Unidade/Torre).</p>
            </div>


          <div class="card-content table-responsive">
            <?php
              if(isset($vendas['results']) && !empty($vendas['results'])){
                $count_duplicadas = 0;
                foreach ($vendas['results'] as $venda) {
                  ?>
                  <table class="table">
                    <?php
                    if(!$count_duplicadas){
                      ?>
                      <thead class="text-primary">
                        <th>Empreendimento</th>
                        <th>Estágio</th>
                        <th>Unidade/Torre</th>
                        <th>Data</th>
                        <th>VGV (L)</th>
                      </thead>
                      <?php
                    }
                    ?>
                    <tbody>
                        <tr class="warning">
                          <td><?php echo $venda['venda_id']; ?>-<?php echo $venda['empreendimento_nome']; ?></td>
                          <td><?php echo $venda['estagio_nome']; ?></td>
                          <td><?php echo $venda['unidade'] . (isset($venda['torre']) && $venda['torre'] != '-' ? '/' . $venda['torre'] : ''); ?></td>
                          <td><?php echo $venda['data_contrato']; ?></td>
                          <td class="text-primary"><?php echo number_format($venda['vgv_liquido'], 0, ',', '.'); ?></td>
                        </tr>

                        <?php
                        if(isset($venda['usuarios']) && !empty($venda['usuarios'])){
                          ?>
                          <tr class="warning">
                            <td colspan="5">
                              <table class="table table table-condensed">
                                <thead class="text-primary">
                                  <th>Usuário</th>
                                  <th>Perfil na venda</th>
                                  <th>Pontuação</th>
                                </thead>
                                <tbody>
                                  <?php
                                  foreach ($venda['usuarios'] as $usuario) {
                                    ?>
                                    <tr>
                                      <td><?php echo $usuario['usuario_apelido']; ?></td>
                                      <td><?php echo $usuario['perfil_nome']; ?></td>
                                      <td><?php echo $usuario['pontuacao']; ?></td>
                                    </tr>
                                    <?php
                                  }
                                ?>
                                </table>
                              </td>
                            </tr>
                            <?php
                        }
                        ?>

                        <?php
                        if(isset($venda['duplicados']) && !empty($venda['duplicados'])){
                          foreach ($venda['duplicados'] as $venda_duplicada) {
                            ?>
                            <tr class="danger">
                              <td><?php echo $venda_duplicada['venda_id']; ?>-<?php echo $venda_duplicada['empreendimento_nome']; ?></td>
                              <td><?php echo $venda_duplicada['estagio_nome']; ?></td>
                              <td><?php echo $venda_duplicada['unidade'] . (isset($venda_duplicada['torre']) && $venda_duplicada['torre'] != '-' ? '/' . $venda_duplicada['torre'] : ''); ?></td>
                              <td><?php echo $venda_duplicada['data_contrato']; ?></td>
                              <td class="text-primary"><?php echo number_format($venda_duplicada['vgv_liquido'], 0, ',', '.'); ?></td>
                            </tr>

                            <?php
                            if(isset($venda_duplicada['usuarios']) && !empty($venda_duplicada['usuarios'])){
                              ?>
                              <tr class="warning">
                                <td colspan="5">
                                  <table class="table table table-condensed">
                                    <thead class="text-primary">
                                      <th>Usuário</th>
                                      <th>Perfil na venda</th>
                                      <th>Pontuação</th>
                                    </thead>
                                    <tbody>
                                      <?php
                                      foreach ($venda_duplicada['usuarios'] as $usuario) {
                                        ?>
                                        <tr>
                                          <td><?php echo $usuario['usuario_apelido']; ?></td>
                                          <td><?php echo $usuario['perfil_nome']; ?></td>
                                          <td><?php echo $usuario['pontuacao']; ?></td>
                                        </tr>
                                        <?php
                                      }
                                    ?>
                                    </table>
                                  </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <?php
                          }
                        }
                        ?>
                    </tbody>
                  </table>
                  <hr>
                  <?php
                  $count_duplicadas++;
                }
              }
            ?>
          </div>

          <?php echo isset($vendas['pagination']) ? $vendas['pagination'] : ''; ?>
        </div>
      </div>
    </div>
  </div>
</div>
