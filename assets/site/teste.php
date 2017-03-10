<html>
  <head>
    <link href="css/main.css" rel="stylesheet"/>
  </head>
  <body>
    
    <div class="container">
      <div class="row" style="background: #0000ff;">
        <?php
        for($loop = 1 ; $loop <= 12 ; $loop++){
          ?>
          <div class="col-md-1"><?php echo $loop; ?></div>
          <?php
        }
        ?>
      </div>

      <div class="row" style="background: #000;">
        <div class="col-xs-5 col-md-1">1</div>
        <div class="col-xs-5 col-md-3">3</div>
      </div>

      <div class="row" style="background: #ff00cc;">
        <?php
        for($loop = 1 ; $loop <= 30 ; $loop++){
          ?>
          <div class="col-md-4"><?php echo $loop; ?></div>
          <?php
        }
        ?>
      </div>

      <div class="row" style="background: #666;">
        <div class="col-md-offset-4 col-md-4">4-4</div>
      </div>
      
    </div>

  </body>
</html>