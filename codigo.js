$('#formlogin').submit(function (e) {
    e.preventDefault();
    var username = $.trim($("#username").val());
    var password = $.trim($("#password").val());

    if (username === "" || password === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Debe ingresar un usuario y contraseña',
        });
        return false;
    } else {
        $.ajax({
            url: "app/db/login.php",
            type: "POST",
            datatype: "json",
            data: { username: username, password: password },
            success: function (response) {
                const data = JSON.parse(response);

                if (!data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: data.message,
                    });
                } else {
                    // Guardar el idRol como número en localStorage
                    localStorage.setItem('idRol', parseInt(data.idRol, 10));

                    Swal.fire({
                        icon: 'success',
                        title: 'La conexión ha sido exitosa',
                        text: "Bienvenidos",
                        confirmButtonText: "Ingresar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "app/Dashboard/index.php";
                        }
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con la conexión al servidor.',
                });
            }
        });
    }
});
