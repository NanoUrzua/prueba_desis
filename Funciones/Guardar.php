<?php
include('Conexion.php');

if(isset($_POST)){

    $errores = array();

    //Creación de variables
    $nombre   = $_POST['nombre'];
    $alias    = $_POST['alias'];
    $rut      = $_POST['rut'];
    $email    = $_POST['email'];
    $region   = $_POST['region'];
    $comuna   = $_POST['comuna'];
    $candidato   = $_POST['candidato'];
    $nosotros_web = $_POST['nosotros_web'];
    $nosotros_tv = $_POST['nosotros_tv'];
    $nosotros_rs = $_POST['nosotros_rs'];
    $nosotros_amigo = $_POST['nosotros_amigo'];

    //VALIDACIÓN DE NOMBRE
    if(empty($nombre)){
        $errores[] = 'El Nombre y Apellido no puede estar vacio';
    }else if(strlen($nombre) > 50){
        $errores[] = 'El Nombre y Apellido tiene un limite de 50 caracteres';
    }

    //VALIDACIÓN DE ALIAS
    if(empty($alias)){
        $errores[] = 'El Alias no puede estar vacio';
    }else if(strlen($alias) > 50){
        $errores[] = 'El Alias tiene un limite de 50 caracteres';
    }else if(strlen($alias) < 6){
        $errores[] = 'El Alias debe tener al menos 6 caracteres';
    }

    if(strlen($alias) > 5){
        $solo_numeros = preg_split('/[^0-9]+/i', $alias);
        $union_numeros = '';
        foreach ($solo_numeros as $key => $value) {
            $union_numeros = $union_numeros.''.$value;
        }

        $cantidad_numeros = (int) strlen($union_numeros);

        $alias_sin_espacios = str_replace(' ', '', $alias);

        $cantidad_letras = strlen($alias_sin_espacios) - $cantidad_numeros;

        if($cantidad_letras == 0 || $cantidad_numeros == 0){
            $errores[] = 'El Alias debe contener tanto letras como numeros';
        }
    }

    //VALIDACIÓN DE RUT

    $parteNumerica = str_replace(substr($rut, -2, 2), '', $rut);

    if (!preg_match("/^[0-9]*$/", $parteNumerica)) {
        $errores[] = 'El Rut no es valido';
    }else{
        $guionYVerificador = substr($rut, -2, 2);
        if (strlen($guionYVerificador) != 2) {
            $errores[] = 'El Rut no es valido';
        }else{
            if (!preg_match('/(^[-]{1}+[0-9kK]).{0}$/', $guionYVerificador)) {
                $errores[] = 'El Rut no es valido';
            }else{
                if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
                    $errores[] = 'El Rut no es valido';
                }else{
                    $rutV = preg_replace('/[\.\-]/i', '', $rut);
                    $dv = substr($rutV, -1);
                    $numero = substr($rutV, 0, strlen($rutV) - 1);
                    $i = 2;
                    $suma = 0;
                    foreach (array_reverse(str_split($numero)) as $v) {
                        if ($i == 8) {
                            $i = 2;
                        }
                        $suma += $v * $i;
                        ++$i;
                    }
                    $dvr = 11 - ($suma % 11);
                    if ($dvr == 11) {
                        $dvr = 0;
                    }
                    if ($dvr == 10) {
                        $dvr = 'K';
                    }
                    if ($dvr == strtoupper($dv)) {
                        // CORRECTO
                    } else {
                        $errores[] = 'El Rut no es valido';
                    }
                }

            }
    
        }
    
    }

    //VALIDACIÓN DE EMAIL

    if(empty($email)){
        $errores[] = 'El Email no puede estar vació';
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El Email no es correcto';
    }

    //VALIDACIÓN REGIÓN

    if(empty($region)){
        $errores[] = 'La Región no puede estar vacia';
    }

    //VALIDACIÓN COMUNA

    if(empty($comuna)){
        $errores[] = 'La Comuna no puede estar vacia';
    }

    //VALIDACIÓN CANDIDATO

    if(empty($candidato)){
        $errores[] = 'Debe seleccionar un Candidato';
    }

    //VALIDACIÓN COMO SE ENTERO DE NOSOTROS

    $count = 0;
    $union_nosotros = '';

    if($nosotros_web == 'true'){
        $count++;
        $union_nosotros = $union_nosotros.',Web';
    }

    if($nosotros_tv == 'true'){
        $count++;
        $union_nosotros = $union_nosotros.',TV';
    }

    if($nosotros_rs == 'true'){
        $count++;
        $union_nosotros = $union_nosotros.',Redes Sociales';
    }

    if($nosotros_amigo == 'true'){
        $count++;
        $union_nosotros = $union_nosotros.',Amigo';
    }

    if($count < 2){
        $errores[] = 'Debe seleccionar al menos 2 opciones';
    }else{
        $union_nosotros = substr($union_nosotros,1);
    }
    
    if(count($errores) == 0){

        $query2 = "SELECT * FROM votos WHERE Rut = '$rut'";
        $resultado2 = mysqli_query($conexion, $query2);
        
        if(count(mysqli_fetch_all($resultado2)) == 0){
            $query = "INSERT INTO votos (Nombre, Alias, Rut, Email, Region_id, Comuna_id, Candidato_id, Nosotros) VALUES 
            ('$nombre', '$alias', '$rut', '$email', $region, $comuna, $candidato, '$union_nosotros');";
    
            $resultado = mysqli_query($conexion, $query);
     
            if($resultado){
                echo json_encode(array('estado' => true, 'errores' => $errores));
            }else{
                echo json_encode(array('estado' => false, 'errores' => $errores));
            }
        }else{
            $errores[] = 'El Rut ingresado ya voto';
            echo json_encode(array('estado' => true, 'errores' => $errores));
        }


    }else{
        echo json_encode(array('estado' => true, 'errores' => $errores));
    }

}else{
    echo json_encode(array('estado' => false, 'errores' => $errores));
}

?>