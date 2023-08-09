<?php
include('Conexion.php');

if(isset($_POST)){
    $query = "SELECT Comuna_id, Nombre FROM comuna WHERE Region_id = ".$_POST['id']."";

    $resultado = mysqli_query($conexion, $query);

    echo json_encode(mysqli_fetch_all($resultado));
}

?>