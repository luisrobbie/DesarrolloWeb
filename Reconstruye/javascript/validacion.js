document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("registroForm").addEventListener("submit", function (event) {
        event.preventDefault();
        
        let nombre = document.getElementById("nombre").value.trim();
        let usuario = document.getElementById("usuario").value.trim();
        let contraseña = document.getElementById("contraseña").value.trim();
        let correo = document.getElementById("correo").value.trim();
        let telefono = document.getElementById("telefono").value.trim();
        
        if (nombre.length < 3 || usuario.length < 3 || contraseña.length < 10 || !correo.includes("@") || telefono.length !== 10) {
            alert("Por favor, complete correctamente todos los campos.");
            return false;
        }

        alert("Registro exitoso");
        window.close();
    });
});