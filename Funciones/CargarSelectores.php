<?php
include('Conexion.php');

$query = "SELECT * FROM region";

$resultado = mysqli_query($conexion, $query);

$query = "SELECT * FROM candidato";

$resultado2 = mysqli_query($conexion, $query);

echo json_encode(array('region' => mysqli_fetch_all($resultado), 'candidato' => mysqli_fetch_all($resultado2)));

?>