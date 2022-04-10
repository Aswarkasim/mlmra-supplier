(function ($) {
    // form Product
    let _formProfile = $('form[name="form-profile"]');
    _formProfile.find('input[name="opsi_password"]').on('change', function () {
        var _this = $(this);
        if (_this.is(':checked')) {
            _formProfile.find('.js-opsi-password').removeClass('d-none');
            $( ".js-password-val").rules( "add", {
                required: true,
                messages: {
                    required: "This field is required.",
                },
            });
        } else {
            _formProfile.find('.js-opsi-password').addClass('d-none');
        }
    });
})(jQuery);

