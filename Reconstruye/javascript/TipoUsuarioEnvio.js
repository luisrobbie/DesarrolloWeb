function enviarDatos(url, alto, ancho, method, action) {
    event.preventDefault(); // Evita la recarga de la página

    const formData = new FormData(document.getElementById("tipoUsuario"));

    fetch(action, {
        method: method,
        body: formData
    }).then(response => response.text()).then(data => {
        let izquierda = (window.innerWidth - ancho) / 2;
        let arriba = (window.innerHeight - alto) / 2;
        let nuevaVentana = window.open(url, 'nuevaVentana', `width=${ancho},height=${alto},top=${arriba},left=${izquierda}`);

        if (nuevaVentana) {
            setTimeout(() => window.close(), 2000); // Cierra la ventana actual después de 2 segundos
        }
    }).catch(error => console.error("Error al enviar datos:", error));
}
