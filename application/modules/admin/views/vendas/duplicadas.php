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
                              <table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                  <?php
                                  foreach ($venda['usuarios'] as $perfil => $perfis) {
                                    ?>
                                    <td valign="top" width="33%">
                                      <strong><?php echo ucfirst($perfil); ?></strong><br />
                                      <?php
                                      foreach($perfis as $usuario){
                                        ?>
                                        <h6><?php echo $usuario['usuario_apelido']; ?> <small style="text-transform: lowercase;"><?php echo number_format($usuario['pontuacao'], 0, ',', '.'); ?> pontos</small></h6>
                                        <?php
                                      }
                                      ?>
                                    </td>
                                    <?php
                                  }
                                ?>
                              </tr>
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
                            <tr class="info">
                              <td><?php echo $venda_duplicada['venda_id']; ?>-<?php echo $venda_duplicada['empreendimento_nome']; ?></td>
                              <td><?php echo $venda_duplicada['estagio_nome']; ?></td>
                              <td><?php echo $venda_duplicada['unidade'] . (isset($venda_duplicada['torre']) && $venda_duplicada['torre'] != '-' ? '/' . $venda_duplicada['torre'] : ''); ?></td>
                              <td><?php echo $venda_duplicada['data_contrato']; ?></td>
                              <td class="text-primary"><?php echo number_format($venda_duplicada['vgv_liquido'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="info">
                              <td colspan="5">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                  <?php
                                  if(isset($venda_duplicada['usuarios']) && !empty($venda_duplicada['usuarios'])){
                                    ?>
                                    <tr>
                                      <?php
                                      foreach ($venda_duplicada['usuarios'] as $perfil => $perfis) {
                                        ?>
                                        <td valign="top" width="33%">
                                          <strong><?php echo ucfirst($perfil); ?></strong><br />
                                          <?php
                                          foreach($perfis as $usuario){
                                            ?>
                                            <h6><?php echo $usuario['usuario_apelido']; ?> <small style="text-transform: lowercase;"><?php echo number_format($usuario['pontuacao'], 0, ',', '.'); ?> pontos</small></h6>
                                            <?php
                                          }
                                          ?>
                                        </td>
                                        <?php
                                      }
                                    ?>
                                  </tr>
                                  <?php
                                  }
                                  ?>
                                   <tr>
                                    <td colspan="3" class="text-center">
                                      <a href="" class="btn btn-danger btn-xs">Excluir venda</a>
                                      <a href="" class="btn btn-success btn-xs">Não é duplicada</a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
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
