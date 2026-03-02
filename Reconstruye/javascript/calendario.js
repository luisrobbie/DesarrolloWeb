document.addEventListener('DOMContentLoaded', function() {
    // Variables de estado
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;
    
    // Elementos del DOM
    const monthYearElement = document.getElementById('month-year');
    const daysElement = document.getElementById('days');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    const timeOptionsElement = document.getElementById('time-options');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const form = document.getElementById('Form_Cita');
    
    // Generar horas en 3 columnas (8 AM - 8 PM)
    const generateTimeOptions = () => {
        timeOptionsElement.innerHTML = '';
        const startHour = 8;   // 8 AM
        const endHour = 20;     // 8 PM (20 en formato 24h)
        const columns = 2;      // Número de columnas
        
        // Crear contenedor de columnas
        const columnsContainer = document.createElement('div');
        columnsContainer.className = 'time-columns-container';
        
        // Crear columnas
        const columnsArray = [];
        for (let i = 0; i < columns; i++) {
            const column = document.createElement('div');
            column.className = 'time-column';
            columnsArray.push(column);
            columnsContainer.appendChild(column);
        }
        
        // Distribuir horarios en columnas
        let columnIndex = 0;
        for (let hour = startHour; hour <= endHour; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                if (hour === endHour && minute > 0) break;
                
                // Formatear hora en formato AM/PM
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                const displayTime = `${displayHour}:${minute.toString().padStart(2, '0')} ${ampm}`;
                
                const timeOption = document.createElement('div');
                timeOption.className = 'time-option';
                timeOption.textContent = displayTime;
                timeOption.dataset.time = timeString;
                
                timeOption.addEventListener('click', function() {
                    document.querySelectorAll('.time-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    selectedTime = timeString;
                    updateHiddenInput();
                });
                
                columnsArray[columnIndex].appendChild(timeOption);
                columnIndex = (columnIndex + 1) % columns;
            }
        }
        
        timeOptionsElement.appendChild(columnsContainer);
    };
    
    // Resto del código permanece igual
    const renderCalendar = () => {
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        monthYearElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        
        daysElement.innerHTML = '';
        
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        
        const firstDayOfWeek = firstDay.getDay();
        const startingDay = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;
        
        for (let i = 0; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'day disabled';
            daysElement.appendChild(emptyDay);
        }
        
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            dayElement.textContent = day;
            
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            dayElement.dataset.date = date.toISOString().split('T')[0];
            
            if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                dayElement.classList.add('selected');
            }
            
            dayElement.addEventListener('click', () => {
                document.querySelectorAll('.day').forEach(d => {
                    d.classList.remove('selected');
                });
                dayElement.classList.add('selected');
                selectedDate = date;
                updateHiddenInput();
            });
            
            daysElement.appendChild(dayElement);
        }
    };
    
    const updateHiddenInput = () => {
        if (selectedDate && selectedTime) {
            const dateStr = selectedDate.toISOString().split('T')[0];
            const result = `${dateStr}T${selectedTime}`;
            fechaConsultaInput.value = result;
        }
    };
    
    prevMonthButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextMonthButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    form.addEventListener('submit', function(e) {
        if (!selectedDate || !selectedTime) {
            e.preventDefault();
            alert('Por favor, selecciona una fecha y una hora');
        }
    });
    
    form.addEventListener('reset', function() {
        selectedDate = null;
        selectedTime = null;
        document.querySelectorAll('.day.selected, .time-option.selected').forEach(el => {
            el.classList.remove('selected');
        });
        fechaConsultaInput.value = '';
        currentDate = new Date();
        renderCalendar();
        generateTimeOptions();
    });
    
    generateTimeOptions();
    renderCalendar();
});