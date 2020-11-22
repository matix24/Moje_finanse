$(document).ready(function () {
    $('form[name=context]').validate({
        lang: 'pl',
        rules: {
            'context[name]': {
                required: true,
                minlength: 3,
                maxlength: 32
            },
            'context[description]': {
                required: false,
                maxlength: 255
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});