$('#formregister').submit(function (e) {
    e.preventDefault();

    // Obtener valores de los campos
   
    var cedula = $.trim($("#Cedula").val()); // Obtener el valor de Cedula
    var username = $.trim($("#username").val());
    var password = $.trim($("#password").val());
    var correo = $.trim($("#correo").val());

    // Verificar si los campos están vacíos
    if (cedula.length === 0 || username.length === 0 || password.length === 0 || correo.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Todos los campos son obligatorios',
        });
        return false;
    }

    // Enviar datos al servidor
    $.ajax({
        url: "app/db/registro.php", // Ruta del archivo PHP que procesa el registro
        type: "POST",
        dataType: "json",
        data: { 
 // Enviar nacionalidad como un campo separado
            Cedula: cedula,
            username: username, 
            password: password,
            correo: correo 
        },
        success: function (data) {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registro exitoso',
                    text: "Ya puedes iniciar sesión",
                    confirmButtonText: "Ingresar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index.php";
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: data.message,
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error en la comunicación con el servidor',
                text: 'Por favor, intente de nuevo más tarde.'
            });
        }
    });
});
