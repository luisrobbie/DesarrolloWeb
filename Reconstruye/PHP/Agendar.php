<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <script src="../javascript/validacion.js"></script>
    <script src="../javascript/ventanas.js"></script>
    <link rel="stylesheet" href="../CSS/calendario.css">
</head>
<body>
    <?php SESSION_START(); ?>
    <div class="titulo">
        <img src="../img/Calendar2_Icon.png" height="35px" style="margin-right:20px;">
        <h2>Ingrese los datos de la cita</h2>
    </div>
    <div class="Formulario_cita">
        <form id="Form_Cita" name="agendar" method="post" action="conexionPaciente.php"> 
            <div class="item">
                <label for="motivo-cita">¿Cuál es el motivo por el que solicita la cita?</label>
                <select class="Selector" id="motivo" name="motivo">
                    <option value="0" disabled selected>Seleccione una opción</option>
                    <option value="1">Dificultades de aprendizaje</option>
                    <option value="3">Dificultades de adaptación</option>
                    <option value="4">Problemas de conducta infanto-juveniles</option>
                    <option value="5">Alteraciones en el estado de ánimo</option>
                    <option value="6">Alteraciones relacionadas con el consumo de sustancias</option>
                    <option value="7">Alteraciones en la conducta alimentaria</option>
                    <option value="8">Alteraciones en la conducta sexual</option>
                    <option value="9">Alteraciones en el control de impulsos</option>
                    <option value="10">Orientación vocacional</option>
                    <option value="11">Psicodiagnóstico clínico</option>
                    <option value="12">Atención a tx. mentales diagnósticados</option>
                    <option value="13">Otro</option>
                </select>
            </div>
            
            <div class="item">
                <label>Selecciona fecha y hora:</label><br>
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
                <input type="hidden" id="fecha_consulta" name="fecha_consulta" required>
            </div>
            
            <div id="psicologo" class="item">
                <label>Selecciona al psicólogo:
                    <select class="Selector" id="Selector" name="psicologo_id" required>
                        <option value="">-- Seleccione --</option>
                        <?php
                            $conn = new mysqli("reconstruye.ddns.net","IvanLoco","S0yBienL0c0","DB_RECONSTRUYE",3308);
                            $sql = "CALL SPD_CONSULTA_NOMBRES_PSICOLOGOS()";
                            $result = $conn->query($sql); 
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['IdPsicologo'];
                                    $nombre = $row['NombrePsicologo'];
                                    echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($nombre) . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No hay psicólogos disponibles</option>';
                            }
                            $conn->close();
                        ?> 
                    </select>
                </label>
            </div>
            <div class="botones">
                <input type="submit" value="Enviar" class="boton">
                <input type="reset" value="Restablecer" class="boton">
            </div>
        </form>
    </div>

    <script src="../javascript/calendario.js"></script>                        
</body>
</html>