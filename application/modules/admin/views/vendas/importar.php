<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Importar planilha de vendas</h2>

        <?php
        if(isset($importacoes)){
          ?>
          <div class="alert alert-warning">
            <?php
            foreach ($importacoes as $key => $value) {
              if($key == 'vendas_inseridas'){
                ?>
                - <?php echo $value == 1 ? 'Foi inserido <strong>'. $value .'</strong> registro.' : 'Foram inseridos <strong>'. $value .'</strong> registros.'; ?>
                <br />
                <?php
              }else if($key == 'vendas_existentes'){
                ?>
                - Dos registros inseridos, <strong><?php echo $value; ?></strong> aparentemente <?php echo $value == 1 ? 'consta como duplicado' : 'constam como duplicados'; ?>.<br />
                <?php
              }
            }
            ?>
          </div>
          <?php
          print_l();
        }
        ?>

        <form action="<?php echo base_url('admin/vendas/importar'); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" name="flag" value="1" />

                <div class="input-group col-sm-6">
                  <input id="uploadFile" class="form-control" placeholder="Selecione o arquivo" disabled="disabled" />
                  <span class="input-group-btn">
                    <div class="fileUpload btn btn-default">
                        <span>Selecionar arquivo</span>
                        <input id="uploadBtn" type="file" name="arquivo" class="upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                    </div>
                  </span>
                </div>
                <small>Apenas arquivos .xlsx</small>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("uploadBtn").onchange = function () {
    document.getElementById("uploadFile").value = this.value;
};
</script>