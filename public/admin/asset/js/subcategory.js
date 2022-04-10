(function ($) {
    // form - category
    let _formSubCategory = $('form[name="form-subcategory"]');
    _formSubCategory.find('.js-subcategory-action').on('click', function () {
        console.log("tes");
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formSubCategory.find('input[name="action_type"]').val(_actionType);
        _formSubCategory.submit();
    });
})(jQuery);

