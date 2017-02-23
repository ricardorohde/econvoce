    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
<?php
?>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-menu">
            <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'home') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'empreendimentos') ? 'active' : ''; ?>"><a href="<?php echo base_url('empreendimentos'); ?>">Empreendimentos</a></li>
            <li class="<?php echo isset($section['hierarchy']) && ($section['hierarchy'][0] === 'ranking') ? 'active' : ''; ?>"><a href="<?php echo base_url('ranking'); ?>">Ranking</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Actfjds kjfhkdsjhf kjhdskjfhdjskh d jksahk jhksajhd ksah ion</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
