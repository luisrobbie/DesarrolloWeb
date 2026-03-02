function abrirEnIframe(url) {
    let iframe = document.querySelector("iframe");
    if (iframe) {
        iframe.src = url;
    } else {
        console.error("No se encontró un iframe en la página.");
    }
}
