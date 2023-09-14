
<?php  @include 'config.php';

// Consulta SQL para obtener ganancias mensuales
$query = "SELECT MONTH(fecha_insercion) AS mes, SUM(ganancias) AS ganancia_mensual FROM ganancias GROUP BY mes";

$resultado = mysqli_query($conn, $query);

$ganancias_mensuales = []; //Lista para guardar el mes y la ganancia mensual
while ($fila = mysqli_fetch_assoc($resultado)) {
    $ganancias_mensuales[$fila['mes']] = $fila['ganancia_mensual'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css"> <!-- Agrega tus estilos CSS aquí -->
    <title>Ganancias </title>
</head>
<body>
    <h1>Ganancias Mensuales</h1>
    <table>
        <tr>
            <th>Mes</th>
            <th>Ganancia</th>
        </tr>
        <?php foreach ($ganancias_mensuales as $mes => $ganancia) : ?>
            <tr>
                <td class="td"><?php echo $mes; ?></td>
                <td class="td">$<?php echo number_format($ganancia,0,',','.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Ganancias Diarias</h2>
</body>
<style>
    .td{
        background-color: beige;
        border: 13px;
        border-color: black;
        padding:12px;
        margin-right: 12px;
    }
</style>
</html>
