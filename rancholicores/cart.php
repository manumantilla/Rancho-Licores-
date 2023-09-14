<?php

@include 'config.php';
//Este es el listener cuando se quiera actualizar algun dato del product
if(isset($_POST['update_update_btn']) ){
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];
   $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
   if($update_quantity_query){
      header('location:cart.php');//Esto sirve para que paren los envios POST 
   };
};

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
   header('location:cart.php');
};

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart`");
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="shopping-cart">

   <h1 class="heading">F A C T U R A</h1>

   <table>

      <thead>
         <th>image</th>
         <th>Nombre</th>
         <th>Precio</th>
         <th>Cantidad</th>
         <th>Precio Total</th>
         <th>Accion</th>
      </thead>

      <tbody>

         <?php 
         //Estos comandos es para buscar todos los datos de mysql
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
         $grand_total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
         ?>
         <tr>
            <!--SUBIR IMAGENES-->
            <td><img src="imgproductos/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td>$<?php echo number_format($fetch_cart['price']); ?></td>
            <td>
               <form action="" method="post"><!--Todos los form tiene que tener method POST para enviar señaes-->
                  <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['id']; ?>" >
                  <input type="number" name="update_quantity" min="1"  value="<?php echo $fetch_cart['quantity']; ?>" >
                  <input type="submit" value="Actualizar" name="update_update_btn">
               </form>   
            </td>
            <!--Aqui se calcula cuanto tiene q pagar  -->
            <td>$<?php $sub_total = 0; $sub_total = $fetch_cart['price'] * $fetch_cart['quantity']; echo number_format($sub_total,0,',','.');?></td>
            <!--Esta e sla manera de desplegar una section -->
            <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> Eliminar</a></td>

         </tr>
         <?php
           $grand_total += $sub_total;  
            };
         };
         ?>
         <tr class="table-bottom">
            <!--Este boton devuelve a la pagina de productos -->
            <td><a href="products.php" class="option-btn" style="margin-top: 0;">Seguir comprando</a></td>
            <td colspan="3">Total a pagar</td>
            <td>$<?php echo number_format($grand_total,0,',','.'); ?></td>
            <td><a href="cart.php?delete_all" onclick="return confirm('Quieres borrar todo?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
         </tr>

      </tbody>

   </table>

   <div class="checkout-btn">
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Pagar</a>
   </div>

</section>

</div>
   
<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>