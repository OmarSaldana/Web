// Archivo: /public/assets/js/formulario.js

document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.querySelector("form");
    if (!formulario) return; // Si no hay formulario en la página, no hacer nada

    formulario.addEventListener("submit", function (evento) {
        let esValido = true;
        const camposObligatorios = formulario.querySelectorAll("input[required]");
        const mensajesError = formulario.querySelectorAll(".help.is-danger");

        // Eliminar mensajes de error anteriores para evitar duplicados
        mensajesError.forEach(mensaje => mensaje.remove());

        camposObligatorios.forEach(campo => {
            const tipo = campo.getAttribute("type");
            const nombre = campo.getAttribute("name");
            const valor = campo.value.trim();
            const contenedor = campo.closest(".control");

            // Validar si el campo está vacío
            if (!valor) {
                mostrarError(contenedor, "Este campo es obligatorio");
                esValido = false;
            }
            // Verificar si el correo electrónico es válido
            else if (tipo === "email" && !validarCorreo(valor)) {
                mostrarError(contenedor, "Correo electrónico inválido");
                esValido = false;
            }
            // Validar que la contraseña tenga al menos 6 caracteres
            else if (tipo === "password" && valor.length < 6) {
                mostrarError(contenedor, "La contraseña debe tener al menos 6 caracteres");
                esValido = false;
            }
            // Evitar que los campos de nombre o apellidos tengan números u otros caracteres
            else if (["nombre", "a_paterno", "a_materno"].includes(nombre) && !validarTexto(valor)) {
                mostrarError(contenedor, "Solo se permiten letras");
                esValido = false;
            }
        });

        // Si hay errores, cancelar el envío del formulario
        if (!esValido) {
            evento.preventDefault();
        }
    });

    // Muestra el mensaje de error debajo del campo correspondiente
    function mostrarError(contenedor, mensaje) {
        const parrafo = document.createElement("p");
        parrafo.className = "help is-danger";
        parrafo.textContent = mensaje;
        contenedor.appendChild(parrafo);
    }

    // Validar formato de correo electrónico
    function validarCorreo(correo) {
        const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return expresion.test(correo);
    }

    // Validar que el texto solo contenga letras y espacios
    function validarTexto(texto) {
        const expresion = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
        return expresion.test(texto);
    }
});
