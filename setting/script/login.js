/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function Login() {
}
Login.prototype.login = function () {

    var $username = $('#username');
    var $password = $('#password');

    if ($username.val() === "") {
        $username.focus();
        return;
    }
    if ($password.val() === "") {
        $password.focus();
        return;
    }
    var fd = new FormData($(".form-signin")[0]);
    fd.append('action', 'login');
    $.ajax({
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        url: 'php/login.php',
        success: function (res) {           
            if (res.success) {                
                if (res.role_key === "1") {
                    window.location = "user.php";
                }
            } else {
                alert('Access Denied');
            }
        }
    });
    return false; // avoid to execute the actual submit of the form.
};
Login.prototype.logout = function () {

    var fd = new FormData();
    fd.append('action', 'logout');
    $.ajax({
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        url: 'php/login.php',
        success: function (res) {
            if (res.success) {
                window.location = "index.php";
            } else {
                alert('Access Denied');
            }
        }
    });
    return false; // avoid to execute the actual submit of the form.
};

var Login = new Login();
$(function () {
    $('.btn-submit').click(function () {
        Login.login();
    });
    $('.fa-logout').click(function () {
        Login.logout();
    });

});


