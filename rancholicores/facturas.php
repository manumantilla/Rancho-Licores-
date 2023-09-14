<?php
@include 'config.php';
// Listener para crear una nueva deuda
if (isset($_POST['abonari'])) {
    $update_precioabono = $_POST['update_abono'];
    $update_conceptor = $_POST['update_concepto'];
    $up_id = $_POST['update_id'];

    // Recupera el registro de cuentas de la base de datos
    $select_cuentas = mysqli_query($conn, "SELECT * FROM `cuentas` WHERE id = '$up_id'");
    if (mysqli_num_rows($select_cuentas) > 0) {
        $row = mysqli_fetch_assoc($select_cuentas);
        $current_preciod = $row['preciod'];
        $final = $current_preciod - $update_precioabono;
    } 
    $update_query = mysqli_query($conn, "UPDATE `cuentas` SET preciod = '$final', concepto = '$update_conceptor' WHERE id = '$up_id'");
    header('Location: facturas.php');
    exit();
}

if (isset($_POST['addcuenta'])) {
    $cuenta_name = $_POST['nombreD'];
    $cuenta_concepto = $_POST['concepto'];
    $cuenta_fecha = $_POST['fecha'];
    $cuenta_valor = $_POST['valortotal'];

    $insert_product = mysqli_query($conn, "INSERT INTO cuentas (nameC, fecha, concepto, precioD) VALUES ('$cuenta_name','$cuenta_fecha','$cuenta_concepto','$cuenta_valor')");
    // Para evitar el envío del formulario
    header('Location:facturas.php' );
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cuentas   </title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
   <link rel="stylesheet" href="style.css"> 
   <?php include 'header.php'; ?>
</head>
<body>
    <!-- HTML para crear una nueva cuenta -->
    <div>
        <form action="" method="post" class="add-new-cuenta" enctype="multipart/form-data">
            <h3 class="tittle">Agregar una nueva cuenta 💰</h3>
            <input type="text" class="box" name="nombreD" placeholder="Ingresa el nombre" required>
            <input type="text" class="box" name="concepto" placeholder="Concepto" required>
            <input type="number" class="box" name="valortotal" placeholder="Valor total" required>
            <input type="submit" value="Agregar cuenta" name="addcuenta" class="btn"> 
        </form>
    </div>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');
    .td{
        font-size: 23px;    
    }
    .add_abonos{
       background-color: rgba(118, 147, 204, 0.7);
       
       display: none; 
       position: fixed;
        right:156px;
        z-index: 1100;
        padding:2rem;
        bottom: 70px;
        justify-content: center;
        min-height: 45vh;
        width: 70%; ;
    }
    .tittle {
        font-family: 'Poppins', sans-serif;
        font-size: 34px;
    }
    .add-new-cuenta {
        font-family: 'Poppins', sans-serif;
        width: 600px;
        margin: 0 auto;
        padding: 100px;
        margin-top: -20px;
        background-color: #f2f2f2;
        border-radius: 3px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .add-new-cuenta h3 {
        text-align: center;
        margin-bottom: 20px;
    }
  
    .box {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: white;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: orange;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    
    </style>

      <section class="add_abonos">
        <?php
        @include 'config.php';

        // Check if the "edit" parameter is present in the URL
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit']; //con un GET traigo la informacion de la cuenta que quiero abonar
            $edit_query = mysqli_query($conn, "SELECT * FROM `cuentas` WHERE id = $edit_id");
            // Check if there are rows returned
            if (mysqli_num_rows($edit_query) > 0) {
                while ($fetch_abo = mysqli_fetch_assoc($edit_query)) {
                    // Display the "Add Abonos" section
                    //Ella tiene style.display ESCONDIDA y con esta linea cambiamos eso a flex para que
                    //aparezca superpuesta
                    ?><script>document.querySelector(".add_abonos").style.display = "flex";</script><?php
                    ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <a>¿Cuanto abona?</a>
                        <br>
                        <input type="hidden" name="update_id" value="<?php echo $fetch_abo['id']; ?>">
                        <br>
                        <input type="number" class="box" required name="update_abono" value="<?php echo $fetch_abo['preciod']; ?>">
                        <br>
                        <a>Concepto Modificado</a>
                        <input type="text" class="box" required name="update_concepto" value="<?php echo $fetch_abo['concepto']; ?>">
                        <br>

                        <button class="btn" name="abonari">Abonar</button>
                    </form>

                    <?php

                }

                // btn cerrar
                ?>
                <br>
                <br>
                <button class="btn-close" onclick="document.querySelector('.add_abonos').style.display = 'none';">Cerrar</button>
                <?php
            }
        }
        ?>
    </section>



    <div>
        <form action="" method="post" class="abonar4" enctype="multipart/form-data">
            <h3>Cuentas en existencia</h3>
            <table class="table">
                <thead class="thead">
                    <tr>
                        <th class="th">Nombre</th>
                        <th class="th">Fecha</th>
                        <th class="th">Concepto</th>
                        <th class="th">Precio</th>
                        <th class="th">Acción</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                <?php @include 'config.php';
                $show_cuentas = mysqli_query($conn, "SELECT * FROM cuentas");
                // Verifica si la consulta se ejecutó correctamente
                if (mysqli_num_rows($show_cuentas) > 0) {
                    while ($fetch_cuentas = mysqli_fetch_assoc($show_cuentas)) {
                        if ($fetch_cuentas['preciod'] > 0) {
                            if ($fetch_cuentas['preciod'] > 1000) {
                                echo '<tr style="background-color: beige;">';
                            } else {
                                echo '<tr>';
                            }
                            ?>
                            <td class="td"><?php echo $fetch_cuentas['nameC']; ?></td>
                            <td class="td"><?php echo $fetch_cuentas['fecha_insercion']; ?></td>
                            <td class="td"><?php echo $fetch_cuentas['concepto']; ?></td>
                            <td class="td">$ <?php echo number_format($fetch_cuentas['preciod'], 0, ',', '.'); ?></td>

                            <td class="td">
                                <a href="facturas.php?edit=<?php echo $fetch_cuentas['id']; ?>" class="option-btn"><i class="fas fa-edit"></i> Abonar</a>
                            </td> 
                        </tr>
                        <?php
                    }
                }
            }
                ?>

                </tbody>
            </table>
        </form>
    </div>
    <style>
   
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    thead {
        background-color: #f2f2f2;
    }
    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    </style>
    <!--Aqui va la ventana mas pequeña para agregar abonos a la cuenta-->
  
    <style>
 

    </style>
    <!-- custom js file link  -->
    <script src="script.js"></script>
    
</body>
</html>
