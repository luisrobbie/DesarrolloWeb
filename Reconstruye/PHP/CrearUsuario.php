<?php
require_once("conexion.php");


$mysql = new connection();
$conexion = $mysql->get_connection();
SESSION_START();
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
    //$contraseña =$_POST['contraseña'];
    $idpregunta = intval($_POST['pregunta']);
    $respuesta = $_POST['respuesta'];
    $_SESSION['Registro_Respuesta']="";
    $_SESSION['EstadoRegistro']=0;

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
        $sql = "CALL SPD_INSERTA_PERSONA(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @pcResultado)";
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
            $_SESSION['Registro_Respuesta']= trim($row['resultado']);
            $_SESSION['EstadoRegistro']=1;
            header('Location: ../PHP/Registro.php');
        }
        else {
            throw new Exception("Error al obtener resultado: " . $conexion->error);
            header('Location: ../PHP/Registro.php');
            $_SESSION['EstadoRegistro']=1;
        }
    
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    exit();
}
else{
    $conexion->close();
    header('Location: ../Html/Registro.php');
    $_SESSION['EstadoRegistro']=0;
}

?>