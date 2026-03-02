<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <script src="../javascript/validacion.js"></script>
    <script src="../javascript/ventanas.js"></script>
</head>
<body>

<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
// Fecha mínima para el datetime-local
date_default_timezone_set('America/Mexico_City');
$ahora = date('Y-m-d\TH:i');
?>

<div class="titulo">
    <img src="../img/Calendar_Icon.png" height="35px" style="margin-right:20px;">
    <h2>Reagendar</h2>
</div>

<div class="Formulario_cita">
    <form id="Form_Cita" name="agendar" method="post" action="conexionReagendar.php">  
        <div id="FechaConsulta" class="item">
            <label>Selecciona la consulta que quieres reagendar:
                <select class="Selector" id="Selector" name="FechaConsulta" required>
                    <option value="">-- Seleccione --</option>
                    <?php
                        require_once("conexion.php");
                        $mysql = new connection();
                        $conn = $mysql->get_connection();
                        $nombre_usuario = $_SESSION['usuario'];

                        $sql = "CALL SPD_CONSULTA_CITAS_AGENDADAS(?)";
                        $stmt = $conn->prepare($sql);

                        if ($stmt) {
                            $stmt->bind_param("s", $nombre_usuario);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = htmlspecialchars($row['IdPsicologo']);
                                    $motivo = htmlspecialchars($row['IdMotivo']);
                                    $FechaConsulta = htmlspecialchars($row['FechaConsulta']);
                                    echo '<option value="' . $id . '|' . $motivo . '|' . $FechaConsulta . '">' . $FechaConsulta . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No hay consultas agendadas</option>';
                            }
                            $stmt->close();
                            $conn->close();
                        } else {
                            echo '<option value="" disabled>Error en la consulta</option>';
                        }
                    ?> 
                </select>
            </label>
        </div>

        <!-- Inputs ocultos -->
        <input type="hidden" name="IdPsicologo" id="IdPsicologo">
        <input type="hidden" name="IdMotivo" id="IdMotivo">
        <input type="hidden" name="FechaConsulta" id="FechaConsultaReal">

        <div class="item">
            <label for="CitaReagendada">Selecciona fecha y hora de la consulta:</label><br>
            <input type="datetime-local" id="CitaReagendada" name="nueva_fecha_consulta" min="<?= $ahora ?>" required>
        </div>
        
        <div class="botones">
            <input type="submit" value="Enviar" class="boton">
            <input type="reset" value="Restablecer" class="boton">
        </div>
    </form>
</div>

<script>
// Asignar valores a los inputs ocultos al seleccionar una opción
document.getElementById("Selector").addEventListener("change", function () {
    const partes = this.value.split("|");
    if (partes.length === 3) {
        document.getElementById("IdPsicologo").value = partes[0];
        document.getElementById("IdMotivo").value = partes[1];
        document.getElementById("FechaConsultaReal").value = partes[2];
    }
});
</script>

</body>
</html>