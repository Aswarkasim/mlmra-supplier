(function ($) {
    // form - category
    let _formNotification = $('form[name="form-notification"]');
    _formNotification.find('.js-notification-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formNotification.find('input[name="action_type"]').val(_actionType);
        _formNotification.submit();
    });
})(jQuery);

