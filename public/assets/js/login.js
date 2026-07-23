$(function () {
    var $form = $('#loginForm');
    if (!$form.length) return;

    var eyeOpen = '<path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" /><circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round" />';
    var eyeClosed = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';

    $(document).on('click', '.toggle-password', function () {
        var $input = $(this).prev('input');
        var isPassword = $input.attr('type') === 'password';
        $input.attr('type', isPassword ? 'text' : 'password');
        $(this).find('svg').html(isPassword ? eyeClosed : eyeOpen);
    });

    $form.on('submit', function (e) {
        var $login = $('#login');
        var $password = $('#password');
        var $loginError = $('#loginError');
        var $passwordError = $('#passwordError');
        var valid = true;

        $loginError.addClass('hidden').text('');
        $passwordError.addClass('hidden').text('');
        $login.removeClass('has-error');
        $password.removeClass('has-error');

        if (!$login.val().trim()) {
            $loginError.text('Please enter your email or username.').removeClass('hidden');
            $login.addClass('has-error');
            valid = false;
        }

        if (!$password.val().trim()) {
            $passwordError.text('Please enter your password.').removeClass('hidden');
            $password.addClass('has-error');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
