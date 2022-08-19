
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
        url: 'libs/php/login.php',
        success: function (res) {
            if (res.success) {
                window.location = "stamp";
            } else {
                alert('invalid username or password. please re-enter your user information');
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
        url: 'libs/php/login.php',
        success: function (res) {
            if (res.success) {
                window.location = "login";
            } else {
                alert('invalid username or password. please re-enter your user information');
            }
        }
    });
    return false; // avoid to execute the actual submit of the form.
};

var Login = new Login();
$(function () {

    $('.form-control').keypress(function (e) {
        if (e.which === 13) {
            Login.login();
            return false;    //<----Add this line
        }
    });
    $('.btn-submit').click(function () {
        Login.login();
    });
    $('.fa-logout').click(function () {
        Login.logout();
    });

});


