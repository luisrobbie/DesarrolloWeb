<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>agendar</title>
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
session_start();

use function PHPSTORM_META\type;

require_once("conexion.php");
$mysql = new connection();
$conexion = $mysql->get_connection();
$nombre_usuario =$_SESSION['usuario'];
$id_psicologo = $_POST['psicologo_id'];
$motivo = $_POST['motivo'];
$fecha = $_POST['fecha_consulta'];

if($_POST['motivo']=="" || !isset($_POST['motivo'])){
    echo "<p>No ha escrito Motivo de cita</p>";
    exit;
}
    try {   
        // Llamamos al procedimiento para guardar los datos de cita del formulario en la tabla consulta
        $sql = "CALL SPD_SOLICITAR_CITA_PACIENTE(?, ?, ?, ?, @pcResultado)";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar: " . $conexion->error);
        }
        else{
            echo "<p>Estado de Cita: </p>";
        }
        // Bind de parámetros (solo 3 parámetros según tu procedimiento)
        $stmt->bind_param("siss", $nombre_usuario, $id_psicologo, $motivo, $fecha);
    
        $stmt->execute();
        $stmt->close();

        // Obtener el valor del parámetro OUT
        $resultado_query = $conexion->query("SELECT @pcResultado AS resultado");
        if ($resultado_query) {
            $row = $resultado_query->fetch_assoc();
            $resultado=trim($row['resultado']);
            if($resultado=="Se ha enviado la solicitud de cita exitosamente"){
                echo "<p class='correcto'>$resultado</p>";
            }
            else{
                echo "<p class='incorrecto'>$resultado</p>";
            }
            $row = $resultado_query->fetch_assoc();
        }

    }catch (Exception $e) {
        die("Error: " . $e->getMessage());
    };

?>

</body>
</html>