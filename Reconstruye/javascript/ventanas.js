function Ventana(url, alto, ancho) {
    let izquierda = (window.innerWidth - ancho) / 2;
    let arriba = (window.innerHeight - alto) / 2;
    window.open(url, 'nuevaVentana', `width=${ancho},height=${alto},top=${arriba},left=${izquierda}`);
}
