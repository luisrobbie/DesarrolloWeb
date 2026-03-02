// Add this script to your existing JavaScript or create a new file (e.g., MenuDinamico.js)

function MenuConsultas() {
    // Show only Agendar and Reagendar
    document.getElementById('Consultas').style.display = 'block';
    document.getElementById('Psicologos').style.display = 'none';
    document.getElementById('ListaMenu').style.display = 'block';
    document.getElementById('Admin').style.display = 'none';
    document.getElementById('Menu').style.width = '30%';
    document.getElementById('Info').style.width = '70%';
}

function MenuPsicologos() {
    // Show only Consultar and Insertar
    document.getElementById('Consultas').style.display = 'none';
    document.getElementById('Psicologos').style.display = 'block';
    document.getElementById('ListaMenu').style.display = 'block';
    document.getElementById('Admin').style.display = 'none';
}

function MenuAdmin() {
    // Show only Consultar and Insertar
    document.getElementById('Consultas').style.display = 'none';
    document.getElementById('Psicologos').style.display = 'none';
    document.getElementById('ListaMenu').style.display = 'block';
    document.getElementById('Admin').style.display = 'block';
}
function MenuUnico() {
    // Hide the entire menu
    document.getElementById('ListaMenu').style.display = 'none';
}
function abrirEnIframe_Ocultar(url) {
    MenuUnico();
    let iframe = document.querySelector("iframe");
    if (iframe) {
        iframe.src = url;
    } else {
        console.error("No se encontró un iframe en la página.");
    }
}