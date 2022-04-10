(function ($) {
    // form - category
    let _formCategory = $('form[name="form-category"]');
    _formCategory.find('.js-category-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formCategory.find('input[name="action_type"]').val(_actionType);
        _formCategory.submit();
    });
})(jQuery);

