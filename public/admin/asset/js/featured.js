(function ($) {
    // form Product
    let _formFeatured = $('form[name="form-featured"]');
    _formFeatured.find('.js-featured-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formFeatured.find('input[name="action_type"]').val(_actionType);
        _formFeatured.submit();
    });

    let _formFeaturedBanner = $('form[name="form-featured-banner"]');
    _formFeaturedBanner.find('.js-featured-banner-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formFeaturedBanner.find('input[name="action_type"]').val(_actionType);
        _formFeaturedBanner.submit();
    });
})(jQuery);

