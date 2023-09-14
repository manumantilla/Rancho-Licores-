<?php
@include 'config.php';
//Agregar el boton y subir un nuevo prpducto
if(isset($_POST['add_product'])){
   $p_name = $_POST['p_name'];
   $p_price = $_POST['p_price'];
   $p_image = $_FILES['p_image']['name'];
   $p_priceInicial = $_POST['p_precioInicial'];
   $p_quantity = $_POST['p_quantity'];
   $_proveedor = $_POST['p_proveedor'];
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
   //imgproductos Es donde se va a guardar las imagenes
   $p_image_folder = 'imgproductos/'.$p_image;

   $insert_query = mysqli_query($conn, "INSERT INTO `products`(name, price, quantity,priceInicial, proveedor,image) VALUES('$p_name', '$p_price', '$p_quantity','$p_priceInicial', '$_proveedor','$p_image')") ;

   if($insert_query){
      move_uploaded_file($p_image_tmp_name, $p_image_folder);
      $message[] = 'Se añadió el producto';
   }else{
      $message[] = 'Hubo un error al añadir el producto';
   }
};
//Prog para el boton borrar
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_query = mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id ") or die('query failed');
   if($delete_query){
      header('location:admin.php');
      $message[] = 'Producto ha sido eliminado';
   }else{
      header('location:admin.php');
      $message[] = 'producto no se ha eliminado';
   };
};
//Boton actualizar los datos   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_p_name = $_POST['update_p_name'];
   $update_p_price = $_POST['update_p_price'];
   $update_p_quantity = $_POST['update_p_quantity'];
   $update_p_priceInicial = $_POST['update_p_priceInicial'];
   $update_p_image = $_FILES['update_p_image']['name'];
   $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
   $update_p_image_folder = 'uploaded_img/'.$update_p_image;

   $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', quantity = '$update_p_quantity', image = '$update_p_image', priceInicial= '$update_p_priceInicial' WHERE id = '$update_p_id'");

   if($update_query){
      move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);   
      $message[] = 'product updated succesfully';
      header('location:admin.php');
   }else{
      $message[] = 'product could not be updated';
      header('location:admin.php');
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

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

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data">
   <h3>Agregar un nuevo producto a Rancho y Licores Papo</h3>
   <input type="text" name="p_name" placeholder="Nombre del producto" class="box" required>
   <input type="number" name="p_price" min="0" placeholder="Precio" class="box" required>
   <input type="number" name="p_quantity" min="1" placeholder="Cantidad" class="box" required>
   <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
   <input type="number" name="p_precioInicial" min="0" placeholder="Entra el precio mayorista"class="box" required>
   <input type="text" name="p_proveedor"placeholder="Proveedor"class="box" required>
   <input type="submit" value="Agregar un nuevo producto" name="add_product" class="btn">

</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th>Imagen</th>
         <th>Nombre</th>
         <th>Precio</th>
         <th>Cantidad</th>
         <th>Accion</th>
      </thead>

      <tbody>
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`");
            if(mysqli_num_rows($select_products) > 0){
               //$row es una variable de tipo lista que guarda todos los datos de la tabla
               while($row = mysqli_fetch_assoc($select_products)){
         ?>

         <tr>
            <td><img src="imgproductos/<?php echo $row['image']; ?>" height="100" alt=""></td>
            <!--Si ven como aqui llamo a row mi vairable tipo lista-->
            <td><?php echo $row['name']; ?></td>
            <td>$<?php echo   number_format($row['price'],0,',','.'); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            
            <td>
               <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Quieres borrar este producto?');"> <i class="fas fa-trash"></i> Borrar </a>
               <!--A cada href se le agrega el id y con el while recorre todos-->
               <a href="admin.php?edit=<?php echo $row['id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> Actualizar </a>
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>Ningun producto agregado</div>";
            };
         ?>
      </tbody>
   </table>

</section>
<!-- AQUI ESTA EL CODIGO PARA EDITAR PRECIOS CANTIDAD O Imagen-->
<section class="edit-form-container">
   <?php
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>
<!--Editar o poner una nueva cantidas-->
   <form method="post" action="" >
      <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="20  0" alt="">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
      <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
      <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
      <input type="number" min="0" class="box" required name="update_p_priceInicial" value="<?php echo $fetch_edit['priceInicial']; ?>">
      
      <input type="number" min="0" class="box" required name="update_p_quantity" value="<?php echo $fetch_edit['quantity']; ?>">
      <input type="file" class="box" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">
      <input type="submit" value="update the prodcut" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-edit" class="option-btn">
   </form>

   <?php 
            };
         };
         echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
      }  ;
   ?>

</section>

</div>
<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>