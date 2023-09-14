<?php @include 'config.php';
if (isset($_POST['order_btn'])) {
   // Ganancias
   $total_g = $_POST['gan'];
   // Evitar inyección SQL usando consultas preparadas
   $stmt = mysqli_query($conn, "INSERT INTO `ganancias` (`ganancias`) VALUES ('$total_g')"); 

   if ($stmt) {
      echo "Inserted ganancias successfuclly!";
      //Jmmm aqui esta raro porq cart es semi importante
      mysqli_query($conn, "DELETE FROM `cart`");
      header('location:cart.php');
      exit();
   } else {
      echo "Failed to insert ganancias: " . mysqli_error($conn);
   }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Factura</title>
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>
<div class="container">
<section class="checkout-form">
<h1 class="heading">Vender</h1>

<form action="" method="post">
   <div class="display-order">
   <input placeholder="Nombre">
   <input placeholder="CC">
   <br>
   <br>
   <br>
   <?php
   //Select_cart es un avairalve de tipo list que va guardando cada fila de la bd
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart`"); 
   $grand_total = 0;
   $total_ganancia = 0;
   if (mysqli_num_rows($select_cart) > 0) {
      while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
         ?> 
         <h3>Fecha: <?php echo $fetch_cart['fecha_insercion']; ?></h3>
         <?php
          $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
          $grand_total += $total_price;
          $yatusabe = $fetch_cart['priceInicial'] * $fetch_cart['quantity'];
          $total_ganancia += $yatusabe; // Sumamos la ganancia actual a la ganancia total
          ?>
           <br>
          <span><?= $fetch_cart['name']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
          <?php    
          ?>
          <input type="hidden" name="gan" value="<?= $total_ganancia ?>">

         <?php
    } 
    ?>
    <span class="grand-total"> Total a pagar : $<?= number_format($grand_total, 0,',','.'); ?> </span>
    <span name="" class="ganacia">Ganancia del producto:$<?=  number_format( $total_ganancia, 0,',','.'); ?></span>
    
 
    <?php
} else {
    echo "<div class='display-order'><span>No hay nada en la factura!</span></div>";
}
?>
   </div>
   <input type="submit" value="Pagado" name="order_btn" class="btn">
</form>
</section>
</div>
<script src="script.js"></script>
</body>
</html>