$(document).ready(function () {
    const codeSection = $('#codeSection');
    const passwordSection = $('#passwordSection');
    const confirmBtn = $('#confirmBtn');
    let generatedCode = null;

    async function sendEmail(identificacion, code) {
        try {
            await $.ajax({
                url: 'app/db/enviar_codigo.php',
                type: 'POST',
                dataType: 'json',
                data: { cedula: identificacion, codigo: code },
                success: function (data) {
                    if (data.success) {
                        Swal.fire('Éxito', 'El código ha sido enviado a su correo.', 'success');
                        codeSection.show();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'No se pudo enviar el correo.', 'error');
                }
            });
        } catch {
            Swal.fire('Error', 'Hubo un problema al enviar el correo.', 'error');
        }
    }

    $('#sendCode').click(function () {
        const identificacion = $('#identificacion').val().trim();

        if (!identificacion) {
            Swal.fire('Error', 'Por favor, ingrese su identificación.', 'error');
            return;
        }

        generatedCode = Math.floor(100000 + Math.random() * 900000).toString();
        sendEmail(identificacion, generatedCode);
    });

    $('#codigo').on('input', function () {
        const codigo = $(this).val().trim();

        if (codigo.length === 6 && codigo === generatedCode) {
            Swal.fire('Éxito', 'El código es válido.', 'success');
            passwordSection.show();
            confirmBtn.prop('disabled', false);
        } else if (codigo.length === 6) {
            Swal.fire('Error', 'El código es incorrecto.', 'error');
        }
    });

    $('#formRecovery').submit(function (e) {
        e.preventDefault();
        const identificacion = $('#identificacion').val().trim();
        const password = $('#password').val().trim();

        if (!password) {
            Swal.fire('Error', 'Por favor, ingrese una nueva contraseña.', 'error');
            return;
        }

        $.ajax({
            url: 'app/db/cambiar_contraseña.php',
            type: 'POST',
            dataType: 'json',
            data: { cedula: identificacion, password },
            success: function (data) {
                if (data.success) {
                    Swal.fire('Éxito', 'La contraseña ha sido cambiada correctamente.', 'success');
                    window.location.href = 'index.php';
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Hubo un problema al cambiar la contraseña.', 'error');
            }
        });
    });
});