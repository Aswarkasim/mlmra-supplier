(function ($) {
    // form - category
    let _brand = $('form[name="form-brand"]');
    _brand.find('.js-brand-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _brand.find('input[name="action_type"]').val(_actionType);
        _brand.submit();
    });
})(jQuery);

