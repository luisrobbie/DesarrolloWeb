function cambiarFormulario(mostrado, ocultado) {
            document.getElementById(ocultado).classList.add("hidden"); // Oculta la parte anterior
            document.getElementById(mostrado).classList.remove("hidden"); // Muestra la nueva parte
        }