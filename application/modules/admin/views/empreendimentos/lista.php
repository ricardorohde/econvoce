<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card">

          <div class="card-header" data-background-color="gray">
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <a class="btn btn-sm"><?php echo isset($empreendimentos['total_rows']) ? ($empreendimentos['total_rows'] == 1 ? 'Foi encontrado 1 registro' : 'Foram encontrados ' . $empreendimentos['total_rows'] . ' registros') : '<strong>Nenhum registro encontrado</strong>'; ?></a>
              </div>

              <?php
              if(isset($estagios)){
                ?>
                <div class="col-xs-12 col-sm-6">
                  <ul class="nav nav-sm nav-pills">
                    <?php
                    foreach ($estagios as $estagio) {
                      ?>
                      <li role="presentation" class="<?php echo (isset($estagio_slug) && $estagio_slug == $estagio['slug']) ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/empreendimentos/' . $estagio['slug']); ?>"><?php echo $estagio['nome']; ?></a></li>
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
                <th>Nome do empreendimento</th>
                <th>Estágio</th>
              </thead>
              <tbody>
              <?php
              if(isset($empreendimentos['results']) && !empty($empreendimentos['results'])){
                foreach ($empreendimentos['results'] as $empreendimento) {
                  ?>
                  <tr>
                    <td><?php echo $empreendimento['nome'] === $empreendimento['apelido'] ? $empreendimento['apelido'] : $empreendimento['nome']; ?></td>
                    <td><?php echo $empreendimento['estagio_nome']; ?></td>
                  </tr>
                  <?php
                }
              }
              ?>
              </tbody>
            </table>
          </div>

          <?php echo isset($empreendimentos['pagination']) ? $empreendimentos['pagination'] : ''; ?>
        </div>
      </div>
    </div>
  </div>
</div>
