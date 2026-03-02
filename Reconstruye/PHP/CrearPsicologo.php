<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mensaje</title>
    
    <style>
        p {
    text-align: center;
    font-size: 32px;
    font-weight: bold;
    padding: 2vw;
    padding-bottom: 0;
    background-color: rgba(71, 71, 71, 0.28);
    border-radius: 5px;
    margin: 0;
    }

    .correcto {
    color: green;
    text-shadow: 2px 2px 4px rgba(0, 128, 0, 0.5);
    }

    .incorrecto {
    color: red;
    text-shadow: 2px 2px 4px rgba(255, 0, 0, 0.5);
    }
    </style>
</head>
<body>


<?php
require_once("conexion.php");


$mysql = new connection();
$conexion = $mysql->get_connection();
// Datos ficticios
    $genero = $_POST['genero'];
    $nombres = $_POST['Nombres'];
    $primer_apellido = $_POST['Primer_Apellido'];
    $segundo_apellido = $_POST['Segundo_Apellido'];
    $telefono = $_POST['telefono'];
    $telefono2 = $_POST['telefono2'] ?? ''; // Opcional
    $correo = $_POST['correo'];
    $fecha_nac = $_POST['fecha'];
    $contraseña = md5( $_POST['contraseña'] );
    $idpregunta = intval($_POST['pregunta']);
    $respuesta = $_POST['respuesta'];


$validar=true;
if ($genero=='N' or $genero==NULL){
    $validar=false;
}
if ($idpregunta ==0){
    $validar=false;
}

if ($validar==true){
    try {
        // Llamada al procedimiento con parámetro OUT
        $sql = "CALL SPD_INSERTA_PSICOLOGO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @pcResultado)";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar: " . $conexion->error);
        }
        else{
            //echo "Conexion Exitosa --->";
        }
        $stmt->bind_param(
            "ssssssssiss",
            $genero,
            $nombres,
            $primer_apellido,
            $segundo_apellido,
            $telefono,
            $telefono2,
            $correo,
            $fecha_nac,
            $idpregunta,
            $contraseña,
            $respuesta
        );
    
        $stmt->execute();
        $stmt->close();
    
        // Obtener el valor del parámetro OUT
        $resultado_query = $conexion->query("SELECT @pcResultado AS resultado");
        if ($resultado_query) {
            $row = $resultado_query->fetch_assoc();
            $Registro_RespuestaPsi= trim($row['resultado']);
            echo "<p> $Registro_RespuestaPsi </p>";
        }
        else {
            throw new Exception("Error al obtener resultado: " . $conexion->error);
            header('Location: ../PHP/RegistroPsicologo.php');
        }
    
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    exit();
}
else{
    $conexion->close();
    header('Location: ../PHP/RegistroPsicologo.php');
}

?>
    
</body>
</html>