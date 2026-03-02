<?php
    SESSION_START();
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <script src="../javascript/ventanas.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/calendario2.css">
</head>
<body>
    
    <div class="titulo" style="border-radius:5px;">
        <img src="../img/Calendar_Icon.png" height="35px" style="margin-right:20px;">
        <h2>Calendario de citas</h2>
    </div>

    <?php 
    require_once("conexion.php");
    $mysql = new connection();
    $conn = $mysql->get_connection();
    $id_usuario =$_SESSION['usuario'];

    $sql = "CALL SPD_CONSULTA_CITAS(?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar: " . $conn->error);
    }
        
    $stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Almacenar todas las citas en un array para usar en JavaScript
    $appointments = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = array(
                'date' => $row['FechaConsulta'],
                'psicologo' => htmlspecialchars($row['NombrePsicologo']),
                'status' => htmlspecialchars($row['Estatus']),
                'observaciones' => htmlspecialchars($row['Observaciones'] ?? 'Sin observaciones')
            );
        }
    }
    $conn->close();
    ?>

    <div class="calendar-container">
        <div class="calendar">
            <div class="calendar-header">
                <div class="calendar-title" id="calendar-month-year">Mayo 2025</div>
                <div class="calendar-nav">
                    <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                    <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="calendar-weekdays">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
                <div>Dom</div>
            </div>
            <div class="calendar-days" id="calendar-days"></div>
        </div>

        <div class="appointments-container">
            <div class="appointments-title">Citas para <span id="selected-date">Selecciona una fecha</span></div>
            <div id="appointments-list">
                <div class="no-appointments">Selecciona una fecha con citas para ver los detalles</div>
            </div>
        </div>
    </div>

    <script>
        // Pasar las citas de PHP a JavaScript
        const appointments = <?php echo json_encode($appointments); ?>;
        
        // Variables del calendario
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Mapear los nombres de los meses
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        // Función para generar el calendario
        function generateCalendar(month, year) {
            const calendarDays = document.getElementById('calendar-days');
            calendarDays.innerHTML = '';
            
            // Actualizar el título del mes/año
            document.getElementById('calendar-month-year').textContent = `${monthNames[month]} ${year}`;
            
            // Obtener el primer día del mes y cuántos días tiene el mes
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Ajustar el primer día (Lunes=1, Domingo=0 en JavaScript, lo ajustamos para que Domingo=6)
            let startingDay = firstDay === 0 ? 6 : firstDay - 1;
            
            // Crear celdas vacías para los días del mes anterior
            for (let i = 0; i < startingDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('calendar-day', 'empty');
                calendarDays.appendChild(emptyDay);
            }
            
            // Crear celdas para cada día del mes
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = day;
                
                // Verificar si es hoy
                const today = new Date();
                if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    dayElement.classList.add('today');
                }
                
                // Formatear la fecha para comparación (YYYY-MM-DD)
                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Verificar si hay citas para este día
                const hasAppointment = appointments.some(app => {
                    const appDate = app.date.split(' ')[0];
                    return appDate === formattedDate;
                });
                
                if (hasAppointment) {
                    dayElement.classList.add('has-appointment');
                }
                
                // Agregar evento click
                dayElement.addEventListener('click', () => {
                    // Remover selección previa
                    document.querySelectorAll('.calendar-day.selected').forEach(el => {
                        el.classList.remove('selected');
                    });
                    
                    // Seleccionar este día
                    dayElement.classList.add('selected');
                    
                    // Mostrar citas para este día
                    showAppointmentsForDate(formattedDate);
                });
                
                calendarDays.appendChild(dayElement);
            }
        }
        
        // Función para mostrar las citas de una fecha específica
        function showAppointmentsForDate(date) {
            const appointmentsList = document.getElementById('appointments-list');
            const selectedDateElement = document.getElementById('selected-date');
            
            // Formatear la fecha para mostrar (DD de MMMM de YYYY)
            const dateParts = date.split('-');
            const formattedDisplayDate = `${dateParts[2]} de ${monthNames[parseInt(dateParts[1]) - 1]} de ${dateParts[0]}`;
            selectedDateElement.textContent = formattedDisplayDate;
            
            // Filtrar citas para esta fecha
            const filteredAppointments = appointments.filter(app => {
                const appDate = app.date.split(' ')[0];
                return appDate === date;
            });
            
            if (filteredAppointments.length > 0) {
                let html = '<div class="citas-grid">';
                
                filteredAppointments.forEach(app => {
                    const time = app.date.split(' ')[1].substring(0, 5); // Extraer solo HH:MM
                    
                    html += `
                        <div class="cita-card ${app.status.toLowerCase()}">
                            <div class="cita-header">
                                <h3>${app.psicologo}</h3>
                                <span class="cita-status">${app.status}</span>
                            </div>
                            <div class="cita-header">${time}</div>
                            <div class="cita-notes">${app.observaciones}</div>
                        </div>
                    `;
                });
                
                html += '</div>';
                appointmentsList.innerHTML = html;
            } else {
                appointmentsList.innerHTML = '<div class="no-appointments">No hay citas programadas para esta fecha</div>';
            }
        }
        
        // Event listeners para navegación del calendario
        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });
        
        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });
        
        // Inicializar el calendario
        generateCalendar(currentMonth, currentYear);
        
        // Seleccionar automáticamente el día actual si tiene citas
        const today = new Date();
        const formattedToday = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        
        const hasAppointmentToday = appointments.some(app => {
            const appDate = app.date.split(' ')[0];
            return appDate === formattedToday;
        });
        
        if (hasAppointmentToday) {
            // Esperar a que se renderice el calendario
            setTimeout(() => {
                const todayElement = document.querySelector('.calendar-day.today');
                if (todayElement) {
                    todayElement.click();
                }
            }, 100);
        }
    </script>
</body>
</html>