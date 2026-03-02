<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <link rel="stylesheet" href="../CSS/calendario.css">
    <script src="../javascript/ventanas.js"></script>
</head>
<body>

<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
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
        <input type="hidden" name="IdPsicologo" id="IdPsicologo">
        <input type="hidden" name="IdMotivo" id="IdMotivo">
        <input type="hidden" name="FechaConsulta" id="FechaConsultaReal">

        <div class="item">
            <label>Selecciona fecha y hora de la consulta:</label><br>
            <div class="calendar-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <button type="button" id="prev-month">←</button>
                        <h2 id="month-year">Mayo 2025</h2>
                        <button type="button" id="next-month">→</button>
                    </div>
                    <div class="calendar-grid" id="day-headers">
                        <div class="day-header">Lun</div>
                        <div class="day-header">Mar</div>
                        <div class="day-header">Mié</div>
                        <div class="day-header">Jue</div>
                        <div class="day-header">Vie</div>
                        <div class="day-header">Sáb</div>
                        <div class="day-header">Dom</div>
                    </div>
                    <div class="calendar-grid" id="days"></div>
                </div>
                
                <div class="time-selector">
                    <h3>Selecciona Hora</h3>
                    <div id="time-options"></div>
                </div>
            </div>
            <input type="hidden" id="CitaReagendada" name="nueva_fecha_consulta" required>
        </div>
        
        <div class="botones">
            <input type="submit" value="Enviar" class="boton">
            <input type="reset" value="Restablecer" class="boton">
        </div>
    </form>
</div>

<script src="../javascript/calendario2.js"></script>
<script>
document.getElementById("Selector").addEventListener("change", function () {
    const partes = this.value.split("|");
    if (partes.length === 3) {
        document.getElementById("IdPsicologo").value = partes[0];
        document.getElementById("IdMotivo").value = partes[1];
        document.getElementById("FechaConsultaReal").value = partes[2];
    }
});


document.getElementById("Form_Cita").addEventListener("submit", function(e) {
    const fechaSeleccionada = new Date(document.getElementById("CitaReagendada").value);
    const ahora = new Date();
    
    if (fechaSeleccionada < ahora) {
        e.preventDefault();
        alert("No puedes seleccionar una fecha pasada.");
    }
});
</script>

</body>
</html>