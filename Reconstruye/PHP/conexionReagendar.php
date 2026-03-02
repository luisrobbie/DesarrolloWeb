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
session_start();

// Validación de sesión y datos POST
if (
    !isset($_SESSION['usuario']) ||
    !isset($_POST['IdPsicologo']) ||
    !isset($_POST['FechaConsulta']) ||
    !isset($_POST['nueva_fecha_consulta']) ||
    !isset($_POST['IdMotivo'])
) {
    die("Datos incompletos para reagendar la cita.");
}

require_once("conexion.php");

$mysql = new connection();
$conexion = $mysql->get_connection();

$nombre_usuario = $_SESSION['usuario'];
$id_psicologo = intval($_POST['IdPsicologo']);
$fecha_consulta = $_POST['FechaConsulta'];
$NuevaFechaConsulta = $_POST['nueva_fecha_consulta'];
$motivo = intval($_POST['IdMotivo']);

try {
    $sql = "CALL SPD_REAGENDAR_CITA_PACIENTE(?, ?, ?, ?, ?, @pcResultado)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("siiss", $nombre_usuario, $id_psicologo, $motivo, $fecha_consulta, $NuevaFechaConsulta);
    $stmt->execute();
    $stmt->close();

    $resultado_query = $conexion->query("SELECT @pcResultado AS resultado");

    if ($resultado_query) {
        $row = $resultado_query->fetch_assoc();
        $resultado = trim($row['resultado']);
        $resultado2= htmlspecialchars($resultado);
        echo "<p> $resultado2</p>";
    } else {
        echo "Error al obtener el resultado.";
    }

    $conexion->close();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

</body>
</html>