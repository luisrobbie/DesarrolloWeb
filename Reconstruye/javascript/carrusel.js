document.addEventListener("DOMContentLoaded", function () { const images = document.querySelectorAll(".imagebox"); let index = 0;
    function changeImage() {
        images.forEach(img => img.style.display = "none");
        images[index].style.display = "block";
        index = (index + 1) % images.length; // Ciclo infinito
    }
    
    changeImage(); // Mostrar la primera imagen al inicio
    setInterval(changeImage, 6000); // Cambiar cada 6 segundos
    
    });
    