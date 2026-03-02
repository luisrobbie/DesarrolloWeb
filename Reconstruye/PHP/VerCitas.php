<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <script src="../javascript/ventanas.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(234, 238, 244);
            padding: 20px;
        }
        .titulo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            color:rgb(0, 40, 101);
        }
        .citas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .cita-card {
            background-color: rgb(159, 192, 221);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(90, 109, 138, 0.1);
            border-left: 5px solid #5a6d8a;
            transition: transform 0.3s ease;
        }
        .cita-card:hover{
            transform: translateY(-3px);
        }
        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            color: #5a6d8a;
            font-weight: bold;
        }
        .cita-header h3 {
            margin: 0;
            color:rgb(0, 0, 0);
        }
        .cita-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .cita-notes {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 10px;
            border-radius: 8px;
            color: #6a7b8f;
            font-style: italic;
        }
        .pendiente .cita-status {
            background-color: #fff3cd;
            color: #856404;
        }
        .confirmada .cita-status {
            background-color: #d4edda;
            color: #155724;
        }
        .cancelada .cita-status {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php
    SESSION_START();
    ?>
    <div class="titulo" style="border-radius:5px;">
        <img src="../img/Calendar_Icon.png" height="35px" style="margin-right:20px;">
        <h2>Mis Citas</h2>
    </div>
<div class="Formulario_cita">
    <?php 
    require_once("conexion.php");
    $mysql = new connection();
    $conn = $mysql->get_connection();
    $id_usuario =$_SESSION['usuario'];

    $sql = "CALL SPD_CONSULTA_CITAS(?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar: " . $conn->error);
    }else{
        //echo "Conexion Exitosa --->";
    }
        
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if (!$result) {
        //echo "Error en la consulta: " . $conn->error . "\n";
    } else {
        //echo "Número de filas: " . $result->num_rows . "\n";
        if ($result->num_rows > 0) {
            $first_row = $result->fetch_assoc();
            //print_r($first_row);
            // Volver al inicio del resultset
            $result->data_seek(0);
        }
    }

    echo '<div class="citas-grid">';
    while ($row = $result->fetch_assoc()) {
        $date = htmlspecialchars($row['FechaConsulta']);
        $psicologo = htmlspecialchars($row['NombrePsicologo']);
        $status = htmlspecialchars($row['Estatus']);
        $observaciones = htmlspecialchars($row['Observaciones'] ?? 'Sin observaciones');
        
        echo '<div class="cita-card ' . strtolower($status) . '">
                <div class="cita-header">
                    <h3>' . $psicologo . '</h3>
                    <span class="cita-status">' . $status . '</span>
                </div>
                <div class="cita-header">' . $date . '</div>
                <div class="cita-notes">' . $observaciones . '</div>
            </div>';
    }
    
    echo "</div>";
    $conn->close();

    ?>
</div>
</body>
</html>