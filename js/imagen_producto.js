function mostrarImagenGrande(src) {
    const modal = document.getElementById("imagenModal");
    const imagenGrande = document.getElementById("imagenGrande");

    imagenGrande.src = src;
    modal.style.display = "block";
}

function cerrarModal() {
    document.getElementById("imagenModal").style.display = "none";
}

// Cerrar el modal si se hace clic fuera de la imagen
window.onclick = function(event) {
    const modal = document.getElementById("imagenModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
