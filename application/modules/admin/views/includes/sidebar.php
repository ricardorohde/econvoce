<div class="sidebar" data-color="blue" data-image="../assets/img/sidebar-1.jpg">
  <!--
  Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
  Tip 2: you can also add an image using data-image tag
  -->

  <div class="logo">
    <a href="<?php echo base_url('admin'); ?>" class="simple-text">Econ Você</a>
  </div>

  <?php
  $menu = array(
    array(
      'icon' => 'dashboard',
      'slug' => 'dashboard',
      'name' => 'Dashboard',
      'url' => 'admin',
    ),
    array(
      'icon' => 'bubble_chart',
      'slug' => 'vendas',
      'name' => 'Vendas',
      'url' => 'admin/vendas',
    ),
    array(
      'icon' => 'person',
      'slug' => 'usuarios',
      'name' => 'Usuários',
      'url' => 'admin/usuarios',
    ),
    array(
      'icon' => 'location_on',
      'slug' => 'empreendimentos',
      'name' => 'Empreendimentos',
      'url' => 'admin/empreendimentos',
    )
  );
  ?>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <?php
      foreach ($menu as $value) {
        ?>
        <li class="<?php echo isset($section['page']['one']) && $section['page']['one'] == $value['slug'] ? 'active' : ''; ?>">
          <a href="<?php echo base_url($value['url']); ?>">
            <i class="material-icons"><?php echo $value['icon']; ?></i>
            <p><?php echo $value['name']; ?></p>
          </a>
        </li>
        <?php
      }
      ?>
    </ul>
  </div>
</div>