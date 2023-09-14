<?php @include 'config.php';
//Agregar al carrito/factura
if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_priceInicial = $_POST['product_priceInicial'];
   $product_id = $_POST['product_id'];
   $product_quantity = 1;

      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name'");

      if(mysqli_num_rows($select_cart) > 0){
         $message[] = 'Ya esta ese producto agregado';
      }else{
         $insert_product = mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity, id, priceInicial) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity', '$product_id','$product_priceInicial')");
         $message[] = 'Producto Agregado :)';
      }
   }
   ?>
<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Productos</title>
      <!-- font awesome cdn link  -->
         <!-- custom css file link  -->
      <link rel="stylesheet" href="sty.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   </head>
   <body>
      
   <?php

   if(isset($message)){
      foreach($message as $message){
         echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
      };
   };

   ?>

   <?php include 'header.php'; ?>
   <div class="container">
   <section class="products">
      <h1 class="heading">💰Productos en existencia💰</h1>
      <div class="box-container">

         <?php
         //Hago la conexion y llamado
         $select_products = mysqli_query($conn, "SELECT * FROM `products`");
         if(mysqli_num_rows($select_products) > 0){   
            //fetch product  guarda en un array 
            while($fetch_product = mysqli_fetch_assoc($select_products)){
         ?>
         <form action="" method="post"> 
            <div class="box">
               <!--La imag la sacamos desde la carpera imgproductos-->
               <img class="imgProductos" src="imgproductos/<?php echo $fetch_product['image']; ?>" alt="">
               <h3><?php echo $fetch_product['name']; ?></h3>
               <div class="price">$<?php echo number_format($fetch_product['price'],0,',','.'); ?></div>
               <div class="cantidad">Cantidad <?php echo $fetch_product['quantity']; ?></div>
               <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
               <input type="hidden" name="product_priceInicial" value="<?php echo $fetch_product['priceInicial']; ?>">
               <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
               <input type="submit" class="btn" value="Agregar" name="add_to_cart">
            </div>
         </form>
            <?php
               };
            };
            ?>
      </div>
</section>

</div>

<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>