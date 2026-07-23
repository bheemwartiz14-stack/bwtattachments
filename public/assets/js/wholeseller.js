$(function () {
    var $form = $('#wholesellerForm');
    if (!$form.length) return;

    var requiredMessage = 'This field is required.';

    var fields = [
        { id: 'wholesale_company_name', required: true, message: requiredMessage },
        { id: 'vat_number', required: true, message: requiredMessage },
        { id: 'address', required: true, message: requiredMessage },
        { id: 'postal_code', required: true, message: requiredMessage },
        { id: 'city', required: true, message: requiredMessage },
        { id: 'country', required: true, message: requiredMessage },
        { id: 'name', required: true, message: requiredMessage },
        { id: 'email', required: true, message: requiredMessage, email: true },
        { id: 'phone', required: true, message: requiredMessage, pattern: /^[0-9+\-\s()]{10,20}$/, patternMessage: requiredMessage },
        { id: 'username', required: true, message: requiredMessage },
        { id: 'commission_percentage', required: false, numeric: true, min: 0, max: 100, numericMessage: 'Must be a number between 0 and 100.' },
    ];

    var isEdit = $form.find('input[name="_method"]').length > 0;
    if (!isEdit) {
        fields.push({ id: 'password', required: true, message: requiredMessage, minlength: 8, minlengthMessage: 'The password must be at least 8 characters.' });
    }

    function showError($input, message) {
        $input.addClass('has-error');
        var $wrapper = $input.closest('[class]').filter(function () {
            return this.contains($input[0]) && $(this).find('> label').length;
        });
        if (!$wrapper.length) $wrapper = $input.closest('div');
        var $errorEl = $wrapper.find('.js-client-error');
        if (!$errorEl.length) {
            var $relative = $input.closest('.relative');
            $errorEl = $('<p class="mt-1 text-sm text-red-600 dark:text-red-400 js-client-error"></p>');
            if ($relative.length) {
                $relative.after($errorEl);
            } else {
                $wrapper.append($errorEl);
            }
        }
        $errorEl.text(message);
    }

    function clearError($input) {
        $input.removeClass('has-error');
        var $wrapper = $input.closest('div');
        $wrapper.find('.js-client-error').remove();
    }

    function clearAllErrors() {
        $form.find('.has-error').removeClass('has-error');
        $form.find('.js-client-error').remove();
    }

    $form.on('submit', function (e) {
        clearAllErrors();
        var valid = true;

        $.each(fields, function (_, field) {
            var $input = $('#' + field.id);
            if (!$input.length) return;
            var val = $input.val() ? $input.val().toString().trim() : '';

            if (field.required && !val) {
                showError($input, field.message);
                valid = false;
                return;
            }

            if (!val && !field.required) return;

            if (field.email && val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                showError($input, 'Please enter a valid email address.');
                valid = false;
                return;
            }

            if (field.pattern && val && !field.pattern.test(val)) {
                showError($input, field.patternMessage);
                valid = false;
                return;
            }

            if (field.minlength && val.length < field.minlength) {
                showError($input, field.minlengthMessage);
                valid = false;
                return;
            }

            if (field.numeric && val && isNaN(parseFloat(val))) {
                showError($input, field.numericMessage);
                valid = false;
                return;
            }

            if ((field.min !== undefined || field.max !== undefined) && val) {
                var num = parseFloat(val);
                if (isNaN(num) || (field.min !== undefined && num < field.min) || (field.max !== undefined && num > field.max)) {
                    showError($input, field.numericMessage);
                    valid = false;
                    return;
                }
            }
        });

        if (!valid) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: $form.find('.has-error').first().offset().top - 100 }, 300);
        }
    });

    $form.on('input change', '.has-error', function () {
        clearError($(this));
    });
});
