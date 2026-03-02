<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pacientes</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <link rel="stylesheet" href="../CSS/Tabla.css">
    <script src="../javascript/validacion.js"></script>
    <script src="../javascript/ventanas.js"></script>

</head>

<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

    <div class="titulo" style="margin:0%;">
        <img src="../img/Calendar_Icon.png" height="35px" style="margin-right:20px;">
        <h2>Mis Pacientes</h2>
    </div>

    <?php 
    require_once("conexion.php");
    $mysql = new connection();
    $conn = $mysql->get_connection();
    $id_psicologo = $_SESSION['usuario'];  // Assuming psychologist ID is stored in session

    // Call the stored procedure
    $sql = "CALL SPD_CONSULTA_NOMBRES_PACIENTES(?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $id_psicologo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<p style='text-align:center;'>No hay pacientes asignados</p>";
    } else {
    ?>
    
    <table class="citas-table">
        <thead>
            <tr>
                <th>ID Paciente</th>
                <th>Nombre del Paciente</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                $nombre = htmlspecialchars($row['NombrePaciente']);
                $id = htmlspecialchars($row['IdPaciente']);
                
                echo '<tr>';
                echo '<td>' . $id . '</td>';
                echo '<td>' . $nombre . '</td>';
                echo '</tr>';
                
                $counter++;
            }
            ?>
        </tbody>
    </table>
    
    <?php 
    }
    
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>