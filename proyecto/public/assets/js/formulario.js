// Archivo: /public/assets/js/formulario.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    if (!form) return; // Si no hay formulario en la página, no hacer nada

    form.addEventListener("submit", function (e) {
        let valido = true;
        const campos = form.querySelectorAll("input[required]");
        const mensajes = form.querySelectorAll(".help.is-danger");

        // Borrar cualquier mensaje de error anterior para evitar duplicados
        mensajes.forEach(m => m.remove());

        campos.forEach(campo => {
            const tipo = campo.getAttribute("type");
            const nombre = campo.getAttribute("name");
            const valor = campo.value.trim();
            const divControl = campo.closest(".control");

            // Validar si el campo está vacío
            if (!valor) {
                mostrarError(divControl, "Este campo es obligatorio");
                valido = false;
            }
            // Verificar si el email es válido
            else if (tipo === "email" && !validarEmail(valor)) {
                mostrarError(divControl, "Correo electrónico inválido");
                valido = false;
            }
            // Validar que la contraseña tenga al menos 6 caracteres
            else if (tipo === "password" && valor.length < 6) {
                mostrarError(divControl, "La contraseña debe tener al menos 6 caracteres");
                valido = false;
            }
            // Evitar que los campos de nombre o apellidos tengan números u otros caracteres
            else if (["nombre", "a_paterno", "a_materno"].includes(nombre) && !validarTexto(valor)) {
                mostrarError(divControl, "Solo se permiten letras");
                valido = false;
            }
        });

        // Si hay errores, cancelar el envío del formulario
        if (!valido) {
            e.preventDefault();
        }
    });

    // Esta función crea y muestra el mensaje de error debajo del campo
    function mostrarError(control, mensaje) {
        const help = document.createElement("p");
        help.className = "help is-danger";
        help.textContent = mensaje;
        control.appendChild(help);
    }

    // Expresión regular para validar un correo bien formado
    function validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Solo aceptar letras, incluyendo tildes y la ñ
    function validarTexto(texto) {
        const re = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
        return re.test(texto);
    }
});
