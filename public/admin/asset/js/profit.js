(function ($) {
    // form - profit
    let _formProfit = $('form[name="form-profit"]');
    _formProfit.find('.js-profit-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formProfit.find('input[name="action_type"]').val(_actionType);
        _formProfit.submit();
    });
})(jQuery);

