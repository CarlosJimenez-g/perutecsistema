//login
function validar() {
    let usu = document.login1.usuario.value;
    let pass = document.login1.password.value;

    if (usu === "grupo2@gmail.com" || usu === "profesormarcelo@gmail.com") {
        if ((usu === "grupo2@gmail.com" && pass === "12345") || (usu === "profesormarcelo@gmail.com" && pass === "nota20")) {
            alert("Bienvenido " + usu);
            location = "index.html";
        } else {
            alert(usu + " , su clave es incorrecta");
        }
    } else {
        alert(usu + "  usted no esta registrado");
    }
}
//index
function bienvenida() {
    alert("ESTAS INGRESANDO AL SISTEMA");
}

/* ------------------------------------------------------------------------------------------------------- */
