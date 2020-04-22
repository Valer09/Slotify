$(document).ready(function () {

    console.log('prova');
    $("#hideLogin").click(function () {
        $("#loginForm").hide();
        $("#registerForm").show();
    })

    $("#hideRegister").click(function () {
        $("#loginForm").show();
        $("#registerForm").hide();
    })

});