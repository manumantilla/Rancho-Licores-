<header class="header">

   <div class="flex">

      <a href="#" class="logo">Rancho y Licores Papo 💰</a>

      <nav class="navbar">
         <a href="admin.php">Agregar/editar un producto</a>
         <a href="products.php">Ver productos</a>
         <a href="facturas.php">Facturas</a>
         
      </nav>

      <?php
      
      $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
      $row_count = mysqli_num_rows($select_rows);

      ?>

      <a href="cart.php" class="cart">Factura <span><?php echo $row_count; ?></span> </a>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>