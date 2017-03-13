<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Importar planilha de recargas</h2>

        <?php
        if(isset($importacoes)){
          ?>
          <div class="alert alert-warning">
            <?php
            foreach ($importacoes as $key => $value) {
              if($key == 'vendas_inseridas' && $value){
                ?>
                - <?php echo $value == 1 ? '<strong>'. $value .'</strong> registro inserido' : '<strong>'. $value .'</strong> registros inseridos'; ?>.
                <br />
                <?php
              }else if($key == 'vendas_existentes' && $value){
                ?>
                - <strong><?php echo $value; ?></strong> <?php echo $value == 1 ? 'registro' : 'registros'; ?> com o mesmo empreendimento/unidade/torre.<br />
                <?php
              }else if($key == 'vendas_identicas' && $value){
                ?>
                - <strong><?php echo $value; ?></strong> <?php echo $value == 1 ? 'registro ignorado por conter' : 'registros ignorados por conterem'; ?> o mesmo empreendimento/unidade/torre e a mesma data de contrato.<br />
                <?php
              }
            }
            ?>
          </div>
          <?php
        }
        ?>

        <form action="<?php echo base_url('admin/recargas/importar'); ?>" method="POST" enctype="multipart/form-data">
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